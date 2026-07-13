<?php
/**
 * Title: Hero with background image
 * Slug: now/hero-image
 * Categories: now, banner
 * Description: Full-bleed hero built on a Cover block. Drop a wide NOW illustration in as the background; the dim overlay keeps the headline readable.
 *
 * @package now-blog
 */
?>
<!-- wp:cover {"overlayColor":"base-deep","dimRatio":60,"minHeightUnit":"px","minHeight":480,"align":"full","className":"now-hero-image","style":{"spacing":{"padding":{"top":"var:preset|spacing|100","bottom":"var:preset|spacing|100"}}},"layout":{"type":"constrained","contentSize":"860px"}} -->
<div class="wp-block-cover alignfull now-hero-image" style="padding-top:var(--wp--preset--spacing--100);padding-bottom:var(--wp--preset--spacing--100);min-height:480px">
	<span aria-hidden="true" class="wp-block-cover__background has-base-deep-background-color has-background-dim-60 has-background-dim"></span>
	<div class="wp-block-cover__inner-container">
		<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.14em"}},"textColor":"primary-light","fontFamily":"heading"} -->
		<p class="has-text-align-center has-primary-light-color has-text-color has-heading-font-family" style="font-size:var(--wp--preset--font-size--small);letter-spacing:0.14em;text-transform:uppercase"><?php esc_html_e( 'The NowPlix Blog', 'now-blog' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"var:preset|font-size|display","fontWeight":"400","lineHeight":"1.04"}},"textColor":"contrast"} -->
		<h1 class="wp-block-heading has-text-align-center has-contrast-color has-text-color" style="font-size:var(--wp--preset--font-size--display);font-weight:400;line-height:1.04"><?php esc_html_e( 'Signals from the future of iGaming.', 'now-blog' ); ?></h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|large"}},"textColor":"text"} -->
		<p class="has-text-align-center has-text-color has-text" style="font-size:var(--wp--preset--font-size--large)"><?php esc_html_e( 'Product, design and technology stories from the NowPlix platform.', 'now-blog' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
		<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--30)">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#latest"><?php esc_html_e( 'Read the latest', 'now-blog' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
</div>
<!-- /wp:cover -->
