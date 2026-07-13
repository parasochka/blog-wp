# CLAUDE.md — NOW block theme

Guidance for AI agents (and humans) working in this repo. Read this first.

## What this is

**NOW** is a custom WordPress **block theme (Full Site Editing)** for the
[Nowplix blog](https://blog.nowplix.dev). The **repo root IS the theme folder** —
there is no `wp-content/themes/now/` nesting here; `style.css` and `theme.json`
sit at the root.

Design tokens come from the Figma **"NOW"** design system and live in
`theme.json`. Figma is the source of truth for color/type/spacing/radii/shadows —
see [`DESIGN-SYNC.md`](./DESIGN-SYNC.md) for the mapping.

```
Figma "NOW"  ─►  theme.json + templates  ─►  GitHub  ─►  WP Pusher  ─►  WordPress
 design system     this repo                  version    (pull in wp-admin)  live site
```

## File map

| Path | Purpose |
| --- | --- |
| `style.css` | Theme header only (required by WP). No real CSS here. |
| `theme.json` | **Primary styling surface** — design tokens + global styles. Edit this first. |
| `functions.php` | Asset/font loading, pattern categories, block styles. |
| `assets/css/theme.css` | Supplemental CSS for **only what `theme.json` can't express** (custom block styles, glows, transitions, forms, scroll-reveal). |
| `templates/*.html` | Block-markup page templates (index, home, single, page, archive, author, search, 404). |
| `parts/*.html` | `header.html`, `footer.html`. |
| `patterns/*.php` | Reusable NOW blocks (`hero`, `newsletter-cta`). PHP so strings are translatable. |
| `.github/workflows/validate.yml` | CI: checks `theme.json` is valid JSON + required files/`style.css` header exist on every push. |
| `.claude/skills/` | Installed design skills (see below). |

## How to make design changes (order of preference)

1. **`theme.json` first.** Colors, fonts, sizes, spacing, radii, shadows, and
   per-block/element styles belong here. They generate `--wp--preset--*` and
   `--wp--custom--*` CSS variables automatically.
2. **`assets/css/theme.css` only when `theme.json` can't express it** — custom
   block styles (`.is-style-card`), hover glows, transitions, `:active`
   feedback, form controls, scroll-reveal, reduced-motion. Always reference
   tokens via `var(--wp--preset--…)` / `var(--wp--custom--…)`, never raw hex.
3. **Templates/patterns** for structure. Reference tokens with
   `var:preset|color|<slug>`, `var:preset|spacing|NN`, `var:custom|radius|xx` —
   **never hardcoded hex or rem**.

After any `theme.json` edit, confirm it still parses:
`python3 -c "import json; json.load(open('theme.json'))"`.

## Design-system rules (non-negotiable)

- **No raw hex in templates, patterns, or CSS.** Every color is a palette slug.
- **Every color used must exist as a palette slug** in `theme.json`.
- **Spacing uses the scale** (`spacing|10…110` = 4…128px on a 4px grid).
- **Fonts:** Archivo Black → headings (single weight **400** — it is already
  black; never 700/800 or you get faux-bold). 42dot Sans → body/UI (600 for
  buttons/labels). JetBrains Mono → code.
- **Roles, not literals.** Map by semantic role (background/primary/muted) so a
  Figma palette change re-themes the whole site through `theme.json` alone.
- **Motion tokens live in `theme.json`** (`custom.ease`, `custom.duration`,
  `custom.z`) and are mirrored as `--now-*` fallbacks in `theme.css`.

## Installed design skills

Three skills are installed under `.claude/skills/`. They encode the design bar
this theme is held to. Invoke them by name when doing design work.

| Skill | By | Use for |
| --- | --- | --- |
| `impeccable` | Paul Bakaus | Full design language: `critique`, `audit`, `polish`, `layout`, `typeset`, `colorize`, `animate`. Catches AI "slop" and enforces the absolute bans below. |
| `design-taste-frontend` (`taste-skill`) | Leon Lin | Anti-slop taste pass for landing/editorial surfaces (hero, newsletter, home). Pulls output toward top-studio quality. |
| `emil-design-eng` | Emil Kowalski | Motion decisions: what should animate, easing, duration, `:active` feedback. Companions: `review-animations`, `improve-animations`, `animation-vocabulary`. |

### Impeccable absolute bans — already enforced here, keep it that way

- **No side-stripe borders** (`border-left`/`right` > 1px as an accent). Quotes
  use a surface card / full top+bottom rules instead of a colored stripe.
- **No gradient text** (`background-clip: text` on a gradient).
- **Glassmorphism only when purposeful** — the sticky header blur is the one
  sanctioned use; don't add decorative glass cards.
- **No hero-metric template, no identical endless card grids.**
- **No tiny uppercase tracked eyebrow above _every_ section.** The one hero
  kicker is deliberate brand voice; don't scatter more.
- **No text that overflows its container** — test heading copy at every
  breakpoint (display clamp max is 72px, under the 96px ceiling).
- **Contrast:** body ≥ 4.5:1, large text ≥ 3:1. Don't lighten body "for
  elegance."

### Emil motion rules — already applied in `theme.css`

- Custom ease-out curves, **not** the weak CSS defaults. Tokens:
  `--wp--custom--ease--out` = `cubic-bezier(0.23, 1, 0.32, 1)`.
- Colour/hover changes may use plain `ease`; transform/shadow use ease-out.
- **UI animations < 320ms.** Button press feedback 100–160ms (`scale(0.97)` on
  `:active`).
- **Never animate high-frequency / keyboard-initiated actions.**
- **`prefers-reduced-motion: reduce` is not optional** — the global reset in
  `theme.css` neutralizes animation/transition for those users.
- Reveals must **enhance an already-visible default** — the scroll-reveal is
  gated behind `@supports (animation-timeline: view())` + reduced-motion so
  content is never hidden on unsupported browsers or crawlers.

## Validation & deploy

- CI (`validate.yml`) runs on push: valid JSON + required files. Keep it green —
  WP Pusher pulls from GitHub, so a broken build ships a broken theme.
- Deploy is admin-only via **WP Pusher** (`parasochka/blog-wp`, branch `main`),
  or manual zip upload. No SSH/secrets. See [`README.md`](./README.md).

## Working conventions

- Develop on the assigned feature branch; commit with clear messages; push with
  `git push -u origin <branch>`. Do **not** open a PR unless asked.
- Content (posts, pages, menus, media) lives in WordPress, not in git. The theme
  ships structure + style only.
- When in doubt about a token value, check `theme.json` and `DESIGN-SYNC.md`
  before inventing one.
