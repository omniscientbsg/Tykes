<?php
/**
 * Widget: Tykes Contact Office
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Widget_Tykes_Contact_Office extends Widget_Base_Tykes {

    public function get_name(): string  { return 'tykes-contact-office'; }
    public function get_title(): string { return esc_html__( 'Tykes Contact Office', 'tykes-ds' ); }
    public function get_icon(): string  { return 'eicon-map-pin'; }

    protected function register_controls(): void {
        $this->start_controls_section( 'sec_office', [
            'label' => __( 'Office Section', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'eyebrow', [
            'label'   => __( 'Section Eyebrow', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Find Us',
        ] );

        $this->add_control( 'title', [
            'label'   => __( 'Section Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Our Corporate Office',
        ] );

        $repeater = new Repeater();
        $repeater->add_control( 'icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid' ] ] );
        $repeater->add_control( 'icon_color', [
            'label'   => 'Icon Theme',
            'type'    => Controls_Manager::SELECT,
            'default' => 'purple',
            'options' => [
                'purple' => 'Purple',
                'orange' => 'Orange',
                'teal'   => 'Teal',
                'pink'   => 'Pink',
            ],
        ] );
        $repeater->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Registered Address' ] );
        $repeater->add_control( 'content', [ 'label' => 'Content / Link Text', 'type' => Controls_Manager::TEXTAREA, 'default' => 'Office No. 209, The Corporate Park' ] );
        $repeater->add_control( 'link_url', [ 'label' => 'Content Link', 'type' => Controls_Manager::URL ] );
        $repeater->add_control( 'subtext', [ 'label' => 'Subtext', 'type' => Controls_Manager::TEXTAREA ] );

        $this->add_control( 'office_cards', [
            'label'       => __( 'Office Cards', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            'default'     => [
                [ 'icon' => [ 'value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid' ], 'icon_color' => 'purple', 'title' => 'Registered Address', 'content' => "Office No. 209, The Corporate Park\nPlot No. 14/15, Sector 18, Vashi\nNavi Mumbai, Maharashtra – 400703" ],
                [ 'icon' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ], 'icon_color' => 'orange', 'title' => 'Phone', 'content' => '8400-966-400', 'link_url' => ['url'=>'tel:8400966400'], 'subtext' => 'Monday – Saturday • 9:00 am – 6:00 pm' ],
                [ 'icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ], 'icon_color' => 'teal', 'title' => 'Email', 'content' => 'info@tykes.school', 'link_url' => ['url'=>'mailto:info@tykes.school'], 'subtext' => 'Responses within 1 business day' ],
            ],
        ] );

        $this->add_control( 'map_iframe_url', [
            'label'   => __( 'Map Embed URL (src)', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'description' => 'Extract the "src" from Google Maps embed code.',
            'default' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3770.835154743454!2d72.99757657520516!3d19.070966581698293!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c1341c2c2f43%3A0x6b409c7fc1a52e50!2sKidzonia%20International%20Preschool!5e0!3m2!1sen!2sin!4v1708422329388!5m2!1sen!2sin',
        ] );
        
        $this->add_control( 'map_badge_icon', [ 'label' => 'Map Badge Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-school', 'library' => 'fa-solid' ] ] );
        $this->add_control( 'map_badge_title', [ 'label' => 'Map Badge Title', 'type' => Controls_Manager::TEXT, 'default' => 'Tykes HQ' ] );
        $this->add_control( 'map_badge_sub', [ 'label' => 'Map Badge Subtitle', 'type' => Controls_Manager::TEXT, 'default' => 'Vashi, Navi Mumbai' ] );

        $this->end_controls_section();

        $this->style_section_icons();
        $this->add_section_spacing_controls( '{{WRAPPER}} .office-sec' );
    }

    private function style_section_icons(): void {
        $this->start_controls_section( 'style_icons', [ 'label' => __( '🎨 Icon Controls', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        
        $this->add_control( 'heading_cards', [ 'label' => __( 'Office Cards', 'tykes-ds' ), 'type' => Controls_Manager::HEADING ] );
        $this->add_control( 'card_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .od-icon i, {{WRAPPER}} .od-icon svg' => 'font-size: {{SIZE}}px; width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );
        $this->add_control( 'card_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .od-icon i, {{WRAPPER}} .od-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'card_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .od-icon i, {{WRAPPER}} .od-icon svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'card_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .od-icon i, {{WRAPPER}} .od-icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->add_control( 'heading_map_badge', [ 'label' => __( 'Map Badge Icon', 'tykes-ds' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
        $this->add_control( 'map_badge_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .mb-icon i, {{WRAPPER}} .mb-icon svg' => 'font-size: {{SIZE}}px; width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );
        $this->add_control( 'map_badge_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .mb-icon i, {{WRAPPER}} .mb-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'map_badge_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .mb-icon i, {{WRAPPER}} .mb-icon svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'map_badge_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .mb-icon i, {{WRAPPER}} .mb-icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <section class="office-sec" id="office-location">
            <div class="container">
                <?php if ( ! empty( $s['eyebrow'] ) ) : ?>
                    <div class="section-eyebrow b-poppins"><?php echo esc_html( $s['eyebrow'] ); ?></div>
                <?php endif; ?>
                
                <?php if ( ! empty( $s['title'] ) ) : ?>
                    <h2 class="section-title"><?php echo wp_kses_post( $s['title'] ); ?></h2>
                <?php endif; ?>

                <div class="office-grid">
                    <div class="office-details">
                        <?php foreach ( $s['office_cards'] as $card ) : ?>
                            <div class="od-card">
                                <div class="od-icon <?php echo esc_attr( $card['icon_color'] ); ?>"><?php \Elementor\Icons_Manager::render_icon( $card['icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
                                <div class="od-text">
                                    <h5 class="b-poppins"><?php echo esc_html( $card['title'] ); ?></h5>
                                    <?php if ( ! empty( $card['link_url']['url'] ) ) : ?>
                                        <a href="<?php echo esc_url( $card['link_url']['url'] ); ?>" class="b-poppins"><?php echo wp_kses_post( nl2br( $card['content'] ) ); ?></a>
                                    <?php else : ?>
                                        <p class="b-poppins"><?php echo wp_kses_post( nl2br( $card['content'] ) ); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ( ! empty( $card['subtext'] ) ) : ?>
                                        <p class="sub b-poppins"><?php echo wp_kses_post( nl2br( $card['subtext'] ) ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="map-wrap">
                        <div class="map-overlay-badge">
                            <span class="mb-icon"><?php \Elementor\Icons_Manager::render_icon( $s['map_badge_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                            <div>
                                <h5><?php echo esc_html( $s['map_badge_title'] ); ?></h5>
                                <p class="b-poppins"><?php echo esc_html( $s['map_badge_sub'] ); ?></p>
                            </div>
                        </div>
                        <?php if ( ! empty( $s['map_iframe_url'] ) ) : ?>
                            <iframe src="<?php echo esc_url( $s['map_iframe_url'] ); ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}
