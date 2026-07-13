<?php
/**
 * Title: Primary navigation
 * Slug: now/primary-nav
 * Categories: now
 * Inserter: no
 * Description: Header main menu — Home plus the blog's live categories, editable in the Site Editor.
 *
 * @package now-blog
 */
?>
<!-- wp:navigation {"overlayMenu":"mobile","className":"now-primary-nav","layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|50"}}} -->
<!-- wp:home-link {"label":"<?php echo esc_attr_x( 'Home', 'menu item', 'now-blog' ); ?>"} /-->
<?php echo now_blog_category_nav_links( 6 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup with values escaped in the helper. ?>
<!-- /wp:navigation -->
