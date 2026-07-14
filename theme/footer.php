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
				<p style="color:var(--text-muted); font-size:14px; margin:0"><?php echo esc_html( now_mod( 'now_footer_tagline' ) ); ?></p>
			</div>
			<div style="display:flex; flex-wrap:wrap; gap:64px">
				<div>
					<h4 style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-muted); margin:0 0 16px"><?php esc_html_e( 'Sections', 'now-blog' ); ?></h4>
					<?php now_footer_sections(); ?>
				</div>
				<div>
					<h4 style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-muted); margin:0 0 16px"><?php esc_html_e( 'Platform', 'now-blog' ); ?></h4>
					<?php
					now_footer_links(
						'footer_platform',
						'footer-platform',
						array(
							'Casino'     => 'https://nowplix.com/platform/modules/casino',
							'Sportsbook' => 'https://nowplix.com/platform/modules/sportsbook',
							'Payments'   => 'https://nowplix.com/platform/modules/payments',
							'Bonuses'    => 'https://nowplix.com/platform/modules/bonus',
						)
					);
					?>
				</div>
				<div>
					<h4 style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-muted); margin:0 0 16px"><?php esc_html_e( 'Company', 'now-blog' ); ?></h4>
					<?php
					now_footer_links(
						'footer_company',
						'footer-company',
						array(
							'About'        => 'https://nowplix.com/about/company',
							'Platform'     => 'https://nowplix.com/platform/modules/all-modules',
							'Partners'     => 'https://nowplix.com/about/partners',
							'Contact Form' => 'https://nowplix.com/about/contact',
						)
					);
					?>
				</div>
			</div>
		</div>
		<div style="display:flex; flex-wrap:wrap; gap:12px; justify-content:space-between; align-items:center; margin-top:48px; padding-top:24px; border-top:1px solid var(--border); color:var(--text-muted); font-size:13px; font-family:var(--font-body)">
			<span><?php printf( esc_html__( '© %s NowPlix. Built with the NOW design system.', 'now-blog' ), esc_html( gmdate( 'Y' ) ) ); ?></span>
			<span style="display:inline-flex; gap:16px">
				<?php
				now_footer_links(
					'footer_legal',
					'footer-legal',
					array(
						'Terms'   => 'https://nowplix.com/about/terms-and-conditions',
						'Privacy' => 'https://nowplix.com/about/privacy-policy',
					),
					true
				);
				?>
				<span>18+</span>
			</span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
