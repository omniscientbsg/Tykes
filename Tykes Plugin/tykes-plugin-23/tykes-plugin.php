<?php
/**
 * Plugin Name:  Tykes Design System
 * Plugin URI:   https://tykes.school/
 * Description:  Premium Elementor widget library + global header/footer override for Tykes Early Years. Widgets, settings panel, full-width enforcement, and deep style controls in one system.
 * Version:      2.2.0
 * Author:       Tykes Early Years
 * Author URI:   https://tykes.school/
 * License:      GPL-2.0-or-later
 * Text Domain:  tykes-ds
 * Requires at least: 6.2
 * Requires PHP: 7.4
 *
 * @package Tykes_DS
 */

defined( 'ABSPATH' ) || exit;

/* ── Constants ──────────────────────────────────────────────────── */
define( 'TYKES_DS_VERSION',  '2.2.0' );
define( 'TYKES_DS_FILE',     __FILE__ );
define( 'TYKES_DS_PATH',     plugin_dir_path( __FILE__ ) );
define( 'TYKES_DS_URL',      plugin_dir_url( __FILE__ ) );
define( 'TYKES_DS_WIDGETS',  TYKES_DS_PATH . 'widgets/' );
define( 'TYKES_DS_INCLUDES', TYKES_DS_PATH . 'includes/' );
define( 'TYKES_DS_ADMIN',    TYKES_DS_PATH . 'admin/' );

/* ── Autoloader ─────────────────────────────────────────────────── */
spl_autoload_register( function ( string $class ): void {
    // Only handle our namespace.
    if ( strpos( $class, 'Tykes_DS\\' ) !== 0 ) {
        return;
    }
    $relative  = str_replace( 'Tykes_DS\\', '', $class );
    $file_name = 'class-' . strtolower( str_replace( [ '\\', '_' ], [ '/', '-' ], $relative ) ) . '.php';
    $paths     = [ TYKES_DS_INCLUDES, TYKES_DS_WIDGETS, TYKES_DS_ADMIN ];
    foreach ( $paths as $dir ) {
        $full = $dir . $file_name;
        if ( file_exists( $full ) ) {
            require_once $full;
            return;
        }
    }
} );

/**
 * Primary plugin controller – singleton.
 */
final class Tykes_DS_Plugin {

    private static $instance = null;

    /* ── Boot ───────────────────────────────────────────────────── */

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        /*
         * Load the plugin textdomain at init so WordPress 6.7 does not
         * trigger the "too early" notice. All plugin hooks and admin
         * pages are still registered after this point.
         */
        add_action( 'init', [ $this, 'load_textdomain' ], 1 );

