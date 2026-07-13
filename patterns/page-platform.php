<?php
/**
 * Title: Page — The NowPlix Platform
 * Slug: now/page-platform
 * Categories: now
 * Description: Platform page — hero, product pillars, feature grid, dashboard showcase, CTA band.
 *
 * @package now-blog
 */

$now_hero_device    = esc_url( get_theme_file_uri( 'assets/img/hero-device.png' ) );
$now_hero_dashboard = esc_url( get_theme_file_uri( 'assets/img/hero-dashboard.jpg' ) );
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|100"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide">

	<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|80"},"margin":{"top":"var:preset|spacing|80"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-center" style="margin-top:var(--wp--preset--spacing--80)">
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:paragraph {"className":"now-section-eyebrow","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.16em"}},"textColor":"primary-light","fontFamily":"heading"} -->
			<p class="now-section-eyebrow has-primary-light-color has-text-color has-heading-font-family" style="font-size:var(--wp--preset--font-size--small);letter-spacing:0.16em;text-transform:uppercase"><?php esc_html_e( 'The NowPlix Platform', 'now-blog' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"level":1,"style":{"typography":{"fontSize":"var:preset|font-size|huge","fontWeight":"400","lineHeight":"1.04","letterSpacing":"-0.02em"}},"textColor":"contrast"} -->
			<h1 class="wp-block-heading has-contrast-color has-text-color" style="font-size:var(--wp--preset--font-size--huge);font-weight:400;letter-spacing:-0.02em;line-height:1.04"><?php esc_html_e( 'Casino, sportsbook, and the tech that runs them.', 'now-blog' ); ?></h1>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large"}},"textColor":"text"} -->
			<p class="has-text-color has-text" style="font-size:var(--wp--preset--font-size--large)"><?php esc_html_e( 'One real-money platform, engineered for peak nights. Live odds that update in milliseconds, a wallet you can trust, and a dark UI that never strains.', 'now-blog' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|30"}}} -->
			<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--30)">
				<!-- wp:button {"backgroundColor":"accent","textColor":"base-deep","style":{"border":{"radius":"var:custom|radius|md"}}} -->
				<div class="wp-block-button"><a class="wp-block-button__link has-base-deep-color has-accent-background-color has-text-color has-background wp-element-button" href="https://nowplix.dev" target="_blank" rel="noreferrer noopener" style="border-radius:var(--wp--custom--radius--md)"><?php esc_html_e( 'Play now', 'now-blog' ); ?></a></div>
				<!-- /wp:button -->
				<!-- wp:button {"className":"is-style-ghost"} -->
				<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link wp-element-button" href="#pillars"><?php esc_html_e( "See what's inside", 'now-blog' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large","className":"is-style-rounded-lg","style":{"border":{"radius":"var:custom|radius|2xl"},"shadow":"var:preset|shadow|glow-strong"}} -->
			<figure class="wp-block-image is-style-rounded-lg" style="border-radius:var(--wp--custom--radius--2xl);box-shadow:var(--wp--preset--shadow--glow-strong)"><img src="<?php echo $now_hero_device; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>" alt="<?php echo esc_attr__( 'NowPlix platform', 'now-blog' ); ?>" style="aspect-ratio:4/3;object-fit:cover"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:group {"tagName":"section","anchor":"pillars","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group" id="pillars">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"className":"now-section-eyebrow","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.16em"}},"textColor":"primary-light","fontFamily":"heading"} -->
			<p class="now-section-eyebrow has-primary-light-color has-text-color has-heading-font-family" style="font-size:var(--wp--preset--font-size--small);letter-spacing:0.16em;text-transform:uppercase"><?php esc_html_e( 'Three products, one platform', 'now-blog' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"var:preset|font-size|xx-large"}},"textColor":"contrast"} -->
			<h2 class="wp-block-heading has-contrast-color has-text-color" style="font-size:var(--wp--preset--font-size--xx-large)"><?php esc_html_e( 'Everything a player needs, in one place.', 'now-blog' ); ?></h2>
			<!-- /wp:heading -->
		</div>
		<!-- /wp:group -->

		<!-- wp:columns {"className":"now-pillar-grid"} -->
		<div class="wp-block-columns now-pillar-grid">
			<?php
			$now_pillars = array(
				array( 'dice', __( 'Casino', 'now-blog' ), __( 'Slots, live dealer and provably-fair table games. Thousands of titles on a floor that loads instantly.', 'now-blog' ), __( 'Enter the casino', 'now-blog' ) ),
				array( 'trending-up', __( 'Sportsbook', 'now-blog' ), __( 'In-play odds priced while the match moves, with cash-out you can trust and a feed that lands in 87ms.', 'now-blog' ), __( 'Open the sportsbook', 'now-blog' ) ),
				array( 'zap', __( 'Live', 'now-blog' ), __( 'Real-time markets and streams that stay in sync with a stadium of players — without melting the fleet.', 'now-blog' ), __( 'Go live', 'now-blog' ) ),
			);
			foreach ( $now_pillars as $now_p ) :
				?>
			<!-- wp:column {"className":"now-card-surface"} -->
			<div class="wp-block-column now-card-surface">
				<!-- wp:html -->
				<div class="now-icon-chip now-icon-chip-lg"><?php echo now_blog_icon( $now_p[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></div>
				<!-- /wp:html -->
				<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|x-large","letterSpacing":"-0.01em"}},"textColor":"contrast"} -->
				<h3 class="wp-block-heading has-contrast-color has-text-color" style="font-size:var(--wp--preset--font-size--x-large);letter-spacing:-0.01em"><?php echo esc_html( $now_p[1] ); ?></h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.65"}},"textColor":"text"} -->
				<p class="has-text-color has-text" style="line-height:1.65"><?php echo esc_html( $now_p[2] ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","fontWeight":"600"}},"textColor":"primary-light"} -->
				<p class="has-primary-light-color has-text-color" style="font-size:var(--wp--preset--font-size--small);font-weight:600"><a href="https://nowplix.dev"><?php echo esc_html( $now_p[3] ); ?> &rarr;</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->
			<?php endforeach; ?>
		</div>
		<!-- /wp:columns -->
	</section>
	<!-- /wp:group -->

	<!-- wp:group {"className":"now-feature-grid","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"grid","minimumColumnWidth":"280px"}} -->
	<div class="wp-block-group now-feature-grid">
		<?php
		$now_features = array(
			array( 'wallet', __( 'Event-sourced wallet', 'now-blog' ), __( 'One append-only ledger of truth for every deposit, bet and payout.', 'now-blog' ) ),
			array( 'shield', __( 'Provably fair', 'now-blog' ), __( 'Verifiable seeds and hashes on every spin and deal.', 'now-blog' ) ),
			array( 'gift', __( 'Promotions engine', 'now-blog' ), __( 'Targeted offers and bonus maths that stay honest by design.', 'now-blog' ) ),
			array( 'users', __( 'Built for peak', 'now-blog' ), __( 'Capacity pre-warmed against the event calendar for 400% nights.', 'now-blog' ) ),
			array( 'spark', __( 'Dark UI, zero strain', 'now-blog' ), __( 'A committed dark palette with one accent and restrained glow.', 'now-blog' ) ),
			array( 'grid', __( 'Operator dashboard', 'now-blog' ), __( 'Revenue, conversion and KPIs in real time for the platform team.', 'now-blog' ) ),
		);
		foreach ( $now_features as $now_f ) :
			?>
		<!-- wp:group {"className":"now-feature","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
		<div class="wp-block-group now-feature">
			<!-- wp:html -->
			<div class="now-icon-chip"><?php echo now_blog_icon( $now_f[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></div>
			<!-- /wp:html -->
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:heading {"level":4,"style":{"typography":{"fontSize":"var:preset|font-size|large","letterSpacing":"-0.01em"}},"textColor":"contrast"} -->
				<h4 class="wp-block-heading has-contrast-color has-text-color" style="font-size:var(--wp--preset--font-size--large);letter-spacing:-0.01em"><?php echo esc_html( $now_f[1] ); ?></h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","lineHeight":"1.6"}},"textColor":"text"} -->
				<p class="has-text-color has-text" style="font-size:var(--wp--preset--font-size--small);line-height:1.6"><?php echo esc_html( $now_f[2] ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"now-showcase","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group now-showcase">
		<!-- wp:image {"sizeSlug":"large","style":{"border":{"radius":"var:custom|radius|2xl"},"shadow":"var:preset|shadow|glow-strong"}} -->
		<figure class="wp-block-image size-large" style="border-radius:var(--wp--custom--radius--2xl);box-shadow:var(--wp--preset--shadow--glow-strong)"><img src="<?php echo $now_hero_dashboard; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>" alt="<?php echo esc_attr__( 'NowPlix analytics dashboard', 'now-blog' ); ?>"/></figure>
		<!-- /wp:image -->
		<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"var:preset|font-size|small"}},"textColor":"muted"} -->
		<p class="has-text-align-center has-muted-color has-text-color" style="font-size:var(--wp--preset--font-size--small)"><?php esc_html_e( 'The operator dashboard — revenue, conversion and live KPIs on the deep-indigo surface.', 'now-blog' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"now-cta-band","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|30"}},"gradient":"brand","layout":{"type":"constrained","contentSize":"560px"}} -->
	<div class="wp-block-group now-cta-band has-brand-gradient-background has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--60)">
		<!-- wp:heading {"textAlign":"center","level":2,"textColor":"contrast"} -->
		<h2 class="wp-block-heading has-text-align-center has-contrast-color has-text-color"><?php esc_html_e( 'Ready when you are.', 'now-blog' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"contrast"} -->
		<p class="has-text-align-center has-contrast-color has-text-color"><?php esc_html_e( 'Join the platform built for the biggest nights of the year.', 'now-blog' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"backgroundColor":"accent","textColor":"base-deep","style":{"border":{"radius":"var:custom|radius|md"}}} -->
			<div class="wp-block-button"><a class="wp-block-button__link has-base-deep-color has-accent-background-color has-text-color has-background wp-element-button" href="https://nowplix.dev" target="_blank" rel="noreferrer noopener" style="border-radius:var(--wp--custom--radius--md)"><?php esc_html_e( 'Explore the platform', 'now-blog' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
