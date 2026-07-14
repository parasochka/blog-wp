<?php
/**
 * Display helpers — the dynamic bits of the handoff screens.
 *
 * The repeated markup itself (story card, author card) lives in
 * template-parts/; these helpers supply the data those parts render.
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Unicode-aware word count — str_word_count() only sees ASCII words, which
 * silently returns 0 for Cyrillic/Greek/CJK content.
 */
function now_word_count( $text ) {
	return (int) preg_match_all( '/[\p{L}\p{N}][\p{L}\p{N}\'’-]*/u', (string) $text );
}

/**
 * Estimated reading time, e.g. "8 min". ~200 wpm, minimum 1.
 */
function now_reading_time( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = now_word_count( wp_strip_all_tags( (string) $content ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	/* translators: %d: minutes to read. */
	return sprintf( _n( '%d min', '%d min', $minutes, 'now-blog' ), $minutes );
}

/**
 * Author "badge": monogram initials + a deterministic brand gradient,
 * matching the avatar chips in the design.
 *
 * @return array{mono:string,grad:string}
 */
function now_author_badge( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return now_user_badge( (int) get_post_field( 'post_author', $post_id ) );
}

/**
 * The same monogram badge keyed by user ID directly — for author archives and
 * author cards, where there is no post in scope.
 *
 * @return array{mono:string,grad:string}
 */
function now_user_badge( $author ) {
	$author = (int) $author;
	$name   = get_the_author_meta( 'display_name', $author );

	$mono = '';
	foreach ( preg_split( '/\s+/', trim( (string) $name ) ) as $part ) {
		if ( '' !== $part ) {
			$mono .= strtoupper( mb_substr( $part, 0, 1 ) );
		}
		if ( strlen( $mono ) >= 2 ) {
			break;
		}
	}
	$mono = $mono ? $mono : 'NP';

	$grads = array(
		'linear-gradient(135deg,#8A74FF,#5149E6)',
		'linear-gradient(135deg,#6B55F5,#0F0F40)',
		'linear-gradient(135deg,#FFC05E,#7060F0)',
		'linear-gradient(135deg,#B09BFF,#FFAC34)',
		'linear-gradient(135deg,#8A74FF,#3B34C0)',
		'linear-gradient(135deg,#7060F0,#0F0F40)',
	);

	return array(
		'mono' => $mono,
		'grad' => $grads[ $author % count( $grads ) ],
	);
}

/**
 * Gravatar <img> overlay for an author avatar chip. Layers the admin-set
 * Gravatar on top of the monogram badge: `default=blank` returns a transparent
 * image for authors with no Gravatar, so the monogram gradient shows through —
 * the design's fallback is preserved 1:1, while a real Gravatar simply appears.
 *
 * Drop the return value inside a circular chip that is `position:relative;
 * overflow:hidden` (the six badge chips across the templates). Returns '' when
 * avatars are disabled (Settings → Discussion) so the monogram stands alone.
 *
 * @param int $author User ID.
 * @param int $size   Rendered pixel size (2× is requested from Gravatar for HiDPI).
 * @return string <img> HTML, or '' if avatars are off / no URL.
 */
function now_author_avatar_img( $author, $size ) {
	$author = (int) $author;
	$size   = (int) $size;
	$url    = get_avatar_url(
		$author,
		array(
			'size'    => $size * 2,
			'default' => 'blank',
		)
	);
	if ( ! $url ) {
		return '';
	}
	return sprintf(
		'<img src="%1$s" alt="" aria-hidden="true" loading="lazy" decoding="async" width="%2$d" height="%2$d" style="position:absolute; inset:0; width:100%%; height:100%%; object-fit:cover; display:block; border-radius:inherit" />',
		esc_url( $url ),
		$size
	);
}

/**
 * A short, clamp-friendly excerpt (the card CSS clamps to 2 lines).
 */
function now_card_excerpt( $words = 24 ) {
	return wp_trim_words( get_the_excerpt(), $words, '…' );
}

/**
 * Render one editorial story card. Must be called inside the loop. The markup
 * lives in template-parts/card.php; this wrapper keeps call sites one-liners.
 */
function now_render_card( $show_excerpt = true ) {
	get_template_part( 'template-parts/card', null, array( 'show_excerpt' => (bool) $show_excerpt ) );
}

/**
 * rel="" for a menu/footer link. External hosts get nofollow + noopener +
 * noreferrer automatically; add the CSS class `follow` to a menu item in
 * Appearance → Menus to opt a link out of nofollow, or `nofollow` to force it
 * on an internal link. XFN rel values set in the admin are preserved.
 */
function now_link_rel( $url, $classes = array(), $xfn = '' ) {
	static $home = null;
	if ( null === $home ) {
		$home = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
	}
	$rel     = array_filter( array_map( 'trim', explode( ' ', (string) $xfn ) ) );
	$classes = (array) $classes;
	$host    = wp_parse_url( (string) $url, PHP_URL_HOST );

	$external = $host && 0 !== strcasecmp( $host, (string) $home );
	if ( ( $external && ! in_array( 'follow', $classes, true ) ) || in_array( 'nofollow', $classes, true ) ) {
		$rel[] = 'nofollow';
	}
	if ( $external ) {
		$rel[] = 'noopener';
		$rel[] = 'noreferrer';
	}
	return implode( ' ', array_unique( $rel ) );
}

/**
 * Author bio for the author card / author page. The wp-admin profile
 * "Biographical Info" is the source; a designed default keeps the card
 * substantive (E-E-A-T) until the profile is filled in.
 */
function now_author_bio( $author ) {
	$bio = trim( (string) get_the_author_meta( 'description', (int) $author ) );
	if ( '' !== $bio ) {
		return $bio;
	}
	return sprintf(
		/* translators: %s: author display name. */
		__( '%s writes about the iGaming industry on the NowPlix blog — launch playbooks, platform economics, market entry, compliance and player retention, drawn from work on the NowPlix platform.', 'now-blog' ),
		get_the_author_meta( 'display_name', (int) $author )
	);
}

/**
 * The article-footer author card. The markup lives in
 * template-parts/author-card.php; this wrapper keeps call sites one-liners.
 */
function now_author_card( $author = 0 ) {
	get_template_part( 'template-parts/author-card', null, array( 'author' => (int) $author ) );
}

/**
 * The glass wordmark lockup used in the header/footer: the custom logo if the
 * site owner set one, else the bundled glass PNG. $height in px.
 */
function now_logo_img( $height = 26 ) {
	$alt = get_bloginfo( 'name' );
	if ( has_custom_logo() ) {
		$id  = (int) get_theme_mod( 'custom_logo' );
		$src = wp_get_attachment_image_url( $id, 'full' );
		if ( $src ) {
			return sprintf(
				'<img src="%s" alt="%s" style="height:%dpx; width:auto; display:block"/>',
				esc_url( $src ),
				esc_attr( $alt ),
				(int) $height
			);
		}
	}
	return sprintf(
		'<img src="%s" alt="%s" style="height:%dpx; width:auto; display:block"/>',
		esc_url( get_theme_file_uri( 'assets/img/logo-now-glass.png' ) ),
		esc_attr( $alt ),
		(int) $height
	);
}

/**
 * Trim the auto-excerpt "read more" to a plain ellipsis.
 */
function now_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'now_excerpt_more' );
