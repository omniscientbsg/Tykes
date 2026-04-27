<?php
/**
 * Tykes Home Stats Widget
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

use \Elementor\Controls_Manager;
use \Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Widget_Tykes_Home_Stats extends Widget_Base_Tykes {

    public function get_name(): string {
        return 'tykes-home-stats';
    }

    public function get_title(): string {
        return esc_html__( 'Tykes Home Stats', 'tykes-ds' );
    }

    public function get_icon(): string {
        return 'eicon-counter';
    }

    protected function register_controls(): void {

        $this->start_controls_section( 'section_stats', [
            'label' => esc_html__( 'Stats Strip', 'tykes-ds' ),
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'stat_num', [
            'label'   => esc_html__( 'Number', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '100+',
        ] );

        $repeater->add_control( 'stat_lbl', [
            'label'   => esc_html__( 'Label', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Awesome Stat',
        ] );

        $this->add_control( 'stats_list', [
            'label'       => esc_html__( 'Stats', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'stat_num' => '18,000+',
                    'stat_lbl' => 'Children Nurtured',
                ],
                [
                    'stat_num' => '45+',
                    'stat_lbl' => 'Profit Centres',
                ],
                [
                    'stat_num' => '50+',
                    'stat_lbl' => 'Awards Won',
                ],
                [
                    'stat_num' => '#1',
                    'stat_lbl' => 'India ECE Index 2026',
                ],
            ],
            'title_field' => '{{{ stat_lbl }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['stats_list'] ) ) {
            return;
        }

        // Dynamically set grid columns based on number of items (up to 4)
        $count = count( $settings['stats_list'] );
        $grid_style = ( $count !== 4 ) ? 'style="grid-template-columns: repeat(' . esc_attr( $count ) . ', 1fr);"' : '';
        ?>
        <div class="stats-strip-custom container">
            <div class="stats-strip-grid" <?php echo $grid_style; ?>>
                <?php foreach ( $settings['stats_list'] as $item ) : ?>
                    <div class="stat-item-c b-poppins elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
                        <span class="num"><?php echo esc_html( $item['stat_num'] ); ?></span>
                        <span class="lbl"><?php echo esc_html( $item['stat_lbl'] ); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
