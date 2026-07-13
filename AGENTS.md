# AGENTS.md

This repo's agent guidance lives in **[`CLAUDE.md`](./CLAUDE.md)** — read it first.

Quick orientation:

- **NOW** is a WordPress **block theme (FSE)**; the repo root **is** the theme.
- Style via **`theme.json` first**; use `assets/css/theme.css` only for what
  `theme.json` can't express. Never hardcode hex/rem — use design tokens.
- Product context for the `impeccable` skill: [`PRODUCT.md`](./PRODUCT.md).
  Visual system: [`DESIGN.md`](./DESIGN.md) and [`DESIGN-SYNC.md`](./DESIGN-SYNC.md).
- Installed design skills (`.claude/skills/`): `impeccable`,
  `design-taste-frontend`, `emil-design-eng` (+ animation companions). Honour
  the Impeccable absolute bans and Emil's motion rules documented in `CLAUDE.md`.
