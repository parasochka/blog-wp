<?php
/**
 * NOW block theme — functions and setup.
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOW_BLOG_VERSION', wp_get_theme()->get( 'Version' ) );

/**
 * Theme setup: supports and translation.
 */
function now_blog_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	// Site logo — the NOW wordmark. Wide lockup, so a generous default
	// width and no forced crop (see MEDIA-GUIDE.md for asset sizes).
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_editor_style( 'assets/css/theme.css' );
	load_theme_textdomain( 'now-blog', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'now_blog_setup' );

/**
 * Web fonts for the NOW design system.
 *
 * Archivo Black → headings / sub-blocks (single heavy 400 weight)
 * 42dot Sans    → body / UI content
 * JetBrains Mono → code
 *
 * Loaded from Google Fonts for zero-config rendering. To self-host for
 * privacy/perf, drop the .woff2 files in assets/fonts/, declare them via
 * `fontFace` in theme.json, and remove this enqueue.
 */
function now_blog_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Archivo+Black&family=42dot+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap';
}

function now_blog_enqueue_fonts() {
	echo "<link rel='preconnect' href='https://fonts.googleapis.com'>\n";
	echo "<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>\n";
}
add_action( 'wp_head', 'now_blog_enqueue_fonts', 1 );

/**
 * Front-end assets. Block themes render most styling from theme.json;
 * this stylesheet only carries what theme.json cannot express.
 */
function now_blog_enqueue_assets() {
	wp_enqueue_style( 'now-blog-fonts', now_blog_fonts_url(), array(), null );

	wp_enqueue_style(
		'now-blog-theme',
		get_theme_file_uri( 'assets/css/theme.css' ),
		array( 'now-blog-fonts' ),
		NOW_BLOG_VERSION
	);

	// Progressive reading enhancements (auto table-of-contents, reading
	// progress, scrollspy, share links). Dependency-free vanilla JS; the
	// article reads perfectly with it disabled, so it is purely additive.
	wp_enqueue_script(
		'now-blog-reader',
		get_theme_file_uri( 'assets/js/now.js' ),
		array(),
		NOW_BLOG_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'now_blog_enqueue_assets' );

/**
 * Load the same fonts + styles inside the block editor so the editing
 * experience matches the front end.
 */
function now_blog_editor_assets() {
	wp_enqueue_style( 'now-blog-fonts', now_blog_fonts_url(), array(), null );
}
add_action( 'enqueue_block_editor_assets', 'now_blog_editor_assets' );

/**
 * Build core/navigation-link block markup for the blog's real categories.
 *
 * Both the header main menu and the article sidebar menu are dynamic:
 * WordPress renders the site's actual categories (busiest first), so the
 * links always resolve to a live archive — never a guessed slug that 404s —
 * and the menus maintain themselves as categories come and go. The owner can
 * still curate everything from the Site Editor; this is only the fallback
 * content the theme ships so the menus are never empty on a fresh pull.
 *
 * @param int $limit Maximum number of categories to include.
 * @return string Concatenated block markup (empty string if no categories).
 */
function now_blog_category_nav_links( $limit = 8 ) {
	$categories = get_categories(
		array(
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => (int) $limit,
			'hide_empty' => true,
		)
	);

	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return '';
	}

	$markup = '';
	foreach ( $categories as $category ) {
		$link = get_category_link( $category->term_id );
		if ( is_wp_error( $link ) ) {
			continue;
		}
		$markup .= sprintf(
			'<!-- wp:navigation-link {"label":%s,"url":%s,"kind":"custom","isTopLevelLink":true} /-->',
			wp_json_encode( $category->name ),
			wp_json_encode( $link )
		);
	}

	return $markup;
}

/**
 * Build the home page's per-category story rails.
 *
 * The reference "Home" is a stack of category rows — one horizontal rail of
 * story cards per section — not a single feed. WordPress renders the site's
 * real categories (busiest first), so the home page reshapes itself as the
 * blog's taxonomy changes and never points at a guessed slug.
 *
 * @param int $limit Maximum number of category rows to render.
 * @return string Concatenated block markup (empty string if no categories).
 */
