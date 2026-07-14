<?php
/**
 * NOW — NowPlix blog. Classic PHP theme.
 *
 * The Claude Design "NOW" design system in _ds/…/tokens/*.css is the single
 * source of truth for styling. Templates reproduce the handoff screens
 * (screens/*.dc.html) verbatim; only dynamic slots become WordPress calls.
 * No design tokens are duplicated into theme.json — edit the token CSS to
 * re-theme the site.
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOW_VERSION', '1.2.1' ); // bump on CSS/JS changes — busts the ?ver= asset cache.
define( 'NOW_DS_DIR', '_ds/nowplix-igaming-design-system-5bcffbc0-c8f0-442c-b6f3-9be1de820175' );

/* ------------------------------------------------------------------ *
 * Theme setup
 * ------------------------------------------------------------------ */
function now_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );

	// The glass "now" wordmark. A wide lockup (~3.45:1); no forced crop.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 52,
			'width'       => 180,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary'         => __( 'Primary (header)', 'now-blog' ),
			'footer_platform' => __( 'Footer — Platform column', 'now-blog' ),
			'footer_company'  => __( 'Footer — Company column', 'now-blog' ),
			'footer_legal'    => __( 'Footer — Legal (Terms / Privacy)', 'now-blog' ),
		)
	);

	load_theme_textdomain( 'now-blog', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'now_setup' );

if ( ! isset( $content_width ) ) {
	$content_width = 760;
}

/* ------------------------------------------------------------------ *
 * Assets — design-system tokens (as-is) + supplemental theme CSS
 * ------------------------------------------------------------------ */
function now_enqueue_assets() {
	// 1) Design system: one entry point that @imports fonts + all tokens.
	//    This is the ONLY source of truth for colors / type / spacing / etc.
	wp_enqueue_style(
		'now-ds',
		get_theme_file_uri( NOW_DS_DIR . '/styles.css' ),
		array(),
		NOW_VERSION
	);

	// 2) Supplemental theme CSS (prose, hovers, body halo, responsive).
	wp_enqueue_style(
		'now-theme',
		get_theme_file_uri( 'assets/css/now.css' ),
		array( 'now-ds' ),
		NOW_VERSION
	);

	// 3) The stylesheet handle WordPress expects (header only).
	wp_enqueue_style( 'now-style', get_stylesheet_uri(), array( 'now-theme' ), NOW_VERSION );

	// 4) Progressive article enhancements (TOC scrollspy, share, rail arrows).
	wp_enqueue_script(
		'now-script',
		get_theme_file_uri( 'assets/js/now.js' ),
		array(),
		NOW_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'now_enqueue_assets' );

// Preconnect to Google Fonts (fonts.css @imports them).
function now_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = 'https://fonts.googleapis.com';
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'now_resource_hints', 10, 2 );

/* ------------------------------------------------------------------ *
 * Design helpers — reproduce the dynamic bits of the handoff screens
 * ------------------------------------------------------------------ */

/**
 * Unicode-aware word count — str_word_count() only sees ASCII words, which
 * silently returns 0 for Cyrillic/Greek/CJK content.
 */
function now_word_count( $text ) {
	return (int) preg_match_all( '/[\p{L}\p{N}][\p{L}\p{N}\'’-]*/u', (string) $text );
}

/**
 * Estimated reading time, e.g. "8 min". ~200 wpm, minimum 1.
 */
