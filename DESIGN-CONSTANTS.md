# DESIGN-CONSTANTS.md — extracted from `NOW.fig`

> **Source of truth, parsed.** These are the design constants defined inside
> the Figma **"NOW"** file (`NOW.fig`, exported 2026-07-13), read straight out
> of the binary `.fig` — not transcribed from screenshots. The machine-readable
> dump lives in [`design-constants.json`](./design-constants.json); the live
> theme mirrors these in [`theme.json`](./theme.json).
>
> **How it was read:** `.fig` is a ZIP → `canvas.fig` is `fig-kiwi` (a Kiwi
> schema block, raw-deflate, + a data block, **Zstandard**). Decoding the Kiwi
> message yields **903 nodes** across 3 canvases (`Landing Page`,
> `Internal Only Canvas`, `Cover`). The file has **no Figma Variables /
> Collections** — the system is built with **color & text styles**, so every
> constant below is extracted from actual node fills, text runs, corner radii
> and effects.

---

## Color

Values are the raw fills observed in the file, mapped to their semantic role
and `theme.json` palette slug. Usage counts are how often each fill appears.

### Neutrals (dark UI ramp) — `custom.color.neutral` 0…950

| Hex | Uses | Role | Slug |
| --- | ---: | --- | --- |
| `#ffffff` | 108 | Headings / primary text | `contrast` · neutral-0 |
| `#f5f5fa` | 1 | — | neutral-50 |
| `#eaeaf2` | 1 | — | neutral-100 |
| `#d0d0e0` | 1 | Body text (blog reading tier) | `text` · neutral-200 |
| `#ababc8` | **145** | Secondary / supporting text | neutral-300 |
| `#8080a8` | 67 | Muted / meta | `muted` · neutral-400 |
| `#5a5a80` | 38 | Disabled / faint | neutral-500 |
| `#414168` | 2 | Hairline / divider (solid) | neutral-600 |
| `#2e2e52` | 64 | Elevated surface | `surface-elevated` · neutral-700 |
| `#1e1e40` | 16 | Surface | `surface` · neutral-800 |
| `#141430` | 25 | Surface (deep panel) | neutral-850* |
| `#0f0f2d` | 14 | Page background | `base` · neutral-900 |
| `#080818` | 1 | Deepest background | `base-deep` · neutral-950 |

\* `#141430` is a heavily-used deep-panel tone that sits between `base` and
`surface`; it is captured in the constants but not (yet) promoted to a slug.

**Hairline treatment:** the file's most common divider is **`#ffffff` at 10%**
(`rgba(255,255,255,0.1)`, 36 uses), not a solid line. `theme.json`'s
`border` slug (`#2b2b50`) is the solid approximation of white-10%-over-`base`.

### Primary (brand indigo) — `custom.color.primary` 50…900

| Hex | Role | Slug |
| --- | --- | --- |
| `#edeeff` | 50 | — |
| `#d4d5ff` | 100 | — |
| `#ababff` | 200 | — |
| `#8a74ff` | 300 | Links / primary-light |
| `#7060f0` | 400 | — |
| `#5149e6` | 500 | **Primary / brand** (54 uses) |
| `#3b34c0` | 600 | Primary hover |
| `#2a2590` | 700 | — |
| `#1a1760` | 800 | — |
| `#0f0f40` | 900 | Gradient floor |

`#6b55f5` is the **secondary** brand tone (between 400 and 500).
Brand primary is also used at low alpha for glows/tints: `#5149e6` at
**@0.30 / @0.20 / @0.15 / @0.12** (fills), `#5149e6` at **@0.15** (inner glow).

### Accent (gaming amber) — `custom.color.accent`

| Hex | Slug |
| --- | --- |
| `#ffc05e` | accent-300 |
| `#ffac34` | `accent` / accent-400 (12 uses) |
| `#f5900a` | `accent-strong` / accent-500 |
| `#d47004` | accent-600 |
| `#a55206` | accent-700 |

### Semantic

| Hex | Uses | Slug |
| --- | ---: | --- |
| `#22c55e` | 11 | `success` (tint `@0.15`) |
| `#ef4444` | 7 | `error` (`#ff5555` light variant, tint `@0.15`) |
| `#3b82f6` | 6 | `info` (tint `@0.15`) |
| `#ffac34` | 12 | `warning` (= accent) |

---

## Gradients

All linear. The first five are the named `theme.json` gradients (stops verified
against the file); the rest are additional gradients present in the file,
documented for reuse.