function now_blog_category_rows( $limit = 4 ) {
	$categories = get_categories(
		array(
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => (int) $limit,
			'hide_empty' => true,
		)
	);

	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return '';
	}

	$card = '<!-- wp:group {"className":"now-card","layout":{"type":"default"}} -->'
		. '<div class="wp-block-group now-card">'
		. '<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","style":{"border":{"radius":"var:custom|radius|lg"}}} /-->'
		. '<!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.08em"}},"textColor":"primary-light"} /-->'
		. '<!-- wp:post-title {"isLink":true,"style":{"typography":{"fontSize":"var:preset|font-size|large"}}} /-->'
		. '<!-- wp:post-excerpt {"moreText":"","excerptLength":18} /-->'
		. '<!-- wp:post-date {"className":"now-meta","style":{"typography":{"fontSize":"var:preset|font-size|small"}}} /-->'
		. '</div><!-- /wp:group -->';

	$markup   = '';
	$query_id = 20;

	foreach ( $categories as $category ) {
		$query_id++;
		$link  = get_category_link( $category->term_id );
		$link  = is_wp_error( $link ) ? '#' : $link;
		$blurb = trim( wp_strip_all_tags( $category->description ) );

		$head = '<!-- wp:group {"className":"now-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->'
			. '<div class="wp-block-group now-section-head">'
			. '<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->'
			. '<div class="wp-block-group">';

		if ( '' !== $blurb ) {
			$head .= '<!-- wp:paragraph {"className":"now-section-eyebrow","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.16em"}},"textColor":"primary-light","fontFamily":"heading"} -->'
				. '<p class="now-section-eyebrow has-primary-light-color has-text-color has-heading-font-family" style="font-size:var(--wp--preset--font-size--small);letter-spacing:0.16em;text-transform:uppercase">'
				. esc_html( $blurb )
				. '</p><!-- /wp:paragraph -->';
		}

		$head .= '<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"var:preset|font-size|x-large"}}} -->'
			. '<h2 class="wp-block-heading" style="font-size:var(--wp--preset--font-size--x-large)">'
			. esc_html( $category->name )
			. '</h2><!-- /wp:heading -->'
			. '</div><!-- /wp:group -->'
			. '<!-- wp:paragraph {"className":"now-view-all"} -->'
			. '<p class="now-view-all"><a href="' . esc_url( $link ) . '">' . esc_html__( 'View all', 'now-blog' ) . ' &rarr;</a></p>'
			. '<!-- /wp:paragraph -->'
			. '</div><!-- /wp:group -->';

		$query = sprintf(
			'<!-- wp:query {"queryId":%1$d,"query":{"perPage":8,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","taxQuery":{"category":[%2$d]},"inherit":false},"className":"now-row","layout":{"type":"default"}} -->'
			. '<div class="wp-block-query now-row">'
			. '<!-- wp:post-template {"layout":{"type":"default"}} -->'
			. '%3$s'
			. '<!-- /wp:post-template -->'
			. '<!-- wp:query-no-results -->'
			. '<!-- wp:paragraph {"textColor":"muted"} --><p class="has-muted-color has-text-color">'
			. esc_html__( 'No stories in this category yet.', 'now-blog' )
			. '</p><!-- /wp:paragraph -->'
			. '<!-- /wp:query-no-results -->'
			. '</div><!-- /wp:query -->',
			$query_id,
			(int) $category->term_id,
			$card
		);

		$markup .= '<!-- wp:group {"tagName":"section","align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|100"}}},"layout":{"type":"constrained"}} -->'
			. '<section class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--100)">'
			. $head
			. $query
			. '</section><!-- /wp:group -->';
	}

	return $markup;
}

/**
 * Inline Lucide-style icon (24×24, 2px stroke, currentColor) for the marketing
 * page chips. Emoji are never used as UI icons in this design system; these are
 * the sanctioned stroke set. Unknown names fall back to the brand "spark".
 *
 * @param string $name Icon name.
 * @return string Inline SVG markup.
 */
function now_blog_icon( $name ) {
	$paths = array(
		'zap'         => '<path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z"/>',
		'shield'      => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>',
		'spark'       => '<path d="M12 2c.6 4.2 1.8 5.4 6 6-4.2.6-5.4 1.8-6 6-.6-4.2-1.8-5.4-6-6 4.2-.6 5.4-1.8 6-6Z"/>',
		'users'       => '<circle cx="9" cy="7" r="4"/><path d="M2 21v-1a7 7 0 0 1 14 0v1"/><path d="M17 3.1a4 4 0 0 1 0 7.8"/><path d="M22 21v-1a7 7 0 0 0-4-6.3"/>',
		'dice'        => '<rect x="3" y="3" width="18" height="18" rx="4"/><circle cx="8.5" cy="8.5" r="1.2"/><circle cx="15.5" cy="15.5" r="1.2"/><circle cx="15.5" cy="8.5" r="1.2"/><circle cx="8.5" cy="15.5" r="1.2"/>',
		'trending-up' => '<polyline points="3 17 9 11 13 15 21 7"/><polyline points="15 7 21 7 21 13"/>',
		'wallet'      => '<path d="M3 7a2 2 0 0 1 2-2h13a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/><path d="M16 12h4"/><circle cx="16" cy="12" r="0.6"/>',
		'gift'        => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M5 12v8h14v-8"/><path d="M12 8v12"/><path d="M12 8S9 3 7 5s5 3 5 3Zm0 0s3-5 5-3-5 3-5 3Z"/>',
		'grid'        => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
	);

	$inner = isset( $paths[ $name ] ) ? $paths[ $name ] : $paths['spark'];

	return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $inner . '</svg>';
}

/**
 * Register a "NOW" block pattern category so theme patterns group together.
 */
function now_blog_register_pattern_categories() {
	register_block_pattern_category(
		'now',
		array( 'label' => __( 'NOW', 'now-blog' ) )
	);
}
add_action( 'init', 'now_blog_register_pattern_categories' );

/**
 * Register custom block styles used across the design system.
 */
function now_blog_register_block_styles() {
	register_block_style(
		'core/group',
		array(
			'name'  => 'card',
			'label' => __( 'Card', 'now-blog' ),
		)
	);
	register_block_style(
		'core/image',
		array(
			'name'  => 'rounded-lg',
			'label' => __( 'Rounded (large)', 'now-blog' ),
		)
	);
	register_block_style(
		'core/button',
		array(
			'name'  => 'ghost',
			'label' => __( 'Ghost', 'now-blog' ),
		)
	);
}
add_action( 'init', 'now_blog_register_block_styles' );
