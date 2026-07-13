/*
 * NOW theme — progressive enhancements. Dependency-free, fully optional:
 * every template reads perfectly with this disabled.
 *   1. Category rail arrows (‹ ›)
 *   2. Article "On this page" TOC + scrollspy
 *   3. Share "Copy link"
 */
(function () {
	'use strict';

	/* ---- 1. Category rail arrows ---- */
	document.querySelectorAll('.now-rail-btn').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var rail = document.getElementById(btn.getAttribute('data-rail'));
			if (!rail) return;
			var dir = parseInt(btn.getAttribute('data-dir'), 10) || 1;
			rail.scrollBy({ left: dir * Math.max(320, rail.clientWidth * 0.8), behavior: 'smooth' });
		});
	});

	/* ---- 2. Article TOC + scrollspy ---- */
	var list = document.querySelector('.now-toc-list');
	var prose = document.querySelector('.now-prose');
	if (list && prose) {
		var headings = Array.prototype.slice.call(prose.querySelectorAll('h2'));
		var slug = function (t) {
			return t.toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
		};
		var links = [];
		headings.forEach(function (h) {
			if (!h.id) { h.id = slug(h.textContent) || ('sec-' + links.length); }
			var li = document.createElement('li');
			var a = document.createElement('a');
			a.className = 'now-toc-link';
			a.href = '#' + h.id;
			a.textContent = h.textContent;
			a.addEventListener('click', function (e) {
				e.preventDefault();
				var top = h.getBoundingClientRect().top + window.pageYOffset - 92;
				window.scrollTo({ top: top, behavior: 'smooth' });
				history.replaceState(null, '', '#' + h.id);
			});
			li.appendChild(a);
			list.appendChild(li);
			links.push(a);
		});

		if (headings.length >= 2) {
			var card = document.querySelector('.now-toc-card');
			if (card) { card.style.display = 'block'; }

			var spy = function () {
				var pos = window.pageYOffset + 120;
				var current = 0;
				headings.forEach(function (h, i) { if (h.offsetTop <= pos) { current = i; } });
				links.forEach(function (a, i) { a.classList.toggle('now-active', i === current); });
			};
			window.addEventListener('scroll', spy, { passive: true });
			spy();
		}
	}

	/* ---- 3. Copy link ---- */
	document.querySelectorAll('.now-copy-link').forEach(function (btn) {
		var original = btn.textContent;
		btn.addEventListener('click', function () {
			var url = btn.getAttribute('data-url') || window.location.href;
			var done = function () {
				btn.textContent = 'Copied ✓';
				setTimeout(function () { btn.textContent = original; }, 1500);
			};
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url).then(done, done);
			} else {
				var t = document.createElement('textarea');
				t.value = url; document.body.appendChild(t); t.select();
				try { document.execCommand('copy'); } catch (e) {}
				document.body.removeChild(t); done();
			}
		});
	});
})();
