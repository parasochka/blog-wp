<?php
/**
 * Standard page → readable prose column. Marketing pages that ship their own
 * full-bleed layout (About, Platform) use the "Full-width canvas" template
 * (page-canvas.php) instead.
 *
 * @package now-blog
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
<article <?php post_class(); ?>>
	<section style="width:100%; max-width:1200px; margin-inline:auto; padding:96px 24px 24px">
		<div style="max-width:820px">
			<h1 style="font-family:var(--font-display); font-weight:400; font-size:clamp(38px,5vw,60px); line-height:1.04; letter-spacing:-0.02em; color:var(--text-primary); margin:0 0 8px"><?php the_title(); ?></h1>
		</div>
	</section>
	<div style="width:100%; max-width:820px; margin-inline:auto; padding-inline:24px; margin-top:24px">
		<div class="now-prose" style="font-size:18px; line-height:1.78; color:var(--text-secondary)">
			<?php
			the_content();
			wp_link_pages(
				array(
					'before' => '<div class="now-page-links">' . esc_html__( 'Pages:', 'now-blog' ),
					'after'  => '</div>',
				)
			);
			?>
		</div>
	</div>
</article>
	<?php
endwhile;

get_footer();
