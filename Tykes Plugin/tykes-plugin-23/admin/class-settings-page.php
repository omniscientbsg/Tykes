<?php
/**
 * Admin Settings Page
 *
 * WordPress Dashboard → Settings → Tykes Settings
 * Provides controls for:
 *  - Global header template or menu
 *  - Global footer template
 *  - Full-width enforcement toggle
 *  - Plugin color overrides
 *
 * @package Tykes_DS
 */

namespace Tykes_DS\Admin;

defined( 'ABSPATH' ) || exit;

class Settings_Page {

    private static $instance = null;
    const OPTION_KEY = 'tykes_ds_settings';
    const PAGE_SLUG  = 'tykes-ds-settings';

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_menu',    [ $this, 'register_menu' ] );
        add_action( 'admin_init',    [ $this, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
        add_action( 'admin_post_tykes_convert_elementor', [ $this, 'handle_elementor_conversion' ] );
    }

    /* ── Menu registration ──────────────────────────────────────── */

    public function register_menu(): void {
        add_menu_page(
            esc_html__( 'Tykes Design System', 'tykes-ds' ),
            esc_html__( 'Tykes Design System', 'tykes-ds' ),
            'manage_options',
            self::PAGE_SLUG,
            [ $this, 'render_page' ],
            'dashicons-welcome-learn-more',
            60
        );
    }

    /* ── Settings API registration ──────────────────────────────── */

    public function register_settings(): void {
        register_setting(
            'tykes_ds_group',
            self::OPTION_KEY,
            [ 'sanitize_callback' => [ $this, 'sanitize_options' ] ]
        );

        /* ── Header section ── */
        add_settings_section( 'tykes_header_section', __( '🧭 Global Header', 'tykes-ds' ), '__return_null', self::PAGE_SLUG );

        add_settings_field( 'header_type', __( 'Header Source', 'tykes-ds' ), [ $this, 'field_header_type' ], self::PAGE_SLUG, 'tykes_header_section' );
        add_settings_field( 'header_template_id', __( 'Elementor Header Template', 'tykes-ds' ), [ $this, 'field_header_template' ], self::PAGE_SLUG, 'tykes_header_section' );
        add_settings_field( 'header_menu_id', __( 'WordPress Menu', 'tykes-ds' ), [ $this, 'field_header_menu' ], self::PAGE_SLUG, 'tykes_header_section' );
        add_settings_field( 'header_widget', __( 'Tykes Header Widget', 'tykes-ds' ), [ $this, 'field_header_widget_toggle' ], self::PAGE_SLUG, 'tykes_header_section' );

        /* ── Footer section ── */
        add_settings_section( 'tykes_footer_section', __( '🦶 Global Footer', 'tykes-ds' ), '__return_null', self::PAGE_SLUG );

        add_settings_field( 'footer_type', __( 'Footer Source', 'tykes-ds' ), [ $this, 'field_footer_type' ], self::PAGE_SLUG, 'tykes_footer_section' );
        add_settings_field( 'footer_template_id', __( 'Elementor Footer Template', 'tykes-ds' ), [ $this, 'field_footer_template' ], self::PAGE_SLUG, 'tykes_footer_section' );
        add_settings_field( 'footer_widget', __( 'Tykes Footer Widget', 'tykes-ds' ), [ $this, 'field_footer_widget_toggle' ], self::PAGE_SLUG, 'tykes_footer_section' );

        /* ── Layout section ── */
        add_settings_section( 'tykes_layout_section', __( '📐 Layout & Full-Width', 'tykes-ds' ), '__return_null', self::PAGE_SLUG );

        add_settings_field( 'force_full_width', __( 'Force Full-Width Sections', 'tykes-ds' ), [ $this, 'field_force_full_width' ], self::PAGE_SLUG, 'tykes_layout_section' );
        add_settings_field( 'remove_elementor_section_spacing', __( 'Remove Elementor Section Vertical Spacing', 'tykes-ds' ), [ $this, 'field_remove_elementor_section_spacing' ], self::PAGE_SLUG, 'tykes_layout_section' );
        add_settings_field( 'disable_hello_header', __( 'Disable Hello Theme Header', 'tykes-ds' ), [ $this, 'field_disable_hello_header' ], self::PAGE_SLUG, 'tykes_layout_section' );
        add_settings_field( 'disable_hello_footer', __( 'Disable Hello Theme Footer', 'tykes-ds' ), [ $this, 'field_disable_hello_footer' ], self::PAGE_SLUG, 'tykes_layout_section' );

        /* ── Branding section ── */
        add_settings_section( 'tykes_brand_section', __( '🎨 Brand Colours', 'tykes-ds' ), '__return_null', self::PAGE_SLUG );

        add_settings_field( 'brand_primary', __( 'Primary Colour', 'tykes-ds' ), [ $this, 'field_color_primary' ], self::PAGE_SLUG, 'tykes_brand_section' );
        add_settings_field( 'brand_gold', __( 'Gold / Accent Colour', 'tykes-ds' ), [ $this, 'field_color_gold' ], self::PAGE_SLUG, 'tykes_brand_section' );
        add_settings_field( 'brand_teal', __( 'Teal Colour', 'tykes-ds' ), [ $this, 'field_color_teal' ], self::PAGE_SLUG, 'tykes_brand_section' );
    }

    /* ── Sanitizer ──────────────────────────────────────────────── */

    public function sanitize_options( array $input ): array {
        $clean = [];
        $clean['header_type']         = in_array( $input['header_type'] ?? '', [ 'widget', 'template', 'menu' ], true ) ? $input['header_type'] : 'widget';
        $clean['header_template_id']  = absint( $input['header_template_id'] ?? 0 );
        $clean['header_menu_id']      = absint( $input['header_menu_id'] ?? 0 );
        $clean['header_widget']       = ! empty( $input['header_widget'] ) ? 1 : 0;
        $clean['footer_type']         = in_array( $input['footer_type'] ?? '', [ 'widget', 'template' ], true ) ? $input['footer_type'] : 'widget';
        $clean['footer_template_id']  = absint( $input['footer_template_id'] ?? 0 );
        $clean['footer_widget']       = ! empty( $input['footer_widget'] ) ? 1 : 0;
        $clean['force_full_width']    = ! empty( $input['force_full_width'] ) ? 1 : 0;
        $clean['disable_hello_header']= ! empty( $input['disable_hello_header'] ) ? 1 : 0;
        $clean['disable_hello_footer']= ! empty( $input['disable_hello_footer'] ) ? 1 : 0;
        $clean['brand_primary']       = sanitize_hex_color( $input['brand_primary'] ?? '#8257bd' );
        $clean['brand_gold']          = sanitize_hex_color( $input['brand_gold'] ?? '#fdbc02' );
        $clean['brand_teal']          = sanitize_hex_color( $input['brand_teal'] ?? '#05a28d' );
        $clean['remove_elementor_section_spacing'] = ! empty( $input['remove_elementor_section_spacing'] ) ? 1 : 0;
        return $clean;
    }

    /* ── Static option getter ───────────────────────────────────── */

    public static function get( string $key, $default = null ) {
        $options = get_option( self::OPTION_KEY, [] );
        return $options[ $key ] ?? $default;
    }

    /* ── Field renderers ────────────────────────────────────────── */

    private function opt( string $key ) {
        return self::get( $key );
    }

    public function field_header_type(): void {
        $val = $this->opt( 'header_type' ) ?: 'widget';
        ?>
        <select name="<?php echo esc_attr( self::OPTION_KEY ); ?>[header_type]" id="tykes_header_type" class="tykes-select">
            <option value="widget"   <?php selected( $val, 'widget' ); ?>><?php esc_html_e( 'Tykes Header Widget (recommended)', 'tykes-ds' ); ?></option>
            <option value="template" <?php selected( $val, 'template' ); ?>><?php esc_html_e( 'Elementor Template', 'tykes-ds' ); ?></option>
            <option value="menu"     <?php selected( $val, 'menu' ); ?>><?php esc_html_e( 'WordPress Menu', 'tykes-ds' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Choose how the global header is rendered on every page.', 'tykes-ds' ); ?></p>

        <?php if ( 'widget' === $val ) : ?>
            <p style="margin-top:10px;">
                <a href="<?php echo esc_url( admin_url( 'admin-post.php?action=tykes_convert_elementor&type=header&_wpnonce=' . wp_create_nonce('tykes_convert') ) ); ?>" class="button button-primary" style="background: #8257bd; border-color: #8257bd;">
                    <?php esc_html_e( '✨ Convert & Edit Header in Elementor', 'tykes-ds' ); ?>
                </a>
            </p>
        <?php endif; ?>
        <?php
    }

    public function field_header_template(): void {
        $val       = (int) ( $this->opt( 'header_template_id' ) ?? 0 );
        $templates = $this->get_elementor_templates();
        ?>
        <select name="<?php echo esc_attr( self::OPTION_KEY ); ?>[header_template_id]" class="tykes-select">
            <option value="0"><?php esc_html_e( '— Select Template —', 'tykes-ds' ); ?></option>
            <?php foreach ( $templates as $id => $title ) : ?>
                <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $val, $id ); ?>>
                    <?php echo esc_html( $title ); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e( 'Used when Header Source = Elementor Template.', 'tykes-ds' ); ?></p>
        <p><a href="<?php echo esc_url( $this->get_elementor_template_create_url( 'header' ) ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Create new Elementor header template', 'tykes-ds' ); ?></a></p>
        <p><a href="<?php echo esc_url( $this->get_elementor_templates_library_url() ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Open Elementor template library', 'tykes-ds' ); ?></a></p>
        <?php if ( $val ) : ?>
            <p><a href="<?php echo esc_url( $this->get_elementor_template_edit_url( $val ) ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Edit selected header template in Elementor', 'tykes-ds' ); ?></a></p>
        <?php endif; ?>
        <?php
    }

    public function field_header_menu(): void {
        $val   = (int) ( $this->opt( 'header_menu_id' ) ?? 0 );
        $menus = wp_get_nav_menus();
        ?>
        <select name="<?php echo esc_attr( self::OPTION_KEY ); ?>[header_menu_id]" class="tykes-select">
            <option value="0"><?php esc_html_e( '— Select Menu —', 'tykes-ds' ); ?></option>
            <?php foreach ( $menus as $menu ) : ?>
                <option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $val, $menu->term_id ); ?>>
                    <?php echo esc_html( $menu->name ); ?> (<?php echo esc_html( $menu->count ); ?> items)
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e( 'Used when Header Source = WordPress Menu.', 'tykes-ds' ); ?></p>
        <?php
    }

