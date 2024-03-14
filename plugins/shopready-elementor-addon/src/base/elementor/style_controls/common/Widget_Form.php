<?php

/**
 * @package Shop Ready
 */

namespace Shop_Ready\base\elementor\style_controls\common;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

trait Widget_Form
{


    public function input_field($atts)
    {

        $atts_variable = shortcode_atts(
            array(
                'title'           => esc_html__('Input', 'shopready-elementor-addon'),
                'slug'            => '_meta_after_before_style',
                'element_name'    => 'after__woo_ready__',
                'selector'        => '{{WRAPPER}} ',
                'hover_selector' => false,
                'condition'       => '',
                'tab' => Controls_Manager::TAB_STYLE,
                'disable_controls' => []
            ),
            $atts
        );

        extract($atts_variable);

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize($slug);

        /*----------------------------
            ELEMENT__STYLE
        -----------------------------*/
        $tab_start_section_args =  [
            'label' => $title,
            'tab'   => $tab,
        ];

        if (is_array($condition)) {
            $tab_start_section_args['condition'] = $condition;
        }


        /*---------------------------
            INPUT STYLE START
        ----------------------------*/
        $this->start_controls_section(
            $widget . '_fomre_field_section',
            $tab_start_section_args
        );


        $this->start_controls_tabs(
            $widget . 'style_ST_tabs'
        );



        $this->start_controls_tab(
            $widget . '_normal_tabs',
            [
                'label' => esc_html__('Normal', 'shopready-elementor-addon'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     =>   $widget . '_input_box_typography',
                'selector' => $selector,
            ]
        );

        $this->add_control(
            $widget . 'input_bo_text_color',
            [
                'label'     => esc_html__('Text Color', 'shopready-elementor-addon'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    $selector => 'color: {{VALUE}}',
                ],
            ]
        );

        if (!in_array('bg', $disable_controls)) {
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => $widget . 'input_box_background',
                    'label'    => esc_html__('Background', 'shopready-elementor-addon'),
                    'types'    => ['classic', 'gradient'],
                    'selector' => $selector,
                ]
            );
        }

        $this->add_control(
            $widget . 'input_box_placeholder_color',
            [
                'label'     => esc_html__('Placeholder Color', 'shopready-elementor-addon'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    $selector . '::-webkit-input-placeholder'   => 'color: {{VALUE}};',
                    $selector . '::-moz-placeholder'            => 'color: {{VALUE}};',
                    $selector . ':-ms-input-placeholder'        => 'color: {{VALUE}};',


                ],
            ]
        );

