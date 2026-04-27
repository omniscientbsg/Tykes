<?php
/**
 * Widget: Tykes Difference Features
 * Named: "Tykes Difference Features" — page-specific naming convention.
 *
 * @package Tykes_DS
 */
namespace Tykes_DS;
defined( 'ABSPATH' ) || exit;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

class Widget_Tykes_Difference_Features extends Widget_Base_Tykes {
    public function get_name(): string  { return 'tykes-difference-features'; }
    public function get_title(): string { return esc_html__( 'Tykes Difference Features', 'tykes-ds' ); }
    public function get_icon(): string  { return 'eicon-star'; }

    protected function register_controls(): void {
        /* Intro */
        $this->start_controls_section( 'sec_intro', [ 'label' => __( '📋 Intro Text', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'eyebrow',         [ 'label' => __( 'Eyebrow', 'tykes-ds' ),       'type' => Controls_Manager::TEXT,     'default' => 'Why Choose Tykes' ] );
        $this->add_control( 'section_title',   [ 'label' => __( 'Section Title', 'tykes-ds' ), 'type' => Controls_Manager::TEXT,     'default' => 'What Sets Tykes Apart' ] );
        $this->add_control( 'intro_paragraph', [ 'label' => __( 'Paragraph', 'tykes-ds' ),     'type' => Controls_Manager::TEXTAREA, 'rows' => 5, 'default' => 'Institutional quality at every centre — the same academic framework, the same operational rigour, the same child outcomes.' ] );
        $this->end_controls_section();

        /* Callout card */
        $this->start_controls_section( 'sec_callout', [ 'label' => __( '💬 Callout Card', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'callout_heading', [ 'label' => __( 'Heading', 'tykes-ds' ), 'type' => Controls_Manager::TEXT,     'default' => 'The Same Child. A Completely Different Outcome.' ] );
        $this->add_control( 'callout_body',    [ 'label' => __( 'Body',    'tykes-ds' ), 'type' => Controls_Manager::TEXTAREA, 'default' => 'Kidzonia-grade curriculum. Professionally trained educators. Consistent quality at every Tykes centre.' ] );
        $this->end_controls_section();

        /* Feature cards */
        $this->start_controls_section( 'sec_features', [ 'label' => __( '⭐ Feature Cards', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $repeater = new Repeater();
        $repeater->add_control( 'feat_icon', [ 'label' => __( 'Icon', 'tykes-ds' ), 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ] ] );
        $repeater->add_control( 'feat_title', [ 'label' => __( 'Title', 'tykes-ds' ),        'type' => Controls_Manager::TEXT,     'default' => 'Feature Title' ] );
        $repeater->add_control( 'feat_body',  [ 'label' => __( 'Description', 'tykes-ds' ),  'type' => Controls_Manager::TEXTAREA, 'default' => 'Feature description goes here.' ] );
        $repeater->add_control( 'feat_icon_bg', [ 'label' => __( 'Icon Background', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '' ] );

        $this->add_control( 'features', [
            'label'   => __( 'Feature Cards', 'tykes-ds' ),
            'type'    => Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [
                [ 'feat_icon' => [ 'value' => 'fas fa-university', 'library' => 'fa-solid' ], 'feat_title' => 'Institutional Consistency',  'feat_body' => 'Every Tykes classroom follows identical curriculum and lesson planning systems. Quality doesn\'t vary by location.' ],
                [ 'feat_icon' => [ 'value' => 'fas fa-chart-bar', 'library' => 'fa-solid' ], 'feat_title' => 'Documented Child Progress',  'feat_body' => 'Structured assessment frameworks tracking each child\'s development across all domains with detailed parent reports.' ],
                [ 'feat_icon' => [ 'value' => 'fas fa-users', 'library' => 'fa-solid' ], 'feat_title' => 'Parent Partnership',     'feat_body' => 'Regular communication, workshops, and digital engagement keeping families informed every step of the way.' ],
                [ 'feat_icon' => [ 'value' => 'fas fa-puzzle-piece', 'library' => 'fa-solid' ], 'feat_title' => 'Multiple Intelligence',      'feat_body' => 'We identify and celebrate every child\'s unique intelligence profile across linguistic, logical, spatial, and interpersonal domains.' ],
                [ 'feat_icon' => [ 'value' => 'fas fa-graduation-cap', 'library' => 'fa-solid' ], 'feat_title' => 'Seamless School Readiness', 'feat_body' => 'Children leaving Tykes Senior KG are academically, socially, and emotionally prepared for CBSE, ICSE, or IGCSE.' ],
                [ 'feat_icon' => [ 'value' => 'fas fa-sync-alt', 'library' => 'fa-solid' ], 'feat_title' => 'Continuous Improvement',    'feat_body' => 'Academic oversight, teacher training, and parent feedback mechanisms woven into every centre\'s daily operations.' ],
            ],
            'title_field' => '{{{ feat_icon }}} {{{ feat_title }}}',
        ] );

        /* Grid columns control */
        $this->add_responsive_control( 'grid_columns', [
            'label'          => __( 'Columns', 'tykes-ds' ),
            'type'           => Controls_Manager::SELECT,
            'options'        => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ],
            'default'        => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'selectors'      => [ '{{WRAPPER}} .diff-features' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ],
        ] );

        $this->end_controls_section();

        /* Style */
        $this->start_controls_section( 'style_section', [ 'label' => __( '🎨 Section Style', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'gradient_start', [ 'label' => __( 'Gradient Start', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff' ] );
        $this->add_control( 'gradient_end',   [ 'label' => __( 'Gradient End', 'tykes-ds' ),   'type' => Controls_Manager::COLOR, 'default' => '#f9f7ff', 'selectors' => [ '{{WRAPPER}} .difference-sec' => 'background: linear-gradient(135deg, {{gradient_start.VALUE}} 0%, {{VALUE}} 100%);' ] ] );
        $this->add_control( 'eyebrow_color', [ 'label' => __( 'Eyebrow Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#8257bd', 'selectors' => [ '{{WRAPPER}} .section-eyebrow' => 'color: {{VALUE}};', '{{WRAPPER}} .section-eyebrow::before' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'title_color', [ 'label' => __( 'Title Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#1E1B4B', 'selectors' => [ '{{WRAPPER}} .section-title' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'title_typo',   'selector' => '{{WRAPPER}} .section-title' ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'eyebrow_typo', 'selector' => '{{WRAPPER}} .section-eyebrow' ] );
        $this->end_controls_section();

        /* Card style */
        $this->start_controls_section( 'style_cards', [ 'label' => __( '🃏 Card Style', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'card_bg', [ 'label' => __( 'Card Background', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#F9F7FF', 'selectors' => [ '{{WRAPPER}} .diff-feat' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'card_hover_border', [ 'label' => __( 'Hover Border', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#f3edff', 'selectors' => [ '{{WRAPPER}} .diff-feat:hover' => 'border-color: {{VALUE}};' ] ] );
        $this->add_control( 'card_title_color', [ 'label' => __( 'Card Title Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#1E1B4B', 'selectors' => [ '{{WRAPPER}} .diff-feat h4' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'card_body_color',  [ 'label' => __( 'Card Body Colour', 'tykes-ds' ),  'type' => Controls_Manager::COLOR, 'default' => '#6B7280', 'selectors' => [ '{{WRAPPER}} .diff-feat p'  => 'color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'card_padding', [ 'label' => __( 'Card Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'default' => [ 'top' => '32', 'right' => '28', 'bottom' => '32', 'left' => '28', 'unit' => 'px', 'isLinked' => false ], 'selectors' => [ '{{WRAPPER}} .diff-feat' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'card_radius', [ 'label' => __( 'Border Radius', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'default' => [ 'size' => 20 ], 'selectors' => [ '{{WRAPPER}} .diff-feat' => 'border-radius: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'grid_gap', [ 'label' => __( 'Grid Gap', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'default' => [ 'size' => 24 ], 'selectors' => [ '{{WRAPPER}} .diff-features' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'card_shadow', 'selector' => '{{WRAPPER}} .diff-feat:hover' ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'card_title_typo', 'selector' => '{{WRAPPER}} .diff-feat h4' ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'card_body_typo',  'selector' => '{{WRAPPER}} .diff-feat p' ] );

        $this->add_control( 'feat_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 80 ] ], 'selectors' => [ '{{WRAPPER}} .feat-icon i, {{WRAPPER}} .feat-icon svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'feat_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .feat-icon i, {{WRAPPER}} .feat-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'feat_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .feat-icon i, {{WRAPPER}} .feat-icon svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'feat_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .feat-icon i, {{WRAPPER}} .feat-icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->end_controls_section();

        /* Callout style */
        $this->start_controls_section( 'style_callout', [ 'label' => __( '💬 Callout Style', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'callout_gradient', [ 'label' => __( 'Gradient Start', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#8257bd', 'selectors' => [ '{{WRAPPER}} .diff-intro-callout' => 'background: linear-gradient(135deg,{{VALUE}},#6d46a8);' ] ] );
        $this->add_control( 'callout_title_color', [ 'label' => __( 'Title Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#fdbc02', 'selectors' => [ '{{WRAPPER}} .diff-intro-callout h4' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'callout_body_color',  [ 'label' => __( 'Body Colour', 'tykes-ds' ),  'type' => Controls_Manager::COLOR, 'default' => 'rgba(255,255,255,0.8)', 'selectors' => [ '{{WRAPPER}} .diff-intro-callout p'  => 'color: {{VALUE}};' ] ] );
        $this->end_controls_section();

        $this->add_section_spacing_controls( '{{WRAPPER}} .difference-sec' );
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
<section class="difference-sec" id="tykes-difference">
  <div class="container difference-inner">
    <div class="diff-intro">
      <div class="diff-intro-text">
        <div class="section-eyebrow b-poppins"><?php echo esc_html( $s['eyebrow'] ); ?></div>
        <h2 class="section-title h-fredoka"><?php echo esc_html( $s['section_title'] ); ?></h2>
        <p class="b-poppins"><?php echo esc_html( $s['intro_paragraph'] ); ?></p>
      </div>
      <div class="diff-intro-callout">
        <h4 class="h-fredoka"><?php echo esc_html( $s['callout_heading'] ); ?></h4>
        <p class="b-poppins"><?php echo esc_html( $s['callout_body'] ); ?></p>
      </div>
    </div>
    <div class="diff-features">
      <?php foreach ( $s['features'] as $feat ) : ?>
      <div class="diff-feat">
        <div class="feat-icon" aria-hidden="true"><?php \Elementor\Icons_Manager::render_icon( $feat['feat_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
        <h4 class="h-fredoka"><?php echo esc_html( $feat['feat_title'] ); ?></h4>
        <p class="b-poppins"><?php echo esc_html( $feat['feat_body'] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
        <?php
    }
}
