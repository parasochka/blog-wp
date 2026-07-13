<?php
/**
 * Title: Footer sections column
 * Slug: now/footer-sections
 * Categories: now
 * Inserter: no
 * Description: Footer "Sections" column — Home plus the blog's live categories.
 *
 * @package now-blog
 */
?>
<!-- wp:group {"className":"now-footer-col","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group now-footer-col">
	<!-- wp:heading {"level":4,"className":"now-footer-head"} -->
	<h4 class="wp-block-heading now-footer-head"><?php esc_html_e( 'Sections', 'now-blog' ); ?></h4>
	<!-- /wp:heading -->

	<!-- wp:navigation {"overlayMenu":"never","className":"now-footer-nav","layout":{"type":"flex","orientation":"vertical","justifyContent":"left"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}}} -->
	<!-- wp:home-link {"label":"<?php echo esc_attr_x( 'Home', 'menu item', 'now-blog' ); ?>"} /-->
	<?php echo now_blog_category_nav_links( 5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup with values escaped in the helper. ?>
	<!-- /wp:navigation -->
</div>
<!-- /wp:group -->
