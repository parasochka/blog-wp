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
	// 1) Design system. styles.css is a pure @import manifest — enqueueing each
	//    imported sheet directly keeps the DS files byte-identical (still the
	//    only source of truth) while flattening the render-blocking @import
	//    chain: every token sheet is discovered from the HTML and downloads in
	//    parallel instead of waiting on styles.css first.
	$ds_deps = now_enqueue_ds_sheets();

	// 2) Supplemental theme CSS (prose, hovers, body halo, responsive).
	wp_enqueue_style(
		'now-theme',
		get_theme_file_uri( 'assets/css/now.css' ),
		$ds_deps,
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

/**
 * Enqueue every stylesheet the DS manifest (_ds/now/styles.css) @imports, as
 * individual handles. Nested external @imports (Google Fonts inside
 * tokens/fonts.css) get their own earlier <link> so the font CSS starts
 * downloading immediately instead of after the token file round-trip; the
 * duplicate @import inside fonts.css then resolves from the browser cache.
 *
 * Falls back to enqueueing the manifest itself if it can't be parsed, so a
 * DS re-sync can never leave the site unstyled.
 *
 * @return string[] Handles for dependents to require.
 */
function now_enqueue_ds_sheets() {
	$ds_path  = get_theme_file_path( NOW_DS_DIR );
	$ds_uri   = get_theme_file_uri( NOW_DS_DIR );
	$manifest = is_readable( $ds_path . '/styles.css' ) ? (string) file_get_contents( $ds_path . '/styles.css' ) : '';
	$imports  = array();
	preg_match_all( '/@import\s+url\(\s*["\']?([^"\')]+)["\']?\s*\)/i', $manifest, $m );

	$handles = array();
	foreach ( $m[1] as $import ) {
		$rel = preg_replace( '#^\./#', '', $import );
		if ( ! is_readable( $ds_path . '/' . $rel ) ) {
			continue;
		}
		$handle = 'now-ds-' . sanitize_title( str_replace( array( '/', '.css' ), array( '-', '' ), $rel ) );

		// Hoist external @imports (the Google Fonts css2 request) ahead of
		// the token file that declares them.
		$sheet = (string) file_get_contents( $ds_path . '/' . $rel );
		if ( preg_match_all( '/@import\s+url\(\s*["\']?(https?:[^"\')]+)["\']?\s*\)/i', $sheet, $ext ) ) {
			foreach ( $ext[1] as $i => $url ) {
				$ext_handle = $handle . '-ext' . ( $i ? '-' . $i : '' );
				wp_enqueue_style( $ext_handle, $url, array(), null );
				$handles[] = $ext_handle;
			}
		}

		wp_enqueue_style( $handle, $ds_uri . '/' . $rel, array(), NOW_VERSION );
		$handles[] = $handle;
	}

	if ( ! $handles ) {
		wp_enqueue_style( 'now-ds', get_theme_file_uri( NOW_DS_DIR . '/styles.css' ), array(), NOW_VERSION );
		$handles[] = 'now-ds';
	}

	return $handles;
}

// Preconnect to Google Fonts (fonts.css @imports them).
function now_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = 'https://fonts.googleapis.com';
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'now_resource_hints', 10, 2 );
