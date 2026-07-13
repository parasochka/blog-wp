# NOW uploader — minimal Express backend

A tiny helper for the **awkward, heavy things** that are painful to push through
chat: bulk/large **media uploads**, setting **featured images**, and seeding
**draft posts**. Everyday content edits keep happening in chat / the WordPress
MCP — this is only for the problematic uploads.

It talks to the WordPress REST API with an **Application Password** and runs
**separately** from WordPress (locally, or on any small Node host). It is **not**
part of the PHP theme — WP Pusher will copy this folder to the server, where it
just sits inert. Run it wherever Node runs.

## Setup

```bash
cd tools/uploader
cp .env.example .env      # then fill WP_URL / WP_USER / WP_APP_PASSWORD
npm install
npm start                 # → http://localhost:4000
```

Create the Application Password in **wp-admin → Users → Your Profile →
Application Passwords** (an admin account so uploads are allowed).

Open `http://localhost:4000` for a small dark UI: drag-drop an image to upload
it to the media library (returns the media ID + URL), then set it as a post's
featured image by ID.

## API

| Method + path | Body | Does |
| --- | --- | --- |
| `GET /api/health` | — | Verifies the connection; returns the authenticated user + roles. |
| `POST /api/media` | multipart: `file` (+ optional `alt`, `title`) | Uploads to the media library. Returns `{ id, source_url, mime_type }`. |
| `POST /api/featured` | JSON: `{ postId, mediaId }` | Sets a post's featured image. |
| `POST /api/post` | JSON: `{ title, content?, excerpt?, categories?, featured_media?, status? }` | Creates a post (draft by default). |

```bash
# examples
curl -F file=@art-liquid.png -F alt="Liquid render" http://localhost:4000/api/media
curl -X POST http://localhost:4000/api/featured \
  -H 'Content-Type: application/json' -d '{"postId":76,"mediaId":180}'
```

## Notes

- `.env` and `node_modules/` are git-ignored — credentials never leave your machine.
- The theme ships the 3D illustrations under `assets/img/`; after the theme is
  deployed they are also reachable at
  `https://blog.nowplix.dev/wp-content/themes/blog-wp/assets/img/…` if you'd
  rather pull them into the media library from a URL.
