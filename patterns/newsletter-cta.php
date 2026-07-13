<?php
/**
 * Title: Newsletter CTA
 * Slug: now/newsletter-cta
 * Categories: now, call-to-action
 * Description: Full-width gradient call to action for newsletter signup.
 *
 * @package now-blog
 */
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|60","right":"var:preset|spacing|60"}},"blockGap":"var:preset|spacing|40","border":{"radius":"var:custom|radius|lg"}},"gradient":"brand","layout":{"type":"constrained","contentSize":"640px"}} -->
<div class="wp-block-group alignwide has-brand-gradient-background has-background" style="border-radius:var(--wp--custom--radius--lg);padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--60)">
	<!-- wp:heading {"textAlign":"center","level":2,"textColor":"base"} -->
	<h2 class="wp-block-heading has-text-align-center has-base-color has-text-color"><?php esc_html_e( 'Never miss a post', 'now-blog' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","textColor":"base"} -->
	<p class="has-text-align-center has-base-color has-text-color"><?php esc_html_e( 'Get new stories delivered to your inbox. No spam, unsubscribe anytime.', 'now-blog' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"base","textColor":"primary","style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}}}} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-base-background-color has-text-color has-background wp-element-button"><?php esc_html_e( 'Subscribe', 'now-blog' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
