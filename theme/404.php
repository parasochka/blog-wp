<?php
/**
 * 404.
 *
 * @package now-blog
 */

get_header();
?>

<section style="width:100%; max-width:1200px; margin-inline:auto; padding:96px 24px; text-align:center; min-height:52vh">
	<span style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:var(--text-brand)">404</span>
	<h1 style="font-family:var(--font-display); font-weight:400; font-size:clamp(38px,5vw,48px); line-height:1.04; letter-spacing:-0.02em; color:var(--text-primary); margin:16px auto 8px; max-width:16ch; text-wrap:balance"><?php esc_html_e( 'This page slipped off the table.', 'now-blog' ); ?></h1>
	<p style="font-size:18px; color:var(--text-secondary); max-width:520px; margin:0 auto 24px"><?php esc_html_e( 'The story you were after has moved or never existed. Head back to the latest signals.', 'now-blog' ); ?></p>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="now-btn-primary" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; font-family:var(--font-ui); font-weight:600; font-size:14px; line-height:1; letter-spacing:0.1px; height:44px; padding:0 16px; border-radius:var(--radius-md); background:var(--primary-500); color:#fff"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg> <?php esc_html_e( 'Back to home', 'now-blog' ); ?></a>
</section>

<?php
get_footer();
