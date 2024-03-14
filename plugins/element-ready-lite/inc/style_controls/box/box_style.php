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

trait Element_Ready_Box_Style {

    public function box_css($atts) {
        
        $atts_variable = shortcode_atts(
            array(
                'title'        => esc_html__('Box Style','element-ready-lite'),
                'slug'         => '_box_style',
                'element_name' => '_element_ready_',
                'selector'     => '{{WRAPPER}} ',
                'condition'    => '',
                'disable_controls'    => [],
            ), $atts );

        extract($atts_variable);    

        $widget = $this->get_name().'_'.element_ready_heading_camelize($slug);

        $tab_start_section_args =  [
            'label' => $title,
            'tab'   => Controls_Manager::TAB_STYLE,
        ];
        
        if(is_array($condition)){
            $tab_start_section_args['condition'] = $condition;
        }
        /*----------------------------
            ELEMENT__STYLE
        -----------------------------*/
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

                if(!in_array('bg',$disable_controls)){

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
                  }

                  if(!in_array('border',$disable_controls)){
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
                   }

                   if(!in_array('box-shadow',$disable_controls)){
                        // Shadow
                        $this->add_group_control(
                            Group_Control_Box_Shadow::get_type(),
                            [
                                'name'     => $widget.'_shadow',
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

                     }  

                     if(!in_array('css',$disable_controls)){  
                            $this->add_responsive_control(
                                $widget.'main_section_element_ready_yu_custom_css',
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

                $this->end_controls_tab();

       
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
                                'inine-grid'   => esc_html__( 'Inline Grid', 'element-ready-lite' ),
                                'none'         => esc_html__( 'None', 'element-ready-lite' ),
                                ''             => esc_html__( 'inherit', 'element-ready-lite' ),
                            ],
                            'selectors' => [
                                $selector => 'display: {{VALUE}};'
                        ],
                        ]
                        
                    );

                    $this->add_responsive_control(
                        $widget . '_section_align_items_section_e_y' . $element_name . '_grid_align_place_content',
                        [
                            'label'     => esc_html__( 'Place Content', 'element-ready-lite' ),
                            'type'      => \Elementor\Controls_Manager::SELECT,
                            'default'   => 'center',
                            'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['grid', 'inline-grid']],
                            'options'   => [
                                'start'                => esc_html__( 'Start / Left', 'element-ready-lite' ),
                                'end'                  => esc_html__( 'Right / End', 'element-ready-lite' ),
                                'center'               => esc_html__( 'Center', 'element-ready-lite' ),
                                'center start'         => esc_html__( 'center Left', 'element-ready-lite' ),
                                'center end'           => esc_html__( 'center end', 'element-ready-lite' ),
                                'center stretch'       => esc_html__( 'center stretch', 'element-ready-lite' ),
                                'end space-between'    => esc_html__( 'end space-between', 'element-ready-lite' ),
                                'start space-between'  => esc_html__( 'left space-between', 'element-ready-lite' ),
                                'center space-between' => esc_html__( 'center space-between', 'element-ready-lite' ),
                                'center space-evenly'  => esc_html__( 'center space-evenly', 'element-ready-lite' ),
                                'start space-evenly'   => esc_html__( 'start space-evenly', 'element-ready-lite' ),
                                'end space-evenly'     => esc_html__( 'end space-evenly', 'element-ready-lite' ),
            
                                ''                     => esc_html__( 'default', 'element-ready-lite' ),
                            ],
            
                            'selectors' => [
                                $selector => 'place-content: {{VALUE}};',
                            ],
                        ]
            
                    );
    
                    $this->add_responsive_control(
                        $widget . '_section_align_items_section_e_s' . $element_name . '_grid_justify_items_align',
                        [
                            'label'     => esc_html__( 'Place Self Column', 'element-ready-lite' ),
                            'type'      => \Elementor\Controls_Manager::SELECT,
                            'default'   => 'left',
                            'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['grid','inline-grid'] ],
                            'options'   => [
                                'start auto'           => esc_html__( 'Start / Left', 'element-ready-lite' ),
                                'end normal'           => esc_html__( 'End / Right', 'element-ready-lite' ),
                                'center normal'        => esc_html__( 'Center', 'element-ready-lite' ),
                                'baseline normal'      => esc_html__( 'Baseline', 'element-ready-lite' ),
                                'stretch auto'         => esc_html__( 'Stretch', 'element-ready-lite' ),
                                'first baseline auto'  => esc_html__( 'First Base', 'element-ready-lite' ),
                                'last baseline normal' => esc_html__( 'last baseline normal', 'element-ready-lite' ),
                                'space-between'        => esc_html__( 'space-between', 'element-ready-lite' ),
                                ''                     => esc_html__( 'inherit', 'element-ready-lite' ),
                            ],
            
                            'selectors' => [
                                $selector => 'place-self: {{VALUE}};',
                            ],
                        ]
            
                    );
    
                    $this->add_responsive_control(
                        $widget . 'main_section_ed' . $element_name . '_grid_cols_gap',
                        [
                            'label'      => esc_html__( 'Columns Gap', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['grid','inline-grid','flex'] ],
                            'size_units' => ['px'],
                            'range'      => [
            
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 800,
                                    'step' => 1,
                                ],
            
                            ],
            
                            'selectors'  => [
                                $selector => 'column-gap: {{SIZE}}{{UNIT}};',
                                $selector => 'gap: {{SIZE}}{{UNIT}};',
            
                            ],
                        ]
                    );
    
                    $this->add_responsive_control(
                        $widget . 'main_section_we' . $element_name . '_grid_row_gap',
                        [
                            'label'      => esc_html__( 'Row Gap', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['grid','inline-grid'] ],
                            'size_units' => ['px'],
                            'range'      => [
            
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 500,
                                    'step' => 1,
                                ],
            
                            ],
            
                            'selectors'  => [
                                $selector => 'row-gap: {{SIZE}}{{UNIT}};',
            
                            ],
                        ]
                    );
            
