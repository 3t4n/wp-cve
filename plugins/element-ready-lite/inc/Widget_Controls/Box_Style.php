<?php

namespace Element_Ready\Widget_Controls;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

trait Box_Style {

    public function box_css($atts) {
        
        $atts_variable = shortcode_atts(
            array(
                'title'            => 'Box Style',
                'slug'             => '_box_style',
                'element_name'     => '_boxPUp_ELEMENT_NAME',
                'selector'         => '{{WRAPPER}} ',
                'disable_controls' => [],
            ), $atts );

        extract($atts_variable);    

        $widget = $this->get_name().'_'.element_ready_menu_camelize($slug);
        
        /*----------------------------
            ELEMENT__STYLE
        -----------------------------*/
        $this->start_controls_section(
            $widget.'_style_section',
            [
                'label' => $title,
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( $widget.'_tabs_style' );
                $this->start_controls_tab(
                    $widget.'_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );


                    // Background
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'     => $widget.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => $selector,
                        ]
                    );

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => $widget.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => $selector,
                        ]
                    );

                    // Radius
                    $this->add_responsive_control(
                        $widget.'_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                               
                            ],
                        ]
                    );
                    
                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => $widget.'_shadow',
                            'selector' => $selector,
                        ]
                    );

                    // Margin
                    $this->add_responsive_control(
                        $widget.'_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                
                                $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    // Padding
                    $this->add_responsive_control(
                        $widget.'_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                               
                            ],
                        ]
                    );

                $this->end_controls_tab();

       
            $this->end_controls_tabs();

            $this->add_responsive_control(
                $widget.'_section___section_show_hide_'.$element_name.'_display',
                [
                    'label' => esc_html__( 'Display', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'flex'         => esc_html__( 'Flex', 'element-ready-lite' ),
                        'block'        => esc_html__( 'Block', 'element-ready-lite' ),
                        'inline-block' => esc_html__( 'Inline Block', 'element-ready-lite' ),
                        'grid'         => esc_html__( 'Grid', 'element-ready-lite' ),
                        'none'         => esc_html__( 'None', 'element-ready-lite' ),
                        ''         => esc_html__( 'inherit', 'element-ready-lite' ),
                    ],
                    'selectors' => [
                        $selector => 'display: {{VALUE}};'
                       
                    ],
                ]
            );
            $this->add_control(
                $widget.'_section___section_popover_'.$element_name.'_position',
                [
                    'label' => esc_html__( 'Position', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                    'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                    'return_value' => 'yes',
                ]
            );
    
            $this->start_popover();
            $this->add_responsive_control(
                $widget.'_section__'.$element_name.'_position_type',
                [
                    'label' => esc_html__( 'Position', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'fixed'    => esc_html__('Fixed','element-ready-lite'),
                        'absolute' => esc_html__('Absolute','element-ready-lite'),
                        'relative' => esc_html__('Relative','element-ready-lite'),
                        'sticky'   => esc_html__('Sticky','element-ready-lite'),
                        'static'   => esc_html__('Static','element-ready-lite'),
                        'inherit'  => esc_html__('inherit','element-ready-lite'),
                        ''         => esc_html__('none','element-ready-lite'),
                    ],
                    'selectors' => [
                        $selector => 'position: {{VALUE}};',
                       
                    ],
                    
                ]
            );
    
            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'_position_left',
                [
                    'label' => esc_html__( 'Position Left', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -3000,
                            'max' => 3000,
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
                $widget.'main_section_'.$element_name.'_r_position_top',
                [
                    'label' => esc_html__( 'Position Top', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -3000,
                            'max' => 3000,
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
                $widget.'main_section_'.$element_name.'_r_position_bottom',
                [
                    'label' => esc_html__( 'Position Bottom', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -2100,
                            'max' => 3000,
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
                $widget.'main_section_'.$element_name.'_r_position_right',
                [
                    'label' => esc_html__( 'Position Right', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 3000,
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

            $this->add_control(
                $widget.'main_section_'.$element_name.'_rbox_popover_section_sizen',
            [
                'label' => esc_html__( 'Box Size', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                'return_value' => 'yes',
              
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            $widget.'main_section_'.$element_name.'_r_section__width',
            [
                'label' => esc_html__( 'Width', 'element-ready-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
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
            $widget.'main_section_'.$element_name.'_r_container_height',
            [
                'label' => esc_html__( 'Height', 'element-ready-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
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
                    $selector => 'height: {{SIZE}}{{UNIT}};',
                  
                ],
            ]
        );

       
        $this->end_popover();


        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }


  }