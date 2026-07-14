<?php
/**
 * Tag archive → cut from archive.php (screens/NowBlog-Category.dc.html).
 * Same header + card grid as a category, tag-flavored: "#" title, the tag's
 * description, and a browse row of the most-used tags instead of categories.
 *
 * @package now-blog
 */

get_header();

$now_tag   = get_queried_object();
$now_blurb = trim( wp_strip_all_tags( term_description() ) );
$now_count = ( $now_tag instanceof WP_Term ) ? (int) $now_tag->count : 0;

// The browse row: the site's most-used tags (the current one always included).
$now_top_tags = get_tags(
	array(
		'orderby'    => 'count',
		'order'      => 'DESC',
		'number'     => 12,
		'hide_empty' => true,
	)
);
if ( $now_tag instanceof WP_Term && ! in_array( $now_tag->term_id, wp_list_pluck( $now_top_tags, 'term_id' ), true ) ) {
	array_unshift( $now_top_tags, $now_tag );
}
?>

<section style="width:100%; max-width:1200px; margin-inline:auto; padding:64px 24px 32px">
	<span style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:var(--text-brand)"><?php esc_html_e( 'Tag', 'now-blog' ); ?></span>
	<h1 style="font-family:var(--font-display); font-weight:400; font-size:clamp(38px,5vw,60px); letter-spacing:-0.02em; color:var(--text-primary); margin:12px 0 8px"><span style="color:var(--text-brand)">#</span><?php single_term_title(); ?></h1>
	<?php if ( '' !== $now_blurb ) : ?>
		<p style="color:var(--text-secondary); font-size:18px; max-width:560px; margin:0 0 8px"><?php echo esc_html( $now_blurb ); ?></p>
	<?php endif; ?>
	<p style="color:var(--text-muted); font-family:var(--font-body); font-size:13px; margin:0">
		<?php echo esc_html( sprintf( _n( '%d story', '%d stories', $now_count, 'now-blog' ), $now_count ) ); ?>
	</p>
	<div style="display:flex; flex-wrap:wrap; gap:8px; justify-content:flex-start; margin-top:32px">
		<?php now_tag_pills( $now_top_tags, ( $now_tag instanceof WP_Term ) ? $now_tag->term_id : 0 ); ?>
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