    public function field_header_widget_toggle(): void {
        $val = $this->opt( 'header_widget' ) ?? 1;
        ?>
        <label class="tykes-toggle">
            <input type="checkbox" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[header_widget]" value="1" <?php checked( $val, 1 ); ?>>
            <span><?php esc_html_e( 'Inject Tykes Header widget on every page automatically', 'tykes-ds' ); ?></span>
        </label>
        <?php
    }

    public function field_footer_type(): void {
        $val = $this->opt( 'footer_type' ) ?: 'widget';
        ?>
        <select name="<?php echo esc_attr( self::OPTION_KEY ); ?>[footer_type]" class="tykes-select">
            <option value="widget"   <?php selected( $val, 'widget' ); ?>><?php esc_html_e( 'Tykes Footer Widget (recommended)', 'tykes-ds' ); ?></option>
            <option value="template" <?php selected( $val, 'template' ); ?>><?php esc_html_e( 'Elementor Template', 'tykes-ds' ); ?></option>
        </select>

        <?php if ( 'widget' === $val ) : ?>
            <p style="margin-top:10px;">
                <a href="<?php echo esc_url( admin_url( 'admin-post.php?action=tykes_convert_elementor&type=footer&_wpnonce=' . wp_create_nonce('tykes_convert') ) ); ?>" class="button button-primary" style="background: #8257bd; border-color: #8257bd;">
                    <?php esc_html_e( '✨ Convert & Edit Footer in Elementor', 'tykes-ds' ); ?>
                </a>
            </p>
        <?php endif; ?>
        <?php
    }

