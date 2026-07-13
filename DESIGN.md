# DESIGN.md — NOW visual system

> The captured visual system for design tooling (`impeccable`). The machine
> source of truth is [`theme.json`](./theme.json); the Figma→theme mapping is
> [`DESIGN-SYNC.md`](./DESIGN-SYNC.md). This file is the human-readable summary.

## Color (dark)

| Role | Slug | Value |
| --- | --- | --- |
| Background | `base` | `#0f0f2d` |
| Deep background | `base-deep` | `#080818` |
| Surface | `surface` | `#1e1e40` |
| Surface elevated | `surface-elevated` | `#2e2e52` |
| Border | `border` | `#2b2b50` |
| Headings | `contrast` | `#ffffff` |
| Body | `text` | `#d0d0e0` |
| Muted / meta | `muted` | `#8080a8` |
| Primary / brand | `primary` | `#5149e6` |
| Primary hover | `primary-hover` | `#3b34c0` |
| Link | `primary-light` | `#8a74ff` |
| Secondary | `secondary` | `#6b5ff5` |
| Accent (gaming) | `accent` | `#ffac34` |

Full numeric ramps (`primary`/`neutral`/`accent` 50–950) and 5 gradients live in
`theme.json`. **Contrast:** body/headings pass AA on `base`; `muted` is meta-only.

## Typography

- **Headings / display:** Archivo Black, weight **400** only (already black —
  never 700/800). `letter-spacing: -0.01em`, `line-height: 1.12`.
- **Body / UI:** 42dot Sans. Body 16px, `line-height: 1.65`. Buttons/labels 600.
- **Code:** JetBrains Mono.
- **Scale (fluid):** small 14 · body 16 · body-L 18 · h3 28 · h2 36 · h1 48 ·
  display 72 (clamp max 4.5rem, under the 96px ceiling).
- `text-wrap: balance` on h1–h3; `text-wrap: pretty` on prose.

## Spacing, radius, elevation

- **Spacing:** 4px grid, slugs `10…110` = 4 · 8 · 12 · 16 · 24 · 32 · 48 · 64 ·
  80 · 96 · 128px.
- **Radius:** sm 4 · md 8 · lg 12 · xl 16 · 2xl 24 · pill 9999.
- **Shadows:** `card` (L1), `glow` (brand, L2), `glow-strong` (L3).
- **Layout:** content 760px, wide 1280px.

## Motion (Emil Kowalski)

- Easing tokens (`custom.ease`): `out` `cubic-bezier(0.23, 1, 0.32, 1)`,
  `inOut` `cubic-bezier(0.77, 0, 0.175, 1)`, `drawer` `cubic-bezier(0.32, 0.72, 0, 1)`.
- Duration tokens (`custom.duration`): fast 160 · base 220 · slow 320ms.
- Button `:active` → `scale(0.97)`. Hover lift + brand glow on post images.
- Scroll-reveal on cards via `animation-timeline: view()`, gated behind
  `@supports` + `prefers-reduced-motion` so content is never hidden without it.

## Components / block styles

- `core/group` → `is-style-card` (surface + border + radius + card shadow, glow
  on hover).
- `core/button` → `is-style-ghost` (outlined, primary-light).
- `core/image` → `is-style-rounded-lg`.
- Quotes: **no side-stripe** — `core/quote` is a surface card; `core/pullquote`
  is typographic with full top/bottom rules.
- Sticky glass header; pill search field; dark form controls.
