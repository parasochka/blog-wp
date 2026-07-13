<?php
/**
 * Title: Newsletter CTA
 * Slug: now/newsletter-cta
 * Categories: now, call-to-action
 * Description: Full-width brand-gradient call to action with brand glow.
 *
 * @package now-blog
 */
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|60","right":"var:preset|spacing|60"}},"blockGap":"var:preset|spacing|40","border":{"radius":"var:custom|radius|2xl"},"shadow":"var:preset|shadow|glow"},"gradient":"brand","layout":{"type":"constrained","contentSize":"640px"}} -->
<div class="wp-block-group alignwide has-brand-gradient-background has-background" style="border-radius:var(--wp--custom--radius--2xl);padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--60);box-shadow:var(--wp--preset--shadow--glow)">
	<!-- wp:heading {"textAlign":"center","level":2,"textColor":"contrast"} -->
	<h2 class="wp-block-heading has-text-align-center has-contrast-color has-text-color"><?php esc_html_e( 'Never miss a signal', 'now-blog' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","textColor":"contrast"} -->
	<p class="has-text-align-center has-contrast-color has-text-color"><?php esc_html_e( 'New stories delivered to your inbox. No spam, unsubscribe anytime.', 'now-blog' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"accent","textColor":"base-deep","style":{"border":{"radius":"var:custom|radius|lg"}}} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-base-deep-color has-accent-background-color has-text-color has-background wp-element-button" style="border-radius:var(--wp--custom--radius--lg)"><?php esc_html_e( 'Subscribe', 'now-blog' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
