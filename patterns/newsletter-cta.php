<?php
/**
 * Title: Newsletter CTA
 * Slug: now/newsletter-cta
 * Categories: now, call-to-action
 * Description: Full-width brand-gradient call to action with brand glow and email capture.
 *
 * @package now-blog
 */
?>
<!-- wp:group {"align":"wide","className":"now-newsletter","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|60","right":"var:preset|spacing|60"}},"blockGap":"var:preset|spacing|30","border":{"radius":"var:custom|radius|2xl"},"shadow":"var:preset|shadow|glow"},"gradient":"brand","layout":{"type":"constrained","contentSize":"520px"}} -->
<div class="wp-block-group alignwide now-newsletter has-brand-gradient-background has-background" style="border-radius:var(--wp--custom--radius--2xl);padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--60);box-shadow:var(--wp--preset--shadow--glow)">
	<!-- wp:paragraph {"align":"center","className":"now-newsletter-eyebrow","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.16em","fontWeight":"400"}},"textColor":"contrast","fontFamily":"heading"} -->
	<p class="has-text-align-center now-newsletter-eyebrow has-contrast-color has-text-color has-heading-font-family" style="font-size:var(--wp--preset--font-size--small);font-weight:400;letter-spacing:0.16em;text-transform:uppercase"><?php esc_html_e( 'Newsletter', 'now-blog' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"textAlign":"center","level":2,"textColor":"contrast"} -->
	<h2 class="wp-block-heading has-text-align-center has-contrast-color has-text-color"><?php esc_html_e( 'Never miss a signal', 'now-blog' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","textColor":"contrast"} -->
	<p class="has-text-align-center has-contrast-color has-text-color"><?php esc_html_e( 'New stories delivered to your inbox. No spam, unsubscribe anytime.', 'now-blog' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:html -->
	<form class="now-newsletter-form" method="post" action="https://nowplix.dev" novalidate>
		<input type="email" name="email" required placeholder="<?php echo esc_attr__( 'you@email.com', 'now-blog' ); ?>" aria-label="<?php echo esc_attr__( 'Email address', 'now-blog' ); ?>" />
		<button type="submit"><?php esc_html_e( 'Subscribe', 'now-blog' ); ?></button>
	</form>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
