# NOW — WordPress block theme

A custom WordPress **block theme (Full Site Editing)** for the
[Nowplix blog](https://blog.nowplix.dev), driven by the Figma **"NOW"**
design system. Design tokens live in `theme.json` and map 1:1 to Figma
variables — see [`DESIGN-SYNC.md`](./DESIGN-SYNC.md).

## The pipeline

```
Figma "NOW"  ──►  theme.json + templates  ──►  GitHub  ──►  WP Pusher plugin  ──►  WordPress
  design system     this repo                    version      (pull in wp-admin)     blog.nowplix.dev
```

- **Figma is the source of truth.** Colors, type, spacing, radii, shadows
  come from Figma variables and land in `theme.json`.
- **This repo IS the theme.** Its root is the theme folder.
- **WP Pusher pulls the theme from GitHub** straight through the WordPress
  admin — no server/SSH access required. Push to GitHub, click Update in
  wp-admin (or wire the webhook for one-click auto-update).

## Structure

```
.
├── style.css                # theme header (required)
├── theme.json               # design tokens + global styles (the Figma bridge)
├── functions.php            # asset loading, pattern categories, block styles
├── templates/               # page templates (cover every page type)
│   ├── index.html           #   fallback
│   ├── home.html            #   blog posts index (hero + grid + CTA)
│   ├── single.html          #   single post (+ comments)
│   ├── page.html            #   static page
│   ├── page-wide.html       #   wide page template
│   ├── archive.html         #   category / tag / date archives
│   ├── author.html          #   author archive
│   ├── search.html          #   search results
│   └── 404.html             #   not found
├── parts/                   # header.html, footer.html
├── patterns/                # hero, newsletter-cta (reusable NOW blocks)
├── assets/css/theme.css     # supplemental styles theme.json can't express
└── .github/workflows/       # validate.yml (checks theme.json/style.css on push)
```

## Pages covered

Home/blog index, single post, static page, category, tag, date archive,
author archive, search results, and 404 — the full set a blog needs.

## Install (Method A — manual zip)

No plugin, works everywhere:

1. Download the repo as a zip:
   `https://github.com/parasochka/blog-wp/archive/refs/heads/main.zip`
2. WordPress admin: **Appearance → Themes → Add New → Upload Theme** →
   pick the zip → **Install** → **Activate**.
3. Edit visually under **Appearance → Editor** (Site Editor).

The theme folder will be `blog-wp-main`; it shows in the theme list as
**NOW**. To update, re-upload a fresh zip.

## Install (Method B — WP Pusher, admin-only auto-update)

Deploy from GitHub without any server/SSH access — everything happens in
wp-admin. Recommended once you're iterating on the theme.

1. Install the **WP Pusher** plugin: **Plugins → Add New → Upload Plugin**
   (get it from [wppusher.com](https://wppusher.com)) → Activate.
2. **WP Pusher → Install Theme**:
   - Repository: `parasochka/blog-wp`
   - Branch: `main`
   - Leave "Install from subdirectory" empty (repo root **is** the theme).
   - Click **Install Theme**.
3. **Appearance → Themes → Activate "NOW"**.
4. To update after a new push: **WP Pusher → Themes → Update**, or enable
   **Push-to-Deploy** to have GitHub trigger updates automatically via the
   webhook WP Pusher generates.

WP Pusher pulls over HTTPS from the public repo — no keys, no secrets.

## Continuous validation

`.github/workflows/validate.yml` runs on every push: it checks `theme.json`
is valid JSON and that the required theme files and `style.css` header are
present, so WP Pusher never pulls a broken build. No secrets required.

## Updating design tokens from Figma

See [`DESIGN-SYNC.md`](./DESIGN-SYNC.md) for the full mapping and the
MCP-driven sync workflow. In short: pull Figma variables with the Figma
MCP, map them to `theme.json` paths, commit, push — then update the theme
in WP Pusher (or let Push-to-Deploy do it).

> **Tokens are live.** `theme.json` now carries the **real NowPlix "NOW"**
> design-system values — dark palette (`#0f0f2d` base, `#5149e6` primary,
> `#ffac34` accent), the 12→72px type scale, 4px spacing grid, radii and
> brand-glow elevation. Fonts: **Archivo Black** (headings) + **42dot Sans**
> (body). See [`DESIGN-SYNC.md`](./DESIGN-SYNC.md) for the full mapping.
