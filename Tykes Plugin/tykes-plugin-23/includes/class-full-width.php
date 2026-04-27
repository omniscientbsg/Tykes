<?php
/**
 * Full-Width Enforcement
 *
 * Three-layer approach:
 *  1. Theme template override  – pages using Elementor get "Elementor Full Width" template
 *  2. Elementor section fix    – forces every containing section/column to stretch
 *  3. Widget container fix     – removes Elementor's default widget padding
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

use Tykes_DS\Admin\Settings_Page;

class Full_Width {

    private static $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Always register the page template option.
        add_action( 'after_setup_theme',  [ $this, 'declare_theme_support' ], 20 );

        // CSS runs regardless of toggle so the header/footer injection always looks right.
        add_action( 'wp_head',   [ $this, 'output_full_width_css' ], 1 );
        add_action( 'wp_head',   [ $this, 'remove_hello_body_padding' ], 2 );

        if ( ! Settings_Page::get( 'force_full_width', 1 ) ) {
            return;
        }

        // Set Elementor Full Width as the default template on save.
        add_action( 'elementor/page/after_save', [ $this, 'set_full_width_on_save' ], 10, 2 );
    }

    public function declare_theme_support(): void {
        add_theme_support( 'title-tag' );
    }

    public function set_full_width_on_save( int $post_id, \WP_Post $post ): void {
        if ( 'elementor_library' === $post->post_type ) { return; }
        $current = get_post_meta( $post_id, '_wp_page_template', true );
        if ( '' === $current || 'default' === $current ) {
            update_post_meta( $post_id, '_wp_page_template', 'elementor_canvas' );
        }
    }

    public function output_full_width_css(): void {
        ?>
<style id="tykes-ds-full-width">
/* ════════════════════════════════════════════════════════════════
   TYKES DS – FULL WIDTH ENFORCEMENT
   Target: remove every layer of padding that boxes in our widgets
   ════════════════════════════════════════════════════════════════ */

/* Override Hello Elementor theme CSS max-width constraints on header/footer */
body.tykes-ds-active .site-header,
body.tykes-ds-active .site-header .header-inner {
    max-width: 100% !important;
    width: 100% !important;
}
body.tykes-ds-active .site-footer,
body.tykes-ds-active .site-footer .footer-inner {
    max-width: 100% !important;
    width: 100% !important;
}

/* 1. Hello theme page wrapper */
.hello-elementor .site-main,
.hello-elementor .content-area,
.hello-elementor #content,
.hello-elementor #primary,
.hello-elementor .entry-content,
.elementor-page .page-content,
.elementor-page article.post,
.elementor-page article.page {
    padding: 0 !important;
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}

/* 2. Elementor outer section – the direct wrapper Elementor puts around widgets */
.elementor-section,
.elementor-section.elementor-section-boxed,
.elementor-section.elementor-section-full_width,
.elementor-top-section {
    width: 100% !important;
    max-width: 100% !important;
    left: 0 !important;
    right: 0 !important;
}

/* 3. Elementor container (Flexbox/Grid mode, Elementor 3.6+) */
.e-con,
.e-con-inner,
.e-con.e-con-full_width,
.e-con > .e-con-inner {
    width: 100% !important;
    max-width: 100% !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
}

/* 4. The elementor-container div inside each section */
.elementor-section > .elementor-container {
    max-width: 100% !important;
    width: 100% !important;
    padding: 0 !important;
}

/* 5. Column inside the container */
.elementor-section > .elementor-container > .elementor-column,
.elementor-section > .elementor-container > .elementor-row > .elementor-column {
    width: 100% !important;
    max-width: 100% !important;
    padding: 0 !important;
}

/* 6. The widget container Elementor wraps around every widget output */
.elementor-widget-tykes-header   > .elementor-widget-container,
.elementor-widget-tykes-footer   > .elementor-widget-container,
.elementor-widget-tykes-cta      > .elementor-widget-container,
.elementor-widget-tykes-difference-hero     > .elementor-widget-container,
.elementor-widget-tykes-difference-features > .elementor-widget-container {
    padding: 0 !important;
    margin:  0 !important;
}

/* 7. The widgets themselves */
.elementor-widget-tykes-header,
.elementor-widget-tykes-footer,
.elementor-widget-tykes-cta,
.elementor-widget-tykes-difference-hero,
.elementor-widget-tykes-difference-features {
    width: 100% !important;
    max-width: 100% !important;
    margin-left:  0 !important;
    margin-right: 0 !important;
    padding-left:  0 !important;
    padding-right: 0 !important;
}

/* 8. Injected global header/footer wrappers */
#tykes-global-header,
#tykes-global-footer,
.tykes-header-wrap,
.tykes-footer-wrap {
    width: 100% !important;
    max-width: 100% !important;
    padding: 0 !important;
    margin:  0 !important;
    display: block !important;
    position: relative;
    z-index: auto;
}

/* 9. The actual section HTML inside our widgets */
#tykes-global-header .site-header,
#siteHeader,
#tykes-global-footer .site-footer,
#tykes-global-footer .tykes-footer-native {
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
}

/* 10. Overflow safety */
body.tykes-ds-active {
    overflow-x: hidden;
}

/* 11. When using Elementor "Canvas" template – true full bleed */
body.elementor-editor-active .elementor-inner,
body.elementor-page .elementor-inner {
    width: 100% !important;
}
<?php if ( Settings_Page::get( 'remove_elementor_section_spacing', 0 ) ) : ?>
/* 12. Optional: remove Elementor section/column vertical spacing */
.elementor-section,
.elementor-top-section,
.elementor-section .elementor-container,
.elementor-section .elementor-column,
.elementor-section .elementor-column .elementor-widget-wrap,
.elementor-widget {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}
<?php endif; ?>
</style>
        <?php
    }

    public function remove_hello_body_padding(): void {
        ?>
<style id="tykes-ds-hello-body">
/* Hello Elementor adds padding-top to body for its sticky admin bar + header.
   We manage header spacing ourselves with the fixed #siteHeader approach. */
body.hello-elementor.tykes-ds-active,
body.elementor-page.tykes-ds-active {
    padding-top: 0 !important;
    margin-top:  0 !important;
}
/* Ensure content below our fixed header is offset correctly.
   This matches the header height set in the widget. */
body.tykes-ds-active .site-main,
body.tykes-ds-active #main,
body.tykes-ds-active main {
    padding-top: 0 !important;
    margin-top:  0 !important;
}
</style>
        <?php
    }
}
