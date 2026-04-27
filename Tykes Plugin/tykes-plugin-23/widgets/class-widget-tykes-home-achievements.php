<?php
/**
 * Tykes Home Achievements Widget
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

use \Elementor\Controls_Manager;
use \Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Widget_Tykes_Home_Achievements extends Widget_Base_Tykes {

    public function get_name(): string {
        return 'tykes-home-achievements';
    }

    public function get_title(): string {
        return esc_html__( 'Tykes Home Achievements', 'tykes-ds' );
    }

    public function get_icon(): string {
        return 'eicon-skill-bar';
    }

    protected function register_controls(): void {

        // ─── Header Section ───
        $this->start_controls_section( 'section_header', [
            'label' => esc_html__( 'Section Header', 'tykes-ds' ),
        ] );

        $this->add_control( 'bg_image', [
            'label'   => esc_html__( 'Background Image', 'tykes-ds' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80' ],
        ] );

        $this->add_control( 'eyebrow', [
            'label'   => esc_html__( 'Eyebrow', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Milestones',
        ] );

        $this->add_control( 'title', [
            'label'   => esc_html__( 'Title', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Our Growth in Numbers',
        ] );

        $this->end_controls_section();

        // ─── Achievements Section ───
        $this->start_controls_section( 'section_achievements', [
            'label' => esc_html__( 'Achievements', 'tykes-ds' ),
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'icon', [
            'label'   => esc_html__( 'Icon', 'tykes-ds' ),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-graduation-cap',
                'library' => 'fa-solid',
            ],
        ] );

        $repeater->add_control( 'label', [
            'label'   => esc_html__( 'Label (Number)', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '45+',
        ] );

        $repeater->add_control( 'description', [
            'label'   => esc_html__( 'Description', 'tykes-ds' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'Centres Across India',
        ] );

        $this->add_control( 'achievements_list', [
            'label'       => esc_html__( 'Achievements', 'tykes-ds' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'icon' => [ 'value' => 'fas fa-graduation-cap', 'library' => 'fa-solid' ], 'label' => '45+', 'description' => 'Centres Across India' ],
                [ 'icon' => [ 'value' => 'fas fa-users', 'library' => 'fa-solid' ], 'label' => '18,000+', 'description' => 'Happy Parents' ],
                [ 'icon' => [ 'value' => 'fas fa-trophy', 'library' => 'fa-solid' ], 'label' => '50+', 'description' => 'Awards Won' ],
                [ 'icon' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ], 'label' => '#1', 'description' => 'India ECE Index 2026' ],
                [ 'icon' => [ 'value' => 'fas fa-city', 'library' => 'fa-solid' ], 'label' => '12', 'description' => 'Cities Covered' ],
                [ 'icon' => [ 'value' => 'fas fa-handshake', 'library' => 'fa-solid' ], 'label' => '200+', 'description' => 'Franchise Partners' ],
                [ 'icon' => [ 'value' => 'fas fa-chalkboard-teacher', 'library' => 'fa-solid' ], 'label' => '1500+', 'description' => 'Trained Educators' ],
            ],
            'title_field' => '{{{ label }}}',
        ] );

        $this->end_controls_section();

        // ─── Style Section ───
        $this->start_controls_section( 'style_icon', [
            'label' => esc_html__( 'Icon Style', 'tykes-ds' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'icon_color', [
            'label'   => esc_html__( 'Icon Color', 'tykes-ds' ),
            'type'    => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .achieve-icon i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .achieve-icon svg' => 'fill: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control( 'icon_size', [
            'label'   => esc_html__( 'Icon Size', 'tykes-ds' ),
            'type'    => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'selectors' => [
                '{{WRAPPER}} .achieve-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .achieve-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'icon_padding', [
            'label'   => esc_html__( 'Icon Padding', 'tykes-ds' ),
            'type'    => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} .achieve-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'icon_margin', [
            'label'   => esc_html__( 'Icon Margin', 'tykes-ds' ),
            'type'    => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} .achieve-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();

        $bg_url = ! empty( $settings['bg_image']['url'] ) ? esc_url( $settings['bg_image']['url'] ) : '';
        $bg_style = $bg_url ? 'style="background: url(\'' . $bg_url . '\') center center / cover no-repeat;"' : '';
        
        $widget_id = $this->get_id();
        $track_id  = 'achieveTrack_' . $widget_id;
        $dots_id   = 'achieveDots_' . $widget_id;
        ?>
        <section class="achieve-sec" id="achieve-sec-<?php echo esc_attr( $widget_id ); ?>" <?php echo $bg_style; ?>>
            <div class="achieve-overlay"></div>
            <div class="container achieve-inner">
                <div class="achieve-header">
                    <?php if ( ! empty( $settings['eyebrow'] ) ) : ?>
                        <div class="achieve-eyebrow"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $settings['title'] ) ) : ?>
                        <h2 class="achieve-title"><?php echo esc_html( $settings['title'] ); ?></h2>
                    <?php endif; ?>
                </div>

                <?php if ( ! empty( $settings['achievements_list'] ) ) : ?>
                <div class="achieve-viewport">
                    <div class="achieve-track" id="<?php echo esc_attr( $track_id ); ?>">
                        <?php foreach ( $settings['achievements_list'] as $index => $item ) : ?>
                            <div class="achieve-stat <?php echo $index === 0 ? 'is-active' : ''; ?> elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
                                <div class="achieve-circle">
                                    <div class="achieve-icon">
                                        <?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                    </div>
                                </div>
                                <h3 class="achieve-label"><?php echo esc_html( $item['label'] ); ?></h3>
                                <p class="achieve-desc"><?php echo esc_html( $item['description'] ); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="achieve-dots" id="<?php echo esc_attr( $dots_id ); ?>">
                    <?php foreach ( $settings['achievements_list'] as $index => $item ) : ?>
                        <button class="achieve-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>"></button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <?php if ( ! empty( $settings['achievements_list'] ) && count( $settings['achievements_list'] ) > 1 ) : ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var track  = document.getElementById('<?php echo esc_js( $track_id ); ?>');
            var pills  = document.querySelectorAll('#<?php echo esc_js( $dots_id ); ?> .achieve-dot');
            if (!track || !pills.length) return;

            var stats   = track.querySelectorAll('.achieve-stat');
            var total   = stats.length;
            var perPage = window.innerWidth <= 520 ? 1 : (window.innerWidth <= 900 ? 2 : 4);
            var current = 0;
            var timer;

            function goTo(idx) {
                stats[current].classList.remove('is-active');
                pills[current].classList.remove('active');
                current = (idx + total) % total;
                stats[current].classList.add('is-active');
                pills[current].classList.add('active');

                var itemWidth = stats[0].offsetWidth;
                var offset    = Math.min(current, Math.max(0, total - perPage)) * itemWidth;
                track.style.transform = 'translateX(-' + offset + 'px)';
            }

            function next() { goTo(current + 1); }
            function startAchieve() { timer = setInterval(next, 2800); }
            function stopAchieve()  { clearInterval(timer); }

            pills.forEach(function(pill) {
                pill.addEventListener('click', function() {
                    stopAchieve();
                    goTo(parseInt(this.getAttribute('data-index')));
                    startAchieve();
                });
            });

            var sec = document.getElementById('achieve-sec-<?php echo esc_js( $widget_id ); ?>');
            if (sec) {
                sec.addEventListener('mouseenter', stopAchieve);
                sec.addEventListener('mouseleave', startAchieve);
            }

            window.addEventListener('resize', function() { 
                perPage = window.innerWidth <= 520 ? 1 : (window.innerWidth <= 900 ? 2 : 4);
                goTo(current); 
            });
            
            startAchieve();
        });
        </script>
        <?php endif; ?>
        <?php
    }
}
