# NOW — WordPress block theme

A custom WordPress **block theme (Full Site Editing)** for the
[Nowplix blog](https://blog.nowplix.dev), driven by the Figma **"NOW"**
design system. Design tokens live in `theme.json` and map 1:1 to Figma
variables — see [`DESIGN-SYNC.md`](./DESIGN-SYNC.md).

## The pipeline

```
Figma "NOW"  ──►  theme.json + templates  ──►  GitHub  ──►  GitHub Action  ──►  WordPress (self-hosted)
  design system     this repo                    version         rsync/SSH          blog.nowplix.dev
```

- **Figma is the source of truth.** Colors, type, spacing, radii, shadows
  come from Figma variables and land in `theme.json`.
- **This repo IS the theme.** Its root is `wp-content/themes/now-blog/`.
- **GitHub Action deploys on push** over SSH to the self-hosted server.

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
└── .github/workflows/       # deploy.yml (CI/CD)
```

## Pages covered

Home/blog index, single post, static page, category, tag, date archive,
author archive, search results, and 404 — the full set a blog needs.

## Local install (quick test)

1. Zip the repo contents (not the folder) or clone into
   `wp-content/themes/now-blog/`.
2. In WordPress admin: **Appearance → Themes → Activate "NOW"**.
3. Edit visually under **Appearance → Editor** (Site Editor).

## Deploy (CI/CD)

Pushing to the working branch runs `.github/workflows/deploy.yml`, which
rsyncs the theme to the server over SSH. Configure these **GitHub repo
secrets** (Settings → Secrets and variables → Actions):

| Secret            | Example                                              |
| ----------------- | ---------------------------------------------------- |
| `SSH_HOST`        | `123.45.67.89` or `nowplix.com`                      |
| `SSH_PORT`        | `22` (optional, defaults to 22)                      |
| `SSH_USER`        | `deploy`                                             |
| `SSH_PRIVATE_KEY` | contents of the private key authorized on the server |
| `WP_THEME_PATH`   | `/var/www/blog/wp-content/themes/now-blog`           |
| `WP_PATH`         | `/var/www/blog` (optional, for WP-CLI cache flush)   |

Generate a deploy key on your machine, add the **public** key to the
server's `~/.ssh/authorized_keys`, and paste the **private** key into
`SSH_PRIVATE_KEY`.

## Updating design tokens from Figma

See [`DESIGN-SYNC.md`](./DESIGN-SYNC.md) for the full mapping and the
MCP-driven sync workflow. In short: pull Figma variables with the Figma
MCP, map them to `theme.json` paths, commit, push — the Action deploys.

> **Tokens are live.** `theme.json` now carries the **real NowPlix "NOW"**
> design-system values — dark palette (`#0f0f2d` base, `#5149e6` primary,
> `#ffac34` accent), the 12→72px type scale, 4px spacing grid, radii and
> brand-glow elevation. Fonts: **Archivo Black** (headings) + **42dot Sans**
> (body). See [`DESIGN-SYNC.md`](./DESIGN-SYNC.md) for the full mapping.