function now_reading_time( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = now_word_count( wp_strip_all_tags( (string) $content ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	/* translators: %d: minutes to read. */
	return sprintf( _n( '%d min', '%d min', $minutes, 'now-blog' ), $minutes );
}

/**
 * Author "badge": monogram initials + a deterministic brand gradient,
 * matching the avatar chips in the design.
 *
 * @return array{mono:string,grad:string}
 */
function now_author_badge( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return now_user_badge( (int) get_post_field( 'post_author', $post_id ) );
}

/**
 * The same monogram badge keyed by user ID directly — for author archives and
 * author cards, where there is no post in scope.
 *
 * @return array{mono:string,grad:string}
 */
function now_user_badge( $author ) {
	$author = (int) $author;
	$name   = get_the_author_meta( 'display_name', $author );

	$mono = '';
	foreach ( preg_split( '/\s+/', trim( (string) $name ) ) as $part ) {
		if ( '' !== $part ) {
			$mono .= strtoupper( mb_substr( $part, 0, 1 ) );
		}
		if ( strlen( $mono ) >= 2 ) {
			break;
		}
	}
	$mono = $mono ? $mono : 'NP';

	$grads = array(
		'linear-gradient(135deg,#8A74FF,#5149E6)',
		'linear-gradient(135deg,#6B55F5,#0F0F40)',
		'linear-gradient(135deg,#FFC05E,#7060F0)',
		'linear-gradient(135deg,#B09BFF,#FFAC34)',
		'linear-gradient(135deg,#8A74FF,#3B34C0)',
		'linear-gradient(135deg,#7060F0,#0F0F40)',
	);

	return array(
		'mono' => $mono,
		'grad' => $grads[ $author % count( $grads ) ],
	);
}

/**
 * Gravatar <img> overlay for an author avatar chip. Layers the admin-set
 * Gravatar on top of the monogram badge: `default=blank` returns a transparent
 * image for authors with no Gravatar, so the monogram gradient shows through —
 * the design's fallback is preserved 1:1, while a real Gravatar simply appears.
 *
 * Drop the return value inside a circular chip that is `position:relative;
 * overflow:hidden` (the six badge chips across the templates). Returns '' when
 * avatars are disabled (Settings → Discussion) so the monogram stands alone.
 *
 * @param int $author User ID.
 * @param int $size   Rendered pixel size (2× is requested from Gravatar for HiDPI).
 * @return string <img> HTML, or '' if avatars are off / no URL.
 */
function now_author_avatar_img( $author, $size ) {
	$author = (int) $author;
	$size   = (int) $size;
	$url    = get_avatar_url(
		$author,
		array(
			'size'    => $size * 2,
			'default' => 'blank',
		)
	);
	if ( ! $url ) {
		return '';
	}
	return sprintf(
		'<img src="%1$s" alt="" aria-hidden="true" loading="lazy" decoding="async" width="%2$d" height="%2$d" style="position:absolute; inset:0; width:100%%; height:100%%; object-fit:cover; display:block; border-radius:inherit" />',
		esc_url( $url ),
		$size
	);
}

/**
 * A short, clamp-friendly excerpt (the card CSS clamps to 2 lines).
 */
function now_card_excerpt( $words = 24 ) {
	return wp_trim_words( get_the_excerpt(), $words, '…' );
}

/**
 * Render one editorial story card (image · category · title · [excerpt] · meta).
 * Must be called inside the loop. Markup mirrors screens/*.dc.html verbatim;
 * only the dynamic slots are WordPress calls.
 */
function now_render_card( $show_excerpt = true ) {
	$pid    = get_the_ID();
	$author = (int) get_post_field( 'post_author', $pid );
	$badge  = now_author_badge( $pid );
	$cats   = get_the_category( $pid );
	$cat   = ! empty( $cats ) ? $cats[0] : null;
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
	<?php
}

/**
 * rel="" for a menu/footer link. External hosts get nofollow + noopener +
 * noreferrer automatically; add the CSS class `follow` to a menu item in
 * Appearance → Menus to opt a link out of nofollow, or `nofollow` to force it
 * on an internal link. XFN rel values set in the admin are preserved.
 */
function now_link_rel( $url, $classes = array(), $xfn = '' ) {
	static $home = null;
	if ( null === $home ) {
		$home = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
	}
	$rel     = array_filter( array_map( 'trim', explode( ' ', (string) $xfn ) ) );
	$classes = (array) $classes;
	$host    = wp_parse_url( (string) $url, PHP_URL_HOST );

	$external = $host && 0 !== strcasecmp( $host, (string) $home );
	if ( ( $external && ! in_array( 'follow', $classes, true ) ) || in_array( 'nofollow', $classes, true ) ) {
		$rel[] = 'nofollow';
	}
	if ( $external ) {
		$rel[] = 'noopener';
		$rel[] = 'noreferrer';
	}
	return implode( ' ', array_unique( $rel ) );
}

/**
 * The menu to render for a location: the admin-assigned menu if the location
 * is set, else a conventionally-slugged menu (so menus managed over MCP are
 * picked up without touching Appearance → Menus), else null → theme fallback.
 *
 * @return WP_Term|null
 */
function now_menu_for_location( $location, $slug ) {
	static $cache = array();
	if ( array_key_exists( $location, $cache ) ) {
		return $cache[ $location ];
	}
	$menu      = null;
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations[ $location ] ) ) {
		$menu = wp_get_nav_menu_object( $locations[ $location ] );
	}
	if ( ! $menu ) {
		$menu = wp_get_nav_menu_object( $slug );
	}
	$cache[ $location ] = $menu ? $menu : null;
	return $cache[ $location ];
}

