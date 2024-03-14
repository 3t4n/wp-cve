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






  ////============= Product Item  Title=======================
// bosch  777
    $this->start_controls_section(
            'product_features_x_settings',
            array(
                'label' => __( 'Features Text Setting', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'    => array( 'show_product_features' => '1' ),
            )
        );
        
        
    $this->add_control(
            'show_f_title',
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
                    '{{WRAPPER}} .wps_meta_text' => 'display: {{VALUE}} !important',
                ),
            )
        );  
    $this->add_control(
            'title_f_alingment',
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
                'condition'    => array( 'show_f_title' => 'show' ),
                'toggle' => true,
                'selectors' => array(
                
                    '{{WRAPPER}} .wps_meta_text ' => 'text-align: {{VALUE}} !important',
                ),
            )
        );          


$this->add_control(
    'title_f_padding',
    array(
        'label'      => __( 'Padding', 'wpsection' ),
        'condition'  => array( 'show_f_title' => 'show' ),
        'type'       => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em'],
        'selectors'  => array(
            '{{WRAPPER}} .wps_meta_text li'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
            '{{WRAPPER}} .wps_meta_text'       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
        ),
    )
);


$this->add_control(
    'title_f_margin',
    array(
        'label'      => __( 'Margin', 'wpsection' ),
        'condition'  => array( 'show_f_title' => 'show' ),
        'type'       => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em'],
        'selectors'  => array(
            '{{WRAPPER}} .wps_meta_text li'    => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
            '{{WRAPPER}} .wps_meta_text'       => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important',
        ),
    )
);


$this->add_group_control(
    \Elementor\Group_Control_Typography::get_type(),
    array(
        'name'      => 'title_f_typography',
        'condition' => array( 'show_f_title' => 'show' ),
        'label'     => __( 'Typography', 'wpsection' ),
        'selector'  => '{{WRAPPER}} .wps_meta_text, {{WRAPPER}} .wps_meta_text li',
    )
);


$this->add_control(
    'title_f_color',
    array(
        'label'      => __( 'Color', 'wpsection' ),
        'condition'  => array( 'show_f_title' => 'show' ),
        'type'       => \Elementor\Controls_Manager::COLOR,
        'selectors'  => array(
            '{{WRAPPER}} .wps_meta_text'    => 'color: {{VALUE}} !important',
            '{{WRAPPER}} .wps_meta_text li' => 'color: {{VALUE}} !important',
        ),
    )
);


 $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'block_fe_li_border',
                'condition'  => array( 'show_f_title' => 'show' ),
                'label' => esc_html__( 'Box Border Expand Bottom', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_meta_text li',
            ]
        );



        $this->end_controls_section();
          
//end of title 