                    $this->add_responsive_control(
                        $widget . 'main_section_wet' . $element_name . '_grid_col',
                        [
                            'label'      => esc_html__( 'Column', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['grid','inline-grid'] ],
                            'size_units' => ['px'],
                            'range'      => [
            
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 10,
                                    'step' => 1,
                                ],
            
                            ],
            
                            'selectors'  => [
                                $selector => 'grid-template-columns: repeat( {{SIZE}}, 1fr);',
            
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
                        $widget.'_section___section_flex_wrap_'.$element_name.'_display',
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
                        $widget.'_section_align_section_e_'.$element_name.'_flex_align',
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
                        $widget.'_section_align_items_section_e_'.$element_name.'_flex_align',
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
            }

        if(!in_array('size',$disable_controls)){  

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
        }


        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

    public function box_layout_css($atts) {
        
        $atts_variable = shortcode_atts(
            array(
                'title'        => esc_html__('Box Style','element-ready-lite'),
                'slug'         => '_box_style',
                'element_name' => '_element_ready_',
                'selector'     => '{{WRAPPER}} ',
                'condition'    => '',
                'disable_controls'    => [],
            ), $atts );

        extract($atts_variable);    

        $widget = $this->get_name().'_'.element_ready_heading_camelize($slug);

        $tab_start_section_args =  [
            'label' => $title,
            'tab'   => Controls_Manager::TAB_STYLE,
        ];
        
        if(is_array($condition)){
            $tab_start_section_args['condition'] = $condition;
        }
        /*----------------------------
            ELEMENT__STYLE
        -----------------------------*/
        $this->start_controls_section(
            $widget.'_style_section',
            $tab_start_section_args
        );
    

            $this->add_responsive_control(
                $widget.'_section___section_show_hide_'.$element_name.'_display',
                [
                    'label' => esc_html__( 'Display', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'flex'         => esc_html__( 'Flex', 'element-ready-lite' ),
                        'inline-flex'         => esc_html__( 'Inline Flex', 'element-ready-lite' ),
                        'block'        => esc_html__( 'Block', 'element-ready-lite' ),
                        'inline-block' => esc_html__( 'Inline Block', 'element-ready-lite' ),
                        'grid'         => esc_html__( 'Grid', 'element-ready-lite' ),
                        'inine-grid'         => esc_html__( 'Inline Grid', 'element-ready-lite' ),
                        'none'         => esc_html__( 'None', 'element-ready-lite' ),
                        ''             => esc_html__( 'inherit', 'element-ready-lite' ),
                    ],
                    'selectors' => [
                        $selector => 'display: {{VALUE}};'
                  ],
                ]
                
            );

            $this->add_responsive_control(
                $widget . '_section_align_items_section_e_y' . $element_name . '_grid_align_place_content',
                [
                    'label'     => esc_html__( 'Place Content', 'element-ready-lite' ),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => 'center',
                    'condition' => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['grid', 'inline-grid']],
                    'options'   => [
                        'start'                => esc_html__( 'Start / Left', 'element-ready-lite' ),
                        'end'                  => esc_html__( 'Right / End', 'element-ready-lite' ),
                        'center'               => esc_html__( 'Center', 'element-ready-lite' ),
                        'center start'         => esc_html__( 'center Left', 'element-ready-lite' ),
                        'center end'           => esc_html__( 'center end', 'element-ready-lite' ),
                        'center stretch'       => esc_html__( 'center stretch', 'element-ready-lite' ),
                        'end space-between'    => esc_html__( 'end space-between', 'element-ready-lite' ),
                        'start space-between'  => esc_html__( 'left space-between', 'element-ready-lite' ),
                        'center space-between' => esc_html__( 'center space-between', 'element-ready-lite' ),
                        'center space-evenly'  => esc_html__( 'center space-evenly', 'element-ready-lite' ),
                        'start space-evenly'   => esc_html__( 'start space-evenly', 'element-ready-lite' ),
                        'end space-evenly'     => esc_html__( 'end space-evenly', 'element-ready-lite' ),
    
                        ''                     => esc_html__( 'default', 'element-ready-lite' ),
                    ],
    
                    'selectors' => [
                        $selector => 'place-content: {{VALUE}};',
                    ],
                ]
    
            );
    
            $this->add_responsive_control(
                $widget . '_section_align_items_section_e_s' . $element_name . '_grid_justify_items_align',
                [
                    'label'     => esc_html__( 'Place Self Column', 'element-ready-lite' ),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => 'left',
                    'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['grid','inline-grid'] ],
                    'options'   => [
                        'start auto'           => esc_html__( 'Start / Left', 'element-ready-lite' ),
                        'end normal'           => esc_html__( 'End / Right', 'element-ready-lite' ),
                        'center normal'        => esc_html__( 'Center', 'element-ready-lite' ),
                        'baseline normal'      => esc_html__( 'Baseline', 'element-ready-lite' ),
                        'stretch auto'         => esc_html__( 'Stretch', 'element-ready-lite' ),
                        'first baseline auto'  => esc_html__( 'First Base', 'element-ready-lite' ),
                        'last baseline normal' => esc_html__( 'last baseline normal', 'element-ready-lite' ),
                        'space-between'        => esc_html__( 'space-between', 'element-ready-lite' ),
                        ''                     => esc_html__( 'inherit', 'element-ready-lite' ),
                    ],
    
                    'selectors' => [
                        $selector => 'place-self: {{VALUE}};',
                    ],
                ]
    
            );
    
            $this->add_responsive_control(
                $widget . 'main_section_ed' . $element_name . '_grid_cols_gap',
                [
                    'label'      => esc_html__( 'Columns Gap', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['grid','inline-grid','flex'] ],
                    'size_units' => ['px'],
                    'range'      => [
    
                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],
    
                    ],
    
                    'selectors'  => [
                        $selector => 'column-gap: {{SIZE}}{{UNIT}};',
                        $selector => 'gap: {{SIZE}}{{UNIT}};',
    
                    ],
                ]
            );
    
