<?php



use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;




class wpsection_wps_counter_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'wpsection_wps_counter';
    }

    public function get_title()
    {
        return __('Counter', 'wpsection');
    }

    public function get_icon()
    {
        return 'eicon-counter-circle';
    }

    public function get_keywords()
    {
        return ['wpsection', 'counter'];
    }

    public function get_categories()
    {
        return ['wpsection_category'];
    }


    protected function register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Icon', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'sec_class',
            [
                'label'   => esc_html__('Choose Different Style', 'wpsection'),
                'label_block' => true,
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => array(
                    '1' => esc_html__('Style 1', 'wpsection'),
                    '2' => esc_html__('Style 2', 'wpsection'),
                    '3' => esc_html__('Style 3', 'wpsection'),
                    '4' => esc_html__('Style 4', 'wpsection'),
                    '5' => esc_html__('Style 5', 'wpsection'),


                ),
            ]
        );
        $this->add_control(
            'choose_media',
            [
                'label'   => esc_html__('Select Type', 'wpsection'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'style1',
                'options' => [
                    'style2'  => esc_html__('Icon', 'wpsection'),
                    'style1' => esc_html__('Image', 'wpsection'),
                ],
            ]
        );

        $this->add_control(
            'image',
            [
                'label'     => esc_html__('Choose Image', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::MEDIA,
                'default'   => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'choose_media' => 'style1',
                ],
            ]
        );

        $this->add_control(
            'icons',
            [
                'label'            => esc_html__('Choose Icon', 'wpsection'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'condition'        => [
                    'choose_media' => 'style2',
                ],
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'       => __('Number', 'rashid'),
                'type'        => Controls_Manager::NUMBER,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your Number', 'rashid'),
                'default' => '90',
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => __('Title', 'rashid'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your title', 'rashid'),
                'default' => 'Successful projects',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_infobox_style',
            [
                'label' => esc_html__('Global Style', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bg2_color',
            [
                'label'     => esc_html__('Background Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-counter-2' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'style' => ['2'],
                ],
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label'     => esc_html__('Primary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'style' => ['3', '4', '5'],
                ],
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label'     => esc_html__('Secondary Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'style' => ['3', '5'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__('Icon', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'img_width',
            [
                'label'      => esc_html__('Image Width', 'wpsection'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 300,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 42,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-counter .counter-icon img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'choose_media' => 'choose_image',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => esc_html__('Icon Size', 'wpsection'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 170,
                        'step' => 1,
                    ]
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 42,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-counter .counter-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'choose_media' => 'choose_icon',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_bg_size',
            [
                'label'      => esc_html__('Icon Background Size', 'wpsection'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 300,
                        'step' => 1,
                    ]
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-counter .counter-icon i' => 'width: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'choose_media' => 'choose_icon',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label'      => esc_html__('Margin', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-counter .counter-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-counter .counter-icon i' => 'color: {{VALUE}};',
                ],
                'condition'  => [
                    'choose_media' => 'choose_icon',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_number_style',
            [
                'label' => esc_html__('Number', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label'     => esc_html__('Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-counter .counter-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_number',
                'selector' => '{{WRAPPER}} .wpsection-counter .counter-number',
            ]
        );

        $this->add_responsive_control(
            'number_margin',
            [
                'label'      => esc_html__('Margin', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-counter .counter-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-counter .counter-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography_title',
                'selector' => '{{WRAPPER}} .wpsection-counter .counter-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__('Margin', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-counter .counter-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $allowed_tags = wp_kses_allowed_html('post');
?>







        <div class="wpsection-counter wpsection-counter-<?php echo esc_attr($settings['sec_class']); ?>">



            <div class="counter-icon">
                <?php if ('style1' === $settings['choose_media']) : ?>
                    <?php if (esc_url($settings['image']['id'])) : ?>
                        <img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" />
                    <?php else : ?>
                        <div class="noimage"></div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ('style2' === $settings['choose_media']) : ?>
                    <i class="<?php echo esc_attr($settings['icons']); ?>"></i>
                <?php endif; ?>
            </div>


            <div class="counter-info">
                <h3 id="counter" class="counter-number"><?php echo $settings['subtitle']; ?></h3>
                <h4 class="counter-title"><?php echo $settings['title']; ?></h4>
            </div>
        </div>


<?php
    }
}


Plugin::instance()->widgets_manager->register(new \wpsection_wps_counter_Widget());
