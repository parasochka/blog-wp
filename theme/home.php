<?php
/**
 * Home / blog index → screens/NowBlog-Home.dc.html.
 * Hero → featured lead → one horizontal rail per category → newsletter.
 *
 * @package now-blog
 */

get_header();
?>

<!-- ============ HERO ============ -->
<section style="width:100%; max-width:1200px; margin-inline:auto; padding:96px 24px 48px; text-align:center">
	<span style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:var(--text-brand)"><?php esc_html_e( 'The NowPlix Blog', 'now-blog' ); ?></span>
	<h1 style="font-family:var(--font-display); font-weight:400; font-size:clamp(44px,6.5vw,84px); line-height:1.02; letter-spacing:-0.02em; color:var(--text-primary); margin:16px 0; text-wrap:balance"><?php esc_html_e( 'Signals from the future of iGaming.', 'now-blog' ); ?></h1>
	<p style="font-size:19px; color:var(--text-secondary); max-width:620px; margin:0 auto 24px; text-wrap:pretty"><?php esc_html_e( 'Product, design and technology stories from the NowPlix platform — casino, sportsbook and everything around them.', 'now-blog' ); ?></p>
	<div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap">
		<a href="#latest" class="now-btn-primary" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; font-family:var(--font-body); font-weight:600; font-size:14px; height:44px; padding:0 20px; border-radius:var(--radius-md); background:var(--primary-500); color:#fff"><?php esc_html_e( 'Read the latest', 'now-blog' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="now-btn-ghost" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; font-family:var(--font-body); font-weight:600; font-size:14px; height:44px; padding:0 20px; border-radius:var(--radius-md); background:transparent; color:var(--primary-200); border:1px solid var(--primary-500)"><?php esc_html_e( 'About NowPlix', 'now-blog' ); ?></a>
	</div>
	<div style="display:flex; flex-wrap:wrap; gap:8px; justify-content:center; margin-top:32px">
		<?php now_category_pills(); ?>
	</div>
</section>

<?php
/* ============ FEATURED LEAD — latest (or sticky) story ============ */
$sticky   = get_option( 'sticky_posts' );
$feat_args = array(
	'posts_per_page'      => 1,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);
if ( ! empty( $sticky ) ) {
	$feat_args['post__in'] = $sticky;
	$feat_args['orderby']  = 'date';
}
$featured = new WP_Query( $feat_args );
if ( $featured->have_posts() ) :
	while ( $featured->have_posts() ) :
		$featured->the_post();
		$badge = now_author_badge();
		$cats  = get_the_category();
		$cat   = ! empty( $cats ) ? $cats[0] : null;
		?>
