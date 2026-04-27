/**
 * Tykes Design System - Core JS
 * Handles header states, mobile menu, and global interactive elements.
 */

(function() {
    'use strict';

    // Helper: Select single or multiple elements.
    function qs( sel, ctx )  { return ( ctx || document ).querySelector( sel ); }
    function qsa( sel, ctx ) { return Array.from( ( ctx || document ).querySelectorAll( sel ) ); }

    /* ── 1. Header scroll state ── */

    var header = qs( '#siteHeader' );

    function onScroll() {
        if ( header ) {
            header.classList.toggle( 'scrolled', window.scrollY > 40 );
        }
    }

    window.addEventListener( 'scroll', onScroll, { passive: true } );
    onScroll(); // Run once on load.

    /* ── 2. Mobile Drawer ── */

    var drawer    = qs( '#mobileDrawer' );
    var hamBtn    = qs( '#hamBtn' );
    var drawerOpen = false;

    function openDrawer() {
        if ( ! drawer ) { return; }
        drawerOpen = true;
        drawer.classList.add( 'open' );
        drawer.style.pointerEvents = 'all';
        if ( hamBtn ) {
            hamBtn.classList.add( 'open' );
            hamBtn.setAttribute( 'aria-expanded', 'true' );
        }
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        if ( ! drawer ) { return; }
        drawerOpen = false;
        drawer.classList.remove( 'open' );
        drawer.style.pointerEvents = 'none';
        if ( hamBtn ) {
            hamBtn.classList.remove( 'open' );
            hamBtn.setAttribute( 'aria-expanded', 'false' );
        }
        document.body.style.overflow = '';
    }

    window.toggleDrawer = function () {
        drawerOpen ? closeDrawer() : openDrawer();
    };

    window.closeDrawer = closeDrawer;

    window.handleDrawerClick = function ( e ) {
        // Close only when clicking the semi-transparent backdrop, not the panel.
        if ( e.target === drawer ) { closeDrawer(); }
    };

    /* ── 3. Mobile submenu accordion ── */

    window.toggleMobSub = function ( id ) {
        var el = qs( '#' + id );
        if ( el ) { el.classList.toggle( 'open' ); }
    };

    /* ── 3b. Desktop dropdown — robust JS toggle + MutationObserver ──
     *
     * WHY: The header is injected via wp_body_open after the script runs.
     * DOMContentLoaded alone is too early. We use MutationObserver to detect
     * when .nav-item elements appear and initialise them immediately.
     *
     * On mouse devices (hover:hover pointer:fine) CSS :hover handles the
     * show/hide visually. JS adds .open on click for touch/keyboard and also
     * rotates the chevron. Both mechanisms work together.
     */

    function closeAllDropdowns( except ) {
        qsa( '.nav-item.open' ).forEach( function ( item ) {
            if ( item !== except ) {
                item.classList.remove( 'open' );
                var parentLink = item.querySelector( ':scope > a' );
                if ( parentLink ) { parentLink.setAttribute( 'aria-expanded', 'false' ); }
            }
        } );
    }

    function initOneNavItem( item ) {
        if ( item._tykesInit ) { return; }
        item._tykesInit = true;

        var dropdown = item.querySelector( '.nav-dropdown' );
        if ( ! dropdown ) { return; } // no children

        var parentLink = item.querySelector( ':scope > a' );
        if ( ! parentLink ) { return; }

        parentLink.setAttribute( 'aria-haspopup', 'true' );
        parentLink.setAttribute( 'aria-expanded', 'false' );

        parentLink.addEventListener( 'click', function ( e ) {
            var href = parentLink.getAttribute( 'href' ) || '';
            var hasRealHref = href && href !== '#' && href !== 'javascript:void(0)' && href !== 'javascript:;';
            var hoverDevice = window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches;

            // On a real mouse with a real link, let the link navigate;
            // CSS :hover already reveals the dropdown on mouseover.
            if ( hoverDevice && hasRealHref ) { return; }

            e.preventDefault();
            var isOpen = item.classList.contains( 'open' );
            closeAllDropdowns( item );
            item.classList.toggle( 'open', !isOpen );
            parentLink.setAttribute( 'aria-expanded', item.classList.contains( 'open' ) ? 'true' : 'false' );
        } );

        // Clicking any child link closes the dropdown.
        dropdown.querySelectorAll( 'a' ).forEach( function ( child ) {
            child.addEventListener( 'click', function () {
                item.classList.remove( 'open' );
                parentLink.setAttribute( 'aria-expanded', 'false' );
            } );
        } );
    }

    function initAllDropdowns() {
        qsa( '.nav-item' ).forEach( initOneNavItem );
    }

    // Close when clicking outside the nav.
    document.addEventListener( 'click', function ( e ) {
        if ( ! e.target.closest( '.nav-item' ) ) { closeAllDropdowns( null ); }
    } );

    // Escape key closes open dropdowns.
    document.addEventListener( 'keydown', function ( e ) {
        if ( e.key === 'Escape' ) { closeAllDropdowns( null ); }
    } );

    // Run immediately (catches items already in DOM).
    initAllDropdowns();

    // Watch for the header being injected after page load (wp_body_open timing).
    if ( window.MutationObserver ) {
        var navObserver = new MutationObserver( function ( mutations ) {
            mutations.forEach( function ( m ) {
                m.addedNodes.forEach( function ( node ) {
                    if ( node.nodeType !== 1 ) { return; }
                    if ( node.classList && node.classList.contains( 'nav-item' ) ) {
                        initOneNavItem( node );
                    }
                    // Also check children of injected containers.
                    node.querySelectorAll && node.querySelectorAll( '.nav-item' ).forEach( initOneNavItem );
                } );
            } );
        } );
        navObserver.observe( document.body || document.documentElement, {
            childList: true,
            subtree: true
        } );
    }

    /* ── 4. Enquiry Popup ── */

    var overlay      = qs( '#tykes-form-overlay' );
    var popup        = qs( '#tykes-popup-form' );
    var closeBtn     = qs( '#tykes-popup-close' );
    var popupIsOpen  = false;

    // Focusable elements inside popup (for focus trap).
    function getFocusables() {
        if ( ! popup ) { return []; }
        return qsa( 'a[href], button, input, select, textarea, [tabindex]:not([tabindex="-1"])', popup )
            .filter( function ( el ) { return ! el.disabled && el.offsetParent !== null; } );
    }

    window.tykesOpenPopup = function () {
        closeDrawer();

        if ( ! overlay || ! popup ) { return; }

        overlay.style.display = 'block';
        popup.style.display   = 'block';

        // Trigger reflow so CSS transitions fire.
        void popup.offsetHeight;

        requestAnimationFrame( function () {
            overlay.classList.add( 'active' );
            popup.classList.add( 'active' );
        } );

        document.body.style.overflow = 'hidden';
        popupIsOpen = true;

        // Move focus into the popup.
        var focusables = getFocusables();
        if ( focusables.length ) { focusables[ 0 ].focus(); }
    };

    window.tykesClosePopup = function () {
        if ( ! overlay || ! popup ) { return; }

        overlay.classList.remove( 'active' );
        popup.classList.remove( 'active' );

        setTimeout( function () {
            overlay.style.display        = 'none';
            popup.style.display          = 'none';
            document.body.style.overflow = '';
            popupIsOpen = false;
        }, 400 );
    };

    // Focus trap inside popup.
    document.addEventListener( 'keydown', function ( e ) {
        if ( ! popupIsOpen ) { return; }
        if ( e.key !== 'Tab' ) { return; }

        var focusables = getFocusables();
        if ( ! focusables.length ) { return; }

        var first = focusables[ 0 ];
        var last  = focusables[ focusables.length - 1 ];

        if ( e.shiftKey ) {
            if ( document.activeElement === first ) {
                e.preventDefault();
                last.focus();
            }
        } else {
            if ( document.activeElement === last ) {
                e.preventDefault();
                first.focus();
            }
        }
    } );

    /* ── 5. Keyboard: Escape closes popup & drawer ── */

    document.addEventListener( 'keydown', function ( e ) {
        if ( e.key !== 'Escape' ) { return; }
        if ( popupIsOpen )  { window.tykesClosePopup(); }
        if ( drawerOpen   ) { closeDrawer(); }
    } );

    /* ── 6. Smooth scroll for anchor links inside Tykes sections ── */

    document.addEventListener( 'click', function ( e ) {
        var link = e.target.closest( 'a[href^="#"]' );
        if ( ! link ) { return; }

        var targetId = link.getAttribute( 'href' ).slice( 1 );
        if ( ! targetId ) { return; }

        var target = qs( '#' + targetId );
        if ( ! target ) { return; }

        e.preventDefault();
        target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
    } );

    /* ── 7. Elementor editor live-reload safety ── */

    // Re-initialise scroll state when Elementor reloads the preview.
    if ( window.elementor ) {
        window.elementor.on( 'preview:loaded', onScroll );
    }

})();

/**
 * Tykes Switch Tab (Global)
 * Used by Widget_Tykes_Contact_Forms
 */
window.tykesSwitchTab = function(name, btn) {
    document.querySelectorAll('.ptab').forEach(function(t) { t.classList.remove('active'); });
    document.querySelectorAll('.ptab-pane').forEach(function(p) { p.classList.remove('active'); });
    
    btn.classList.add('active');
    var pane = document.getElementById('pane-' + name);
    if (pane) {
        pane.classList.add('active');
    }
};

/**
 * Tykes Handle Submit (Global)
 * Simple feedback for form submissions
 */
window.tykesHandleSubmit = function(e, form) {
    e.preventDefault();
    var btn = form.querySelector('[type=submit]');
    if (!btn) return;

    var orig = btn.innerHTML;
    btn.innerHTML = '✨ Sent! We\'ll get back to you shortly.';
    btn.style.background = 'linear-gradient(135deg, #22C55E, #16a34a)';
    btn.disabled = true;

    setTimeout(function() {
        btn.innerHTML = orig;
        btn.style.background = '';
        btn.disabled = false;
        form.reset();
    }, 5000);
};
