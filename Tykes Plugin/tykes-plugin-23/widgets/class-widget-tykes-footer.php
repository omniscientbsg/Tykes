<?php
/**
 * Widget: Tykes Footer
 * Named: "Tykes Footer" (global widget naming convention).
 *
 * @package Tykes_DS
 */
namespace Tykes_DS;
defined( 'ABSPATH' ) || exit;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

class Widget_Tykes_Footer extends Widget_Base_Tykes {
    public function get_name(): string  { return 'tykes-footer'; }
    public function get_title(): string { return esc_html__( 'Tykes Footer', 'tykes-ds' ); }
    public function get_icon(): string  { return 'eicon-footer'; }

    protected function register_controls(): void {
        /* Brand */
        $this->start_controls_section( 'sec_brand', [ 'label' => __( '🖼 Brand', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'footer_logo', [ 'label' => __( 'Logo', 'tykes-ds' ), 'type' => Controls_Manager::MEDIA, 'default' => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/03/Tykes-Kidzonia-Logo.png' ] ] );
        $this->add_responsive_control( 'logo_height', [ 'label' => __( 'Logo Height', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'default' => [ 'size' => 52 ], 'selectors' => [ '{{WRAPPER}} .footer-brand img' => 'height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'brand_description', [ 'label' => __( 'Description', 'tykes-ds' ), 'type' => Controls_Manager::TEXTAREA, 'default' => "Built on the academic foundation of Kidzonia International Preschools — India's most awarded early childhood education network." ] );
        $this->end_controls_section();

        /* Social */
        $this->start_controls_section( 'sec_social', [ 'label' => __( '🔗 Social', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $sr = new Repeater();
        $sr->add_control( 'social_icon',  [ 'label' => __( 'Icon', 'tykes-ds' ),       'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ] ] );
        $sr->add_control( 'social_url',   [ 'label' => __( 'URL', 'tykes-ds' ),        'type' => Controls_Manager::URL ] );
        $sr->add_control( 'social_label', [ 'label' => __( 'Aria Label', 'tykes-ds' ), 'type' => Controls_Manager::TEXT, 'default' => 'Social' ] );
        $this->add_control( 'social_links', [ 'label' => __( 'Links', 'tykes-ds' ), 'type' => Controls_Manager::REPEATER, 'fields' => $sr->get_controls(), 'default' => [ [ 'social_icon' => [ 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ], 'social_url' => [ 'url' => 'https://instagram.com' ], 'social_label' => 'Instagram' ], [ 'social_icon' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ], 'social_url' => [ 'url' => 'https://facebook.com' ], 'social_label' => 'Facebook' ], [ 'social_icon' => [ 'value' => 'fab fa-youtube', 'library' => 'fa-brands' ], 'social_url' => [ 'url' => 'https://youtube.com' ], 'social_label' => 'YouTube' ] ], 'title_field' => '{{{ social_label }}}' ] );
        $this->end_controls_section();

        /* Programmes */
        $this->start_controls_section( 'sec_progs', [ 'label' => __( '📚 Programmes Column', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'programmes_heading', [ 'label' => __( 'Heading', 'tykes-ds' ), 'type' => Controls_Manager::TEXT, 'default' => 'Programmes' ] );
        $lr = new Repeater(); $lr->add_control( 'link_label', [ 'label' => __( 'Label', 'tykes-ds' ), 'type' => Controls_Manager::TEXT, 'default' => 'Link' ] ); $lr->add_control( 'link_url', [ 'label' => __( 'URL', 'tykes-ds' ), 'type' => Controls_Manager::URL ] );
        $this->add_control( 'programmes_links', [ 'label' => __( 'Links', 'tykes-ds' ), 'type' => Controls_Manager::REPEATER, 'fields' => $lr->get_controls(), 'default' => [ [ 'link_label' => 'Play Group', 'link_url' => [ 'url' => home_url('/tykes-programmes/#playgroup') ] ], [ 'link_label' => 'Nursery', 'link_url' => [ 'url' => home_url('/tykes-programmes/#nursery') ] ], [ 'link_label' => 'Junior KG', 'link_url' => [ 'url' => home_url('/tykes-programmes/#junior') ] ], [ 'link_label' => 'Senior KG', 'link_url' => [ 'url' => home_url('/tykes-programmes/#senior') ] ], [ 'link_label' => 'Daycare', 'link_url' => [ 'url' => home_url('/tykes-programmes/#daycare') ] ] ], 'title_field' => '{{{ link_label }}}' ] );
        $this->end_controls_section();

        /* Quick Links */
        $this->start_controls_section( 'sec_ql', [ 'label' => __( '🔗 Quick Links Column', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'quick_heading', [ 'label' => __( 'Heading', 'tykes-ds' ), 'type' => Controls_Manager::TEXT, 'default' => 'Quick Links' ] );
        $qlr = new Repeater(); $qlr->add_control( 'link_label', [ 'label' => __( 'Label', 'tykes-ds' ), 'type' => Controls_Manager::TEXT, 'default' => 'Link' ] ); $qlr->add_control( 'link_url', [ 'label' => __( 'URL', 'tykes-ds' ), 'type' => Controls_Manager::URL ] );
        $this->add_control( 'quick_links', [ 'label' => __( 'Links', 'tykes-ds' ), 'type' => Controls_Manager::REPEATER, 'fields' => $qlr->get_controls(), 'default' => [ [ 'link_label' => 'About Us', 'link_url' => [ 'url' => home_url('/about-us/') ] ], [ 'link_label' => 'Our Curriculum', 'link_url' => [ 'url' => home_url('/curriculum/') ] ], [ 'link_label' => 'Admissions', 'link_url' => [ 'url' => '#' ] ], [ 'link_label' => 'Corporate Daycare', 'link_url' => [ 'url' => home_url('/corporate-daycare/') ] ], [ 'link_label' => 'Franchise', 'link_url' => [ 'url' => '#' ] ] ], 'title_field' => '{{{ link_label }}}' ] );
        $this->end_controls_section();

        /* Contact */
        $this->start_controls_section( 'sec_contact', [ 'label' => __( '📞 Contact Column', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'contact_heading', [ 'label' => __( 'Heading', 'tykes-ds' ),  'type' => Controls_Manager::TEXT, 'default' => 'Get In Touch' ] );
        $this->add_control( 'contact_phone',   [ 'label' => __( 'Phone', 'tykes-ds' ),    'type' => Controls_Manager::TEXT, 'default' => '8400-966-400' ] );
        $this->add_control( 'contact_phone_icon', [ 'label' => 'Phone Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ] ] );
        $this->add_control( 'contact_email',   [ 'label' => __( 'Email', 'tykes-ds' ),    'type' => Controls_Manager::TEXT, 'default' => 'info@tykes.school' ] );
        $this->add_control( 'contact_email_icon', [ 'label' => 'Email Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ] ] );
        $this->add_control( 'contact_website', [ 'label' => __( 'Website', 'tykes-ds' ),  'type' => Controls_Manager::TEXT, 'default' => 'tykes.school' ] );
        $this->add_control( 'contact_website_icon', [ 'label' => 'Website Icon', 'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-globe', 'library' => 'fa-solid' ] ] );
        $this->end_controls_section();

        /* Copyright */
        $this->start_controls_section( 'sec_copy', [ 'label' => __( '©️ Copyright', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'copyright_text', [ 'label' => __( 'Text (use {year})', 'tykes-ds' ), 'type' => Controls_Manager::TEXTAREA, 'default' => '© {year} Tykes Early Years — A Kidzonia Enterprise. All rights reserved.' ] );
        $this->add_control( 'copyright_highlight', [ 'label' => __( 'Highlighted Phrase (turns gold)', 'tykes-ds' ), 'type' => Controls_Manager::TEXT, 'default' => 'Tykes Early Years' ] );
        $this->end_controls_section();

        /* Style */
        $this->start_controls_section( 'style_footer', [ 'label' => __( '🎨 Footer Style', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'footer_gradient', [ 'label' => __( 'Gradient Start', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#8257bd', 'selectors' => [ '{{WRAPPER}} .site-footer' => 'background: linear-gradient(135deg,{{VALUE}},#6d46a8);' ] ] );
        $this->add_control( 'footer_link_color',       [ 'label' => __( 'Link Colour', 'tykes-ds' ),       'type' => Controls_Manager::COLOR, 'default' => 'rgba(255,255,255,.6)',  'selectors' => [ '{{WRAPPER}} .footer-col ul a' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'footer_link_hover_color', [ 'label' => __( 'Link Hover Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#fdbc02',               'selectors' => [ '{{WRAPPER}} .footer-col ul a:hover' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'footer_heading_color',    [ 'label' => __( 'Column Heading', 'tykes-ds' ),    'type' => Controls_Manager::COLOR, 'default' => '#fff',                  'selectors' => [ '{{WRAPPER}} .footer-col h5' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'footer_link_typo', 'selector' => '{{WRAPPER}} .footer-col ul a' ] );

        $this->add_control( 'heading_social_icons', [ 'label' => __( 'Social Icons', 'tykes-ds' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
        $this->add_control( 'social_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ], 'selectors' => [ '{{WRAPPER}} .social-btn i, {{WRAPPER}} .social-btn svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'social_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .social-btn i, {{WRAPPER}} .social-btn svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'social_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .social-btn i, {{WRAPPER}} .social-btn svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'social_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .social-btn i, {{WRAPPER}} .social-btn svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_control( 'social_icon_hover_color', [ 'label' => __( 'Icon Hover Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .social-btn:hover i, {{WRAPPER}} .social-btn:hover svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );

        $this->add_control( 'heading_contact_icons', [ 'label' => __( 'Contact Icons', 'tykes-ds' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
        $this->add_control( 'contact_icon_size', [ 'label' => __( 'Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ], 'selectors' => [ '{{WRAPPER}} .footer-contact-item span i, {{WRAPPER}} .footer-contact-item span svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'contact_icon_color', [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .footer-contact-item span i, {{WRAPPER}} .footer-contact-item span svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'contact_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .footer-contact-item span i, {{WRAPPER}} .footer-contact-item span svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'contact_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .footer-contact-item span i, {{WRAPPER}} .footer-contact-item span svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->add_control( 'heading_brand_logo', [ 'label' => __( 'Brand Logo', 'tykes-ds' ), 'type' => Controls_Manager::HEADING, 'separator' => 'before' ] );
        $this->add_responsive_control( 'logo_padding', [ 'label' => __( 'Logo Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .footer-brand img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'logo_margin',  [ 'label' => __( 'Logo Margin', 'tykes-ds' ),  'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .footer-brand img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );

        $this->end_controls_section();

        $this->add_section_spacing_controls( '{{WRAPPER}} .site-footer' );
    }

    protected function get_default_standalone_settings(): array {
        return [
            'footer_logo'          => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/03/Tykes-Kidzonia-Logo.png' ],
            'brand_description'    => "Built on the academic foundation of Kidzonia International Preschools — India's most awarded early childhood education network.",
            'social_links'         => [ [ 'social_icon' => [ 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ], 'social_url' => [ 'url' => 'https://instagram.com' ], 'social_label' => 'Instagram' ] ],
            'programmes_heading'   => 'Programmes',
            'programmes_links'     => [ [ 'link_label' => 'Play Group', 'link_url' => [ 'url' => home_url('/tykes-programmes/#playgroup') ] ] ],
            'quick_heading'        => 'Quick Links',
            'quick_links'          => [ [ 'link_label' => 'About Us', 'link_url' => [ 'url' => home_url('/about-us/') ] ] ],
            'contact_heading'      => 'Get In Touch',
            'contact_phone'        => '8400-966-400',
            'contact_phone_icon'   => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ],
            'contact_email'        => 'info@tykes.school',
            'contact_email_icon'   => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ],
            'contact_website'      => 'tykes.school',
            'contact_website_icon' => [ 'value' => 'fas fa-globe', 'library' => 'fa-solid' ],
            'copyright_text'       => '© {year} Tykes Early Years — A Kidzonia Enterprise. All rights reserved.',
            'copyright_highlight'  => 'Tykes Early Years',
        ];
    }

    protected function render(): void {
        $s        = $this->get_settings_for_display();
        $year     = gmdate( 'Y' );
        $copyright = str_replace( '{year}', $year, esc_html( $s['copyright_text'] ) );
        if ( $s['copyright_highlight'] ) {
            $copyright = str_replace( esc_html( $s['copyright_highlight'] ), '<span>' . esc_html( $s['copyright_highlight'] ) . '</span>', $copyright );
        }
        ?>
<footer class="site-footer tykes-footer-native">
  <div class="footer-top">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand b-poppins">
          <img src="<?php echo esc_url( $s['footer_logo']['url'] ); ?>" alt="<?php bloginfo('name'); ?>">
          <p><?php echo esc_html( $s['brand_description'] ); ?></p>
          <div class="social-links">
            <?php foreach ( $s['social_links'] as $soc ) : ?>
              <a href="<?php echo esc_url( $soc['social_url']['url'] ?? '#' ); ?>" class="social-btn" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $soc['social_label'] ); ?>">
                <?php \Elementor\Icons_Manager::render_icon( $soc['social_icon'], [ 'aria-hidden' => 'true' ] ); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="footer-col b-poppins">
          <h5 class="h-fredoka"><?php echo esc_html( $s['programmes_heading'] ); ?></h5>
          <ul><?php foreach ( $s['programmes_links'] as $l ) : ?><li><a href="<?php echo esc_url( $l['link_url']['url'] ?? '#' ); ?>"><?php echo esc_html( $l['link_label'] ); ?></a></li><?php endforeach; ?></ul>
        </div>
        <div class="footer-col b-poppins">
          <h5 class="h-fredoka"><?php echo esc_html( $s['quick_heading'] ); ?></h5>
          <ul><?php foreach ( $s['quick_links'] as $l ) : ?><li><a href="<?php echo esc_url( $l['link_url']['url'] ?? '#' ); ?>"><?php echo esc_html( $l['link_label'] ); ?></a></li><?php endforeach; ?></ul>
        </div>
        <div class="footer-col b-poppins">
          <h5 class="h-fredoka"><?php echo esc_html( $s['contact_heading'] ); ?></h5>
          <?php if ( $s['contact_phone'] ) : ?><div class="footer-contact-item"><span><?php \Elementor\Icons_Manager::render_icon( $s['contact_phone_icon'], [ 'aria-hidden' => 'true' ] ); ?></span><a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/','',$s['contact_phone']) ); ?>"><?php echo esc_html( $s['contact_phone'] ); ?></a></div><?php endif; ?>
          <?php if ( $s['contact_email'] ) : ?><div class="footer-contact-item"><span><?php \Elementor\Icons_Manager::render_icon( $s['contact_email_icon'], [ 'aria-hidden' => 'true' ] ); ?></span><a href="mailto:<?php echo esc_attr( $s['contact_email'] ); ?>"><?php echo esc_html( $s['contact_email'] ); ?></a></div><?php endif; ?>
          <?php if ( $s['contact_website'] ) : ?><div class="footer-contact-item"><span><?php \Elementor\Icons_Manager::render_icon( $s['contact_website_icon'], [ 'aria-hidden' => 'true' ] ); ?></span><a href="https://<?php echo esc_attr( $s['contact_website'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $s['contact_website'] ); ?></a></div><?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom b-poppins">
    <div class="container"><p><?php echo wp_kses( $copyright, [ 'span' => [] ] ); ?></p></div>
  </div>
</footer>
        <?php
    }
}
