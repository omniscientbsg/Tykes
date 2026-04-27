<?php
/**
 * Widget: Tykes Header
 *
 * Nav is rendered dynamically from WordPress Appearance → Menus
 * via Tykes_DS\Nav_Walker (exact .nav-item/.nav-dropdown structure).
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

class Widget_Tykes_Header extends Widget_Base_Tykes {

    public function get_name(): string  { return 'tykes-header'; }
    public function get_title(): string { return esc_html__( 'Tykes Header', 'tykes-ds' ); }
    public function get_icon(): string  { return 'eicon-navigation-horizontal'; }

    protected function register_controls(): void {
        $this->section_logo();
        $this->section_nav();
        $this->section_cta_button();
        $this->section_popup();
        $this->section_layout();
        $this->section_style_bar();
        $this->section_style_logo();
        $this->section_style_nav();
        $this->section_style_cta_button();
        $this->section_style_popup();
        $this->add_section_spacing_controls( '#siteHeader' );
    }

    /* ── Logo ── */
    private function section_logo(): void {
        $this->start_controls_section( 'sec_logo', [ 'label' => __( '🖼 Logo', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'logo_url', [ 'label' => __( 'Desktop Logo', 'tykes-ds' ), 'type' => Controls_Manager::MEDIA, 'default' => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/03/Tykes-without-Kidzonia-Enterprise-04-scaled-e1774873351568.png' ] ] );
        $this->add_responsive_control( 'logo_height', [ 'label' => __( 'Logo Height', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 24, 'max' => 100 ] ], 'default' => [ 'size' => 46, 'unit' => 'px' ], 'selectors' => [ '#siteHeader .header-logo img' => 'height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'logo_link', [ 'label' => __( 'Logo Link', 'tykes-ds' ), 'type' => Controls_Manager::URL, 'default' => [ 'url' => home_url( '/' ) ] ] );
        $this->add_control( 'mobile_logo_url', [ 'label' => __( 'Mobile Drawer Logo', 'tykes-ds' ), 'type' => Controls_Manager::MEDIA, 'separator' => 'before', 'default' => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/03/Assets-for-website-14-e1774873291651.png' ] ] );
        $this->end_controls_section();
    }

    /* ── Navigation (WP Menu) ── */
    private function section_nav(): void {
        $this->start_controls_section( 'sec_nav', [ 'label' => __( '🧭 Navigation Menu', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $menus = wp_get_nav_menus();
        $choices = [ '' => __( '— Select Menu —', 'tykes-ds' ) ];
        foreach ( $menus as $menu ) { $choices[ $menu->term_id ] = $menu->name . ' (' . $menu->count . ')'; }
        $this->add_control( 'nav_menu_id', [ 'label' => __( 'Desktop Menu', 'tykes-ds' ), 'type' => Controls_Manager::SELECT, 'options' => $choices, 'default' => '', 'description' => __( 'Appearance → Menus. Sub-items get dropdowns automatically.', 'tykes-ds' ) ] );
        $this->add_control( 'nav_mobile_menu_id', [ 'label' => __( 'Mobile Menu (blank = same as desktop)', 'tykes-ds' ), 'type' => Controls_Manager::SELECT, 'options' => $choices, 'default' => '' ] );
        $this->end_controls_section();
    }

    /* ── CTA Button ── */
    private function section_cta_button(): void {
        $this->start_controls_section( 'sec_cta', [ 'label' => __( '🔘 CTA Button', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'cta_label',  [ 'label' => __( 'Button Label', 'tykes-ds' ), 'type' => Controls_Manager::TEXT, 'default' => 'Book a Visit' ] );
        $this->add_control( 'cta_icon',   [ 'label' => __( 'Button Icon', 'tykes-ds' ),  'type' => Controls_Manager::ICONS, 'default' => [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ] ] );
        $this->add_control( 'cta_action', [ 'label' => __( 'Button Action', 'tykes-ds' ), 'type' => Controls_Manager::SELECT, 'options' => [ 'popup' => __( 'Open Enquiry Popup', 'tykes-ds' ), 'url' => __( 'Navigate to URL', 'tykes-ds' ) ], 'default' => 'popup' ] );
        $this->add_control( 'cta_url',    [ 'label' => __( 'CTA URL', 'tykes-ds' ), 'type' => Controls_Manager::URL, 'condition' => [ 'cta_action' => 'url' ] ] );
        $this->end_controls_section();
    }

    /* ── Popup ── */
    private function section_popup(): void {
        $this->start_controls_section( 'sec_popup', [ 'label' => __( '📋 Enquiry Popup', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'popup_title',           [ 'label' => __( 'Popup Title', 'tykes-ds' ),    'type' => Controls_Manager::TEXT,     'default' => 'Book a Free Visit 🌟' ] );
        $this->add_control( 'popup_subtitle',        [ 'label' => __( 'Subtitle', 'tykes-ds' ),       'type' => Controls_Manager::TEXTAREA, 'default' => 'Fill in your details and our admissions team will get in touch.' ] );
        $this->add_control( 'popup_submit_label',    [ 'label' => __( 'Submit Label', 'tykes-ds' ),   'type' => Controls_Manager::TEXT,     'default' => 'Submit Enquiry' ] );
        $this->add_control( 'popup_submit_icon',     [ 'label' => __( 'Submit Icon', 'tykes-ds' ),    'type' => Controls_Manager::ICONS,    'default' => [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ] ] );
        $this->add_control( 'popup_success_message', [ 'label' => __( 'Success Message', 'tykes-ds' ),'type' => Controls_Manager::TEXTAREA, 'default' => 'Thank you! Our admissions team will contact you shortly.' ] );
        $this->end_controls_section();
    }

    /* ── Layout ── */
    private function section_layout(): void {
        $this->start_controls_section( 'sec_layout', [ 'label' => __( '📐 Layout', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
        $this->add_control( 'sticky_header', [ 'label' => __( 'Sticky Header', 'tykes-ds' ), 'type' => Controls_Manager::SWITCHER, 'label_on' => 'Yes', 'label_off' => 'No', 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->end_controls_section();
    }

    /* ── Style: Bar ── */
    private function section_style_bar(): void {
        $this->start_controls_section( 'style_bar', [ 'label' => __( '🎨 Header Bar', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'header_bg',         [ 'label' => __( 'Background', 'tykes-ds' ),         'type' => Controls_Manager::COLOR, 'default' => 'rgba(255,255,255,0.85)', 'selectors' => [ '#siteHeader .header-inner' => 'background:{{VALUE}};' ] ] );
        $this->add_control( 'header_scrolled_bg',[ 'label' => __( 'Background (scrolled)', 'tykes-ds' ),'type' => Controls_Manager::COLOR, 'default' => 'rgba(255,255,255,0.97)', 'selectors' => [ '#siteHeader.scrolled .header-inner' => 'background:{{VALUE}};' ] ] );
        $this->add_responsive_control( 'header_padding', [ 'label' => __( 'Inner Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px','em' ], 'default' => [ 'top'=>'18','right'=>'30','bottom'=>'18','left'=>'30','unit'=>'px','isLinked'=>false ], 'selectors' => [ '#siteHeader .header-inner' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'header_shadow', 'selector' => '#siteHeader.scrolled .header-inner' ] );
        $this->end_controls_section();
    }

    /* ── Style: Logo ── */
    private function section_style_logo(): void {
        $this->start_controls_section( 'style_logo', [ 'label' => __( '🖼 Logo Style', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'logo_max_width', [ 'label' => __( 'Max Width', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px','%' ], 'selectors' => [ '#siteHeader .header-logo img' => 'width:{{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();
    }

    /* ── Style: Nav ── */
    private function section_style_nav(): void {
        $this->start_controls_section( 'style_nav', [ 'label' => __( '🧭 Navigation Style', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'nav_typography', 'selector' => '#siteHeader .nav-item > a' ] );
        $this->add_control( 'nav_item_color',       [ 'label' => __( 'Link Colour', 'tykes-ds' ),       'type' => Controls_Manager::COLOR, 'default' => '#1E1B4B', 'selectors' => [ '#siteHeader .nav-item > a' => 'color:{{VALUE}};' ] ] );
        $this->add_control( 'nav_item_hover_color', [ 'label' => __( 'Hover Colour', 'tykes-ds' ),      'type' => Controls_Manager::COLOR, 'default' => '#8257bd', 'selectors' => [ '#siteHeader .nav-item > a:hover' => 'color:{{VALUE}};' ] ] );
        $this->add_control( 'nav_item_hover_bg',    [ 'label' => __( 'Hover Background', 'tykes-ds' ),  'type' => Controls_Manager::COLOR, 'default' => '#f3edff', 'selectors' => [ '#siteHeader .nav-item > a:hover' => 'background:{{VALUE}};' ] ] );
        $this->add_responsive_control( 'nav_gap',   [ 'label' => __( 'Gap Between Items', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'selectors' => [ '#siteHeader .main-nav' => 'gap:{{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();
    }

    /* ── Style: CTA ── */
    private function section_style_cta_button(): void {
        $this->start_controls_section( 'style_cta_btn', [ 'label' => __( '🔘 CTA Button Style', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'cta_btn_typography', 'selector' => '#siteHeader .btn-enroll' ] );
        $this->add_control( 'cta_btn_bg',     [ 'label' => __( 'Background', 'tykes-ds' ),   'type' => Controls_Manager::COLOR, 'default' => '#8257bd', 'selectors' => [ '#siteHeader .btn-enroll' => 'background:{{VALUE}};' ] ] );
        $this->add_control( 'cta_btn_color',  [ 'label' => __( 'Text Colour', 'tykes-ds' ),  'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '#siteHeader .btn-enroll' => 'color:{{VALUE}};' ] ] );
        $this->add_control( 'cta_icon_size',   [ 'label' => __( 'Icon Size', 'tykes-ds' ),   'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ], 'selectors' => [ '#siteHeader .btn-enroll i, #siteHeader .btn-enroll svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'cta_icon_color',  [ 'label' => __( 'Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '#siteHeader .btn-enroll i, #siteHeader .btn-enroll svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'cta_icon_padding', [ 'label' => __( 'Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '#siteHeader .btn-enroll i, #siteHeader .btn-enroll svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'cta_icon_margin', [ 'label' => __( 'Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '#siteHeader .btn-enroll i, #siteHeader .btn-enroll svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'cta_btn_padding', [ 'label' => __( 'Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px','em' ], 'default' => [ 'top'=>'10','right'=>'22','bottom'=>'10','left'=>'22','unit'=>'px','isLinked'=>false ], 'selectors' => [ '#siteHeader .btn-enroll' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_control( 'cta_btn_radius', [ 'label' => __( 'Border Radius', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px','%' ], 'default' => [ 'size' => 50, 'unit' => 'px' ], 'selectors' => [ '#siteHeader .btn-enroll' => 'border-radius:{{SIZE}}{{UNIT}};' ] ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [ 'name' => 'cta_btn_shadow', 'selector' => '#siteHeader .btn-enroll' ] );
        $this->end_controls_section();
    }

    /* ── Style: Popup ── */
    private function section_style_popup(): void {
        $this->start_controls_section( 'style_popup', [ 'label' => __( '📋 Popup Style', 'tykes-ds' ), 'tab' => Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'popup_title_color',      [ 'label' => __( 'Title Colour', 'tykes-ds' ),             'type' => Controls_Manager::COLOR, 'default' => '#8257bd', 'selectors' => [ '#tykes-popup-form h3' => 'color:{{VALUE}};' ] ] );
        $this->add_control( 'popup_submit_bg',        [ 'label' => __( 'Submit Button Background', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'default' => '#8257bd', 'selectors' => [ '#tykes-popup-form .submit-btn' => 'background:{{VALUE}};' ] ] );
        $this->add_control( 'popup_submit_icon_size', [ 'label' => __( 'Submit Icon Size', 'tykes-ds' ), 'type' => Controls_Manager::SLIDER, 'size_units' => [ 'px', 'em' ], 'range' => [ 'px' => [ 'min' => 10, 'max' => 50 ] ], 'selectors' => [ '#tykes-popup-form .submit-btn i, #tykes-popup-form .submit-btn svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'popup_submit_icon_color',[ 'label' => __( 'Submit Icon Colour', 'tykes-ds' ), 'type' => Controls_Manager::COLOR, 'selectors' => [ '#tykes-popup-form .submit-btn i, #tykes-popup-form .submit-btn svg' => 'color: {{VALUE}}; fill: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'popup_submit_icon_padding', [ 'label' => __( 'Submit Icon Padding', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '#tykes-popup-form .submit-btn i, #tykes-popup-form .submit-btn svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'popup_submit_icon_margin', [ 'label' => __( 'Submit Icon Margin', 'tykes-ds' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '#tykes-popup-form .submit-btn i, #tykes-popup-form .submit-btn svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_control( 'popup_input_focus_color',[ 'label' => __( 'Input Focus Border', 'tykes-ds' ),       'type' => Controls_Manager::COLOR, 'default' => '#8257bd', 'selectors' => [ '#tykes-popup-form .form-control:focus' => 'border-color:{{VALUE}};' ] ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [ 'name' => 'popup_title_typo', 'selector' => '#tykes-popup-form h3' ] );
        $this->end_controls_section();
    }

    /* ── Default standalone settings ── */
    protected function get_default_standalone_settings(): array {
        $global_menu = (int) \Tykes_DS\Admin\Settings_Page::get( 'header_menu_id', 0 );
        return [
            'logo_url'              => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/03/Tykes-without-Kidzonia-Enterprise-04-scaled-e1774873351568.png' ],
            'mobile_logo_url'       => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/03/Assets-for-website-14-e1774873291651.png' ],
            'logo_link'             => [ 'url' => home_url( '/' ) ],
            'nav_menu_id'           => $global_menu,
            'nav_mobile_menu_id'    => '',
            'cta_label'             => 'Book a Visit',
            'cta_icon'              => [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ],
            'cta_action'            => 'popup',
            'cta_url'               => [ 'url' => '' ],
            'popup_title'           => 'Book a Free Visit 🌟',
            'popup_subtitle'        => 'Fill in your details and our admissions team will get in touch.',
            'popup_submit_label'    => 'Submit Enquiry',
            'popup_submit_icon'     => [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ],
            'popup_success_message' => 'Thank you! Our admissions team will contact you shortly.',
            'sticky_header'         => 'yes',
        ];
    }

    /* ── Render ── */
    protected function render(): void {
        if ( ! class_exists( 'Tykes_DS\Nav_Walker' ) ) {
            require_once TYKES_DS_INCLUDES . 'class-nav-walker.php';
        }

        $s             = $this->get_settings_for_display();
        $logo_url      = $s['logo_url']['url']       ?? '';
        $logo_link     = $s['logo_link']['url']       ?? home_url( '/' );
        $mobile_logo   = $s['mobile_logo_url']['url'] ?? '';
        $cta_label     = $s['cta_label']             ?? 'Book a Visit';
        $cta_action    = $s['cta_action']             ?? 'popup';
        $cta_url       = $s['cta_url']['url']         ?? '#';
        $sticky        = ( $s['sticky_header'] ?? 'yes' ) === 'yes';
        $nav_id        = (int) ( $s['nav_menu_id'] ?? 0 );
        $mob_id        = (int) ( $s['nav_mobile_menu_id'] ?? 0 ) ?: $nav_id;
        $popup_title   = $s['popup_title']           ?? 'Book a Free Visit 🌟';
        $popup_sub     = $s['popup_subtitle']        ?? '';
        $submit_label  = $s['popup_submit_label']    ?? 'Submit Enquiry';
        $success_msg   = $s['popup_success_message'] ?? 'Thank you!';

        $desktop_nav = wp_nav_menu( [
            'menu'        => $nav_id ?: 0,
            'container'   => false,
            'items_wrap'  => '%3$s',
            'walker'      => new Nav_Walker(),
            'fallback_cb' => false,
            'echo'        => false,
        ] );

        $mobile_nav = wp_nav_menu( [
            'menu'        => $mob_id ?: 0,
            'container'   => false,
            'items_wrap'  => '%3$s',
            'walker'      => new Nav_Walker_Mobile(),
            'fallback_cb' => false,
            'echo'        => false,
        ] );
        ?>

<header class="site-header<?php echo $sticky ? '' : ' tykes-no-sticky'; ?>" id="siteHeader">
  <div class="header-inner">
    <a href="<?php echo esc_url( $logo_link ); ?>" class="header-logo" aria-label="<?php bloginfo( 'name' ); ?>">
      <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>">
    </a>
    <nav class="main-nav b-poppins" aria-label="<?php esc_attr_e( 'Main Navigation', 'tykes-ds' ); ?>">
      <?php
      if ( $desktop_nav ) {
          echo $desktop_nav; // phpcs:ignore
      } else {
          echo '<div class="nav-item"><a href="' . esc_url( home_url('/') ) . '">' . esc_html__( 'Home', 'tykes-ds' ) . '</a></div>';
          echo '<div class="nav-item" style="opacity:.5;font-size:.8rem;padding:8px 14px;">' . esc_html__( '← Assign a menu in widget settings', 'tykes-ds' ) . '</div>';
      }
      ?>
    </nav>
    <div class="header-cta">
      <?php if ( 'popup' === $cta_action ) : ?>
        <button class="btn-enroll" onclick="tykesOpenPopup()"><?php echo esc_html( $cta_label ); ?> <?php \Elementor\Icons_Manager::render_icon( $s['cta_icon'] ?? [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?></button>
      <?php else : ?>
        <a href="<?php echo esc_url( $cta_url ); ?>" class="btn-enroll"><?php echo esc_html( $cta_label ); ?> <?php \Elementor\Icons_Manager::render_icon( $s['cta_icon'] ?? [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?></a>
      <?php endif; ?>
      <button class="ham-btn" id="hamBtn" onclick="toggleDrawer()"
              aria-label="<?php esc_attr_e( 'Menu', 'tykes-ds' ); ?>"
              aria-expanded="false" aria-controls="mobileDrawer">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

<div class="mobile-drawer" id="mobileDrawer" onclick="handleDrawerClick(event)"
     role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'tykes-ds' ); ?>">
  <div class="drawer-panel">
    <div class="drawer-logo">
      <img src="<?php echo esc_url( $mobile_logo ); ?>" alt="<?php bloginfo( 'name' ); ?>">
    </div>
    <nav class="mob-nav" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'tykes-ds' ); ?>">
      <?php echo $mobile_nav ?: '<a href="' . esc_url( home_url('/') ) . '" class="mob-link">' . esc_html__( 'Home', 'tykes-ds' ) . '</a>'; // phpcs:ignore ?>
    </nav>
    <button class="mob-enroll-btn" onclick="closeDrawer(); tykesOpenPopup();"><?php echo esc_html( $cta_label ); ?> <?php \Elementor\Icons_Manager::render_icon( $s['cta_icon'] ?? [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?></button>
  </div>
</div>

<div id="tykes-form-overlay" onclick="tykesClosePopup()" aria-hidden="true"></div>
<div id="tykes-popup-form" role="dialog" aria-modal="true" aria-labelledby="tykes-popup-title">
  <button id="tykes-popup-close" onclick="tykesClosePopup()" aria-label="<?php esc_attr_e( 'Close popup', 'tykes-ds' ); ?>">✕</button>
  <h3 id="tykes-popup-title"><?php echo esc_html( $popup_title ); ?></h3>
  <p class="b-poppins"><?php echo esc_html( $popup_sub ); ?></p>
  <form onsubmit="return false;" novalidate>
    <div class="form-group"><input type="text"  class="form-control b-poppins" placeholder="<?php esc_attr_e( 'Parent Name *', 'tykes-ds' ); ?>" required></div>
    <div class="form-group"><input type="tel"   class="form-control b-poppins" placeholder="<?php esc_attr_e( 'Mobile *', 'tykes-ds' ); ?>" required></div>
    <div class="form-group"><input type="email" class="form-control b-poppins" placeholder="<?php esc_attr_e( 'Email *', 'tykes-ds' ); ?>" required></div>
    <div class="form-group">
      <select class="form-control b-poppins" required>
        <option value="" disabled selected><?php esc_html_e( "Child's Age Group *", 'tykes-ds' ); ?></option>
        <option><?php esc_html_e( '2–3 Yrs (Play Group)', 'tykes-ds' ); ?></option>
        <option><?php esc_html_e( '3–4 Yrs (Nursery)',    'tykes-ds' ); ?></option>
        <option><?php esc_html_e( '4–5 Yrs (Junior KG)',  'tykes-ds' ); ?></option>
        <option><?php esc_html_e( '5–6 Yrs (Senior KG)',  'tykes-ds' ); ?></option>
        <option><?php esc_html_e( 'Daycare',               'tykes-ds' ); ?></option>
      </select>
    </div>
    <button type="submit" class="submit-btn b-poppins" onclick="tykesClosePopup(); alert('<?php echo esc_js( $success_msg ); ?>');">
      <?php echo esc_html( $submit_label ); ?> <?php \Elementor\Icons_Manager::render_icon( $s['popup_submit_icon'] ?? [ 'value' => 'fas fa-arrow-right', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?>
    </button>
  </form>
</div>
        <?php
    }
}
