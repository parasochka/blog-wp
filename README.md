# NOW — NowPlix blog (classic WordPress theme)

A custom WordPress **classic PHP theme** for the
[NowPlix blog](https://blog.nowplix.dev). The Claude Design **"NOW"** design
system is the source of truth for styling: its tokens live in
[`_ds/…/tokens/*.css`](./_ds) and are enqueued **as-is**. Templates are plain
PHP that reproduce the design-handoff screens pixel-for-pixel. **The repo root
is the theme.**

```
Claude Design "NOW"  ─►  _ds/tokens + *.php  ─►  GitHub  ─►  WP Pusher  ─►  WordPress
 design-handoff screens    this repo (theme)      (main)     (pull/update)    live site
```

## Structure

```
blog-wp/  (= the theme)
├── style.css                # WP theme header (metadata)
├── functions.php            # enqueue tokens/CSS/JS, supports, nav, helpers
├── header.php · footer.php  # sticky glass masthead / footer (shared)
├── home.php                 # hero → featured → per-category rails → newsletter
├── single.php               # article: 21:9 hero, 760px prose, sticky sidebar
├── archive.php · search.php # card grids
├── page.php · page-canvas.php  # pages (full-bleed designed / readable text)
├── index.php · 404.php      # fallbacks
├── _ds/…/tokens/*.css       # DESIGN-SYSTEM SOURCE OF TRUTH (colors/type/space…)
├── _ds/…/styles.css         # token @import manifest (enqueued)
├── assets/css/now.css       # supplemental: prose, hovers, responsive, motion
├── assets/js/now.js         # rail arrows, article TOC, copy-link
├── assets/img/*             # logo (logo-now-glass.png) + 3D illustrations
└── .github/workflows/       # validate.yml (php -l + required files/tokens)
```

## Design changes

1. **A token** (color/space/radius/type/glow) → edit `_ds/…/tokens/*.css`.
   One edit re-themes the whole site.
2. **A shared style** (prose/hover/responsive) → `assets/css/now.css`, via
   `var(--…)` tokens only.
3. **Layout** → the relevant `*.php`, mirroring the handoff screen.

No raw hex/px that duplicates a token. Full rules: [`CLAUDE.md`](./CLAUDE.md).

## Deploy

WP Pusher pulls this repo (branch **main**, install-from-subdirectory empty).
Push to `main` → WP Pusher → **Update** → activate **"NOW — NowPlix Blog"**.
Full migration/ops guide: [`MIGRATION.md`](./MIGRATION.md).

## Validation

`.github/workflows/validate.yml` runs on every push: `php -l` on all PHP files
plus a check that the required theme files and `_ds/` tokens exist. Keep it
green — WP Pusher ships whatever is on `main`.

## Content

Content (posts, pages, categories, menus, media) lives in WordPress, not git —
edited in wp-admin or via the WordPress MCP. The theme renders whatever exists.
For bulk/awkward uploads there is a small helper backend in
[`tools/uploader/`](./tools/uploader).
