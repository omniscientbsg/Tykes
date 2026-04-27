<?php
/**
 * Widget: Tykes Difference Hero
 *
 * Named: "Tykes Difference Hero" — page-specific naming convention.
 * Deep content + style + layout + responsive controls.
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

class Widget_Tykes_Difference_Hero extends Widget_Base_Tykes {

    public function get_name(): string  { return 'tykes-difference-hero'; }
    public function get_title(): string { return esc_html__( 'Tykes Difference Hero', 'tykes-ds' ); }
    public function get_icon(): string  { return 'eicon-banner'; }

    /* ── Controls ─────────────────────────────────────────────── */

    protected function register_controls(): void {
        /* Content tabs */
        $this->content_section_badge();
        $this->content_section_heading();
        $this->content_section_body();
        $this->content_section_nav_pills();
        $this->content_section_ctas();
        $this->content_section_image();
        $this->content_section_layout();

        /* Style tabs */
        $this->style_section_hero_bg();
        $this->style_section_badge();
        $this->style_section_heading();
        $this->style_section_paragraph();
        $this->style_section_nav_pills();
        $this->style_section_ctas();
        $this->style_section_image();

        /* Shared spacing */
        $this->add_section_spacing_controls( '{{WRAPPER}} .curr-hero' );
    }

    /* ── Content sections ─────────────────────────────────────── */

    private function content_section_badge(): void {
        $this->start_controls_section( 'sec_badge', [
            'label' => __( '🏷 Badge', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'badge_text', [
            'label'   => __( 'Badge Text', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Kidzonia Academic Framework',
        ] );

        $this->add_control( 'show_badge', [
            'label'        => __( 'Show Badge', 'tykes-ds' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->end_controls_section();
    }

    private function content_section_heading(): void {
        $this->start_controls_section( 'sec_heading', [
            'label' => __( '✏️ Heading', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'heading_tag', [
            'label'   => __( 'HTML Tag', 'tykes-ds' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [ 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3' ],
            'default' => 'h1',
        ] );

        $this->add_control( 'heading_line_1', [
            'label'   => __( 'Line 1 (white)', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Learning Through Play',
        ] );

        $this->add_control( 'heading_line_2', [
            'label'   => __( 'Line 2 (white)', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'is an Art —',
        ] );

        $this->add_control( 'heading_line_3', [
            'label'   => __( 'Line 3 (gold / accent)', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => "and We've Perfected It.",
        ] );

        $this->end_controls_section();
    }

    private function content_section_body(): void {
        $this->start_controls_section( 'sec_body', [
            'label' => __( '📝 Paragraph', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'hero_paragraph', [
            'label'   => __( 'Paragraph', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'rows'    => 6,
            'default' => 'The Tykes Early Years curriculum is built on the academic philosophy of Kidzonia International Preschools — a framework refined across 18,000+ children, 45+ centres, and over a decade of early childhood education practice in India.',
        ] );

        $this->end_controls_section();
    }

    private function content_section_nav_pills(): void {
        $this->start_controls_section( 'sec_nav_pills', [
            'label' => __( '💊 Navigation Pills', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $repeater = new Repeater();
        $repeater->add_control( 'pill_icon',  [ 'label' => __( 'Icon', 'tykes-ds' ), 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-book', 'library' => 'fa-solid' ] ] );
        $repeater->add_control( 'pill_label', [ 'label' => __( 'Label', 'tykes-ds' ), 'type' => Controls_Manager::TEXT, 'default' => 'Section' ] );
        $repeater->add_control( 'pill_url',   [ 'label' => __( 'URL',   'tykes-ds' ), 'type' => Controls_Manager::URL ] );

        $this->add_control( 'nav_pills', [
            'label'       => __( 'Pills', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'pill_icon' => [ 'value' => 'fas fa-book', 'library' => 'fa-solid' ], 'pill_label' => 'Methodologies',   'pill_url' => [ 'url' => '/curriculum/' ] ],
                [ 'pill_icon' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ], 'pill_label' => 'Tykes Difference', 'pill_url' => [ 'url' => '/tykes-difference/' ] ],
                [ 'pill_icon' => [ 'value' => 'far fa-clock', 'library' => 'fa-regular' ], 'pill_label' => 'A Day @ Tykes',   'pill_url' => [ 'url' => '/a-day-at-tykes/' ] ],
                [ 'pill_icon' => [ 'value' => 'fas fa-handshake', 'library' => 'fa-solid' ], 'pill_label' => 'Our Commitment',  'pill_url' => [ 'url' => '/our-commitment/' ] ],
            ],
            'title_field' => '{{{ pill_label }}}',
        ] );

        $this->end_controls_section();
    }

    private function content_section_ctas(): void {
        $this->start_controls_section( 'sec_ctas', [
            'label' => __( '🔘 CTA Buttons', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'cta_primary_label', [
            'label'   => __( 'Primary Label', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Book a Free Visit',
        ] );

        $this->add_control( 'cta_primary_icon', [
            'label'   => __( 'Primary Icon', 'tykes-ds' ),
            'type'    => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ],
        ] );

        $this->add_control( 'cta_secondary_label', [
            'label'   => __( 'Secondary Label', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'View Programmes',
        ] );

        $this->add_control( 'cta_secondary_url', [
            'label'   => __( 'Secondary URL', 'tykes-ds' ),
            'type'    => Controls_Manager::URL,
            'default' => [ 'url' => home_url( '/tykes-programmes/' ) ],
        ] );

        $this->end_controls_section();
    }

    private function content_section_image(): void {
        $this->start_controls_section( 'sec_image', [
            'label' => __( '🖼 Hero Image', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'hero_image', [
            'label'   => __( 'Main Image', 'tykes-ds' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/04/6.png' ],
        ] );

        $this->add_control( 'image_alt', [
            'label'   => __( 'Alt Text', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Tykes Early Years Children Learning',
        ] );

        $this->add_control( 'badge_card_icon',     [ 'label' => __( 'Badge Icon', 'tykes-ds' ), 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-graduation-cap', 'library' => 'fa-solid' ], 'separator' => 'before' ] );
        $this->add_control( 'badge_card_title',    [ 'label' => __( 'Badge Title', 'tykes-ds' ),        'type' => Controls_Manager::TEXT, 'default' => 'Kidzonia Curriculum' ] );
        $this->add_control( 'badge_card_subtitle', [ 'label' => __( 'Badge Subtitle', 'tykes-ds' ),     'type' => Controls_Manager::TEXT, 'default' => 'Award-Winning Framework' ] );

        $this->end_controls_section();
    }

    private function content_section_layout(): void {
        $this->start_controls_section( 'sec_layout', [
            'label' => __( '📐 Layout', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_responsive_control( 'columns_gap', [
            'label'      => __( 'Column Gap', 'tykes-ds' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vw' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 120 ] ],
            'default'    => [ 'size' => 60, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .curr-hero-inner' => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'text_align', [
            'label'     => __( 'Text Alignment', 'tykes-ds' ),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [ 'title' => __( 'Left', 'tykes-ds' ),   'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => __( 'Center', 'tykes-ds' ), 'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => __( 'Right', 'tykes-ds' ),  'icon' => 'eicon-text-align-right' ],
            ],
            'selectors' => [ '{{WRAPPER}} .curr-hero-text' => 'text-align: {{VALUE}};' ],
            'toggle'    => true,
        ] );

        $this->add_responsive_control( 'hero_min_height', [
            'label'      => __( 'Min Height', 'tykes-ds' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'range'      => [ 'px' => [ 'min' => 200, 'max' => 900 ], 'vh' => [ 'min' => 20, 'max' => 100 ] ],
            'default'    => [ 'size' => 560, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .curr-hero' => 'min-height: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();
    }

    /* ── Style sections ───────────────────────────────────────── */

    private function style_section_hero_bg(): void {
        $this->start_controls_section( 'style_hero_bg', [
            'label' => __( '🎨 Hero Background', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'gradient_start', [
            'label'   => __( 'Gradient Start', 'tykes-ds' ),
            'type'    => Controls_Manager::COLOR,
            'default' => '#05a28d',
        ] );

        $this->add_control( 'gradient_mid', [
            'label'   => __( 'Gradient Mid', 'tykes-ds' ),
            'type'    => Controls_Manager::COLOR,
            'default' => '#047a6c',
        ] );

        $this->add_control( 'gradient_end', [
            'label'     => __( 'Gradient End', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#8257bd',
            'selectors' => [
                '{{WRAPPER}} .curr-hero' => 'background: linear-gradient(135deg, {{gradient_start.VALUE}} 0%, {{gradient_mid.VALUE}} 40%, {{VALUE}} 100%);',
            ],
        ] );

        $this->add_control( 'show_grid_overlay', [
            'label'        => __( 'Show Grid Overlay', 'tykes-ds' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->end_controls_section();
    }

    private function style_section_badge(): void {
        $this->start_controls_section( 'style_badge', [
            'label' => __( '🏷 Badge Style', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'badge_color', [
            'label'     => __( 'Text Colour', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#fdbc02',
            'selectors' => [ '{{WRAPPER}} .curr-hero-badge' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'dot_size', [
            'label'     => __( 'Dot Size', 'tykes-ds' ),
            'type'      => Controls_Manager::SLIDER,
            'selectors' => [ '{{WRAPPER}} .dot-pulse' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ],
        ] );

        $this->add_responsive_control( 'dot_padding', [
            'label'      => __( 'Dot Padding', 'tykes-ds' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors'  => [ '{{WRAPPER}} .dot-pulse' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'dot_margin', [
            'label'      => __( 'Dot Margin', 'tykes-ds' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors'  => [ '{{WRAPPER}} .dot-pulse' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_control( 'badge_bg', [
            'label'     => __( 'Background', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.08)',
            'selectors' => [ '{{WRAPPER}} .curr-hero-badge' => 'background: {{VALUE}};' ],
        ] );

        $this->add_control( 'badge_border_color', [
            'label'     => __( 'Border Colour', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.2)',
            'selectors' => [ '{{WRAPPER}} .curr-hero-badge' => 'border-color: {{VALUE}};' ],
        ] );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [ 'name' => 'badge_typography', 'selector' => '{{WRAPPER}} .curr-hero-badge' ]
        );

        $this->end_controls_section();
    }

    private function style_section_heading(): void {
        $this->start_controls_section( 'style_heading', [
            'label' => __( '✏️ Heading Style', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [ 'name' => 'heading_typography', 'selector' => '{{WRAPPER}} .curr-hero-h1' ]
        );

        $this->add_control( 'heading_white_color', [
            'label'     => __( 'White Lines Colour', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.9)',
            'selectors' => [ '{{WRAPPER}} .curr-hero-h1 .line-white' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'heading_gold_color', [
            'label'     => __( 'Accent Line Colour', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#fdbc02',
            'selectors' => [ '{{WRAPPER}} .curr-hero-h1 .line-gold' => 'color: {{VALUE}};' ],
        ] );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [ 'name' => 'heading_shadow', 'selector' => '{{WRAPPER}} .curr-hero-h1' ]
        );

        $this->end_controls_section();
    }

    private function style_section_paragraph(): void {
        $this->start_controls_section( 'style_paragraph', [
            'label' => __( '📝 Paragraph Style', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [ 'name' => 'para_typography', 'selector' => '{{WRAPPER}} .curr-hero-p' ]
        );

        $this->add_control( 'para_color', [
            'label'     => __( 'Colour', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.72)',
            'selectors' => [ '{{WRAPPER}} .curr-hero-p' => 'color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'para_max_width', [
            'label'      => __( 'Max Width', 'tykes-ds' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'default'    => [ 'size' => 520, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .curr-hero-p' => 'max-width: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();
    }

    private function style_section_nav_pills(): void {
        $this->start_controls_section( 'style_pills', [
            'label' => __( '💊 Pill Style', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'pill_color', [
            'label'     => __( 'Text Colour', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.9)',
            'selectors' => [ '{{WRAPPER}} .curr-hero-nav a' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'pill_bg', [
            'label'     => __( 'Background', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.1)',
            'selectors' => [ '{{WRAPPER}} .curr-hero-nav a' => 'background: {{VALUE}};' ],
        ] );

        $this->add_control( 'pill_hover_bg', [
            'label'     => __( 'Hover Background', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.2)',
            'selectors' => [ '{{WRAPPER}} .curr-hero-nav a:hover' => 'background: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'pill_radius', [
            'label'      => __( 'Border Radius', 'tykes-ds' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'default'    => [ 'size' => 50 ],
            'selectors'  => [ '{{WRAPPER}} .curr-hero-nav a' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'pill_typography', 'selector' => '{{WRAPPER}} .curr-hero-nav a' ] );
        $this->add_control( 'pill_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ], 'selectors' => [ '{{WRAPPER}} .curr-hero-nav a i, {{WRAPPER}} .curr-hero-nav a svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'pill_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .curr-hero-nav a i, {{WRAPPER}} .curr-hero-nav a svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'pill_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .curr-hero-nav a i, {{WRAPPER}} .curr-hero-nav a svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'pill_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .curr-hero-nav a i, {{WRAPPER}} .curr-hero-nav a svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->end_controls_section();
    }

    private function style_section_ctas(): void {
        $this->start_controls_section( 'style_ctas', [
            'label' => __( '🔘 CTA Button Styles', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        /* Primary */
        $this->add_control( '_primary_heading', [
            'label' => __( 'Primary Button', 'tykes-ds' ),
            'type'  => Controls_Manager::HEADING,
        ] );

        $this->add_control( 'btn_primary_bg', [
            'label'     => __( 'Background', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#fdbc02',
            'selectors' => [ '{{WRAPPER}} .btn-hero-main' => 'background: {{VALUE}};' ],
        ] );

        $this->add_control( 'btn_primary_color', [
            'label'     => __( 'Text Colour', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1E1B4B',
            'selectors' => [ '{{WRAPPER}} .btn-hero-main' => 'color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'btn_primary_padding', [
            'label'      => __( 'Padding', 'tykes-ds' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [ '{{WRAPPER}} .btn-hero-main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [ 'name' => 'btn_primary_typo', 'selector' => '{{WRAPPER}} .btn-hero-main' ]
        );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'btn_primary_shadow', 'selector' => '{{WRAPPER}} .btn-hero-main' ] );
        $this->add_control( 'btn_primary_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ], 'selectors' => [ '{{WRAPPER}} .btn-hero-main i, {{WRAPPER}} .btn-hero-main svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'btn_primary_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .btn-hero-main i, {{WRAPPER}} .btn-hero-main svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'btn_primary_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .btn-hero-main i, {{WRAPPER}} .btn-hero-main svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'btn_primary_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .btn-hero-main i, {{WRAPPER}} .btn-hero-main svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        /* Ghost */
        $this->add_control( '_ghost_heading', [
            'label'     => __( 'Secondary Button', 'tykes-ds' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'btn_ghost_color', [
            'label'     => __( 'Text Colour', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .btn-hero-ghost' => 'color: {{VALUE}};' ],
        ] );

        $this->add_control( 'btn_ghost_border_color', [
            'label'     => __( 'Border Colour', 'tykes-ds' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.28)',
            'selectors' => [ '{{WRAPPER}} .btn-hero-ghost' => 'border-color: {{VALUE}};' ],
        ] );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [ 'name' => 'btn_ghost_typo', 'selector' => '{{WRAPPER}} .btn-hero-ghost' ]
        );

        $this->end_controls_section();
    }

    private function style_section_image(): void {
        $this->start_controls_section( 'style_image', [
            'label' => __( '🖼 Image Style', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'image_height', [
            'label'      => __( 'Image Height', 'tykes-ds' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vh' ],
            'default'    => [ 'size' => 390, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .hero-img-main' => 'height: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'image_border_radius', [
            'label'      => __( 'Image Border Radius', 'tykes-ds' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'default'    => [ 'size' => 20 ],
            'selectors'  => [ '{{WRAPPER}} .hero-img-main' => 'border-radius: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'image_shadow', 'selector' => '{{WRAPPER}} .hero-img-frame' ] );
        $this->add_control( 'heading_badge_card_icon', [ 'label' => __( 'Badge Card Icon', 'tykes-ds' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
        $this->add_control( 'badge_card_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 80 ] ], 'selectors' => [ '{{WRAPPER}} .hb-icon i, {{WRAPPER}} .hb-icon svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'badge_card_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .hb-icon i, {{WRAPPER}} .hb-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'badge_card_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .hb-icon i, {{WRAPPER}} .hb-icon svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'badge_card_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .hb-icon i, {{WRAPPER}} .hb-icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->end_controls_section();
    }

    /* ── Render ─────────────────────────────────────────────────── */

    protected function render(): void {
        $s        = $this->get_settings_for_display();
        $tag      = in_array( $s['heading_tag'] ?? 'h1', [ 'h1', 'h2', 'h3' ], true ) ? $s['heading_tag'] : 'h1';
        $show_grid = ( $s['show_grid_overlay'] ?? 'yes' ) === 'yes' ? ' curr-hero--grid' : '';
        ?>

<section class="curr-hero<?php echo esc_attr( $show_grid ); ?>">
  <div class="hero-blob-a" aria-hidden="true"></div>
  <div class="hero-blob-b" aria-hidden="true"></div>

  <div class="container curr-hero-inner">

    <div class="curr-hero-text">

      <?php if ( 'yes' === ( $s['show_badge'] ?? 'yes' ) ) : ?>
      <div class="curr-hero-badge b-poppins">
        <span class="dot-pulse" aria-hidden="true"></span>
        <?php echo esc_html( $s['badge_text'] ?? '' ); ?>
      </div>
      <?php endif; ?>

      <<?php echo esc_attr( $tag ); ?> class="curr-hero-h1 h-fredoka">
        <?php if ( $s['heading_line_1'] ) : ?><span class="line-white"><?php echo esc_html( $s['heading_line_1'] ); ?></span><?php endif; ?>
        <?php if ( $s['heading_line_2'] ) : ?><span class="line-white"><?php echo esc_html( $s['heading_line_2'] ); ?></span><?php endif; ?>
        <?php if ( $s['heading_line_3'] ) : ?><span class="line-gold"><?php echo esc_html( $s['heading_line_3'] ); ?></span><?php endif; ?>
      </<?php echo esc_attr( $tag ); ?>>

      <?php if ( $s['hero_paragraph'] ) : ?>
      <p class="curr-hero-p b-poppins"><?php echo esc_html( $s['hero_paragraph'] ); ?></p>
      <?php endif; ?>

      <?php if ( ! empty( $s['nav_pills'] ) ) : ?>
      <div class="curr-hero-nav" role="list">
        <?php foreach ( $s['nav_pills'] as $pill ) : ?>
          <a href="<?php echo esc_url( $pill['pill_url']['url'] ?? '#' ); ?>" role="listitem">
            <?php \Elementor\Icons_Manager::render_icon( $pill['pill_icon'], [ 'aria-hidden' => 'true' ] ); ?> <?php echo esc_html( $pill['pill_label'] ); ?>
          </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="curr-hero-ctas">
        <button class="btn-hero-main b-poppins" onclick="tykesOpenPopup()">
          <?php echo esc_html( $s['cta_primary_label'] ?? 'Book a Free Visit' ); ?> <?php \Elementor\Icons_Manager::render_icon( $s['cta_primary_icon'] ?? [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?>
        </button>
        <a href="<?php echo esc_url( $s['cta_secondary_url']['url'] ?? '#' ); ?>" class="btn-hero-ghost b-poppins"
           <?php echo ! empty( $s['cta_secondary_url']['is_external'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
          <?php echo esc_html( $s['cta_secondary_label'] ?? 'View Programmes' ); ?>
        </a>
      </div>

    </div><!-- /.curr-hero-text -->

    <div class="curr-hero-visual" aria-hidden="true">
      <div class="hero-img-wrap">
        <div class="hb-badge b-poppins">
          <span class="hb-icon"><?php \Elementor\Icons_Manager::render_icon( $s['badge_card_icon'] ?? [ 'value' => 'fas fa-graduation-cap', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?></span>
          <div>
            <h5 class="h-fredoka"><?php echo esc_html( $s['badge_card_title'] ?? 'Kidzonia Curriculum' ); ?></h5>
            <p><?php echo esc_html( $s['badge_card_subtitle'] ?? 'Award-Winning Framework' ); ?></p>
          </div>
        </div>
        <div class="hero-img-frame">
          <img
            src="<?php echo esc_url( $s['hero_image']['url'] ?? '' ); ?>"
            alt="<?php echo esc_attr( $s['image_alt'] ?? '' ); ?>"
            class="hero-img-main"
            loading="eager"
          >
        </div>
      </div>
    </div><!-- /.curr-hero-visual -->

  </div><!-- /.curr-hero-inner -->
</section>
        <?php
    }
}