    public function field_footer_template(): void {
        $val       = (int) ( $this->opt( 'footer_template_id' ) ?? 0 );
        $templates = $this->get_elementor_templates();
        ?>
        <select name="<?php echo esc_attr( self::OPTION_KEY ); ?>[footer_template_id]" class="tykes-select">
            <option value="0"><?php esc_html_e( '— Select Template —', 'tykes-ds' ); ?></option>
            <?php foreach ( $templates as $id => $title ) : ?>
                <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $val, $id ); ?>>
                    <?php echo esc_html( $title ); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e( 'Used when Footer Source = Elementor Template.', 'tykes-ds' ); ?></p>
        <p><a href="<?php echo esc_url( $this->get_elementor_template_create_url( 'footer' ) ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Create new Elementor footer template', 'tykes-ds' ); ?></a></p>
        <p><a href="<?php echo esc_url( $this->get_elementor_templates_library_url() ); ?>" class="button button-secondary" target="_blank"><?php esc_html_e( 'Open Elementor template library', 'tykes-ds' ); ?></a></p>
        <?php if ( $val ) : ?>
            <p><a href="<?php echo esc_url( $this->get_elementor_template_edit_url( $val ) ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Edit selected footer template in Elementor', 'tykes-ds' ); ?></a></p>
        <?php endif; ?>
        <?php
    }

    public function field_footer_widget_toggle(): void {
        $val = $this->opt( 'footer_widget' ) ?? 1;
        ?>
        <label class="tykes-toggle">
            <input type="checkbox" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[footer_widget]" value="1" <?php checked( $val, 1 ); ?>>
            <span><?php esc_html_e( 'Inject Tykes Footer widget on every page automatically', 'tykes-ds' ); ?></span>
        </label>
        <?php
    }

    public function field_force_full_width(): void {
        $val = $this->opt( 'force_full_width' ) ?? 1;
        ?>
        <label class="tykes-toggle">
            <input type="checkbox" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[force_full_width]" value="1" <?php checked( $val, 1 ); ?>>
            <span><?php esc_html_e( 'Remove Elementor container max-width constraints from Tykes widgets', 'tykes-ds' ); ?></span>
        </label>
        <?php
    }

    public function field_remove_elementor_section_spacing(): void {
        $val = $this->opt( 'remove_elementor_section_spacing' ) ?? 0;
        ?>
        <label class="tykes-toggle">
            <input type="checkbox" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[remove_elementor_section_spacing]" value="1" <?php checked( $val, 1 ); ?>>
            <span><?php esc_html_e( 'Remove vertical top/bottom padding from Elementor sections and columns', 'tykes-ds' ); ?></span>
        </label>
        <p class="description"><?php esc_html_e( 'Use this when Elementor default section spacing is causing unwanted gaps above or below content.', 'tykes-ds' ); ?></p>
        <?php
    }

    public function field_disable_hello_header(): void {
        $val = $this->opt( 'disable_hello_header' ) ?? 1;
        ?>
        <label class="tykes-toggle">
            <input type="checkbox" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[disable_hello_header]" value="1" <?php checked( $val, 1 ); ?>>
            <span><?php esc_html_e( 'Hide the Hello Elementor default header', 'tykes-ds' ); ?></span>
        </label>
        <?php
    }

    public function field_disable_hello_footer(): void {
        $val = $this->opt( 'disable_hello_footer' ) ?? 1;
        ?>
        <label class="tykes-toggle">
            <input type="checkbox" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[disable_hello_footer]" value="1" <?php checked( $val, 1 ); ?>>
            <span><?php esc_html_e( 'Hide the Hello Elementor default footer', 'tykes-ds' ); ?></span>
        </label>
        <?php
    }

    public function field_color_primary(): void {
        $val = $this->opt( 'brand_primary' ) ?: '#8257bd';
        echo '<input type="color" name="' . esc_attr( self::OPTION_KEY ) . '[brand_primary]" value="' . esc_attr( $val ) . '" class="tykes-color-picker">';
        echo '<code class="tykes-hex-display">' . esc_html( $val ) . '</code>';
    }

    public function field_color_gold(): void {
        $val = $this->opt( 'brand_gold' ) ?: '#fdbc02';
        echo '<input type="color" name="' . esc_attr( self::OPTION_KEY ) . '[brand_gold]" value="' . esc_attr( $val ) . '" class="tykes-color-picker">';
        echo '<code class="tykes-hex-display">' . esc_html( $val ) . '</code>';
    }

    public function field_color_teal(): void {
        $val = $this->opt( 'brand_teal' ) ?: '#05a28d';
        echo '<input type="color" name="' . esc_attr( self::OPTION_KEY ) . '[brand_teal]" value="' . esc_attr( $val ) . '" class="tykes-color-picker">';
        echo '<code class="tykes-hex-display">' . esc_html( $val ) . '</code>';
    }

    /* ── Page render ────────────────────────────────────────────── */

    public function render_page(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have permission to view this page.', 'tykes-ds' ) );
        }
        ?>
        <div class="wrap tykes-ds-admin">
            <div class="tykes-admin-header">
                <span class="tykes-admin-logo">🎓</span>
                <div>
                    <h1><?php esc_html_e( 'Tykes Design System', 'tykes-ds' ); ?></h1>
                    <p class="tykes-admin-version">v<?php echo esc_html( TYKES_DS_VERSION ); ?> &mdash; <?php esc_html_e( 'Premium Elementor Widget Library', 'tykes-ds' ); ?></p>
                </div>
            </div>

            <?php settings_errors( 'tykes_ds_group' ); ?>

            <div class="tykes-admin-body">
                <form method="post" action="options.php">
                    <?php
                    settings_fields( 'tykes_ds_group' );
                    do_settings_sections( self::PAGE_SLUG );
                    submit_button( __( 'Save Tykes Settings', 'tykes-ds' ), 'primary tykes-save-btn' );
                    ?>
                </form>

                <aside class="tykes-admin-sidebar">
                    <div class="tykes-sidebar-card">
                        <h3>📦 <?php esc_html_e( 'Registered Widgets', 'tykes-ds' ); ?></h3>
                        <ul>
                            <li>✅ <?php esc_html_e( 'Tykes Header', 'tykes-ds' ); ?></li>
                            <li>✅ <?php esc_html_e( 'Tykes Footer', 'tykes-ds' ); ?></li>
                            <li>✅ <?php esc_html_e( 'Tykes CTA', 'tykes-ds' ); ?></li>
                            <li>✅ <?php esc_html_e( 'Tykes Difference Hero', 'tykes-ds' ); ?></li>
                            <li>✅ <?php esc_html_e( 'Tykes Difference Features', 'tykes-ds' ); ?></li>
                        </ul>
                        <p><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=elementor_library' ) ); ?>" class="button"><?php esc_html_e( 'Manage Templates →', 'tykes-ds' ); ?></a></p>
                    </div>
                    <div class="tykes-sidebar-card">
                        <h3>🔗 <?php esc_html_e( 'Quick Links', 'tykes-ds' ); ?></h3>
                        <ul>
                            <li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Appearance → Menus', 'tykes-ds' ); ?></a></li>
                            <li><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customizer', 'tykes-ds' ); ?></a></li>
                            <li><a href="https://tykes.school/" target="_blank" rel="noopener"><?php esc_html_e( 'Tykes Website', 'tykes-ds' ); ?></a></li>
                        </ul>
                    </div>
                </aside>
            </div><!-- /.tykes-admin-body -->
        </div><!-- /.wrap -->
        <?php
    }

    /* ── Helpers ────────────────────────────────────────────────── */

    private function get_elementor_templates(): array {
        $posts = get_posts( [
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ] );
        $result = [];
        foreach ( $posts as $post ) {
            $result[ $post->ID ] = $post->post_title;
        }
        return $result;
    }

    private function get_elementor_template_create_url( string $type = '' ): string {
        $url = admin_url( 'post-new.php?post_type=elementor_library' );
        if ( in_array( $type, [ 'header', 'footer' ], true ) ) {
            $url = add_query_arg( 'elementor_library_type', $type, $url );
        }
        return $url;
    }

    private function get_elementor_template_edit_url( int $template_id ): string {
        $edit_link = get_edit_post_link( $template_id, 'raw' );
        if ( $edit_link ) {
            return add_query_arg( 'action', 'elementor', $edit_link );
        }
        return admin_url( 'post.php?post=' . absint( $template_id ) . '&action=elementor' );
    }
    private function get_elementor_templates_library_url(): string {
        return admin_url( 'edit.php?post_type=elementor_library' );
    }
    /* ── Admin styles ───────────────────────────────────────────── */

    public function enqueue_admin_assets( string $hook ): void {
        if ( 'settings_page_' . self::PAGE_SLUG !== $hook ) {
            return;
        }
        wp_enqueue_style(
            'tykes-ds-admin',
            TYKES_DS_URL . 'admin/admin.css',
            [],
            TYKES_DS_VERSION
        );
        wp_enqueue_script(
            'tykes-ds-admin',
            TYKES_DS_URL . 'admin/admin.js',
            [ 'jquery' ],
            TYKES_DS_VERSION,
            true
        );
    }

    /* ✨ Automation: Convert Tykes Widget to Elementor Template ✨ */
    public function handle_elementor_conversion(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Unauthorized' );
        }
        check_admin_referer( 'tykes_convert' );

        $type = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : 'header';
        $widgetType = ( 'footer' === $type ) ? 'tykes-footer' : 'tykes-header';
        $title = ( 'footer' === $type ) ? 'Custom Tykes Footer' : 'Custom Tykes Header';

        // Create Elementor Template Post
        $post_id = wp_insert_post( [
            'post_title'  => $title,
            'post_status' => 'publish',
            'post_type'   => 'elementor_library',
        ] );

        if ( ! is_wp_error( $post_id ) ) {
            // Inject Tykes Widget JSON into Elementor Data
            $elementor_data = [
                [
                    'id'       => substr( md5( uniqid() ), 0, 7 ),
                    'elType'   => 'section',
                    'elements' => [
                        [
                            'id'       => substr( md5( uniqid() ), 0, 7 ),
                            'elType'   => 'column',
                            'elements' => [
                                [
                                    'id'         => substr( md5( uniqid() ), 0, 7 ),
                                    'elType'     => 'widget',
                                    'widgetType' => $widgetType,
                                    'settings'   => [],
                                ],
                            ],
                        ],
                    ],
                ]
            ];
            
            update_post_meta( $post_id, '_elementor_data', wp_slash( json_encode( $elementor_data ) ) );
            update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
            update_post_meta( $post_id, '_elementor_template_type', $type );

            // Update Tykes Settings to use this new template
            $options = get_option( self::OPTION_KEY, [] );
            if ( 'footer' === $type ) {
                $options['footer_type'] = 'template';
                $options['footer_template_id'] = $post_id;
            } else {
                $options['header_type'] = 'template';
                $options['header_template_id'] = $post_id;
            }
            update_option( self::OPTION_KEY, $options );

            // Redirect directly to Elementor Editor
            $edit_url = admin_url( 'post.php?post=' . $post_id . '&action=elementor' );
            wp_redirect( $edit_url );
            exit;
        }
        
        wp_redirect( admin_url( 'admin.php?page=' . self::PAGE_SLUG ) );
        exit;
    }
}
