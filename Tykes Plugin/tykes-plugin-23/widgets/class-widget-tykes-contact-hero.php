<?php
/**
 * Widget: Tykes Contact Hero
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

class Widget_Tykes_Contact_Hero extends Widget_Base_Tykes {

    public function get_name(): string  { return 'tykes-contact-hero'; }
    public function get_title(): string { return esc_html__( 'Tykes Contact Hero', 'tykes-ds' ); }
    public function get_icon(): string  { return 'eicon-envelope'; }

    protected function register_controls(): void {
        /* Content tabs */
        $this->content_section_layout();
        $this->content_section_left();
        $this->content_section_left_chips();
        $this->content_section_right_info();
        $this->content_section_right_image();

        /* Style tabs */
        $this->style_section_hero_bg();
        $this->style_section_badge();
        $this->style_section_icons();
        $this->style_section_image();

        /* Shared spacing */
        $this->add_section_spacing_controls( '{{WRAPPER}} .ct-hero' );
    }

    private function content_section_layout(): void {
        $this->start_controls_section( 'sec_layout', [
            'label' => __( '📐 Layout', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'right_layout', [
            'label'   => __( 'Right Side Layout', 'tykes-ds' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'info_cards',
            'options' => [
                'info_cards' => __( 'Info Cards Grid', 'tykes-ds' ),
                'image_box'  => __( 'Image Box', 'tykes-ds' ),
            ],
        ] );

        $this->end_controls_section();
    }

    private function content_section_left(): void {
        $this->start_controls_section( 'sec_left', [
            'label' => __( '📝 Left Content', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'badge_text', [
            'label'   => __( 'Badge Text', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'We\'re Here to Help',
        ] );

        $this->add_control( 'heading_line1', [
            'label'   => __( 'Heading Line 1', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'We\'d Love to',
        ] );

        $this->add_control( 'heading_line2', [
            'label'   => __( 'Heading Line 2 (Highlighted)', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Hear From You.',
        ] );

        $this->add_control( 'description', [
            'label'   => __( 'Description', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Whether you\'re a parent exploring admission options, an organisation looking at corporate daycare, or an entrepreneur interested in the Tykes franchise — our team is ready to talk.',
        ] );

        $this->add_control( 'eyebrow_style', [
            'label'   => __( 'Eyebrow Style', 'tykes-ds' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'classic',
            'options' => [
                'classic' => __( 'Classic (Gold)', 'tykes-ds' ),
                'glass'   => __( 'Glass (White/Blur)', 'tykes-ds' ),
            ],
            'separator' => 'before',
        ] );

        $this->end_controls_section();
    }

    private function content_section_left_chips(): void {
        $this->start_controls_section( 'sec_left_chips', [
            'label' => __( '🔗 Left Contact Chips', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $repeater = new Repeater();
        $repeater->add_control( 'icon', [
            'label'   => __( 'Icon', 'tykes-ds' ),
            'type'    => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ],
        ] );
        $repeater->add_control( 'label', [
            'label'   => __( 'Label', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '8400-966-400',
        ] );
        $repeater->add_control( 'link', [
            'label'   => __( 'Link URL', 'tykes-ds' ),
            'type'    => Controls_Manager::URL,
            'default' => [ 'url' => 'tel:8400966400' ],
        ] );

        $this->add_control( 'left_chips', [
            'label'       => __( 'Contact Chips', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ label }}}',
            'default'     => [
                [ 'icon' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ], 'label' => '8400-966-400', 'link' => [ 'url' => 'tel:8400966400' ] ],
                [ 'icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ], 'label' => 'info@tykes.school', 'link' => [ 'url' => 'mailto:info@tykes.school' ] ],
                [ 'icon' => [ 'value' => 'fas fa-globe', 'library' => 'fa-solid' ], 'label' => 'tykes.school', 'link' => [ 'url' => 'https://tykes.school' ] ],
            ],
        ] );

        $this->end_controls_section();
    }

    private function content_section_right_info(): void {
        $this->start_controls_section( 'sec_right_info', [
            'label'     => __( '📋 Right Info Cards', 'tykes-ds' ),
            'tab'       => Controls_Manager::TAB_CONTENT,
            'condition' => [ 'right_layout' => 'info_cards' ],
        ] );

        $repeater = new Repeater();
        $repeater->add_control( 'icon', [
            'label'   => __( 'Icon', 'tykes-ds' ),
            'type'    => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ],
        ] );
        $repeater->add_control( 'title', [
            'label'   => __( 'Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Call Us Directly',
        ] );
        $repeater->add_control( 'content', [
            'label'   => __( 'Content / Link Text', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => '8400-966-400',
        ] );
        $repeater->add_control( 'link_url', [
            'label'   => __( 'Content Link (Optional)', 'tykes-ds' ),
            'type'    => Controls_Manager::URL,
        ] );
        $repeater->add_control( 'subtext', [
            'label'   => __( 'Subtext', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Mon–Sat • 9:00 am – 6:00 pm',
        ] );

        $this->add_control( 'info_cards', [
            'label'       => __( 'Cards', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            'default'     => [
                [ 'icon' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ], 'title' => 'Call Us Directly', 'content' => '8400-966-400', 'link_url' => ['url'=>'tel:8400966400'], 'subtext' => 'Mon–Sat • 9:00 am – 6:00 pm' ],
                [ 'icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ], 'title' => 'Email Us', 'content' => 'info@tykes.school', 'link_url' => ['url'=>'mailto:info@tykes.school'], 'subtext' => 'We respond within 1 business day' ],
                [ 'icon' => [ 'value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid' ], 'title' => 'Corporate Office', 'content' => 'Office 209, The Corporate Park<br>Sector 18, Vashi, Navi Mumbai 400703', 'subtext' => '' ],
                [ 'icon' => [ 'value' => 'far fa-clock', 'library' => 'fa-regular' ], 'title' => 'Office Hours', 'content' => 'Monday – Saturday<br>9:00 am – 6:00 pm IST', 'subtext' => '' ],
            ],
        ] );

        $this->end_controls_section();
    }

    private function content_section_right_image(): void {
        $this->start_controls_section( 'sec_right_image', [
            'label'     => __( '🖼️ Right Image Box', 'tykes-ds' ),
            'tab'       => Controls_Manager::TAB_CONTENT,
            'condition' => [ 'right_layout' => 'image_box' ],
        ] );

        $this->add_control( 'hero_image', [
            'label'   => __( 'Main Image', 'tykes-ds' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/04/6.png' ],
        ] );

        $this->add_control( 'badge_card_icon', [
            'label'   => __( 'Badge Icon', 'tykes-ds' ),
            'type'    => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ],
        ] );
        
        $this->add_control( 'badge_card_title', [
            'label'   => __( 'Badge Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Kidzonia Curriculum',
        ] );

        $this->add_control( 'badge_card_subtitle', [
            'label'   => __( 'Badge Subtitle', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Award-Winning Framework',
        ] );

        $this->end_controls_section();
    }

    private function style_section_hero_bg(): void {
        $this->start_controls_section( 'style_hero_bg', [
            'label' => __( '✨ Hero Background', 'tykes-ds' ),
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
                '{{WRAPPER}} .ct-hero' => 'background: linear-gradient(135deg, {{gradient_start.VALUE}} 0%, {{gradient_mid.VALUE}} 40%, {{VALUE}} 100%);',
            ],
        ] );

        $this->end_controls_section();
    }

    private function style_section_badge(): void {
        $this->start_controls_section( 'style_badge', [ 'label' => __( '🏷 Badge / Dot', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'dot_size', [ 'label' => __( 'Dot Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .dot-pulse' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );
        $this->add_control( 'dot_color', [ 'label' => __( 'Dot Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .dot-pulse' => 'background: {{VALUE}};' ] ] );
        $this->end_controls_section();
    }

    private function style_section_icons(): void {
        $this->start_controls_section( 'style_icons', [ 'label' => __( '🎨 Icon Controls', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        
        $this->add_control( 'heading_chips', [ 'label' => __( 'Contact Chips', 'tykes-ds' ), 'type' => Controls_Manager::HEADING ] );
        $this->add_control( 'chip_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .ct-hero-chip i, {{WRAPPER}} .ct-hero-chip svg' => 'font-size: {{SIZE}}px; width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );
        $this->add_control( 'chip_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ct-hero-chip i, {{WRAPPER}} .ct-hero-chip svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'chip_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .ct-hero-chip i, {{WRAPPER}} .ct-hero-chip svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'chip_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .ct-hero-chip i, {{WRAPPER}} .ct-hero-chip svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->add_control( 'heading_qcards', [ 'label' => __( 'Quick Contact Cards', 'tykes-ds' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => [ 'right_layout' => 'info_cards' ] ] );
        $this->add_control( 'qcard_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .ct-qcard .qi i, {{WRAPPER}} .ct-qcard .qi svg' => 'font-size: {{SIZE}}px; width: {{SIZE}}px; height: {{SIZE}}px;' ], 'condition' => [ 'right_layout' => 'info_cards' ] ] );
        $this->add_control( 'qcard_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ct-qcard .qi i, {{WRAPPER}} .ct-qcard .qi svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ], 'condition' => [ 'right_layout' => 'info_cards' ] ] );
        $this->add_responsive_control( 'qcard_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .ct-qcard .qi i, {{WRAPPER}} .ct-qcard .qi svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ], 'condition' => [ 'right_layout' => 'info_cards' ] ] );
        $this->add_responsive_control( 'qcard_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .ct-qcard .qi i, {{WRAPPER}} .ct-qcard .qi svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ], 'condition' => [ 'right_layout' => 'info_cards' ] ] );

        $this->add_control( 'heading_badge_card', [ 'label' => __( 'Image Badge Card', 'tykes-ds' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before', 'condition' => [ 'right_layout' => 'image_box' ] ] );
        $this->add_control( 'badge_card_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .hb-icon i, {{WRAPPER}} .hb-icon svg' => 'font-size: {{SIZE}}px; width: {{SIZE}}px; height: {{SIZE}}px;' ], 'condition' => [ 'right_layout' => 'image_box' ] ] );
        $this->add_control( 'badge_card_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .hb-icon i, {{WRAPPER}} .hb-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ], 'condition' => [ 'right_layout' => 'image_box' ] ] );
        $this->add_responsive_control( 'badge_card_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .hb-icon i, {{WRAPPER}} .hb-icon svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ], 'condition' => [ 'right_layout' => 'image_box' ] ] );
        $this->add_responsive_control( 'badge_card_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .hb-icon i, {{WRAPPER}} .hb-icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ], 'condition' => [ 'right_layout' => 'image_box' ] ] );

        $this->end_controls_section();
    }

    private function style_section_image(): void {
        $this->start_controls_section( 'style_image', [
            'label' => __( '🖼️ Image Style', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [ 'right_layout' => 'image_box' ],
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

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [ 'name' => 'image_shadow', 'selector' => '{{WRAPPER}} .hero-img-frame' ]
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <section class="ct-hero">
            <div class="hero-blob-a"></div>
            <div class="hero-blob-b"></div>
            <div class="container ct-hero-inner">
                
                <div class="ct-hero-text">
                    <?php if ( ! empty( $s['badge_text'] ) ) : 
                        $badge_class = ( 'glass' === $s['eyebrow_style'] ) ? 'hero-eyebrow style-glass b-poppins' : 'hero-eyebrow b-poppins';
                        ?>
                        <div class="<?php echo esc_attr( $badge_class ); ?>">
                            <span class="dot-pulse"></span> <?php echo esc_html( $s['badge_text'] ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <h1 class="ct-hero-h1">
                        <?php echo wp_kses_post( $s['heading_line1'] ); ?><br>
                        <span><?php echo wp_kses_post( $s['heading_line2'] ); ?></span>
                    </h1>
                    
                    <?php if ( ! empty( $s['description'] ) ) : ?>
                        <p class="ct-hero-p b-poppins"><?php echo wp_kses_post( $s['description'] ); ?></p>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $s['left_chips'] ) ) : ?>
                        <div class="ct-hero-chips">
                            <?php foreach ( $s['left_chips'] as $chip ) : ?>
                                <div class="ct-hero-chip b-poppins">
                                    <?php \Elementor\Icons_Manager::render_icon( $chip['icon'], [ 'aria-hidden' => 'true' ] ); ?> 
                                    <?php if ( ! empty( $chip['link']['url'] ) ) : ?>
                                        <a href="<?php echo esc_url( $chip['link']['url'] ); ?>" <?php echo !empty($chip['link']['is_external']) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                            <?php echo esc_html( $chip['label'] ); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php echo esc_html( $chip['label'] ); ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ( 'info_cards' === $s['right_layout'] ) : ?>
                    <div class="ct-hero-cards">
                        <?php foreach ( $s['info_cards'] as $card ) : ?>
                            <div class="ct-qcard">
                                <div class="qi"><?php \Elementor\Icons_Manager::render_icon( $card['icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
                                <div>
                                    <h4><?php echo esc_html( $card['title'] ); ?></h4>
                                    <?php if ( ! empty( $card['link_url']['url'] ) ) : ?>
                                        <a href="<?php echo esc_url( $card['link_url']['url'] ); ?>" class="b-poppins"><?php echo wp_kses_post( $card['content'] ); ?></a>
                                    <?php else : ?>
                                        <p class="b-poppins"><?php echo wp_kses_post( $card['content'] ); ?></p>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $card['subtext'] ) ) : ?>
                                        <p class="b-poppins"><?php echo wp_kses_post( $card['subtext'] ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="curr-hero-visual">
                        <div class="hero-img-wrap">
                            <div class="hb-badge b-poppins">
                                <span class="hb-icon"><?php \Elementor\Icons_Manager::render_icon( $s['badge_card_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                                <div>
                                    <h5 class="h-fredoka"><?php echo esc_html( $s['badge_card_title'] ); ?></h5>
                                    <p><?php echo esc_html( $s['badge_card_subtitle'] ); ?></p>
                                </div>
                            </div>
                            <div class="hero-img-frame">
                                <img src="<?php echo esc_url( $s['hero_image']['url'] ?? '' ); ?>" alt="" class="hero-img-main" loading="eager">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}
