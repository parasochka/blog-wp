<?php
/**
 * Title: Page — About NowPlix
 * Slug: now/page-about
 * Categories: now
 * Description: About page — hero, mission, stats strip, values and a brand CTA band.
 *
 * @package now-blog
 */

$now_art_hand = esc_url( get_theme_file_uri( 'assets/img/art-hand.png' ) );
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|100"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide">

	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40","margin":{"top":"var:preset|spacing|80"}}},"layout":{"type":"constrained","contentSize":"820px","justifyContent":"left"}} -->
	<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--80)">
		<!-- wp:paragraph {"className":"now-section-eyebrow","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.16em"}},"textColor":"primary-light","fontFamily":"heading"} -->
		<p class="now-section-eyebrow has-primary-light-color has-text-color has-heading-font-family" style="font-size:var(--wp--preset--font-size--small);letter-spacing:0.16em;text-transform:uppercase"><?php esc_html_e( 'About NowPlix', 'now-blog' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"level":1,"style":{"typography":{"fontSize":"var:preset|font-size|display","fontWeight":"400","lineHeight":"1.03","letterSpacing":"-0.02em"}},"textColor":"contrast"} -->
		<h1 class="wp-block-heading has-contrast-color has-text-color" style="font-size:var(--wp--preset--font-size--display);font-weight:400;letter-spacing:-0.02em;line-height:1.03"><?php esc_html_e( 'We build the platform behind the play.', 'now-blog' ); ?></h1>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large"}},"textColor":"text"} -->
		<p class="has-text-color has-text" style="font-size:var(--wp--preset--font-size--large)"><?php esc_html_e( 'NowPlix is a real-money iGaming platform — casino, sportsbook and everything around them. This blog is where our product, design and engineering teams write about the craft that keeps it fast, fair and calm under load.', 'now-blog' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|80"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"var:preset|font-size|xx-large","lineHeight":"1.1"}},"textColor":"contrast"} -->
			<h2 class="wp-block-heading has-contrast-color has-text-color" style="font-size:var(--wp--preset--font-size--xx-large);line-height:1.1"><?php esc_html_e( 'Premium, not playful. Calm, not loud.', 'now-blog' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.75"}},"textColor":"text"} -->
			<p class="has-text-color has-text" style="font-size:var(--wp--preset--font-size--large);line-height:1.75"><?php esc_html_e( 'We treat a casino and a sportsbook the way an engineering team treats any high-stakes system: real numbers over hype, latency budgets over promises, and trust designed into every screen where money moves.', 'now-blog' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.75"}},"textColor":"text"} -->
			<p class="has-text-color has-text" style="font-size:var(--wp--preset--font-size--large);line-height:1.75"><?php esc_html_e( 'The house line — signals from the future of iGaming — is a standard, not a slogan. It is why we publish how we hold a p95 flat while volume quadruples, and why we never bolt responsible gaming on as an afterthought.', 'now-blog' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"large","className":"is-style-rounded-lg","style":{"border":{"radius":"var:custom|radius|2xl"},"shadow":"var:preset|shadow|glow-strong"}} -->
			<figure class="wp-block-image is-style-rounded-lg has-custom-border" style="border-radius:var(--wp--custom--radius--2xl);box-shadow:var(--wp--preset--shadow--glow-strong)"><img src="<?php echo $now_art_hand; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>" alt="<?php echo esc_attr__( 'NowPlix glass render', 'now-blog' ); ?>" style="aspect-ratio:1;object-fit:cover"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:columns {"className":"now-stats"} -->
	<div class="wp-block-columns now-stats">
		<?php
		$now_stats = array(
			array( '87ms', __( 'Median odds update', 'now-blog' ) ),
			array( '400%', __( 'Peak event scale', 'now-blog' ) ),
			array( '5', __( 'Story categories', 'now-blog' ) ),
			array( '99.98%', __( 'Uptime, live markets', 'now-blog' ) ),
		);
		foreach ( $now_stats as $now_s ) :
			?>
		<!-- wp:column {"className":"now-stat"} -->
		<div class="wp-block-column now-stat">
			<!-- wp:paragraph {"className":"now-stat-value"} -->
			<p class="now-stat-value"><?php echo esc_html( $now_s[0] ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"className":"now-stat-label"} -->
			<p class="now-stat-label"><?php echo esc_html( $now_s[1] ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:columns -->

	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"className":"now-section-eyebrow","style":{"typography":{"fontSize":"var:preset|font-size|small","textTransform":"uppercase","letterSpacing":"0.16em"}},"textColor":"primary-light","fontFamily":"heading"} -->
			<p class="now-section-eyebrow has-primary-light-color has-text-color has-heading-font-family" style="font-size:var(--wp--preset--font-size--small);letter-spacing:0.16em;text-transform:uppercase"><?php esc_html_e( 'What we believe', 'now-blog' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"var:preset|font-size|xx-large"}},"textColor":"contrast"} -->
			<h2 class="wp-block-heading has-contrast-color has-text-color" style="font-size:var(--wp--preset--font-size--xx-large)"><?php esc_html_e( 'Four principles, on every screen.', 'now-blog' ); ?></h2>
			<!-- /wp:heading -->
		</div>
		<!-- /wp:group -->

		<!-- wp:columns {"className":"now-value-grid"} -->
		<div class="wp-block-columns now-value-grid">
			<?php
			$now_values = array(
				array( 'zap', __( 'Fast, on purpose', 'now-blog' ), __( 'Every hop gets a latency number. We spend the budget deliberately so the player never feels the load.', 'now-blog' ) ),
				array( 'shield', __( 'Fair and provable', 'now-blog' ), __( 'Provably-fair maths and transparent odds. Trust is a feature we ship, not a claim we make.', 'now-blog' ) ),
				array( 'spark', __( 'Craft over noise', 'now-blog' ), __( 'One accent colour, a committed dark palette and restrained motion. Premium, never playful-cartoon.', 'now-blog' ) ),
				array( 'users', __( 'Responsible by design', 'now-blog' ), __( 'Limits, cool-offs and honest nudges are built in from the first screen — not bolted on later.', 'now-blog' ) ),
			);
			foreach ( $now_values as $now_v ) :
				?>
			<!-- wp:column {"className":"now-card-surface"} -->
			<div class="wp-block-column now-card-surface">
				<!-- wp:html -->
				<div class="now-icon-chip"><?php echo now_blog_icon( $now_v[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></div>
				<!-- /wp:html -->
				<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.2"}},"textColor":"contrast"} -->
				<h3 class="wp-block-heading has-contrast-color has-text-color" style="font-size:var(--wp--preset--font-size--large);line-height:1.2"><?php echo esc_html( $now_v[1] ); ?></h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"style":{"typography":{"fontSize":"var:preset|font-size|small","lineHeight":"1.6"}},"textColor":"text"} -->
				<p class="has-text-color has-text" style="font-size:var(--wp--preset--font-size--small);line-height:1.6"><?php echo esc_html( $now_v[2] ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->
			<?php endforeach; ?>
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"now-cta-band","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|30"}},"gradient":"brand","layout":{"type":"constrained","contentSize":"560px"}} -->
	<div class="wp-block-group now-cta-band has-brand-gradient-background has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--60)">
		<!-- wp:heading {"textAlign":"center","level":2,"textColor":"contrast"} -->
		<h2 class="wp-block-heading has-text-align-center has-contrast-color has-text-color"><?php esc_html_e( 'See what the platform can do.', 'now-blog' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"contrast"} -->
		<p class="has-text-align-center has-contrast-color has-text-color"><?php esc_html_e( 'Casino, sportsbook and the technology behind them — live at nowplix.dev.', 'now-blog' ); ?></p>
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
