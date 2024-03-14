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



    $this->start_controls_section(
                'expand_bottom_settings',
                array(
                    'label' => __( 'Expand Bottom Setting', 'wpsection' ),
                    'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                )
            );

        
    $this->add_control(
            'show_expand_block_bottom',
            array(
                'label' => esc_html__( 'Show Bottom', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
				 'condition'    => array( 'wps_columns_expand' => 'bottom' ),
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
                    '{{WRAPPER}} .mr_style_one .hide_content' => 'display: {{VALUE}} !important',
                ),
            )
        );  


        

$this->add_control(
            'box_expand_bottom_height',
            [
                'label' => esc_html__( 'Min Height', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'condition'    => array( 'show_expand_block_bottom' => 'show' ),
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_style_one .hide_content' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        


        $this->add_control(
            'block_expand_bottom_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                'condition'    => array( 'show_expand_block_bottom' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_style_one .hide_content' => 'background: {{VALUE}} !important',
                ),
            )
        );


        $this->add_control(
            'block_expand_bottom_hover_color',
            array(
                'label'     => __( 'Hover Color', 'wpsection' ),
               'condition'    => array( 'show_expand_block_bottom' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_style_one .hide_content:hover' => 'background: {{VALUE}} !important',
                
                    
                    
                ),
            )
        );
    
        $this->add_control(
            'block_expand_bottom_margin',
            array(
                'label'     => __( 'Block Margin', 'wpsection' ),
                 'condition'    => array( 'show_expand_block_bottom' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .mr_style_one .hide_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );



        $this->add_control(
            'block_expand_bottom_padding',
            array(
                'label'     => __( 'Block Padding', 'wpsection' ),
                  'condition'    => array( 'show_expand_block_bottom' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .mr_style_one .hide_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

		$this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'block_m_hover_shadow',
                    'condition'    => array( 'show_expand_block_bottom' => 'show' ),
                'label' => esc_html__( 'Hover Bottom Expand Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_style_one:hover .hide_content',
            ]
        );


   $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'block_expand_border',
                    'condition'    => array( 'show_expand_block_bottom' => 'show' ),
                'label' => esc_html__( 'Box Border Expand Bottom', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_style_one .hide_content',
            ]
        );

      $this->add_control(
            'block_expand_bottom_radius',
            array(
                'label'     => __( 'Border Radius Expand Bottom', 'wpsection' ),
                     'condition'    => array( 'show_expand_block_bottom' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_style_one .hide_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );











        $this->end_controls_section();

                
        
//Product Bottom area  