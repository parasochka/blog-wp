# NowPlix — “NOW” Design System

The design system for **NowPlix**, a dark, premium **iGaming platform** (casino
+ sportsbook), and its editorial surface, the **NowPlix blog** (blog.nowplix.dev).
The system's name inside Figma is **“NOW.”** It is a committed dark aesthetic:
a deep indigo base carried by one saturated brand indigo and a single gaming-amber
accent, futuristic glass-render imagery, and a brand **glow** used in place of
soft drop shadows.

> This design system was reconstructed from the sources below. It contains no
> confidential material beyond what those sources expose.

## Sources

- **Figma:** `NOW.fig` (the "NOW" design system — Landing Page, Internal/product
  canvas, Cover). Source of truth for tokens, the logo vector, and imagery.
- **GitHub:** [`parasochka/blog-wp`](https://github.com/parasochka/blog-wp) — the
  **NOW** WordPress block theme that ships the blog. Its `DESIGN-CONSTANTS.md`,
  `DESIGN.md` and `theme.json` are a second, independently-parsed record of the
  same `NOW.fig` tokens and cross-verify the values here. Explore that repo to
  build or extend the production WordPress theme.
- **Uploads:** the brand's 3D glass-render illustration set (in `assets/img/`).

## Products / surfaces

1. **NowPlix product & dashboard** — the iGaming platform app: dark analytics UI
   (revenue, conversion, KPIs). Data UI uses **Inter**. Primitives: `StatCard`,
   `Table`, `Card`, `Badge`, forms.
2. **NowPlix blog (“NOW”)** — the editorial/marketing surface. Type is **Archivo
   Black + 42dot Sans only** (no Inter). Recreated in full under
   `ui_kits/now-blog/` (home with per-category rails, article, media guide).

---

## CONTENT FUNDAMENTALS

**Voice** — confident, technical, forward-looking. The house line is *“Signals
from the future of iGaming.”* It sounds like an engineering/product team that
takes craft seriously, not a casino barker.

- **Person:** second person for the reader (“stories delivered to *your*
  inbox”), first-person plural for the platform (“how *we* hold latency flat”).
- **Case:** sentence case for headings and body. **Uppercase** is reserved for
  small eyebrows / category labels, always with wide tracking (`0.14–0.16em`).
- **Tone:** premium and calm. “Dark, premium, brand-saturated — not
  playful-cartoon, not corporate-navy.” Purposeful, restrained.
- **Register:** technical but plain. Real numbers over hype (“shave milliseconds
  off a live table”, “400% on a Saturday night”). Explains, doesn't boast.
- **Emoji:** none. **Exclamation marks:** effectively none.
- **Example copy:** eyebrow *“THE NOWPLIX BLOG”* → H1 *“Signals from the future
  of iGaming.”* → sub *“Product, design and technology stories from the NowPlix
  platform — casino, sportsbook and everything around them.”* CTAs: *“Read the
  latest”*, *“Explore the platform”*, *“Never miss a signal.”*

---

## VISUAL FOUNDATIONS

**Color.** Deep-indigo dark system. Base `#0F0F2D`; deepest `#080818`; deep
panel `#141430`; surfaces `#1E1E40` / `#2E2E52`. Brand indigo `#5149E6`
(hover `#3B34C0`, link `#8A74FF`); secondary `#6B55F5`. One accent — **gaming
amber `#FFAC34`** — used sparingly for the highest-intent CTA. Text: white
headings, `#ABABC8` secondary, `#8080A8` muted. Committed strategy: the dark
surface + one or two saturated brand colors carry the identity. Named gradients
(`brand`, `sunset`, `ocean`, `aurora`, `lavender`, `amber`) exist but are used
with restraint. See `tokens/colors.css`.

**Type.** Three families (`tokens/typography.css`):
- **Archivo Black** — display & headings, **weight 400 only** (it is already
  black — never faux-bold to 700/800). `letter-spacing: -0.01em`, line-height ~1.12.
- **42dot Sans** — marketing body, navigation, buttons/labels (Medium 600).
- **Inter** — product/dashboard UI, forms, tables, data (Extra Bold 800 numerals).

  Scale: display 56 · h1 48 · h2 36 · h3 28 · h4 22 · h5 18 · body 18/16/14.

**Spacing.** 4px grid (`--space-1…--space-32` = 4 · 8 · 12 · 16 · 24 · 32 · 48 ·
64 · 80 · 96 · 128). 80px is the section gutter. See `tokens/spacing.css`.

**Radius.** `sm 4 · md 8 (workhorse) · lg 12 · xl 16 · 2xl 24 · 3xl 32 · pill 999`,
plus the signature asymmetric **CTA “spark” corner** `12px 4px 12px 4px`.

**Elevation = glow, not shadow.** Depth is a **hairline stroke + inner brand
glow**, not a soft drop shadow. L1 `inset 0 0 0 1px border`; L2 (featured) `inset
brand ring + inset 32px indigo glow`; L3 (lifted) `hairline + 0 8 16 rgba(0,0,0,.4)`.
Ambient halos: `--glow-brand` (indigo), `--glow-accent` (amber). See
`tokens/elevation.css`.

**Backgrounds & imagery.** Full-bleed dark fields with faint radial **brand
halos** top-left / top-right. Imagery is a distinct 3D **glass-render** set:
translucent electric-indigo glass typography, orbs, liquid metal, data tiles —
always on a `#080818` field, cool/saturated, high-gloss. No stock photography.

**Cards.** Two idioms. *Editorial listings* are **borderless** — the rounded
image is the card, text grouped beneath (no box). *Functional surfaces*
(sidebar, newsletter, product cards) are boxed: surface fill + hairline border +
`radius-xl`, gaining a brand glow on hover.

**Motion.** Custom ease-out `cubic-bezier(0.23,1,0.32,1)`; durations 160 / 220 /
320ms. Hover = subtle lift (-3px) + brand glow on media, image scale 1.045.
Press = `scale(0.97)`. Scroll-reveal rises content in — but only as an
enhancement over already-visible content, and always gated behind
`prefers-reduced-motion`.

**Corners / borders / transparency.** Hairlines are `#2B2B50` (a solid stand-in
for the file's true divider, white @10%). Sticky headers use `blur(12–14px)` +
`base @ ~80%` glass. Focus = `inset brand ring + soft indigo glow`.

---

## ICONOGRAPHY

- **Style:** Lucide-style **2px stroke, round cap/join, 24×24** line icons.
  Paints with `currentColor`. Provided as the `Icon` component (`components/brand/`)
  — a curated inline subset (chevron, arrow, search, grid, trending-up, shield,
  zap, dice, wallet, users, gift, loader, x/x-circle, check, menu) plus the
  **4-point brand “spark”** (a filled star accent).
- **Extending:** add glyphs to `Icon.jsx` in the same 2px-stroke style, or link
  Lucide from CDN for the full set (same weight/caps — a faithful match).
- **Emoji / unicode:** not used as UI iconography. The only non-stroke glyph is
  the brand spark.
- **Logo:** the **NowPlix wordmark is real vector geometry extracted from
  `NOW.fig`** (`components/brand/Logo.jsx`) — the rounded-geometric **“now”**
  mark, with a full “now / PLIX” lockup. Monochrome, paints with `color`. The
  3D glass “now” renders in `assets/img` are the same mark as brand illustration.

---

## Index / manifest

**Root**
- `styles.css` — global entry (imports only). `readme.md` — this guide.
- `SKILL.md` — portable Agent-Skill wrapper.

**Tokens** (`tokens/`) — `colors.css`, `typography.css`, `spacing.css`,
`radius.css`, `elevation.css`, `fonts.css`.

**Components** (`components/`) — reusable primitives (`.jsx` + `.d.ts` +
`.prompt.md`, one `@dsCard` per group):
- **actions/** — `Button`
- **brand/** — `Logo`, `Icon`
- **forms/** — `Input`, `Textarea`, `Select`
- **feedback/** — `Badge`
- **surfaces/** — `Card`, `IconTile`, `StatCard`
- **data/** — `Table`

**Guidelines** (`guidelines/`) — foundation specimen cards for the Design System
tab: color (primary, neutral, secondary/accent, semantic, gradients), type
(families, headings, body), spacing, radius, elevation, brand (logo, imagery).

**UI kits** (`ui_kits/`)
- **now-blog/** — the NOW editorial blog: interactive Home → Category → Article,
  per-category horizontal rails, floating article sidebar, and `MEDIA-GUIDE.html`.

**Assets** (`assets/img/`) — the brand's 3D glass-render illustration set.

## Fonts — substitution note

`NOW.fig` did not ship font binaries. **Archivo Black**, **42dot Sans** and
**Inter** are the exact families named in the file and load from Google Fonts
(`tokens/fonts.css`). Swap to self-hosted `woff2` for offline/production use — if
you have licensed binaries, drop them in and replace the `@import` with
`@font-face` rules.