| Name | Stops | In `theme.json` |
| --- | --- | :---: |
| `brand` | `#8a74ff 0%` → `#5149e6 100%` | ✅ |
| `deep-ocean` | `#6b55f5 0%` → `#0f0f40 100%` | ✅ |
| `sunset` | `#ffc05e 0%` → `#7060f0 100%` | ✅ |
| `aurora` | `#b09bff 0%` → `#ffac34 100%` | ✅ |
| `purple-fade` | `#ababff 0%` → `#0f0f40 100%` (180°) | ✅ |
| violet-rise | `#534699 36%` → `#8a74ff 100%` | — |
| lift-primary | `#edeeff 0%` → `#5149e6 100%` | — |
| amber-deep | `#ffc05e 0%` → `#a55206 100%` | — |
| scrim | `#0f0f2d @0 0%` → `#0f0f2d 100%` (fade-to-solid overlay) | — |

---

## Typography

**Brand fonts** (confirmed from text nodes):

- **Display / headings — Archivo Black**, single weight **400** (already black;
  never faux-bold). Sizes in file: **36 · 40 · 48 · 56 · 72 px**. Line-height
  `48px` on 40 (1.2); `100%` on the big display sizes. `TITLE`/uppercase case.
- **Body / UI — 42dot Sans** (Regular + Medium). Sizes: **14 · 16 · 18 px**.
  Reading line-heights: **16→26px (1.63)**, **18→28px (1.56)**. Medium 600 for
  buttons/labels.
- **Code — JetBrains Mono** (blog code blocks; no code in the landing mockup).

> ⚠️ **Inter is not a brand font.** The file contains a large amount of Inter
> text at 9–14px — it lives inside embedded **product/dashboard screenshots**
> (the `Internal Only Canvas`), i.e. UI chrome being illustrated, not the NOW
> type system. Do not adopt Inter into the theme.

Scale in `theme.json`: `small 14 · body 16 · body-L 18 · h3 28 · h2 36 ·
h1 48 · display 72` (fluid clamp max 4.5rem / 72px).

---

## Radius

Corner radii observed in the file (px → uses). `8px` is the workhorse.

| px | Uses | Slug |
| ---: | ---: | --- |
| 2 | 22 | `xs` |
| 4 | 37 | `sm` |
| 8 | **150** | `md` |
| 12 | 17 | `lg` |
| 16 | 10 | `xl` |
| 24 | 2 | `2xl` |
| 32 | 2 | `3xl` |
| 50 | 8 | (pill-ish; use `pill`) |
| 999 | 32 | `pill` (`9999px`) |

---

## Spacing

Gaps + paddings observed (px → uses). The theme exposes a curated **4px-grid**
scale (`spacing.10…110` = 4·8·12·16·24·32·48·64·80·96·128); the slugs are kept
stable so templates don't break. The file also uses **20** (34×) and **40** (6×)
frequently — favour the nearest scale step (24/48) in templates, or use an
explicit value where a landing layout needs 20/40.

`8 (109) · 12 (120) · 16 (107) · 24 (42) · 10 (37) · 20 (34) · 32 (32) · 80 (19)`
are the dominant steps. Off-grid values (5, 7, 18, 36, 60, 88, 136) come from
one-off landing compositions and are not tokens.

---

## Elevation / effects

Every shadow/effect in the file (`x y blur spread color`):

| Kind | Value | Maps to |
| --- | --- | --- |
| Drop | `0 2 8 · rgba(0,0,0,.30)` | `shadow.card` (L1) |
| Drop | `0 8 24 · rgba(81,73,230,.20)` | `shadow.glow` (L2, brand) |
| Drop | `0 16 48 · rgba(81,73,230,.40)` | `shadow.glow-strong` (L3, brand) |
| Drop | `0 8 16 · rgba(0,0,0,.40)` | dark elevation (heavy card) |
| Drop | `0 0 16 · rgba(81,73,230,.50)` | brand **ring** glow (bright) |
| Drop | `0 0 8 · rgba(81,73,230,.20)` | brand ring glow (subtle) |
| Inner | `0 0 32 · rgba(81,73,230,.15)` | inner brand glow |

The signature look is the **brand glow ring** — objects glow indigo (see the
file thumbnail). `theme.json` ships the three-step elevation; the ring/inner
glows above are available for custom block styles via `assets/css/theme.css`.

---

## Layout

Content `760px`, wide `1280px` (blog reading measures; not landing-derived).

---

## Re-running this extraction

`NOW.fig` is not committed (binary asset). To re-parse a fresh export:

1. `unzip NOW.fig` → take `canvas.fig`.
2. `canvas.fig` = `fig-kiwi` header + a raw-deflate **Kiwi schema** block + a
   **Zstandard** data block. Inflate the schema, decode it, `zstd -d` the data,
   then decode the Kiwi `Message` with the schema.
3. Walk `nodeChanges[]`, aggregating `fillPaints`/`strokePaints` (colors),
   `fontName`/`fontSize`/`lineHeight` (type), `cornerRadius*` (radii),
   `stackSpacing`/padding (spacing), and `effects` (shadows).
4. Diff against `theme.json`; update slugs whose values drift.

See [`DESIGN-SYNC.md`](./DESIGN-SYNC.md) for the role → slug mapping and rules.
