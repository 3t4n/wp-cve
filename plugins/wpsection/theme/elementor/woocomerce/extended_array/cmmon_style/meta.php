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





// bosch  aaa

//===================Meta Controll Area =====================

        $this->start_controls_section(
            'meta_position_settings',
            array(
                'label' => __( 'Meta Position Setting', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );



    $this->add_control(
            'show_meta',
            array(
                'label' => __( 'Show Meta Area ', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__( 'Show', 'wpsection' ), 
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__( 'Hide', 'wpsection' ),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .mr_pro_list' => 'display: {{VALUE}} !important',
                ),
            )
        );  

   $this->add_control(
            'meta_position',
            array(
                'label' => esc_html__( 'Vertical/Horizontal', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Horizontal', 'wpsection' ),
                        'icon' => ' eicon-gallery-grid',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Vertical', 'wpsection' ),
                        'icon' => ' eicon-toggle',
                    ],
                ],
                'default' => 'column',
                'toggle' => true,
                'selectors' => array(
                    '{{WRAPPER}} .mr_pro_list' => 'flex-direction: {{VALUE}} !important',
                ),
            )
        );  
        
       $this->add_control(
            'meta_alingment',
            array(
                'label' => esc_html__( 'Alignment', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'condition'    => array( 'meta_position' => 'column' ),
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'wpsection' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpsection' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'wpsection' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'flex-end',
               
                'toggle' => true,
                'selectors' => array(
                    '{{WRAPPER}} .mr_pro_list' => 'align-items: {{VALUE}} !important',
                ),
            )
        );  




     $this->add_control(
            'quick_margin_ul',
            array(
                'label'     => __( 'Area Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .mr_pro_list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
                 'separator' => 'after',
            )
        );

    $this->end_controls_section();
 //Code of Meta feild

       
$this->start_controls_section(
            'meta_button_control',
            array(
                'label' => __( 'Meta Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                
            )
        );

        $this->add_control(
            'meta_title_color',
            array(
                'label'     => __( 'Icon Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_pro_list li i' => 'color: {{VALUE}} !important',
                ),
             
            )
        );



     $this->add_control(
            'meta_bg_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_pro_list .single_metas' => 'background: {{VALUE}} !important',
                ),
            )
        );  
$this->add_control(
            'meta_hover_color',
            array(
                'label'     => __( 'Hover Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_pro_list .single_metas:hover' => 'background: {{VALUE}} !important',
                ),
            )
        ); 
$this->add_control(
            'meta_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_pro_list li i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


$this->add_control(
            'meta_icon_width',
            [
                'label' => esc_html__( 'Icon Box Width ', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_pro_list li' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
$this->add_control(
            'meta_icon_height',
            [
                'label' => esc_html__( 'Icon Box Height ', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_pro_list li' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


$this->add_control(
            'meta_box_lineheight',
            [
                'label' => esc_html__( 'Icon Box Lineheight ', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_pro_list li' => 'line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'meta_view_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .mr_pro_list .single_metas' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
            $this->add_control(
            'quick_view_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .mr_pro_list .single_metas' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        



    $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'quick_view_border',
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_pro_list .single_metas',
            ]
        );  
        
            $this->add_control(
            'quick_view_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .mr_pro_list .single_metas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        ); 




          $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'meta_shadow',

                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_pro_list .single_metas',
               
            ]
        );
    $this->end_controls_section();

//tooltip area

$this->start_controls_section(
            'tooltip_button_control',
            array(
                'label' => __( 'Tool Tip Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                
            )
        );
    $this->add_control(
            'show_tooltip',
            array(
                'label' => __( 'Show Tool Tip', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__( 'Show', 'wpsection' ), 
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__( 'Hide', 'wpsection' ),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .tool_tip' => 'display: {{VALUE}} !important',
                ),
                 'separator' => 'before',
            )
        );        
     
     $this->add_control(
            'tooltip_alingment',
            array(
                'label' => esc_html__( 'Tooltip Style ', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'mr_tooltip_left' => [
                        'title' => esc_html__( 'Left', 'wpsection' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                  
                    'mr_tooltip_right' => [
                        'title' => esc_html__( 'Right', 'wpsection' ),
                        'icon' => ' eicon-h-align-right',
                    ],
                    'mr_tooltip_top' => [
                        'title' => esc_html__( 'Top', 'wpsection' ),
                        'icon' => ' eicon-v-align-top',
                    ],

                      'mr_tooltip_bottom' => [
                        'title' => esc_html__( 'Bottom', 'wpsection' ),
                        'icon' => ' eicon-v-align-bottom',
                    ],

                ],
                'default' => 'mr_tooltip_left',
               
                'toggle' => true,
              
            )
        );  

    $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'tooltip_typography',
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_pro_list .tool_tip',
            )
        );  

  $this->add_control(
            'tooltip_color',
            array(
                'label'     => __( 'Tooltip Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_pro_list .tool_tip' => 'color: {{VALUE}} !important',
                ),
            )
        );

$this->add_control(
            'tooltip_bgcolor',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_pro_list .tool_tip' => 'background: {{VALUE}} !important',
                ),
            )
        );

      
    
        $this->add_control(
            'tooltip_margin',
            array(
                'label'     => __( 'Tooltip Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .mr_pro_list .tool_tip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'tooltip_padding',
            array(
                'label'     => __( 'Tooltip Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .mr_pro_list .tool_tip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

  
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tooltip_border',
                'label' => esc_html__( 'Tooltip Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_pro_list .tool_tip',
            ]
        );
                
            $this->add_control(
            'tooltip_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_pro_list .tool_tip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

 
     $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tooltip_shadow',
                'label' => esc_html__( 'Tooltip Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_pro_list .tool_tip',
            ]
        );

 

        $this->end_controls_section();