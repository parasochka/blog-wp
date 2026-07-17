# CLAUDE.md — NOW theme (classic PHP)

Guidance for AI agents (and humans) working in this repo. Read this first.

## What this is

**NOW** is a custom WordPress **classic PHP theme** for the
[NowPlix blog](https://blog.nowplix.dev). The theme lives in **`theme/`** —
WP Pusher installs from that subdirectory, so docs, tools and CI at the repo
root are never deployed to WordPress.

The **design system is the source of truth for styling.** The Claude Design
"NOW" tokens live in [`theme/_ds/now/tokens/*.css`](./theme/_ds/now) and are
enqueued **as-is** — they are the single place colors, type, spacing, radii,
glows and gradients are defined. There is **no `theme.json` token bridge**
(deliberately removed): edit a token in `theme/_ds/now/tokens/` and the whole
site re-themes.

```
Claude Design "NOW"  ─►  theme/ (_ds/now + PHP)  ─►  GitHub  ─►  WP Pusher  ─►  WordPress
 design-handoff screens    this repo                  (main)     (subdir: theme)  live site
```

## File map (paths relative to `theme/`)

| Path | Purpose |
| --- | --- |
| `style.css` | WP theme header + a pointer note. No real CSS here. |
| `functions.php` | Thin loader: `NOW_VERSION` / `NOW_DS_DIR` constants + `require inc/*`. |
| `inc/setup.php` | Theme supports, the 4 nav locations (`primary`, `footer_platform`, `footer_company`, `footer_legal`), content width, and the back-compat shim mapping the old `page-canvas.php` template slug to `page-templates/canvas.php`. |
| `inc/enqueue.php` | Inlines all first-party CSS into `<head>` (every sheet the `_ds/now/styles.css` manifest `@import`s + `assets/css/now.css` + `style.css`, concatenated at runtime — DS files stay byte-identical, zero render-blocking CSS requests); Google Fonts loads async (preload→stylesheet swap + preconnect); enqueues `assets/js/now.js` deferred. |
| `inc/template-tags.php` | Display helpers: `now_reading_time`, `now_author_badge`/`now_user_badge`, `now_author_avatar_img`, `now_card_excerpt`, `now_link_rel` (external links get `rel="nofollow noopener noreferrer"` automatically), `now_author_bio`, `now_logo_img`, plus the one-liner wrappers `now_render_card()` / `now_author_card()` around the template-parts. |
| `inc/nav.php` | `now_primary_nav` / `now_mobile_nav` (+ fallbacks and walkers — WP menus win, curated markup is the fallback), `now_category_pills`, `now_tag_pills`, `now_footer_sections`, `now_footer_links`. |
| `inc/customizer.php` | `now_theme_defaults()` / `now_mod()` + Customizer basics: header CTA, footer tagline, sidebar promo, inline-related controls (on/off toggle, word interval, max inserts per post). |
| `inc/inline-related.php` | The inline "Keep reading" content inserts (`now_inline_related`). |
| `template-parts/card.php` | The editorial story card — one source of markup for the home rails, archive/tag/author/search grids and "related". Rendered via `now_render_card( $show_excerpt )`. |
| `template-parts/author-card.php` | The article-footer author card (E-E-A-T). Rendered via `now_author_card()`. |
| `header.php` / `footer.php` | Sticky glass masthead / footer. Shared by every template. |
| `home.php` | Blog front page: hero → featured lead → one horizontal rail per category → newsletter. |
| `single.php` | Article: centered head → 21:9 hero → 760px prose + sticky TOC/share sidebar → related. |
| `archive.php` | Generic archive (date, fallback): header + responsive card grid. |
| `tag.php` | Tag archive: `#`-titled header + most-used-tags browse row + card grid. |
| `author.php` | Author profile (E-E-A-T): badge, bio, article count, topics + card grid. |
| `search.php` | Search results (card grid). |
| `page.php` | Standard page (readable prose column). |
| `page-templates/canvas.php` | Template Name **"Full-width canvas"** — edge-to-edge `the_content()`; use for the designed About/Platform pages. |
| `index.php` / `404.php` | Fallback grid / not-found. |
| `_ds/now/tokens/*.css` + `_ds/now/styles.css` | **Design-system source of truth.** Do not fork these values elsewhere. |
| `assets/css/now.css` | Supplemental CSS only: page canvas, `.now-prose`, real `:hover` states, responsive stacking, reduced-motion. References DS tokens. |
| `assets/js/now.js` | Optional progressive JS: rail arrows, article TOC + scrollspy, copy-link. |
| `assets/img/*` | Logo (`logo-now-glass.png`) + 3D glass-render illustrations. |

Repo root (not deployed): `.github/workflows/validate.yml` (CI: `php -l` every
file + required files/tokens present), `tools/uploader/` (content helper),
docs (`README.md`, `MIGRATION.md`, `PRODUCT.md`).

## How the templates are built

Each template is cut from a design-handoff **screen** (`NowBlog-*.dc.html`). The
rule: **keep the screen's HTML and inline styles verbatim; swap only the dynamic
slots** for WordPress calls (`the_title`, `the_content`, `the_post_thumbnail`,
`WP_Query`, `get_category_link`, etc.). This is why the templates carry inline
`style="…"` with `var(--token)` — that is the handoff, preserved 1:1.

- The handoff's preview-only `style-hover="…"` attribute is **not real CSS** —
  every hover/active state is reimplemented in `assets/css/now.css` (keyed by a
  class like `.now-card`, `.now-pill`, `.now-nav-link`, `.now-toc-link`).
- Repeated markup lives once in `template-parts/` (`card.php`,
  `author-card.php`) so the home rails, archive grid, search and "related" stay
  identical. Call sites use the `now_render_card()` / `now_author_card()`
  wrappers from `inc/template-tags.php`.

## How to make design changes (order of preference)

1. **A token value** (color/space/radius/type/glow) → edit
   `theme/_ds/now/tokens/*.css`. One edit re-themes everywhere. This is the
   single source of truth.
2. **A shared style** (prose, hover, responsive) → `theme/assets/css/now.css`,
   always via `var(--…)` tokens — never raw hex/px that duplicates a token.
3. **Layout/structure** → the relevant `theme/*.php`, matching the handoff
   screen. Reference tokens with `var(--…)` inline, exactly as the screens do.

## Design-system rules (non-negotiable)

- **Tokens are the source of truth.** No second copy of a color/space value in
  PHP or `now.css` — reference `var(--primary-500)`, `var(--space-6)`, etc.
- **Fonts:** Archivo Black → display/headings (single weight **400** — never
  700/800). 42dot Sans → body/UI (600 for buttons/labels). Inter → data/tables.
- **Elevation = glow, not shadow.** Use `--elev-*` / `--glow-*`. Amber
  `--accent-400` is reserved for top-priority CTAs (Explore platform, Subscribe).
- **Verify pixel-for-pixel** against the handoff screens
  (`design_handoff_nowplix_wordpress/screens/*.dc.html` in the design package).
- **Layout widths:** content rail `1200px` (`max-width:1200px; padding-inline:24px`
  on every section), article reading column `760px`.

## Content vs code

Code (theme, tokens, templates) is versioned here → push `main` → WP Pusher
pulls. **Content** (posts, pages, categories, menus, media) lives in WordPress,
edited in wp-admin or via the WordPress MCP — never in git. The theme renders
whatever categories/posts exist (nav, pills, footer "Sections" and the home rails
are all built from the live taxonomy).

## Validation & deploy

- CI (`validate.yml`) runs `php -l` on every file and checks required files +
  tokens on each push. Keep it green — WP Pusher ships whatever is on `main`.
- Deploy: WP Pusher (Install/Update Theme from `parasochka/blog-wp`, branch
  `main`, **install from subdirectory: `theme`**). See [`README.md`](./README.md).
- After activating: Site Logo is optional — the theme falls back to the bundled
  `assets/img/logo-now-glass.png`. Set Permalinks to `/%category%/%postname%/`
  and Reading → "your latest posts" so `home.php` drives the front page.

## Re-syncing the design system

When Claude Design re-exports the package, drop the new export's contents over
`theme/_ds/now/` (same path) for a clean diff, and re-cut any screen whose
markup changed. Keeping the `theme/_ds/now/` path stable is what makes the
re-sync a diff, not a manual retranslation.
