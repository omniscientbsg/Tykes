<?php
/**
 * Tykes Home Features Widget
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

use \Elementor\Controls_Manager;
use \Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Widget_Tykes_Home_Features extends Widget_Base_Tykes {

    public function get_name(): string {
        return 'tykes-home-features';
    }

    public function get_title(): string {
        return esc_html__( 'Tykes Home Features', 'tykes-ds' );
    }

    public function get_icon(): string {
        return 'eicon-carousel';
    }

    protected function register_controls(): void {

        // ─── Header Section ───
        $this->start_controls_section( 'section_header', [
            'label' => esc_html__( 'Section Header', 'tykes-ds' ),
        ] );

        $this->add_control( 'title', [
            'label'   => esc_html__( 'Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'What Sets Tykes Apart',
        ] );

        $this->add_control( 'subtitle', [
            'label'   => esc_html__( 'Subtitle', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'The same institutional quality, research-backed curriculum, and professional systems that earned Kidzonia the #1 India ECE Excellence Index 2025 — now in your city.',
            'rows'    => 3,
        ] );

        $this->end_controls_section();

        // ─── Cards Section ───
        $this->start_controls_section( 'section_cards', [
            'label' => esc_html__( 'Feature Cards', 'tykes-ds' ),
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'card_theme', [
            'label'   => esc_html__( 'Card Color Theme', 'tykes-ds' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'card-1' => 'Orange',
                'card-2' => 'Coral Red',
                'card-3' => 'Yellow',
                'card-4' => 'Purple',
                'card-5' => 'Green',
                'card-6' => 'Rose Pink',
            ],
            'default' => 'card-1',
        ] );

        $repeater->add_control( 'card_title', [
            'label'   => esc_html__( 'Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Research-Based Curriculum',
        ] );

        $repeater->add_control( 'card_desc', [
            'label'   => esc_html__( 'Description', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Integrates Montessori, Reggio Emilia, Multiple Intelligence Theory, and Bloom\'s Taxonomy for holistic cognitive and social development.',
        ] );

        $repeater->add_control( 'card_link_text', [
            'label'   => esc_html__( 'Link Text', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Learn More &rarr;',
        ] );

        $repeater->add_control( 'card_link', [
            'label'   => esc_html__( 'Link URL', 'tykes-ds' ),
            'type'    => Controls_Manager::URL,
            'default' => [ 'url' => '#' ],
        ] );

        $repeater->add_control( 'card_image', [
            'label'   => esc_html__( 'Card Image', 'tykes-ds' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/04/Tykes-PlayGroup.png' ],
        ] );

        $this->add_control( 'features_list', [
            'label'       => esc_html__( 'Cards', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'card_theme' => 'card-1',
                    'card_title' => 'Research-Based Curriculum',
                ],
                [
                    'card_theme' => 'card-2',
                    'card_title' => 'Safety First',
                ],
                [
                    'card_theme' => 'card-3',
                    'card_title' => 'Structured Learning Spaces',
                ],
                [
                    'card_theme' => 'card-4',
                    'card_title' => 'Qualified & Trained Educators',
                ],
                [
                    'card_theme' => 'card-5',
                    'card_title' => 'Kidzonia-Backed Operations',
                ],
                [
                    'card_theme' => 'card-6',
                    'card_title' => 'Digital Learning Integration',
                ],
            ],
            'title_field' => '{{{ card_title }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();

        // Generate a unique ID for the slider so multiple instances on a page work independently
        $slider_id = 'whySlider_' . $this->get_id();
        ?>
        <section class="why-sec b-poppins">
            <div class="container" style="max-width: 1400px; padding: 0 20px;">
                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h2 class="why-title" style="color: var(--p); margin-bottom: 20px;"><?php echo esc_html( $settings['title'] ); ?></h2>
                <?php endif; ?>
                
                <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                    <p class="why-subtitle" style="font-family: 'Poppins', sans-serif; font-size: 1rem; color: var(--muted); max-width: 620px; margin: 0 auto 32px; line-height: 1.6;">
                        <?php echo wp_kses_post( $settings['subtitle'] ); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ( ! empty( $settings['features_list'] ) ) : ?>
                <div class="why-carousel">
                    <!-- Left Arrow -->
                    <button class="why-nav-btn why-prev-btn" onclick="document.getElementById('<?php echo esc_attr( $slider_id ); ?>').scrollBy({left: -400, behavior: 'smooth'})">&lsaquo;</button>
                    
                    <div class="why-slider" id="<?php echo esc_attr( $slider_id ); ?>">
                        <?php foreach ( $settings['features_list'] as $item ) : 
                            $url = ! empty( $item['card_link']['url'] ) ? esc_url( $item['card_link']['url'] ) : '#';
                            $theme = ! empty( $item['card_theme'] ) ? esc_attr( $item['card_theme'] ) : 'card-1';
                        ?>
                        <a href="<?php echo $url; ?>" class="why-card <?php echo $theme; ?> elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
                            <h3><?php echo esc_html( $item['card_title'] ); ?></h3>
                            <p><?php echo wp_kses_post( $item['card_desc'] ); ?></p>
                            
                            <?php if ( ! empty( $item['card_link_text'] ) ) : ?>
                                <span class="read-more"><?php echo wp_kses_post( $item['card_link_text'] ); ?></span>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $item['card_image']['url'] ) ) : ?>
                            <div class="why-card-img-wrap">
                                <img src="<?php echo esc_url( $item['card_image']['url'] ); ?>" alt="<?php echo esc_attr( $item['card_title'] ); ?>" class="why-card-img">
                            </div>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- Right Arrow -->
                    <button class="why-nav-btn why-next-btn" onclick="document.getElementById('<?php echo esc_attr( $slider_id ); ?>').scrollBy({left: 400, behavior: 'smooth'})">&rsaquo;</button>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}
