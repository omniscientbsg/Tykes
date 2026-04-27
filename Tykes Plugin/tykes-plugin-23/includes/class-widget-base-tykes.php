<?php
/**
 * Abstract Base Widget
 *
 * Every Tykes widget extends this class.
 * It provides:
 *  - Consistent get_categories() pointing to our panel group
 *  - Shared "Section Spacing" style controls (padding, margin)
 *  - Shared "Section Background" style controls
 *  - Shared Typography group control helpers
 *  - Shared responsive helpers
 *  - A render_standalone() method for use by the header/footer injector
 *    outside of the Elementor page editor
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

abstract class Widget_Base_Tykes extends Widget_Base {
    /** Fallback settings for standalone rendering when Elementor does not provide set_settings_for_display(). */
    private $standalone_settings = [];
    /* ── Shared metadata ────────────────────────────────────────── */

    public function get_categories(): array {
        return [ 'tykes-design-system' ];
    }

    public function get_keywords(): array {
        return [ 'tykes', 'early years', 'kidzonia' ];
    }

    /* ── Shared control sections ────────────────────────────────── */

    /**
     * Register a "Section Spacing" style section.
     * Call inside register_controls() after your content sections.
     *
     * @param string $selector  CSS selector relative to {{WRAPPER}}.
     *                          Defaults to the widget's outer section element.
     */
    protected function add_section_spacing_controls( string $selector = '{{WRAPPER}} > .elementor-widget-container > section, {{WRAPPER}} > .elementor-widget-container > header, {{WRAPPER}} > .elementor-widget-container > footer' ): void {

        $this->start_controls_section( '_shared_spacing', [
            'label' => __( '📐 Section Spacing', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( '_section_padding', [
            'label'      => __( 'Padding', 'tykes-ds' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%', 'vw' ],
            'selectors'  => [ $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( '_section_margin', [
            'label'      => __( 'Margin', 'tykes-ds' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [ $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( '_section_border_radius', [
            'label'      => __( 'Border Radius', 'tykes-ds' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors'  => [ $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();
    }

    /**
     * Register a "Section Background" style section.
     */
    protected function add_section_background_controls( string $selector = '{{WRAPPER}} > .elementor-widget-container > section' ): void {

        $this->start_controls_section( '_shared_background', [
            'label' => __( '🎨 Section Background', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_section_bg',
                'label'    => __( 'Background', 'tykes-ds' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => $selector,
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => '_section_shadow',
                'label'    => __( 'Box Shadow', 'tykes-ds' ),
                'selector' => $selector,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Convenience: add a Typography group control for a given element.
     *
     * @param string $name     Unique control name within this widget.
     * @param string $label    Human-readable label.
     * @param string $selector CSS selector.
     */
    protected function add_typography_control( string $name, string $label, string $selector ): void {
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => $name,
                'label'    => $label,
                'selector' => $selector,
            ]
        );
    }

    /**
     * Convenience: add a responsive alignment control.
     */
    protected function add_alignment_control( string $name, string $label, string $selector, array $options = [] ): void {
        $this->add_responsive_control( $name, array_merge( [
            'label'     => $label,
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [ 'title' => __( 'Left', 'tykes-ds' ),   'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => __( 'Center', 'tykes-ds' ), 'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => __( 'Right', 'tykes-ds' ),  'icon' => 'eicon-text-align-right' ],
            ],
            'selectors' => [ $selector => 'text-align: {{VALUE}};' ],
            'toggle'    => true,
        ], $options ) );
    }

    /* ── Standalone render (for global header/footer injection) ── */

    /**
     * Render this widget's HTML outside of Elementor, using default settings.
     * Override in subclasses to pass custom defaults.
     */
    public function render_standalone(): void {
        // Simulate get_settings_for_display() with default values.
        if ( method_exists( $this, 'set_settings_for_display' ) ) {
            $this->set_settings_for_display( $this->get_default_standalone_settings() );
        } else {
            // Always include _column_size to prevent Elementor warnings
            $this->standalone_settings = array_merge( 
                [ '_column_size' => '100' ], 
                $this->get_default_standalone_settings() 
            );
        }
        $this->render();
    }

    /**
     * Override get_settings_for_display() so standalone render can use fallback values.
     *
     * @param string|null $setting_key Optional single setting key.
     * @return mixed
     */
    public function get_settings_for_display( $setting_key = null ) {
        if ( null === $setting_key ) {
            if ( ! empty( $this->standalone_settings ) ) {
                return $this->standalone_settings;
            }
        } elseif ( is_array( $this->standalone_settings ) && array_key_exists( $setting_key, $this->standalone_settings ) ) {
            return $this->standalone_settings[ $setting_key ];
        }

        return parent::get_settings_for_display( $setting_key );
    }

    /**
     * Return the array of defaults used by render_standalone().
     * Override in each widget to provide sensible defaults.
     */
    protected function get_default_standalone_settings(): array {
        return [ '_column_size' => '100' ];
    }
}
