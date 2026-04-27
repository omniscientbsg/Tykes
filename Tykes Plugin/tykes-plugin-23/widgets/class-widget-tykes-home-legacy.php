<?php
/**
 * Tykes Home Legacy Widget
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

use \Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Tykes_Home_Legacy extends Widget_Base_Tykes {

    public function get_name(): string {
        return 'tykes-home-legacy';
    }

    public function get_title(): string {
        return esc_html__( 'Tykes Home Legacy', 'tykes-ds' );
    }

    public function get_icon(): string {
        return 'eicon-image-rollover';
    }

    protected function register_controls(): void {

        // ─── Images Section ───
        $this->start_controls_section( 'section_images', [
            'label' => esc_html__( 'Images', 'tykes-ds' ),
        ] );

        $this->add_control( 'main_image', [
            'label'   => esc_html__( 'Main Image', 'tykes-ds' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/04/6.png' ],
        ] );

        $this->add_control( 'card_image', [
            'label'   => esc_html__( 'Overlay Card Image', 'tykes-ds' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/04/6.png' ],
        ] );

        $this->add_control( 'card_tag', [
            'label'   => esc_html__( 'Overlay Card Tag Text', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Montessori Activity',
        ] );

        $this->end_controls_section();

        // ─── Content Section ───
        $this->start_controls_section( 'section_content', [
            'label' => esc_html__( 'Content', 'tykes-ds' ),
        ] );

        $this->add_control( 'eyebrow', [
            'label'   => esc_html__( 'Eyebrow', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'The Kidzonia Legacy',
        ] );

        $this->add_control( 'title', [
            'label'   => esc_html__( 'Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Building Institutions<br>That Shape Foundations',
            'rows'    => 3,
        ] );

        $this->add_control( 'description', [
            'label'   => esc_html__( 'Description', 'tykes-ds' ),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<p class="legacy-p b-poppins">Tykes Early Years is a modern early childhood institution rooted in the academic philosophy of Kidzonia International Preschools — one of India\'s most recognised preschool networks.</p><p class="legacy-p b-poppins">We combine a structured, research-backed curriculum with playfully designed learning spaces, ensuring every child gets a confident start to their educational journey.</p>',
        ] );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="legacy-sec">
            <div class="container legacy-grid">
                <div class="legacy-img-col">
                    <div class="leg-circle"></div>
                    <?php if ( ! empty( $settings['main_image']['url'] ) ) : ?>
                        <img src="<?php echo esc_url( $settings['main_image']['url'] ); ?>" alt="Legacy Image" class="leg-img-main">
                    <?php endif; ?>
                    
                    <div class="leg-img-card">
                        <?php if ( ! empty( $settings['card_image']['url'] ) ) : ?>
                            <img src="<?php echo esc_url( $settings['card_image']['url'] ); ?>" alt="Activity">
                        <?php endif; ?>
                        <?php if ( ! empty( $settings['card_tag'] ) ) : ?>
                            <div class="leg-card-tag b-poppins"><?php echo esc_html( $settings['card_tag'] ); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="legacy-content">
                    <?php if ( ! empty( $settings['eyebrow'] ) ) : ?>
                        <div class="legacy-eyebrow b-poppins"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $settings['title'] ) ) : ?>
                        <h2 class="legacy-h2"><?php echo wp_kses_post( $settings['title'] ); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $settings['description'] ) ) : ?>
                        <?php echo wp_kses_post( $settings['description'] ); ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
