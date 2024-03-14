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





        
// bosch  ppp  === progress =============
$this->start_controls_section(
            'progress_control',
            array(
                'label' => __( 'Progress Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'    => array( 'show_product_progress' => '1' ),
                
            )
        );
        
$this->add_control(
                    'hide_sold_text',
                    array(
                        'label' => esc_html__( 'Show Sold text-align', 'wpsection' ),
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
                            '{{WRAPPER}} .mr_product_progress .product-single-item-sold' => 'display: {{VALUE}} !important',

                        ),
                    )
                );      
    
    $this->add_control(
            'sold_text',
            array(
                'label'       => __( 'Sold Text', 'wpsection' ),
                'condition'    => array( 'hide_sold_text' => 'show' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default' => __( 'sold', 'wpsection' ),
            )
        );
     $this->add_control(
                    'wps_sold_alingment',
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
                            '{{WRAPPER}}  .mr_product_progress .product-single-item-sold' => 'text-align: {{VALUE}} !important',
                        ),
                    )
                ); 
        $this->add_control(
                    'sold_color',
                    array(
                        'label'     => __( 'Color', 'wpsection' ),
                        'condition'    => array( 'hide_sold_text' => 'show' ),
                        'type'      => \Elementor\Controls_Manager::COLOR,
                        'selectors' => array(
                            '{{WRAPPER}} .mr_product_progress .product-single-item-sold p' => 'color: {{VALUE}} !important',

                        ),
                    )
                );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'progress_sold',
                'label'    => __( 'Typography', 'wpsection' ),
                'condition'    => array( 'hide_sold_text' => 'show' ),
                'selector' => '{{WRAPPER}} .mr_product_progress .product-single-item-sold p',
                 
            )
        );  



        $this->add_control(
                    'show_progress',
                    array(
                        'label' => esc_html__( 'Show Progress', 'wpsection' ),
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
                            '{{WRAPPER}} .mr_product_progress .product-single-item-bar' => 'display: {{VALUE}} !important',

                        ),
                        'separator' => 'before',
                    )
                );      
    


    
        $this->add_control(
                    'border_green',
                    array(
                        'label'     => __( 'Background One', 'wpsection' ),
                        'condition'    => array( 'show_progress' => 'show' ),
                        'type'      => \Elementor\Controls_Manager::COLOR,
                        'selectors' => array(
                            '{{WRAPPER}} .mr_product_progress span.border-green' => 'background: {{VALUE}} !important',

                        ),
                    )
                );
        
        $this->add_control(
            'level_one',
            array(
                'label'       => __( 'Level One', 'wpsection' ),
                'condition'    => array( 'show_progress' => 'show' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'dynamic'     => [
                    'active' => true,
                ],
                 'default' => __( '50', 'wpsection' ),
            )
        );
        
        
        $this->add_control(
                    'border_yellow',
                    array(
                        'label'     => __( 'Background Color Three', 'wpsection' ),
                        'condition'    => array( 'show_progress' => 'show' ),
                        'type'      => \Elementor\Controls_Manager::COLOR,
                        'selectors' => array(
                            '{{WRAPPER}} .mr_product_progress span.border-yellow' => 'background: {{VALUE}} !important',

                        ),
                    )
                );  
    $this->add_control(
            'level_two',
            array(
                'label'       => __( 'Level Two', 'wpsection' ),
                'condition'    => array( 'show_progress' => 'show' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'dynamic'     => [
                    'active' => true,
                ],
                'default' => __( '75', 'wpsection' ),
            )
        );
        $this->add_control(
                    'border_red',
                    array(
                        'label'     => __( 'Background Color Two', 'wpsection' ),
                        'condition'    => array( 'show_progress' => 'show' ),
                        'type'      => \Elementor\Controls_Manager::COLOR,
                        'selectors' => array(
                            '{{WRAPPER}} .mr_product_progress span.border-red' => 'background: {{VALUE}} !important',

                        ),
                    )
                );


            
    $this->add_control(
            'progress_button_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array( 'show_progress' => 'show' ),
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_progress .product-single-item-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'progress_border',
                'condition'    => array( 'show_progress' => 'show' ),
                'selector' => '{{WRAPPER}} .mr_product_progress .product-single-item-bar',
            )
        );
    
        
            $this->add_control(
            'progress_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'condition'    => array( 'show_progress' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_progress .product-single-item-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        
        
        $this->end_controls_section();
        
//End progress bar
