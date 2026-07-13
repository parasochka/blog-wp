<?php
/**
 * Title: Hero
 * Slug: now/hero
 * Categories: now, banner
 * Description: Editorial hero heading with lede and call to action.
 *
 * @package now-blog
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|70"}},"blockGap":"var:preset|spacing|40"},"layout":{"type":"constrained","contentSize":"820px"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.08em"}},"textColor":"primary"} -->
	<p class="has-text-align-center has-primary-color has-text-color" style="font-size:var(--wp--preset--font-size--small);letter-spacing:0.08em;text-transform:uppercase"><?php esc_html_e( 'The NOW Blog', 'now-blog' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"var:preset|font-size|display"}}} -->
	<h1 class="wp-block-heading has-text-align-center" style="font-size:var(--wp--preset--font-size--display)"><?php esc_html_e( 'Ideas, stories and signals from the edge of now.', 'now-blog' ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|large"}},"textColor":"muted"} -->
	<p class="has-text-align-center has-muted-color has-text-color" style="font-size:var(--wp--preset--font-size--large)"><?php esc_html_e( 'A curated blog on design, technology and the culture around them.', 'now-blog' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--30)">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#latest"><?php esc_html_e( 'Read the latest', 'now-blog' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
