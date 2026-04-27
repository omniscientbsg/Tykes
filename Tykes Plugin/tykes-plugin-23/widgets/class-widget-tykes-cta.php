<?php
/**
 * Widget: Tykes CTA
 * Named: "Tykes CTA" (global widget naming convention).
 *
 * @package Tykes_DS
 */
namespace Tykes_DS;
defined( 'ABSPATH' ) || exit;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

class Widget_Tykes_CTA extends Widget_Base_Tykes {
    public function get_name(): string  { return 'tykes-cta'; }
    public function get_title(): string { return esc_html__( 'Tykes CTA', 'tykes-ds' ); }
    public function get_icon(): string  { return 'eicon-call-to-action'; }

    protected function register_controls(): void {
        /* Content */
        $this->start_controls_section( 'sec_content', [ 'label' => __( '📋 Content', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'pill_text',        [ 'label' => __( 'Pill Label', 'tykes-ds' ),            'type' => Controls_Manager::TEXT,     'default' => '2025–26 Admissions Open' ] );
        $this->add_control( 'heading_plain',    [ 'label' => __( 'Heading (plain)', 'tykes-ds' ),        'type' => Controls_Manager::TEXT,     'default' => 'Give Your Child the' ] );
        $this->add_control( 'heading_gradient', [ 'label' => __( 'Heading (highlight)', 'tykes-ds' ),   'type' => Controls_Manager::TEXT,     'default' => 'Best Start.' ] );
        $this->add_control( 'description',      [ 'label' => __( 'Description', 'tykes-ds' ),           'type' => Controls_Manager::TEXTAREA, 'default' => 'Limited seats available. Book a free visit today.' ] );
        $this->end_controls_section();

        /* Buttons */
        $this->start_controls_section( 'sec_btns', [ 'label' => __( '🔘 Buttons', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'btn_primary_label',   [ 'label' => __( 'Primary Label', 'tykes-ds' ),   'type' => Controls_Manager::TEXT, 'default' => 'Enquire Now' ] );
        $this->add_control( 'btn_primary_icon',    [ 'label' => __( 'Primary Icon', 'tykes-ds' ),    'type' => Controls_Manager::ICONS,'default' => [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ] ] );
        $this->add_control( 'btn_secondary_label', [ 'label' => __( 'Secondary Label', 'tykes-ds' ), 'type' => Controls_Manager::TEXT, 'default' => 'Explore Programmes' ] );
        $this->add_control( 'btn_secondary_url',   [ 'label' => __( 'Secondary URL', 'tykes-ds' ),   'type' => Controls_Manager::URL,  'default' => [ 'url' => home_url( '/tykes-programmes/' ) ] ] );
        $this->end_controls_section();

        /* Style */
        $this->start_controls_section( 'style_cta', [ 'label' => __( '🎨 Style', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'gradient_start',     [ 'label' => __( 'Gradient Start', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#6d46a8', 'selectors' => [ '{{WRAPPER}} .curr-cta' => 'background: linear-gradient(135deg,{{VALUE}} 0%,#8257bd 50%,#6340a0 100%);' ] ] );
        $this->add_control( 'heading_color',      [ 'label' => __( 'Heading Colour', 'tykes-ds' ),  'type' => Controls_Manager::COLOR, 'default' => '#fff', 'selectors' => [ '{{WRAPPER}} .cta-h2' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'highlight_color',    [ 'label' => __( 'Highlight Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#FBBF24', 'selectors' => [ '{{WRAPPER}} .cta-h2 .gd' => 'background: linear-gradient(90deg,{{VALUE}},#F97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;' ] ] );
        $this->add_control( 'pill_color',         [ 'label' => __( 'Pill Colour', 'tykes-ds' ),      'type' => Controls_Manager::COLOR, 'default' => '#FBBF24', 'selectors' => [ '{{WRAPPER}} .cta-pill' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'desc_color',         [ 'label' => __( 'Description Colour', 'tykes-ds' ),'type' => Controls_Manager::COLOR, 'default' => 'rgba(255,255,255,.55)', 'selectors' => [ '{{WRAPPER}} .cta-desc' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'btn_primary_bg',     [ 'label' => __( 'Primary Btn Bg', 'tykes-ds' ),   'type' => Controls_Manager::COLOR, 'default' => '#fdbc02', 'selectors' => [ '{{WRAPPER}} .cta-btn-main' => 'background: {{VALUE}};' ] ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'heading_typo', 'selector' => '{{WRAPPER}} .cta-h2' ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'desc_typo',    'selector' => '{{WRAPPER}} .cta-desc' ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'btn_shadow',   'selector' => '{{WRAPPER}} .cta-btn-main' ] );
        $this->add_control( 'btn_primary_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ], 'selectors' => [ '{{WRAPPER}} .cta-btn-main i, {{WRAPPER}} .cta-btn-main svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'btn_primary_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cta-btn-main i, {{WRAPPER}} .cta-btn-main svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'btn_primary_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .cta-btn-main i, {{WRAPPER}} .cta-btn-main svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'btn_primary_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .cta-btn-main i, {{WRAPPER}} .cta-btn-main svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->end_controls_section();

        $this->add_section_spacing_controls( '{{WRAPPER}} .curr-cta' );
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
<section class="curr-cta">
  <div class="container cta-inner">
    <div class="cta-pill b-poppins"><?php echo esc_html( $s['pill_text'] ); ?></div>
    <h2 class="cta-h2 h-fredoka"><?php echo esc_html( $s['heading_plain'] ); ?> <span class="gd"><?php echo esc_html( $s['heading_gradient'] ); ?></span></h2>
    <p class="cta-desc b-poppins"><?php echo esc_html( $s['description'] ); ?></p>
    <div class="cta-btns">
      <button class="cta-btn-main b-poppins" onclick="tykesOpenPopup()"><?php echo esc_html( $s['btn_primary_label'] ?? 'Enquire Now' ); ?> <?php \Elementor\Icons_Manager::render_icon( $s['btn_primary_icon'] ?? [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?></button>
      <a href="<?php echo esc_url( $s['btn_secondary_url']['url'] ?? '#' ); ?>" class="cta-btn-secondary b-poppins"><?php echo esc_html( $s['btn_secondary_label'] ); ?></a>
    </div>
  </div>
</section>
        <?php
    }
}
