<?php
/**
 * Footer. Mirrors screens/*.dc.html footer verbatim; the "Sections" column
 * is the site's live categories.
 *
 * @package now-blog
 */
?>
</main><!-- #main -->

<footer class="now-footer" style="background:var(--bg-page-deep); border-top:1px solid var(--border); margin-top:128px; padding:64px 0 48px">
	<div style="width:100%; max-width:1200px; margin-inline:auto; padding-inline:24px">
		<div style="display:flex; flex-wrap:wrap; gap:48px; justify-content:space-between">
			<div style="max-width:320px">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-block; margin-bottom:16px"><?php echo now_logo_img( 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></a>
				<p style="color:var(--text-muted); font-size:14px; margin:0"><?php esc_html_e( 'Signals from the future of iGaming — product, design and engineering stories from the NowPlix platform.', 'now-blog' ); ?></p>
			</div>
			<div style="display:flex; flex-wrap:wrap; gap:64px">
				<div>
					<h4 style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-muted); margin:0 0 16px"><?php esc_html_e( 'Sections', 'now-blog' ); ?></h4>
					<?php now_footer_sections(); ?>
				</div>
				<div>
					<h4 style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-muted); margin:0 0 16px"><?php esc_html_e( 'Platform', 'now-blog' ); ?></h4>
					<a class="now-foot-link" href="https://nowplix.dev" target="_blank" rel="noreferrer" style="display:block; color:var(--text-secondary); font-family:var(--font-body); font-size:14px; padding:5px 0">Casino</a>
					<a class="now-foot-link" href="https://nowplix.dev" target="_blank" rel="noreferrer" style="display:block; color:var(--text-secondary); font-family:var(--font-body); font-size:14px; padding:5px 0">Sportsbook</a>
					<a class="now-foot-link" href="https://nowplix.dev" target="_blank" rel="noreferrer" style="display:block; color:var(--text-secondary); font-family:var(--font-body); font-size:14px; padding:5px 0">Live</a>
					<a class="now-foot-link" href="https://nowplix.dev" target="_blank" rel="noreferrer" style="display:block; color:var(--text-secondary); font-family:var(--font-body); font-size:14px; padding:5px 0">Promotions</a>
				</div>
				<div>
					<h4 style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-muted); margin:0 0 16px"><?php esc_html_e( 'Company', 'now-blog' ); ?></h4>
					<a class="now-foot-link" href="<?php echo esc_url( home_url( '/about/' ) ); ?>" style="display:block; color:var(--text-secondary); font-family:var(--font-body); font-size:14px; padding:5px 0">About</a>
					<a class="now-foot-link" href="<?php echo esc_url( home_url( '/platform/' ) ); ?>" style="display:block; color:var(--text-secondary); font-family:var(--font-body); font-size:14px; padding:5px 0">Platform</a>
					<a class="now-foot-link" href="https://nowplix.dev" target="_blank" rel="noreferrer" style="display:block; color:var(--text-secondary); font-family:var(--font-body); font-size:14px; padding:5px 0">Press</a>
					<a class="now-foot-link" href="https://nowplix.dev" target="_blank" rel="noreferrer" style="display:block; color:var(--text-secondary); font-family:var(--font-body); font-size:14px; padding:5px 0">Responsible gaming</a>
				</div>
			</div>
		</div>
		<div style="display:flex; flex-wrap:wrap; gap:12px; justify-content:space-between; align-items:center; margin-top:48px; padding-top:24px; border-top:1px solid var(--border); color:var(--text-muted); font-size:13px; font-family:var(--font-body)">
			<span><?php printf( esc_html__( '© %s NowPlix. Built with the NOW design system.', 'now-blog' ), esc_html( gmdate( 'Y' ) ) ); ?></span>
			<span style="display:inline-flex; gap:16px">
				<a class="now-foot-link" href="https://nowplix.dev" target="_blank" rel="noreferrer" style="color:var(--text-muted)">Terms</a>
				<a class="now-foot-link" href="https://nowplix.dev" target="_blank" rel="noreferrer" style="color:var(--text-muted)">Privacy</a>
				<span>18+</span>
			</span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
