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


//Special Offer Button 

$this->start_controls_section(
            'spcl_button_control',
            array(
                'label' => __( 'Special Offer Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                
            )
        );
        
        $this->add_control(
                    'spcl_show_button',
                    array(
                        'label' => esc_html__( 'Show Button', 'wpsection' ),
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
                            '{{WRAPPER}} .mr_spcl' => 'display: {{VALUE}} !important',

                        ),
                    )
                );      
        $this->add_control(
                    'spcl_button_alingment',
                    array(
                        'label' => esc_html__( 'Alignment', 'wpsection' ),
                        'type' => \Elementor\Controls_Manager::CHOOSE,
                        'condition'    => array( 'spcl_show_button' => 'show' ),
                        'options' => [
                            'left' => [
                                'title' => esc_html__( 'Left', 'wpsection' ),
                                'icon' => 'eicon-text-align-left',
                            ],
                            'center' => [
                                'title' => esc_html__( 'Center', 'wpsection' ),
                                'icon' => 'eicon-text-align-center',
                            ],
                            'right' => [
                                'title' => esc_html__( 'Right', 'wpsection' ),
                                'icon' => 'eicon-text-align-right',
                            ],
                        ],
                        'default' => 'left',
                        'toggle' => true,
                        'selectors' => array(
                            '{{WRAPPER}} .mr_spcl' => 'text-align: {{VALUE}} !important',
                        ),
                    )
                );  

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'spcl_button_typography',
                'condition'    => array( 'spcl_show_button' => 'show' ),
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_spcl .spcl_text',
            )
        );      
        $this->add_control(
                    'spcl_button_color',
                    array(
                        'label'     => __( 'Button Color', 'wpsection' ),
                        'condition'    => array( 'spcl_show_button' => 'show' ),
                        'type'      => \Elementor\Controls_Manager::COLOR,
                        'selectors' => array(
                            '{{WRAPPER}} .mr_spcl .spcl_text' => 'color: {{VALUE}} !important',

                        ),
                    )
                );
        $this->add_control(
                    'spcl_button_bg_color',
                    array(
                        'label'     => __( 'Background Color', 'wpsection' ),
                        'condition'    => array( 'spcl_show_button' => 'show' ),
                        'type'      => \Elementor\Controls_Manager::COLOR,
                        'selectors' => array(
                            '{{WRAPPER}} .mr_spcl .spcl_text' => 'background: {{VALUE}} !important',
                        ),
                    )
                );  
            
    $this->add_control(
            'spcl_button_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array( 'spcl_show_button' => 'show' ),
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .mr_spcl .spcl_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            ) 
        );

    $this->add_control(
            'spcl_button_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array( 'spcl_show_button' => 'show' ),
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_spcl .spcl_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'spcl_border',
                'condition'    => array( 'spcl_show_button' => 'show' ),
                'selector' => '{{WRAPPER}} .mr_spcl .spcl_text',
            )
        );

        $this->add_control(
            'spcl_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'condition'    => array( 'spcl_show_button' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_spcl .spcl_text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'spcl_shadow',
                'condition'    => array( 'spcl_show_button' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_spcl .spcl_text',
            ]
        );

        
        
    
        
        $this->end_controls_section();
        

//End of Special Offer   
//Offer Text
   $this->start_controls_section(
            'product_offerx_settings',
            array(
                'label' => __( 'Offer Text Setting', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'    => array( 'show_offer_x_event' => '1' ),
            )
        );
        
        
    $this->add_control(
            'show_offerx_title',
            array(
                'label' => esc_html__( 'Show Title', 'wpsection' ),
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
                    '{{WRAPPER}} .wps_offer_text' => 'display: {{VALUE}} !important',
                ),
            )
        );  
    $this->add_control(
            'title_offerx_alingment',
            array(
                'label' => esc_html__( 'Alignment', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpsection' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpsection' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpsection' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'condition'    => array( 'show_offerx_title' => 'show' ),
                'toggle' => true,
                'selectors' => array(
                
                    '{{WRAPPER}} .wps_offer_text' => 'text-align: {{VALUE}} !important',
                ),
            )
        );          


    $this->add_control(
            'offerx_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'condition'    => array( 'show_offerx_title' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_offer_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

       $this->add_control(
            'cofferx_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'condition'    => array( 'show_offerx_title' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_offer_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'offerx_typography',
                'condition'    => array( 'show_offerx_title' => 'show' ),
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_offer_text p',
            )
        );

        $this->add_control(
            'offerx_color',
            array(
                'label'     => __( 'Color', 'wpsection' ),
                'condition'    => array( 'show_offerx_title' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_offer_text p' => 'color: {{VALUE}} !important',
        
                ),
            )
        );

        $this->end_controls_section();
    
        