/**
 * Walker: header nav from a WP menu, matching the handoff markup — top-level
 * `.now-nav-link` anchors; an item with children becomes the `.now-nav-item`
 * hover dropdown (`.now-dropdown-grid` of name + blurb links).
 */
class Now_Walker_Nav extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<div class="now-dropdown"><div class="now-dropdown-grid">';
	}
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</div></div>';
	}
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes      = (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$is_current   = (bool) array_intersect( array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' ), $classes );
		$rel          = now_link_rel( $item->url, $classes, $item->xfn );
		$attrs        = ( $item->target ? ' target="' . esc_attr( $item->target ) . '"' : '' ) . ( $rel ? ' rel="' . esc_attr( $rel ) . '"' : '' );
		$url          = (string) $item->url;

		if ( 0 === $depth ) {
			if ( $has_children ) {
				// '#' custom items are pure toggles; real URLs stay clickable.
				$href    = ( '' === $url || '#' === $url ) ? ' tabindex="0" role="button"' : ' href="' . esc_url( $url ) . '"' . $attrs;
				$output .= '<div class="now-nav-item">';
				$output .= '<a class="now-nav-link now-dropdown-toggle' . ( $is_current ? ' now-active' : '' ) . '"' . $href . ' aria-haspopup="true" aria-expanded="false">' . esc_html( $item->title ) . ' <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></a>';
			} else {
				$output .= '<a class="now-nav-link' . ( $is_current ? ' now-active' : '' ) . '" href="' . esc_url( $url ) . '"' . $attrs . '>' . esc_html( $item->title ) . '</a>';
			}
			return;
		}

		$blurb = trim( wp_strip_all_tags( (string) $item->description ) );
		if ( '' === $blurb && 'category' === $item->object ) {
			$blurb = trim( wp_strip_all_tags( (string) term_description( (int) $item->object_id ) ) );
		}
		$output .= '<a class="now-dropdown-link" href="' . esc_url( $url ) . '"' . $attrs . '><span class="now-dropdown-name">' . esc_html( $item->title ) . '</span>'
			. ( $blurb ? '<span class="now-dropdown-blurb">' . esc_html( $blurb ) . '</span>' : '' ) . '</a>';
	}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( 0 === $depth && in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
			$output .= '</div>';
		}
	}
}

/**
 * Walker: the same menu flattened for the burger panel — every destination
 * becomes one tap-friendly `.now-mobile-link`; '#' toggle rows are dropped
 * (their children are promoted to the flat list).
 */
class Now_Walker_Mobile extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$url = (string) $item->url;
		if ( '' === $url || '#' === $url ) {
			return;
		}
		$rel     = now_link_rel( $url, (array) $item->classes, $item->xfn );
		$attrs   = ( $item->target ? ' target="' . esc_attr( $item->target ) . '"' : '' ) . ( $rel ? ' rel="' . esc_attr( $rel ) . '"' : '' );
		$output .= '<a class="now-mobile-link" href="' . esc_url( $url ) . '"' . $attrs . '>' . esc_html( $item->title ) . '</a>';
	}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}

/**
 * Primary navigation. Renders the WP menu assigned to the `primary` location
 * (or slugged `main-menu`) so nav is managed in Appearance → Menus; falls back
 * to the curated Home · Categories · About · Platform menu when none exists.
 */
