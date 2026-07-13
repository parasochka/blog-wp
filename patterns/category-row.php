<?php
/**
 * Title: Category row
 * Slug: now/category-row
 * Categories: now, query
 * Description: One category as a titled row of story cards (scroll-snap rail). Duplicate it per category and pick the category in the Query Loop settings.
 *
 * @package now-blog
 */
?>
<!-- wp:group {"tagName":"section","align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|90"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--90)">
	<!-- wp:group {"className":"now-section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
	<div class="wp-block-group now-section-head">
		<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"var:preset|font-size|x-large"}}} -->
		<h2 class="wp-block-heading" style="font-size:var(--wp--preset--font-size--x-large)"><?php esc_html_e( 'Category', 'now-blog' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"now-view-all"} -->
		<p class="now-view-all"><a href="#"><?php esc_html_e( 'View all', 'now-blog' ); ?> →</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":11,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"className":"now-row","layout":{"type":"default"}} -->
	<div class="wp-block-query now-row">
		<!-- wp:post-template {"layout":{"type":"default"}} -->
			<!-- wp:group {"className":"now-card","layout":{"type":"default"}} -->
			<div class="wp-block-group now-card">
				<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","style":{"border":{"radius":"var:custom|radius|lg"}}} /-->
				<!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.06em"}},"textColor":"primary-light"} /-->
				<!-- wp:post-title {"isLink":true,"style":{"typography":{"fontSize":"var:preset|font-size|large"}}} /-->
				<!-- wp:post-date {"className":"now-meta","style":{"typography":{"fontSize":"var:preset|font-size|small"}}} /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph {"textColor":"muted"} -->
			<p class="has-muted-color has-text-color"><?php esc_html_e( 'No stories in this category yet.', 'now-blog' ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</section>
<!-- /wp:group -->
