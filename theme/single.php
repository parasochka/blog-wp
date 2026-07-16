<?php
/**
 * Single post → screens/NowBlog-Article.dc.html.
 * Centered head → 21:9 hero → 760px prose + sticky sidebar → related.
 *
 * @package now-blog
 */

get_header();

while ( have_posts() ) :
	the_post();
	$badge = now_author_badge();
	$cats  = get_the_category();
	$cat   = ! empty( $cats ) ? $cats[0] : null;
	$url   = get_permalink();
	$title = get_the_title();
	?>

<article <?php post_class(); ?>>
	<!-- head -->
	<section style="width:100%; max-width:1200px; margin-inline:auto; padding:64px 24px 32px; text-align:center">
		<?php if ( $cat ) : ?>
			<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:var(--text-brand)"><?php echo esc_html( $cat->name ); ?></a>
		<?php endif; ?>
		<h1 style="font-family:var(--font-display); font-weight:400; font-size:clamp(34px,5vw,48px); line-height:1.06; letter-spacing:-0.01em; color:var(--text-primary); max-width:20ch; margin:16px auto; text-wrap:balance"><?php the_title(); ?></h1>
		<?php $now_author_url = get_author_posts_url( (int) get_the_author_meta( 'ID' ) ); ?>
		<div style="display:inline-flex; align-items:center; gap:12px">
			<a href="<?php echo esc_url( $now_author_url ); ?>" rel="author" aria-hidden="true" tabindex="-1" style="position:relative; overflow:hidden; width:40px; height:40px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:15px; background:<?php echo esc_attr( $badge['grad'] ); ?>"><?php echo esc_html( $badge['mono'] ); echo now_author_avatar_img( (int) get_the_author_meta( 'ID' ), 40 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<div style="text-align:left">
				<a class="now-author-link" href="<?php echo esc_url( $now_author_url ); ?>" rel="author" style="display:block; color:var(--text-primary); font-family:var(--font-body); font-weight:600; font-size:14px"><?php the_author(); ?></a>
				<div style="display:flex; align-items:center; gap:12px; color:var(--text-muted); font-size:13px; margin-top:2px">
					<span><?php echo esc_html( get_the_date() ); ?></span><span style="width:3px; height:3px; border-radius:50%; background:var(--text-muted)"></span><span><?php echo esc_html( now_reading_time() ); ?> <?php esc_html_e( 'read', 'now-blog' ); ?></span>
				</div>
			</div>
		</div>
	</section>

	<!-- 21:9 hero -->
	<?php if ( has_post_thumbnail() ) : ?>
	<div style="width:100%; max-width:1200px; margin-inline:auto; padding-inline:24px">
		<div style="overflow:hidden; border-radius:var(--radius-xl); aspect-ratio:21/9; background:var(--bg-page-deep); box-shadow:var(--elev-3)">
			<?php the_post_thumbnail( 'full', array( 'style' => 'width:100%; height:100%; object-fit:cover; display:block', 'alt' => the_title_attribute( array( 'echo' => false ) ), 'fetchpriority' => 'high', 'loading' => 'eager' ) ); ?>
		</div>
	</div>
	<?php endif; ?>

	<!-- body: prose + floating sidebar -->
	<div style="width:100%; max-width:1200px; margin-inline:auto; padding-inline:24px">
		<div class="now-article-grid" style="display:grid; grid-template-columns:minmax(0,760px) 300px; gap:80px; justify-content:center; align-items:start; margin-top:64px">

			<div style="min-width:0; display:flex; flex-direction:column">
				<div class="now-prose" style="font-size:18px; line-height:1.78; color:var(--text-secondary); min-width:0">
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

				<?php $now_post_tags = get_the_tags(); ?>
				<?php if ( $now_post_tags && ! is_wp_error( $now_post_tags ) ) : ?>
				<div class="now-article-tags" style="margin-top:48px; padding-top:32px; border-top:1px solid var(--border)">
					<p style="font-family:var(--font-display); font-weight:400; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:var(--text-muted); margin:0 0 14px"><?php esc_html_e( 'Tagged', 'now-blog' ); ?></p>
					<div style="display:flex; flex-wrap:wrap; gap:8px">
						<?php now_tag_pills( $now_post_tags ); ?>
					</div>
				</div>
				<?php endif; ?>

				<?php now_author_card(); ?>
			</div>

			<aside class="now-sidebar" style="position:sticky; top:92px; display:flex; flex-direction:column; gap:16px">
				<div style="background:var(--surface-card); border:1px solid var(--border); border-radius:var(--radius-xl); padding:24px; box-shadow:var(--elev-1)">
					<p style="font-family:var(--font-display); font-weight:400; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:var(--text-muted); margin:0 0 16px"><?php esc_html_e( 'Share', 'now-blog' ); ?></p>
					<div style="display:flex; flex-wrap:wrap; gap:8px">
						<a class="now-share" href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( $url ); ?>&text=<?php echo rawurlencode( $title ); ?>" target="_blank" rel="nofollow noopener noreferrer" style="display:inline-flex; align-items:center; gap:6px; padding:8px 12px; border-radius:var(--radius-md); border:1px solid var(--border); background:var(--bg-page); color:var(--text-secondary); font-family:var(--font-body); font-weight:600; font-size:13px">X</a>
						<a class="now-share" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode( $url ); ?>" target="_blank" rel="nofollow noopener noreferrer" style="display:inline-flex; align-items:center; gap:6px; padding:8px 12px; border-radius:var(--radius-md); border:1px solid var(--border); background:var(--bg-page); color:var(--text-secondary); font-family:var(--font-body); font-weight:600; font-size:13px">LinkedIn</a>
						<a class="now-share" href="https://t.me/share/url?url=<?php echo rawurlencode( $url ); ?>&text=<?php echo rawurlencode( $title ); ?>" target="_blank" rel="nofollow noopener noreferrer" style="display:inline-flex; align-items:center; gap:6px; padding:8px 12px; border-radius:var(--radius-md); border:1px solid var(--border); background:var(--bg-page); color:var(--text-secondary); font-family:var(--font-body); font-weight:600; font-size:13px">Telegram</a>
						<a class="now-share" href="https://wa.me/?text=<?php echo rawurlencode( $title . ' ' . $url ); ?>" target="_blank" rel="nofollow noopener noreferrer" style="display:inline-flex; align-items:center; gap:6px; padding:8px 12px; border-radius:var(--radius-md); border:1px solid var(--border); background:var(--bg-page); color:var(--text-secondary); font-family:var(--font-body); font-weight:600; font-size:13px">WhatsApp</a>
						<button type="button" class="now-share now-copy-link" data-url="<?php echo esc_url( $url ); ?>" style="display:inline-flex; align-items:center; gap:6px; padding:8px 12px; border-radius:var(--radius-md); border:1px solid var(--border); background:var(--bg-page); color:var(--text-secondary); font-family:var(--font-body); font-weight:600; font-size:13px; cursor:pointer"><?php esc_html_e( 'Copy link', 'now-blog' ); ?></button>
					</div>
				</div>

				<div style="background:var(--surface-card); border-radius:var(--radius-xl); padding:24px; box-shadow:var(--elev-2)">
					<h3 style="font-family:var(--font-display); font-weight:400; font-size:18px; color:var(--text-primary); margin:0 0 8px; letter-spacing:-0.01em"><?php echo esc_html( now_mod( 'now_promo_title' ) ); ?></h3>
					<p style="color:var(--text-secondary); font-size:14px; margin:0 0 16px"><?php echo esc_html( now_mod( 'now_promo_text' ) ); ?></p>
					<?php
					$now_promo_url = now_mod( 'now_promo_url' );
					$now_promo_rel = now_link_rel( $now_promo_url ); // empty for internal URLs → same tab, no rel
					?>
					<a href="<?php echo esc_url( $now_promo_url ); ?>"<?php echo $now_promo_rel ? ' target="_blank" rel="' . esc_attr( $now_promo_rel ) . '"' : ''; ?> class="now-cta-accent" style="display:flex; align-items:center; justify-content:center; gap:8px; width:100%; box-sizing:border-box; font-family:var(--font-ui); font-weight:700; font-size:14px; line-height:1; letter-spacing:0.1px; height:44px; padding:0 16px; border-radius:var(--radius-cta); background:var(--accent-400); color:var(--neutral-950); box-shadow:var(--glow-accent)"><?php echo esc_html( now_mod( 'now_promo_button' ) ); ?></a>
				</div>
			</aside>
		</div>
	</div>

	<!-- related -->
	<?php
	$rel_cat = $cat ? $cat->term_id : 0;
	$related = new WP_Query(
		array(
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'cat'                 => $rel_cat,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( $related->have_posts() ) :
		?>
	<section style="width:100%; max-width:1200px; margin-inline:auto; padding-inline:24px; margin-top:96px">
		<div style="display:flex; align-items:baseline; justify-content:space-between; margin-bottom:24px">
			<h2 style="font-family:var(--font-display); font-weight:400; font-size:30px; color:var(--text-primary); margin:0; letter-spacing:-0.01em"><?php esc_html_e( 'More stories', 'now-blog' ); ?></h2>
		</div>
		<div class="now-related-grid" style="display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:24px">
			<?php
			while ( $related->have_posts() ) {
				$related->the_post();
				now_render_card( true );
			}
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php endif; ?>
</article>

	<?php
endwhile;

get_footer();
