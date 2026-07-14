<?php
/**
 * Template Name: Full-width canvas
 *
 * Renders page content edge-to-edge with no theme chrome around it — for the
 * designed marketing pages (About, Platform) whose content already ships the
 * full section layout (each section self-constrains to max-width:1200).
 *
 * @package now-blog
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
<article <?php post_class( 'now-canvas' ); ?>>
	<?php the_content(); ?>
</article>
	<?php
endwhile;

get_footer();
