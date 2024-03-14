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



//========== Thumbnail Dot ===================================


// Dot Button Setting
	
$this->start_controls_section(
			'slider_prodcut_m_dot_control',
			array(
				'label' => __( 'Thumbnail Slider Dot', 'wpsection' ),
		
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
$this->add_control(
			'slider_prodcut_m_show_dot',
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
					'{{WRAPPER}} .wps_thumbnail_area .swiper-pagination .swiper-pagination-bullet' => 'display: {{VALUE}} !important',
				),
			)
		);		


				$this->add_control( 'slider_prodcut_m_dot_width',
					[
						'label' => esc_html__( 'Dot Width',  'wpsection' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 500,
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
							'{{WRAPPER}} .wps_thumbnail_area .swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			
				$this->add_control( 'slider_prodcut_m_dot_height',
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
							'{{WRAPPER}} .wps_thumbnail_area .swiper-pagination .swiper-pagination-bullet ' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
		
$this->add_control(
			'slider_prodcut_m_dot_color',
			array(
				'label'     => __( 'Dot Color', 'wpsection' ),
			  'condition'    => array( 'wps_product_color_dot' => 'product_dot' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => array(
					'{{WRAPPER}}  .wps_thumbnail_area .swiper-pagination .swiper-pagination-bullet' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_prodcut_m_color_hover',
			array(
				'label'     => __( 'Dot Hover Color', 'wpsection' ),
			 'condition'    => array( 'wps_product_color_dot' => 'product_dot' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wps_thumbnail_area .swiper-pagination .swiper-pagination-bullet:hover' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_prodcut_m_bg_color',
			array(
				'label'     => __( 'Active Color', 'wpsection' ),
			 'condition'    => array( 'wps_product_color_dot' => 'product_dot' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .wps_thumbnail_area .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active  ' => 'background: {{VALUE}} !important',
				),
			)
		);	
			
	$this->add_control(
			'slider_prodcut_m_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
			
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .wps_thumbnail_area .swiper-pagination .swiper-pagination-bullet ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_prodcut_m_dot_margin',
			array(
				'label'     => __( 'Margin', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
		
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}} .wps_thumbnail_area .swiper-pagination .swiper-pagination-bullet ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_prodcut_m_dot_border',
			
				'selector' => '{{WRAPPER}}  .wps_thumbnail_area .swiper-pagination .swiper-pagination-bullet ',
			)
		);
	

		$this->add_control(
			'slider_prodcut_m_dot_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
		
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .wps_thumbnail_area .swiper-pagination .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


				
		
				$this->add_control( 'slider_prodcut_m_dot_horizontal',
					[
						'label' => esc_html__( 'Horizontal Position ',  'wpsection' ),
					
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
							'{{WRAPPER}} .wps_thumbnail_area .swiper-pagination ' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			

				$this->add_control( 'slider_prodcut_m_dot_vertical',
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
							'{{WRAPPER}} .wps_thumbnail_area .swiper-pagination' => 'top: {{SIZE}}{{UNIT}};',
						]
					]
				);

		$this->end_controls_section();

// =============================================== Dot Button Hover Style Setting ==================================
	
$this->start_controls_section(
			'slider_prodcut_hover_control',
			array(
				'label' => __( 'Thumbnail Hover Dot', 'wpsection' ),
		
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
$this->add_control(
			'slider_prodcut_hover_show_dot',
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
					'{{WRAPPER}} .wps_thumbnail_area .product_block_one .hover-slider-indicator-dot ' => 'display: {{VALUE}} !important',
				),
			)
		);		


				$this->add_control( 'slider_prodcut_hover_dot_width',
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
							'{{WRAPPER}} .wps_thumbnail_area .product_block_one .hover-slider-indicator-dot' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			
				$this->add_control( 'slider_prodcut_hover_dot_height',
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
							'{{WRAPPER}} .wps_thumbnail_area .product_block_one .hover-slider-indicator-dot ' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
		
$this->add_control(
			'slider_prodcut_hover_dot_color',
			array(
				'label'     => __( 'Dot Color', 'wpsection' ),
			 'condition'    => array( 'wps_product_color_dot' => 'product_dot' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => array(
					'{{WRAPPER}}  .wps_thumbnail_area .product_block_one .hover-slider-indicator-dot' => 'background: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'slider_prodcut_hover_color_hover',
			array(
				'label'     => __( 'Dot Hover Color', 'wpsection' ),
			 'condition'    => array( 'wps_product_color_dot' => 'product_dot' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wps_thumbnail_area .product_block_one .hover-slider-indicator-dot:hover' => 'background: {{VALUE}} !important',

				),
			)
		);
	
	$this->add_control(
			'slider_prodcut_hover_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
			
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .wps_thumbnail_area .product_block_one .hover-slider-indicator-dot ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'slider_prodcut_hover_dot_margin',
			array(
				'label'     => __( 'Margin', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
		
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}} .wps_thumbnail_area .product_block_one .hover-slider-indicator-dot ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'slider_prodcut_hover_dot_border',
			
				'selector' => '{{WRAPPER}}  .wps_thumbnail_area .product_block_one .hover-slider-indicator-dot ',
			)
		);
	

		$this->add_control(
			'slider_prodcut_hover_dot_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
		
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .wps_thumbnail_area .product_block_one .hover-slider-indicator-dot ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


				
		
				$this->add_control( 'slider_prodcut_hover_dot_horizontal',
					[
						'label' => esc_html__( 'Horizontal Position',  'wpsection' ),
					
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
							'{{WRAPPER}} .wps_thumbnail_area .product_block_one .hover-slider-indicator' => 'left: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
			

				$this->add_control( 'slider_prodcut_hover_dot_vertical',
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
							'{{WRAPPER}} .wps_thumbnail_area .product_block_one .hover-slider-indicator' => 'top: {{SIZE}}{{UNIT}};',

					
						]
					]
				);


		$this->end_controls_section();	     