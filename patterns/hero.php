<?php
/**
 * Title: Hero
 * Slug: now/hero
 * Categories: now, banner
 * Description: Editorial hero — eyebrow, display heading, sub, CTAs and category pills (dark).
 *
 * @package now-blog
 */
?>
<!-- wp:group {"align":"wide","className":"now-hero","style":{"spacing":{"padding":{"top":"var:preset|spacing|100","bottom":"var:preset|spacing|70"}},"blockGap":"var:preset|spacing|40"},"layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group alignwide now-hero" style="padding-top:var(--wp--preset--spacing--100);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.16em","fontStyle":"normal","fontWeight":"400"}},"textColor":"primary-light","fontFamily":"heading"} -->
	<p class="has-text-align-center has-primary-light-color has-text-color has-heading-font-family" style="font-size:var(--wp--preset--font-size--small);font-style:normal;font-weight:400;letter-spacing:0.16em;text-transform:uppercase"><?php esc_html_e( 'The NowPlix Blog', 'now-blog' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"var:preset|font-size|display","fontWeight":"400","lineHeight":"1.02","letterSpacing":"-0.02em"}},"textColor":"contrast"} -->
	<h1 class="wp-block-heading has-text-align-center has-contrast-color has-text-color" style="font-size:var(--wp--preset--font-size--display);font-weight:400;letter-spacing:-0.02em;line-height:1.02"><?php esc_html_e( 'Signals from the future of iGaming.', 'now-blog' ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|large"}},"textColor":"text"} -->
	<p class="has-text-align-center has-text-color has-text" style="font-size:var(--wp--preset--font-size--large)"><?php esc_html_e( 'Product, design and technology stories from the NowPlix platform — casino, sportsbook and everything around them.', 'now-blog' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|30"}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--30)">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#latest"><?php esc_html_e( 'Read the latest', 'now-blog' ); ?></a></div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-ghost"} -->
		<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link wp-element-button" href="/about/"><?php esc_html_e( 'About NowPlix', 'now-blog' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

	<!-- wp:categories {"showPostCounts":false,"showEmpty":false,"className":"now-category-pills aligncenter","style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}}} /-->
</div>
<!-- /wp:group -->
