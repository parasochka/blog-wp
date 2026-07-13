<?php
/**
 * Title: Article sidebar sections
 * Slug: now/sidebar-sections
 * Categories: now
 * Inserter: no
 * Description: Vertical categories menu for the single-article sidebar (that template only).
 *
 * @package now-blog
 */

$now_sidebar_links = now_blog_category_nav_links( 8 );

// Nothing to show on a site with no categories yet — render nothing rather
// than an empty card.
if ( '' === $now_sidebar_links ) {
	return;
}
?>
<!-- wp:group {"className":"now-sidebar-nav is-style-card","layout":{"type":"default"}} -->
<div class="wp-block-group now-sidebar-nav is-style-card">
	<!-- wp:paragraph {"className":"now-sidebar-label"} -->
	<p class="now-sidebar-label"><?php esc_html_e( 'Sections', 'now-blog' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","justifyContent":"left"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
	<?php echo $now_sidebar_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup with values escaped in the helper. ?>
	<!-- /wp:navigation -->
</div>
<!-- /wp:group -->
