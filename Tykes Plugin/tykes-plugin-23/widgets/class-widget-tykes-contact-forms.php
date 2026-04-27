<?php
/**
 * Widget: Tykes Contact Forms
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Widget_Tykes_Contact_Forms extends Widget_Base_Tykes {

    public function get_name(): string  { return 'tykes-contact-forms'; }
    public function get_title(): string { return esc_html__( 'Tykes Contact Forms', 'tykes-ds' ); }
    public function get_icon(): string  { return 'eicon-form-horizontal'; }

    protected function register_controls(): void {
        $this->content_section_general();
        $this->content_section_tab_admissions();
        $this->content_section_tab_corporate();
        $this->content_section_tab_franchise();
        $this->content_section_side_by_side();
        $this->style_section_icons();

        /* Shared spacing */
        $this->add_section_spacing_controls( '{{WRAPPER}} .purpose-sec' );
    }

    private function content_section_general(): void {
        $this->start_controls_section( 'sec_general', [
            'label' => __( 'General Settings', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'eyebrow', [
            'label'   => __( 'Section Eyebrow', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Get in Touch',
        ] );

        $this->add_control( 'title', [
            'label'   => __( 'Section Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'How Can We Help You?',
        ] );

        $this->add_control( 'layout_type', [
            'label'   => __( 'Layout Type', 'tykes-ds' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'tabs',
            'options' => [
                'tabs' => __( 'Tabs Layout (Tri-Purpose)', 'tykes-ds' ),
                'side' => __( 'Side-by-Side (Classic Contact)', 'tykes-ds' ),
            ],
            'separator' => 'before',
        ] );

        $this->end_controls_section();
    }

    private function content_section_side_by_side(): void {
        $this->start_controls_section( 'sec_side', [
            'label'     => __( '📝 Side-by-Side Content', 'tykes-ds' ),
            'tab'       => Controls_Manager::TAB_CONTENT,
            'condition' => [ 'layout_type' => 'side' ],
        ] );

        $this->add_control( 'side_title', [
            'label'   => __( 'Form Column Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Send a Message',
        ] );

        $repeater = new Repeater();
        $repeater->add_control( 'icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ] ] );
        $repeater->add_control( 'icon_theme', [
            'label'   => 'Icon Theme',
            'type'    => Controls_Manager::SELECT,
            'default' => 'blue',
            'options' => [ 'blue' => 'Blue', 'pink' => 'Pink', 'gold' => 'Gold' ],
        ] );
        $repeater->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Call Us Directly' ] );
        $repeater->add_control( 'content', [ 'label' => 'Content / Link Text', 'type' => Controls_Manager::TEXT, 'default' => '8400-966-400' ] );
        $repeater->add_control( 'link_url', [ 'label' => 'Link', 'type' => Controls_Manager::URL ] );
        $repeater->add_control( 'subtext', [ 'label' => 'Subtext', 'type' => Controls_Manager::TEXT, 'default' => 'Mon-Sat • 9am-6pm' ] );

        $this->add_control( 'side_info_cards', [
            'label'       => __( 'Left Info Cards', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            'default'     => [
                [ 'icon' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ], 'icon_theme' => 'blue', 'title' => 'Call Us Directly', 'content' => '8400-966-400', 'link_url' => ['url'=>'tel:8400966400'] ],
                [ 'icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ], 'icon_theme' => 'pink', 'title' => 'Email Us', 'content' => 'info@tykes.school', 'link_url' => ['url'=>'mailto:info@tykes.school'] ],
                [ 'icon' => [ 'value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid' ], 'icon_theme' => 'gold', 'title' => 'Visit Us', 'content' => 'Vashi, Navi Mumbai' ],
            ],
        ] );

        $this->end_controls_section();
    }

    private function content_section_tab_admissions(): void {
        $this->start_controls_section( 'sec_tab_admissions', [
            'label' => __( 'Tab: Admissions', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'adm_tab_label', [
            'label'   => __( 'Tab Label', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '🎓 Admissions',
        ] );

        $this->add_control( 'adm_form_title', [
            'label'   => __( 'Form Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Book a Free Visit ✨',
        ] );

        $this->add_control( 'adm_form_desc', [
            'label'   => __( 'Form Description', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Tell us about your child — our admissions team will respond in 24 hours and schedule a free centre visit at a time that works for you.',
        ] );

        $this->add_control( 'adm_form_submit_label', [
            'label'   => __( 'Submit Button Text', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Submit Enquiry',
        ] );

        $this->add_control( 'adm_form_submit_icon', [
            'label'   => __( 'Submit Button Icon', 'tykes-ds' ),
            'type'    => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ],
        ] );

        $this->add_control( 'adm_info_title', [
            'label'   => __( 'Info Column Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Admissions Helpline',
        ] );

        $this->add_control( 'adm_info_desc', [
            'label'   => __( 'Info Column Desc', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Need immediate assistance? Our admissions counsellors are available Mon–Sat.',
        ] );

        $repeater = new Repeater();
        $repeater->add_control( 'icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ] ] );
        $repeater->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Call Us' ] );
        $repeater->add_control( 'content', [ 'label' => 'Content/Link Text', 'type' => Controls_Manager::TEXTAREA, 'default' => '8400-966-400' ] );
        $repeater->add_control( 'link_url', [ 'label' => 'Link', 'type' => Controls_Manager::URL ] );
        
        $this->add_control( 'adm_info_cards', [
            'label'       => __( 'Info Cards', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            'default'     => [
                [ 'icon' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ], 'title' => 'Call Us', 'content' => '8400-966-400', 'link_url' => ['url'=>'tel:8400966400'] ],
                [ 'icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ], 'title' => 'Email Admissions', 'content' => 'admissions@tykes.school', 'link_url' => ['url'=>'mailto:admissions@tykes.school'] ],
                [ 'icon' => [ 'value' => 'far fa-clock', 'library' => 'fa-regular' ], 'title' => 'Hours', 'content' => '9:00 am – 6:00 pm IST' ],
            ],
        ] );

        $this->end_controls_section();
    }

    private function content_section_tab_corporate(): void {
        $this->start_controls_section( 'sec_tab_corporate', [
            'label' => __( 'Tab: Corporate', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'corp_tab_label', [
            'label'   => __( 'Tab Label', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '🏢 Corporate Daycare',
        ] );

        $this->add_control( 'corp_form_title', [
            'label'   => __( 'Form Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Partner With Us 🤝',
        ] );

        $this->add_control( 'corp_form_desc', [
            'label'   => __( 'Form Description', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Empower your workforce with on-site or near-site corporate daycare solutions.',
        ] );

        $this->add_control( 'corp_form_submit_label', [
            'label'   => __( 'Submit Button Text', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Request Proposal',
        ] );

        $this->add_control( 'corp_form_submit_icon', [
            'label'   => __( 'Submit Button Icon', 'tykes-ds' ),
            'type'    => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ],
        ] );

        $this->add_control( 'corp_info_title', [
            'label'   => __( 'Info Column Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Corporate Partnerships',
        ] );

        $this->add_control( 'corp_info_desc', [
            'label'   => __( 'Info Column Desc', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Join the 50+ companies that trust Tykes to care for their employees\' children.',
        ] );

        $repeater = new Repeater();
        $repeater->add_control( 'icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ] ] );
        $repeater->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Call Us' ] );
        $repeater->add_control( 'content', [ 'label' => 'Content/Link Text', 'type' => Controls_Manager::TEXTAREA, 'default' => '8400-966-400' ] );
        $repeater->add_control( 'link_url', [ 'label' => 'Link', 'type' => Controls_Manager::URL ] );
        
        $this->add_control( 'corp_info_cards', [
            'label'       => __( 'Info Cards', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            'default'     => [
                [ 'icon' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ], 'title' => 'B2B Helpline', 'content' => '8400-966-400', 'link_url' => ['url'=>'tel:8400966400'] ],
                [ 'icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ], 'title' => 'Email B2B Team', 'content' => 'corporate@tykes.school', 'link_url' => ['url'=>'mailto:corporate@tykes.school'] ],
                [ 'icon' => [ 'value' => 'fas fa-file-pdf', 'library' => 'fa-solid' ], 'title' => 'Brochure', 'content' => 'Download Corporate Deck', 'link_url' => ['url'=>'#'] ],
            ],
        ] );

        $this->end_controls_section();
    }

    private function content_section_tab_franchise(): void {
        $this->start_controls_section( 'sec_tab_franchise', [
            'label' => __( 'Tab: Franchise', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'fran_tab_label', [
            'label'   => __( 'Tab Label', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '🚀 Franchise',
        ] );

        $this->add_control( 'fran_form_title', [
            'label'   => __( 'Form Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Start Your Journey 🌟',
        ] );

        $this->add_control( 'fran_form_desc', [
            'label'   => __( 'Form Description', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Interested in opening a Tykes centre? Share your details to schedule a discovery call.',
        ] );

        $this->add_control( 'fran_form_submit_label', [
            'label'   => __( 'Submit Button Text', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Apply for Franchise',
        ] );

        $this->add_control( 'fran_form_submit_icon', [
            'label'   => __( 'Submit Button Icon', 'tykes-ds' ),
            'type'    => Controls_Manager::ICONS,
            'default' => [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ],
        ] );

        $this->add_control( 'fran_info_title', [
            'label'   => __( 'Info Column Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Franchise Opportunities',
        ] );

        $this->add_control( 'fran_info_desc', [
            'label'   => __( 'Info Column Desc', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Join the fastest-growing early education network with a proven ROI model.',
        ] );

        $repeater = new Repeater();
        $repeater->add_control( 'icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ] ] );
        $repeater->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT, 'default' => 'Call Us' ] );
        $repeater->add_control( 'content', [ 'label' => 'Content/Link Text', 'type' => Controls_Manager::TEXTAREA, 'default' => '8400-966-400' ] );
        $repeater->add_control( 'link_url', [ 'label' => 'Link', 'type' => Controls_Manager::URL ] );
        
        $this->add_control( 'fran_info_cards', [
            'label'       => __( 'Info Cards', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title }}}',
            'default'     => [
                [ 'icon' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ], 'title' => 'Franchise Hotline', 'content' => '8400-966-400', 'link_url' => ['url'=>'tel:8400966400'] ],
                [ 'icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ], 'title' => 'Email Us', 'content' => 'franchise@tykes.school', 'link_url' => ['url'=>'mailto:franchise@tykes.school'] ],
                [ 'icon' => [ 'value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid' ], 'title' => 'Investment', 'content' => 'Starting at ₹25L', 'link_url' => ['url'=>'#'] ],
            ],
        ] );

        $this->end_controls_section();
    }

    private function style_section_icons(): void {
        $this->start_controls_section( 'style_icons', [ 'label' => __( '🎨 Icons Style', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );

        $this->add_control( 'heading_submit_icons', [ 'label' => __( 'Submit Button Icons', 'tykes-ds' ), 'type' => Controls_Manager::HEADING ] );
        $this->add_control( 'submit_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ], 'selectors' => [ '{{WRAPPER}} .cf-submit i, {{WRAPPER}} .cf-submit svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'submit_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cf-submit i, {{WRAPPER}} .cf-submit svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'submit_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .cf-submit i, {{WRAPPER}} .cf-submit svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'submit_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .cf-submit i, {{WRAPPER}} .cf-submit svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->add_control( 'heading_info_icons', [ 'label' => __( 'Info Card Icons', 'tykes-ds' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
        $this->add_control( 'info_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 100 ] ], 'selectors' => [ '{{WRAPPER}} .ic-icon i, {{WRAPPER}} .ic-icon svg, {{WRAPPER}} .ic-icon-wrap i, {{WRAPPER}} .ic-icon-wrap svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'info_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .ic-icon i, {{WRAPPER}} .ic-icon svg, {{WRAPPER}} .ic-icon-wrap i, {{WRAPPER}} .ic-icon-wrap svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'info_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .ic-icon i, {{WRAPPER}} .ic-icon svg, {{WRAPPER}} .ic-icon-wrap i, {{WRAPPER}} .ic-icon-wrap svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'info_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .ic-icon i, {{WRAPPER}} .ic-icon svg, {{WRAPPER}} .ic-icon-wrap i, {{WRAPPER}} .ic-icon-wrap svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->end_controls_section();
    }

    private function render_info_cards( $cards ) {
        if ( empty( $cards ) ) return;
        echo '<div class="info-cards">';
        foreach ( $cards as $card ) {
            echo '<div class="info-card">';
            echo '<div class="ic-icon">';
            \Elementor\Icons_Manager::render_icon( $card['icon'], [ 'aria-hidden' => 'true' ] );
            echo '</div>';
            echo '<div>';
            echo '<h5>' . esc_html( $card['title'] ) . '</h5>';
            if ( ! empty( $card['link_url']['url'] ) ) {
                echo '<a href="' . esc_url( $card['link_url']['url'] ) . '" class="b-poppins">' . wp_kses_post( $card['content'] ) . '</a>';
            } else {
                echo '<p class="b-poppins">' . wp_kses_post( $card['content'] ) . '</p>';
            }
            echo '</div></div>';
        }
        echo '</div>';
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <section class="purpose-sec" id="contact-forms-section">
            <div class="container">
                <?php if ( ! empty( $s['eyebrow'] ) ) : ?>
                    <div class="section-eyebrow b-poppins"><?php echo esc_html( $s['eyebrow'] ); ?></div>
                <?php endif; ?>
                
                <?php if ( ! empty( $s['title'] ) ) : ?>
                    <h2 class="section-title"><?php echo wp_kses_post( $s['title'] ); ?></h2>
                <?php endif; ?>

                <?php if ( 'tabs' === $s['layout_type'] ) : ?>
                    <!-- Tab buttons -->
                    <div class="purpose-tabs" role="tablist">
                        <button class="ptab active" onclick="tykesSwitchTab('admissions', this)" role="tab"><?php echo esc_html( $s['adm_tab_label'] ); ?></button>
                        <button class="ptab" onclick="tykesSwitchTab('corporate', this)" role="tab"><?php echo esc_html( $s['corp_tab_label'] ); ?></button>
                        <button class="ptab" onclick="tykesSwitchTab('franchise', this)" role="tab"><?php echo esc_html( $s['fran_tab_label'] ); ?></button>
                    </div>

                    <!-- Tab 1: Admissions -->
                    <div class="ptab-pane active" id="pane-admissions">
                        <div class="contact-form-card">
                            <h3><?php echo esc_html( $s['adm_form_title'] ); ?></h3>
                            <p class="b-poppins"><?php echo wp_kses_post( $s['adm_form_desc'] ); ?></p>
                            <form onsubmit="tykesHandleSubmit(event, this)">
                                <div class="cf-row">
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Parent Name *</label>
                                        <input type="text" class="cf-input b-poppins" placeholder="Full name" required>
                                    </div>
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Mobile *</label>
                                        <input type="tel" class="cf-input b-poppins" placeholder="10-digit mobile" required>
                                    </div>
                                </div>
                                <div class="cf-group">
                                    <label class="cf-label b-poppins">Email Address *</label>
                                    <input type="email" class="cf-input b-poppins" placeholder="parent@email.com" required>
                                </div>
                                <div class="cf-row">
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Child's Age Group *</label>
                                        <select class="cf-input b-poppins" required>
                                            <option value="">Select programme</option>
                                            <option value="toddler">Toddler</option>
                                            <option value="preschool">Preschool</option>
                                            <option value="afterschool">After School</option>
                                        </select>
                                    </div>
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Nearest City *</label>
                                        <select class="cf-input b-poppins" required>
                                            <option value="">Select city</option>
                                            <option value="pune">Pune</option>
                                            <option value="mumbai">Mumbai</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="cf-group">
                                    <label class="cf-label b-poppins">Message (Optional)</label>
                                    <textarea class="cf-input b-poppins" placeholder="Anything you'd like to share..." rows="3"></textarea>
                                </div>
                                <button type="submit" class="cf-submit"><?php echo esc_html( $s['adm_form_submit_label'] ?? 'Send Enquiry — We\'ll Call You Back' ); ?> <?php \Elementor\Icons_Manager::render_icon( $s['adm_form_submit_icon'] ?? [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?></button>
                                <div class="cf-privacy">✨ Your details are safe with us. We will never share your information.</div>
                            </form>
                        </div>
                        <div class="tab-info">
                            <h3><?php echo esc_html( $s['adm_info_title'] ); ?></h3>
                            <p class="b-poppins"><?php echo wp_kses_post( $s['adm_info_desc'] ); ?></p>
                            <?php $this->render_info_cards( $s['adm_info_cards'] ); ?>
                        </div>
                    </div>

                    <!-- Tab 2: Corporate -->
                    <div class="ptab-pane" id="pane-corporate">
                        <div class="contact-form-card">
                            <h3><?php echo esc_html( $s['corp_form_title'] ); ?></h3>
                            <p class="b-poppins"><?php echo wp_kses_post( $s['corp_form_desc'] ); ?></p>
                            <form onsubmit="tykesHandleSubmit(event, this)">
                                <div class="cf-row">
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Your Name *</label>
                                        <input type="text" class="cf-input b-poppins" placeholder="Full name" required>
                                    </div>
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Designation *</label>
                                        <input type="text" class="cf-input b-poppins" placeholder="HR Manager, CEO.." required>
                                    </div>
                                </div>
                                <div class="cf-row">
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Organisation Name *</label>
                                        <input type="text" class="cf-input b-poppins" placeholder="Company name" required>
                                    </div>
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Approx. Employee Count *</label>
                                        <select class="cf-input b-poppins" required>
                                            <option value="">Select range</option>
                                            <option value="1-50">1 - 50</option>
                                            <option value="51-200">51 - 200</option>
                                            <option value="201+">201+</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="cf-row">
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Work Mobile *</label>
                                        <input type="tel" class="cf-input b-poppins" placeholder="10-digit number" required>
                                    </div>
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Work Email *</label>
                                        <input type="email" class="cf-input b-poppins" placeholder="you@company.com" required>
                                    </div>
                                </div>
                                <div class="cf-group">
                                    <label class="cf-label b-poppins">Office Location / City *</label>
                                    <input type="text" class="cf-input b-poppins" placeholder="City or area where your office is located" required>
                                </div>
                                <div class="cf-group">
                                    <label class="cf-label b-poppins">Tell us more (optional)</label>
                                    <textarea class="cf-input b-poppins" placeholder="Any specific requirements or questions.." rows="3"></textarea>
                                </div>
                                <button type="submit" class="cf-submit"><?php echo esc_html( $s['corp_form_submit_label'] ?? 'Send Partnership Enquiry' ); ?> <?php \Elementor\Icons_Manager::render_icon( $s['corp_form_submit_icon'] ?? [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?></button>
                                <div class="cf-privacy">Our B2B team will contact you within 24 hours.</div>
                            </form>
                        </div>
                        <div class="tab-info">
                            <h3><?php echo esc_html( $s['corp_info_title'] ); ?></h3>
                            <p class="b-poppins"><?php echo wp_kses_post( $s['corp_info_desc'] ); ?></p>
                            <?php $this->render_info_cards( $s['corp_info_cards'] ); ?>
                        </div>
                    </div>

                    <!-- Tab 3: Franchise -->
                    <div class="ptab-pane" id="pane-franchise">
                        <div class="contact-form-card">
                            <h3><?php echo esc_html( $s['fran_form_title'] ); ?></h3>
                            <p class="b-poppins"><?php echo wp_kses_post( $s['fran_form_desc'] ); ?></p>
                            <form onsubmit="tykesHandleSubmit(event, this)">
                                <div class="cf-row">
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Full Name *</label>
                                        <input type="text" class="cf-input b-poppins" placeholder="Your name" required>
                                    </div>
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Mobile *</label>
                                        <input type="tel" class="cf-input b-poppins" placeholder="10-digit number" required>
                                    </div>
                                </div>
                                <div class="cf-group">
                                    <label class="cf-label b-poppins">Email *</label>
                                    <input type="email" class="cf-input b-poppins" placeholder="you@email.com" required>
                                </div>
                                <div class="cf-row">
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">City / Location Interested In *</label>
                                        <input type="text" class="cf-input b-poppins" placeholder="Where you'd like to open" required>
                                    </div>
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Investment Readiness *</label>
                                        <select class="cf-input b-poppins" required>
                                            <option value="">Select range</option>
                                            <option value="15-25">₹15L - ₹25L</option>
                                            <option value="25-40">₹25L - ₹40L</option>
                                            <option value="40+">₹40L+</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="cf-group">
                                    <label class="cf-label b-poppins">Prior Experience (Optional)</label>
                                    <input type="text" class="cf-input b-poppins" placeholder="Tell us your background">
                                </div>
                                <div class="cf-group">
                                    <label class="cf-label b-poppins">Your Question or Message</label>
                                    <textarea class="cf-input b-poppins" placeholder="What would you like to know?" rows="3"></textarea>
                                </div>
                                <button type="submit" class="cf-submit"><?php echo esc_html( $s['fran_form_submit_label'] ?? 'Begin Franchise Conversation' ); ?> <?php \Elementor\Icons_Manager::render_icon( $s['fran_form_submit_icon'] ?? [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?></button>
                                <div class="cf-privacy">A franchise advisor will connect with you soon.</div>
                            </form>
                        </div>
                        <div class="tab-info">
                            <h3><?php echo esc_html( $s['fran_info_title'] ); ?></h3>
                            <p class="b-poppins"><?php echo wp_kses_post( $s['fran_info_desc'] ); ?></p>
                            <?php $this->render_info_cards( $s['fran_info_cards'] ); ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="cf-side-by-side">
                        <div class="contact-info-col">
                            <?php foreach ( $s['side_info_cards'] as $card ) : ?>
                                <div class="info-card-premium">
                                    <div class="ic-icon-wrap <?php echo esc_attr( $card['icon_theme'] ); ?>">
                                        <?php \Elementor\Icons_Manager::render_icon( $card['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    </div>
                                    <div class="ic-text">
                                        <h4><?php echo esc_html( $card['title'] ); ?></h4>
                                        <?php if ( ! empty( $card['link_url']['url'] ) ) : ?>
                                            <a href="<?php echo esc_url( $card['link_url']['url'] ); ?>" class="b-poppins"><?php echo wp_kses_post( $card['content'] ); ?></a>
                                        <?php else : ?>
                                            <p class="b-poppins" style="font-weight:700; color:var(--p); font-size:1.1rem"><?php echo wp_kses_post( $card['content'] ); ?></p>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $card['subtext'] ) ) : ?>
                                            <p class="b-poppins"><?php echo wp_kses_post( $card['subtext'] ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="contact-form-card">
                            <h3><?php echo esc_html( $s['side_title'] ); ?></h3>
                            <form onsubmit="tykesHandleSubmit(event, this)">
                                <div class="cf-group">
                                    <label class="cf-label b-poppins">Your Name *</label>
                                    <input type="text" class="cf-input b-poppins" placeholder="Enter your full name" required>
                                </div>
                                <div class="cf-row">
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Email Address *</label>
                                        <input type="email" class="cf-input b-poppins" placeholder="parent@email.com" required>
                                    </div>
                                    <div class="cf-group">
                                        <label class="cf-label b-poppins">Mobile Number *</label>
                                        <input type="tel" class="cf-input b-poppins" placeholder="10-digit mobile" required>
                                    </div>
                                </div>
                                <div class="cf-group">
                                    <label class="cf-label b-poppins">Your Message</label>
                                    <textarea class="cf-input b-poppins" placeholder="How can we help you?" rows="4"></textarea>
                                </div>
                                <button type="submit" class="cf-submit"><?php echo esc_html( $s['adm_form_submit_label'] ?? 'Send Message' ); ?> <?php \Elementor\Icons_Manager::render_icon( $s['adm_form_submit_icon'] ?? [ 'value' => 'fas fa-paper-plane', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?></button>
                                <div class="cf-privacy">We'll respond within 24 business hours.</div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </section>
        <?php
    }
}
