/*
 * NOW uploader — a minimal Express backend for the NowPlix blog.
 *
 * It exists for the awkward things that are painful to push through chat:
 * heavy/bulk media uploads, setting featured images, seeding draft posts.
 * Everyday content edits keep happening in chat / the WordPress MCP.
 *
 * It talks to the WordPress REST API with an Application Password and runs
 * SEPARATELY from WordPress (locally, or on any small Node host) — it is not
 * part of the PHP theme runtime. See ./README.md.
 */
require('dotenv').config();
const path = require('path');
const express = require('express');
const multer = require('multer');

const { WP_URL, WP_USER, WP_APP_PASSWORD, PORT = 4000 } = process.env;

if (!WP_URL || !WP_USER || !WP_APP_PASSWORD) {
	console.error('✗ Missing config. Copy .env.example → .env and fill WP_URL / WP_USER / WP_APP_PASSWORD.');
	process.exit(1);
}

const BASE = WP_URL.replace(/\/$/, '');
const AUTH = 'Basic ' + Buffer.from(`${WP_USER}:${WP_APP_PASSWORD}`).toString('base64');
const wp = (p) => `${BASE}/wp-json/wp/v2${p}`;

const app = express();
app.use(express.json({ limit: '1mb' }));
app.use(express.static(path.join(__dirname, 'public')));

const upload = multer({
	storage: multer.memoryStorage(),
	limits: { fileSize: 64 * 1024 * 1024 }, // 64 MB; the WP server may cap lower.
});

const jsonError = (res, status, body) => res.status(status).json(typeof body === 'string' ? { error: body } : body);

// Health / connection check.
app.get('/api/health', async (_req, res) => {
	try {
		const r = await fetch(wp('/users/me?context=edit'), { headers: { Authorization: AUTH } });
		const data = await r.json();
		if (!r.ok) return jsonError(res, r.status, data);
		res.json({ ok: true, site: BASE, user: data.name, roles: data.roles });
	} catch (e) {
		jsonError(res, 502, String(e));
	}
});

// Upload one file to the media library. multipart/form-data: file (+ optional alt, title).
app.post('/api/media', upload.single('file'), async (req, res) => {
	try {
		if (!req.file) return jsonError(res, 400, 'No file provided (field name must be "file").');
		const r = await fetch(wp('/media'), {
			method: 'POST',
			headers: {
				Authorization: AUTH,
				'Content-Disposition': `attachment; filename="${req.file.originalname.replace(/"/g, '')}"`,
				'Content-Type': req.file.mimetype || 'application/octet-stream',
			},
			body: req.file.buffer,
		});
		const data = await r.json();
		if (!r.ok) return jsonError(res, r.status, data);

		// Optional metadata.
		const patch = {};
		if (req.body.alt) patch.alt_text = req.body.alt;
		if (req.body.title) patch.title = req.body.title;
		if (Object.keys(patch).length) {
			await fetch(wp(`/media/${data.id}`), {
				method: 'POST',
				headers: { Authorization: AUTH, 'Content-Type': 'application/json' },
				body: JSON.stringify(patch),
			});
		}
		res.json({ id: data.id, source_url: data.source_url, mime_type: data.mime_type });
	} catch (e) {
		jsonError(res, 500, String(e));
	}
});

// Set a post's featured image. JSON: { postId, mediaId }.
app.post('/api/featured', async (req, res) => {
	try {
		const postId = Number(req.body.postId);
		const mediaId = Number(req.body.mediaId);
		if (!postId || !mediaId) return jsonError(res, 400, 'postId and mediaId are required numbers.');
		const r = await fetch(wp(`/posts/${postId}`), {
			method: 'POST',
			headers: { Authorization: AUTH, 'Content-Type': 'application/json' },
			body: JSON.stringify({ featured_media: mediaId }),
		});
		const data = await r.json();
		if (!r.ok) return jsonError(res, r.status, data);
		res.json({ id: data.id, featured_media: data.featured_media, link: data.link });
	} catch (e) {
		jsonError(res, 500, String(e));
	}
});

// Create a draft post (seed the problematic stuff, then finish in chat/wp-admin).
// JSON: { title, content?, excerpt?, categories?: number[], featured_media?, status? }.
app.post('/api/post', async (req, res) => {
	try {
		const { title, content = '', excerpt = '', categories, featured_media, status = 'draft' } = req.body;
		if (!title) return jsonError(res, 400, 'title is required.');
		const payload = { title, content, excerpt, status };
		if (Array.isArray(categories)) payload.categories = categories;
		if (featured_media) payload.featured_media = Number(featured_media);
		const r = await fetch(wp('/posts'), {
			method: 'POST',
			headers: { Authorization: AUTH, 'Content-Type': 'application/json' },
			body: JSON.stringify(payload),
		});
		const data = await r.json();
		if (!r.ok) return jsonError(res, r.status, data);
		res.json({ id: data.id, status: data.status, link: data.link });
	} catch (e) {
		jsonError(res, 500, String(e));
	}
});

app.listen(PORT, () => {
	console.log(`NOW uploader → http://localhost:${PORT}  (site: ${BASE})`);
});