function now_primary_nav() {
	$menu = now_menu_for_location( 'primary', 'main-menu' );
	if ( $menu ) {
		wp_nav_menu(
			array(
				'menu'        => $menu,
				'container'   => false,
				'items_wrap'  => '%3$s',
				'depth'       => 2,
				'walker'      => new Now_Walker_Nav(),
				'fallback_cb' => false,
			)
		);
		return;
	}
	now_primary_nav_fallback();
}

/**
 * Curated fallback nav (no WP menu exists): Home · Categories (a dropdown
 * listing every live category) · About · Platform. Styled via .now-nav in
 * now.css. Pages resolve by slug (about / platform) with a sensible fallback.
 */
function now_primary_nav_fallback() {
	$home_active = ( is_front_page() || is_home() ) ? ' now-active' : '';
	printf(
		'<a class="now-nav-link%s" href="%s">%s</a>',
		esc_attr( $home_active ),
		esc_url( home_url( '/' ) ),
		esc_html__( 'Home', 'now-blog' )
	);

	// Categories dropdown — every non-empty category with its blurb.
	$cats = get_categories(
		array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		)
	);
	if ( ! empty( $cats ) ) {
		$cat_active = is_category() ? ' now-active' : '';
		echo '<div class="now-nav-item">';
		printf(
			'<a class="now-nav-link now-dropdown-toggle%s" tabindex="0" role="button" aria-haspopup="true" aria-expanded="false">%s <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></a>',
			esc_attr( $cat_active ),
			esc_html__( 'Categories', 'now-blog' )
		);
		echo '<div class="now-dropdown"><div class="now-dropdown-grid">';
		foreach ( $cats as $c ) {
			$blurb = trim( wp_strip_all_tags( $c->description ) );
			printf(
				'<a class="now-dropdown-link" href="%s"><span class="now-dropdown-name">%s</span>%s</a>',
				esc_url( get_category_link( $c ) ),
				esc_html( $c->name ),
				$blurb ? '<span class="now-dropdown-blurb">' . esc_html( $blurb ) . '</span>' : ''
			);
		}
		echo '</div></div></div>';
	}

	// Pages (resolve by slug; fall back to the conventional path).
	$about    = get_page_by_path( 'about' );
	$platform = get_page_by_path( 'platform' );
	printf(
		'<a class="now-nav-link" href="%s">%s</a>',
		esc_url( $about ? get_permalink( $about ) : home_url( '/about/' ) ),
		esc_html__( 'About', 'now-blog' )
	);
	printf(
		'<a class="now-nav-link" href="%s">%s</a>',
		esc_url( $platform ? get_permalink( $platform ) : home_url( '/platform/' ) ),
		esc_html__( 'Platform', 'now-blog' )
	);
}

/**
 * Mobile navigation — a flat, tap-friendly list shown inside the burger menu.
 * Uses the same WP menu as the header (flattened by Now_Walker_Mobile); falls
 * back to Home, every live category, then About · Platform.
 */
function now_mobile_nav() {
	$menu = now_menu_for_location( 'primary', 'main-menu' );
	if ( $menu ) {
		echo '<nav class="now-mobile-links" aria-label="' . esc_attr__( 'Mobile', 'now-blog' ) . '">';
		wp_nav_menu(
			array(
				'menu'        => $menu,
				'container'   => false,
				'items_wrap'  => '%3$s',
				'depth'       => 2,
				'walker'      => new Now_Walker_Mobile(),
				'fallback_cb' => false,
			)
		);
		echo '</nav>';
		return;
	}
	now_mobile_nav_fallback();
}

/**
 * Curated fallback for the burger panel (no WP menu exists).
 */
