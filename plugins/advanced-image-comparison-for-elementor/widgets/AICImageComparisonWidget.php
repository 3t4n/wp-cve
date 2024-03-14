<?php

namespace AIC\Classes\Widgets;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Utils;
use \Elementor\Widget_Base;

class AICImageComparisonWidget extends Widget_Base
{
    
    public function get_name() 
    {
        return "aic-image-comparison";
    }

    public function get_title() 
    {
        return __( 'Advanced Image Comparison', 'advanced-image-comparison-for-elementor' );
    }

    public function get_icon() 
    {
        return "eicon-image-before-after";
    }

    public function get_categories()
    {
       return ['basic'];
    }

    protected function register_controls()
    {
        
        /**
         * Image Comparison 
        */
        $this->start_controls_section(
            'aic_image_comparison',
            [
                'label' => esc_html__( 'Image Comparison', 'advanced-image-comparison-for-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
            $this->add_control(
                'aic_before_image_label',
                [
                    'label'       => esc_html__( 'Label Before', 'advanced-image-comparison-for-elementor' ),
                    'type'        => Controls_Manager::TEXT,
                    'default'     => 'Before',
                    'title'       => esc_html__( 'before image label', 'advanced-image-comparison-for-elementor' ),
                    'dynamic' => [
                        'active' => true,
                    ]
                ]
            );
            $this->add_control(
                'aic_before_image_alt',
                [
                    'label'       => esc_html__( 'Before Image Alt Tag', 'advanced-image-comparison-for-elementor' ),
                    'type'        => Controls_Manager::TEXT,
                    // 'label_block' => true,
                    'default'     => 'Before image alt',
                    'placeholder' => __( 'Add alt tag', 'advanced-image-comparison-for-elementor' ),
                    'title'       => esc_html__( 'Add before image Alt Tag', 'advanced-image-comparison-for-elementor' ),
                    'dynamic' => [
                        'active' => true,
                    ]
                ]
            );
            $this->add_control(
                'aic_before_image',
                [
                    'label'   => esc_html__( 'Choose Before Image', 'advanced-image-comparison-for-elementor' ),
                    'type'    => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'dynamic' => [
                        'active' => true,
                    ]
                ]
            );
            $this->add_control(
                'aic_after_image_label',
                [
                    'label'       => esc_html__( 'Label After', 'advanced-image-comparison-for-elementor' ),
                    'type'        => Controls_Manager::TEXT,
                    // 'label_block' => true,
                    'default'     => 'After',
                    'title'       => esc_html__( 'After image label', 'advanced-image-comparison-for-elementor' ),
                    'separator'  => 'before',
                    'dynamic' => [
                        'active' => true,
                    ]
                ]
            );
            $this->add_control(
                'aic_after_image_alt',
                [
                    'label'       => esc_html__( 'After Image Alt Tag', 'advanced-image-comparison-for-elementor' ),
                    'type'        => Controls_Manager::TEXT,
                    // 'label_block' => true,
                    'default'     => 'After image alt',
                    'placeholder' => __( 'Add alt tag', 'advanced-image-comparison-for-elementor' ),
                    'title'       => esc_html__( 'After image Alt Tag', 'advanced-image-comparison-for-elementor' ),
                    'dynamic' => [
                        'active' => true,
                    ]
                ]
            );
            $this->add_control(
                'aic_after_image',
                [
                    'label'   => esc_html__( 'Choose After Image', 'advanced-image-comparison-for-elementor' ),
                    'type'    => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'dynamic' => [
                        'active' => true,
                    ]
                ]
            );
        $this->end_controls_section();
        
        /***
        * Addintional Options
        */
        $this->start_controls_section(
            'aic_image_additional_options',
            [
                'label' => esc_html__( 'Additional Options', 'advanced-image-comparison-for-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
            $this->add_control(
                'aic_image_visibility',
                [
                    'label'      => esc_html__( 'Image Visibility', 'advanced-image-comparison-for-elementor' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['%', ''],
                    'range'      => ['%' => ['min' => 0, 'max' => 100]],
                    'default'    => ['size' => 50, 'unit' => '%'],
                ]
            );
            $this->add_control(
                'aic_image_orientation',
                [
                    'label'   => esc_html__( 'Layout', 'advanced-image-comparison-for-elementor' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => [
                        'horizontal' => esc_html__( 'Horizontal', 'advanced-image-comparison-for-elementor' ),
                        'vertical'   => esc_html__( 'Vertical', 'advanced-image-comparison-for-elementor' ),
                    ],
                    'default' => 'horizontal',  
                ]
            );
            $this->add_control(
                'aic_image_click_to_move',
                [
                    'label'     => esc_html__( 'Move Slider On Click', 'advanced-image-comparison-for-elementor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_on'  => __( 'yes', 'advanced-image-comparison-for-elementor' ),
                    'label_off' => __( 'no', 'advanced-image-comparison-for-elementor' ),
                    'default'   => 'no',
                ]
            );
            $this->add_control(
                'aic_image_move_slider_on_hover',
                [
                    'label'     => esc_html__( 'Move Slider On Hover', 'advanced-image-comparison-for-elementor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_on'  => __( 'yes', 'advanced-image-comparison-for-elementor' ),
                    'label_off' => __( 'no', 'advanced-image-comparison-for-elementor' ),
                    'default'   => 'yes',
                ]
            );
            $this->add_control(
                'aic_image_overlay',
                [
                    'label'     => esc_html__( 'Image Overlay ?', 'advanced-image-comparison-for-elementor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_on'  => __( 'yes', 'advanced-image-comparison-for-elementor' ),
                    'label_off' => __( 'no', 'advanced-image-comparison-for-elementor' ),
                    'default'   => 'no',
                ]
            );
        $this->end_controls_section();
        
        /**
         * Image Container
        */
        $this->start_controls_section(
            'aic_image_container_section',
            [
                'label' => esc_html__( 'Image Container', 'advanced-image-comparison-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
            $this->add_control(
                'aic_image_container_width',
                [
                    'label'     => esc_html__( 'Set max width for the container?', 'advanced-image-comparison-for-elementor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'label_on'  => __( 'yes', 'advanced-image-comparison-for-elementor' ),
                    'label_off' => __( 'no', 'advanced-image-comparison-for-elementor' ),
                    'default'   => 'yes',
                ]
            );
            $this->add_responsive_control(
                'aic_image_container_width_value',
                [
                    'label'      => __( 'Container Max Width', 'advanced-image-comparison-for-elementor' ),
                    'type'       => Controls_Manager::SLIDER,
                    'default'    => [
                        'size' => 80,
                        'unit' => '%',
                    ],
                    'size_units' => ['%', 'px'],
                    'range'      => [
                        '%'  => [
                            'min' => 1,
                            'max' => 100,
                        ],
                        'px' => [
                            'min' => 1,
                            'max' => 1000,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .aic-image-container' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'  => [
                        'aic_image_container_width' => 'yes',
                    ]
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => 'aic_image_comparisan_border',
                    'selector' => '{{WRAPPER}} .aic-image-container',
                ]
            );
            $this->add_control(
                'aic_image_comparisan_border_radius',
                [
                    'label'     => esc_html__( 'Border Radius', 'advanced-image-comparison-for-elementor' ),
                    'type'      =>  Controls_Manager::SLIDER,
                    'size_units' => ['px', ''],
                    'range'     => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .aic-image-container' => 'border-radius: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
        $this->end_controls_section();
        
        /**
         * Label
        */
        $this->start_controls_section(
            'aic_label_section',
            [
                'label' => __( 'Label', 'advanced-image-comparison-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'aic_label_align',
                [
                    'label'      => __( 'Align', 'advanced-image-comparison-for-elementor' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'max' => 400,
                        ],
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .twentytwenty-horizontal .aic-image-container .twentytwenty-before-label:before' => 'left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .twentytwenty-horizontal .aic-image-container .twentytwenty-after-label:before'  => 'right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .twentytwenty-vertical .aic-image-container .twentytwenty-before-label:before' => 'top: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .twentytwenty-vertical .aic-image-container .twentytwenty-after-label:before'  => 'bottom: {{SIZE}}{{UNIT}};'
                    ]
                ]
            );
            $this->start_controls_tabs( 'aic_tabs_label' );
                $this->start_controls_tab(
                    'aic_tab_label_before',
                    [
                        'label' => __( 'Before', 'advanced-image-comparison-for-elementor' ),
                    ]
                );
                    $this->add_control(
                        'aic_label_text_color_before',
                        [
                            'label'     => __( 'Text Color', 'advanced-image-comparison-for-elementor' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .aic-image-container .twentytwenty-before-label:before' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_control(
                        'aic_label_bg_color_before',
                        [
                            'label'     => __( 'Background Color', 'advanced-image-comparison-for-elementor' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .aic-image-container .twentytwenty-before-label:before' => 'background: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'        => 'aic_label_border',
                            'label'       => __( 'Border', 'advanced-image-comparison-for-elementor' ),
                            'placeholder' => '1px',
                            'default'     => '1px',
                            'selector'    => '{{WRAPPER}} .aic-image-container .twentytwenty-before-label:before',
                        ]
                    );
                    $this->add_control(
                        'aic_label_border_radius',
                        [
                            'label'      => __( 'Border Radius', 'advanced-image-comparison-for-elementor' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', '%'],
                            'selectors'  => [
                                '{{WRAPPER}} .aic-image-container .twentytwenty-before-label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                $this->end_controls_tab();
                
                $this->start_controls_tab(
                    'aic_tab_label_after',
                    [
                        'label' => __( 'After', 'advanced-image-comparison-for-elementor' ),
                    ]
                );
                    $this->add_control(
                        'aic_label_text_color_after',
                        [
                            'label'     => __( 'Text Color', 'advanced-image-comparison-for-elementor' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .aic-image-container .twentytwenty-after-label:before' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_control(
                        'aic_label_bg_color_after',
                        [
                            'label'     => __( 'Background Color', 'advanced-image-comparison-for-elementor' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .aic-image-container .twentytwenty-after-label:before' => 'background: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'        => 'label_border_after',
                            'label'       => __( 'Border', 'advanced-image-comparison-for-elementor' ),
                            'placeholder' => '1px',
                            'default'     => '1px',
                            'selector'    => '{{WRAPPER}} .aic-image-container .twentytwenty-after-label:before',
                        ]
                    );
                    $this->add_control(
                        'aic_label_border_radius_after',
                        [
                            'label'      => __( 'Border Radius', 'advanced-image-comparison-for-elementor' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', '%'],
                            'selectors'  => [
                                '{{WRAPPER}} .aic-image-container .twentytwenty-after-label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ]
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'aic_label_typography',
                    'label'     => __( 'Typography', 'advanced-image-comparison-for-elementor' ),
                    'selector'  => '{{WRAPPER}} .aic-image-container .twentytwenty-before-label:before, {{WRAPPER}} .aic-image-container .twentytwenty-after-label:before',
                ]
            );
            $this->add_responsive_control(
                'aic_label_padding',
                [
                    'label'      => __( 'Padding', 'advanced-image-comparison-for-elementor' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px','%'],
                    'selectors'  => [
                        '{{WRAPPER}} .aic-image-container .twentytwenty-before-label:before, {{WRAPPER}} .aic-image-container .twentytwenty-after-label:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator'  => 'before'
                ]
            );
        $this->end_controls_section();

        /**
         * Container Overlay
        */
        $this->start_controls_section(
            'aic_overlay_section',
            [
                'label'     => __( 'Overlay', 'advanced-image-comparison-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'aic_image_overlay' => 'yes',
                ],
            ]
        );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'aic_image_comparison_overlay_background',
                    'label'    => __( 'Background', 'advanced-image-comparison-for-elementor' ),
                    'types'    => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .aic-image-container .twentytwenty-overlay:hover',
                ]
            );
        $this->end_controls_section();

        /**
         * Style Tab: Divider
         */
        $this->start_controls_section(
            'aic_divider_section',
            [
                'label' => __( 'Divider', 'advanced-image-comparison-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'aic_divider_color',
                [
                    'label'     => __( 'Color', 'advanced-image-comparison-for-elementor' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .twentytwenty-horizontal .aic-image-container .twentytwenty-handle:before, 
                        {{WRAPPER}} .twentytwenty-horizontal .aic-image-container .twentytwenty-handle:after, 
                        {{WRAPPER}} .twentytwenty-vertical .aic-image-container .twentytwenty-handle:before, 
                        {{WRAPPER}} .twentytwenty-vertical .aic-image-container .twentytwenty-handle:after' => 'background: {{VALUE}}',
                    ],
                ]
            );
            $this->add_responsive_control(
                'aic_divider_width',
                [
                    'label'          => __( 'Width', 'advanced-image-comparison-for-elementor' ),
                    'type'           => Controls_Manager::SLIDER,
                    'default'        => [
                        'size' => 3,
                        'unit' => 'px',
                    ],
                    'size_units'     => ['px', '%'],
                    'range'          => [
                        'px' => [
                            'max' => 20,
                        ],
                    ],
                    'tablet_default' => [
                        'unit' => 'px',
                    ],
                    'mobile_default' => [
                        'unit' => 'px',
                    ],
                    'selectors'      => [
                        '{{WRAPPER}} .twentytwenty-horizontal .aic-image-container .twentytwenty-handle:before, 
                        {{WRAPPER}} .twentytwenty-horizontal .aic-image-container .twentytwenty-handle:after' => 'width: {{SIZE}}{{UNIT}}; margin-left: calc(-{{SIZE}}{{UNIT}}/2);',
                    ],
                    'condition'  => [
                        'aic_image_orientation' => 'horizontal',
                    ],
                ]
            );
        $this->end_controls_section();

        /**
        *  Handle
        */
        $this->start_controls_section(
            'aic_handle_section',
            [
                'label' => __( 'Handle', 'advanced-image-comparison-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs( 'aic_tabs_handle' );
                $this->start_controls_tab(
                    'aic_tab_handle_normal',
                    [
                        'label' => __( 'Normal', 'advanced-image-comparison-for-elementor' ),
                    ]
                );
                    $this->add_control(
                        'aic_handle_icon_color',
                        [
                            'label'     => __( 'Icon Color', 'advanced-image-comparison-for-elementor' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .aic-image-container .twentytwenty-left-arrow'  => 'border-right-color: {{VALUE}}',
                                '{{WRAPPER}} .aic-image-container .twentytwenty-right-arrow' => 'border-left-color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'     => 'aic_handle_background',
                            'types'    => ['classic', 'gradient'],
                            'selector' => '{{WRAPPER}} .aic-image-container .twentytwenty-handle',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name'        => 'aic_handle_border',
                            'label'       => __( 'Border', 'advanced-image-comparison-for-elementor' ),
                            'placeholder' => '1px',
                            'default'     => '1px',
                            'selector'    => '{{WRAPPER}} .aic-image-container .twentytwenty-handle',
                            'separator'   => 'before',
                        ]
                    );
                    $this->add_control(
                        'aic_handle_border_radius',
                        [
                            'label'      => __( 'Border Radius', 'advanced-image-comparison-for-elementor' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', '%'],
                            'selectors'  => [
                                '{{WRAPPER}} .aic-image-container .twentytwenty-handle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => 'aic_handle_box_shadow',
                            'selector' => '{{WRAPPER}} .aic-image-container .twentytwenty-handle',
                        ]
                    );
                $this->end_controls_tab();
                
                $this->start_controls_tab(
                    'aic_tab_handle_hover',
                    [
                        'label' => __( 'Hover', 'advanced-image-comparison-for-elementor' ),
                    ]
                );
                    $this->add_control(
                        'aic_handle_icon_color_hover',
                        [
                            'label'     => __( 'Icon Color', 'advanced-image-comparison-for-elementor' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .aic-image-container .twentytwenty-handle:hover .twentytwenty-left-arrow'  => 'border-right-color: {{VALUE}}',
                                '{{WRAPPER}} .aic-image-container .twentytwenty-handle:hover .twentytwenty-right-arrow' => 'border-left-color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'     => 'aic_handle_background_hover',
                            'types'    => ['classic', 'gradient'],
                            'selector' => '{{WRAPPER}} .aic-image-container .twentytwenty-handle:hover',
                        ]
                    );
                    $this->add_control(
                        'aic_handle_border_color_hover',
                        [
                            'label'     => __( 'Border Color', 'advanced-image-comparison-for-elementor' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .aic-image-container .twentytwenty-handle:hover' => 'border-color: {{VALUE}}',
                            ],
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * Image Overlay
        */
        $this->start_controls_section(
            'aic_image_overlay_section',
            [
                'label'     => __( 'Image Filter', 'advanced-image-comparison-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs( 'aic_tabs_image_filters' );
                $this->start_controls_tab(
                    'aic_tab_before_image_filter',
                    [
                        'label' => __( 'Before', 'advanced-image-comparison-for-elementor' ),
                    ]
                );
                    $this->add_control(
                        'aic_before_image_filter',
                        [
                            'label'   => esc_html__( 'Befor Image Filter', 'advanced-image-comparison-for-elementor' ),
                            'type'    => Controls_Manager::SELECT,
                            'options' => [
                                '' => esc_html__( 'None', 'advanced-image-comparison-for-elementor' ),
                                'blur(4px)' => esc_html__( 'Blur', 'advanced-image-comparison-for-elementor' ),
                                'brightness(0.30)' => esc_html__( 'Brightness', 'advanced-image-comparison-for-elementor' ),
                                'contrast(180%)'   => esc_html__( 'Contrast', 'advanced-image-comparison-for-elementor' ),
                                'grayscale(100%)'  => esc_html__( 'Grayscale', 'advanced-image-comparison-for-elementor' ),
                                'hue-rotate(180deg)'=> esc_html__( 'Hue Rotate', 'advanced-image-comparison-for-elementor' ),
                                'invert(100%)'   => esc_html__( 'Invert', 'advanced-image-comparison-for-elementor' ),
                                'opacity(50%)'   => esc_html__( 'Opacity', 'advanced-image-comparison-for-elementor' ),
                                'saturate(7)'   => esc_html__( 'Saturate', 'advanced-image-comparison-for-elementor' ),
                                'sepia(100%)'   => esc_html__( 'Sepia', 'advanced-image-comparison-for-elementor' ),
                            ],
                            'default' => '',
                            'selectors'  => [
                                '{{WRAPPER}} .aic-image-container .aic-before-img' => 'filter: {{aic_image_filter}};',
                            ],
                        ]
                    );
                    $this->end_controls_tab();

                    $this->start_controls_tab(
                        'aic_tab_after_image_filter',
                        [
                            'label' => __( 'After', 'advanced-image-comparison-for-elementor' ),
                        ]
                    );
                    $this->add_control(
                        'aic_after_image_filter',
                        [
                            'label'   => esc_html__( 'After Image Filter', 'advanced-image-comparison-for-elementor' ),
                            'type'    => Controls_Manager::SELECT,
                            'options' => [
                                '' => esc_html__( 'None', 'advanced-image-comparison-for-elementor' ),
                                'blur(4px)' => esc_html__( 'Blur', 'advanced-image-comparison-for-elementor' ),
                                'brightness(0.30)' => esc_html__( 'Brightness', 'advanced-image-comparison-for-elementor' ),
                                'contrast(180%)'   => esc_html__( 'Contrast', 'advanced-image-comparison-for-elementor' ),
                                'grayscale(100%)'  => esc_html__( 'Grayscale', 'advanced-image-comparison-for-elementor' ),
                                'hue-rotate(180deg)'=> esc_html__( 'Hue Rotate', 'advanced-image-comparison-for-elementor' ),
                                'invert(100%)'   => esc_html__( 'Invert', 'advanced-image-comparison-for-elementor' ),
                                'opacity(50%)'   => esc_html__( 'Opacity', 'advanced-image-comparison-for-elementor' ),
                                'saturate(7)'   => esc_html__( 'Saturate', 'advanced-image-comparison-for-elementor' ),
                                'sepia(100%)'   => esc_html__( 'Sepia', 'advanced-image-comparison-for-elementor' ),
                            ],
                            'default' => 'grayscale(100%)',
                            'selectors'  => [
                                '{{WRAPPER}} .aic-image-container .aic-after-img' => 'filter: {{aic_image_filter}};',
                            ],
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render() {
       
        $settings = $this->get_settings_for_display();
        $beforeImage = $settings['aic_before_image'];
        $beforeImageAlt = $settings['aic_before_image_alt'];
        $afterImage = $settings['aic_after_image'];
        $afterImageAlt = $settings['aic_after_image_alt'];
        $imageVisibility = $settings['aic_image_visibility']['size'];

        $this->add_render_attribute(
            'aic-attr',
            [
                'id'                => 'aic-image-comparison-' . esc_attr( $this->get_id() ),
                'class'             => ['aic-image-container'],
                'data-offset'       => $imageVisibility ? ( $imageVisibility / 100 ) : 0,
                'data-orientation'  => $settings['aic_image_orientation'],
                'data-before_label' => $settings['aic_before_image_label'],
                'data-after_label'  => $settings['aic_after_image_label'],
                'data-overlay'      => $settings['aic_image_overlay'],
                'data-onhover'      => $settings['aic_image_move_slider_on_hover'],
                'data-click_to_move'=> $settings['aic_image_click_to_move'],
            ]
        );

        echo '<div ' . $this->get_render_attribute_string( 'aic-attr' ) . '>
			<img class="aic-before-img" alt="' . esc_attr( $beforeImageAlt ) . '" src="' . esc_url( $beforeImage['url'] ) . '">
			<img class="aic-after-img" alt="' . esc_attr( $afterImageAlt ) . '" src="' . esc_url( $afterImage['url'] ) . '">
        </div>';
    }

} 