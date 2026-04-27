/**
 * Tykes Design System – Admin JS
 * Handles:
 *  - Live hex display update when colour picker changes
 *  - Conditional field show/hide based on header/footer source dropdowns
 */

( function ( $ ) {
    'use strict';

    $( document ).ready( function () {

        /* ── Colour picker → live hex display ───────────────────── */
        $( '.tykes-color-picker' ).on( 'input change', function () {
            var $hex = $( this ).next( '.tykes-hex-display' );
            if ( $hex.length ) {
                $hex.text( $( this ).val() );
            }
        } );

        /* ── Conditional field visibility ────────────────────────── */

        function updateHeaderFields() {
            var val = $( '#tykes_header_type' ).val();
            $( '.tykes-header-template-row' ).toggle( val === 'template' );
            $( '.tykes-header-menu-row'     ).toggle( val === 'menu' );
            $( '.tykes-header-widget-row'   ).toggle( val === 'widget' );
        }

        function updateFooterFields() {
            var val = $( 'select[name="tykes_ds_settings[footer_type]"]' ).val();
            $( '.tykes-footer-template-row' ).toggle( val === 'template' );
        }

        // Tag rows with helper classes so we can target them.
        tagRow( 'tykes_ds_settings[header_template_id]', 'tykes-header-template-row' );
        tagRow( 'tykes_ds_settings[header_menu_id]',     'tykes-header-menu-row'     );
        tagRow( 'tykes_ds_settings[header_widget]',      'tykes-header-widget-row'   );
        tagRow( 'tykes_ds_settings[footer_template_id]', 'tykes-footer-template-row' );

        function tagRow( name, cls ) {
            $( '[name="' + name + '"]' ).closest( 'tr' ).addClass( cls );
        }

        // Initial state.
        updateHeaderFields();
        updateFooterFields();

        // On change.
        $( '#tykes_header_type' ).on( 'change', updateHeaderFields );
        $( 'select[name="tykes_ds_settings[footer_type]"]' ).on( 'change', updateFooterFields );

        /* ── Save confirmation toast ─────────────────────────────── */
        if ( $( '.settings-error.updated' ).length ) {
            showToast( '✅ Tykes settings saved!', '#22C55E' );
        }
        if ( $( '.settings-error.error' ).length ) {
            showToast( '❌ Please check your settings and try again.', '#EF4444' );
        }

        function showToast( message, color ) {
            var $toast = $( '<div>' )
                .text( message )
                .css( {
                    position:    'fixed',
                    bottom:      '28px',
                    right:       '28px',
                    background:  color,
                    color:       '#fff',
                    padding:     '12px 22px',
                    borderRadius:'10px',
                    fontFamily:  "'Poppins',sans-serif",
                    fontWeight:  '600',
                    fontSize:    '.9rem',
                    boxShadow:   '0 8px 24px rgba(0,0,0,.18)',
                    zIndex:      99999,
                    opacity:     0,
                    transition:  'opacity .3s',
                } );

            $( 'body' ).append( $toast );
            setTimeout( function () { $toast.css( 'opacity', 1 ); }, 50 );
            setTimeout( function () {
                $toast.css( 'opacity', 0 );
                setTimeout( function () { $toast.remove(); }, 350 );
            }, 3500 );
        }
    } );

} )( jQuery );