function now_mobile_nav_fallback() {
	echo '<nav class="now-mobile-links" aria-label="' . esc_attr__( 'Mobile', 'now-blog' ) . '">';

	printf(
		'<a class="now-mobile-link" href="%s">%s</a>',
		esc_url( home_url( '/' ) ),
		esc_html__( 'Home', 'now-blog' )
	);

	$cats = get_categories(
		array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		)
	);
	foreach ( $cats as $c ) {
		printf(
			'<a class="now-mobile-link" href="%s">%s</a>',
			esc_url( get_category_link( $c ) ),
			esc_html( $c->name )
		);
	}

	$about    = get_page_by_path( 'about' );
	$platform = get_page_by_path( 'platform' );
	printf(
		'<a class="now-mobile-link" href="%s">%s</a>',
		esc_url( $about ? get_permalink( $about ) : home_url( '/about/' ) ),
		esc_html__( 'About', 'now-blog' )
	);
	printf(
		'<a class="now-mobile-link" href="%s">%s</a>',
		esc_url( $platform ? get_permalink( $platform ) : home_url( '/platform/' ) ),
		esc_html__( 'Platform', 'now-blog' )
	);

	echo '</nav>';
}

/**
 * Category "pills" browse row (hero + archive header). $active highlights the
 * current category. Inline styles mirror the handoff; hover via .now-pill.
 */
function now_category_pills( $active = 0 ) {
	$cats = get_categories(
		array(
			'orderby'    => 'term_id',
			'order'      => 'ASC',
			'hide_empty' => true,
		)
	);
	foreach ( $cats as $c ) {
		$is     = ( $active && (int) $active === (int) $c->term_id );
		$border = $is ? 'var(--primary-500)' : 'var(--border)';
		$bg     = $is ? 'color-mix(in srgb, var(--primary-500) 16%, var(--surface-card))' : 'var(--surface-card)';
		$color  = $is ? '#fff' : 'var(--text-secondary)';
		printf(
			'<a class="now-pill" href="%1$s" style="padding:8px 16px; border-radius:999px; border:1px solid %2$s; background:%3$s; color:%4$s; font-family:var(--font-body); font-weight:500; font-size:13px">%5$s</a>',
			esc_url( get_category_link( $c ) ),
			esc_attr( $border ),
			esc_attr( $bg ),
			esc_attr( $color ),
			esc_html( $c->name )
		);
	}
}

/**
 * Tag "pills" row — the article-footer "Tagged" block and the tag-archive
 * browse row. Same pill DNA as now_category_pills (inline styles mirror the
 * handoff; hover via .now-pill) with a brand "#" prefix so tags read as tags.
 * $tags is an array of WP_Term; $active highlights the current tag.
 */
function now_tag_pills( $tags, $active = 0 ) {
	foreach ( (array) $tags as $t ) {
		if ( ! ( $t instanceof WP_Term ) ) {
			continue;
		}
		$is     = ( $active && (int) $active === (int) $t->term_id );
		$border = $is ? 'var(--primary-500)' : 'var(--border)';
		$bg     = $is ? 'color-mix(in srgb, var(--primary-500) 16%, var(--surface-card))' : 'var(--surface-card)';
		$color  = $is ? '#fff' : 'var(--text-secondary)';
		printf(
			'<a class="now-pill" href="%1$s" style="padding:8px 16px; border-radius:999px; border:1px solid %2$s; background:%3$s; color:%4$s; font-family:var(--font-body); font-weight:500; font-size:13px"><span style="color:var(--text-brand); margin-right:2px">#</span>%5$s</a>',
			esc_url( get_term_link( $t ) ),
			esc_attr( $border ),
			esc_attr( $bg ),
			esc_attr( $color ),
			esc_html( $t->name )
		);
	}
}

/**
 * Author bio for the author card / author page. The wp-admin profile
 * "Biographical Info" is the source; a designed default keeps the card
 * substantive (E-E-A-T) until the profile is filled in.
 */
function now_author_bio( $author ) {
	$bio = trim( (string) get_the_author_meta( 'description', (int) $author ) );
	if ( '' !== $bio ) {
		return $bio;
	}
	return sprintf(
		/* translators: %s: author display name. */
		__( '%s writes about the iGaming industry on the NowPlix blog — launch playbooks, platform economics, market entry, compliance and player retention, drawn from work on the NowPlix platform.', 'now-blog' ),
		get_the_author_meta( 'display_name', (int) $author )
	);
}

/**
 * The article-footer author card: monogram badge · "Written by" · name →
 * author archive · bio · article count + links. One substantive, linked
 * author block per post (E-E-A-T). Styled via .now-author-card in now.css.
 */
