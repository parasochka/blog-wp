<?php
/**
 * Inline "Keep reading" inserts — related stories woven into the prose.
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Related stories for the inline inserts: same tags first, then the latest
 * from the post's categories, then the latest site-wide — so the module
 * renders on every article even when tags don't intersect. Returns at most
 * $count posts, never the current one.
 *
 * @return WP_Post[]
 */
function now_inline_related_posts( $post_id, $count = 2 ) {
	$base = array(
		'posts_per_page'      => $count,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$posts = array();
	$tags  = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );
	if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
		$posts = get_posts( $base + array( 'tag__in' => $tags ) );
	}

	if ( count( $posts ) < $count ) {
		$cats = wp_get_post_categories( $post_id );
		if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
			$exclude = array_merge( array( $post_id ), wp_list_pluck( $posts, 'ID' ) );
			$more    = get_posts(
				array(
					'posts_per_page'      => $count - count( $posts ),
					'post__not_in'        => $exclude,
					'category__in'        => $cats,
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
				)
			);
			$posts = array_merge( $posts, $more );
		}
	}

	// Last resort: the freshest stories site-wide, so no article goes without.
	if ( count( $posts ) < $count ) {
		$exclude = array_merge( array( $post_id ), wp_list_pluck( $posts, 'ID' ) );
		$more    = get_posts(
			array(
				'posts_per_page'      => $count - count( $posts ),
				'post__not_in'        => $exclude,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		$posts = array_merge( $posts, $more );
	}

	return $posts;
}

/**
 * One inline related-story banner: square thumb · eyebrow/title/blurb · amber
 * "Read more" CTA. Compact horizontal strip with the brand ring (--elev-2),
 * borrowing the sidebar-promo look; styled via .now-inline-read in now.css.
 */
function now_inline_related_card( $rel ) {
	$link  = get_permalink( $rel );
	$thumb = get_the_post_thumbnail(
		$rel,
		'thumbnail',
		array(
			'loading' => 'lazy',
			'alt'     => get_the_title( $rel ),
		)
	);
	$blurb = wp_trim_words( get_the_excerpt( $rel ), 20, '…' );

	$html  = '<aside class="now-inline-read">';
	if ( $thumb ) {
		$html .= '<a class="now-inline-read-thumb" href="' . esc_url( $link ) . '" tabindex="-1" aria-hidden="true">' . $thumb . '</a>';
	}
	$html .= '<div class="now-inline-read-body">';
	$html .= '<span class="now-inline-read-eyebrow">' . esc_html__( 'Keep reading', 'now-blog' ) . '</span>';
	$html .= '<a class="now-inline-read-title" href="' . esc_url( $link ) . '">' . esc_html( get_the_title( $rel ) ) . '</a>';
	if ( $blurb ) {
		$html .= '<p class="now-inline-read-desc">' . esc_html( $blurb ) . '</p>';
	}
	$html .= '</div>';
	$html .= '<a class="now-inline-read-btn" href="' . esc_url( $link ) . '">' . esc_html__( 'Read more', 'now-blog' ) . '</a>';
	$html .= '</aside>';

	return $html;
}

/**
 * Weave "Keep reading" banners into long article bodies — one per N words
 * and at most M per post (both editable in the Customizer, 300 words / 2
 * inserts by default), preferably after a closed paragraph that is followed
 * by another paragraph. Sectioned articles where every paragraph is
 * immediately followed by a heading never offer such a slot, so if the
 * first pass places nothing we rerun it allowing inserts at a section
 * boundary (before an <h2>–<h6>) — still never before a list or figure.
 */
function now_inline_related( $content ) {
	if ( ! now_mod( 'now_inline_related' ) || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() || post_password_required() ) {
		return $content;
	}

	$blocks = preg_split( '/(<\/p>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( false === $blocks || count( $blocks ) < 5 ) {
		return $content;
	}

	/** Interval (words between inserts) and cap — Customizer values, still filterable. */
	$interval = max( 100, (int) apply_filters( 'now_inline_related_interval', (int) now_mod( 'now_inline_related_interval' ) ) );
	$max      = max( 1, (int) apply_filters( 'now_inline_related_max', (int) now_mod( 'now_inline_related_max' ) ) );

	$related = now_inline_related_posts( get_the_ID(), $max );
	if ( empty( $related ) ) {
		return $content;
	}

	$weave = static function ( $allow_before_heading ) use ( $blocks, $related, $interval ) {
		$out   = '';
		$words = 0;
		$used  = 0;

		foreach ( $blocks as $i => $chunk ) {
			$out .= $chunk;
			if ( $used >= count( $related ) ) {
				continue; // all cards placed — just pass the rest through.
			}
			if ( '</p>' !== strtolower( $chunk ) ) {
				$words += now_word_count( wp_strip_all_tags( $chunk ) );
				continue;
			}
			$next = isset( $blocks[ $i + 1 ] ) ? ltrim( $blocks[ $i + 1 ] ) : '';
			$slot = ( 0 === stripos( $next, '<p' ) ) // between two paragraphs
				|| ( $allow_before_heading && preg_match( '/^<h[2-6]\b/i', $next ) );
			if ( $words >= $interval && $slot ) {
				$out .= now_inline_related_card( $related[ $used ] );
				$used++;
				$words = 0;
			}
		}

		return array( $out, $used );
	};

	list( $out, $used ) = $weave( false );
	if ( 0 === $used ) {
		list( $out, $used ) = $weave( true );
	}

	return $used > 0 ? $out : $content;
}
add_filter( 'the_content', 'now_inline_related', 20 );
