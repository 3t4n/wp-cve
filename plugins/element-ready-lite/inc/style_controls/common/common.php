<?php
/**
 * @package Element Ready
 */
namespace Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

trait Element_Ready_Common_Style {

    public function text_minimum_css( $atts ) {

        $atts_variable = shortcode_atts(
            [
                'title'          => esc_html__( 'Text Style', 'element-ready-lite' ),
                'slug'           => '_text_style',
                'element_name'   => '__ele_ready__',
                'selector'       => '{{WRAPPER}} ',
                'hover_selector' => '{{WRAPPER}} ',
                'condition'      => '',
                'disable_controls'    => [],
                'tab'            => Controls_Manager::TAB_STYLE,
            ], $atts );

        extract( $atts_variable );

        $widget = $this->get_name() . '_' . element_ready_heading_camelize( $slug );

        /*----------------------------
        ELEMENT__STYLE
        -----------------------------*/

        $tab_start_section_args = [
            'label' => $title,
            'tab'   => $tab,
        ];

        if ( is_array( $condition ) ) {
            $tab_start_section_args['condition'] = $condition;
        }

        $this->start_controls_section(
            $widget . '_style_section',
            $tab_start_section_args
        );

        $this->start_controls_tabs( $widget . '_tabs_style' );
       
        do_action('custom_tab_'.$widget,$this);
        $this->start_controls_tab(
            $widget . '_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'element-ready-lite' ),
            ]
        );
       
