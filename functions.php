<?php
/**
 * NOW — NowPlix blog. Classic PHP theme.
 *
 * The Claude Design "NOW" design system in _ds/…/tokens/*.css is the single
 * source of truth for styling. Templates reproduce the handoff screens
 * (screens/*.dc.html) verbatim; only dynamic slots become WordPress calls.
 * No design tokens are duplicated into theme.json — edit the token CSS to
 * re-theme the site.
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOW_VERSION', '1.0.0' );
define( 'NOW_DS_DIR', '_ds/nowplix-igaming-design-system-5bcffbc0-c8f0-442c-b6f3-9be1de820175' );

/* ------------------------------------------------------------------ *
 * Theme setup
 * ------------------------------------------------------------------ */
function now_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );

	// The glass "now" wordmark. A wide lockup (~3.45:1); no forced crop.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 52,
			'width'       => 180,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary (header)', 'now-blog' ),
		)
	);

	load_theme_textdomain( 'now-blog', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'now_setup' );

if ( ! isset( $content_width ) ) {
	$content_width = 760;
}

/* ------------------------------------------------------------------ *
 * Assets — design-system tokens (as-is) + supplemental theme CSS
 * ------------------------------------------------------------------ */
function now_enqueue_assets() {
	// 1) Design system: one entry point that @imports fonts + all tokens.
	//    This is the ONLY source of truth for colors / type / spacing / etc.
	wp_enqueue_style(
		'now-ds',
		get_theme_file_uri( NOW_DS_DIR . '/styles.css' ),
		array(),
		NOW_VERSION
	);

	// 2) Supplemental theme CSS (prose, hovers, body halo, responsive).
	wp_enqueue_style(
		'now-theme',
		get_theme_file_uri( 'assets/css/now.css' ),
		array( 'now-ds' ),
		NOW_VERSION
	);

	// 3) The stylesheet handle WordPress expects (header only).
	wp_enqueue_style( 'now-style', get_stylesheet_uri(), array( 'now-theme' ), NOW_VERSION );

	// 4) Progressive article enhancements (TOC scrollspy, share, rail arrows).
	wp_enqueue_script(
		'now-script',
		get_theme_file_uri( 'assets/js/now.js' ),
		array(),
		NOW_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'now_enqueue_assets' );

// Preconnect to Google Fonts (fonts.css @imports them).
function now_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = 'https://fonts.googleapis.com';
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'now_resource_hints', 10, 2 );

/* ------------------------------------------------------------------ *
 * Design helpers — reproduce the dynamic bits of the handoff screens
 * ------------------------------------------------------------------ */

/**
 * Estimated reading time, e.g. "8 min". ~200 wpm, minimum 1.
 */
function now_reading_time( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( (string) $content ) );
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
	$author  = (int) get_post_field( 'post_author', $post_id );
	$name    = get_the_author_meta( 'display_name', $author );

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
 * A short, clamp-friendly excerpt (the card CSS clamps to 2 lines).
 */
function now_card_excerpt( $words = 24 ) {
	return wp_trim_words( get_the_excerpt(), $words, '…' );
}

/**
 * Render one editorial story card (image · category · title · [excerpt] · meta).
 * Must be called inside the loop. Markup mirrors screens/*.dc.html verbatim;
 * only the dynamic slots are WordPress calls.
 */
function now_render_card( $show_excerpt = true ) {
	$pid   = get_the_ID();
	$badge = now_author_badge( $pid );
	$cats  = get_the_category( $pid );
	$cat   = ! empty( $cats ) ? $cats[0] : null;
	?>
	<article class="now-card" style="display:flex; flex-direction:column; gap:12px; scroll-snap-align:start">
		<a class="now-card-media" href="<?php the_permalink(); ?>" style="position:relative; display:block; overflow:hidden; border-radius:var(--radius-lg); background:var(--bg-page-deep); aspect-ratio:16/9">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail(
					'large',
					array(
						'loading' => 'lazy',
						'style'   => 'width:100%; height:100%; object-fit:cover; display:block',
						'alt'     => the_title_attribute( array( 'echo' => false ) ),
					)
				);
			}
			?>
		</a>
		<?php if ( $cat ) : ?>
			<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" style="font-family:var(--font-display); font-weight:400; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:var(--text-brand)"><?php echo esc_html( $cat->name ); ?></a>
		<?php endif; ?>
		<h3 style="font-family:var(--font-display); font-weight:400; font-size:19px; line-height:1.22; color:var(--text-primary); margin:0"><a class="now-card-title" href="<?php the_permalink(); ?>" style="color:inherit"><?php the_title(); ?></a></h3>
		<?php if ( $show_excerpt && get_the_excerpt() ) : ?>
			<p style="font-size:14px; color:var(--text-secondary); margin:0; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden"><?php echo esc_html( now_card_excerpt() ); ?></p>
		<?php endif; ?>
		<div style="display:flex; align-items:center; gap:12px; color:var(--text-muted); font-size:13px">
			<span style="width:22px; height:22px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:9px; background:<?php echo esc_attr( $badge['grad'] ); ?>"><?php echo esc_html( $badge['mono'] ); ?></span>
			<span><?php echo esc_html( get_the_date() ); ?></span>
			<span style="width:3px; height:3px; border-radius:50%; background:var(--text-muted)"></span>
			<span><?php echo esc_html( now_reading_time( $pid ) ); ?></span>
		</div>
	</article>
	<?php
}

