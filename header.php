<?php
/**
 * Header — sticky glass masthead. Mirrors screens/*.dc.html header verbatim;
 * logo, nav and search are dynamic slots.
 *
 * @package now-blog
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="now-header" style="position:sticky; top:0; z-index:100; background:color-mix(in srgb, var(--bg-page) 78%, transparent); backdrop-filter:saturate(150%) blur(14px); -webkit-backdrop-filter:saturate(150%) blur(14px); border-bottom:1px solid var(--border)">
	<div style="width:100%; max-width:1200px; margin-inline:auto; padding-inline:24px; display:flex; align-items:center; justify-content:space-between; gap:32px; height:68px">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="now-brand" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" style="display:inline-flex; align-items:center">
			<?php echo now_logo_img( 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
		</a>

		<nav class="now-nav" aria-label="<?php esc_attr_e( 'Primary', 'now-blog' ); ?>" style="display:flex; align-items:center; gap:24px">
			<?php now_primary_nav(); ?>
		</nav>

		<div style="display:flex; align-items:center; gap:16px">
			<form role="search" method="get" class="now-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-flex; align-items:center; gap:8px; height:40px; padding:0 14px; border-radius:999px; border:1px solid var(--border); background:var(--surface-card); color:var(--text-muted); font-family:var(--font-body); font-size:13px">
				<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
				<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search stories', 'now-blog' ); ?>" aria-label="<?php esc_attr_e( 'Search stories', 'now-blog' ); ?>" style="border:0; background:transparent; outline:none; color:inherit; font:inherit; width:110px"/>
			</form>
			<a href="https://nowplix.dev" target="_blank" rel="noreferrer" class="now-cta-accent" style="display:inline-flex; align-items:center; justify-content:center; font-family:var(--font-body); font-weight:600; font-size:13px; height:38px; padding:0 16px; border-radius:var(--radius-md); background:var(--accent-400); color:#1a1204; box-shadow:var(--glow-accent)"><?php esc_html_e( 'Explore platform', 'now-blog' ); ?></a>
			<button type="button" class="now-burger" aria-label="<?php esc_attr_e( 'Menu', 'now-blog' ); ?>" aria-controls="now-mobile-menu" aria-expanded="false">
				<span class="now-burger-box" aria-hidden="true"><span class="now-burger-line"></span><span class="now-burger-line"></span><span class="now-burger-line"></span></span>
			</button>
		</div>
	</div>

	<!-- Mobile menu (revealed by .now-burger; hidden ≥561px) -->
	<div id="now-mobile-menu" class="now-mobile-menu" hidden>
		<div style="max-width:1200px; margin-inline:auto; padding:8px 24px 24px">
			<form role="search" method="get" class="now-mobile-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
				<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search stories', 'now-blog' ); ?>" aria-label="<?php esc_attr_e( 'Search stories', 'now-blog' ); ?>"/>
			</form>
			<?php now_mobile_nav(); ?>
		</div>
	</div>
</header>

<main id="main">
