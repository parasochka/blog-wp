# AGENTS.md

This repo's agent guidance lives in **[`CLAUDE.md`](./CLAUDE.md)** — read it first.

Quick orientation:

- **NOW** is a WordPress **classic PHP theme**; the repo root **is** the theme.
- **Styling source of truth is `_ds/…/tokens/*.css`** (enqueued as-is). Put
  shared CSS in `assets/css/now.css`; put layout in the `*.php` templates,
  mirroring the design-handoff screens. Never hardcode hex/px that duplicates a
  token — always `var(--…)`.
- Templates keep the handoff HTML/inline styles verbatim; only dynamic slots
  become WordPress calls. Repeated markup lives in helpers in `functions.php`
  (`now_render_card`, etc.).
- Product context for the `impeccable` skill: [`PRODUCT.md`](./PRODUCT.md).
  Deploy & migration: [`MIGRATION.md`](./MIGRATION.md).
- Installed design skills (`.claude/skills/`): `impeccable`,
  `design-taste-frontend`, `emil-design-eng` (+ animation companions). Honour
  the Impeccable absolute bans and Emil's motion rules documented in `CLAUDE.md`.
