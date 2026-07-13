# MEDIA-GUIDE.md — assets, sizes & admin setup

Everything you upload by hand, where it goes in wp-admin, and the exact
aspect ratio / pixel size so nothing crops badly or shows a seam. Plus the
one-time WordPress settings the redesign expects (permalinks, homepage,
menu, the article sidebar).

The NOW illustration set is **dark-navy 3D art on a `#0f0f2d` background** —
the same colour as the theme base. Every image container in the theme has a
`base-deep` backing and uses `object-fit: cover`, so dark art blends with no
visible edge and never letterboxes. Keep that in mind: **light or crop-safe
edges are not required — a full-bleed dark render is exactly what fits.**

---

## Part 1 — One-time WordPress settings

### 1.1 Permalinks (the classic `/category/post` URL you want)

**Settings → Permalinks → Custom Structure**, paste:

```
/%category%/%postname%/
```

Save. Posts now live at `/ai-tech/game-recommendation-engines-engagement/`
exactly like your current article. (WordPress shows the first category in
the URL; assign one primary category per post.)

### 1.2 Homepage as a feed of articles

**Settings → Reading → Your homepage displays → Your latest posts.**
That renders `templates/home.html` (hero intro + featured lead + category
pills + "Latest stories" magazine grid + newsletter). Nothing else to do.

### 1.3 Optional: a category-structured homepage

You said you also want each category as its own row of cards. WordPress
can't guess your category IDs from a file, so this one is assembled once in
the editor (2 minutes) and is then fully yours:

1. **Pages → Add New**, title it `Home`, publish (leave it empty).
2. **Settings → Reading → A static page → Homepage: Home.**
3. Open **Appearance → Editor → Pages → Home**, and for each category:
   - Click **+ → Patterns → NOW → "Category row"** to insert one row.
   - Select the row's **Query Loop** block → in the sidebar open
     **Filters → Taxonomies → Categories** and pick the category.
   - Set the heading text and the "View all" link to that category.
   - Repeat for every category you want as a row.
4. Drop a **"Featured lead story"** pattern above the rows and a
   **"Newsletter CTA"** below if you like.

Each row is a horizontal scroll-snap rail on tablet and a clean 4-up grid on
wide screens, so a category can hold many stories without a wall of cards.

### 1.4 The navigation menu (header)

Block themes edit the menu visually, not under the old *Appearance → Menus*:

1. **Appearance → Editor → Navigation** (or open the header and click the
   Navigation block).
2. Use **+** to add pages, custom links, or **category** links.
3. To mirror the "browse by category" bar, add your main categories here.

The header already renders your logo + the menu; the mobile breakpoint
collapses the menu into a hamburger automatically.

### 1.5 The article sidebar

`templates/single.html` ships a floating (sticky) sidebar with three cards:

- **On this page** — an automatic table of contents. It builds itself from
  the H2/H3 headings in your article (no plugin, no manual list) and
  highlights the section you're reading. It appears only when the article
  has at least two headings, and hides on mobile where it's noise.
- **Share** — X / LinkedIn / Telegram / copy-link, wired to the live URL.
- **Play on NowPlix** — the "Article sidebar CTA" pattern. Edit its text and
  button, or swap in a different pattern.

To add your own card (e.g. a square illustration promo): open
**Appearance → Editor → Templates → Single**, click into the `aside`
(sidebar), and add an **Image** block or another pattern. Give an image
block the CSS class `now-sidebar-image` (Block → Advanced → Additional CSS
class) to get the rounded 1:1 art slot described below.

---

## Part 2 — Graphics you upload, with exact sizes

### 2.1 Quick reference

