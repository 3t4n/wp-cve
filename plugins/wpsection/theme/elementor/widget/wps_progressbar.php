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




class wpsection_wps_progressbar_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'wpsection_wps_progressbar';
    }

    public function get_title()
    {
        return __('Progress bar', 'wpsection');
    }

    public function get_icon()
    {
        return 'eicon-progress-tracker';
    }

    public function get_keywords()
    {
        return ['wpsection', 'progressbar'];
    }

    public function get_categories()
    {
        return ['wpsection_category'];
    }


    protected function register_controls()
    {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'wpsection'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'style',
            [
                'label'   => esc_html__('Choose Different Style', 'wpsection'),
                'label_block' => true,
                'type'    => Controls_Manager::SELECT,
                'default' => 'style2',
                'options' => array(
                    'style1' => esc_html__('Choose Style 1', 'wpsection'),
                    'style2' => esc_html__('Choose Style 2', 'wpsection'),

                ),
            ]
        );

        $this->add_control(
            'sec_class',
            [
                'label'   => esc_html__('Choose Different Style', 'wpsection'),
                'label_block' => true,
                'type'    => Controls_Manager::SELECT,
                'default' => '3',
                'options' => array(
                    '1' => esc_html__('Style 1', 'wpsection'),
                    '2' => esc_html__('Style 2', 'wpsection'),
                    '3' => esc_html__('Style 3', 'wpsection'),
                    '4' => esc_html__('Style 4', 'wpsection'),


                ),
            ]
        );
 

        $this->add_control(
            'pb_label',
            [
                'label'     => esc_html__('Label', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::TEXT,
                'default' => 'Financial Advice',
            ]
        );
        $this->add_control(
            'start_label',
            [
                'label'     => esc_html__('Start Label', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::TEXT,
                'default' => '0',
            ]
        );

        $this->add_control(
            'end_label',
            [
                'label'     => esc_html__('End Label', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::TEXT,
                'default' => '100',
            ]
        );
        $this->add_control(
            'pb_value',
            [
                'label' => esc_html__('Percentage Value', 'wpsection'),
                'type'  => Controls_Manager::NUMBER,
                'default' => '75',
            ]
        );
        $this->add_control(
            'image',
            [
                'label'     => esc_html__('Bar Image', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    // 'style' => [ '3' ]
                ]
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'wpsection_btn_style_holder',
            [
                'label' => esc_html__('Global Style', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'pb_margin',
            [
                'label'      => esc_html__('Margin', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-progress-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pb_height',
            [
                'label'      => esc_html__('Bar Height', 'wpsection'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 150,
                        'step' => 1,
                    ]
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-progress-bar' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    // 'style' => ['1', '2']
                ]
            ]
        );

        $this->add_responsive_control(
            'pb_radius',
            [
                'label'      => esc_html__('Border Radius', 'wpsection'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .wpsection-progress-bar, {{WRAPPER}} .wpsection-progress-fill, {{WRAPPER}} .wpsection-progress-bar-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'label'    => esc_html__('Label Typography', 'wpsection'),
                'selector' => '{{WRAPPER}} .wpsection-pb-label, {{WRAPPER}} .wpsection-pb-start-label, {{WRAPPER}} .wpsection-pb-end-label',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'percent_typography',
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                    ),
                ),
                'label'     => esc_html__('Percent Value Typography', 'wpsection'),
                'selector'  => '{{WRAPPER}} .wpsection-pb-percent',
                'condition' => [
                    // 'style' => ['1', '2']
                ]
            ]
        );

        $this->add_responsive_control(
            'label_color',
            [
                'label'     => esc_html__('Label Color', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-progress-bar .wpsection-pb-label'  => 'color: {{VALUE}}',

                ],
                'condition' => [
                    // 'style' => [ '1', '3' ]
                ]
            ]

        );

        $this->add_responsive_control(
            'value_color',
            [
                'label'     => esc_html__('Value Color', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-progress-bar .wpsection-pb-percent' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpsection-progress-bar-3 .wpsection-pb-percent svg path, 
					{{WRAPPER}} .wpsection-progress-bar-3 .wpsection-pb-percent svg text' => 'fill: {{VALUE}}',
                ],
            ]

        );
        $this->add_responsive_control(
            'start_color',
            [
                'label'     => esc_html__('Start label', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-progress-bar .wpsection-pb-start-label'  => 'color: {{VALUE}}',

                ],

            ]

        );

        $this->add_responsive_control(
            'end_color',
            [
                'label'     => esc_html__('End label', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-progress-bar .wpsection-pb-end-label'  => 'color: {{VALUE}}',

                ],

            ]

        );

        $this->add_responsive_control(
            'bar_color',
            [
                'label'     => esc_html__('Bar Color', 'wpsection'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-progress-bar-1, 
					{{WRAPPER}} .wpsection-progress-bar-2, 
					{{WRAPPER}} .wpsection-progress-bar-3, 
					{{WRAPPER}} .wpsection-progress-bar-4, 
					{{WRAPPER}} .wpsection-progress-bar-5, 
					{{WRAPPER}} .wpsection-progress-bar-6, 
					{{WRAPPER}} .wpsection-progress-bar .wpsection-progress-bar-inner' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'fill_color',
            [
                'label'     => esc_html__('Fill Color', 'wpsection'),
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style1',
                        ),
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                    ),
                ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpsection-progress-bar-1 .wpsection-progress-fill, 
					{{WRAPPER}} .wpsection-progress-bar-2 .wpsection-progress-fill, 
					{{WRAPPER}} .wpsection-progress-bar-3 .wpsection-progress-fill, 
					{{WRAPPER}} .wpsection-progress-bar-4 .wpsection-progress-fill, 
					{{WRAPPER}} .wpsection-progress-bar-5 .wpsection-progress-fill, 
					{{WRAPPER}} .wpsection-progress-bar-6 .wpsection-progress-fill, 
					{{WRAPPER}} .wpsection-progress-bar .wpsection-progress-fill' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    // 'style' => [ '1', '3' ]
                ]
            ]

        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'      => 'fill_gradient',
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                    ),
                ),
                'label'     => esc_html__('Fill Color', 'wpsection'),
                'types'     => ['gradient'],
                'selector'  => '{{WRAPPER}} .wpsection-progress-bar-2 .wpsection-progress-fill',
                'exclude'   => [
                    'image'
                ],
                'condition' => [
                    // 'style' => ['2']
                ]
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'indicator_color',
                'conditions' => array(
                    'relation' => 'or',
                    'terms'    => array(
                        array(
                            'name'     => 'style',
                            'operator' => '==',
                            'value'    => 'style2',
                        ),
                    ),
                ),
                'label' => esc_html__('Indicator Color', 'wpsection'),
                'types' => ['classic', 'gradient'],
                'exclude'    => [
                    'image'
                ],
                'selector'   => '{{WRAPPER}} .wpsection-progress-bar-3 .wpsection-pb-indicator',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $allowed_tags = wp_kses_allowed_html('post');
?>


        <?php


        ?>



        <?php if ('style1' === $settings['style']) : ?>
            <div id="wpsection-progress-bar" class="wpsection-progress-bar wpsection-progress-bar-<?php echo esc_attr($settings['sec_class']); ?>">
                <div class="wpsection-progress-fill" role="progressbar" aria-valuenow="<?php echo $settings['pb_value']; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $settings['pb_value'] . '%'; ?>">

                    <span class="wpsection-pb-label"><?php echo $settings['pb_label']; ?></span>

                    <span class="wpsection-pb-percent"><?php echo $settings['pb_value'] . '%'; ?></span>

                </div>
            </div>
        <?php endif; ?>

        <?php if ('style2' === $settings['style']) : ?>
            <div id="wpsection-progress-bar" class="wpsection-progress-bar wpsection-progress-bar-<?php echo esc_attr($settings['sec_class']); ?>">

                <span class="wpsection-pb-start-label"><?php echo $settings['start_label']; ?></span>

                <div class="wpsection-progress-bar-inner">
                    <div class="wpsection-progress-fill" role="progressbar" aria-valuenow="<?php echo $settings['pb_value']; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $settings['pb_value'] . '%'; ?>">


                        <div class="wpsection-pb-indicator">
                            <?php if (esc_url($settings['image']['id'])) : ?>
                                <img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" alt="<?php echo $settings['pb_value']; ?>" />
                            <?php else : ?>
                                <div class="noimage"></div>
                            <?php endif; ?>
                        </div>

                        <div class="wpsection-pb-percent">
                        </div>


                    </div>
                </div>

                <span class="wpsection-pb-end-label"><?php echo $settings['end_label']; ?></span>

            </div>
        <?php endif; ?>


<?php
    }
}


Plugin::instance()->widgets_manager->register(new \wpsection_wps_progressbar_Widget());