<section id="latest" style="width:100%; max-width:1200px; margin-inline:auto; padding-inline:24px; margin-top:64px">
	<div class="now-featured" style="display:grid; grid-template-columns:1.35fr 1fr; gap:64px; align-items:center">
		<a href="<?php the_permalink(); ?>" class="now-card-media" style="display:block; overflow:hidden; border-radius:var(--radius-2xl); box-shadow:var(--elev-3); aspect-ratio:3/2; background:var(--bg-page-deep)">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail(
					'large',
					array(
						'style'         => 'width:100%; height:100%; object-fit:cover; display:block',
						'alt'           => the_title_attribute( array( 'echo' => false ) ),
						'fetchpriority' => 'high',
						'loading'       => 'eager',
					)
				);
			}
			?>
		</a>
		<div style="display:flex; flex-direction:column; gap:16px">
			<span style="font-family:var(--font-display); font-weight:400; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:var(--text-brand)"><?php echo esc_html( $cat ? $cat->name : '' ); ?> · <?php esc_html_e( 'Featured', 'now-blog' ); ?></span>
			<h2 style="font-family:var(--font-display); font-weight:400; font-size:clamp(30px,3.4vw,46px); line-height:1.08; color:var(--text-primary); margin:0; text-wrap:balance"><a href="<?php the_permalink(); ?>" style="color:inherit"><?php the_title(); ?></a></h2>
			<p style="font-size:18px; color:var(--text-secondary); margin:0; text-wrap:pretty"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 34, '…' ) ); ?></p>
			<div style="display:flex; align-items:center; gap:12px; color:var(--text-muted); font-size:13px">
				<span style="position:relative; overflow:hidden; width:24px; height:24px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:10px; background:<?php echo esc_attr( $badge['grad'] ); ?>"><?php echo esc_html( $badge['mono'] ); echo now_author_avatar_img( (int) get_the_author_meta( 'ID' ), 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span style="color:var(--text-secondary)"><?php the_author(); ?></span>
				<span style="width:3px; height:3px; border-radius:50%; background:var(--text-muted)"></span>
				<span><?php echo esc_html( get_the_date() ); ?></span>
				<span style="width:3px; height:3px; border-radius:50%; background:var(--text-muted)"></span>
				<span><?php echo esc_html( now_reading_time() ); ?></span>
			</div>
		</div>
	</div>
</section>
		<?php
	endwhile;
	wp_reset_postdata();
endif;

/* ============ CATEGORY ROWS — one rail per category ============ */
$row_cats = get_categories(
	array(
		'orderby'    => 'term_id',
		'order'      => 'ASC',
		'hide_empty' => true,
	)
);
?>
<div style="width:100%; max-width:1200px; margin-inline:auto; padding-inline:24px">
	<?php
	foreach ( $row_cats as $c ) :
		$rail_q = new WP_Query(
			array(
				'posts_per_page'      => 8,
				'cat'                 => $c->term_id,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		if ( ! $rail_q->have_posts() ) {
			continue;
		}
		$rail_id = 'now-rail-' . $c->slug;
		$blurb   = trim( wp_strip_all_tags( $c->description ) );
		?>
	<section style="margin-top:96px">
		<div style="display:flex; flex-wrap:wrap; align-items:baseline; justify-content:space-between; gap:12px; margin-bottom:24px">
			<div>
				<?php if ( '' !== $blurb ) : ?>
					<span style="display:block; margin-bottom:6px; font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:var(--text-brand)"><?php echo esc_html( $blurb ); ?></span>
				<?php endif; ?>
				<h2 style="font-family:var(--font-display); font-weight:400; font-size:30px; color:var(--text-primary); margin:0; letter-spacing:-0.01em"><a href="<?php echo esc_url( get_category_link( $c ) ); ?>" style="color:inherit"><?php echo esc_html( $c->name ); ?></a></h2>
			</div>
			<div style="display:flex; align-items:center; gap:16px">
				<a class="now-viewall" href="<?php echo esc_url( get_category_link( $c ) ); ?>" style="font-family:var(--font-body); font-weight:600; font-size:14px; color:var(--text-brand); display:inline-flex; align-items:center; gap:6px"><?php esc_html_e( 'View all', 'now-blog' ); ?> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
				<div style="display:flex; gap:8px">
					<button type="button" class="now-rail-btn" data-rail="<?php echo esc_attr( $rail_id ); ?>" data-dir="-1" aria-label="<?php esc_attr_e( 'Scroll left', 'now-blog' ); ?>" style="width:38px; height:38px; border-radius:50%; border:1px solid var(--border); background:var(--surface-card); color:var(--text-secondary); display:inline-flex; align-items:center; justify-content:center; cursor:pointer"><span style="display:inline-flex; transform:rotate(180deg)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg></span></button>
					<button type="button" class="now-rail-btn" data-rail="<?php echo esc_attr( $rail_id ); ?>" data-dir="1" aria-label="<?php esc_attr_e( 'Scroll right', 'now-blog' ); ?>" style="width:38px; height:38px; border-radius:50%; border:1px solid var(--border); background:var(--surface-card); color:var(--text-secondary); display:inline-flex; align-items:center; justify-content:center; cursor:pointer"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg></button>
				</div>
			</div>
		</div>
		<div class="now-rail" id="<?php echo esc_attr( $rail_id ); ?>" style="display:grid; grid-auto-flow:column; grid-auto-columns:minmax(300px,1fr); gap:24px; overflow-x:auto; scroll-snap-type:x mandatory; padding-bottom:12px; scrollbar-width:thin">
			<?php
			while ( $rail_q->have_posts() ) {
				$rail_q->the_post();
				now_render_card( true );
			}
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php endforeach; ?>
</div>

<!-- ============ NEWSLETTER ============ -->
<section style="width:100%; max-width:1200px; margin-inline:auto; padding-inline:24px; margin-top:96px">
	<div style="position:relative; overflow:hidden; border-radius:var(--radius-2xl); background:var(--gradient-brand); box-shadow:var(--glow-brand); padding:64px 32px; text-align:center">
		<span style="font-family:var(--font-display); font-weight:400; font-size:12px; letter-spacing:0.16em; text-transform:uppercase; color:rgba(255,255,255,0.85)"><?php esc_html_e( 'Newsletter', 'now-blog' ); ?></span>
		<h2 style="font-family:var(--font-display); font-weight:400; font-size:clamp(28px,3vw,40px); color:#fff; margin:8px 0 0; letter-spacing:-0.01em"><?php esc_html_e( 'Never miss a signal', 'now-blog' ); ?></h2>
		<p style="color:rgba(255,255,255,0.86); max-width:460px; margin:12px auto 24px"><?php esc_html_e( 'New stories delivered to your inbox. No spam, unsubscribe anytime.', 'now-blog' ); ?></p>
		<form class="now-newsletter" action="#" method="post" style="display:flex; gap:10px; max-width:460px; margin:0 auto">
			<input type="email" name="email" placeholder="you@email.com" required style="flex:1; height:48px; padding:0 16px; border-radius:var(--radius-md); border:1px solid rgba(255,255,255,0.28); background:rgba(8,8,24,0.35); color:#fff; font-family:var(--font-body); font-size:15px; outline:none"/>
			<button type="submit" class="now-cta-accent" style="display:inline-flex; align-items:center; justify-content:center; font-family:var(--font-body); font-weight:600; font-size:14px; height:48px; padding:0 20px; border-radius:var(--radius-md); background:var(--accent-400); color:#1a1204; box-shadow:var(--glow-accent); border:none; cursor:pointer"><?php esc_html_e( 'Subscribe', 'now-blog' ); ?></button>
		</form>
	</div>
</section>

<?php
get_footer();
