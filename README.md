# NOW — NowPlix blog (classic WordPress theme)

A custom WordPress **classic PHP theme** for the
[NowPlix blog](https://blog.nowplix.dev). The Claude Design **"NOW"** design
system is the source of truth for styling: its tokens live in
[`theme/_ds/now/tokens/*.css`](./theme/_ds/now) and are enqueued **as-is**.
Templates are plain PHP that reproduce the design-handoff screens
pixel-for-pixel. **The theme is the `theme/` folder** — WP Pusher installs from
that subdirectory; everything else in the repo (docs, tools, CI) stays out of
the deployed theme.

```
Claude Design "NOW"  ─►  theme/ (_ds/now + *.php)  ─►  GitHub  ─►  WP Pusher  ─►  WordPress
 design-handoff screens    this repo (theme dir)       (main)     (subdir: theme)   live site
```

## Structure

```
blog-wp/
├── theme/                       # ← THE THEME (WP Pusher subdirectory)
│   ├── style.css                # WP theme header (metadata)
│   ├── functions.php            # thin loader — requires inc/*
│   ├── inc/
│   │   ├── setup.php            # theme supports, menus, template shims
│   │   ├── enqueue.php          # DS tokens + theme CSS/JS
│   │   ├── template-tags.php    # reading time, badges, logo, card wrappers
│   │   ├── nav.php              # header/mobile nav, pills, footer links
│   │   ├── customizer.php       # defaults + Customize → NOW controls
│   │   └── inline-related.php   # inline "Keep reading" inserts
│   ├── template-parts/
│   │   ├── card.php             # the editorial story card (all grids/rails)
│   │   └── author-card.php      # the article-footer author card (E-E-A-T)
│   ├── page-templates/
│   │   └── canvas.php           # Template Name "Full-width canvas"
│   ├── header.php · footer.php  # sticky glass masthead / footer (shared)
│   ├── home.php                 # hero → featured → per-category rails → newsletter
│   ├── single.php               # article: 21:9 hero, 760px prose, sticky sidebar
│   ├── archive.php · tag.php · author.php · search.php  # card grids
│   ├── page.php · index.php · 404.php                   # pages / fallbacks
│   ├── _ds/now/tokens/*.css     # DESIGN-SYSTEM SOURCE OF TRUTH (colors/type/space…)
│   ├── _ds/now/styles.css       # token @import manifest (enqueued)
│   ├── assets/css/now.css       # supplemental: prose, hovers, responsive, motion
│   ├── assets/js/now.js         # rail arrows, article TOC, copy-link
│   └── assets/img/*             # logo (logo-now-glass.png) + 3D illustrations
├── tools/uploader/              # dev helper backend — never deployed
└── .github/workflows/           # validate.yml (php -l + required files/tokens)
```

## Design changes

1. **A token** (color/space/radius/type/glow) → edit `theme/_ds/now/tokens/*.css`.
   One edit re-themes the whole site.
2. **A shared style** (prose/hover/responsive) → `theme/assets/css/now.css`, via
   `var(--…)` tokens only.
3. **Layout** → the relevant `theme/*.php`, mirroring the handoff screen.

No raw hex/px that duplicates a token. Full rules: [`CLAUDE.md`](./CLAUDE.md).

## Deploy

WP Pusher pulls this repo (branch **main**, install from subdirectory:
**`theme`**). Push to `main` → WP Pusher → **Update** → activate
**"NOW — NowPlix Blog"**. Full migration/ops guide:
[`MIGRATION.md`](./MIGRATION.md).

## Validation

`.github/workflows/validate.yml` runs on every push: `php -l` on all PHP files
plus a check that the required theme files and `theme/_ds/now` tokens exist.
Keep it green — WP Pusher ships whatever is on `main`.

## Content

Content (posts, pages, categories, menus, media) lives in WordPress, not git —
edited in wp-admin or via the WordPress MCP. The theme renders whatever exists.
For bulk/awkward uploads there is a small helper backend in
[`tools/uploader/`](./tools/uploader).
