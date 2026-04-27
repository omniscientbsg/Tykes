<?php
/**
 * Tykes Home Testimonials Widget
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

use \Elementor\Controls_Manager;
use \Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Widget_Tykes_Home_Testimonials extends Widget_Base_Tykes {

    public function get_name(): string {
        return 'tykes-home-testimonials';
    }

    public function get_title(): string {
        return esc_html__( 'Tykes Home Testimonials', 'tykes-ds' );
    }

    public function get_icon(): string {
        return 'eicon-testimonial';
    }

    protected function register_controls(): void {

        // ─── Header Section ───
        $this->start_controls_section( 'section_header', [
            'label' => esc_html__( 'Section Header', 'tykes-ds' ),
        ] );

        $this->add_control( 'eyebrow', [
            'label'   => esc_html__( 'Eyebrow', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Parent Voices',
        ] );

        $this->add_control( 'title', [
            'label'   => esc_html__( 'Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'What Parents Say About Us',
        ] );

        $this->end_controls_section();

        // ─── Testimonials Section ───
        $this->start_controls_section( 'section_testimonials', [
            'label' => esc_html__( 'Testimonials', 'tykes-ds' ),
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'stars', [
            'label'   => esc_html__( 'Stars', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '★★★★★',
        ] );

        $repeater->add_control( 'quote', [
            'label'   => esc_html__( 'Quote', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => '"The transformation in our child\'s confidence since joining Tykes is remarkable. The blend of play and structured learning is exactly what we were looking for."',
            'rows'    => 4,
        ] );

        $repeater->add_control( 'avatar', [
            'label'   => esc_html__( 'Avatar Image', 'tykes-ds' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80' ],
        ] );

        $repeater->add_control( 'author_name', [
            'label'   => esc_html__( 'Author Name', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Sarah M.',
        ] );

        $repeater->add_control( 'author_role', [
            'label'   => esc_html__( 'Author Role', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Mother of Aarav (Nursery)',
        ] );

        $this->add_control( 'testimonials_list', [
            'label'       => esc_html__( 'Testimonials', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'stars' => '★★★★★',
                    'quote' => '"The transformation in our child\'s confidence since joining Tykes is remarkable. The blend of play and structured learning is exactly what we were looking for."',
                    'author_name' => 'Sarah M.',
                    'author_role' => 'Mother of Aarav (Nursery)',
                    'avatar' => [ 'url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80' ],
                ],
                [
                    'stars' => '★★★★★',
                    'quote' => '"We love the transparent communication. Getting daily updates on the app gives us so much peace of mind while we are at work."',
                    'author_name' => 'Rahul K.',
                    'author_role' => 'Father of Riya (DayCare)',
                    'avatar' => [ 'url' => 'https://images.unsplash.com/photo-1599566150163-29194dcaad36?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80' ],
                ],
                [
                    'stars' => '★★★★★',
                    'quote' => '"The teachers are extremely caring and well-trained. By Senior KG, my son is already reading and taking initiative at home. Highly recommend Tykes!"',
                    'author_name' => 'Priya V.',
                    'author_role' => 'Mother of Vihaan (Senior KG)',
                    'avatar' => [ 'url' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80' ],
                ],
            ],
            'title_field' => '{{{ author_name }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="testi-sec b-poppins">
            <div class="container">
                <div class="testi-header">
                    <?php if ( ! empty( $settings['eyebrow'] ) ) : ?>
                        <div class="testi-eyebrow b-poppins"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $settings['title'] ) ) : ?>
                        <h2 class="sb-h2 h-fredoka" style="font-size:clamp(2.4rem, 4vw, 3rem);"><?php echo wp_kses_post( $settings['title'] ); ?></h2>
                    <?php endif; ?>
                </div>
                
                <?php if ( ! empty( $settings['testimonials_list'] ) ) : ?>
                <div class="t-grid">
                    <?php foreach ( $settings['testimonials_list'] as $item ) : ?>
                    <div class="t-card elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
                        <div class="t-stars"><?php echo esc_html( $item['stars'] ); ?></div>
                        <p class="t-body"><?php echo wp_kses_post( $item['quote'] ); ?></p>
                        <div class="t-profile">
                            <?php if ( ! empty( $item['avatar']['url'] ) ) : ?>
                                <img src="<?php echo esc_url( $item['avatar']['url'] ); ?>" alt="<?php echo esc_attr( $item['author_name'] ); ?>" class="t-avatar">
                            <?php endif; ?>
                            <div class="t-author-info">
                                <span class="t-author"><?php echo esc_html( $item['author_name'] ); ?></span>
                                <span class="t-role"><?php echo esc_html( $item['author_role'] ); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}