function now_author_card( $author = 0 ) {
	$author = $author ? (int) $author : (int) get_post_field( 'post_author', get_the_ID() );
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
	<?php
}

/**
 * Footer "Sections" column — the site's categories.
 */
function now_footer_sections() {
	$cats = get_categories(
		array(
			'orderby'    => 'term_id',
			'order'      => 'ASC',
			'hide_empty' => true,
		)
	);
	foreach ( $cats as $c ) {
		printf(
			'<a class="now-foot-link" href="%s" style="display:block; color:var(--text-secondary); font-family:var(--font-body); font-size:14px; padding:5px 0">%s</a>',
			esc_url( get_category_link( $c ) ),
			esc_html( $c->name )
		);
	}
}

/**
 * One footer link column (Platform / Company / Legal). Rendered from the WP
 * menu assigned to $location (or slugged $slug) so labels, URLs, targets and
 * follow/nofollow are managed in Appearance → Menus; $fallback (label => url)
 * keeps the designed defaults when no menu exists. External links get
 * rel="nofollow noopener noreferrer" automatically (see now_link_rel).
 */
function now_footer_links( $location, $slug, $fallback, $inline = false ) {
	$style = $inline
		? 'color:var(--text-muted)'
		: 'display:block; color:var(--text-secondary); font-family:var(--font-body); font-size:14px; padding:5px 0';

	$menu = now_menu_for_location( $location, $slug );
	if ( $menu ) {
		// A menu that exists but was deliberately emptied stays empty —
		// don't resurrect the designed defaults over the editor's choice.
		$items = wp_get_nav_menu_items( $menu );
		foreach ( (array) $items as $item ) {
			$rel = now_link_rel( $item->url, (array) $item->classes, $item->xfn );
			printf(
				'<a class="now-foot-link" href="%s"%s%s style="%s">%s</a>',
				esc_url( $item->url ),
				$item->target ? ' target="' . esc_attr( $item->target ) . '"' : '',
				$rel ? ' rel="' . esc_attr( $rel ) . '"' : '',
				esc_attr( $style ),
				esc_html( $item->title )
			);
		}
		return;
	}

	foreach ( $fallback as $label => $url ) {
		$rel = now_link_rel( $url );
		printf(
			'<a class="now-foot-link" href="%s"%s%s style="%s">%s</a>',
			esc_url( $url ),
			$rel ? ' target="_blank"' : '', // new tab only for external hosts
			$rel ? ' rel="' . esc_attr( $rel ) . '"' : '',
			esc_attr( $style ),
			esc_html( $label )
		);
	}
}

/**
 * The glass wordmark lockup used in the header/footer: the custom logo if the
 * site owner set one, else the bundled glass PNG. $height in px.
 */
function now_logo_img( $height = 26 ) {
	$alt = get_bloginfo( 'name' );
	if ( has_custom_logo() ) {
		$id  = (int) get_theme_mod( 'custom_logo' );
		$src = wp_get_attachment_image_url( $id, 'full' );
		if ( $src ) {
			return sprintf(
				'<img src="%s" alt="%s" style="height:%dpx; width:auto; display:block"/>',
				esc_url( $src ),
				esc_attr( $alt ),
				(int) $height
			);
		}
	}
	return sprintf(
		'<img src="%s" alt="%s" style="height:%dpx; width:auto; display:block"/>',
		esc_url( get_theme_file_uri( 'assets/img/logo-now-glass.png' ) ),
		esc_attr( $alt ),
		(int) $height
	);
}

/**
 * Trim the auto-excerpt "read more" to a plain ellipsis.
 */
function now_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'now_excerpt_more' );

/* ------------------------------------------------------------------ *
 * Customizer — the theme's editable basics (Appearance → Customize → NOW)
 * ------------------------------------------------------------------ */

/**
 * Defaults mirror the approved design so the site reads identically until a
 * value is changed in the Customizer.
 */
