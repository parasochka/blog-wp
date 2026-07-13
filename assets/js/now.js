/*
 * NOW theme - progressive reading enhancements.
 *
 * Dependency-free. Everything here is ADDITIVE: the article is complete and
 * readable with JavaScript disabled. This script only layers on wayfinding
 * (auto table of contents, scroll-spy), a reading-time estimate, a scroll
 * progress bar, and share links. Nothing here gates content visibility.
 *
 * Motion follows the Emil Kowalski rules already in theme.css: no scroll
 * event listeners (the progress bar is pure CSS scroll-timeline; the spy uses
 * IntersectionObserver), and prefers-reduced-motion is honoured in CSS.
 */
(function () {
	'use strict';

	var article = document.querySelector('.now-article-main .wp-block-post-content')
		|| document.querySelector('.wp-block-post-content');

	// Only enhance single articles. They carry the .now-article layout
	// wrapper; the body.single class is a secondary signal.
	var isArticle = article
		&& (document.querySelector('.now-article') || document.body.classList.contains('single'));
	if (!isArticle) {
		return;
	}

	/* ---- Reading time -------------------------------------------------- */
	var timeEl = document.querySelector('.now-reading-time');
	if (timeEl) {
		var words = (article.textContent || '').trim().split(/\s+/).filter(Boolean).length;
		var minutes = Math.max(1, Math.round(words / 200));
		timeEl.textContent = minutes + ' min read';
	}

	/* ---- Reading progress bar (animated by CSS scroll-timeline) -------- */
	if (!document.querySelector('.now-progress')) {
		var bar = document.createElement('div');
		bar.className = 'now-progress';
		bar.setAttribute('aria-hidden', 'true');
		document.body.appendChild(bar);
	}

	/* ---- Auto table of contents --------------------------------------- */
	var tocRoot = document.querySelector('.now-toc');
	var tocList = document.getElementById('now-toc-list');
	var headings = Array.prototype.slice.call(article.querySelectorAll('h2, h3'));

	function slugify(text) {
		return text
			.toLowerCase()
			.replace(/[^\p{L}\p{N}\s-]/gu, '')
			.trim()
			.replace(/\s+/g, '-')
			.slice(0, 60);
	}

	var links = [];

	if (tocRoot && tocList && headings.length >= 2) {
		var used = {};
		headings.forEach(function (heading) {
			var id = heading.id;
			if (!id) {
				id = slugify(heading.textContent || 'section') || 'section';
				if (used[id]) {
					id = id + '-' + used[id]++;
				} else {
					used[id] = 1;
				}
				heading.id = id;
			}

			var li = document.createElement('li');
			li.className = heading.tagName === 'H3' ? 'now-toc-h3' : 'now-toc-h2';

			var a = document.createElement('a');
			a.href = '#' + id;
			a.textContent = heading.textContent;
			li.appendChild(a);
			tocList.appendChild(li);
			links.push({ id: id, link: a });
		});

		tocRoot.classList.add('is-populated');

		/* ---- Scroll-spy via IntersectionObserver ---------------------- */
		if ('IntersectionObserver' in window) {
			var byId = {};
			links.forEach(function (item) { byId[item.id] = item.link; });

			var visible = {};
			var observer = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					visible[entry.target.id] = entry.isIntersecting;
				});

				var activeId = null;
				for (var i = 0; i < headings.length; i++) {
					if (visible[headings[i].id]) { activeId = headings[i].id; break; }
				}

				links.forEach(function (item) {
					item.link.classList.toggle('is-active', item.id === activeId);
				});
			}, { rootMargin: '-96px 0px -66% 0px', threshold: 0 });

			headings.forEach(function (heading) { observer.observe(heading); });
		}
	}

	/* ---- Share links -------------------------------------------------- */
	var shareRoot = document.querySelector('.now-share-links');
	if (shareRoot) {
		var url = encodeURIComponent(window.location.href);
		var title = encodeURIComponent(document.title || '');
		var targets = {
			x: 'https://twitter.com/intent/tweet?url=' + url + '&text=' + title,
			linkedin: 'https://www.linkedin.com/sharing/share-offsite/?url=' + url,
			facebook: 'https://www.facebook.com/sharer/sharer.php?u=' + url,
			telegram: 'https://t.me/share/url?url=' + url + '&text=' + title
		};

		shareRoot.querySelectorAll('a[data-share]').forEach(function (a) {
			var type = a.getAttribute('data-share');
			if (targets[type]) {
				a.href = targets[type];
				a.target = '_blank';
				a.rel = 'noopener noreferrer';
			}
		});

		var copyBtn = shareRoot.querySelector('button[data-copy]');
		if (copyBtn && navigator.clipboard) {
			var original = copyBtn.textContent;
			copyBtn.addEventListener('click', function () {
				navigator.clipboard.writeText(window.location.href).then(function () {
					copyBtn.textContent = 'Copied';
					setTimeout(function () { copyBtn.textContent = original; }, 1800);
				});
			});
		} else if (copyBtn) {
			copyBtn.hidden = true;
		}
	}
})();