        do_action('custom_'.$widget,$this);
        // Typgraphy
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => $widget . '_stypography',
                'selector' => $selector,
            ]
        );

        // Icon Color
        $this->add_control(
            $widget . '_text_color',
            [
                'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    $selector => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        if(!in_array('bg',$disable_controls)){  
            //  Background
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'gens_' . $element_name . '_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => ['classic', 'gradient'],
                    'selector' => $selector,
                ]
            );
        }

        if(!in_array('border',$disable_controls)){  
            // Border
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => 'gens_' . $element_name . '_border',
                    'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                    'selector' => $selector,
                ]
            );

            $this->add_responsive_control(
                $widget . '_radius',
                [
                    'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }
        if(!in_array('dimension',$disable_controls)){  
            // Margin
            $this->add_responsive_control(
                $widget . '_smargin',
                [
                    'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Padding
            $this->add_responsive_control(
                $widget . '_padding',
                [
                    'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }
        if(!in_array('display',$disable_controls)){  

            $this->add_responsive_control(
                $widget . '_section___section_show_hide_' . $element_name . '_display',
                [
                    'label'     => esc_html__( 'Display', 'element-ready-lite' ),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => [
                        'flex'         => esc_html__( 'Flex', 'element-ready-lite' ),
                        'inline-flex'  => esc_html__( 'Inline Flex', 'element-ready-lite' ),
                        'block'        => esc_html__( 'Block', 'element-ready-lite' ),
                        'inline-block' => esc_html__( 'Inline Block', 'element-ready-lite' ),
                        'none'         => esc_html__( 'None', 'element-ready-lite' ),
                        ''             => esc_html__( 'Default', 'element-ready-lite' ),
                    ],
                    'selectors' => [
                        $selector => 'display: {{VALUE}};',
                    ],
                ]
            );
        }

        $this->add_control(
            $widget . 'ele_box_transition',
            [
                'label'      => esc_html__( 'Transition', 'element-ready-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0.1,
                        'max'  => 3,
                        'step' => 0.1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0.5,
                ],
                'selectors'  => [
                    $selector => 'transition: {{SIZE}}s;',

                ],
            ]
        );

        $this->end_controls_tab();
        // Hover selector
        if ( $hover_selector != false || $hover_selector != '' ) {

            $this->start_controls_tab(
                $widget . '_hover_tab',
                [
                    'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                ]
            );

            // Icon Color
            $this->add_control(
                $widget . 'hover_text_color',
                [
                    'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        $hover_selector => 'color: {{VALUE}} !important;',
                    ],
                ]
            );
            if(!in_array('bg',$disable_controls)){  
                // Hover Background
                $this->add_group_control(
                    Group_Control_Background::get_type(),
                    [
                        'name'     => 'hovers_' . $element_name . '_background',
                        'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                        'types'    => ['classic', 'gradient'],
                        'selector' => $hover_selector,
                    ]
                );
            }
            if(!in_array('border',$disable_controls)){  
                // Border
                $this->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name'     => 'hovers_' . $element_name . '_border',
                        'label'    => esc_html__( 'Border', 'element-ready-lite' ),
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
  
    public function element_before_psudocode($atts){
        $atts_variable = shortcode_atts(
            array(
                'title'           => esc_html__('Separate','element-ready-lite'),
                'slug'            => '_meta_after_before_style',
                'element_name'    => 'after_element_ready_',
                'selector'        => '{{WRAPPER}} ',
                'selector_parent' => '',
                'condition'       => '',
                'disable_controls'    => [],
            ), $atts );

        extract($atts_variable);    
        
        $widget = $this->get_name().'_'.element_ready_heading_camelize($slug);

        /*----------------------------
            ELEMENT__STYLE
        -----------------------------*/
        $tab_start_section_args =  [
            'label' => $title,
            'tab'   => Controls_Manager::TAB_STYLE,
        ];

        if(is_array($condition)){
            $tab_start_section_args['condition'] = $condition;
        }
      
        $this->start_controls_section(
            $widget.'_style_after_before_section',
            $tab_start_section_args
        );

        $this->add_responsive_control(
            $element_name.'__bg_text_custom_tab_area_css',
            [
                'label'     => esc_html__( 'Custom CSS', 'element-ready-lite' ),
                'type'      => Controls_Manager::CODE,
                'rows'      => 20,
                'language'  => 'css',
                'selectors' => [
                    $selector => '{{VALUE}};',
                ],
                'separator' => 'before',
               
            ]
        );
 
        $this->add_control(
            'psdu_'.$element_name.'_color',
            [
                'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    $selector => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $widget.'main_section_'.$element_name.'psudud_opacity_color',
            [
                'label' => esc_html__( 'Opacity', 'element-ready-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                   
                ],
               
                'selectors' => [
                    $selector_parent  => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => $widget.'main_section_'.$element_name.'psudud_border_gp_',
				'label' => esc_html__( 'Border', 'element-ready-lite' ),
				'selector' => $selector,
			]
		);

        $this->add_responsive_control(
            $widget.'main_section_'.$element_name.'_r_size_transform',
            [
                'label' => esc_html__( 'Transform', 'element-ready-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -360,
                        'max' => 360,
                        'step' => 5,
                    ],
                   
                ],
               
                'selectors' => [
                    $selector  => 'transform: translateY(-50%) rotate({{SIZE}}deg);',
                ],
            ]
        );
        
        if($selector_parent !=''){
            $this->add_responsive_control(
                $widget.'psudu_padding',
                [
                    'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        $selector_parent => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }
       

        $this->add_responsive_control(
            $widget.'main_section_'.$element_name.'_psudu_size_width',
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
                    $selector  => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $widget.'main_section_'.$element_name.'psudud_size_height',
            [
                'label' => esc_html__( 'Height', 'element-ready-lite' ),
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
                    $selector  => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $widget.'main_section_'.$element_name.'psudud_position_left_',
            [
                'label' => esc_html__( 'Position Left', 'element-ready-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
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
                    $selector  => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $widget.'main_section_'.$element_name.'psudud_position_top_',
            [
                'label' => esc_html__( 'Position Top', 'element-ready-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
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
                    $selector  => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            $widget.'_section__psudu_section_show_hide_'.$element_name.'_display',
            [
                'label' => esc_html__( 'Display', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'block'        => esc_html__( 'Block', 'element-ready-lite' ),
                    'none'         => esc_html__( 'None', 'element-ready-lite' ),
                    ''             => esc_html__( 'inherit', 'element-ready-lite' ),
                ],
                'selectors' => [
                    $selector => 'display: {{VALUE}};'
               ],
            ]
            
        );
   

        $this->end_controls_section();
    }

    public function element_size($atts){
        $atts_variable = shortcode_atts(
            array(
                'title'        => esc_html__('Size Style','element-ready-lite'),
                'slug'         => '_size_style',
                'element_name' => '_element_ready_',
                'selector'     => '{{WRAPPER}} ',
                'condition'    => '',
                'disable_controls'    => [],
            ), $atts );

        extract($atts_variable);    
        
        $widget = $this->get_name().'_'.element_ready_heading_camelize($slug);

        /*----------------------------
            ELEMENT__STYLE
        -----------------------------*/
        $tab_start_section_args =  [
            'label' => $title,
            'tab'   => Controls_Manager::TAB_STYLE,
        ];

        if(is_array($condition)){
            $tab_start_section_args['condition'] = $condition;
        }
      
        $this->start_controls_section(
            $widget.'_style_size_section',
            $tab_start_section_args
        );

        $this->add_responsive_control(
            $widget.'main_section_'.$element_name.'_r_size_width',
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
                    $selector  => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $widget.'main_section_'.$element_name.'_r_size_height',
            [
                'label' => esc_html__( 'Height', 'element-ready-lite' ),
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
                    $selector  => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        if(!in_array('box-shadow',$disable_controls)){  
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => $widget.'mainer'.$element_name.'_r_box_shadow',
                    'label' => __( 'Box Shadow', 'element-ready-lite' ),
                    'selector' => $selector,
                ]
            );
        }
        
        if(!in_array('border',$disable_controls)){

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => $widget.'size_border',
                    'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                    'selector' => $selector,
                ]
            );

            // Radius
            $this->add_responsive_control(
                $widget.'seze_radius',
                [
                    'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }


        $this->end_controls_section();
    }

    public function text_css($atts) {

        $atts_variable = shortcode_atts(
            array(
                'title'            => esc_html__('Text Style','element-ready-lite'),
                'slug'             => '_text_style',
                'element_name'     => '_element_ready_',
                'selector'         => '{{WRAPPER}} ',
                'hover_selector'   => '{{WRAPPER}} ',
                'condition'        => '',
                'disable_controls' => [],
            ), $atts );

        extract($atts_variable);    
        
        $widget = $this->get_name().'_'.element_ready_heading_camelize($slug);

      
        /*----------------------------
            ELEMENT__STYLE
        -----------------------------*/
        $tab_start_section_args =  [
            'label' => $title,
            'tab'   => Controls_Manager::TAB_STYLE,
        ];

        if(is_array($condition)){
            $tab_start_section_args['condition'] = $condition;
        }
      
        $this->start_controls_section(
            $widget.'_style_section',
            $tab_start_section_args
        );

        if(!in_array('alignment',$disable_controls)){  

                $this->add_responsive_control(
                    $widget.'_alignment', [
                        'label'   => esc_html__( 'Alignment', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::CHOOSE,
                        'options' => [

                    'left'		 => [
                        
                        'title' => esc_html__( 'Left', 'element-ready-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    
                    ],
                        'center'	     => [
                        
                        'title' => esc_html__( 'Center', 'element-ready-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    
                    ],
                    'right'	 => [

                        'title' => esc_html__( 'Right', 'element-ready-lite' ),
                        'icon'  => 'eicon-text-align-right',
                        
                    ],
                    
                    'justify'	 => [
                        'title' => esc_html__( 'Justified', 'element-ready-lite' ),
                        'icon'  => 'eicon-text-align-justify',
                        ],
                    ],
                    
                    'selectors' => [
                        $selector => 'text-align: {{VALUE}};',
                    ],
                    ]
                );//Responsive control end
            }   

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
                            'selector'  => $selector,
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        $widget.'_text_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                $selector => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Text_Shadow::get_type(),
                        [
                            'name' =>  $widget.'text_shadow_',
                            'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
                            'selector' => $selector ,
                        ]
                    );
            
                if(!in_array('bg',$disable_controls)){  
                    // Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => $widget.'text_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient','video' ],
                            'selector' => $selector,
                        ]
                    );
                }

                if(!in_array('border',$disable_controls)){  
                    // Border
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
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
                }

                if(!in_array('box-shadow',$disable_controls)){      
                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => $widget.'normal_shadow',
                            'selector' => $selector,
                        ]
                    );
                }

                if(!in_array('dimension',$disable_controls)){  
                    // Margin
                    $this->add_responsive_control(
                        $widget.'_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                $selector  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                }

                if(!in_array('css',$disable_controls)){  
                    $this->add_responsive_control(
                        $widget.'main_section_'.$element_name.'_element_ready_control_custom_css',
                        [
                            'label'     => esc_html__( 'Custom CSS', 'element-ready-lite' ),
                            'type'      => Controls_Manager::CODE,
                            'rows'      => 20,
                            'language'  => 'css',
                            'selectors' => [
                                $selector => '{{VALUE}};',
                              
                            ],
                            'separator' => 'before',
                        ]
                    );
                }
                if(!in_array('size',$disable_controls)){  

                    $this->add_responsive_control(
                        $widget.'main_section_'.$element_name.'_r_itemdsd_el__width',
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
                        $widget.'main_section_'.$element_name.'_r_item_dsd_maxel__width',
                        [
                            'label' => esc_html__( 'Max Width', 'element-ready-lite' ),
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
                                $selector => 'max-width: {{SIZE}}{{UNIT}};',
                            
                            ],
                        ]
                    );

                    $this->add_responsive_control(
                        $widget.'main_section_'.$element_name.'_r_item_errt_min_el__width',
                        [
                            'label' => esc_html__( 'Min Width', 'element-ready-lite' ),
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
                                $selector => 'min-width: {{SIZE}}{{UNIT}};',
                            
                            ],
                        ]
                    );
                }

                $this->end_controls_tab();
                if($hover_selector != false || $hover_selector != ''){
   
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
                                $hover_selector => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Text_Shadow::get_type(),
                        [
                            'name' =>  $widget.'text_shadow_hover_',
                            'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
                            'selector' => $hover_selector ,
                        ]
                    );

                    if(!in_array('bg',$disable_controls)){  
                        // Hover Background
                        $this->add_group_control(
                            Group_Control_Background::get_type(),
                            [
                                'name'     => 'hover_'.$element_name.'_background',
                                'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                                'types'    => [ 'classic', 'gradient' ],
                                'selector' => $hover_selector,
                            ]
                        );	
                    }

                    if(!in_array('border',$disable_controls)){  
                            // Border
                            $this->add_group_control(
                                Group_Control_Border::get_type(),
                                [
                                    'name'     => 'hover_'.$element_name.'_border',
                                    'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                                    'selector' => $hover_selector,
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
                                        $hover_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    ],
                                ]
                            );
                    }

                    if(!in_array('box-shadow',$disable_controls)){  
                        // Shadow
                        $this->add_group_control(
                            Group_Control_Box_Shadow:: get_type(),
                            [
                                'name'     => 'hover_'.$element_name.'_shadow',
                                'selector' => $hover_selector,
                            ]
                        );
                    }

                    if(!in_array('css',$disable_controls)){  
                        $this->add_responsive_control(
                            $widget.'main_section_'.$element_name.'_element_ready_control_hover_custom_css',
                            [
                                'label'     => esc_html__( 'Custom CSS', 'element-ready-lite' ),
                                'type'      => Controls_Manager::CODE,
                                'rows'      => 20,
                                'language'  => 'css',
                                'selectors' => [
                                    $hover_selector => '{{VALUE}};',
                                
                                ],
                                'separator' => 'before',
                            ]
                        );
                    }
                    
                $this->end_controls_tab();
                } // hover_select check end
            $this->end_controls_tabs();
            if(!in_array('display',$disable_controls)){  

                $this->add_responsive_control(
                    $widget.'_section___section_show_hide_'.$element_name.'_display',
                    [
                        'label' => esc_html__( 'Display', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            'flex'         => esc_html__( 'Flex', 'element-ready-lite' ),
                            'inline-flex'  => esc_html__( 'Inline Flex', 'element-ready-lite' ),
                            'block'        => esc_html__( 'Block', 'element-ready-lite' ),
                            'inline-block' => esc_html__( 'Inline Block', 'element-ready-lite' ),
                            'grid'         => esc_html__( 'Grid', 'element-ready-lite' ),
                            'none'         => esc_html__( 'None', 'element-ready-lite' ),
                            ''             => esc_html__( 'Default', 'element-ready-lite' ),
                        ],
                        'selectors' => [
                        $selector => 'display: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    $widget.'_section___section_flex_direction_'.$element_name.'_display',
                    [
                        'label' => esc_html__( 'Flex Direction', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            'column'         => esc_html__( 'Column', 'element-ready-lite' ),
                            'row'            => esc_html__( 'Row', 'element-ready-lite' ),
                            'column-reverse' => esc_html__( 'Column Reverse', 'element-ready-lite' ),
                            'row-reverse'    => esc_html__( 'Row Reverse', 'element-ready-lite' ),
                            'revert'         => esc_html__( 'Revert', 'element-ready-lite' ),
                            'none'           => esc_html__( 'None', 'element-ready-lite' ),
                            ''               => esc_html__( 'inherit', 'element-ready-lite' ),
                        ],
                        'selectors' => [
                            $selector => 'flex-direction: {{VALUE}};'
                        ],
                        'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['flex','inline-flex'] ]
                    ]
                    
                );

                $this->add_responsive_control(
                    $widget.'_section__s_section_flex_wrap_'.$element_name.'_display',
                    [
                        'label' => esc_html__( 'Flex Wrap', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            'wrap'         => esc_html__( 'Wrap', 'element-ready-lite' ),
                            'wrap-reverse' => esc_html__( 'Wrap Reverse', 'element-ready-lite' ),
                            'nowrap'       => esc_html__( 'No Wrap', 'element-ready-lite' ),
                            'unset'        => esc_html__( 'Unset', 'element-ready-lite' ),
                            'normal'       => esc_html__( 'None', 'element-ready-lite' ),
                            'inherit'      => esc_html__( 'inherit', 'element-ready-lite' ),
                        ],
                        'selectors' => [
                            $selector => 'flex-wrap: {{VALUE}};'
                        ],
                        'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['flex','inline-flex'] ]
                    ]
                    
                );

                $this->add_responsive_control(
                    $widget.'_section_align_sessction_e_'.$element_name.'_flex_align',
                    [
                        'label' => esc_html__( 'Alignment', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            'flex-start'    => esc_html__( 'Left', 'element-ready-lite' ),
                            'flex-end'      => esc_html__( 'Right', 'element-ready-lite' ),
                            'center'        => esc_html__( 'Center', 'element-ready-lite' ),
                            'space-around'  => esc_html__( 'Space Around', 'element-ready-lite' ),
                            'space-between' => esc_html__( 'Space Between', 'element-ready-lite' ),
                            'space-evenly'  => esc_html__( 'Space Evenly', 'element-ready-lite' ),
                            ''              => esc_html__( 'inherit', 'element-ready-lite' ),
                        ],
                        'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['flex','inline-flex'] ],

                        'selectors' => [
                            $selector => 'justify-content: {{VALUE}};'
                    ],
                    ]
                    
                );

                $this->add_responsive_control(
                    $widget.'er_section_align_items_ssection_e_'.$element_name.'_flex_align',
                    [
                        'label' => esc_html__( 'Align Items', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            'flex-start' => esc_html__( 'Left', 'element-ready-lite' ),
                            'flex-end'   => esc_html__( 'Right', 'element-ready-lite' ),
                            'center'     => esc_html__( 'Center', 'element-ready-lite' ),
                            'baseline'   => esc_html__( 'Baseline', 'element-ready-lite' ),
                            ''           => esc_html__( 'inherit', 'element-ready-lite' ),
                        ],
                        'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['flex','inline-flex'] ],

                        'selectors' => [
                            $selector => 'align-items: {{VALUE}};'
                    ],
                    ]
                    
                );
            }

            if(!in_array('position',$disable_controls)){  

                $this->add_control(
                    $widget.'_section___section_popover_'.$element_name.'_position',
                    [
                        'label'        => esc_html__( 'Position', 'element-ready-lite' ),
                        'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                        'label_off'    => esc_html__( 'Default', 'element-ready-lite' ),
                        'label_on'     => esc_html__( 'Custom', 'element-ready-lite' ),
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
                    $widget.'main_section_'.$element_name.'_r_position_top',
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
                            $selector  => 'bottom: {{SIZE}}{{UNIT}};',
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

    public function text_wrapper_css($atts) {

        $atts_variable = shortcode_atts(
            array(
                'title'          => esc_html__('Text Style','element-ready-lite'),
                'slug'           => '_text_style',
                'element_name'   => '_element_ready_',
                'selector'       => '{{WRAPPER}} ',
                'hover_selector' => '{{WRAPPER}} ',
                'condition'      => '',
                'disable_controls'    => [],
            ), $atts );

        extract($atts_variable);    
        
        $widget = $this->get_name().'_'.element_ready_heading_camelize($slug);

      
        /*----------------------------
            ELEMENT__STYLE
        -----------------------------*/
        $tab_start_section_args =  [
            'label' => $title,
            'tab'   => Controls_Manager::TAB_STYLE,
        ];

        if(is_array($condition)){
            $tab_start_section_args['condition'] = $condition;
        }
      
        $this->start_controls_section(
            $widget.'_style_section',
            $tab_start_section_args
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
                            'selector'  => $selector,
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        $widget.'_text_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                $selector => 'color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Text_Shadow::get_type(),
                        [
                            'name' =>  $widget.'text_shadow_',
                            'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
                            'selector' => $selector ,
                        ]
                    );
            
                    if(!in_array('bg',$disable_controls)){  
                        // Background
                        $this->add_group_control(
                            Group_Control_Background:: get_type(),
                            [
                                'name'     => $widget.'text_background',
                                'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                                'types'    => [ 'classic', 'gradient','video' ],
                                'selector' => $selector,
                            ]
                        );
                     }
                     if(!in_array('border',$disable_controls)){  
                            // Border
                            $this->add_group_control(
                                Group_Control_Border::get_type(),
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
                    }
                    if(!in_array('box-shadow',$disable_controls)){  
                        // Shadow
                        $this->add_group_control(
                            Group_Control_Box_Shadow::get_type(),
                            [
                                'name'     => $widget.'normal_shadow',
                                'selector' => $selector,
                            ]
                        );
                    }
                    if(!in_array('dimension',$disable_controls)){  
                        // Margin
                        $this->add_responsive_control(
                            $widget.'_margin',
                            [
                                'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                                'type'       => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%', 'em' ],
                                'selectors'  => [
                                    $selector  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    }
                    if(!in_array('css',$disable_controls)){  

                        $this->add_responsive_control(
                            $widget.'main_section_'.$element_name.'_element_ready_control_custom_css',
                            [
                                'label'     => esc_html__( 'Custom CSS', 'element-ready-lite' ),
                                'type'      => Controls_Manager::CODE,
                                'rows'      => 20,
                                'language'  => 'css',
                                'selectors' => [
                                    $selector => '{{VALUE}};',
                                
                                ],
                                'separator' => 'before',
                            ]
                        );

                    }
                    if(!in_array('size',$disable_controls)){  
                    
                        $this->add_responsive_control(
                            $widget.'main_section_'.$element_name.'_r_item_el__width',
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
                            $widget.'main_section_'.$element_name.'_r_item__maxel__width',
                            [
                                'label' => esc_html__( 'Max Width', 'element-ready-lite' ),
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
                                    $selector => 'max-width: {{SIZE}}{{UNIT}};',
                                
                                ],
                            ]
                        );

                        $this->add_responsive_control(
                            $widget.'main_section_'.$element_name.'_r_item__min_el__width',
                            [
                                'label' => esc_html__( 'Min Width', 'element-ready-lite' ),
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
                                    $selector => 'min-width: {{SIZE}}{{UNIT}};',
                                
                                ],
                            ]
                        );
                    }

                $this->end_controls_tab();
                if($hover_selector != false || $hover_selector != ''){
   
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
                                $hover_selector => 'color: {{VALUE}} !important;',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        \Elementor\Group_Control_Text_Shadow::get_type(),
                        [
                            'name' =>  $widget.'text_shadow_hover_',
                            'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
                            'selector' => $hover_selector ,
                        ]
                    );
                    if(!in_array('bg',$disable_controls)){  
                        // Hover Background
                        $this->add_group_control(
                            Group_Control_Background::get_type(),
                            [
                                'name'     => 'hover_'.$element_name.'_background',
                                'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                                'types'    => [ 'classic', 'gradient' ],
                                'selector' => $hover_selector,
                            ]
                        );	
                    }
                    if(!in_array('border',$disable_controls)){  
                        // Border
                        $this->add_group_control(
                            Group_Control_Border::get_type(),
                            [
                                'name'     => 'hover_'.$element_name.'_border',
                                'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                                'selector' => $hover_selector,
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
                                    $hover_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                            ]
                        );
                    }
                    if(!in_array('box-shadow',$disable_controls)){  
                        // Shadow
                        $this->add_group_control(
                            Group_Control_Box_Shadow:: get_type(),
                            [
                                'name'     => 'hover_'.$element_name.'_shadow',
                                'selector' => $hover_selector,
                            ]
                        );
                    }
                    if(!in_array('css',$disable_controls)){  
                        $this->add_responsive_control(
                            $widget.'main_section_'.$element_name.'_element_ready_control_hover_custom_css',
                            [
                                'label'     => esc_html__( 'Custom CSS', 'element-ready-lite' ),
                                'type'      => Controls_Manager::CODE,
                                'rows'      => 20,
                                'language'  => 'css',
                                'selectors' => [
                                    $hover_selector => '{{VALUE}};',
                                
                                ],
                                'separator' => 'before',
                            ]
                        );
                    }
                    
                $this->end_controls_tab();
                } // hover_select check end
            $this->end_controls_tabs();

            if(!in_array('display',$disable_controls)){  

                $this->add_responsive_control(
                    $widget.'_section___section_show_hide_'.$element_name.'_display',
                    [
                        'label' => esc_html__( 'Display', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            'flex'         => esc_html__( 'Flex', 'element-ready-lite' ),
                            'inline-flex'  => esc_html__( 'Inline Flex', 'element-ready-lite' ),
                            'block'        => esc_html__( 'Block', 'element-ready-lite' ),
                            'inline-block' => esc_html__( 'Inline Block', 'element-ready-lite' ),
                            'grid'         => esc_html__( 'Grid', 'element-ready-lite' ),
                            'none'         => esc_html__( 'None', 'element-ready-lite' ),
                            ''             => esc_html__( 'Default', 'element-ready-lite' ),
                        ],
                        'selectors' => [
                        $selector => 'display: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    $widget.'_section___section_flex_direction_'.$element_name.'_display',
                    [
                        'label' => esc_html__( 'Flex Direction', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            'column'         => esc_html__( 'Column', 'element-ready-lite' ),
                            'row'            => esc_html__( 'Row', 'element-ready-lite' ),
                            'column-reverse' => esc_html__( 'Column Reverse', 'element-ready-lite' ),
                            'row-reverse'    => esc_html__( 'Row Reverse', 'element-ready-lite' ),
                            'revert'         => esc_html__( 'Revert', 'element-ready-lite' ),
                            'none'           => esc_html__( 'None', 'element-ready-lite' ),
                            ''               => esc_html__( 'inherit', 'element-ready-lite' ),
                        ],
                        'selectors' => [
                            $selector => 'flex-direction: {{VALUE}};'
                        ],
                        'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['flex','inline-flex'] ]
                    ]
                    
                );

            
                $this->add_responsive_control(
                    $widget.'_section__s_section_flexr_wrap_'.$element_name.'_display',
                    [
                        'label' => esc_html__( 'Flex Wrap', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            'wrap'         => esc_html__( 'Wrap', 'element-ready-lite' ),
                            'wrap-reverse' => esc_html__( 'Wrap Reverse', 'element-ready-lite' ),
                            'nowrap'       => esc_html__( 'No Wrap', 'element-ready-lite' ),
                            'unset'        => esc_html__( 'Unset', 'element-ready-lite' ),
                            'normal'       => esc_html__( 'None', 'element-ready-lite' ),
                            'inherit'      => esc_html__( 'inherit', 'element-ready-lite' ),
                        ],
                        'selectors' => [
                            $selector => 'flex-wrap: {{VALUE}};'
                        ],
                        'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['flex','inline-flex'] ]
                    ]
                    
                );

                $this->add_responsive_control(
                    $widget.'_section_align_sessctionr_e_'.$element_name.'_flex_align',
                    [
                        'label' => esc_html__( 'Alignment', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            'flex-start'    => esc_html__( 'Left', 'element-ready-lite' ),
                            'flex-end'      => esc_html__( 'Right', 'element-ready-lite' ),
                            'center'        => esc_html__( 'Center', 'element-ready-lite' ),
                            'space-around'  => esc_html__( 'Space Around', 'element-ready-lite' ),
                            'space-between' => esc_html__( 'Space Between', 'element-ready-lite' ),
                            'space-evenly'  => esc_html__( 'Space Evenly', 'element-ready-lite' ),
                            ''              => esc_html__( 'inherit', 'element-ready-lite' ),
                        ],
                        'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['flex','inline-flex'] ],

                        'selectors' => [
                            $selector => 'justify-content: {{VALUE}};'
                    ],
                    ]
                    
                );

                $this->add_responsive_control(
                    $widget.'er_section_align_items_rssection_e_'.$element_name.'_flex_align',
                    [
                        'label' => esc_html__( 'Align Items', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            'flex-start' => esc_html__( 'Left', 'element-ready-lite' ),
                            'flex-end'   => esc_html__( 'Right', 'element-ready-lite' ),
                            'center'     => esc_html__( 'Center', 'element-ready-lite' ),
                            'baseline'   => esc_html__( 'Baseline', 'element-ready-lite' ),
                            ''           => esc_html__( 'inherit', 'element-ready-lite' ),
                        ],
                        'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['flex','inline-flex'] ],

                        'selectors' => [
                            $selector => 'align-items: {{VALUE}};'
                    ],
                    ]
                    
                );
            }
            if(!in_array('position',$disable_controls)){  
            $this->add_control(
                $widget.'_section___section_popover_'.$element_name.'_position',
                [
                    'label'        => esc_html__( 'Position', 'element-ready-lite' ),
                    'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                    'label_off'    => esc_html__( 'Default', 'element-ready-lite' ),
                    'label_on'     => esc_html__( 'Custom', 'element-ready-lite' ),
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
                $widget.'main_section_'.$element_name.'_r_position_top',
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
                        $selector  => 'bottom: {{SIZE}}{{UNIT}};',
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


  }