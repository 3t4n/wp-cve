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



//price control============
// bosch  666
    $this->start_controls_section(
            'price_settings',
            array(
                'label' => __( 'Price Setting', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'    => array( 'show_product_price' => '1' ),
            )
        );
        
    $this->add_control(
            'show_price',
            array(
                'label' => esc_html__( 'Show Price', 'wpsection' ),
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
                    '{{WRAPPER}} .mr_shop_price' => 'display: {{VALUE}} !important',
                ),
            )
        );  
    $this->add_control(
            'price_alingment',
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
                'condition'    => array( 'show_price' => 'show' ),
                'toggle' => true,
                'selectors' => array(
                    '{{WRAPPER}} .mr_shop_price' => 'text-align: {{VALUE}} !important',
                ),
            )
        );  
    $this->add_control(
            'price_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'condition'    => array( 'show_price' => 'show' ),
                'selectors' => array(
                    '{{WRAPPER}} .mr_shop_price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
     $this->add_control(
            'margin_padding',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'condition'    => array( 'show_price' => 'show' ),
                'selectors' => array(
                    '{{WRAPPER}} .mr_shop_price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
       


          $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'price_typography',
                'condition'    => array( 'show_price' => 'show' ),
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_shop_price ins .amount bdi',
            )
        );

     $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'currency_typography',
                'condition'    => array( 'show_price' => 'show' ),
                'label'    => __( 'Currency Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_shop_price ins .amount bdi .woocommerce-Price-currencySymbol',
            )
        );


        
/*
$this->add_control(
    'price_color',
    array(
        'label'      => __( 'Color', 'wpsection' ),
        'condition'  => array( 'show_price' => 'show' ),
        'separator'  => 'after',
        'type'       => \Elementor\Controls_Manager::COLOR,
        'selectors'  => array(
            '{{WRAPPER}} .mr_shop_price ins .amount bdi,
			{{WRAPPER}} .mr_shop_price ins .amount bdi .woocommerce-Price-currencySymbol,
			{{WRAPPER}} .mr_shop .woocommerce-Price-amount.amount bdi' => 'color: {{VALUE}} !important',
        ),
    )
);
*/
$this->add_control(
    'price_color',
    array(
        'label'      => __( 'Color', 'wpsection' ),
        'condition'  => array( 'show_price' => 'show' ),

        'type'       => \Elementor\Controls_Manager::COLOR,
        'selectors'  => array(
            '{{WRAPPER}} .mr_shop_price ins .amount bdi' => 'color: {{VALUE}} !important',
        ),
    )
);

$this->add_control(
    'price_currency_symbol_color',
    array(
        'label'      => __( 'Currency Symbol Color', 'wpsection' ),
        'condition'  => array( 'show_price' => 'show' ),
        'type'       => \Elementor\Controls_Manager::COLOR,
        'selectors'  => array(
            '{{WRAPPER}} .mr_shop_price ins .amount bdi .woocommerce-Price-currencySymbol' => 'color: {{VALUE}} !important',
        ),
    )
);



$this->add_control(
    'price_old_color',
    array(
        'label'      => __( 'Price Old Color', 'wpsection' ),
        'condition'  => array( 'show_price' => 'show' ),
		
        'type'       => \Elementor\Controls_Manager::COLOR,
        'selectors'  => array(
            '{{WRAPPER}} .mr_shop_price del .amount bdi' => 'color: {{VALUE}} !important',
        ),
    )
);
$this->add_control(
    'price_old_symbol_color',
    array(
        'label'      => __( 'Price Old Symbol Color', 'wpsection' ),
        'condition'  => array( 'show_price' => 'show' ),
        'type'       => \Elementor\Controls_Manager::COLOR,
        'selectors'  => array(
            '{{WRAPPER}} .mr_shop_price del .amount bdi .woocommerce-Price-currencySymbol' => 'color: {{VALUE}} !important',
        ),
    )
);


      $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'price_old_typography',
                'condition'    => array( 'show_price' => 'show' ),
                'label'    => __( 'Old Price Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_shop_price del .amount bdi',
            )
        );

   $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'currency_old_typography',
                'condition'    => array( 'show_price' => 'show' ),
                'label'    => __( 'Old Currency Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}} .mr_shop_price del .amount bdi .woocommerce-Price-currencySymbol',
            )
        );
        
           $this->add_control(
            'price_underline_color',
            array(
                'label'     => __( 'Price Old Under-Line Color', 'wpsection' ),
                'condition'    => array( 'show_price' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_shop_price del .amount bdi' => 'text-decoration-color: {{VALUE}} !important',
                ),
            )
        );

           $this->add_control(
            'price_underline_size',
            [
                'label' => esc_html__( 'Under Line Size', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_shop_price del .amount bdi' => 'text-decoration-thickness: {{SIZE}}{{UNIT}};',
                ],
            ]
        );



        $this->end_controls_section();
//End of Text=========      