        if (!in_array('height-width', $disable_controls)) {

            $this->add_responsive_control(
                $widget . 'input_box_height',
                [
                    'label'      => esc_html__('Height', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'max' => 550,
                        ],
                    ],

                    'selectors' => [
                        $selector   => 'height:{{SIZE}}{{UNIT}} !important;',

                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_responsive_control(
                $widget . 'input_box_width',
                [
                    'label'      => esc_html__('Width', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector  => 'width:{{SIZE}}{{UNIT}} !important;',

                    ],
                ]
            );
        }
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => $widget . 'input_box_border',
                'label'    => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => $selector,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            $widget . 'input_box_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    $selector   => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',

                ],
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => $widget . 'input_box_shadow',
                'selector' => $selector,
            ]
        );
        $this->add_responsive_control(
            $widget . 'input_box_padding',
            [
                'label'      => esc_html__('Padding', 'shopready-elementor-addon'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [

                    $selector   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            $widget . 'input_box_margin',
            [
                'label'      => esc_html__('Margin', 'shopready-elementor-addon'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    $selector   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            $widget . 'input_box_transition',
            [
                'label'      => esc_html__('Transition', 'shopready-elementor-addon'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0.1,
                        'max'  => 3,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0.3,
                ],
                'selectors' => [
                    $selector   => 'transition: {{SIZE}}s;',

                ],
            ]
        );

        $this->end_controls_tab();

        if ($hover_selector) {

            $this->start_controls_tab(
                $widget . '_hover_tabs',
                [
                    'label' => esc_html__('Focus / hover', 'shopready-elementor-addon'),
                ]
            );

            $this->add_control(
                $widget . 'input_box_hover_color',
                [
                    'label'     => esc_html__('Text Color', 'shopready-elementor-addon'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        $hover_selector   => 'color:{{VALUE}};',

                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => $widget . 'input_box_hover_backkground',
                    'label'    => esc_html__('Focus Background', 'shopready-elementor-addon'),
                    'types'    => ['classic', 'gradient'],
                    'selector' => $hover_selector,


                ]
            );
            $this->add_control(
                $widget . 'input_box_hover_border_color',
                [
                    'label'     => esc_html__('Border Color', 'shopready-elementor-addon'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        $hover_selector  => 'border-color:{{VALUE}};',

                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => $widget . 'input_box_hover_shadow',
                    'selector' => $hover_selector


                ]
            );

            $this->end_controls_tab();
        }
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*-----------------------------
            INPUT STYLE END
        -------------------------------*/
    }

    public function checkbox_field($atts)
    {

        $atts_variable = shortcode_atts(
            array(
                'title'           => esc_html__('Checkbox', 'shopready-elementor-addon'),
                'slug'            => '_checkbox_after_before_style',
                'element_name'    => 'after_check_woo_ready__',
                'selector'        => '{{WRAPPER}} ',
                'hover_selector' => false,
                'condition'       => '',
                'disable_controls' => []
            ),
            $atts
        );

        extract($atts_variable);

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize($slug);

        /*----------------------------
            ELEMENT__STYLE
        -----------------------------*/
        $tab_start_section_args =  [
            'label' => $title,
            'tab'   => Controls_Manager::TAB_STYLE,
        ];

        if (is_array($condition)) {
            $tab_start_section_args['condition'] = $condition;
        }




        /*---------------------------
            INPUT STYLE START
        ----------------------------*/
        $this->start_controls_section(
            $widget . '_fomre_field_section',
            $tab_start_section_args
        );


        $this->start_controls_tabs(
            $widget . 'style_ST_tabs'
        );



        $this->start_controls_tab(
            $widget . '_normal_tabs',
            [
                'label' => esc_html__('Normal', 'shopready-elementor-addon'),
            ]
        );

        if (!in_array('bg', $disable_controls)) {

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => $widget . 'input_box_background',
                    'label'    => esc_html__('Background', 'shopready-elementor-addon'),
                    'types'    => ['classic', 'gradient'],
                    'selector' => $selector,
                ]
            );
        }

        if (!in_array('size', $disable_controls)) {

            $this->add_responsive_control(
                $widget . 'input_box_height',
                [
                    'label'      => esc_html__('Height', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'max' => 550,
                        ],
                    ],

                    'selectors' => [
                        $selector   => 'height:{{SIZE}}{{UNIT}};',

                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                $widget . 'input_box_width',
                [
                    'label'      => esc_html__('Width', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector  => 'width:{{SIZE}}{{UNIT}};',

                    ],
                ]
            );
        }

        if (!in_array('border', $disable_controls)) {

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => $widget . 'input_box_border',
                    'label'    => esc_html__('Border', 'shopready-elementor-addon'),
                    'selector' => $selector,
                    'separator' => 'before',
                ]
            );
            $this->add_responsive_control(
                $widget . 'input_box_border_radius',
                [
                    'label'     => esc_html__('Border Radius', 'shopready-elementor-addon'),
                    'type'      => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        $selector   => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',

                    ],
                    'separator' => 'before',
                ]
            );
        }

        if (!in_array('box-shadow', $disable_controls)) {

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => $widget . 'input_box_shadow',
                    'selector' => $selector,
                ]
            );
        }

        if (!in_array('dimensions', $disable_controls)) {

            $this->add_responsive_control(
                $widget . 'input_box_padding',
                [
                    'label'      => esc_html__('Padding', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [

                        $selector   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_responsive_control(
                $widget . 'input_box_margin',
                [
                    'label'      => esc_html__('Margin', 'shopready-elementor-addon'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        $selector   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    ],
                    'separator' => 'before',
                ]
            );
        }

        $this->end_controls_tab();

        if ($hover_selector) {

            $this->start_controls_tab(
                $widget . '_hover_tabs',
                [
                    'label' => esc_html__('Tick', 'shopready-elementor-addon'),
                ]
            );


            $this->add_control(
                $widget . 'input_box_hover_border_color',
                [
                    'label'     => esc_html__('Color', 'shopready-elementor-addon'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        $hover_selector  => 'border-color:{{VALUE}};',

                    ],
                ]
            );


            $this->end_controls_tab();
        }
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*-----------------------------
            INPUT STYLE END
        -------------------------------*/
    }
}
