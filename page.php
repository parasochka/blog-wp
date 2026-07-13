<?php
/**
 * Page. Renders content full-bleed so the designed marketing pages (About,
 * Platform) — which ship their own `<section>` layout — display exactly as
 * authored. Plain text pages stay readable: top-level text elements are
 * constrained to a 760px column via `.now-page` in now.css, while `<section>`
 * children are left full-width.
 *
 * @package now-blog
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
<article <?php post_class( 'now-page' ); ?>>
	<?php
	the_content();
	wp_link_pages(
		array(
			'before' => '<div class="now-page-links">' . esc_html__( 'Pages:', 'now-blog' ),
			'after'  => '</div>',
		)
	);
	?>
</article>
	<?php
endwhile;

get_footer();