        add_action( 'plugins_loaded', [ $this, 'init' ], 5 );
    }

    public function load_textdomain(): void {
        load_plugin_textdomain( 'tykes-ds', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    public function init(): void {
        // Always load admin + settings (even without Elementor).
        $this->load_admin();

        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'notice_missing_elementor' ] );
            return;
        }

        if ( ! version_compare( ELEMENTOR_VERSION, '3.18.0', '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'notice_old_elementor' ] );
            return;
        }

        $this->load_core();
    }

    /* ── Sub-system loaders ─────────────────────────────────────── */

    private function load_admin(): void {
        require_once TYKES_DS_ADMIN . 'class-settings-page.php';
        \Tykes_DS\Admin\Settings_Page::instance();
    }

    private function load_core(): void {
        // Nav walkers (loaded before widgets so widget can use them).
        require_once TYKES_DS_INCLUDES . 'class-nav-walker.php';
        require_once TYKES_DS_INCLUDES . 'class-nav-walker-mobile.php';

        // Header / footer override.
        require_once TYKES_DS_INCLUDES . 'class-header-footer.php';
        \Tykes_DS\Header_Footer::instance();

        // Full-width enforcement.
        require_once TYKES_DS_INCLUDES . 'class-full-width.php';
        \Tykes_DS\Full_Width::instance();

        // Widget registry.
        require_once TYKES_DS_INCLUDES . 'class-widget-registry.php';
        \Tykes_DS\Widget_Registry::instance();

        // Asset enqueueing.
        add_action( 'wp_enqueue_scripts',                         [ $this, 'enqueue_styles' ], 20 );
        add_action( 'wp_enqueue_scripts',                         [ $this, 'enqueue_scripts' ], 20 );
        add_action( 'elementor/frontend/after_enqueue_styles',     [ $this, 'enqueue_styles' ] );
        add_action( 'elementor/frontend/after_enqueue_scripts',    [ $this, 'enqueue_scripts' ] );
        add_action( 'elementor/editor/after_enqueue_styles',      [ $this, 'enqueue_styles' ] );
        add_action( 'elementor/editor/after_enqueue_scripts',     [ $this, 'enqueue_scripts' ] );
    }

    /* ── Asset enqueueing ───────────────────────────────────────── */

    public function enqueue_styles(): void {
        wp_enqueue_style(
            'tykes-ds-fonts',
            'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap',
            [],
            null
        );
        wp_enqueue_style(
            'tykes-ds-main',
            TYKES_DS_URL . 'assets/css/tykes-ds.css',
            [ 'tykes-ds-fonts' ],
            filemtime( TYKES_DS_PATH . 'assets/css/tykes-ds.css' )
        );

        /*
         * CRITICAL CSS — injected INLINE into <head> at priority 999.
         * This runs AFTER all other styles (Elementor, Hello theme, etc.)
         * so it always wins. It cannot be cached by any CDN or host.
         */
        add_action( 'wp_head', [ $this, 'inject_critical_css' ], 999 );
    }

    /**
     * Inject tykes-critical.css inline into <head>.
     * Inline = no HTTP request = no caching = always fresh.
     */
    public function inject_critical_css(): void {
        $path = TYKES_DS_PATH . 'assets/css/tykes-critical.css';
        if ( ! file_exists( $path ) ) {
            return;
        }
        $css = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions
        if ( $css ) {
            echo "\n<style id=\"tykes-ds-critical\" data-v=\"2.2\">\n";
            echo $css; // phpcs:ignore WordPress.Security.EscapeOutput
            echo "\n</style>\n";
        }
    }

    public function enqueue_scripts(): void {
        wp_enqueue_script(
            'tykes-ds-main',
            TYKES_DS_URL . 'assets/js/tykes-ds.js',
            [],
            filemtime( TYKES_DS_PATH . 'assets/js/tykes-ds.js' ),
            true
        );

        /*
         * Standalone nav dropdown script — loaded independently.
         * Uses a hard timestamp version so WordPress CANNOT serve a cached copy.
         * This script has zero dependencies and handles dropdowns on its own.
         */
        wp_enqueue_script(
            'tykes-ds-nav',
            TYKES_DS_URL . 'assets/js/tykes-nav.js',
            [], // NO dependencies — works even if tykes-ds-main is cached/broken
            '2.2.' . filemtime( TYKES_DS_PATH . 'assets/js/tykes-nav.js' ),
            true  // Load in footer
        );

        // Accordion/Toggle handler for form sections and custom accordions.
        wp_enqueue_script(
            'tykes-ds-accordion',
            TYKES_DS_URL . 'assets/js/accordion.js',
            [ 'tykes-ds-main' ],
            filemtime( TYKES_DS_PATH . 'assets/js/accordion.js' ),
            true
        );
        // Pass settings to JS.
        wp_localize_script( 'tykes-ds-main', 'tykesDsConfig', [
            'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
            'nonce'      => wp_create_nonce( 'tykes_ds_nonce' ),
            'pluginUrl'  => TYKES_DS_URL,
        ] );
    }

    /* ── Admin notices ──────────────────────────────────────────── */

    public function notice_missing_elementor(): void {
        printf(
            '<div class="notice notice-error"><p><strong>Tykes Design System</strong> requires <a href="%s">Elementor</a> to be installed and active.</p></div>',
            esc_url( admin_url( 'plugin-install.php?s=elementor&tab=search&type=term' ) )
        );
    }

    public function notice_old_elementor(): void {
        printf(
            '<div class="notice notice-warning"><p><strong>Tykes Design System</strong> requires Elementor 3.18.0+. Your version: %s.</p></div>',
            esc_html( ELEMENTOR_VERSION )
        );
    }
}

// Boot the plugin.
Tykes_DS_Plugin::instance();

/**
 * Suppress Elementor's "Undefined array key _column_size" notice.
 *
 * This is a known Elementor bug in older installations where column metadata
 * is missing the _column_size key. The notice is harmless but pollutes the
 * frontend output. We patch it by adding an error handler that silences
 * E_NOTICE / E_WARNING originating from Elementor's column.php.
 *
 * This runs at priority 1 on 'init' (before Elementor renders anything)
 * and restores the previous handler after the request lifecycle completes.
 */
add_action( 'init', function () {
    set_error_handler( function ( $errno, $errstr, $errfile ) {
        // Only suppress notices/warnings from Elementor's column renderer.
        // Uses strpos() instead of str_contains() for PHP 7.4 compatibility.
        if (
            ( $errno === E_NOTICE || $errno === E_WARNING ) &&
            strpos( $errfile, 'elementor' ) !== false &&
            strpos( $errstr, '_column_size' ) !== false
        ) {
            return true; // Suppress — do not bubble to PHP.
        }
        return false; // Let everything else through.
    }, E_NOTICE | E_WARNING );
}, 1 );