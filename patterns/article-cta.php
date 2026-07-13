<?php
/**
 * Title: Article sidebar CTA
 * Slug: now/article-cta
 * Categories: now, call-to-action
 * Description: Compact brand call-to-action card for the article floating sidebar.
 *
 * @package now-blog
 */
?>
<!-- wp:group {"className":"is-style-card","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card">
	<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large"}}} -->
	<h3 class="wp-block-heading" style="font-size:var(--wp--preset--font-size--large)"><?php esc_html_e( 'Play on NowPlix', 'now-blog' ); ?></h3>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small"}},"textColor":"text"} -->
	<p class="has-text-color has-text" style="font-size:var(--wp--preset--font-size--small)"><?php esc_html_e( 'Casino, sportsbook and the tech behind them. See what the platform can do.', 'now-blog' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"accent","textColor":"base-deep","width":100,"style":{"border":{"radius":"var:custom|radius|lg"}}} -->
		<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-base-deep-color has-accent-background-color has-text-color has-background wp-element-button" href="https://nowplix.dev" style="border-radius:var(--wp--custom--radius--lg)"><?php esc_html_e( 'Explore the platform', 'now-blog' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
