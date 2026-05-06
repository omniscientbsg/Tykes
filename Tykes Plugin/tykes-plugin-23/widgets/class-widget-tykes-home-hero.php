<?php
/**
 * Tykes Home Hero Widget
 *
 * @package Tykes_DS
 */

namespace Tykes_DS;

use \Elementor\Controls_Manager;

defined('ABSPATH') || exit;

class Widget_Tykes_Home_Hero extends Widget_Base_Tykes
{

    public function get_name(): string
    {
        return 'tykes-home-hero';
    }

    public function get_title(): string
    {
        return esc_html__('Tykes Home Hero', 'tykes-ds');
    }

    public function get_icon(): string
    {
        return 'eicon-image-box';
    }

    protected function register_controls(): void
    {

        // ─── Content Section ───
        $this->start_controls_section('section_content', [
            'label' => esc_html__('Hero Content', 'tykes-ds'),
        ]);

        $this->add_control('eyebrow', [
            'label' => esc_html__('Eyebrow Text', 'tykes-ds'),
            'type' => Controls_Manager::TEXT,
            'default' => 'A Kidzonia Enterprise',
        ]);

        $this->add_control('title_normal', [
            'label' => esc_html__('Title (Normal text)', 'tykes-ds'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Where Every Child\'s Journey Begins with',
            'rows' => 2,
        ]);

        $this->add_control('title_highlight', [
            'label' => esc_html__('Title (Highlighted text)', 'tykes-ds'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Wonder.',
        ]);

        $this->add_control('description', [
            'label' => esc_html__('Description', 'tykes-ds'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'Tykes Early Years is a professionally structured preschool built on the academic legacy of Kidzonia, offering Premium Preschool & Daycare across India\'s key cities.',
        ]);

        $this->end_controls_section();

        // ─── Buttons Section ───
        $this->start_controls_section('section_buttons', [
            'label' => esc_html__('Buttons', 'tykes-ds'),
        ]);

        $this->add_control('btn_primary_text', [
            'label' => esc_html__('Primary Button Text', 'tykes-ds'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Book a Free Visit &rarr;',
        ]);

        $this->add_control('btn_primary_action', [
            'label' => esc_html__('Primary Button Action', 'tykes-ds'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'popup' => 'Open Popup',
                'link' => 'Custom Link',
            ],
            'default' => 'popup',
        ]);

        $this->add_control('btn_primary_link', [
            'label' => esc_html__('Primary Link URL', 'tykes-ds'),
            'type' => Controls_Manager::URL,
            'placeholder' => 'https://your-link.com',
            'condition' => ['btn_primary_action' => 'link'],
        ]);

        $this->add_control('btn_secondary_text', [
            'label' => esc_html__('Secondary Button Text', 'tykes-ds'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Explore Programmes',
        ]);

        $this->add_control('btn_secondary_link', [
            'label' => esc_html__('Secondary Link URL', 'tykes-ds'),
            'type' => Controls_Manager::URL,
            'default' => ['url' => '#programmes'],
        ]);

        $this->end_controls_section();

        // ─── Image & Badges Section ───
        $this->start_controls_section('section_image', [
            'label' => esc_html__('Image & Badges', 'tykes-ds'),
        ]);

        $this->add_control('hero_image', [
            'label' => esc_html__('Hero Image', 'tykes-ds'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => 'https://tykes.school/wp-content/uploads/2026/04/2.png'],
        ]);

        $this->add_control('badge_tr_num', [
            'label' => esc_html__('Top-Right Badge (Number)', 'tykes-ds'),
            'type' => Controls_Manager::TEXT,
            'default' => '#1',
            'separator' => 'before',
        ]);

        $this->add_control('badge_tr_lbl', [
            'label' => esc_html__('Top-Right Badge (Label)', 'tykes-ds'),
            'type' => Controls_Manager::TEXT,
            'default' => 'NCR India 2026',
        ]);

        $this->add_control('badge_bl_icon', [
            'label' => esc_html__('Bottom-Left Badge (Icon)', 'tykes-ds'),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-users',
                'library' => 'fa-solid',
            ],
            'separator' => 'before',
        ]);

        $this->add_control('badge_bl_num', [
            'label' => esc_html__('Bottom-Left Badge (Number)', 'tykes-ds'),
            'type' => Controls_Manager::TEXT,
            'default' => '18,000+',
        ]);

        $this->add_control('badge_bl_lbl', [
            'label' => esc_html__('Bottom-Left Badge (Label)', 'tykes-ds'),
            'type' => Controls_Manager::TEXT,
            'default' => 'Children Nurtured',
        ]);

        $this->end_controls_section();

        // ─── Style Section ───
        $this->start_controls_section('style_badge_icon', [
            'label' => esc_html__('Badge Icon Style', 'tykes-ds'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('badge_icon_color', [
            'label' => esc_html__('Icon Color', 'tykes-ds'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .h-badge-bl .icon i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .h-badge-bl .icon svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('badge_icon_size', [
            'label' => esc_html__('Icon Size', 'tykes-ds'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem'],
            'selectors' => [
                '{{WRAPPER}} .h-badge-bl .icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .h-badge-bl .icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('badge_icon_padding', [
            'label' => esc_html__('Icon Padding', 'tykes-ds'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .h-badge-bl .icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('badge_icon_margin', [
            'label' => esc_html__('Icon Margin', 'tykes-ds'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .h-badge-bl .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        $primary_btn_attr = '';
        if ('popup' === $settings['btn_primary_action']) {
            $primary_btn_attr = 'onclick="tykesOpenPopup(); return false;" href="#"';
        } else {
            $primary_url = $settings['btn_primary_link']['url'] ?? '#';
            $primary_btn_attr = 'href="' . esc_url($primary_url) . '"';
        }

        $secondary_url = $settings['btn_secondary_link']['url'] ?? '#';
        ?>
        <section class="hero-sec">
            <div class="container hero-grid">
                <div class="hero-content">
                    <?php if (!empty($settings['eyebrow'])): ?>
                        <div class="hero-eyebrow b-poppins"><span class="dot"></span>
                            <?php echo wp_kses_post($settings['eyebrow']); ?></div>
                    <?php endif; ?>

                    <h1 class="hero-h1">
                        <?php echo wp_kses_post($settings['title_normal']); ?>
                        <span class="highlight"><?php echo wp_kses_post($settings['title_highlight']); ?></span>
                    </h1>

                    <?php if (!empty($settings['description'])): ?>
                        <p class="hero-p b-poppins"><?php echo wp_kses_post($settings['description']); ?></p>
                    <?php endif; ?>

                    <div class="hero-btns">
                        <?php if (!empty($settings['btn_primary_text'])): ?>
                            <a <?php echo $primary_btn_attr; ?>
                                class="btn-orange"><?php echo wp_kses_post($settings['btn_primary_text']); ?></a>
                        <?php endif; ?>

                        <?php if (!empty($settings['btn_secondary_text'])): ?>
                            <a href="<?php echo esc_url($secondary_url); ?>"
                                class="btn-white"><?php echo wp_kses_post($settings['btn_secondary_text']); ?></a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="hero-image-wrap">
                    <?php if (!empty($settings['hero_image']['url'])): ?>
                        <img src="<?php echo esc_url($settings['hero_image']['url']); ?>" alt="Tykes Hero" class="hero-img"
                            style="object-position: top;">
                    <?php endif; ?>

                    <div class="h-badge-tr b-poppins">
                        <span class="num"><?php echo esc_html($settings['badge_tr_num']); ?></span>
                        <span class="lbl"><?php echo esc_html($settings['badge_tr_lbl']); ?></span>
                    </div>

                    <div class="h-badge-bl b-poppins">
                        <div class="icon">
                            <?php \Elementor\Icons_Manager::render_icon($settings['badge_bl_icon'], ['aria-hidden' => 'true']); ?>
                        </div>
                        <div>
                            <span class="num"><?php echo esc_html($settings['badge_bl_num']); ?></span>
                            <span class="lbl"><?php echo esc_html($settings['badge_bl_lbl']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}