function now_theme_defaults() {
	static $defaults = null;
	if ( null !== $defaults ) {
		return $defaults;
	}
	$defaults = array(
		'now_cta_label'      => __( 'Explore platform', 'now-blog' ),
		'now_cta_url'        => 'https://nowplix.com/about/contact',
		'now_footer_tagline' => __( 'Signals from the future of iGaming — product, design and engineering stories from the NowPlix platform.', 'now-blog' ),
		'now_promo_title'    => __( 'Play on NowPlix', 'now-blog' ),
		'now_promo_text'     => __( 'Casino, sportsbook and the tech behind them. See what the platform can do.', 'now-blog' ),
		'now_promo_button'   => __( 'Explore the platform', 'now-blog' ),
		'now_promo_url'      => 'https://nowplix.com/about/contact',
		'now_inline_related' => true,
		'now_inline_related_interval' => 300,
		'now_inline_related_max' => 2,
	);
	return $defaults;
}

/**
 * get_theme_mod with the design defaults baked in.
 */
function now_mod( $key ) {
	$defaults = now_theme_defaults();
	return get_theme_mod( $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

function now_customize_register( $wp_customize ) {
	$d = now_theme_defaults();

	$wp_customize->add_section(
		'now_theme',
		array(
			'title'    => __( 'NOW theme', 'now-blog' ),
			'priority' => 30,
		)
	);

	$fields = array(
		'now_cta_label'      => array( __( 'Header CTA — label', 'now-blog' ), 'text', 'sanitize_text_field' ),
		'now_cta_url'        => array( __( 'Header CTA — URL', 'now-blog' ), 'url', 'esc_url_raw' ),
		'now_footer_tagline' => array( __( 'Footer tagline', 'now-blog' ), 'textarea', 'sanitize_textarea_field' ),
		'now_promo_title'    => array( __( 'Article sidebar promo — title', 'now-blog' ), 'text', 'sanitize_text_field' ),
		'now_promo_text'     => array( __( 'Article sidebar promo — text', 'now-blog' ), 'textarea', 'sanitize_textarea_field' ),
		'now_promo_button'   => array( __( 'Article sidebar promo — button label', 'now-blog' ), 'text', 'sanitize_text_field' ),
		'now_promo_url'      => array( __( 'Article sidebar promo — button URL', 'now-blog' ), 'url', 'esc_url_raw' ),
		'now_inline_related' => array( __( 'Weave "Keep reading" story cards into long articles', 'now-blog' ), 'checkbox', 'rest_sanitize_boolean' ),
		'now_inline_related_interval' => array( __( '"Keep reading" — words between inserts', 'now-blog' ), 'number', 'absint', array( 'min' => 100, 'max' => 2000, 'step' => 50 ) ),
		'now_inline_related_max' => array( __( '"Keep reading" — max inserts per article', 'now-blog' ), 'number', 'absint', array( 'min' => 1, 'max' => 5 ) ),
	);

	foreach ( $fields as $key => $field ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => $d[ $key ],
				'sanitize_callback' => $field[2],
			)
		);
		$control = array(
			'label'   => $field[0],
			'section' => 'now_theme',
			'type'    => $field[1],
		);
		if ( isset( $field[3] ) ) {
			$control['input_attrs'] = $field[3];
		}
		$wp_customize->add_control( $key, $control );
	}
}
add_action( 'customize_register', 'now_customize_register' );

/* ------------------------------------------------------------------ *
 * Inline "Keep reading" inserts — related stories woven into the prose
 * ------------------------------------------------------------------ */

/**
 * Related stories for the inline inserts: same tags first, then the latest
 * from the post's categories, then the latest site-wide — so the module
 * renders on every article even when tags don't intersect. Returns at most
 * $count posts, never the current one.
 *
 * @return WP_Post[]
 */
