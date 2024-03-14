<?php

namespace Element_ready\Widget_Controls;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

trait Shopping_Cart_Style {

    public function icon_css($title = 'icon style',$slug='shopping_icon_style',$element_name='shopping_ICON_ELEMENT_NAME') {
        
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

                    // Typgraphy
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'      => $widget.'_typography',
                            'selector'  => '{{WRAPPER}} .element-ready-shopping-cart-open i',
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        $widget.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .element-ready-shopping-cart-open i' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => $widget.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .element-ready-shopping-cart-open i',
                        ]
                    );

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => $widget.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element-ready-shopping-cart-open i',
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
                                '{{WRAPPER}} .element-ready-shopping-cart-open i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => $widget.'_shadow',
                            'selector' => '{{WRAPPER}} .element-ready-shopping-cart-open i',
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
                                '{{WRAPPER}} .element-ready-shopping-cart-open i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    $widget.'_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );

                    //Hover Color
                    $this->add_control(
                        'hover_'.$element_name.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :hover .element-ready-shopping-cart-open i, {{WRAPPER}} :focus .element-ready-shopping-cart-open i' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Hover Background
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} :hover .element-ready-shopping-cart-open i,{{WRAPPER}} :focus .element-ready-shopping-cart-open i',
                        ]
                    );	

                    $this->add_control(
                        'hover_'.$element_name.'border_color',
                        [
                            'label'     => esc_html__( 'Border Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :hover .element-ready-shopping-cart-open i,{{WRAPPER}} :hover .element-ready-shopping-cart-open i' => 'border-color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Radius
                    $this->add_responsive_control(
                        'hover_'.$element_name.'_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} :hover .element-ready-shopping-cart-open i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-shopping-cart-open' => 'display: {{VALUE}};',
                    ],
                ]
            );
  
        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

    public function interface_text_css($title = 'Interface Text style',$slug='shopping_interface_style',$element_name='shopping_interface_ele') {
        
        
        $widget = $this->get_name().'_'.element_ready_camelize($slug);
        
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

                    // Typgraphy
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'      => $widget.'_typography',
                            'selector'  => '{{WRAPPER}} .element-ready-shopping-cart-open',
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        $widget.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .element-ready-shopping-cart-open' => 'color: {{VALUE}};',
                            ],
                        ]
                    );
    
               
                $this->end_controls_tab();

                $this->start_controls_tab(
                    $widget.'_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );

                    //Hover Color
                    $this->add_control(
                        'hover_'.$element_name.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :hover .element-ready-shopping-cart-open, {{WRAPPER}} :focus .element-ready-shopping-cart-open' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Hover Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} :hover .element-ready-shopping-cart-open,{{WRAPPER}} :focus .element-ready-shopping-cart-open',
                        ]
                    );	

                    $this->add_control(
                        'hover_'.$element_name.'boders_color',
                        [
                            'label'     => esc_html__( 'Border Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :hover .element-ready-shopping-cart-open,{{WRAPPER}} :hover .element-ready-shopping-cart-open' => 'border-color: {{VALUE}};',
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
                         'none'         => esc_html__( 'None', 'element-ready-lite' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-shopping-cart-open' => 'display: {{VALUE}};',
                    ],
                ]
            );
        
        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }
    public function interface_cart_count_css($title = 'Interface Cart count',$slug='shopping_interfacecart_count_style',$element_name='shopping_cart_count_ele') {
        
        
        $widget = $this->get_name().'_'.element_ready_camelize($slug);
        
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

                    // Typgraphy
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'      => $widget.'_typography',
                            'selector'  => '{{WRAPPER}} .element-ready-interface-cart-count',
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        $widget.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .element-ready-interface-cart-count' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => $widget.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .element-ready-interface-cart-count',
                        ]
                    );

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => $widget.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element-ready-interface-cart-count',
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
                                '{{WRAPPER}} .element-ready-interface-cart-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => $widget.'_shadow',
                            'selector' => '{{WRAPPER}} .element-ready-interface-cart-count',
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
                                '{{WRAPPER}} .element-ready-interface-cart-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    $widget.'_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );

                    //Hover Color
                    $this->add_control(
                        'hover_'.$element_name.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :hover .element-ready-interface-cart-count, {{WRAPPER}} :focus .element-ready-interface-cart-count' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Hover Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} :hover .element-ready-interface-cart-count,{{WRAPPER}} :focus .element-ready-interface-cart-count',
                        ]
                    );	

                    $this->add_control(
                        'hover_'.$element_name.'border_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :hover .element-ready-interface-cart-count,{{WRAPPER}} :hover .element-ready-interface-cart-count' => 'color: {{VALUE}};',
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
                        'none'         => esc_html__( 'None', 'element-ready-lite' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-interface-cart-count' => 'display: {{VALUE}};',
                    ],
                ]
            );
        
        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }
    

    public function popup_css($title = 'PopUp',$slug='popup_box_style',$element_name='POPUp_ELEMENT_NAME') {
        
        
        $widget = $this->get_name().'_'.element_ready_camelize($slug);
        
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

        $this->add_control(
            $widget.'_section___section_popover_'.$element_name.'close_position',
            [
                'label'        => esc_html__( 'Close Icon Position', 'element-ready-lite' ),
                'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off'    => esc_html__( 'Default', 'element-ready-lite' ),
                'label_on'     => esc_html__( 'Custom', 'element-ready-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();
          
            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'close_position_left',
                [
                    'label' => esc_html__( 'Position Left', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
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
                        '{{WRAPPER}} .element-ready-shopping-cart-close' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'close_r_position_top',
                [
                    'label' => esc_html__( 'Position Top', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
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
                        '{{WRAPPER}} .element-ready-shopping-cart-close' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'close_r_position_bottom',
                [
                    'label' => esc_html__( 'Position Bottom', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
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
                        '{{WRAPPER}} .element-ready-shopping-cart-close' => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'close_r_position_right',
                [
                    'label' => esc_html__( 'Position Right', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
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
                        '{{WRAPPER}} .element-ready-shopping-cart-close' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->end_popover();

            $this->add_control(
                'popup_close_icon',
                [
                    'label' => esc_html__( 'Close Icon', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-times',
                        'library' => 'solid',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'      => $widget.'_close_typography',
                    'selector'  => '{{WRAPPER}} .element-ready-shopping-cart-close i',
                ]
            );

            // Icon Color
            $this->add_control(
                $widget.'close_icon_color',
                [
                    'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-shopping-cart-close i' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                $widget.'close_icon_bg_color',
                [
                    'label'     => esc_html__( 'Background', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-shopping-cart-close' => 'background: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget.'close_padding',
                [
                    'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .element-ready-shopping-cart-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        
                    ],
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
                            'selector' => '{{WRAPPER}} .element-ready-shopping-cart-canvas,{{WRAPPER}} .element-ready-user-modal-content',
                        ]
                    );

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => $widget.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element-ready-shopping-cart-canvas, {{WRAPPER}} .element-ready-user-modal-content',
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
                                '{{WRAPPER}} .element-ready-shopping-cart-canvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element-ready-user-modal-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => $widget.'_shadow',
                            'selector' => '{{WRAPPER}} .element-ready-shopping-cart-canvas,{{WRAPPER}} .element-ready-user-modal-content',
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
                                '{{WRAPPER}} .element-ready-shopping-cart-canvas' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element-ready-user-modal-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .element-ready-shopping-cart-canvas' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element-ready-user-modal-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

       
            $this->end_controls_tabs();
  
        $this->add_responsive_control(
            $widget.'main_section_'.$element_name.'_r_section__width',
            [
                'label' => esc_html__( 'Width', 'element-ready-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
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
                    '{{WRAPPER}} .element-ready-shopping-cart-canvas' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .modal .modal-dialog' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

    public function popup_overlay_css($title = 'PopUp Overlay',$slug='popup_box_overlay_style',$element_name='POPUp_ovelay_ELEMENT_NAME') {
        
        
        $widget = $this->get_name().'_'.element_ready_camelize($slug);
        
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
                            'selector' => '{{WRAPPER}} .element-ready-shopping-cart-wrapper .overlay.open::before',
                        ]
                    );

                   
                $this->end_controls_tab();

       
            $this->end_controls_tabs();

        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

    public function checkout_button_css($title = 'View Button',$slug='view_b_style',$element_name='view_button_ele_name') {
        
        
        $widget = $this->get_name().'_'.element_ready_camelize($slug);
        
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

                    // Typgraphy
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'      => $widget.'_typography',
                            'selector'  => '{{WRAPPER}} .cart-btn .element-ready-cart-btn',
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        $widget.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .cart-btn .element-ready-cart-btn' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => $widget.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .cart-btn .element-ready-cart-btn',
                        ]
                    );

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => $widget.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .cart-btn .element-ready-cart-btn',
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
                                '{{WRAPPER}} .cart-btn .element-ready-cart-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => $widget.'_shadow',
                            'selector' => '{{WRAPPER}} .cart-btn .element-ready-cart-btn',
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
                                '{{WRAPPER}} .cart-btn .element-ready-cart-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .cart-btn .element-ready-cart-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    $widget.'_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );

                    //Hover Color
                    $this->add_control(
                        'hover_'.$element_name.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :hover .cart-btn .element-ready-cart-btn, {{WRAPPER}} :focus .cart-btn .element-ready-cart-btn' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Hover Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} :hover .cart-btn .element-ready-cart-btn,{{WRAPPER}} :focus .cart-btn .element-ready-cart-btn',
                        ]
                    );	

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} :hover .cart-btn .element-ready-cart-btn,{{WRAPPER}} :hover .cart-btn .element-ready-cart-btn',
                        ]
                    );

                    // Radius
                    $this->add_responsive_control(
                        'hover_'.$element_name.'_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} :hover .cart-btn .element-ready-cart-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_shadow',
                            'selector' => '{{WRAPPER}} :hover .cart-btn .element-ready-cart-btn',
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
                        'none'         => esc_html__( 'None', 'element-ready-lite' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .cart-btn' => 'display: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'_gap',
                [
                    'label' => esc_html__( 'Gap', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 300,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'condition' => [
                        $widget.'_section___section_show_hide_'.$element_name.'_display' => ['flex']
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}} .cart-btn' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
       
        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

    public function view_cart_button_css($title = 'Checkout Cart',$slug='view_cart_style',$element_name='view_cart_button_ele_name') {
        
        
        $widget = $this->get_name().'_'.element_ready_camelize($slug);
        
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

                    // Typgraphy
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'      => $widget.'_typography',
                            'selector'  => '{{WRAPPER}} .cart-btn .element-ready-checkout-btn',
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        $widget.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .cart-btn .element-ready-checkout-btn' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => $widget.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .cart-btn .element-ready-checkout-btn',
                        ]
                    );

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => $widget.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .cart-btn .element-ready-checkout-btn',
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
                                '{{WRAPPER}} .cart-btn .element-ready-checkout-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => $widget.'_shadow',
                            'selector' => '{{WRAPPER}} .cart-btn .element-ready-checkout-btn',
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
                                '{{WRAPPER}} .cart-btn .element-ready-checkout-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .cart-btn .element-ready-checkout-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    $widget.'_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );

                    //Hover Color
                    $this->add_control(
                        'hover_'.$element_name.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :hover .cart-btn .element-ready-checkout-btn, {{WRAPPER}} :focus .cart-btn .element-ready-checkout-btn' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Hover Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} :hover .cart-btn .element-ready-checkout-btn,{{WRAPPER}} :focus .cart-btn .element-ready-checkout-btn',
                        ]
                    );	

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} :hover .cart-btn .element-ready-checkout-btn,{{WRAPPER}} :hover .cart-btn .element-ready-checkout-btn',
                        ]
                    );

                    // Radius
                    $this->add_responsive_control(
                        'hover_'.$element_name.'_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} :hover .cart-btn .element-ready-checkout-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_shadow',
                            'selector' => '{{WRAPPER}} :hover .cart-btn .element-ready-checkout-btn',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
         
        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

    public function sub_total_title_css($title = 'Sub Total',$slug='sub_total_style',$element_name='sub_total_') {
        
        
        $widget = $this->get_name().'_'.element_ready_camelize($slug);
        
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

                $this->add_group_control(
                    Group_Control_Border:: get_type(),
                    [
                        'name'     => $widget.'_topbar_border',
                        'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                        'selector' => '{{WRAPPER}} .element-ready-shopping_cart-btn',
                    ]
                );


            $this->start_controls_tabs( $widget.'_tabs_style' );
                $this->start_controls_tab(
                    $widget.'_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );

                    // Typgraphy
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'      => $widget.'_typography',
                            'selector'  => '{{WRAPPER}} .element-ready-shopping_cart-btn .element-ready-sub-total,{{WRAPPER}} .element_ready_modal_lost_password',
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        $widget.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .element-ready-shopping_cart-btn .element-ready-sub-total' => 'color: {{VALUE}} !important;',
                                '{{WRAPPER}} .element_ready_modal_lost_password' => 'color: {{VALUE}} !important;',
                            ],
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
                                '{{WRAPPER}} .element_ready_lost_password' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .element_ready_modal_lost_password' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .element-ready-shopping_cart-btn .element-ready-sub-total' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};display:block;',
                                '{{WRAPPER}} .element_ready_modal_lost_password' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};display:block;',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    $widget.'_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );

                    //Hover Color
                    $this->add_control(
                        'hover_'.$element_name.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :hover .element-ready-shopping_cart-btn .element-ready-sub-total, {{WRAPPER}} :focus .element-ready-shopping_cart-btn .element-ready-sub-total' => 'color: {{VALUE}};',
                                '{{WRAPPER}} :hover .element_ready_modal_lost_password, {{WRAPPER}} :focus .element_ready_modal_lost_password' => 'color: {{VALUE}};',
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
                        'none'         => esc_html__( 'None', 'element-ready-lite' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-shopping_cart-btn .element-ready-sub-total' => 'display: {{VALUE}};',
                        '{{WRAPPER}} .element_ready_modal_lost_password'                        => 'display: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget.'_section___section_align_'.$element_name.'_content',
                [
                    'label' => esc_html__( 'Alignment', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'center' => esc_html__( 'Center', 'element-ready-lite' ),
                        'left'   => esc_html__( 'Left', 'element-ready-lite' ),
                        'right'  => esc_html__( 'Right', 'element-ready-lite' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-shopping_cart-btn .total' => 'justify-content: {{VALUE}};',
                    ],
                ]
            );
     
       
        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

    public function modal_heading_css($title = 'Modal heading',$slug='modal_ehading_style',$element_name='MODAL_HEADING_ELEMENT_NAME') {
        
        
        $widget = $this->get_name().'_'.element_ready_camelize($slug);
        
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
                        'label' => esc_html__( 'Heading', 'element-ready-lite' ),
                    ]
                );

                    // Typgraphy
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'      => $widget.'_typography',
                            'selector'  => '{{WRAPPER}} .element-ready-shopping_cart-top-bar h6',
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        $widget.'_color',
                        [
                            'label'     => esc_html__( ' Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .element-ready-shopping_cart-top-bar h6' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => $widget.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .element-ready-shopping_cart-top-bar h6',
                        ]
                    );

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => $widget.'_text_border',
                            'label'    => esc_html__( 'Text Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element-ready-shopping_cart-top-bar h6',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => $widget.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .element-ready-user-modal-content .modal-header',
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
                                '{{WRAPPER}} .element-ready-shopping_cart-top-bar h6' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => $widget.'_shadow',
                            'selector' => '{{WRAPPER}} .element-ready-shopping_cart-top-bar h6',
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
                                '{{WRAPPER}} .element-ready-shopping_cart-top-bar h6' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .element-ready-shopping_cart-top-bar h6' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    $widget.'_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );

                    //Hover Color
                    $this->add_control(
                        'hover_'.$element_name.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :hover .element-ready-shopping_cart-top-bar h6, {{WRAPPER}} :focus .element-ready-shopping_cart-top-bar h6' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Hover Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} :hover .element-ready-shopping_cart-top-bar h6,{{WRAPPER}} :focus .element-ready-shopping_cart-top-bar h6',
                        ]
                    );	

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} :hover .element-ready-shopping_cart-top-bar h6,{{WRAPPER}} :hover .element-ready-shopping_cart-top-bar h6',
                        ]
                    );

                    // Radius
                    $this->add_responsive_control(
                        'hover_'.$element_name.'_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} :hover .element-ready-shopping_cart-top-bar h6' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_shadow',
                            'selector' => '{{WRAPPER}} :hover .element-ready-shopping_cart-top-bar h6',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();

         
        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

    public function sub_total_css($title = 'Sub Total',$slug='sub_total_style',$element_name='sub_total_ELEMENT_NAME') {
        
        
        $widget = $this->get_name().'_'.element_ready_camelize($slug);
        
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

                    // Typgraphy
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'      => $widget.'_typography',
                            'selector'  => '{{WRAPPER}} .element-ready-sub-total-amount',
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        $widget.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .element-ready-sub-total-amount' => 'color: {{VALUE}};',
                            ],
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
                                '{{WRAPPER}} .element-ready-sub-total-amount' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .element-ready-sub-total-amount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        'none'         => esc_html__( 'None', 'element-ready-lite' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .total' => 'display: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'_gap',
                [
                    'label' => esc_html__( 'Gap', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 300,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'condition' => [
                        $widget.'_section___section_show_hide_'.$element_name.'_display' => ['flex']
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}} .total' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
   
        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

  }