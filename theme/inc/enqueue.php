<?php
/**
 * Assets — design-system tokens (as-is) + supplemental theme CSS/JS.
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
