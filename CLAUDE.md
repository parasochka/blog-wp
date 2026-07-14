# CLAUDE.md — NOW theme (classic PHP)

Guidance for AI agents (and humans) working in this repo. Read this first.

## What this is

**NOW** is a custom WordPress **classic PHP theme** for the
[NowPlix blog](https://blog.nowplix.dev). The **repo root IS the theme folder** —
`style.css`, `functions.php` and the `*.php` templates sit at the root.

The **design system is the source of truth for styling.** The Claude Design
"NOW" tokens live in [`_ds/…/tokens/*.css`](./_ds) and are enqueued **as-is** —
they are the single place colors, type, spacing, radii, glows and gradients are
defined. There is **no `theme.json` token bridge** (deliberately removed): edit a
token in `_ds/…/tokens/` and the whole site re-themes.

```
Claude Design "NOW"  ─►  _ds/tokens + PHP templates  ─►  GitHub  ─►  WP Pusher  ─►  WordPress
 design-handoff screens    this repo (the theme)          (main)     (pull in wp-admin)  live site
```

## File map

| Path | Purpose |
| --- | --- |
| `style.css` | WP theme header + a pointer note. No real CSS here. |
| `functions.php` | Enqueue tokens/CSS/JS, theme supports, nav menus (4 locations: `primary`, `footer_platform`, `footer_company`, `footer_legal` — WP menus win, curated markup is the fallback; external links get `rel="nofollow noopener noreferrer"` automatically via `now_link_rel()`), Customizer basics (header CTA, footer tagline, sidebar promo, inline-related controls: on/off toggle, word interval, max inserts per post — see `now_theme_defaults()`), the inline "Keep reading" content inserts (`now_inline_related`), and the design **helpers** (`now_render_card`, `now_reading_time`, `now_author_badge`, `now_primary_nav`, `now_category_pills`, `now_logo_img`). |
| `header.php` / `footer.php` | Sticky glass masthead / footer. Shared by every template. |
| `home.php` | Blog front page: hero → featured lead → one horizontal rail per category → newsletter. |
| `single.php` | Article: centered head → 21:9 hero → 760px prose + sticky TOC/share sidebar → related. |
| `archive.php` | Category/tag/author/date: header + responsive card grid. |
| `search.php` | Search results (card grid). |
| `page.php` | Standard page (readable prose column). |
| `page-canvas.php` | Template Name **"Full-width canvas"** — edge-to-edge `the_content()`; use for the designed About/Platform pages. |
| `index.php` / `404.php` | Fallback grid / not-found. |
| `_ds/…/tokens/*.css` + `styles.css` | **Design-system source of truth.** Do not fork these values elsewhere. |
| `assets/css/now.css` | Supplemental CSS only: page canvas, `.now-prose`, real `:hover` states, responsive stacking, reduced-motion. References DS tokens. |
| `assets/js/now.js` | Optional progressive JS: rail arrows, article TOC + scrollspy, copy-link. |
| `assets/img/*` | Logo (`logo-now-glass.png`) + 3D glass-render illustrations. |
| `.github/workflows/validate.yml` | CI: `php -l` every file + required files/tokens present. |

## How the templates are built

Each template is cut from a design-handoff **screen** (`NowBlog-*.dc.html`). The
rule: **keep the screen's HTML and inline styles verbatim; swap only the dynamic
slots** for WordPress calls (`the_title`, `the_content`, `the_post_thumbnail`,
`WP_Query`, `get_category_link`, etc.). This is why the templates carry inline
`style="…"` with `var(--token)` — that is the handoff, preserved 1:1.

- The handoff's preview-only `style-hover="…"` attribute is **not real CSS** —
  every hover/active state is reimplemented in `assets/css/now.css` (keyed by a
  class like `.now-card`, `.now-pill`, `.now-nav-link`, `.now-toc-link`).
- Repeated markup (the story card) lives once in `now_render_card()` so the home
  rails, archive grid, search and "related" stay identical.

## How to make design changes (order of preference)

1. **A token value** (color/space/radius/type/glow) → edit `_ds/…/tokens/*.css`.
   One edit re-themes everywhere. This is the single source of truth.
2. **A shared style** (prose, hover, responsive) → `assets/css/now.css`, always
   via `var(--…)` tokens — never raw hex/px that duplicates a token.
3. **Layout/structure** → the relevant `*.php`, matching the handoff screen.
   Reference tokens with `var(--…)` inline, exactly as the screens do.

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
  `main`, install-from-subdirectory empty — repo root is the theme). See
  [`README.md`](./README.md).
- After activating: Site Logo is optional — the theme falls back to the bundled
  `assets/img/logo-now-glass.png`. Set Permalinks to `/%category%/%postname%/`
  and Reading → "your latest posts" so `home.php` drives the front page.

## Re-syncing the design system

When Claude Design re-exports the package, drop the new `_ds/…/` folder over the
existing one (same path) for a clean diff, and re-cut any screen whose markup
changed. Keeping the `_ds/` path stable is what makes the re-sync a diff, not a
manual retranslation.