            $this->add_responsive_control(
                $widget . 'main_section_we' . $element_name . '_grid_row_gap',
                [
                    'label'      => esc_html__( 'Row Gap', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['grid','inline-grid'] ],
                    'size_units' => ['px'],
                    'range'      => [
    
                        'px' => [
                            'min'  => 0,
                            'max'  => 500,
                            'step' => 1,
                        ],
    
                    ],
    
                    'selectors'  => [
                        $selector => 'row-gap: {{SIZE}}{{UNIT}};',
    
                    ],
                ]
            );
    
            $this->add_responsive_control(
                $widget . 'main_section_wet' . $element_name . '_grid_col',
                [
                    'label'      => esc_html__( 'Column', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['grid','inline-grid'] ],
                    'size_units' => ['px'],
                    'range'      => [
    
                        'px' => [
                            'min'  => 0,
                            'max'  => 10,
                            'step' => 1,
                        ],
    
                    ],
    
                    'selectors'  => [
                        $selector => 'grid-template-columns: repeat( {{SIZE}}, 1fr);',
    
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
                $widget.'_section___section_flex_wrap_'.$element_name.'_display',
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
                $widget.'_section_align_section_e_'.$element_name.'_flex_align',
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
                $widget.'_section_align_items_section_e_'.$element_name.'_flex_align',
                [
                    'label' => esc_html__( 'Align Items', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'flex-start'    => esc_html__( 'Left', 'element-ready-lite' ),
                        'flex-end'      => esc_html__( 'Right', 'element-ready-lite' ),
                        'center'        => esc_html__( 'Center', 'element-ready-lite' ),
                        'baseline'  => esc_html__( 'Baseline', 'element-ready-lite' ),
                        ''              => esc_html__( 'inherit', 'element-ready-lite' ),
                    ],
                    'condition' => [ $widget.'_section___section_show_hide_'.$element_name.'_display' => ['flex','inline-flex'] ],

                    'selectors' => [
                        $selector => 'align-items: {{VALUE}};'
                   ],
                ]
                
            );
   
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

   
        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

    public function box_minimum_css( $atts ) {

        $atts_variable = shortcode_atts(
            [
                'title'        => esc_html__( 'Box Style', 'element-ready-lite' ),
                'slug'         => 'mini_box_style',
                'element_name' => '__er_ready__',
                'selector'     => '{{WRAPPER}} ',
                'condition'    => '',
                'disable_controls'    => [],
                'tab'          => Controls_Manager::TAB_STYLE,

            ], $atts );

        extract( $atts_variable );

        $widget = $this->get_name() . '_' . element_ready_heading_camelize( $slug );

        $tab_start_section_args = [
            'label' => $title,
            'tab'   => $tab,
        ];

        if ( is_array( $condition ) ) {
            $tab_start_section_args['condition'] = $condition;
        }

        /*----------------------------
        ELEMENT__STYLE
        -----------------------------*/
        $this->start_controls_section(
            $widget . '_style_section',
            $tab_start_section_args
        );

        $this->start_controls_tabs( $widget . '_tabs_style' );
        $this->start_controls_tab(
            $widget . '_normal_tab',
            [
                'label' => esc_html__( 'Style', 'element-ready-lite' ),
            ]
        );

        if(!in_array('bg',$disable_controls)){  
            // Background
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => $widget . '_background',
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
                    'name'     => $widget . '_border',
                    'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                    'selector' => $selector,
                ]
            );

            // Radius
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
                $widget . '_margin',
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

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        if(!in_array('display',$disable_controls)){  
            
            $this->add_responsive_control(
                $widget . '_section___section_show_hide_' . $element_name . '_display',
                [
                    'label'     => esc_html__( 'Display', 'element-ready-lite' ),
                    'type'      => \Elementor\Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => [
                        'flex'         => esc_html__( 'Flex Layout', 'element-ready-lite' ),
                        'block'        => esc_html__( 'Block Layout', 'element-ready-lite' ),
                        'inline-block' => esc_html__( 'Inline Layout', 'element-ready-lite' ),
                        'none'         => esc_html__( 'None', 'element-ready-lite' ),
                        ''             => esc_html__( 'inherit', 'element-ready-lite' ),
                    ],
                    'selectors' => [
                        $selector => 'display: {{VALUE}};',
                    ],
                ]

            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_basis',
                [
                    'label'      => esc_html__( 'Item Width', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['inherit']],
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],
                        '%'  => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'flex-basis: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_' . $element_name . '_flex_order',
                [
                    'label'      => esc_html__( 'Item Order', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex', 'inline-flex', 'inherit', 'initial', 'grid']],
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'order: {{SIZE}}',

                    ],
                ]
            );

            $this->add_responsive_control(
                $widget . 'main_section_ed' . $element_name . '_grid_colss_gap',
                [
                    'label'      => esc_html__( 'Columns Gap', 'element-ready-lite' ),
                    'type'       => Controls_Manager::SLIDER,
                    'condition'  => [$widget . '_section___section_show_hide_' . $element_name . '_display' => ['flex','grid']],
                    'size_units' => ['px'],
                    'range'      => [

                        'px' => [
                            'min'  => 0,
                            'max'  => 800,
                            'step' => 1,
                        ],

                    ],

                    'selectors'  => [
                        $selector => 'column-gap: {{SIZE}}{{UNIT}};',
                        $selector => 'gap: {{SIZE}}{{UNIT}};',

                    ],
                ]
            );
        }

        $this->end_controls_section();
        /*----------------------------
    ELEMENT__STYLE END
    -----------------------------*/
    }


  }