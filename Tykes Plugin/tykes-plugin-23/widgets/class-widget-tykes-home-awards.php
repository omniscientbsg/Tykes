<?php
/**
 * Tykes Home Awards Widget
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

use \Elementor\Controls_Manager;
use \Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Widget_Tykes_Home_Awards extends Widget_Base_Tykes {

    public function get_name(): string {
        return 'tykes-home-awards';
    }

    public function get_title(): string {
        return esc_html__( 'Tykes Home Awards', 'tykes-ds' );
    }

    public function get_icon(): string {
        return 'eicon-price-list';
    }

    protected function register_controls(): void {

        $this->start_controls_section( 'section_awards', [
            'label' => esc_html__( 'Awards Ticker', 'tykes-ds' ),
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'award_text', [
            'label'   => esc_html__( 'Award Name', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Best Pre-School Chain',
        ] );

        $this->add_control( 'awards_list', [
            'label'       => esc_html__( 'Awards', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'award_text' => 'Best Pre-School Chain in North India 2023' ],
                [ 'award_text' => 'Times Education Icon Award 2023' ],
                [ 'award_text' => 'National Educational Excellence Award' ],
                [ 'award_text' => 'Global Teaching Excellence' ],
            ],
            'title_field' => '{{{ award_text }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['awards_list'] ) ) {
            return;
        }

        // To make a seamless infinite scroll, we duplicate the list once or twice
        // CSS animation 'marquee' takes care of the smooth loop
        $items = $settings['awards_list'];
        $rendered_items = array_merge( $items, $items, $items ); // Duplicate for safety
        ?>
        <section class="awards-ticker">
            <div class="ticker-track">
                <?php foreach ( $rendered_items as $item ) : ?>
                    <div class="ticker-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
                        <?php echo esc_html( $item['award_text'] ); ?>
                    </div>
                    <div class="ticker-dot"></div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
