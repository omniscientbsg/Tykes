<?php
/**
 * Tykes Mobile Nav Walker
 *
 * Outputs the Tykes mobile drawer menu structure:
 *
 *   <a href="…" class="mob-link">Home</a>
 *
 *   <button class="mob-link" onclick="toggleMobSub('tykes-sub-123')">
 *     About Us <span>›</span>
 *   </button>
 *   <div class="mob-sub" id="tykes-sub-123">
 *     <a href="…">Child Item</a>
 *   </div>
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

class Nav_Walker_Mobile extends \Walker_Nav_Menu {

    /** Track the generated sub-id for each parent item. */
    private $current_sub_id = '';

    /** Colour palette for child-item dots — cycles automatically. */
    const DOT_PALETTE = [
        '#7C3AED', '#EC4899', '#14B8A6', '#F59E0B',
        '#05a28d', '#F97316', '#22C55E', '#0EA5E9',
        '#A78BFA', '#EF4444',
    ];

    /** Track dot index per dropdown so each parent resets the cycle. */
    private $dot_index = 0;

    public function start_lvl( &$output, $depth = 0, $args = null ): void {
        if ( 0 === $depth ) {
            // Reset dot index when opening a new sub-menu
            $this->dot_index = 0;
            $output .= '<div class="mob-sub" id="' . esc_attr( $this->current_sub_id ) . '">';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = null ): void {
        if ( 0 === $depth ) {
            $output .= '</div><!-- /.mob-sub -->';
        }
    }

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $id = 0 ): void {
        $item         = $data_object;
        $title        = apply_filters( 'the_title', $item->title, $item->ID );
        $url          = $item->url ?: '#';
        $target       = $item->target ? ' target="' . esc_attr( $item->target ) . '"' : '';
        $classes      = (array) $item->classes;
        $has_children = in_array( 'menu-item-has-children', $classes, true );

        if ( 0 === $depth ) {
            if ( $has_children ) {
                // Generate a stable unique ID for the sub-panel.
                $this->current_sub_id = 'tykes-sub-' . absint( $item->ID );
                $output .= '<button class="mob-link" onclick="toggleMobSub(\'' . esc_js( $this->current_sub_id ) . '\')">';
                $output .= esc_html( $title );
                $output .= ' <span aria-hidden="true">›</span>';
                $output .= '</button>';
            } else {
                $output .= '<a href="' . esc_url( $url ) . '" class="mob-link"' . $target . '>';
                $output .= esc_html( $title );
                $output .= '</a>';
            }
        } else {
            // Child item inside mob-sub.
            $dot_color = $this->get_dot_color( $classes );
            
            $output .= '<a href="' . esc_url( $url ) . '"' . $target . '>';
            $output .= '<span class="dot" style="background:' . esc_attr( $dot_color ) . ';" aria-hidden="true"></span>';
            $output .= esc_html( $title );
            $output .= '</a>';
            
            $this->dot_index++;
        }
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ): void {
        // No closing tags needed — all elements are self-closed in start_el.
    }

    /**
     * Determine dot colour for a child item.
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
