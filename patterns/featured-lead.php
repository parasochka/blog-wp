<?php
/**
 * Title: Featured lead story
 * Slug: now/featured-lead
 * Categories: now, query
 * Description: Large asymmetric lead article (image + headline) pulling the latest post.
 *
 * @package now-blog
 */
?>
<!-- wp:query {"queryId":10,"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"className":"now-featured","align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide now-featured">
	<!-- wp:post-template {"layout":{"type":"default"}} -->
		<!-- wp:group {"className":"now-featured-item","layout":{"type":"default"}} -->
		<div class="wp-block-group now-featured-item">
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2","style":{"border":{"radius":"var:custom|radius|2xl"}}} /-->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.08em"}},"textColor":"primary-light"} /-->
				<!-- wp:post-title {"isLink":true} /-->
				<!-- wp:post-excerpt {"moreText":"","excerptLength":30} /-->

				<!-- wp:group {"className":"now-meta","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
				<div class="wp-block-group now-meta">
					<!-- wp:post-author-name {"style":{"typography":{"fontSize":"var:preset|font-size|small"}}} /-->
					<!-- wp:post-date {"style":{"typography":{"fontSize":"var:preset|font-size|small"}}} /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->
</div>
<!-- /wp:query -->
