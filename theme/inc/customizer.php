<?php
/**
 * Customizer — the theme's editable basics (Appearance → Customize → NOW).
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defaults mirror the approved design so the site reads identically until a
 * value is changed in the Customizer.
 */
function now_theme_defaults() {
	static $defaults = null;
	if ( null !== $defaults ) {
		return $defaults;
	}
	$defaults = array(
		'now_cta_label'      => __( 'Explore platform', 'now-blog' ),
		'now_cta_url'        => 'https://nowplix.com/about/contact',
		'now_footer_tagline' => __( 'Signals from the future of iGaming — product, design and engineering stories from the NowPlix platform.', 'now-blog' ),
		'now_promo_title'    => __( 'Play on NowPlix', 'now-blog' ),
		'now_promo_text'     => __( 'Casino, sportsbook and the tech behind them. See what the platform can do.', 'now-blog' ),
		'now_promo_button'   => __( 'Explore the platform', 'now-blog' ),
		'now_promo_url'      => 'https://nowplix.com/about/contact',
		'now_inline_related' => true,
		'now_inline_related_interval' => 300,
		'now_inline_related_max' => 2,
	);
	return $defaults;
}

/**
 * get_theme_mod with the design defaults baked in.
 */
function now_mod( $key ) {
	$defaults = now_theme_defaults();
	return get_theme_mod( $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

function now_customize_register( $wp_customize ) {
	$d = now_theme_defaults();

	$wp_customize->add_section(
		'now_theme',
		array(
			'title'    => __( 'NOW theme', 'now-blog' ),
			'priority' => 30,
		)
	);

	$fields = array(
		'now_cta_label'      => array( __( 'Header CTA — label', 'now-blog' ), 'text', 'sanitize_text_field' ),
		'now_cta_url'        => array( __( 'Header CTA — URL', 'now-blog' ), 'url', 'esc_url_raw' ),
		'now_footer_tagline' => array( __( 'Footer tagline', 'now-blog' ), 'textarea', 'sanitize_textarea_field' ),
		'now_promo_title'    => array( __( 'Article sidebar promo — title', 'now-blog' ), 'text', 'sanitize_text_field' ),
		'now_promo_text'     => array( __( 'Article sidebar promo — text', 'now-blog' ), 'textarea', 'sanitize_textarea_field' ),
		'now_promo_button'   => array( __( 'Article sidebar promo — button label', 'now-blog' ), 'text', 'sanitize_text_field' ),
		'now_promo_url'      => array( __( 'Article sidebar promo — button URL', 'now-blog' ), 'url', 'esc_url_raw' ),
		'now_inline_related' => array( __( 'Weave "Keep reading" story cards into long articles', 'now-blog' ), 'checkbox', 'rest_sanitize_boolean' ),
		'now_inline_related_interval' => array( __( '"Keep reading" — words between inserts', 'now-blog' ), 'number', 'absint', array( 'min' => 100, 'max' => 2000, 'step' => 50 ) ),
		'now_inline_related_max' => array( __( '"Keep reading" — max inserts per article', 'now-blog' ), 'number', 'absint', array( 'min' => 1, 'max' => 5 ) ),
	);

	foreach ( $fields as $key => $field ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => $d[ $key ],
				'sanitize_callback' => $field[2],
			)
		);
		$control = array(
			'label'   => $field[0],
			'section' => 'now_theme',
			'type'    => $field[1],
		);
		if ( isset( $field[3] ) ) {
			$control['input_attrs'] = $field[3];
		}
		$wp_customize->add_control( $key, $control );
	}
}
add_action( 'customize_register', 'now_customize_register' );