function now_inline_related_posts( $post_id, $count = 2 ) {
	$base = array(
		'posts_per_page'      => $count,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$posts = array();
	$tags  = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );
	if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
		$posts = get_posts( $base + array( 'tag__in' => $tags ) );
	}

	if ( count( $posts ) < $count ) {
		$cats = wp_get_post_categories( $post_id );
		if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
			$exclude = array_merge( array( $post_id ), wp_list_pluck( $posts, 'ID' ) );
			$more    = get_posts(
				array(
					'posts_per_page'      => $count - count( $posts ),
					'post__not_in'        => $exclude,
					'category__in'        => $cats,
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
				)
			);
			$posts = array_merge( $posts, $more );
		}
	}

	// Last resort: the freshest stories site-wide, so no article goes without.
	if ( count( $posts ) < $count ) {
		$exclude = array_merge( array( $post_id ), wp_list_pluck( $posts, 'ID' ) );
		$more    = get_posts(
			array(
				'posts_per_page'      => $count - count( $posts ),
				'post__not_in'        => $exclude,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		$posts = array_merge( $posts, $more );
	}

	return $posts;
}

/**
 * One inline related-story banner: square thumb · eyebrow/title/blurb · amber
 * "Read more" CTA. Compact horizontal strip with the brand ring (--elev-2),
 * borrowing the sidebar-promo look; styled via .now-inline-read in now.css.
 */
function now_inline_related_card( $rel ) {
	$link  = get_permalink( $rel );
	$thumb = get_the_post_thumbnail(
		$rel,
		'thumbnail',
		array(
			'loading' => 'lazy',
			'alt'     => get_the_title( $rel ),
		)
	);
	$blurb = wp_trim_words( get_the_excerpt( $rel ), 20, '…' );

	$html  = '<aside class="now-inline-read">';
	if ( $thumb ) {
		$html .= '<a class="now-inline-read-thumb" href="' . esc_url( $link ) . '" tabindex="-1" aria-hidden="true">' . $thumb . '</a>';
	}
	$html .= '<div class="now-inline-read-body">';
	$html .= '<span class="now-inline-read-eyebrow">' . esc_html__( 'Keep reading', 'now-blog' ) . '</span>';
	$html .= '<a class="now-inline-read-title" href="' . esc_url( $link ) . '">' . esc_html( get_the_title( $rel ) ) . '</a>';
	if ( $blurb ) {
		$html .= '<p class="now-inline-read-desc">' . esc_html( $blurb ) . '</p>';
	}
	$html .= '</div>';
	$html .= '<a class="now-inline-read-btn" href="' . esc_url( $link ) . '">' . esc_html__( 'Read more', 'now-blog' ) . '</a>';
	$html .= '</aside>';

	return $html;
}

/**
 * Weave "Keep reading" banners into long article bodies — one per N words
 * and at most M per post (both editable in the Customizer, 300 words / 2
 * inserts by default), always after a closed paragraph that is followed by
 * another paragraph (never straight before a heading, list or figure).
 */
function now_inline_related( $content ) {
	if ( ! now_mod( 'now_inline_related' ) || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() || post_password_required() ) {
		return $content;
	}

	$blocks = preg_split( '/(<\/p>)/i', $content, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( false === $blocks || count( $blocks ) < 5 ) {
		return $content;
	}

	/** Interval (words between inserts) and cap — Customizer values, still filterable. */
	$interval = max( 100, (int) apply_filters( 'now_inline_related_interval', (int) now_mod( 'now_inline_related_interval' ) ) );
	$max      = max( 1, (int) apply_filters( 'now_inline_related_max', (int) now_mod( 'now_inline_related_max' ) ) );

	$related = now_inline_related_posts( get_the_ID(), $max );
	if ( empty( $related ) ) {
		return $content;
	}

	$out   = '';
	$words = 0;
	$used  = 0;

	foreach ( $blocks as $i => $chunk ) {
		$out .= $chunk;
		if ( $used >= count( $related ) ) {
			continue; // all cards placed — just pass the rest through.
		}
		if ( '</p>' !== strtolower( $chunk ) ) {
			$words += now_word_count( wp_strip_all_tags( $chunk ) );
			continue;
		}
		$next = isset( $blocks[ $i + 1 ] ) ? ltrim( $blocks[ $i + 1 ] ) : '';
		if ( $words >= $interval && 0 === stripos( $next, '<p' ) ) { // only between two paragraphs
			$out .= now_inline_related_card( $related[ $used ] );
			$used++;
			$words = 0;
		}
	}

	return $out;
}
add_filter( 'the_content', 'now_inline_related', 20 );
