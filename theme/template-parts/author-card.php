<?php
/**
 * The article-footer author card: monogram badge · "Written by" · name →
 * author archive · bio · article count + links. One substantive, linked
 * author block per post (E-E-A-T). Styled via .now-author-card in now.css.
 * Rendered via now_author_card() or
 * get_template_part( 'template-parts/author-card', null, array( 'author' => int ) ).
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$author = ! empty( $args['author'] ) ? (int) $args['author'] : (int) get_post_field( 'post_author', get_the_ID() );
if ( ! $author ) {
	return;
}
$badge = now_user_badge( $author );
$name  = get_the_author_meta( 'display_name', $author );
$url   = get_author_posts_url( $author );
$count = (int) count_user_posts( $author, 'post', true );
$site  = (string) get_the_author_meta( 'url', $author );
?>
<aside class="now-author-card">
	<a class="now-author-card-avatar" href="<?php echo esc_url( $url ); ?>" aria-hidden="true" tabindex="-1" style="position:relative; overflow:hidden; background:<?php echo esc_attr( $badge['grad'] ); ?>"><?php echo esc_html( $badge['mono'] ); echo now_author_avatar_img( $author, 56 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	<div class="now-author-card-body">
		<span class="now-author-card-eyebrow"><?php esc_html_e( 'Written by', 'now-blog' ); ?></span>
		<a class="now-author-card-name" href="<?php echo esc_url( $url ); ?>" rel="author"><?php echo esc_html( $name ); ?></a>
		<p class="now-author-card-bio"><?php echo esc_html( now_author_bio( $author ) ); ?></p>
		<div class="now-author-card-meta">
			<span><?php echo esc_html( sprintf( _n( '%d article', '%d articles', $count, 'now-blog' ), $count ) ); ?></span>
			<span class="now-author-card-dot" aria-hidden="true"></span>
			<a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'All articles', 'now-blog' ); ?> &rarr;</a>
			<?php if ( $site && untrailingslashit( $site ) !== untrailingslashit( home_url( '/' ) ) ) : ?>
				<span class="now-author-card-dot" aria-hidden="true"></span>
				<a href="<?php echo esc_url( $site ); ?>" rel="me nofollow noopener noreferrer"><?php esc_html_e( 'Website', 'now-blog' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</aside>
