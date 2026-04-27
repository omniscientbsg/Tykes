<?php
/**
 * Global Header & Footer Override
 *
 * Disables Hello Elementor's native header/footer and injects
 * the Tykes header/footer via the chosen source (widget, Elementor
 * template, or WordPress nav menu).
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

use Tykes_DS\Admin\Settings_Page;

class Header_Footer {

    private static $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // ── Hello Elementor suppression ──────────────────────────
        add_action( 'init', [ $this, 'suppress_hello_theme' ] );

        // ── Inject Tykes header/footer into <body> ───────────────
        add_action( 'wp_body_open',  [ $this, 'render_header' ], 5  );
        add_action( 'wp_footer',     [ $this, 'render_footer' ], 5  );

        // ── Elementor canvas: add body class for full-page pages ─
        add_filter( 'body_class',    [ $this, 'add_body_classes' ] );

        // ── Register Elementor theme locations for header/footer support ─
        add_action( 'elementor/theme/register_locations', [ $this, 'register_elementor_locations' ] );

        // ── Output dynamic brand CSS variables from settings ─────
        add_action( 'wp_head',       [ $this, 'output_brand_css_vars' ], 20 );
    }

    /* ── Hello Elementor suppression ───────────────────────────── */

    public function suppress_hello_theme(): void {
        $is_hello = (
            'hello-elementor' === get_template() ||
            'hello-elementor' === get_stylesheet() ||
            false !== stripos( wp_get_theme()->get( 'Name' ), 'hello' )
        );

        // ── Always apply CSS suppression regardless of theme detection ──
        // (handles child themes of Hello and edge cases)
        add_action( 'wp_head', [ $this, 'hide_hello_via_css' ], 5 );

        if ( ! $is_hello ) {
            return;
        }

        // ── Remove Hello's header via its own filter hooks ──
        if ( Settings_Page::get( 'disable_hello_header', 1 ) ) {
            // hello-elementor/header.php calls get_template_part() controlled by this filter.
            add_filter( 'hello_elementor_header_template', '__return_false' );

            // hello_elementor theme uses these actions to print header HTML.
            // Remove them all at priority 10 (default).
            add_action( 'init', function () {
                remove_action( 'hello_elementor_header', 'hello_elementor_header_template' );
            }, 20 );
        }

        // ── Remove Hello's footer via its own filter hooks ──
        if ( Settings_Page::get( 'disable_hello_footer', 1 ) ) {
            add_filter( 'hello_elementor_footer_template', '__return_false' );
            add_action( 'init', function () {
                remove_action( 'hello_elementor_footer', 'hello_elementor_footer_template' );
            }, 20 );
        }

        // ── Remove the entire header.php / footer.php template parts ──
        // Hello uses get_header() / get_footer() which load template files.
        // We override the template file path to a blank file.
        add_filter( 'hello_elementor_page_title', '__return_false' );
    }

    public function hide_hello_via_css(): void {
        $hide_header = Settings_Page::get( 'disable_hello_header', 1 );
        $hide_footer = Settings_Page::get( 'disable_hello_footer', 1 );

        if ( ! $hide_header && ! $hide_footer ) {
            return;
        }

        echo "<style id=\"tykes-ds-hello-override\">\n";

        if ( $hide_header ) {
            // Target every possible Hello header selector.
            // Hello Elementor renders: <header id="site-header"> or <header class="site-header">
            // depending on version. We target both.
            echo "
/* ── Hide Hello Elementor native header ── */
#site-header,
header#site-header,
.hello-elementor header.site-header,
header.site-header:not(#siteHeader),
body > header:not(#siteHeader),
body > div > header:not(#siteHeader) {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
    overflow: hidden !important;
}
/* Ensure OUR header is always visible */
#siteHeader,
.tykes-header-wrap,
#tykes-global-header {
    display: block !important;
    visibility: visible !important;
    height: auto !important;
    overflow: visible !important;
}
";
        }

        if ( $hide_footer ) {
            echo "
/* ── Hide Hello Elementor native footer ── */
#site-footer,
footer#site-footer,
.hello-elementor footer.site-footer,
footer.site-footer:not(.tykes-footer-native),
body > footer:not(.tykes-footer-native) {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
    overflow: hidden !important;
}
/* Ensure OUR footer is always visible */
.tykes-footer-native,
.tykes-footer-wrap,
#tykes-global-footer {
    display: block !important;
    visibility: visible !important;
    height: auto !important;
    overflow: visible !important;
}
";
        }

        echo "</style>\n";
    }

    /* ── Header render ──────────────────────────────────────────── */

    public function render_header(): void {
        // Skip on Elementor canvas (full-screen editing).
        if ( $this->is_elementor_canvas() ) {
            return;
        }

        $source = Settings_Page::get( 'header_type', 'widget' );

        echo '<div class="tykes-header-wrap" id="tykes-global-header">';

        switch ( $source ) {
            case 'template':
                $template_id = (int) Settings_Page::get( 'header_template_id', 0 );
                if ( $template_id ) {
                    echo $this->render_elementor_template( $template_id ); // phpcs:ignore WordPress.Security.EscapeOutput
                }
                break;

            case 'menu':
                $menu_id = (int) Settings_Page::get( 'header_menu_id', 0 );
                $this->render_menu_header( $menu_id );
                break;

            case 'widget':
            default:
                $this->render_tykes_header_widget();
                break;
        }

        echo '</div><!-- #tykes-global-header -->';
    }

    /* ── Footer render ──────────────────────────────────────────── */

    public function render_footer(): void {
        if ( $this->is_elementor_canvas() ) {
            return;
        }

        $source = Settings_Page::get( 'footer_type', 'widget' );

        echo '<div class="tykes-footer-wrap" id="tykes-global-footer">';

        switch ( $source ) {
            case 'template':
                $template_id = (int) Settings_Page::get( 'footer_template_id', 0 );
                if ( $template_id ) {
                    echo $this->render_elementor_template( $template_id ); // phpcs:ignore WordPress.Security.EscapeOutput
                }
                break;

            case 'widget':
            default:
                $this->render_tykes_footer_widget();
                break;
        }

        echo '</div><!-- #tykes-global-footer -->';
    }

    /* ── Source renderers ───────────────────────────────────────── */

    /**
     * Render an Elementor saved template by post ID.
     * Uses Elementor's own frontend renderer so all widgets fire correctly.
     */
    private function render_elementor_template( int $post_id ): string {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return '';
        }
        $plugin = \Elementor\Plugin::instance();
        if ( ! isset( $plugin->frontend ) || ! method_exists( $plugin->frontend, 'get_builder_content_for_display' ) ) {
            return '';
        }
        return $plugin->frontend->get_builder_content_for_display( $post_id, true );
    }

    /**
     * Render a WordPress nav menu as a minimal accessible header.
     */
    private function render_menu_header( int $menu_id ): void {
        ?>
        <header class="site-header tykes-menu-header" id="siteHeader">
            <div class="header-inner">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-logo">
                    <img src="<?php echo esc_url( get_site_icon_url( 200, TYKES_DS_URL . 'assets/img/logo-placeholder.png' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>">
                </a>
                <?php
                wp_nav_menu( [
                    'menu'            => $menu_id ?: 0,
                    'container'       => 'nav',
                    'container_class' => 'main-nav b-poppins',
                    'menu_class'      => 'tykes-nav-menu',
                    'fallback_cb'     => false,
                ] );
                ?>
                <div class="header-cta">
                    <button class="btn-enroll" onclick="tykesOpenPopup()">
                        <?php esc_html_e( 'Book a Visit →', 'tykes-ds' ); ?>
                    </button>
                </div>
            </div>
        </header>
        <?php
    }

    /**
     * Directly output the Tykes Header widget HTML (no Elementor overhead).
     * This mirrors the widget render() output so it works on non-Elementor pages too.
     */
    private function render_tykes_header_widget(): void {
        // Instantiate and call render directly so the full header HTML appears
        // without needing to place a widget in every page's Elementor canvas.
        if ( class_exists( '\Tykes_DS\Widget_Tykes_Header' ) ) {
            $widget = new \Tykes_DS\Widget_Tykes_Header();
            if ( method_exists( $widget, 'render_standalone' ) ) {
                $widget->render_standalone();
                return;
            }
        }
        // Fallback: minimal inline header.
        $this->render_minimal_fallback_header();
    }

    private function render_tykes_footer_widget(): void {
        if ( class_exists( '\Tykes_DS\Widget_Tykes_Footer' ) ) {
            $widget = new \Tykes_DS\Widget_Tykes_Footer();
            if ( method_exists( $widget, 'render_standalone' ) ) {
                $widget->render_standalone();
                return;
            }
        }
        $this->render_minimal_fallback_footer();
    }

    private function render_minimal_fallback_header(): void {
        ?>
        <header class="site-header" id="siteHeader">
            <div class="header-inner">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-logo">
                    <span style="font-family:'Fredoka',sans-serif;font-size:1.5rem;color:#8257bd;font-weight:700;">
                        <?php bloginfo( 'name' ); ?>
                    </span>
                </a>
                <div class="header-cta">
                    <button class="btn-enroll" onclick="tykesOpenPopup()">
                        <?php esc_html_e( 'Book a Visit →', 'tykes-ds' ); ?>
                    </button>
                </div>
            </div>
        </header>
        <?php
    }

    private function render_minimal_fallback_footer(): void {
        ?>
        <footer class="site-footer" style="text-align:center;padding:40px 20px;">
            <p style="color:rgba(255,255,255,.6);font-family:'Poppins',sans-serif;font-size:.9rem;">
                &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> 
                <span style="color:#fdbc02;"><?php bloginfo( 'name' ); ?></span>. 
                <?php esc_html_e( 'All rights reserved.', 'tykes-ds' ); ?>
            </p>
        </footer>
        <?php
    }

    /* ── Brand CSS variables (from settings) ────────────────────── */

    public function output_brand_css_vars(): void {
        $primary = Settings_Page::get( 'brand_primary', '#8257bd' );
        $gold    = Settings_Page::get( 'brand_gold',    '#fdbc02' );
        $teal    = Settings_Page::get( 'brand_teal',    '#05a28d' );

        // Only output overrides if they differ from plugin defaults.
        $defaults = [ '#8257bd', '#fdbc02', '#05a28d' ];
        if ( [ $primary, $gold, $teal ] === $defaults ) {
            return;
        }

        printf(
            "<style id=\"tykes-ds-brand-vars\">\n:root {\n  --p: %s;\n  --pd: %s;\n  --gold: %s;\n  --goldd: %s;\n  --teal: %s;\n}\n</style>\n",
            esc_attr( $primary ),
            esc_attr( $this->darken_hex( $primary, 15 ) ),
            esc_attr( $gold ),
            esc_attr( $this->darken_hex( $gold, 10 ) ),
            esc_attr( $teal )
        );
    }

    /* ── Body classes ───────────────────────────────────────────── */

    public function add_body_classes( array $classes ): array {
        $classes[] = 'tykes-ds-active';

        if ( Settings_Page::get( 'force_full_width', 1 ) ) {
            $classes[] = 'tykes-full-width';
        }

        if ( Settings_Page::get( 'disable_hello_header', 1 ) ) {
            $classes[] = 'tykes-no-hello-header';
        }

        return $classes;
    }

    public function register_elementor_locations( $elementor_theme_manager ): void {
        if ( ! is_object( $elementor_theme_manager ) ) {
            return;
        }

        // Register all core Elementor theme locations (header, footer, single, archive, etc.)
        if ( method_exists( $elementor_theme_manager, 'register_all_core_location' ) ) {
            $elementor_theme_manager->register_all_core_location();
        }

        // Override header location to use the selected Elementor template if set
        $header_type = Settings_Page::get( 'header_type', 'widget' );
        if ( 'template' === $header_type ) {
            $header_template_id = (int) Settings_Page::get( 'header_template_id', 0 );
            if ( $header_template_id ) {
                if ( method_exists( $elementor_theme_manager, 'set_location_template' ) ) {
                    $elementor_theme_manager->set_location_template( 'header', $header_template_id );
                }
            }
        }

        // Override footer location to use the selected Elementor template if set
        $footer_type = Settings_Page::get( 'footer_type', 'widget' );
        if ( 'template' === $footer_type ) {
            $footer_template_id = (int) Settings_Page::get( 'footer_template_id', 0 );
            if ( $footer_template_id ) {
                if ( method_exists( $elementor_theme_manager, 'set_location_template' ) ) {
                    $elementor_theme_manager->set_location_template( 'footer', $footer_template_id );
                }
            }
        }
    }

    /* ── Utilities ──────────────────────────────────────────────── */

    private function is_elementor_canvas(): bool {
        return isset( $_GET['elementor-preview'] ) // phpcs:ignore WordPress.Security.NonceVerification
            || ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) );
    }

    /**
     * Naively darken a hex colour by a given percentage (0-100).
     * Good enough for generating a dark variant from the primary colour.
     */
    private function darken_hex( string $hex, int $percent ): string {
        $hex = ltrim( $hex, '#' );
        if ( strlen( $hex ) === 3 ) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        $r = max( 0, (int) ( hexdec( substr( $hex, 0, 2 ) ) * ( 1 - $percent / 100 ) ) );
        $g = max( 0, (int) ( hexdec( substr( $hex, 2, 2 ) ) * ( 1 - $percent / 100 ) ) );
        $b = max( 0, (int) ( hexdec( substr( $hex, 4, 2 ) ) * ( 1 - $percent / 100 ) ) );
        return sprintf( '#%02x%02x%02x', $r, $g, $b );
    }
}
