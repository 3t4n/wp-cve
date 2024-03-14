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
			'slider_path_button_3_control',
			array(
				'label' => __( 'Slider Arrow  Settings', 'wpsection' ),
		
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'    => array( 'style' => 'style-1' ),
			)
		);
		
$this->add_control(
			'slider_path_show_button_3',
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
					'{{WRAPPER}} .slider_path .owl-nav ' => 'display: {{VALUE}} !important',
				),
			)
		);



$this->add_control(
    'show_wps_awl_prev_next',
    array(
        'label' => esc_html__( 'Hide under 1000px', 'wpsection' ),
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
            '{{WRAPPER}} .slider_path .owl-nav' => '{{VALUE}}',
        ),
    )
);

// Custom CSS
echo '
<style>
    @media screen and (max-width: 1000px) {
        {{WRAPPER}} .slider_path .owl-nav {
            display: none !important;
        }
    }
</style>';




$this->add_control(
			'slider_path_button_3_color',
			array(
				'label'     => __( 'Button Color', 'wpsection' ),
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#cbcbcb',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav .owl-prev' => 'color: {{VALUE}} !important',
					'{{WRAPPER}}  .slider_path .owl-nav .owl-next' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_button_3_color_hover',
			array(
				'label'     => __( 'Button Hover Color', 'wpsection' ),
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff ',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav .owl-prev:hover' => 'color: {{VALUE}} !important',
					'{{WRAPPER}}  .slider_path .owl-nav .owl-next:hover' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_button_3_bg_color',
			array(
				'label'     => __( 'Background Color', 'wpsection' ),
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#f3f3f3 ',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'slider_path_button_3_hover_color',
			array(
				'label'     => __( 'Background Hover Color', 'wpsection' ),
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button:hover' => 'background: {{VALUE}} !important',
				),
			)
		);	
		
		
	
		$this->add_control( 'slider_path_dot_3_width',
					[
						'label' => esc_html__( 'Arraw Width',  'wpsection' ),
						//'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
						'default' => [
							'unit' => 'px',
							'size' => 30,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav .owl-prev' => 'width: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .slider_path .owl-nav .owl-next' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

	$this->add_control( 'slider_path_dot_3_height',
					[
						'label' => esc_html__( 'Arraw Height', 'wpsection' ),
						//'condition'    => array( 'slider_path_show_dot' => 'show' ),
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
						'default' => [
							'unit' => 'px',
							'size' =>30,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav .owl-prev' => 'height: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .slider_path .owl-nav .owl-next ' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
			
	
		
	$this->add_control(
			'slider_path_button_3_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_path_button_3_margin',
			array(
				'label'     => __( 'Margin', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_path_button_3_typography',
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'label'    => __( 'Typography', 'wpsection' ),
				'selector' => '{{WRAPPER}}  .slider_path .owl-nav button',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_border_3',
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'selector' => '{{WRAPPER}}  .slider_path .owl-nav .owl-prev, .slider_path .owl-nav .owl-next ',
			)
		);
	

		$this->add_control(
			'slider_path_border_3_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


				
		
				$this->add_control( 'slider_path_horizontal_prev',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
						//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -200,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav .owl-prev' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
				$this->add_control( 'slider_path_horizontal_next',
					[
						'label' => esc_html__( 'Horizontal Position Next', 'wpsection'),
						//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
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
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav .owl-next' => 'left: {{SIZE}}{{UNIT}};',
						],
						
					]
				);
		
				$this->add_control( 'slider_path_vertical',
					[
						'label' => esc_html__( 'Vertical Position', 'wpsection' ),
						//'condition'    => array( 'slider_path_show_button_3' => 'show' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -200,
								'max' => 1000,
								'step' => 1,
							],
							'%' => [
								'min' => -100,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 200,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-nav ' => 'top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .slider_path .owl-nav ' => 'top: {{SIZE}}{{UNIT}};',
						]
					]
				);


		$this->end_controls_section();
		
	

// Dot Button Setting
	
$this->start_controls_section(
			'slider_path_dot_control',
			array(
				'label' => __( 'Slider Dot  Settings', 'wpsection' ),
		
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'    => array( 'style' => 'style-1' ),
			)
		);
		
$this->add_control(
			'slider_path_show_dot',
			array(
				'label' => esc_html__( 'Show Dot', 'wpsection' ),
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
					'{{WRAPPER}} .slider_path .owl-dots ' => 'display: {{VALUE}} !important',
				),
			)
		);		

		
$this->add_control(
    'show_wps_awl_dot',
    array(
        'label' => esc_html__( 'Hide under 1000px', 'wpsection' ),
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
            '{{WRAPPER}} .slider_path .owl-dots' => '{{VALUE}}',
        ),
    )
);

// Custom CSS
echo '
<style>
    @media screen and (max-width: 1000px) {
        {{WRAPPER}} .slider_path .owl-dots {
            display: none !important;
        }
    }
</style>';


$this->add_control(
			'slider_path_hide_mobile',
			array(
				'label' => esc_html__( 'Show Dot', 'wpsection' ),
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
				
			)
		);	






				$this->add_control( 'slider_path_dot_width',
					[
						'label' => esc_html__( 'Dot Width',  'wpsection' ),
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
						'default' => [
							'unit' => 'px',
							'size' => 10,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots span' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			
				$this->add_control( 'slider_path_dot_height',
					[
						'label' => esc_html__( 'Dot Height', 'wpsection' ),
					
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 10,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots span ' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
		
$this->add_control(
			'slider_path_dot_color',
			array(
				'label'     => __( 'Dot Color', 'wpsection' ),
			
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_dot_color_hover',
			array(
				'label'     => __( 'Dot Hover Color', 'wpsection' ),
			
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span:hover' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_dot_bg_color',
			array(
				'label'     => __( 'Active Color', 'wpsection' ),
			
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}}  .owl-theme .owl-dots .owl-dot.active span' => 'background: {{VALUE}} !important',
				),
			)
		);	
			
	$this->add_control(
			'slider_path_dot_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
			
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_path_dot_margin',
			array(
				'label'     => __( 'Margin', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
		
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_dot_border',
			
				'selector' => '{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span',
			)
		);
	

		$this->add_control(
			'slider_path_dot_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
		
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .slider_path .owl-theme .owl-dots .owl-dot span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


				
		
				$this->add_control( 'slider_path_dot_horizontal',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
					
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => -100,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			

				$this->add_control( 'slider_path_dot_vertical',
					[
						'label' => esc_html__( 'Vertical Position', 'wpsection' ),
					
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -200,
								'max' => 1000,
								'step' => 1,
							],
							'%' => [
								'min' => -100,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .slider_path .owl-theme .owl-dots  ' => 'top: {{SIZE}}{{UNIT}};',
					
						]
					]
				);


		$this->end_controls_section();	



// Dot for Slider 2 and 3

// Dot Button Setting
	


$this->start_controls_section(
    'slider_path_dot_sweep_control',
    array(
        'label'      => __( 'Slider Dot Settings', 'wpsection' ),
        'tab'        => \Elementor\Controls_Manager::TAB_STYLE,
        'condition'  => array( 'style' => array( 'style-2', 'style-3' ) ),
    )
);

		
$this->add_control(
			'slider_path_sweep_show_dot',
			array(
				'label' => esc_html__( 'Show Dot', 'wpsection' ),
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
					'{{WRAPPER}} .wps_slider_two_dot .swiper-pagination-bullet ' => 'display: {{VALUE}} !important',
				),
			)
		);	



$this->add_control(
			'slider_path_hide_sweep_mobile',
			array(
				'label' => esc_html__( 'Show Dot', 'wpsection' ),
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
				
			)
		);

$this->add_control(
    'show_wps_awl_nav',
    array(
        'label' => esc_html__( 'Hide under 1000px', 'wpsection' ),
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
            '{{WRAPPER}} .wps_slider_two_dot .swiper-pagination-bullet' => '{{VALUE}}',
        ),
    )
);

// Custom CSS
echo '
<style>
    @media screen and (max-width: 1000px) {
        {{WRAPPER}} .wps_slider_two_dot .swiper-pagination-bullet {
            display: none !important;
        }
    }
</style>';


				$this->add_control( 'slider_path_sweep_dot_width',
					[
						'label' => esc_html__( 'Dot Width',  'wpsection' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 10,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_two_dot .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			
				$this->add_control( 'slider_path_sweep_dot_height',
					[
						'label' => esc_html__( 'Dot Height', 'wpsection' ),
					
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 10,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slider_two_dot .swiper-pagination-bullet ' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
		
$this->add_control(
			'slider_path_sweep_dot_color',
			array(
				'label'     => __( 'Dot Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => array(
					'{{WRAPPER}}  .wps_slider_two_dot .swiper-pagination-bullet' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_sweep_dot_color_hover',
			array(
				'label'     => __( 'Dot Hover Color', 'wpsection' ),
			
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .wps_slider_two_dot .swiper-pagination-bullet:hover' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_path_sweep_dot_bg_color',
			array(
				'label'     => __( 'Active Color', 'wpsection' ),
			
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#9826FF',
				'selectors' => array(
					'{{WRAPPER}}  .wps_slider_two_dot .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background: {{VALUE}} !important',
				),
			)
		);	
			
	$this->add_control(
			'slider_path_sweep_dot_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
			
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .wps_slider_two_dot .swiper-pagination-bullet' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_path_sweep_dot_margin',
			array(
				'label'     => __( 'Margin', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
		
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}}  .wps_slider_two_dot .swiper-pagination-bullet' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_path_sweep_dot_border',
			
				'selector' => '{{WRAPPER}}  .wps_slider_two_dot .swiper-pagination-bullet',
			)
		);
	

		$this->add_control(
			'slider_path_sweep_dot_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
		
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .wps_slider_two_dot .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


				
		
				$this->add_control( 'slider_path_sweep_dot_horizontal',
					[
						'label' => esc_html__( 'Horizontal Position Previous',  'wpsection' ),
					
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => -100,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slide_sweeper_two  .swiper-pagination' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			

				$this->add_control( 'slider_path_sweep_dot_vertical',
					[
						'label' => esc_html__( 'Vertical Position', 'wpsection' ),
					
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => -200,
								'max' => 5000,
								'step' => 1,
							],
							'%' => [
								'min' => -100,
								'max' => 100,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' => [
							'{{WRAPPER}} .wps_slide_sweeper_two  .swiper-pagination' => 'top: {{SIZE}}{{UNIT}};',
					
						]
					]
				);


		$this->end_controls_section();	