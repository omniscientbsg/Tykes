<?php
/**
 * Tykes Home Programmes Widget
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

use \Elementor\Controls_Manager;
use \Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Widget_Tykes_Home_Programmes extends Widget_Base_Tykes {

    public function get_name(): string {
        return 'tykes-home-programmes';
    }

    public function get_title(): string {
        return esc_html__( 'Tykes Home Programmes', 'tykes-ds' );
    }

    public function get_icon(): string {
        return 'eicon-gallery-grid';
    }

    protected function register_controls(): void {

        // ─── Header Section ───
        $this->start_controls_section( 'section_header', [
            'label' => esc_html__( 'Section Header', 'tykes-ds' ),
        ] );

        $this->add_control( 'eyebrow', [
            'label'   => esc_html__( 'Eyebrow', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'OUR PROGRAMMES',
        ] );

        $this->add_control( 'title', [
            'label'   => esc_html__( 'Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Every Age. Every Stage.<br>One Beautiful Journey.',
            'rows'    => 3,
        ] );

        $this->add_control( 'subtitle', [
            'label'   => esc_html__( 'Subtitle', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => 'Our five flagship programs are scientifically sequenced to nurture growth at every milestone — from first steps to big school.',
            'rows'    => 3,
        ] );

        $this->end_controls_section();

        // ─── Cards Section ───
        $this->start_controls_section( 'section_cards', [
            'label' => esc_html__( 'Programme Cards', 'tykes-ds' ),
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'card_image', [
            'label'   => esc_html__( 'Card Image', 'tykes-ds' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => 'https://tykes.school/wp-content/uploads/2026/04/6.png' ],
        ] );

        $repeater->add_control( 'card_tag', [
            'label'   => esc_html__( 'Tag Text', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '🍼 AGE 2-3',
        ] );

        $repeater->add_control( 'card_tag_color', [
            'label'   => esc_html__( 'Tag Color Variable', 'tykes-ds' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                '--pink'  => 'Pink',
                '--orange'=> 'Orange',
                '--teal'  => 'Teal',
                '--green' => 'Green',
                '--goldd' => 'Dark Gold',
                '--p'     => 'Purple',
            ],
            'default' => '--pink',
        ] );

        $repeater->add_control( 'card_title', [
            'label'   => esc_html__( 'Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Play Group',
        ] );

        $repeater->add_control( 'card_age', [
            'label'   => esc_html__( 'Subtitle/Age Text', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Where It All Begins',
        ] );

        $repeater->add_control( 'card_arrow_active', [
            'label'   => esc_html__( 'Active Arrow?', 'tykes-ds' ),
            'type'    => Controls_Manager::SWITCHER,
            'label_on' => 'Yes',
            'label_off' => 'No',
            'return_value' => 'yes',
            'default' => '',
            'description' => 'Highlights the arrow button on this card.',
        ] );

        $repeater->add_control( 'card_link', [
            'label'   => esc_html__( 'Link URL', 'tykes-ds' ),
            'type'    => Controls_Manager::URL,
            'default' => [ 'url' => 'https://tykes.school/tykes-programmes/#playgroup' ],
        ] );

        $this->add_control( 'programmes_list', [
            'label'       => esc_html__( 'Cards', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'card_tag' => '🍼 AGE 2-3',
                    'card_tag_color' => '--pink',
                    'card_title' => 'Play Group',
                    'card_age' => 'Where It All Begins',
                ],
                [
                    'card_tag' => '🚀 AGE 3-4',
                    'card_tag_color' => '--orange',
                    'card_title' => 'Nursery',
                    'card_age' => 'Fuel for Independence',
                ],
                [
                    'card_tag' => '🌟 AGE 4-5',
                    'card_tag_color' => '--teal',
                    'card_title' => 'Junior KG',
                    'card_age' => 'Think, Question, Create',
                    'card_arrow_active' => 'yes',
                ],
                [
                    'card_tag' => '🎓 AGE 5-6',
                    'card_tag_color' => '--green',
                    'card_title' => 'Senior KG',
                    'card_age' => 'Ready for Big School',
                ],
                [
                    'card_tag' => '🏡 6M - 12 YRS',
                    'card_tag_color' => '--goldd',
                    'card_title' => 'Premium Daycare',
                    'card_age' => 'A Home Away From Home',
                ],
            ],
            'title_field' => '{{{ card_title }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="prog-overview b-poppins">
            <div class="container">
                <?php if ( ! empty( $settings['eyebrow'] ) ) : ?>
                    <div class="section-label"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
                <?php endif; ?>
                
                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h2 class="section-title h-fredoka"><?php echo wp_kses_post( $settings['title'] ); ?></h2>
                <?php endif; ?>
                
                <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                    <p class="section-sub"><?php echo wp_kses_post( $settings['subtitle'] ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $settings['programmes_list'] ) ) : ?>
                <div class="prog-cards-row">
                    <?php foreach ( $settings['programmes_list'] as $item ) : 
                        $url = ! empty( $item['card_link']['url'] ) ? esc_url( $item['card_link']['url'] ) : '#';
                        $arrow_style = ( 'yes' === $item['card_arrow_active'] ) ? 'style="background:var(--p); color:white;"' : '';
                        $tag_color = ! empty( $item['card_tag_color'] ) ? esc_attr( $item['card_tag_color'] ) : '--pink';
                    ?>
                    <a href="<?php echo $url; ?>" class="prog-card elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
                        <?php if ( ! empty( $item['card_image']['url'] ) ) : ?>
                            <img src="<?php echo esc_url( $item['card_image']['url'] ); ?>" alt="<?php echo esc_attr( $item['card_title'] ); ?>" class="prog-card-img">
                        <?php endif; ?>
                        
                        <div class="prog-card-body">
                            <span class="prog-card-tag" style="color:var(<?php echo $tag_color; ?>);"><?php echo esc_html( $item['card_tag'] ); ?></span>
                            <h3 class="prog-card-name h-fredoka"><?php echo esc_html( $item['card_title'] ); ?></h3>
                            <span class="prog-card-age"><?php echo esc_html( $item['card_age'] ); ?></span>
                            <div class="prog-card-arrow" <?php echo $arrow_style; ?>>&rarr;</div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }
}
