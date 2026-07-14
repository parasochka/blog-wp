<?php
/**
 * Theme setup — supports, nav menu locations, content width, template shims.
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
			'primary'         => __( 'Primary (header)', 'now-blog' ),
			'footer_platform' => __( 'Footer — Platform column', 'now-blog' ),
			'footer_company'  => __( 'Footer — Company column', 'now-blog' ),
			'footer_legal'    => __( 'Footer — Legal (Terms / Privacy)', 'now-blog' ),
		)
	);

	load_theme_textdomain( 'now-blog', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'now_setup' );

if ( ! isset( $content_width ) ) {
	$content_width = 760;
}

/**
 * Back-compat: the "Full-width canvas" template used to live at the theme root
 * as page-canvas.php; it is now page-templates/canvas.php. Pages saved with the
 * old slug in _wp_page_template keep rendering full-width until they are
 * re-saved with the new template.
 */
function now_legacy_canvas_template( $template ) {
	if ( 'page-canvas.php' === get_page_template_slug() ) {
		$new = get_theme_file_path( 'page-templates/canvas.php' );
		if ( file_exists( $new ) ) {
			return $new;
		}
	}
	return $template;
}
add_filter( 'page_template', 'now_legacy_canvas_template' );
