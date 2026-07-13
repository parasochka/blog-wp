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
