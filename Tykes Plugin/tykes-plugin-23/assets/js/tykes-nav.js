/**
 * Tykes Nav Dropdown — Standalone v2.2
 * Works independently of tykes-ds.js.
 * Uses MutationObserver so it works even when the header is
 * injected AFTER this script runs (wp_body_open timing).
 */
(function () {
    'use strict';

    /* ── Helpers ── */
    function $$(sel, ctx) {
        return Array.from((ctx || document).querySelectorAll(sel));
    }

    /* ── State ── */
    var initialized = {};

    /* ── Close all open dropdowns except one ── */
    function closeAll(except) {
        $$('.nav-item.open').forEach(function (item) {
            if (item === except) return;
            item.classList.remove('open');
            var a = item.querySelector(':scope > a');
            if (a) a.setAttribute('aria-expanded', 'false');
        });
    }

    /* ── Init a single nav item ── */
    function initItem(item) {
        var id = item.dataset.tykesNavId;
        if (!id) {
            id = 'nav-' + Math.random().toString(36).slice(2);
            item.dataset.tykesNavId = id;
        }
        if (initialized[id]) return;
        initialized[id] = true;

        var dropdown = item.querySelector('.nav-dropdown');
        if (!dropdown) return; // leaf item — nothing to do

        var link = item.querySelector(':scope > a');
        if (!link) return;

        link.setAttribute('aria-haspopup', 'true');
        link.setAttribute('aria-expanded', 'false');

        link.addEventListener('click', function (e) {
            var href = link.getAttribute('href') || '';
            var realHref = href && href !== '#' && href !== 'javascript:void(0)' && href !== 'javascript:;';
            var isMouse = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

            // On desktop mouse + real link: let CSS :hover work naturally, don't intercept
            if (isMouse && realHref) return;

            e.preventDefault();
            var wasOpen = item.classList.contains('open');
            closeAll(item);
            item.classList.toggle('open', !wasOpen);
            link.setAttribute('aria-expanded', item.classList.contains('open') ? 'true' : 'false');
        });

        // Close when a dropdown child link is clicked
        $$('a', dropdown).forEach(function (child) {
            child.addEventListener('click', function () {
                item.classList.remove('open');
                link.setAttribute('aria-expanded', 'false');
            });
        });
    }

    /* ── Init all current nav items ── */
    function initAll() {
        $$('.nav-item').forEach(initItem);
    }

    /* ── Global: close on outside click ── */
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.nav-item')) closeAll(null);
    });

    /* ── Global: Escape closes dropdowns ── */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAll(null);
    });

    /* ── Run immediately (items already in DOM) ── */
    initAll();

    /* ── MutationObserver: catches header injected via wp_body_open ── */
    if (window.MutationObserver) {
        new MutationObserver(function (mutations) {
            var needsInit = false;
            mutations.forEach(function (m) {
                m.addedNodes.forEach(function (node) {
                    if (node.nodeType !== 1) return;
                    if (node.classList && node.classList.contains('nav-item')) {
                        initItem(node);
                    } else if (node.querySelector) {
                        node.querySelectorAll('.nav-item').forEach(initItem);
                    }
                });
            });
        }).observe(document.documentElement, { childList: true, subtree: true });
    }

    /* ── Also re-run on DOMContentLoaded in case header wasn't ready ── */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    }

})();
