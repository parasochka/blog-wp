<?php
/**
 * One editorial story card (image · category · title · [excerpt] · meta).
 * Must be rendered inside the loop — via now_render_card() or
 * get_template_part( 'template-parts/card', null, array( 'show_excerpt' => bool ) ).
 * Markup mirrors screens/*.dc.html verbatim; only the dynamic slots are
 * WordPress calls.
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_excerpt = ! isset( $args['show_excerpt'] ) || $args['show_excerpt'];

$pid    = get_the_ID();
$author = (int) get_post_field( 'post_author', $pid );
$badge  = now_author_badge( $pid );
$cats   = get_the_category( $pid );
$cat    = ! empty( $cats ) ? $cats[0] : null;
?>
<article class="now-card" style="display:flex; flex-direction:column; gap:12px; scroll-snap-align:start">
	<a class="now-card-media" href="<?php the_permalink(); ?>" style="position:relative; display:block; overflow:hidden; border-radius:var(--radius-lg); background:var(--bg-page-deep); aspect-ratio:16/9">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				'large',
				array(
					'loading' => 'lazy',
					'style'   => 'width:100%; height:100%; object-fit:cover; display:block',
					'alt'     => the_title_attribute( array( 'echo' => false ) ),
				)
			);
		}
		?>
	</a>
	<?php if ( $cat ) : ?>
		<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" style="font-family:var(--font-display); font-weight:400; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:var(--text-brand)"><?php echo esc_html( $cat->name ); ?></a>
	<?php endif; ?>
	<h3 style="font-family:var(--font-display); font-weight:400; font-size:19px; line-height:1.22; color:var(--text-primary); margin:0"><a class="now-card-title" href="<?php the_permalink(); ?>" style="color:inherit"><?php the_title(); ?></a></h3>
	<?php if ( $show_excerpt && get_the_excerpt() ) : ?>
		<p style="font-size:14px; color:var(--text-secondary); margin:0; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden"><?php echo esc_html( now_card_excerpt() ); ?></p>
	<?php endif; ?>
	<div style="display:flex; align-items:center; gap:12px; color:var(--text-muted); font-size:13px">
		<span style="position:relative; overflow:hidden; width:22px; height:22px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:9px; background:<?php echo esc_attr( $badge['grad'] ); ?>"><?php echo esc_html( $badge['mono'] ); echo now_author_avatar_img( $author, 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<span><?php echo esc_html( get_the_date() ); ?></span>
		<span style="width:3px; height:3px; border-radius:50%; background:var(--text-muted)"></span>
		<span><?php echo esc_html( now_reading_time( $pid ) ); ?></span>
	</div>
</article>
