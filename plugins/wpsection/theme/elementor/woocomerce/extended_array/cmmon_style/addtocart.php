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


 // Button Setting
    
$this->start_controls_section(
            'wps_button_control',
            array(
                'label' => __( 'Add to Cart Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'    => array( 'show_prduct_x_button' => '1' ),
            )
        );
        

 $this->add_control(
                    'wps_button_x_alingment',
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
                        'default' => 'center',
                        'toggle' => true,
                        'selectors' => array(
                            '{{WRAPPER}}  .quick_defult_wps' => 'text-align: {{VALUE}} !important',
                        ),
                    )
                );  

        
        
$this->add_control(
            'wps_button_color',
            array(
                'label'     => __( 'Button Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
    
                'selectors' => array(
                    '{{WRAPPER}}   .wps_button' => 'color: {{VALUE}} !important',

                ),
            )
        );
$this->add_control(
            'wps_button_color_hover',
            array(
                'label'     => __( 'Button Hover Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .quick_defult_wps .wps_button:hover' => 'color: {{VALUE}} !important',

                ),
            )
        );
$this->add_control(
            'wps_button_bg_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .quick_defult_wps .wps_button' => 'background: {{VALUE}} !important',
                ),
            )
        );  
$this->add_control(
            'wps_button_hover_color',
            array(
                'label'     => __( 'Background Hover Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .quick_defult_wps .wps_button:hover' => 'background: {{VALUE}} !important',
                    '{{WRAPPER}} .cart-btn button:before' => 'background-color: {{VALUE}} !important',
                ),
            )
        );  
        
        
    
$this->add_control( 'wps_button_width',
                    [
                        'label' => esc_html__( 'Width',  'wpsection' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 2000,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        
                        'selectors' => [
                            '{{WRAPPER}} .quick_defult_wps .wps_button' => 'width: {{SIZE}}{{UNIT}};',
                        ]
                    
                    ]
                );
        

    $this->add_control( 'wps_button_height',
                    [
                        'label' => esc_html__( ' Height', 'wpsection' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 1000,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        
                        'selectors' => [
                            '{{WRAPPER}} .quick_defult_wps .wps_button' => 'height: {{SIZE}}{{UNIT}};',
                    
                        ]
                    ]
                );      
            
    
        
    $this->add_control(
            'wps_button_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .quick_defult_wps .wps_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

    $this->add_control(
            'wps_button_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}}  .quick_defult_wps .wps_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_button_typography',
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}}  .quick_defult_wps .wps_button',
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'wps_button_border',
                'selector' => '{{WRAPPER}}  .quick_defult_wps .wps_button ',
            )
        );
    

        $this->add_control(
            'wps_button_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .quick_defult_wps .wps_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


            $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_button_shadow',
                
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .quick_defult_wps .wps_button',
            ]
        );




$this->add_control(
    'wps_qnt_simbol_icon_separator',
    array(
        'type' => \Elementor\Controls_Manager::DIVIDER,
    )
);


$this->add_control(
            'wps_addto_icon_color',
            array(
                'label'     => __( 'Icon Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
    
                'selectors' => array(
                    '{{WRAPPER}}   .wps_cart_qnt .quick_defult_wps i' => 'color: {{VALUE}} !important',

                ),
            )
        );
$this->add_control(
            'wps_addto_icon_color_hover',
            array(
                'label'     => __( 'Icon Hover Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wps_cart_qnt .quick_defult_wps:hover i' => 'color: {{VALUE}} !important',

                ),
            )
        );
$this->add_control(
            'wps_addto_icon_bg_color',
            array(
                'label'     => __( 'Icon Background Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .wps_cart_qnt .quick_defult_wps i' => 'background: {{VALUE}} !important',
                ),
            )
        );  
$this->add_control(
            'wps_addto_icon_hover_color',
            array(
                'label'     => __( 'Icon Background Hover Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .wps_cart_qnt .quick_defult_wps i:hover' => 'background: {{VALUE}} !important',
        
                ),
            )
        );  
        
    $this->add_control(
            'wps_addto_icon_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wps_cart_qnt .quick_defult_wps i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

    $this->add_control(
            'wps_addto_icon_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_cart_qnt .quick_defult_wps i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_addto_icon_typography',
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}}  .wps_cart_qnt .quick_defult_wps i',
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'wps_addto_icon_border',
                'selector' => '{{WRAPPER}}  .wps_cart_qnt .quick_defult_wps i ',
            )
        );
    

        $this->add_control(
            'wps_addto_icon_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_cart_qnt .quick_defult_wps i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


            $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_addto_icon_shadow',
                
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_cart_qnt .quick_defult_wps i',
            ]
        );
        
        $this->end_controls_section();  

// Button Setting
    
