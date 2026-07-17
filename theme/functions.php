<?php
/**
 * NOW — NowPlix blog. Classic PHP theme.
 *
 * The Claude Design "NOW" design system in _ds/now/tokens/*.css is the single
 * source of truth for styling. Templates reproduce the handoff screens
 * (screens/*.dc.html) verbatim; only dynamic slots become WordPress calls.
 * No design tokens are duplicated into theme.json — edit the token CSS to
 * re-theme the site.
 *
 * This file is a thin loader — the theme code lives in inc/:
 *   inc/setup.php          theme supports, menus, content width, template shim
 *   inc/enqueue.php        design-system tokens + theme CSS/JS
 *   inc/template-tags.php  display helpers (reading time, badges, logo…)
 *   inc/nav.php            header/mobile nav, pills, footer links (+ walkers)
 *   inc/customizer.php     defaults + Appearance → Customize → NOW controls
 *   inc/inline-related.php the inline "Keep reading" content inserts
 *
 * Repeated markup (story card, author card) lives in template-parts/.
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOW_VERSION', '1.4.2' ); // bump on CSS/JS changes — busts the ?ver= asset cache.
define( 'NOW_DS_DIR', '_ds/now' );

require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/nav.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/inline-related.php';
