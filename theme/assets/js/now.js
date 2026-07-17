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
			var max = rail.scrollWidth - rail.clientWidth;
			var target = rail.scrollLeft + dir * Math.max(320, rail.clientWidth * 0.8);
			// Clamp so the rail never scrolls past its content into empty space.
			rail.scrollTo({ left: Math.min(max, Math.max(0, target)), behavior: 'smooth' });
		});
	});

	// Disable each arrow once its rail is already at that end.
	document.querySelectorAll('.now-rail').forEach(function (rail) {
		var btns = document.querySelectorAll('.now-rail-btn[data-rail="' + rail.id + '"]');
		if (!btns.length) return;
		var raf = 0;
		var update = function () {
			raf = 0;
			// 2px tolerance: fractional zoom/HiDPI can leave a sub-pixel gap
			// that scrollLeft never fully closes.
			var max = rail.scrollWidth - rail.clientWidth - 2;
			btns.forEach(function (b) {
				var dir = parseInt(b.getAttribute('data-dir'), 10) || 1;
				b.disabled = dir < 0 ? rail.scrollLeft <= 0 : rail.scrollLeft >= max;
			});
		};
		// rAF-coalesced: scroll/resize can fire many times per frame, and the
		// scrollWidth/clientWidth reads force a reflow if run mid-mutation.
		var schedule = function () {
			if (!raf) { raf = requestAnimationFrame(update); }
		};
		rail.addEventListener('scroll', schedule, { passive: true });
		window.addEventListener('resize', schedule);
		window.addEventListener('load', schedule);
		schedule();
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

			// Geometry is cached and only re-read when layout can actually
			// change (resize, full load, webfont swap) — reading offsetTop per
			// scroll event forces a reflow on every frame.
			var tops = [];
			var proseBottom = 0;
			var spyRaf = 0;
			var measure = function () {
				proseBottom = prose.offsetTop + prose.offsetHeight;
				tops = headings.map(function (h) { return h.offsetTop; });
			};
			var spy = function () {
				spyRaf = 0;
				var pos = window.pageYOffset + 120;
				// Past the end of the article (e.g. the TOC card sits below the
				// prose on mobile): nothing is being read, highlight nothing.
				var current = pos > proseBottom ? -1 : 0;
				if (current === 0) {
					tops.forEach(function (t, i) { if (t <= pos) { current = i; } });
				}
				links.forEach(function (a, i) { a.classList.toggle('now-active', i === current); });
			};
			var scheduleSpy = function () {
				if (!spyRaf) { spyRaf = requestAnimationFrame(spy); }
			};
			var remeasure = function () { measure(); scheduleSpy(); };
			window.addEventListener('scroll', scheduleSpy, { passive: true });
			window.addEventListener('resize', remeasure);
			window.addEventListener('load', remeasure);
			if (document.fonts && document.fonts.ready) { document.fonts.ready.then(remeasure); }
			remeasure();
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

	/* ---- 4. Header dropdown (Categories mega-menu) ----
	 * Hover/:focus-within open it via CSS; this adds click + touch + keyboard
	 * toggling with a real aria-expanded state, so the menu also works where
	 * hover doesn't exist (touch screens) or a pure toggle item has no URL. */
	document.querySelectorAll('.now-nav-item > .now-dropdown-toggle').forEach(function (toggle) {
		var item = toggle.parentElement;
		var setOpen = function (open) {
			item.classList.toggle('now-open', open);
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		};
		var isToggleOnly = !toggle.hasAttribute('href');
		toggle.addEventListener('click', function (e) {
			// Pure toggles ('#' items) always toggle; real links toggle on the
			// first tap on touch devices (no hover) and navigate on the second.
			var open = item.classList.contains('now-open');
			if (isToggleOnly || (!open && window.matchMedia('(hover: none)').matches)) {
				e.preventDefault();
				setOpen(!open);
			}
		});
		toggle.addEventListener('keydown', function (e) {
			if (isToggleOnly && (e.key === 'Enter' || e.key === ' ')) {
				e.preventDefault();
				setOpen(!item.classList.contains('now-open'));
			}
		});
		document.addEventListener('click', function (e) {
			if (!item.contains(e.target)) { setOpen(false); }
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && item.classList.contains('now-open')) {
				setOpen(false); toggle.focus();
			}
		});
	});

	/* ---- 5. Mobile menu (burger) ---- */
	var burger = document.querySelector('.now-burger');
	var mobileMenu = document.getElementById('now-mobile-menu');
	if (burger && mobileMenu) {
		var setOpen = function (open) {
			burger.setAttribute('aria-expanded', open ? 'true' : 'false');
			mobileMenu.hidden = !open;
		};
		burger.addEventListener('click', function () {
			setOpen(burger.getAttribute('aria-expanded') !== 'true');
		});
		// Close on Escape or when a menu link is followed.
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && burger.getAttribute('aria-expanded') === 'true') {
				setOpen(false); burger.focus();
			}
		});
		mobileMenu.addEventListener('click', function (e) {
			if (e.target.closest('a')) { setOpen(false); }
		});
	}
})();
