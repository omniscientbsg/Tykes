<?php
/**
 * Widget: Tykes Contact Social & Hours
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Widget_Tykes_Contact_Social extends Widget_Base_Tykes {

    public function get_name(): string  { return 'tykes-contact-social'; }
    public function get_title(): string { return esc_html__( 'Tykes Contact Social', 'tykes-ds' ); }
    public function get_icon(): string  { return 'eicon-share'; }

    protected function register_controls(): void {
        $this->start_controls_section( 'sec_social_left', [
            'label' => __( 'Left Column: Social', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'social_title', [
            'label'   => __( 'Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Follow Us',
        ] );

        $this->add_control( 'social_desc', [
            'label'   => __( 'Description', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Stay connected with our community, see what\'s happening at Tykes centres, and get early access to admissions updates.',
        ] );

        $repeater = new Repeater();
        $repeater->add_control( 'icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ] ] );
        $repeater->add_control( 'icon_bg', [ 'label' => 'Icon Background CSS', 'type' => Controls_Manager::TEXT, 'default' => 'linear-gradient(135deg,#f9ce34,#ee2a7b,#6228d7)' ] );
        $repeater->add_control( 'platform', [ 'label' => 'Platform', 'type' => Controls_Manager::TEXT, 'default' => 'Instagram' ] );
        $repeater->add_control( 'handle', [ 'label' => 'Handle', 'type' => Controls_Manager::TEXT, 'default' => '@tykesearlyyears' ] );
        $repeater->add_control( 'link_url', [ 'label' => 'Link', 'type' => Controls_Manager::URL ] );

        $this->add_control( 'social_links', [
            'label'       => __( 'Social Links', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ platform }}}',
            'default'     => [
                [ 'icon' => [ 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ], 'icon_bg' => 'linear-gradient(135deg,#f9ce34,#ee2a7b,#6228d7)', 'platform' => 'Instagram', 'handle' => '@tykesearlyyears', 'link_url' => ['url'=>'https://instagram.com'] ],
                [ 'icon' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ], 'icon_bg' => '#1877f2', 'platform' => 'Facebook', 'handle' => 'TykesEarlyYears', 'link_url' => ['url'=>'https://facebook.com'] ],
                [ 'icon' => [ 'value' => 'fab fa-youtube', 'library' => 'fa-brands' ], 'icon_bg' => '#FF0000', 'platform' => 'YouTube', 'handle' => 'Tykes Early Years', 'link_url' => ['url'=>'https://youtube.com'] ],
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'sec_hours_right', [
            'label' => __( 'Right Column: Hours', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'hours_title', [
            'label'   => __( 'Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Office Hours',
        ] );

        $this->add_control( 'hours_desc', [
            'label'   => __( 'Description', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Our team is available Monday through Saturday. Individual centres may vary — contact your nearest centre for specific timings.',
        ] );

        $hours_rep = new Repeater();
        $hours_rep->add_control( 'day', [ 'label' => 'Day', 'type' => Controls_Manager::TEXT, 'default' => 'Monday' ] );
        $hours_rep->add_control( 'hours', [ 'label' => 'Hours', 'type' => Controls_Manager::TEXT, 'default' => '9:00 am – 6:00 pm' ] );
        $hours_rep->add_control( 'status', [ 
            'label' => 'Status', 
            'type' => Controls_Manager::SELECT, 
            'options' => [ 'open' => 'Open', 'closed' => 'Closed' ], 
            'default' => 'open' 
        ] );

        $this->add_control( 'hours_table', [
            'label'       => __( 'Hours Table', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $hours_rep->get_controls(),
            'title_field' => '{{{ day }}}',
            'default'     => [
                [ 'day' => 'Monday', 'hours' => '9:00 am – 6:00 pm', 'status' => 'open' ],
                [ 'day' => 'Tuesday', 'hours' => '9:00 am – 6:00 pm', 'status' => 'open' ],
                [ 'day' => 'Wednesday', 'hours' => '9:00 am – 6:00 pm', 'status' => 'open' ],
                [ 'day' => 'Thursday', 'hours' => '9:00 am – 6:00 pm', 'status' => 'open' ],
                [ 'day' => 'Friday', 'hours' => '9:00 am – 6:00 pm', 'status' => 'open' ],
                [ 'day' => 'Saturday', 'hours' => '9:00 am – 2:00 pm', 'status' => 'open' ],
                [ 'day' => 'Sunday', 'hours' => '—', 'status' => 'closed' ],
            ],
        ] );

        $this->add_control( 'hours_notice', [
            'label'   => __( 'Bottom Notice', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => '💡 For admissions enquiries outside office hours, fill the form above — we\'ll respond the next business day.',
        ] );

        $this->end_controls_section();

        $this->style_section_icons();
        $this->add_section_spacing_controls( '{{WRAPPER}} .social-sec' );
    }

    private function style_section_icons(): void {
        $this->start_controls_section( 'style_icons', [ 'label' => __( '🎨 Icon Controls', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'social_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'selectors' => [ '{{WRAPPER}} .sc-icon i, {{WRAPPER}} .sc-icon svg' => 'font-size: {{SIZE}}px; width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );
        $this->add_control( 'social_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .sc-icon i, {{WRAPPER}} .sc-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'social_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .sc-icon i, {{WRAPPER}} .sc-icon svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'social_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .sc-icon i, {{WRAPPER}} .sc-icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <section class="social-sec">
            <div class="container">
                <div class="social-inner">
                    <!-- Social profiles -->
                    <div class="social-col">
                        <h3><?php echo esc_html( $s['social_title'] ); ?></h3>
                        <p class="b-poppins"><?php echo wp_kses_post( $s['social_desc'] ); ?></p>
                        <div class="social-cards">
                            <?php foreach ( $s['social_links'] as $link ) : ?>
                                <a href="<?php echo esc_url( $link['link_url']['url'] ?? '#' ); ?>" target="_blank" rel="noopener" class="social-card" style="text-decoration:none">
                                    <div class="sc-icon" style="background:<?php echo esc_attr( $link['icon_bg'] ); ?>"><?php \Elementor\Icons_Manager::render_icon( $link['icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
                                    <div>
                                        <h5 class="b-poppins"><?php echo esc_html( $link['platform'] ); ?></h5>
                                        <p class="b-poppins"><?php echo esc_html( $link['handle'] ); ?></p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Office hours -->
                    <div class="hours-col">
                        <h3><?php echo esc_html( $s['hours_title'] ); ?></h3>
                        <p class="b-poppins"><?php echo wp_kses_post( $s['hours_desc'] ); ?></p>
                        <table class="hours-table">
                            <thead>
                                <tr>
                                    <th class="b-poppins">Day</th>
                                    <th class="b-poppins">Hours</th>
                                    <th class="b-poppins">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $s['hours_table'] as $row ) : ?>
                                    <tr>
                                        <td class="b-poppins"><?php echo esc_html( $row['day'] ); ?></td>
                                        <td class="b-poppins"><?php echo esc_html( $row['hours'] ); ?></td>
                                        <td>
                                            <?php if ( $row['status'] === 'open' ) : ?>
                                                <span class="open-pill b-poppins">Open</span>
                                            <?php else : ?>
                                                <span style="background:#fee2e2;color:#dc2626;font-size:.72rem;font-weight:800;padding:3px 10px;border-radius:50px" class="b-poppins">Closed</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if ( ! empty( $s['hours_notice'] ) ) : ?>
                            <div style="background:var(--bg-lav);border-radius:14px;padding:16px 20px;margin-top:16px;font-size:.88rem;color:var(--muted)" class="b-poppins">
                                <?php echo wp_kses_post( $s['hours_notice'] ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}