$this->start_controls_section(
            'wps_qnt_button_control',
            array(
                'label' => __( 'Quantity Button Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                 'condition'    => array( 'wps_product_qun_hide' => '1' ),
            )
        );
        

 $this->add_control(
                    'wps_qnt_button_x_alingment',
                    array(
                        'label' => esc_html__( 'Alignment', 'wpsection' ),
                        'type' => \Elementor\Controls_Manager::CHOOSE,
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
                        'default' => 'center',
                        'toggle' => true,
                        'selectors' => array(
                            '{{WRAPPER}} .wps_qnt_button' => 'justify-content: {{VALUE}} !important',
                        ),
                    )
                );  

        
        
$this->add_control(
            'wps_qnt_button_color',
            array(
                'label'     => __( 'Button Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
    
                'selectors' => array(
                    '{{WRAPPER}} .wps_product_details .quantity input[type="number"]' => 'color: {{VALUE}} !important',

                ),
            )
        );

$this->add_control(
            'wps_qnt_button_bg_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .wps_product_details .quantity' => 'background: {{VALUE}} !important',
                ),
            )
        );  

$this->add_control( 'wps_qnt_button_width',
                    [
                        'label' => esc_html__( 'Width',  'wpsection' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 2000,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        
                        'selectors' => [
                            '{{WRAPPER}} .wps_product_details .quantity' => 'width: {{SIZE}}{{UNIT}};',
                        ]
                    
                    ]
                );

    

    $this->add_control( 'wps_qnt_button_height',
                    [
                        'label' => esc_html__( ' Height', 'wpsection' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 1000,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        
                        'selectors' => [
                            '{{WRAPPER}} .wps_product_details .quantity' => 'height: {{SIZE}}{{UNIT}};',
                    
                        ]
                    ]
                );      
            
    
        
    $this->add_control(
            'wps_qnt_button_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wps_product_details .quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

    $this->add_control(
            'wps_qnt_button_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_product_details .quantity' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_qnt_button_typography',
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}}  .wps_product_details .quantity .qty',
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'wps_qnt_button_border',
                'selector' => '{{WRAPPER}} .wps_product_details .quantity',
            )
        );
    

        $this->add_control(
            'wps_qnt_button_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_product_details .quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


            $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_qnt_button_shadow',
                
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_product_details .quantity',
            ]
        );

// Plus and Minus Simbol Settings


$this->add_control(
    'wps_qnt_simbol_separator',
    array(
        'type' => \Elementor\Controls_Manager::DIVIDER,
    )
);


$this->add_control(
    'wps_qnt_simbol_description',
    array(
        'label' => __( 'Plus Minus Settings', 'wpsection' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => 'Plus Minus Button Settings',
    )
);


     
        
$this->add_control(
            'wps_qnt_simbol_button_color',
            array(
                'label'     => __( 'Button Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
    
                'selectors' => array(
                    '{{WRAPPER}} .wps_product_details .quantity .minus,.wps_product_details .quantity .plus' => 'color: {{VALUE}} !important',

                ),
            )
        );

$this->add_control(
            'wps_qnt_simbol_button_bg_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .wps_product_details .quantity .minus,.wps_product_details .quantity .plus' => 'background: {{VALUE}} !important',
                ),
            )
        );  

$this->add_control( 'wps_qnt_simbol_button_width',
                    [
                        'label' => esc_html__( 'Width',  'wpsection' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 2000,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        
                        'selectors' => [
                            '{{WRAPPER}} .wps_product_details .quantity .minus,.wps_product_details .quantity .plus' => 'width: {{SIZE}}{{UNIT}};',
                        ]
                    
                    ]
                );

    

    $this->add_control( 'wps_qnt_simbol_button_height',
                    [
                        'label' => esc_html__( ' Height', 'wpsection' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 1000,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        
                        'selectors' => [
                            '{{WRAPPER}} .wps_product_details .quantity .minus,.wps_product_details .quantity .plus' => 'height: {{SIZE}}{{UNIT}};',
                    
                        ]
                    ]
                );      
            
    
        
    $this->add_control(
            'wps_qnt_simbol_button_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wps_product_details .quantity .minus,.wps_product_details .quantity .plus' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

    $this->add_control(
            'wps_qnt_simbol_button_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_product_details .quantity .minus,.wps_product_details .quantity .plus' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_qnt_simbol_button_typography',
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_product_details .quantity input[type="button"] ',
            )
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'wps_qnt_simbol_button_border',
                'selector' => '{{WRAPPER}} .wps_product_details .quantity .minus,.wps_product_details .quantity .plus',
            )
        );
    

        $this->add_control(
            'wps_qnt_simbol_button_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_product_details .quantity .minus,.wps_product_details .quantity .plus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


            $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_qnt_simbol_button_shadow',
                
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_product_details .quantity .minus,.wps_product_details .quantity .plus',
            ]
        );




        
        $this->end_controls_section();   