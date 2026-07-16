<?php
/**
 * Navigation — header/mobile nav (+ walkers), category/tag pills, footer links.
 *
 * @package now-blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
 * Icon for a category row in the mega-menu, keyed by slug/name keywords (the
 * handoff assigns one feather icon per section). Unknown categories get a
 * neutral folder. Returns a full inline <svg> string (trusted static markup).
 */
function now_category_icon( $label ) {
	$key   = strtolower( remove_accents( (string) $label ) );
	$icons = array(
		'acquisition' => '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>',
		'compliance'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>',
		'econom'      => '<line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/>',
		'insight'     => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
		'launch'      => '<line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/>',
		'market'      => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
		'retention'   => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'crm'         => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'ai'          => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
		'tech'        => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
	);
	$body  = '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>'; // folder default
	foreach ( $icons as $needle => $svg ) {
		if ( false !== strpos( $key, $needle ) ) {
			$body = $svg;
			break;
		}
	}
	return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $body . '</svg>';
}

/**
 * Walker: header nav from a WP menu, matching the handoff markup — top-level
 * `.now-nav-link` anchors; an item with children becomes the `.now-nav-item`
 * hover mega-menu (`.now-mega-grid` of icon-tile links: icon chip + name +
 * blurb, per the NowBlog handoff header).
 */
class Now_Walker_Nav extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<div class="now-dropdown now-mega"><div class="now-mega-grid">';
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
		$output .= '<a class="now-mega-link" href="' . esc_url( $url ) . '"' . $attrs . '>'
			. '<span class="now-mega-icon">' . now_category_icon( $item->title ) . '</span>'
			. '<span class="now-mega-text"><span class="now-dropdown-name">' . esc_html( $item->title ) . '</span>'
			. ( $blurb ? '<span class="now-dropdown-blurb">' . esc_html( $blurb ) . '</span>' : '' ) . '</span></a>';
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
		echo '<div class="now-dropdown now-mega"><div class="now-mega-grid">';
		foreach ( $cats as $c ) {
			$blurb = trim( wp_strip_all_tags( $c->description ) );
			printf(
				'<a class="now-mega-link" href="%s"><span class="now-mega-icon">%s</span><span class="now-mega-text"><span class="now-dropdown-name">%s</span>%s</span></a>',
				esc_url( get_category_link( $c ) ),
				now_category_icon( $c->name ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG markup.
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
