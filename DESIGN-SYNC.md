# Design sync — Figma → WordPress

This theme treats the **Figma "NOW" design system as the source of truth**.
Design decisions live in Figma variables; the theme mirrors them in
`theme.json`. This document defines the mapping so the sync is repeatable
(by a human or by Claude via the Figma MCP).

## Pipeline

```
Figma "NOW"  ──(Figma Dev Mode MCP)──►  theme.json  ──(git push)──►  WP Pusher  ──►  WordPress
   variables     get_variable_defs         tokens        GitHub      (pull in wp-admin)  live site
   components     get_design_context       templates + patterns
   frames         get_screenshot           visual reference for QA
```

## How tokens were sourced

The "NOW" file lives on a Figma **Starter** team where the connected
account has a **View** seat. Figma Dev Mode (and its MCP `get_variable_defs`
/ `get_screenshot`) requires a paid **Editor/Dev** seat, so the MCP could
not read the file directly. Tokens were instead transcribed from the
exported **design-system screenshots** (the `NowPlix Design System` frame,
components, and grid). The values below are the real NOW values.

Also note the design system is built with **color/text styles**, not Figma
**Variables/Collections** — another reason the variable API returns nothing.

### To automate this later (with a Dev seat)

1. Upgrade the Figma team to Professional and give the account a Dev/Full seat.
2. Reconnect the Figma connector in claude.ai.
3. Ask Claude to sync — it will `get_variable_defs` / `get_design_context`
   / `get_screenshot` and map results to `theme.json` via the table below.

## Token mapping (NOW design system → theme.json)

Semantic roles (what content editors pick), mapped from the NOW palette:

| NOW token                 | theme.json slug                        | Value      |
| ------------------------- | -------------------------------------- | ---------- |
| Neutral 900 (background)  | `color.palette[base]`                  | `#0f0f2d`  |
| Neutral 950               | `color.palette[base-deep]`             | `#080818`  |
| Neutral 800               | `color.palette[surface]`               | `#1e1e40`  |
| Neutral 700               | `color.palette[surface-elevated]`      | `#2e2e52`  |
| Neutral 0                 | `color.palette[contrast]` (headings)   | `#ffffff`  |
| Neutral 200               | `color.palette[text]` (body)           | `#d0d0e0`  |
| Neutral 400               | `color.palette[muted]`                 | `#8080a8`  |
| Primary 500               | `color.palette[primary]`               | `#5149e6`  |
| Primary 600               | `color.palette[primary-hover]`         | `#3b34c0`  |
| Primary 300               | `color.palette[primary-light]` (links) | `#8a74ff`  |
| Secondary 500             | `color.palette[secondary]`             | `#6b5ff5`  |
| Accent 400 / 500          | `color.palette[accent]` / `accent-strong` | `#ffac34` / `#f5900a` |
| Semantic                  | `success/warning/error/info`           | see theme.json |
| Gradients (6)             | `color.gradients[*]`                    | brand / deep-ocean / sunset / aurora / purple-fade |
| Full numeric scales       | `settings.custom.color.{primary,neutral,accent}` | 50…950 |
| Heading / sub-blocks font | `typography.fontFamilies[heading]`     | **Archivo Black** |
| Body / content font       | `typography.fontFamilies[body]`        | **42dot Sans** |
| Type scale 12→72px        | `typography.fontSizes[small…display]`  | Caption…Display XL |
| Spacing 4px grid          | `spacing.spacingSizes[10…110]`         | 4…128px    |
| Radius sm/md/lg/xl/2xl/full | `settings.custom.radius.*`            | 4/8/12/16/24/9999 |
| Elevation L1/L2/L3        | `shadow.presets[card/glow/glow-strong]` | shadow + brand glow |

> **Font weights:** Archivo Black ships a single weight (400) — it is
> already "black", so headings use `font-weight: 400` (never 700/800, which
> would trigger ugly faux-bold). Buttons/labels use 42dot Sans at 600.

### Rules

- **Roles, not literals.** Map by semantic role (background, primary, muted)
  so a palette change in Figma re-themes the whole site through
  `theme.json` alone.
- **Every color used in a template must exist as a palette slug.** No raw
  hex in templates or patterns — always `var(--wp--preset--color--<slug>)`.
- **Spacing uses the scale.** Templates reference `var:preset|spacing|NN`,
  never hardcoded rems.
- **Fonts** must also be delivered: when a Figma font family maps in, add
  the webfont (self-hosted under `assets/fonts/` + `@font-face`, or via a
  fonts provider) so the site actually renders it.

## What is NOT auto-synced

Content (posts, pages, menus, media) lives in WordPress, not in Figma or
git. The theme ships the structure and style; the site owner fills content.
