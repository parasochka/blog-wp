<?php
/**
 * Fallback index — a plain grid of the latest stories. The blog front page
 * uses home.php (hero + featured + category rails); this is the safety net
 * for any query without a more specific template.
 *
 * @package now-blog
 */

get_header();
?>

<section style="width:100%; max-width:1200px; margin-inline:auto; padding:64px 24px 32px">
	<span style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:var(--text-brand)"><?php esc_html_e( 'The NowPlix Blog', 'now-blog' ); ?></span>
	<h1 style="font-family:var(--font-display); font-weight:400; font-size:clamp(38px,5vw,48px); letter-spacing:-0.02em; color:var(--text-primary); margin:12px 0 8px"><?php esc_html_e( 'Latest stories', 'now-blog' ); ?></h1>
</section>

<section style="width:100%; max-width:1200px; margin-inline:auto; padding-inline:24px; margin-top:24px">
	<?php if ( have_posts() ) : ?>
		<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:48px 24px">
			<?php
			while ( have_posts() ) :
				the_post();
				now_render_card( true );
			endwhile;
			?>
		</div>
		<div class="now-pagination" style="margin-top:64px">
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => __( '&larr; Newer', 'now-blog' ),
					'next_text' => __( 'Older &rarr;', 'now-blog' ),
				)
			);
			?>
		</div>
	<?php else : ?>
		<p style="color:var(--text-muted)"><?php esc_html_e( 'Nothing here yet. Check back soon.', 'now-blog' ); ?></p>
	<?php endif; ?>
</section>

<?php
get_footer();
