<?php
/**
 * Author archive → cut from archive.php (screens/NowBlog-Category.dc.html).
 * An E-E-A-T author profile: monogram badge · name · substantive bio ·
 * article count / writing-since / website — then the topics they cover and
 * the same card grid as a category page.
 *
 * @package now-blog
 */

get_header();

$now_author = (int) get_query_var( 'author' );
$now_badge  = now_user_badge( $now_author );
$now_name   = get_the_author_meta( 'display_name', $now_author );
$now_count  = (int) count_user_posts( $now_author, 'post', true );
$now_site   = (string) get_the_author_meta( 'url', $now_author );
$now_site   = ( $now_site && untrailingslashit( $now_site ) !== untrailingslashit( home_url( '/' ) ) ) ? $now_site : '';

// "Since" = the author's first published story.
$now_first = get_posts(
	array(
		'author'         => $now_author,
		'posts_per_page' => 1,
		'orderby'        => 'date',
		'order'          => 'ASC',
		'fields'         => 'ids',
		'no_found_rows'  => true,
	)
);
$now_since = $now_first ? get_the_date( 'F Y', $now_first[0] ) : '';

// Topics: the categories this author actually writes in.
$now_topic_ids = array();
$now_post_ids  = get_posts(
	array(
		'author'         => $now_author,
		'posts_per_page' => 100,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	)
);
foreach ( $now_post_ids as $now_pid ) {
	$now_topic_ids = array_merge( $now_topic_ids, wp_get_post_categories( $now_pid ) );
}
$now_topic_ids = array_unique( $now_topic_ids );
?>

<section style="width:100%; max-width:1200px; margin-inline:auto; padding:64px 24px 32px">
	<div class="now-author-hero" style="display:flex; align-items:flex-start; gap:28px">
		<span aria-hidden="true" style="position:relative; overflow:hidden; flex:0 0 auto; width:88px; height:88px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:30px; box-shadow:var(--elev-2); background:<?php echo esc_attr( $now_badge['grad'] ); ?>"><?php echo esc_html( $now_badge['mono'] ); echo now_author_avatar_img( $now_author, 88 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<div style="min-width:0">
			<span style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:var(--text-brand)"><?php esc_html_e( 'Author', 'now-blog' ); ?></span>
			<h1 style="font-family:var(--font-display); font-weight:400; font-size:clamp(34px,5vw,54px); letter-spacing:-0.02em; color:var(--text-primary); margin:10px 0 12px"><?php echo esc_html( $now_name ); ?></h1>
			<p style="color:var(--text-secondary); font-size:18px; line-height:1.6; max-width:640px; margin:0 0 16px"><?php echo esc_html( now_author_bio( $now_author ) ); ?></p>
			<div style="display:flex; flex-wrap:wrap; align-items:center; gap:12px; color:var(--text-muted); font-family:var(--font-body); font-size:13px">
				<span><?php echo esc_html( sprintf( _n( '%d article', '%d articles', $now_count, 'now-blog' ), $now_count ) ); ?></span>
				<?php if ( $now_since ) : ?>
					<span style="width:3px; height:3px; border-radius:50%; background:var(--text-muted)"></span>
					<span><?php echo esc_html( sprintf( __( 'Writing since %s', 'now-blog' ), $now_since ) ); ?></span>
				<?php endif; ?>
				<?php if ( $now_site ) : ?>
					<span style="width:3px; height:3px; border-radius:50%; background:var(--text-muted)"></span>
					<a href="<?php echo esc_url( $now_site ); ?>" rel="me nofollow noopener noreferrer" style="color:var(--text-brand)"><?php esc_html_e( 'Website', 'now-blog' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php if ( ! empty( $now_topic_ids ) ) : ?>
	<div style="margin-top:40px">
		<p style="font-family:var(--font-display); font-weight:400; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:var(--text-muted); margin:0 0 14px"><?php esc_html_e( 'Writes about', 'now-blog' ); ?></p>
		<div style="display:flex; flex-wrap:wrap; gap:8px; justify-content:flex-start">
			<?php
			foreach ( $now_topic_ids as $now_tid ) {
				$now_term = get_category( $now_tid );
				if ( $now_term && ! is_wp_error( $now_term ) ) {
					printf(
						'<a class="now-pill" href="%1$s" style="padding:8px 16px; border-radius:999px; border:1px solid var(--border); background:var(--surface-card); color:var(--text-secondary); font-family:var(--font-body); font-weight:500; font-size:13px">%2$s</a>',
						esc_url( get_category_link( $now_term ) ),
						esc_html( $now_term->name )
					);
				}
			}
			?>
		</div>
	</div>
	<?php endif; ?>
</section>

<section style="width:100%; max-width:1200px; margin-inline:auto; padding-inline:24px; margin-top:24px">
	<h2 style="font-family:var(--font-display); font-weight:400; font-size:24px; color:var(--text-primary); letter-spacing:-0.01em; margin:0 0 24px"><?php esc_html_e( 'Latest stories', 'now-blog' ); ?></h2>
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
