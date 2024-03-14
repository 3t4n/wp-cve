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





//=============== Product Rating ==============================

// bosch  999
        $this->start_controls_section(
            'product_rating_setting',
            array(
                'label' => __( 'Product Rating Setting', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'    => array( 'show_product_x_rating' => '1' ),
            )
        );


        $this->add_control(
            'show_rating',
            array(
                'label' => __( 'Show Rating', 'wpsection' ),
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
                    '{{WRAPPER}} .mr_star_rating' => 'display: {{VALUE}} !important',
                ),
            )
        );      

    $this->add_control(
    'product_rating_alingment',
            array(
                'label' => esc_html__( 'Alignment', 'wpsection' ),
                'condition'    => array( 'show_rating' => 'show' ),
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
                    '{{WRAPPER}} .mr_rating' => 'text-align: {{VALUE}} !important',
                ),
            )
        );  

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'product_rating_typography',
                'label'    => __( 'Product Rating Typography', 'wpsection' ),
                'condition'    => array( 'show_rating' => 'show' ),
                'selector' => '{{WRAPPER}} .mr_star_rating li i',
            )
        );

        $this->add_control(
            'product_rating_color',
            array(
                'label'     => __( 'Rating Color', 'wpsection' ),
                'condition'    => array( 'show_rating' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_star_rating li i' => 'color: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'product_rating_margin',
            array(
                'label'     => __( 'Product Rating Padding', 'wpsection' ),
                'separator' => 'after',
                'condition'    => array( 'show_rating' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .mr_star_rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        

    $this->add_control(
            'show_avarage_rating',
            array(
                'label' => __( 'Show Avarage Text', 'wpsection' ),
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
                    '{{WRAPPER}} .mr_review_number' => 'display: {{VALUE}} !important',
                ),
            )
        ); 

  $this->add_control(
    'product_avarage_rating_location',
            array(
                'label' => esc_html__( 'Location', 'wpsection' ),
                'condition'    => array( 'show_avarage_rating' => 'show' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'wps_inline_block' => [
                        'title' => esc_html__( 'Right of Rating', 'wpsection' ),
                        'icon' => ' eicon-h-align-right',
                    ],
                    'wps_inline' => [
                        'title' => esc_html__( 'Bottom of Rating', 'wpsection' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'wps_inline_block',
                'toggle' => true,
            )
        );  


 
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'product_avarage_rating_typography',
                'label'    => __( 'Product Avarage Typography', 'wpsection' ),
                    'condition'    => array( 'show_avarage_rating' => 'show' ),
                'selector' => '{{WRAPPER}} .mr_review_number ',
            )
        );

        $this->add_control(
            'product_avarage_rating_color',
            array(
                'label'     => __( 'Color', 'wpsection' ),
                    'condition'    => array( 'show_avarage_rating' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_review_number' => 'color: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'product_avarage_rating_margin',
            array(
                'label'     => __( 'Area Margin', 'wpsection' ),
                'condition'    => array( 'show_avarage_rating' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .mr_rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );  

        $this->end_controls_section();




//end of rating
 