| Asset | Where in wp-admin | Ratio | Upload size (px) | Format | Target weight |
| --- | --- | --- | --- | --- | --- |
| **Logo** (NOW wordmark) | Editor → Header → Site Logo | ~3.3 : 1 | 660 × 200 | SVG or PNG (transparent) | < 30 KB |
| **Site icon / favicon** | Settings → General → Site Icon | 1 : 1 | 512 × 512 | PNG | < 50 KB |
| **Featured image** (per post) | Post → Featured image | 16 : 9 | 1600 × 900 | WebP / JPG | < 300 KB |
| **Hero background** | Cover block → Replace | ~2.4 : 1 | 2000 × 840 | WebP / JPG | < 400 KB |
| **Section band bg** | Cover / Group background | 16 : 9 | 1920 × 1080 | WebP / JPG | < 400 KB |
| **Sidebar square art** | Image block `.now-sidebar-image` | 1 : 1 | 800 × 800 | WebP / PNG | < 200 KB |
| **Social share (OG)** | SEO plugin / per post | 1.91 : 1 | 1200 × 630 | JPG / PNG | < 400 KB |

Your set maps cleanly onto this: the **wide banner renders** (top row of
your library, ~2.4:1) are hero / section backgrounds; the **wordmark
renders** (`NOW`, `302`) are the logo; the **square renders** (spheres,
glass shapes) are sidebar art or featured images.

### 2.2 The featured image, in detail (the one that matters most)

One featured image per post is reused at three different crops:

- **16:9** on the listing cards and "More stories".
- **21:9** as the wide banner at the top of the single article.
- **3:2** in the "Featured lead story" on the homepage (the newest post).

So **upload one 16:9 image at 1600 × 900** and keep the subject inside the
**central 3:2 safe zone** (the middle ~85% horizontally, full height). The
theme crops with `object-fit: cover` from the centre, so a centred subject
survives all three ratios. Avoid putting text or a face hard against the
left/right edge — that edge is what the 3:2 and 21:9 crops trim.

> Safe zone on a 1600 × 900 image: keep anything critical within the
> centred **1350 × 900** box (75 px clear on the left and right).

### 2.3 The logo

- Your NOW wordmark is light art on transparent → it reads on the dark
  header. Export **SVG** (crispest) or a **2× PNG with a transparent
  background**.
- It displays at **34 px tall** in the header; a 660 × 200 source gives
  clean retina rendering.
- Upload: **Appearance → Editor**, open the header, select the **Site Logo**
  block → upload. Toggle **"Use as site icon"** if you want the same mark as
  the favicon, or set a dedicated square icon under **Settings → General →
  Site Icon**.
- If your logo is a full wordmark, you can delete the **Site Title** text
  block next to it in the header so the name isn't shown twice.

### 2.4 Hero & section backgrounds

- Use the **"Hero with background image"** pattern (+ → Patterns → NOW). It's
  a Cover block: select it → **Replace** → upload your wide render.
- The Cover has a 60% `base-deep` overlay so the headline always passes
  contrast over busy art. Lower it (Block → Overlay → Opacity) if your image
  is already dark, raise it if it's bright.
- Best source ratio **~2.4:1** (matches your banner renders). The block is
  full-bleed and `min-height: 480px`, cropping from centre — keep focal
  interest centred.

### 2.5 Sidebar square art

- Add an **Image** block in the article sidebar, upload a **1:1 800 × 800**
  render, and add the class **`now-sidebar-image`** for the rounded, cropped,
  dark-backed 1:1 frame.
- Great for a glass-sphere render above the CTA, or a category illustration.

### 2.6 Formats & optimisation

- Prefer **WebP** for photos/renders (30–40% smaller than JPG at the same
  quality). **SVG** for the logo. **PNG** only when you need transparency on
  a raster.
- WordPress generates responsive sizes and lazy-loads images automatically —
  just upload one high-quality master at the sizes above.
- Keep master uploads under the target weights so the page stays fast (the
  theme targets LCP < 2.5s).

---

## Part 3 — What lives in the theme vs. what you upload

- **In the theme (git):** all structure, layout, colour, type, spacing,
  motion — every template, pattern and style. You never touch code to
  publish.
- **You upload in wp-admin:** the logo, the site icon, and each post's
  featured image; optionally hero/section/sidebar art via the patterns
  above. Posts, pages, categories and the menu are content, so they live in
  WordPress, not in the repo.

That's the whole loop: push design from GitHub (WP Pusher pulls it), upload
your art in wp-admin at the sizes above, and the two meet on the live site.
