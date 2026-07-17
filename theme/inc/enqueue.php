<?php
/**
 * Assets — design-system tokens (as-is) + supplemental theme CSS/JS.
 *
 * The whole first-party CSS payload (~50 KB raw, ~10 KB gzipped as part of the
 * HTML) is inlined into <head>, so the initial render blocks on zero CSS
 * requests. The DS files on disk stay byte-identical — they are read and
 * concatenated at runtime, so a DS re-sync is still a clean diff. Google Fonts
 * (display=swap) loads async: text paints immediately in the fallback font and
 * swaps when the webfonts arrive.
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function now_enqueue_assets() {
	// 1) All first-party CSS, inlined (DS tokens → patterns → now.css →
	//    style.css, same cascade order as before).
	$css = now_inline_css();
	if ( '' !== $css ) {
		wp_register_style( 'now-inline', false, array(), NOW_VERSION );
		wp_enqueue_style( 'now-inline' );
		wp_add_inline_style( 'now-inline', $css );
	} else {
		// Unreadable files (should never happen) — fall back to plain links.
		wp_enqueue_style( 'now-ds', get_theme_file_uri( NOW_DS_DIR . '/styles.css' ), array(), NOW_VERSION );
		wp_enqueue_style( 'now-theme', get_theme_file_uri( 'assets/css/now.css' ), array( 'now-ds' ), NOW_VERSION );
		wp_enqueue_style( 'now-style', get_stylesheet_uri(), array( 'now-theme' ), NOW_VERSION );
	}

	// 2) Progressive article enhancements (TOC scrollspy, share, rail arrows).
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
 * Every stylesheet the DS manifest (_ds/now/styles.css) @imports, in manifest
 * order, followed by the theme's own sheets.
 *
 * @return string[] Absolute file paths.
 */
function now_css_files() {
	$ds_path  = get_theme_file_path( NOW_DS_DIR );
	$manifest = is_readable( $ds_path . '/styles.css' ) ? (string) file_get_contents( $ds_path . '/styles.css' ) : '';
	preg_match_all( '/@import\s+url\(\s*["\']?([^"\')]+)["\']?\s*\)/i', $manifest, $m );

	$files = array();
	foreach ( $m[1] as $import ) {
		if ( preg_match( '#^https?://#i', $import ) ) {
			continue;
		}
		$file = $ds_path . '/' . preg_replace( '#^\./#', '', $import );
		if ( is_readable( $file ) ) {
			$files[] = $file;
		}
	}
	$files[] = get_theme_file_path( 'assets/css/now.css' );
	$files[] = get_theme_file_path( 'style.css' );
	return $files;
}

/**
 * Concatenate the DS + theme CSS for inlining: external @imports (Google
 * Fonts) are stripped — they load async via now_print_font_links() — and
 * comments dropped to keep the HTML payload small. Source files untouched.
 *
 * @return string CSS, '' if nothing was readable.
 */
function now_inline_css() {
	$css = '';
	foreach ( now_css_files() as $file ) {
		if ( ! is_readable( $file ) ) {
			continue;
		}
		$sheet = (string) file_get_contents( $file );
		$sheet = preg_replace( '/@import\s+url\(\s*["\']?https?:[^"\')]+["\']?\s*\)\s*;?/i', '', $sheet );
		$sheet = preg_replace( '#/\*.*?\*/#s', '', $sheet );
		$css  .= trim( $sheet ) . "\n";
	}
	return trim( $css );
}

/**
 * External stylesheet URLs the DS pulls in via nested @import — in practice
 * the Google Fonts css2 request inside tokens/fonts.css.
 *
 * @return string[]
 */
function now_font_css_urls() {
	$urls = array();
	foreach ( now_css_files() as $file ) {
		if ( ! is_readable( $file ) ) {
			continue;
		}
		if ( preg_match_all( '/@import\s+url\(\s*["\']?(https?:[^"\')]+)["\']?\s*\)/i', (string) file_get_contents( $file ), $m ) ) {
			$urls = array_merge( $urls, $m[1] );
		}
	}
	return array_unique( $urls );
}

/**
 * Async-load the Google Fonts CSS: preload swapped to a stylesheet onload, so
 * it never blocks first paint (fonts.css uses display=swap — text renders in
 * the fallback font and swaps in). <noscript> keeps a blocking fallback.
 */
function now_print_font_links() {
	foreach ( now_font_css_urls() as $url ) {
		printf(
			'<link rel="preload" as="style" href="%1$s" onload="this.onload=null;this.rel=\'stylesheet\'"/><noscript><link rel="stylesheet" href="%1$s"/></noscript>' . "\n",
			esc_url( $url )
		);
	}
}
add_action( 'wp_head', 'now_print_font_links', 4 );

// Preconnect to Google Fonts (the font CSS + woff2 files load from there).
function now_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = 'https://fonts.googleapis.com';
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'now_resource_hints', 10, 2 );
