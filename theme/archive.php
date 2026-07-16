<?php
/**
 * Archive (category / tag / author / date) → screens/NowBlog-Category.dc.html.
 * Archive header + a responsive grid of story cards.
 *
 * @package now-blog
 */

get_header();

$now_obj   = get_queried_object();
$now_blurb = '';
if ( is_category() || is_tag() || is_tax() ) {
	$now_eyebrow = single_term_title( '', false );
	$now_title   = $now_eyebrow;
	$now_blurb   = trim( wp_strip_all_tags( term_description() ) );
	$now_eyebrow = is_category() ? __( 'Category', 'now-blog' ) : ( is_tag() ? __( 'Tag', 'now-blog' ) : __( 'Archive', 'now-blog' ) );
	$now_active  = isset( $now_obj->term_id ) ? (int) $now_obj->term_id : 0;
} elseif ( is_author() ) {
	$now_eyebrow = __( 'Author', 'now-blog' );
	$now_title   = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
	$now_active  = 0;
} else {
	$now_eyebrow = __( 'Archive', 'now-blog' );
	$now_title   = get_the_archive_title();
	$now_active  = 0;
}
?>

<section style="width:100%; max-width:1200px; margin-inline:auto; padding:64px 24px 32px">
	<span style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:var(--text-brand)"><?php echo esc_html( $now_eyebrow ); ?></span>
	<h1 style="font-family:var(--font-display); font-weight:400; font-size:clamp(38px,5vw,48px); letter-spacing:-0.02em; color:var(--text-primary); margin:12px 0 8px"><?php echo esc_html( $now_title ); ?></h1>
	<?php if ( '' !== $now_blurb ) : ?>
		<p style="color:var(--text-secondary); font-size:18px; max-width:560px; margin:0"><?php echo esc_html( $now_blurb ); ?></p>
	<?php endif; ?>
	<div style="display:flex; flex-wrap:wrap; gap:8px; justify-content:flex-start; margin-top:32px">
		<?php now_category_pills( $now_active ); ?>
	</div>
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
		<p style="color:var(--text-muted)"><?php esc_html_e( 'No stories here yet. Check back soon.', 'now-blog' ); ?></p>
	<?php endif; ?>
</section>

<?php
get_footer();
