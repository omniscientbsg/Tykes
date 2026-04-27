<?php
/**
 * Widget Registry
 *
 * Central registry for all Tykes Design System widgets.
 * Implements the naming convention:
 *   - Global:        "Tykes {Role}"           → Tykes Header, Tykes Footer, Tykes CTA
 *   - Page-specific: "Tykes {Page} {Section}" → Tykes Difference Hero, Tykes Difference Features
 *
 * To add a new widget, just add its definition to $widget_map below —
 * no other file needs to change.
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

defined( 'ABSPATH' ) || exit;

class Widget_Registry {

    private static $instance = null;

    /**
     * Central map of all widgets.
     *
     * Key   = internal slug (used for get_name(), CSS class, file name)
     * Value = [
     *   'class' => Fully-qualified class name,
     *   'file'  => PHP file inside widgets/ directory,
     *   'title' => Human-readable Elementor panel title (the naming system),
     *   'icon'  => Elementor icon class,
     * ]
     */
    private $widget_map = [];

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->build_widget_map();
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
        add_action( 'elementor/widgets/register',               [ $this, 'register_all' ] );
    }

    /* ── Widget map ─────────────────────────────────────────────── */

    private function build_widget_map(): void {
        /**
         * NAMING CONVENTION IMPLEMENTATION
         * ─────────────────────────────────
         * Global widgets  → "Tykes {Role}"
         * Page widgets    → "Tykes {Page Name} {Section Name}"
         *
         * Add new widgets here; everything else is automatic.
         */
        $this->widget_map = [

            /* ── Global: Header ── */
            'tykes-header' => [
                'class' => 'Tykes_DS\Widget_Tykes_Header',
                'file'  => 'class-widget-tykes-header.php',
                'title' => 'Tykes Header',
                'icon'  => 'eicon-navigation-horizontal',
            ],

            /* ── Global: Footer ── */
            'tykes-footer' => [
                'class' => 'Tykes_DS\Widget_Tykes_Footer',
                'file'  => 'class-widget-tykes-footer.php',
                'title' => 'Tykes Footer',
                'icon'  => 'eicon-footer',
            ],

            /* ── Global: CTA ── */
            'tykes-cta' => [
                'class' => 'Tykes_DS\Widget_Tykes_CTA',
                'file'  => 'class-widget-tykes-cta.php',
                'title' => 'Tykes CTA',
                'icon'  => 'eicon-call-to-action',
            ],

            /* ── Difference page: Hero ── */
            'tykes-difference-hero' => [
                'class' => 'Tykes_DS\Widget_Tykes_Difference_Hero',
                'file'  => 'class-widget-tykes-difference-hero.php',
                'title' => 'Tykes Difference Hero',
                'icon'  => 'eicon-banner',
            ],

            /* ── Difference page: Features grid ── */
            'tykes-difference-features' => [
                'class' => 'Tykes_DS\Widget_Tykes_Difference_Features',
                'file'  => 'class-widget-tykes-difference-features.php',
                'title' => 'Tykes Difference Features',
                'icon'  => 'eicon-star',
            ],

            /* 📇 Contact page: Hero 📇 */
            'tykes-contact-hero' => [
                'class' => 'Tykes_DS\Widget_Tykes_Contact_Hero',
                'file'  => 'class-widget-tykes-contact-hero.php',
                'title' => 'Tykes Contact Hero',
                'icon'  => 'eicon-envelope',
            ],

            /* 📇 Contact page: Forms 📇 */
            'tykes-contact-forms' => [
                'class' => 'Tykes_DS\Widget_Tykes_Contact_Forms',
                'file'  => 'class-widget-tykes-contact-forms.php',
                'title' => 'Tykes Contact Forms',
                'icon'  => 'eicon-form-horizontal',
            ],

            /* 📇 Contact page: Office 📇 */
            'tykes-contact-office' => [
                'class' => 'Tykes_DS\Widget_Tykes_Contact_Office',
                'file'  => 'class-widget-tykes-contact-office.php',
                'title' => 'Tykes Contact Office',
                'icon'  => 'eicon-map-pin',
            ],

            /* 📇 Contact page: Social & Hours 📇 */
            'tykes-contact-social' => [
                'class' => 'Tykes_DS\Widget_Tykes_Contact_Social',
                'file'  => 'class-widget-tykes-contact-social.php',
                'title' => 'Tykes Contact Social',
                'icon'  => 'eicon-share',
            ],

            /* 🏠 Home page: Hero 🏠 */
            'tykes-home-hero' => [
                'class' => 'Tykes_DS\Widget_Tykes_Home_Hero',
                'file'  => 'class-widget-tykes-home-hero.php',
                'title' => 'Tykes Home Hero',
                'icon'  => 'eicon-image-box',
            ],

            /* 🏠 Home page: Stats 🏠 */
            'tykes-home-stats' => [
                'class' => 'Tykes_DS\Widget_Tykes_Home_Stats',
                'file'  => 'class-widget-tykes-home-stats.php',
                'title' => 'Tykes Home Stats',
                'icon'  => 'eicon-counter',
            ],

            /* 🏠 Home page: Legacy 🏠 */
            'tykes-home-legacy' => [
                'class' => 'Tykes_DS\Widget_Tykes_Home_Legacy',
                'file'  => 'class-widget-tykes-home-legacy.php',
                'title' => 'Tykes Home Legacy',
                'icon'  => 'eicon-image-before-after',
            ],

            /* 🏠 Home page: Features 🏠 */
            'tykes-home-features' => [
                'class' => 'Tykes_DS\Widget_Tykes_Home_Features',
                'file'  => 'class-widget-tykes-home-features.php',
                'title' => 'Tykes Home Features',
                'icon'  => 'eicon-carousel',
            ],

            /* 🏠 Home page: Programmes 🏠 */
            'tykes-home-programmes' => [
                'class' => 'Tykes_DS\Widget_Tykes_Home_Programmes',
                'file'  => 'class-widget-tykes-home-programmes.php',
                'title' => 'Tykes Home Programmes',
                'icon'  => 'eicon-gallery-grid',
            ],

            /* 🏠 Home page: Testimonials 🏠 */
            'tykes-home-testimonials' => [
                'class' => 'Tykes_DS\Widget_Tykes_Home_Testimonials',
                'file'  => 'class-widget-tykes-home-testimonials.php',
                'title' => 'Tykes Home Testimonials',
                'icon'  => 'eicon-testimonial-carousel',
            ],

            /* 🏠 Home page: Awards Ticker 🏠 */
            'tykes-home-awards' => [
                'class' => 'Tykes_DS\Widget_Tykes_Home_Awards',
                'file'  => 'class-widget-tykes-home-awards.php',
                'title' => 'Tykes Home Awards',
                'icon'  => 'eicon-marquee',
            ],

            /* 🏠 Home page: Achievements 🏠 */
            'tykes-home-achievements' => [
                'class' => 'Tykes_DS\Widget_Tykes_Home_Achievements',
                'file'  => 'class-widget-tykes-home-achievements.php',
                'title' => 'Tykes Home Achievements',
                'icon'  => 'eicon-gallery-masonry',
            ],

            /*
             * ── HOW TO ADD MORE WIDGETS ──────────────────────────
             * Follow the naming pattern and add an entry here:
             *
             * 'tykes-curriculum-hero' => [
             *     'class' => 'Tykes_DS\Widget_Tykes_Curriculum_Hero',
             *     'file'  => 'class-widget-tykes-curriculum-hero.php',
             *     'title' => __( 'Tykes Curriculum Hero', 'tykes-ds' ),
             *     'icon'  => 'eicon-banner',
             * ],
             */
        ];

        /**
         * Allow third-party code (or child plugins) to extend the map.
         *
         * @param array $widget_map The full widget map.
         */
        $this->widget_map = apply_filters( 'tykes_ds_widget_map', $this->widget_map );
    }

    /* ── Category registration ──────────────────────────────────── */

    public function register_category( \Elementor\Elements_Manager $manager ): void {
        $manager->add_category( 'tykes-design-system', [
            'title' => esc_html__( '🎓 Tykes Design System', 'tykes-ds' ),
            'icon'  => 'fa fa-star',
        ] );
    }

    /* ── Widget registration ────────────────────────────────────── */

    public function register_all( \Elementor\Widgets_Manager $manager ): void {
        // Ensure walker is available before widgets try to use it.
        if ( ! class_exists( 'Tykes_DS\Nav_Walker' ) ) {
            require_once TYKES_DS_INCLUDES . 'class-nav-walker.php';
        }
        if ( ! class_exists( 'Tykes_DS\Nav_Walker_Mobile' ) ) {
            require_once TYKES_DS_INCLUDES . 'class-nav-walker-mobile.php';
        }

        foreach ( $this->widget_map as $slug => $definition ) {
            $file = TYKES_DS_WIDGETS . $definition['file'];

            if ( ! file_exists( $file ) ) {
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions
                error_log( sprintf( '[Tykes DS] Widget file not found: %s', $file ) );
                continue;
            }

            require_once $file;

            if ( ! class_exists( $definition['class'] ) ) {
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions
                error_log( sprintf( '[Tykes DS] Widget class not found: %s', $definition['class'] ) );
                continue;
            }

            $manager->register( new $definition['class']() );
        }
    }

    /* ── Public accessors ───────────────────────────────────────── */

    /** Return the full widget map (for admin UI, documentation, etc.) */
    public function get_widget_map(): array {
        return $this->widget_map;
    }

    /** Return the human title for a given slug, translated at point of use. */
    public function get_title( string $slug ): string {
        $raw = $this->widget_map[ $slug ]['title'] ?? $slug;
        return __( $raw, 'tykes-ds' ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
    }
}