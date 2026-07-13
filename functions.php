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
 * Front-end assets. Block themes render most styling from theme.json;
 * this stylesheet only carries what theme.json cannot express.
 */
function now_blog_enqueue_assets() {
	wp_enqueue_style(
		'now-blog-theme',
		get_theme_file_uri( 'assets/css/theme.css' ),
		array(),
		NOW_BLOG_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'now_blog_enqueue_assets' );

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
