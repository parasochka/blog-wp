# Design sync — Figma → WordPress

This theme treats the **Figma "NOW" design system as the source of truth**.
Design decisions live in Figma variables; the theme mirrors them in
`theme.json`. This document defines the mapping so the sync is repeatable
(by a human or by Claude via the Figma MCP).

## Pipeline

```
Figma "NOW"  ──(Figma Dev Mode MCP)──►  theme.json  ──(git push)──►  GitHub Action  ──►  WordPress
   variables     get_variable_defs         tokens          rsync/SSH                  live site
   components     get_design_context       templates + patterns
   frames         get_screenshot           visual reference for QA
```

## How to run a sync (with the Figma MCP connected)

1. In claude.ai, connect the **Figma** connector (Dev Mode MCP).
2. In Figma, select the frame or the variable collection to export
   (e.g. the palette frame, a component, or a full page such as
   node `2249-800`).
3. Ask Claude to sync. Claude will:
   - `get_variable_defs` → read color / type / spacing / radius variables
   - map each variable to a `theme.json` path using the table below
   - `get_design_context` / `get_code_connect_map` → translate components
     into block markup for `templates/` and `patterns/`
   - `get_screenshot` → keep a visual reference to QA the result against

## Token mapping (Figma variable → theme.json)

Figma variable names are conventions — adjust the left column to match the
actual names in the "NOW" file once the connector is live.

| Figma variable            | theme.json path                                  | Current placeholder |
| ------------------------- | ------------------------------------------------ | ------------------- |
| `color/background`        | `settings.color.palette[slug=base]`              | `#ffffff`           |
| `color/surface`           | `settings.color.palette[slug=surface]`           | `#f5f5f7`           |
| `color/surface-strong`    | `settings.color.palette[slug=surface-strong]`    | `#eeeef2`           |
| `color/text`              | `settings.color.palette[slug=contrast]`          | `#111114`           |
| `color/text-muted`        | `settings.color.palette[slug=muted]`             | `#6b6b76`           |
| `color/border`            | `settings.color.palette[slug=border]`            | `#e5e5ea`           |
| `color/primary`           | `settings.color.palette[slug=primary]`           | `#4f46e5`           |
| `color/primary-hover`     | `settings.color.palette[slug=primary-hover]`     | `#4338ca`           |
| `color/secondary`         | `settings.color.palette[slug=secondary]`         | `#0ea5e9`           |
| `color/accent`            | `settings.color.palette[slug=accent]`            | `#f59e0b`           |
| `font/family-sans`        | `settings.typography.fontFamilies[slug=sans]`    | Inter               |
| `font/family-serif`       | `settings.typography.fontFamilies[slug=serif]`   | Newsreader          |
| `font/family-mono`        | `settings.typography.fontFamilies[slug=mono]`    | JetBrains Mono      |
| `font/size-*`             | `settings.typography.fontSizes[slug=*]`          | small…display       |
| `space/*`                 | `settings.spacing.spacingSizes[slug=20…80]`      | 0.5rem…6rem         |
| `radius/*`                | `settings.custom.radius.*`                        | 6/12/20/999px       |
| `shadow/*`                | `settings.shadow.presets[slug=sm/md/lg]`         | see theme.json      |

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
