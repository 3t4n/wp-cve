<?php

/**
 * @package Shop Ready
 */

namespace Shop_Ready\base\elementor\style_controls\common;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

trait Widget_Common_Style
{

    public function element_before_psudocode($atts)
    {

        $atts_variable = shortcode_atts(
            [
                'title' => esc_html__('Separate', 'shopready-elementor-addon'),
                'slug' => '_meta_after_before_style',
                'element_name' => 'after__mangocube__',
                'selector' => '{{WRAPPER}} ',
                'selector_parent' => '',
                'condition' => '',
                'disable_controls' => []
            ],
            $atts
        );

        extract($atts_variable);

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize($slug);

        /*----------------------------
        ELEMENT__STYLE
        -----------------------------*/
        $tab_start_section_args = [
            'label' => $title,
            'tab' => Controls_Manager::TAB_STYLE,
        ];

        if (is_array($condition)) {
            $tab_start_section_args['condition'] = $condition;
        }

        $this->start_controls_section(
            $widget . '_style_after_before_section',
            $tab_start_section_args
        );


        $this->add_control(
            'psdu_' . $element_name . '_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    $selector => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $widget . 'main_section_' . $element_name . 'psudud_opacity_color',
            [
                'label' => esc_html__('Opacity', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],

                ],

                'selectors' => [
                    $selector_parent => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => $widget . 'main_section_' . $element_name . 'psudud_border_gp_',
                'label' => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => $selector,
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_r_size_transform',
            [
                'label' => esc_html__('Transform', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -360,
                        'max' => 360,
                        'step' => 5,
                    ],

                ],

                'selectors' => [
                    $selector => 'transform: translateY(-50%) rotate({{SIZE}}deg);',
                ],
            ]
        );

        if ($selector_parent != '') {
            $this->add_responsive_control(
                $widget . 'psudu_padding',
                [
                    'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        $selector_parent => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_psudu_size_width',
            [
                'label' => esc_html__('Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    $selector => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . 'psudud_size_height',
            [
                'label' => esc_html__('Height', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    $selector => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . 'psudud_position_left_',
            [
                'label' => esc_html__('Position Left', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2700,
                        'max' => 2700,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    $selector => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . 'psudud_position_top_',
            [
                'label' => esc_html__('Position Top', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2700,
                        'max' => 2700,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    $selector => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $widget . '_section__psudu_section_show_hide_' . $element_name . '_display',
            [
                'label' => esc_html__('Display', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'block' => esc_html__('Block', 'shopready-elementor-addon'),
                    'none' => esc_html__('None', 'shopready-elementor-addon'),
                    '' => esc_html__('inherit', 'shopready-elementor-addon'),
                ],
                'selectors' => [
                    $selector => 'display: {{VALUE}};',
                ],
            ]

        );

        $this->end_controls_section();
    }

    public function element_size($atts)
    {

        $atts_variable = shortcode_atts(
            [
                'title' => esc_html__('Size Style', 'shopready-elementor-addon'),
                'slug' => '_size_style',
                'element_name' => '__mangocube__',
                'selector' => '{{WRAPPER}} ',
                'condition' => '',
                'disable_controls' => []
            ],
            $atts
        );

        extract($atts_variable);

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize($slug);

        /*----------------------------
        ELEMENT__STYLE
        -----------------------------*/
        $tab_start_section_args = [
            'label' => $title,
            'tab' => Controls_Manager::TAB_STYLE,
        ];

        if (is_array($condition)) {
            $tab_start_section_args['condition'] = $condition;
        }

        $this->start_controls_section(
            $widget . '_style_section',
            $tab_start_section_args
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_r_size_width',
            [
                'label' => esc_html__('Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    $selector => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_r_size_max_width',
            [
                'label' => esc_html__('Max Width', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    $selector => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_r_size_height',
            [
                'label' => esc_html__('Height', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    $selector => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $widget . 'main_section_' . $element_name . '_r_size_max_height',
            [
                'label' => esc_html__('Max Height', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],

                'selectors' => [
                    $selector => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => $widget . 'size_border',
                'label' => esc_html__('Border', 'shopready-elementor-addon'),
                'selector' => $selector,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => $widget . 'main_section_' . $element_name . '_r_box_shadow',
                'label' => __('Box Shadow', 'shopready-elementor-addon'),
                'selector' => $selector,
            ]
        );

        // Radius
        $this->add_responsive_control(
            $widget . 'seze_radius',
            [
                'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function text_css($atts)
    {

        $atts_variable = shortcode_atts(
            [
                'title' => esc_html__('Text Style', 'shopready-elementor-addon'),
                'slug' => '_text_style',
                'element_name' => '__woo_ready__',
                'selector' => '{{WRAPPER}} ',
                'hover_selector' => '{{WRAPPER}} ',
                'condition' => '',
                'disable_controls' => []
            ],
            $atts
        );

        extract($atts_variable);

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize($slug);

        /*----------------------------
        ELEMENT__STYLE
        -----------------------------*/
        $tab_start_section_args = [
            'label' => $title,
            'tab' => Controls_Manager::TAB_STYLE,
        ];

        if (is_array($condition)) {
            $tab_start_section_args['condition'] = $condition;
        }

        $this->start_controls_section(
            $widget . '_style_section',
            $tab_start_section_args
        );

        $this->start_controls_tabs($widget . '_tabs_style');


        $this->start_controls_tab(
            $widget . '_normal_tab',
            [
                'label' => esc_html__('Normal', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            $widget . '_sr_ext_hook_id',
            [
                'label' => __('Extension', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'custom_' . $widget
            ]
        );

        do_action('custom_' . $widget, $this);

        // Typgraphy
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => $widget . '_typography',
                'selector' => $selector,
            ]
        );

        $this->add_control(
            $widget . '_text_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    $selector => 'color: {{VALUE}} !important;',

                ],
            ]
        );

        if (!in_array('text_shadow', $disable_controls)) {
            $this->add_group_control(
                \Elementor\Group_Control_Text_Shadow::get_type(),
                [
                    'name' => $widget . 'text_shadow_',
                    'label' => esc_html__('Text Shadow', 'shopready-elementor-addon'),
                    'selector' => $selector,
                ]
            );
        }

        if (!in_array('bg', $disable_controls)) {
            // Background
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => $widget . 'text_background',
                    'label' => esc_html__('Background', 'shopready-elementor-addon'),
                    'types' => ['classic', 'gradient', 'video'],
                    'selector' => $selector,
                ]
            );
        }

        if (!in_array('border', $disable_controls)) {
            // Border
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => $widget . '_border',
                    'label' => esc_html__('Border', 'shopready-elementor-addon'),
                    'selector' => $selector,
                ]
            );

            // Radius
            $this->add_responsive_control(
                $widget . '_radius',
                [
                    'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                ]
            );
        }

        if (!in_array('box-shadow', $disable_controls)) {
            // Shadow
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => $widget . 'normal_shadow',
                    'selector' => $selector,
                ]
            );
        }

        if (!in_array('dimensions', $disable_controls)) {

            // Margin
            $this->add_responsive_control(
                $widget . '_margin',
                [
                    'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                ]
            );

            // Padding
            $this->add_responsive_control(
                $widget . '_padding',
                [
                    'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    ],
                ]
            );
        }

        if ($hover_selector != false || $hover_selector != '') {
            $this->add_control(
                $widget . 'ele_box_transition',
                [
                    'label' => esc_html__('Transition', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0.1,
                            'max' => 3,
                            'step' => 0.1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 0.5,
                    ],
                    'selectors' => [
                        $selector => 'transition: {{SIZE}}s;',

                    ],
                ]
            );
        }


        $this->end_controls_tab();

        if ($hover_selector != false || $hover_selector != '') {

            $this->start_controls_tab(
                $widget . '_hover_tab',
                [
                    'label' => esc_html__('Hover', 'shopready-elementor-addon'),
                ]
            );


            //Hover Color
            $this->add_control(
                'hover_' . $element_name . '_color',
                [
                    'label' => esc_html__('Color', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        $hover_selector => 'color: {{VALUE}} !important;',

                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Text_Shadow::get_type(),
                [
                    'name' => $widget . 'text_shadow_hover_',
                    'label' => esc_html__('Text Shadow', 'shopready-elementor-addon'),
                    'selector' => $hover_selector,
                ]
            );

            if (!in_array('bg', $disable_controls)) {
                // Hover Background
                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'hover_' . $element_name . '_background',
                        'label' => esc_html__('Background', 'shopready-elementor-addon'),
                        'types' => ['classic', 'gradient'],
                        'selector' => $hover_selector,
                    ]
                );
            }

            if (!in_array('border', $disable_controls)) {
                // Border
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'hover_' . $element_name . '_border',
                        'label' => esc_html__('Border', 'shopready-elementor-addon'),
                        'selector' => $hover_selector,
                    ]
                );

                // Radius
                $this->add_responsive_control(
                    'hover_' . $element_name . '_radius',
                    [
                        'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', '%', 'em'],
                        'selectors' => [
                            $hover_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
            }

            if (!in_array('box-shadow', $disable_controls)) {
                // Shadow
                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'hover_' . $element_name . '_shadow',
                        'selector' => $hover_selector,
                    ]
                );
            }

            $this->end_controls_tab();
        }

        if (!in_array('position', $disable_controls)) {
            $this->start_controls_tab(
                $widget . '_position_tab',
                [
                    'label' => esc_html__('Position', 'shopready-elementor-addon'),
                ]
            );

            $this->add_responsive_control(
                $widget . '_section__' . $element_name . '_position_type',
                [
                    'label' => esc_html__('Position', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'fixed' => esc_html__('Fixed', 'shopready-elementor-addon'),
                        'absolute' => esc_html__('Absolute', 'shopready-elementor-addon'),
                        'relative' => esc_html__('Relative', 'shopready-elementor-addon'),
                        'sticky' => esc_html__('Sticky', 'shopready-elementor-addon'),
                        'static' => esc_html__('Static', 'shopready-elementor-addon'),
                        'inherit' => esc_html__('inherit', 'shopready-elementor-addon'),
                        '' => esc_html__('none', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'position: {{VALUE}};',
                    ],

                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_position_left',
                [
                    'label' => esc_html__('Position Left', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_top',
                [
                    'label' => esc_html__('Position Top', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_bottom',
                [
                    'label' => esc_html__('Position Bottom', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_right',
                [
                    'label' => esc_html__('Position Right', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->end_controls_tab();
        }

        if (!in_array('size', $disable_controls)) {

            $this->start_controls_tab(
                $widget . '_size_tab',
                [
                    'label' => esc_html__('Size', 'shopready-elementor-addon'),
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_itemdsd_el__width',
                [
                    'label' => esc_html__('Width', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'width: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_item_dsd_maxel__width',
                [
                    'label' => esc_html__('Max Width', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'max-width: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_item_errt_min_el__width',
                [
                    'label' => esc_html__('Min Width', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'min-width: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_item_errt_min_el__height',
                [
                    'label' => esc_html__('Height', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 500,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );


            $this->end_controls_tab();
        }

        do_action('custom_tab_' . $widget, $this);

        // hover_select check end
        $this->end_controls_tabs();

        if (!in_array('display', $disable_controls)) {
            $this->add_responsive_control(
                $widget . '_section___section_show_hide_' . $element_name . '_display',
                [
                    'label' => esc_html__('Display', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'flex' => esc_html__('Flex', 'shopready-elementor-addon'),
                        'inline-flex' => esc_html__('Inline Flex', 'shopready-elementor-addon'),
                        'block' => esc_html__('Block', 'shopready-elementor-addon'),
                        'inline-block' => esc_html__('Inline Block', 'shopready-elementor-addon'),
                        'grid' => esc_html__('Grid', 'shopready-elementor-addon'),
                        'none' => esc_html__('None', 'shopready-elementor-addon'),
                        '' => esc_html__('Default', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'display: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . '_section___section_flex_direction_' . $element_name . '_display',
                [
                    'label' => esc_html__('Flex Direction', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'column' => esc_html__('Column', 'shopready-elementor-addon'),
                        'row' => esc_html__('Row', 'shopready-elementor-addon'),
                        'column-reverse' => esc_html__('Column Reverse', 'shopready-elementor-addon'),
                        'row-reverse' => esc_html__('Row Reverse', 'shopready-elementor-addon'),
                        'revert' => esc_html__('Revert', 'shopready-elementor-addon'),
                        'none' => esc_html__('None', 'shopready-elementor-addon'),
                        '' => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'flex-direction: {{VALUE}};',
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                ]

            );

            $this->add_responsive_control(
                $widget . 'txt_wr_section_' . $element_name . '_flex_gap',
                [
                    'label' => esc_html__('Gap', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors' => [
                        $selector => 'gap: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . '_section__s_section_flex_wrap_' . $element_name . '_display',
                [
                    'label' => esc_html__('Flex Wrap', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'wrap' => esc_html__('Wrap', 'shopready-elementor-addon'),
                        'wrap-reverse' => esc_html__('Wrap Reverse', 'shopready-elementor-addon'),
                        'nowrap' => esc_html__('No Wrap', 'shopready-elementor-addon'),
                        'unset' => esc_html__('Unset', 'shopready-elementor-addon'),
                        'normal' => esc_html__('None', 'shopready-elementor-addon'),
                        'inherit' => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'flex-wrap: {{VALUE}};',
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                ]

            );

            $this->add_responsive_control(
                $widget . '_alignment',
                [
                    'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [

                        'left' => [

                            'title' => esc_html__('Left', 'shopready-elementor-addon'),
                            'icon' => 'eicon-text-align-left',

                        ],
                        'center' => [

                            'title' => esc_html__('Center', 'shopready-elementor-addon'),
                            'icon' => 'eicon-text-align-center',

                        ],
                        'right' => [

                            'title' => esc_html__('Right', 'shopready-elementor-addon'),
                            'icon' => 'eicon-text-align-right',

                        ],

                        'justify' => [

                            'title' => esc_html__('Justified', 'shopready-elementor-addon'),
                            'icon' => 'eicon-text-align-justify',

                        ],
                    ],

                    'selectors' => [
                        $selector => 'text-align: {{VALUE}};',
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['block', 'inline-block']],
                ]
            ); //Responsive control end

            $this->add_responsive_control(
                $widget . '_section_align_sessction_e_' . $element_name . '_flex_align',
                [
                    'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'flex-start' => esc_html__('Left', 'shopready-elementor-addon'),
                        'flex-end' => esc_html__('Right', 'shopready-elementor-addon'),
                        'center' => esc_html__('Center', 'shopready-elementor-addon'),
                        'space-around' => esc_html__('Space Around', 'shopready-elementor-addon'),
                        'space-between' => esc_html__('Space Between', 'shopready-elementor-addon'),
                        'space-evenly' => esc_html__('Space Evenly', 'shopready-elementor-addon'),
                        '' => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],

                    'selectors' => [
                        $selector => 'justify-content: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . 'er_section_align_items_ssection_e_' . $element_name . '_flex_align',
                [
                    'label' => esc_html__('Align Items', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'flex-start' => esc_html__('Left', 'shopready-elementor-addon'),
                        'flex-end' => esc_html__('Right', 'shopready-elementor-addon'),
                        'center' => esc_html__('Center', 'shopready-elementor-addon'),
                        'baseline' => esc_html__('Baseline', 'shopready-elementor-addon'),
                        '' => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],

                    'selectors' => [
                        $selector => 'align-items: {{VALUE}};',
                    ],
                ]

            );
        }


        $this->end_controls_section();
        /*----------------------------
    ELEMENT__STYLE END
    -----------------------------*/
    }

    public function text_wrapper_css($atts)
    {

        $atts_variable = shortcode_atts(
            [
                'title' => esc_html__('Text Style', 'shopready-elementor-addon'),
                'slug' => '_text_style',
                'element_name' => '__mangocube__',
                'selector' => '{{WRAPPER}} ',
                'hover_selector' => '{{WRAPPER}} ',
                'condition' => '',
                'disable_controls' => []
            ],
            $atts
        );

        extract($atts_variable);

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize($slug);

        /*----------------------------
        ELEMENT__STYLE
        -----------------------------*/
        $tab_start_section_args = [
            'label' => $title,
            'tab' => Controls_Manager::TAB_STYLE,
        ];

        if (is_array($condition)) {
            $tab_start_section_args['condition'] = $condition;
        }

        $this->start_controls_section(
            $widget . '_style_section',
            $tab_start_section_args
        );

        $this->start_controls_tabs($widget . '_tabs_style');

        do_action('custom_tab_' . $widget, $this);
        $this->start_controls_tab(
            $widget . '_normal_tab',
            [
                'label' => esc_html__('Normal', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            $widget . '_sr_extension_hook_id',
            [
                'label' => __('Extension Hook id', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'custom_' . $widget
            ]
        );

        do_action('custom_' . $widget, $this);

        // Typgraphy
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => $widget . '_typography',
                'selector' => $selector,
            ]
        );


        // Icon Color
        $this->add_control(
            $widget . '_text_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    $selector => 'color: {{VALUE}} !important;',

                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => $widget . 'text_shadow_',
                'label' => esc_html__('Text Shadow', 'shopready-elementor-addon'),
                'selector' => $selector,
            ]
        );

        if (!in_array('bg', $disable_controls)) {

            // Background
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => $widget . 'text_background',
                    'label' => esc_html__('Background', 'shopready-elementor-addon'),
                    'types' => ['classic', 'gradient', 'video'],
                    'selector' => $selector,
                ]
            );
        }


        if (!in_array('border', $disable_controls)) {

            // Border
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => $widget . '_border',
                    'label' => esc_html__('Border', 'shopready-elementor-addon'),
                    'selector' => $selector,
                ]
            );

            // Radius
            $this->add_responsive_control(
                $widget . '_radius',
                [
                    'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }

        if (!in_array('box-shadow', $disable_controls)) {
            // Shadow
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => $widget . 'normal_shadow',
                    'selector' => $selector,
                ]
            );
        }

        if (!in_array('dimensions', $disable_controls)) {
            // Margin
            $this->add_responsive_control(
                $widget . '_margin',
                [
                    'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Padding
            $this->add_responsive_control(
                $widget . '_padding',
                [
                    'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }


        $this->add_control(
            $widget . 'ele_box_transition',
            [
                'label' => esc_html__('Transition', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0.5,
                ],
                'selectors' => [
                    $selector => 'transition: {{SIZE}}s;',

                ],
            ]
        );

        if (!in_array('size', $disable_controls)) {

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_item_el__width',
                [
                    'label' => esc_html__('Width', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'width: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_item__maxel__width',
                [
                    'label' => esc_html__('Max Width', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'max-width: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_item__min_el__width',
                [
                    'label' => esc_html__('Min Width', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'min-width: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );
        }

        $this->end_controls_tab();
        if ($hover_selector != false || $hover_selector != '') {

            $this->start_controls_tab(
                $widget . '_hover_tab',
                [
                    'label' => esc_html__('Hover', 'shopready-elementor-addon'),
                ]
            );

            //Hover Color
            $this->add_control(
                'hover_' . $element_name . '_color',
                [
                    'label' => esc_html__('Color', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        $hover_selector => 'color: {{VALUE}} !important;',

                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Text_Shadow::get_type(),
                [
                    'name' => $widget . 'text_shadow_hover_',
                    'label' => esc_html__('Text Shadow', 'shopready-elementor-addon'),
                    'selector' => $hover_selector,
                ]
            );


            if (!in_array('bg', $disable_controls)) {

                // Hover Background
                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'hover_' . $element_name . '_background',
                        'label' => esc_html__('Background', 'shopready-elementor-addon'),
                        'types' => ['classic', 'gradient'],
                        'selector' => $hover_selector,
                    ]
                );
            }


            if (!in_array('border', $disable_controls)) {

                // Border
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'hover_' . $element_name . '_border',
                        'label' => esc_html__('Border', 'shopready-elementor-addon'),
                        'selector' => $hover_selector,
                    ]
                );

                // Radius
                $this->add_responsive_control(
                    'hover_' . $element_name . '_radius',
                    [
                        'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', '%', 'em'],
                        'selectors' => [
                            $hover_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
            }

            if (!in_array('box-shadow', $disable_controls)) {

                // Shadow
                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'hover_' . $element_name . '_shadow',
                        'selector' => $hover_selector,
                    ]
                );


                $this->end_controls_tab();
            } // hover_select check end
        }

        $this->end_controls_tabs();

        if (!in_array('display', $disable_controls)) {

            $this->add_responsive_control(
                $widget . '_section___section_show_hide_' . $element_name . '_display',
                [
                    'label' => esc_html__('Layout', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'flex' => esc_html__('Flex Layout', 'shopready-elementor-addon'),
                        'inline-flex' => esc_html__('Inline Flex Layout', 'shopready-elementor-addon'),
                        'block' => esc_html__('Block layout', 'shopready-elementor-addon'),
                        'inline-block' => esc_html__('Inline Layout', 'shopready-elementor-addon'),
                        'grid' => esc_html__('Grid layout', 'shopready-elementor-addon'),
                        'grid' => esc_html__('Flow Layout', 'shopready-elementor-addon'),
                        'none' => esc_html__('Hide', 'shopready-elementor-addon'),
                        '' => esc_html__('Default', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'display: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . '_section___section_flex_direction_' . $element_name . '_display',
                [
                    'label' => esc_html__('Flex Direction', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'column' => esc_html__('Column', 'shopready-elementor-addon'),
                        'row' => esc_html__('Row', 'shopready-elementor-addon'),
                        'column-reverse' => esc_html__('Column Reverse', 'shopready-elementor-addon'),
                        'row-reverse' => esc_html__('Row Reverse', 'shopready-elementor-addon'),
                        'revert' => esc_html__('Revert', 'shopready-elementor-addon'),
                        'none' => esc_html__('None', 'shopready-elementor-addon'),
                        '' => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'flex-direction: {{VALUE}};',
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                ]

            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_basis',
                [
                    'label' => esc_html__('Item Width', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 800,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],

                    ],

                    'selectors' => [
                        $selector => 'flex-basis: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_grow',
                [
                    'label' => esc_html__('Item Grow', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors' => [
                        $selector => 'flex-grow: {{SIZE}}',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_shrink',
                [
                    'label' => esc_html__('Item Shrink', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors' => [
                        $selector => 'flex-shrink: {{SIZE}}',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_order',
                [
                    'label' => esc_html__('Item Order', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors' => [
                        $selector => 'order: {{SIZE}}',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'text_section_' . $element_name . '_flexs_gap',
                [
                    'label' => esc_html__('Gap', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors' => [
                        $selector => 'gap: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . '_section__s_section_flexr_wrap_' . $element_name . '_display',
                [
                    'label' => esc_html__('Flex Wrap', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'wrap' => esc_html__('Wrap', 'shopready-elementor-addon'),
                        'wrap-reverse' => esc_html__('Wrap Reverse', 'shopready-elementor-addon'),
                        'nowrap' => esc_html__('No Wrap', 'shopready-elementor-addon'),
                        'unset' => esc_html__('Unset', 'shopready-elementor-addon'),
                        'normal' => esc_html__('None', 'shopready-elementor-addon'),
                        'inherit' => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'flex-wrap: {{VALUE}};',
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                ]

            );

            $this->add_responsive_control(
                $widget . '_section_align_sessctionr_e_' . $element_name . '_flex_align',
                [
                    'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'flex-start' => esc_html__('Left', 'shopready-elementor-addon'),
                        'flex-end' => esc_html__('Right', 'shopready-elementor-addon'),
                        'center' => esc_html__('Center', 'shopready-elementor-addon'),
                        'space-around' => esc_html__('Space Around', 'shopready-elementor-addon'),
                        'space-between' => esc_html__('Space Between', 'shopready-elementor-addon'),
                        'space-evenly' => esc_html__('Space Evenly', 'shopready-elementor-addon'),
                        '' => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],

                    'selectors' => [
                        $selector => 'justify-content: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . 'er_section_align_items_rssection_e_' . $element_name . '_flex_align',
                [
                    'label' => esc_html__('Align Items', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'flex-start' => esc_html__('Left', 'shopready-elementor-addon'),
                        'flex-end' => esc_html__('Right', 'shopready-elementor-addon'),
                        'center' => esc_html__('Center', 'shopready-elementor-addon'),
                        'baseline' => esc_html__('Baseline', 'shopready-elementor-addon'),
                        '' => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],

                    'selectors' => [
                        $selector => 'align-items: {{VALUE}};',
                    ],
                ]

            );
        }

        if (!in_array('position', $disable_controls)) {

            $this->add_control(
                $widget . '_section___section_popover_' . $element_name . '_position',
                [
                    'label' => esc_html__('Position', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => esc_html__('Default', 'shopready-elementor-addon'),
                    'label_on' => esc_html__('Custom', 'shopready-elementor-addon'),
                    'return_value' => 'yes',
                ]
            );

            $this->start_popover();
            $this->add_responsive_control(
                $widget . '_section__' . $element_name . '_position_type',
                [
                    'label' => esc_html__('Position', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'fixed' => esc_html__('Fixed', 'shopready-elementor-addon'),
                        'absolute' => esc_html__('Absolute', 'shopready-elementor-addon'),
                        'relative' => esc_html__('Relative', 'shopready-elementor-addon'),
                        'sticky' => esc_html__('Sticky', 'shopready-elementor-addon'),
                        'static' => esc_html__('Static', 'shopready-elementor-addon'),
                        'inherit' => esc_html__('inherit', 'shopready-elementor-addon'),
                        '' => esc_html__('none', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'position: {{VALUE}};',
                    ],

                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_position_left',
                [
                    'label' => esc_html__('Position Left', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_top',
                [
                    'label' => esc_html__('Position Top', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_bottom',
                [
                    'label' => esc_html__('Position Bottom', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_r_position_right',
                [
                    'label' => esc_html__('Position Right', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],

                    'selectors' => [
                        $selector => 'right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->end_popover();
        }

        $this->end_controls_section();
        /*----------------------------
    ELEMENT__STYLE END
    -----------------------------*/
    }
    public function text_minimum_css($atts)
    {

        $atts_variable = shortcode_atts(
            [
                'title' => esc_html__('Text Style', 'shopready-elementor-addon'),
                'slug' => '_text_style',
                'element_name' => '__mangocube__',
                'selector' => '{{WRAPPER}} ',
                'hover_selector' => '{{WRAPPER}} ',
                'condition' => '',
                'disable_controls' => [],
                'tab' => Controls_Manager::TAB_STYLE,
            ],
            $atts
        );

        extract($atts_variable);

        $widget = $this->get_name() . '_' . shop_ready_heading_camelize($slug);

        /*----------------------------
        ELEMENT__STYLE
        -----------------------------*/

        $tab_start_section_args = [
            'label' => $title,
            'tab' => $tab,
        ];

        if (is_array($condition)) {
            $tab_start_section_args['condition'] = $condition;
        }

        $this->start_controls_section(
            $widget . '_style_section',
            $tab_start_section_args
        );

        $this->start_controls_tabs($widget . '_tabs_style');

        do_action('custom_tab_' . $widget, $this);
        $this->start_controls_tab(
            $widget . '_normal_tab',
            [
                'label' => esc_html__('Normal', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            $widget . '_sr_extension_hook_id',
            [
                'label' => __('Extension Hook id', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'custom_' . $widget
            ]
        );

        do_action('custom_' . $widget, $this);
        // Typgraphy
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => $widget . '_stypography',
                'selector' => $selector,
            ]
        );

        //  Color
        $this->add_control(
            $widget . '_text_color',
            [
                'label' => esc_html__('Color', 'shopready-elementor-addon'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    $selector => 'color: {{VALUE}} !important;',

                ],
            ]
        );

        if (!in_array('bg', $disable_controls)) {
            //  Background
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'gens_' . $element_name . '_background',
                    'label' => esc_html__('Background', 'shopready-elementor-addon'),
                    'types' => ['classic', 'gradient'],
                    'selector' => $selector,
                ]
            );
        }

        if (!in_array('border', $disable_controls)) {
            // Border
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'gens_' . $element_name . '_border',
                    'label' => esc_html__('Border', 'shopready-elementor-addon'),
                    'selector' => $selector,
                ]
            );

            $this->add_responsive_control(
                $widget . '_radius',
                [
                    'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }

        if (!in_array('dimensions', $disable_controls)) {

            // Margin
            $this->add_responsive_control(
                $widget . '_smargin',
                [
                    'label' => esc_html__('Margin', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Padding
            $this->add_responsive_control(
                $widget . '_padding',
                [
                    'label' => esc_html__('Padding', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }

        if (!in_array('display', $disable_controls)) {

            $this->add_responsive_control(
                $widget . '_section___section_show_hide_' . $element_name . '_display',
                [
                    'label' => esc_html__('Display', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'flex' => esc_html__('Flex', 'shopready-elementor-addon'),
                        'inline-flex' => esc_html__('Inline Flex', 'shopready-elementor-addon'),
                        'block' => esc_html__('Block', 'shopready-elementor-addon'),
                        'inline-block' => esc_html__('Inline Block', 'shopready-elementor-addon'),
                        'none' => esc_html__('None', 'shopready-elementor-addon'),
                        '' => esc_html__('Default', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'display: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . '_section___section_flex_direction_' . $element_name . '_display',
                [
                    'label' => esc_html__('Flex Direction', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'column' => esc_html__('Column', 'shopready-elementor-addon'),
                        'row' => esc_html__('Row', 'shopready-elementor-addon'),
                        'column-reverse' => esc_html__('Column Reverse', 'shopready-elementor-addon'),
                        'row-reverse' => esc_html__('Row Reverse', 'shopready-elementor-addon'),
                        'revert' => esc_html__('Revert', 'shopready-elementor-addon'),
                        'none' => esc_html__('None', 'shopready-elementor-addon'),
                        '' => esc_html__('inherit', 'shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'flex-direction: {{VALUE}};',
                    ],
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex']],
                ]

            );
        }

        $this->add_control(
            $widget . 'ele_box_transition',
            [
                'label' => esc_html__('Transition', 'shopready-elementor-addon'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    $selector => 'transition: {{SIZE}}s;',

                ],
            ]
        );

        $this->end_controls_tab();
        // Hover selector
        if ($hover_selector != false || $hover_selector != '') {

            $this->start_controls_tab(
                $widget . '_hover_tab',
                [
                    'label' => esc_html__('Hover', 'shopready-elementor-addon'),
                ]
            );

            $this->add_control(
                $widget . '_sr_extension_hover_hook_id',
                [
                    'label' => __('Extension Hook id', 'shopready-elementor-addon'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'custom_hover_' . $widget
                ]
            );

            do_action('custom_hover_' . $widget, $this);

            // Icon Color
            $this->add_control(
                $widget . 'hover_text_color',
                [
                    'label' => esc_html__('Color', 'shopready-elementor-addon'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        $hover_selector => 'color: {{VALUE}} !important;',

                    ],
                ]
            );

            if (!in_array('bg', $disable_controls)) {
                // Hover Background
                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name' => 'hovers_' . $element_name . '_background',
                        'label' => esc_html__('Background', 'shopready-elementor-addon'),
                        'types' => ['classic', 'gradient'],
                        'selector' => $hover_selector,
                    ]
                );
            }

            if (!in_array('border', $disable_controls)) {
                // Border
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'hovers_' . $element_name . '_border',
                        'label' => esc_html__('Border', 'shopready-elementor-addon'),
                        'selector' => $hover_selector,
                    ]
                );
            }

            $this->end_controls_tab();
        } // hover_select check end
        $this->end_controls_tabs();

        $this->end_controls_section();
        /*----------------------------
    ELEMENT__STYLE END
    -----------------------------*/
    }
}