/**
 * Primary navigation: a short curated menu — Home · Categories (a dropdown
 * listing every live category) · About · Platform. Kept short by design; the
 * full taxonomy hangs off the Categories dropdown. Styled via .now-nav in
 * now.css. Pages resolve by slug (about / platform) with a sensible fallback.
 */
function now_primary_nav() {
	$home_active = ( is_front_page() || is_home() ) ? ' now-active' : '';
	printf(
		'<a class="now-nav-link%s" href="%s">%s</a>',
		esc_attr( $home_active ),
		esc_url( home_url( '/' ) ),
		esc_html__( 'Home', 'now-blog' )
	);

	// Categories dropdown — every non-empty category with its blurb.
	$cats = get_categories(
		array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		)
	);
	if ( ! empty( $cats ) ) {
		$cat_active = is_category() ? ' now-active' : '';
		echo '<div class="now-nav-item">';
		printf(
			'<a class="now-nav-link now-dropdown-toggle%s" tabindex="0" role="button" aria-haspopup="true" aria-expanded="false">%s <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></a>',
			esc_attr( $cat_active ),
			esc_html__( 'Categories', 'now-blog' )
		);
		echo '<div class="now-dropdown"><div class="now-dropdown-grid">';
		foreach ( $cats as $c ) {
			$blurb = trim( wp_strip_all_tags( $c->description ) );
			printf(
				'<a class="now-dropdown-link" href="%s"><span class="now-dropdown-name">%s</span>%s</a>',
				esc_url( get_category_link( $c ) ),
				esc_html( $c->name ),
				$blurb ? '<span class="now-dropdown-blurb">' . esc_html( $blurb ) . '</span>' : ''
			);
		}
		echo '</div></div></div>';
	}

	// Pages (resolve by slug; fall back to the conventional path).
	$about    = get_page_by_path( 'about' );
	$platform = get_page_by_path( 'platform' );
	printf(
		'<a class="now-nav-link" href="%s">%s</a>',
		esc_url( $about ? get_permalink( $about ) : home_url( '/about/' ) ),
		esc_html__( 'About', 'now-blog' )
	);
	printf(
		'<a class="now-nav-link" href="%s">%s</a>',
		esc_url( $platform ? get_permalink( $platform ) : home_url( '/platform/' ) ),
		esc_html__( 'Platform', 'now-blog' )
	);
}

/**
 * Category "pills" browse row (hero + archive header). $active highlights the
 * current category. Inline styles mirror the handoff; hover via .now-pill.
 */
function now_category_pills( $active = 0 ) {
	$cats = get_categories(
		array(
			'orderby'    => 'term_id',
			'order'      => 'ASC',
			'hide_empty' => true,
		)
	);
	foreach ( $cats as $c ) {
		$is     = ( $active && (int) $active === (int) $c->term_id );
		$border = $is ? 'var(--primary-500)' : 'var(--border)';
		$bg     = $is ? 'color-mix(in srgb, var(--primary-500) 16%, var(--surface-card))' : 'var(--surface-card)';
		$color  = $is ? '#fff' : 'var(--text-secondary)';
		printf(
			'<a class="now-pill" href="%1$s" style="padding:8px 16px; border-radius:999px; border:1px solid %2$s; background:%3$s; color:%4$s; font-family:var(--font-body); font-weight:500; font-size:13px">%5$s</a>',
			esc_url( get_category_link( $c ) ),
			esc_attr( $border ),
			esc_attr( $bg ),
			esc_attr( $color ),
			esc_html( $c->name )
		);
	}
}

/**
 * Footer "Sections" column — the site's categories.
 */
function now_footer_sections() {
	$cats = get_categories(
		array(
			'orderby'    => 'term_id',
			'order'      => 'ASC',
			'hide_empty' => true,
		)
	);
	foreach ( $cats as $c ) {
		printf(
			'<a class="now-foot-link" href="%s" style="display:block; color:var(--text-secondary); font-family:var(--font-body); font-size:14px; padding:5px 0">%s</a>',
			esc_url( get_category_link( $c ) ),
			esc_html( $c->name )
		);
	}
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
