<?php
/**
 * Tykes Nav Walker
 *
 * Converts a WordPress nav menu into the Tykes header HTML structure:
 *
 *   <div class="nav-item">
 *     <a href="...">Label <svg class="chevron">…</svg></a>
 *     <div class="nav-dropdown">          ← only if has children
 *       <a href="..."><span class="dot" style="background:#xxx"></span>Child</a>
 *     </div>
 *   </div>
 *
 * The dot colours cycle through a predefined palette so sub-items always
 * look designed rather than monochrome. You can override per-item by adding
 * a CSS class "dot-{hex}" in the menu item's class field in WP Dashboard →
 * Appearance → Menus (e.g. "dot-7C3AED").
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

class Nav_Walker extends \Walker_Nav_Menu {

    /** Colour palette for child-item dots — cycles automatically. */
    const DOT_PALETTE = [
        '#7C3AED', '#EC4899', '#14B8A6', '#F59E0B',
        '#05a28d', '#F97316', '#22C55E', '#0EA5E9',
        '#A78BFA', '#EF4444',
    ];

    /** Track dot index per dropdown so each parent resets the cycle. */
    private $dot_index = 0;

    /* ── Level 0: outer wrapper per top-level item ─────────────── */

    public function start_lvl( &$output, $depth = 0, $args = null ): void {
        if ( 0 === $depth ) {
            // Opening a dropdown — reset dot index for this parent.
            $this->dot_index = 0;
            $output .= '<div class="nav-dropdown">';
        }
        // Deeper levels (grandchildren) are rendered flat inside the dropdown.
    }

    public function end_lvl( &$output, $depth = 0, $args = null ): void {
        if ( 0 === $depth ) {
            $output .= '</div><!-- /.nav-dropdown -->';
        }
    }

    /* ── Items ─────────────────────────────────────────────────── */

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $id = 0 ): void {
        $item = $data_object; // WP_Post (menu item).

        $title  = apply_filters( 'the_title', $item->title, $item->ID );
        $url    = $item->url ?: '#';
        $target = $item->target ? ' target="' . esc_attr( $item->target ) . '"' : '';
        $rel    = ( $item->xfn )
            ? ' rel="' . esc_attr( $item->xfn ) . '"'
            : ( $item->target === '_blank' ? ' rel="noopener noreferrer"' : '' );

        $classes      = (array) $item->classes;
        $has_children = in_array( 'menu-item-has-children', $classes, true );
        $is_active    = in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) || in_array( 'current-menu-ancestor', $classes, true );
        $class_attr   = $is_active ? ' class="active"' : '';

        // ── Top-level item ──────────────────────────────────────
        if ( 0 === $depth ) {
            $output .= '<div class="nav-item">';
            $output .= '<a href="' . esc_url( $url ) . '"' . $target . $rel . $class_attr . '>';
            $output .= esc_html( $title );

            if ( $has_children ) {
                // Chevron SVG — CSS rotates it on hover.
                $output .= '<svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>';
            }

            $output .= '</a>';
            // The dropdown div is opened by start_lvl() below.

        // ── Dropdown child item ─────────────────────────────────
        } else {
            // Determine dot colour.
            $dot_color = $this->get_dot_color( $classes );

            $output .= '<a href="' . esc_url( $url ) . '"' . $target . $rel . '>';
            $output .= '<span class="dot" style="background:' . esc_attr( $dot_color ) . ';" aria-hidden="true"></span>';
            $output .= esc_html( $title );
            $output .= '</a>';

            $this->dot_index++;
        }
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ): void {
        if ( 0 === $depth ) {
            $output .= '</div><!-- /.nav-item -->';
        }
        // Child items have no wrapper element — end_el is a no-op.
    }

    /* ── Helpers ────────────────────────────────────────────────── */

    /**
     * Determine dot colour for a child item.
     *
     * Priority order:
     * 1. A CSS class like "dot-7C3AED" set on the menu item.
     * 2. Auto-cycle through DOT_PALETTE.
     */
    private function get_dot_color( array $classes ): string {
        foreach ( $classes as $class ) {
            if ( preg_match( '/^dot-([0-9a-fA-F]{3,6})$/', $class, $m ) ) {
                return '#' . $m[1];
            }
        }
        $palette = self::DOT_PALETTE;
        return $palette[ $this->dot_index % count( $palette ) ];
    }
}
