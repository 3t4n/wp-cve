<?php
	/**
	 * Swiper Slider Renderer For Controllers
	 *
	 * @package AbsoluteAddons
	 * @author Name <email>
	 * @version
	 * @since
	 * @license
	 */

	namespace AbsoluteAddons;

	use Elementor\Controls_Manager;
	use Elementor\Controls_Stack;
	use Elementor\Group_Control_Background;
	use Elementor\Group_Control_Border;
	use Elementor\Group_Control_Box_Shadow;
	use Elementor\Icons_Manager;

	if ( ! defined( 'ABSPATH' ) ) {
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		die();
	}

	trait Absp_Slider_Controller {

		/**
		 * Render Slider Controls.
		 *
		 * @param array $options
		 */
		protected function render_slider_controller( $options = [] ) {

			$defaults = [
				'section'                    => [
					'condition'  => [],
					'conditions' => [],
				],

				'slider_dots_position'       => [
					'condition' => [
						'navigation' => [ 'dots', 'both' ],
					],
				],
				'slider_navigation_position' => [
					'condition' => [
						'navigation' => [ 'arrows', 'both' ],
					],
				],

				'slides_to_show'             => [
					'min'         => 1,
					'max'         => 8,
					'default'     => 3,
					'device_args' => [
						Controls_Stack::RESPONSIVE_TABLET => [
							'required' => FALSE,
							'default'  => 2,
						],
						Controls_Stack::RESPONSIVE_MOBILE => [
							'required' => FALSE,
							'default'  => 1,
						],
					],
					//				'devices' => [ 'desktop', 'tablet', 'mobile' ],
					//				'desktop_default' => [
					//					'default'     => 3,
					//				],
					//				'tablet_default' => [
					//					'default'  => 2,
					//				],
					//				'mobile_default' => [
					//					'default'  => 1,
					//				],
				],
				'slides_to_scroll'           => [
					'min'         => 1,
					'max'         => 8,
					'default'     => 1,
					'device_args' => [
						Controls_Stack::RESPONSIVE_TABLET => [
							'required' => FALSE,
							'default'  => 1,
						],
						Controls_Stack::RESPONSIVE_MOBILE => [
							'required' => FALSE,
							'default'  => 1,
						],
					],
				],
				'autoplay'                   => 'yes',
				'slides_center'              => 'false',
				'slides_loop'                => 'true',
				'autoplay_delay'             => [
					'min'     => '',
					'max'     => '',
					'default' => 5000,
				],
				'spacing_between'            => [
					'range'   => [
						'px' => [
							'max' => 100,
						],
					],
					'default' => [
						'size' => 5,
					],
				],
				'direction'                  => 'ltr',
				'effect'                     => 'slide',
				'navigation'                 => 'dots',
				'arrows_position'            => 'inside',
				'arrows_color'               => [
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'color: {{VALUE}} !important;',
					],
				],
				'arrows_color_hover'         => [
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next:hover' => 'color: {{VALUE}} !important;',
					],
				],
				'dots_position'              => 'outside',
				'dots_position_prefix_class' => '',
				'dots_color'                 => [
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};opacity:1',
					],
				],
				//			'dots_border_color'          => [
				//              'default'   => '',
				//              'selectors' => [
				//                  '{{WRAPPER}} .swiper-pagination-bullet' => 'border-color: {{VALUE}};',
				//              ],
				//          ],
				'dots_active_color'          => [
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background: {{VALUE}};',
					],
				],

				'dots_size'                  => [
					'range'     => [
						'px' => [
							'min' => 5,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					],
				],
				'right_arrow_horizontally'   => [
					'range'   => [
						'px' => [
							'min'  => -500,
							'max'  => 1000,
							'step' => 5,
						],
						'%'  => [
							'min' => -100,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => '%',
						'size' => 0,
					],
				],
				'left_arrow_horizontally'    => [
					'range'   => [
						'px' => [
							'min'  => -500,
							'max'  => 1000,
							'step' => 5,
						],
						'%'  => [
							'min' => -100,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => '%',
						'size' => 0,
					],
				],
				'dot_horizontally'           => [
					'range'   => [
						'px' => [
							'min'  => -500,
							'max'  => 1000,
							'step' => 5,
						],
						'%'  => [
							'min' => -100,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => '%',
						'size' => 0,
					],
				],
				'dot_vertically'             => [
					'range'   => [
						'px' => [
							'min'  => -500,
							'max'  => 1000,
							'step' => 5,
						],
						'%'  => [
							'min' => -100,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => '%',
						'size' => 100,
					],
				],

			];

			$options = absp_parse_args_recursive( $options, $defaults );

			$this->start_controls_section(
				'absp_section_slider',
				[
					'label'      => __( 'Slider Options', 'absolute-addons' ),
					'tab'        => Controls_Manager::TAB_CONTENT,
					'condition'  => $options['section']['condition'],
					'conditions' => $options['section']['conditions'],
				]
			);

			$this->add_responsive_control(
				'slides_to_show',
				[
					'label'       => __( 'Slides To Show', 'absolute-addons' ),
					'type'        => Controls_Manager::NUMBER,
					'min'         => $options['slides_to_show']['min'],
					'max'         => $options['slides_to_show']['max'],
					'default'     => $options['slides_to_show']['default'],
					'required'    => TRUE,
					'device_args' => $options['slides_to_show']['device_args'],
					//				'devices' => [ 'desktop', 'tablet', 'mobile' ],
					//				'desktop_default' => $options['slides_to_show']['desktop_default'],
					//				'tablet_default' => $options['slides_to_show']['tablet_default'],
					//				'mobile_default' => $options['slides_to_show']['mobile_default'],
				]
			);

			$this->add_responsive_control(
				'slides_to_scroll',
				[
					'label'       => __( 'Slides to Scroll', 'absolute-addons' ),
					'description' => __( 'Set how many slides are scrolled per swipe.', 'absolute-addons' ),
					'type'        => Controls_Manager::NUMBER,
					'min'         => $options['slides_to_scroll']['min'],
					'max'         => $options['slides_to_scroll']['max'],
					'default'     => $options['slides_to_scroll']['default'],
					'required'    => TRUE,
					'device_args' => $options['slides_to_scroll']['device_args'],
				]
			);

			$this->add_control(
				'autoplay',
				[
					'label'   => __( 'Autoplay', 'absolute-addons' ),
					'type'    => Controls_Manager::SELECT,
					'default' => $options['autoplay'],
					'options' => [
						'yes' => __( 'Yes', 'absolute-addons' ),
						'no'  => __( 'No', 'absolute-addons' ),
					],
				]
			);

			$this->add_control(
				'slides_center',
				[
					'label'   => __( 'Center Mode', 'absolute-addons' ),
					'type'    => Controls_Manager::SELECT,
					'default' => $options['slides_center'],
					'options' => [
						'true'  => __( 'Yes', 'absolute-addons' ),
						'false' => __( 'No', 'absolute-addons' ),
					],
				]
			);

			$this->add_control(
				'slides_loop',
				[
					'label'   => __( 'Loop', 'absolute-addons' ),
					'type'    => Controls_Manager::SELECT,
					'default' => $options['slides_loop'],
					'options' => [
						'true'  => __( 'Yes', 'absolute-addons' ),
						'false' => __( 'No', 'absolute-addons' ),
					],
				]
			);

			$this->add_control(
				'autoplay_delay',
				[
					'label'     => __( 'Autoplay Speed (ms)', 'absolute-addons' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => $options['autoplay_delay']['min'],
					'max'       => $options['autoplay_delay']['max'],
					'default'   => $options['autoplay_delay']['default'],
					'condition' => [
						'autoplay' => 'yes',
					],
				]
			);

			$this->add_control(
				'spacing_between',
				[
					'label'   => __( 'Distance between slides in px', 'absolute-addons' ),
					'type'    => Controls_Manager::SLIDER,
					'range'   => $options['spacing_between']['range'],
					'default' => $options['spacing_between']['default'],
				]
			);

			$this->add_control(
				'direction',
				[
					'label'   => __( 'Direction', 'absolute-addons' ),
					'type'    => Controls_Manager::SELECT,
					'default' => $options['direction'],
					'options' => [
						'ltr' => __( 'Left', 'absolute-addons' ),
						'rtl' => __( 'Right', 'absolute-addons' ),
					],
				]
			);

			$this->add_control(
				'effect',
				[
					'label'       => __( 'Effect', 'absolute-addons' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => $options['effect'],
					'options'     => [
						'slide' => __( 'Slide', 'absolute-addons' ),
						'fade'  => __( 'Fade', 'absolute-addons' ),
					],
					'description' => __( 'Fade effect works when "Slides to Show" is 1', 'absolute-addons' ),
				]
			);

			$this->add_control(
				'navigation',
				[
					'label'   => __( 'Navigation', 'absolute-addons' ),
					'type'    => Controls_Manager::SELECT,
					'default' => $options['navigation'],
					'options' => [
						'both'   => __( 'Arrows and Dots', 'absolute-addons' ),
						'arrows' => __( 'Arrows', 'absolute-addons' ),
						'dots'   => __( 'Dots', 'absolute-addons' ),
						'none'   => __( 'None', 'absolute-addons' ),
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'slider_navigation_position',
				array(
					'label'     => esc_html__( 'Navigation Style', 'absolute-addons' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => $options['slider_navigation_position']['condition'],
				)
			);

			$this->add_control(
				'navigation_prev_icon',
				[
					'label'            => __( 'Navigation Previous Icon', 'absolute-addons' ),
					'type'             => Controls_Manager::ICONS,
					'label_block'      => FALSE,
					'fa4compatibility' => 'absolute-addons',
					'default'          => [
						'value'   => 'fas fa-chevron-left',
						'library' => 'solid',
					],
					'recommended'      => [
						'fa-solid'   => [
							'chevron-left',
							'angle-left',
							'angle-left',
							'angle-double-left',
							'caret-left',
							'caret-square-left',
						],
						'fa-regular' => [
							'caret-square-up',
						],
					],
					'skin'             => 'inline',
					'condition'        => [
						'navigation' => [ 'arrows', 'both' ],
					],
				]
			);

			$this->add_control(
				'navigation_next_icon',
				[
					'label'            => __( 'Navigation Next Icon', 'absolute-addons' ),
					'type'             => Controls_Manager::ICONS,
					'label_block'      => FALSE,
					'fa4compatibility' => 'absolute-addons',
					'default'          => [
						'value'   => 'fas fa-chevron-right',
						'library' => 'solid',
					],
					'recommended'      => [
						'fa-solid'   => [
							'chevron-right',
							'angle-right',
							'angle-double-right',
							'caret-right',
							'caret-square-right',
						],
						'fa-regular' => [
							'caret-square-right',
						],
					],
					'skin'             => 'inline',
					'condition'        => [
						'navigation' => [ 'arrows', 'both' ],
					],
				]
			);

			$this->add_control(
				'arrows_position',
				[
					'label'        => __( 'Arrows Position', 'absolute-addons' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => $options['arrows_position'],
					'options'      => [
						'inside'  => __( 'Inside', 'absolute-addons' ),
						'outside' => __( 'Outside', 'absolute-addons' ),
					],
					'prefix_class' => 'elementor-arrows-position-',
					'condition'    => [
						'navigation' => [ 'arrows', 'both' ],
					],
				]
			);

			$this->add_control(
				'left_arrow_vertically',
				[
					'label'      => esc_html__( 'Left Arrow Position Vertically ', 'absolute-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 1000,
							'step' => 5,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default'    => [
						'unit' => '%',
						'size' => 50,
					],
					'selectors'  => [
						'{{WRAPPER}} .elementor-swiper-button-prev.absp-nav-prev' => 'top: {{SIZE}}{{UNIT}} !important;',
					],
				]
			);

			$this->add_control(
				'left_arrow_horizontally',
				[
					'label'      => esc_html__( 'Left Arrow Position Horizontally', 'absolute-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range'      => [
						'px' => [
							'min'  => -500,
							'max'  => 1000,
							'step' => 5,
						],
						'%'  => [
							'min' => -100,
							'max' => 100,
						],
					],
					'default'    => [
						'unit' => '%',
						'size' => 1,
					],
					'selectors'  => [
						'{{WRAPPER}} .elementor-swiper-button-prev.absp-nav-prev' => 'left: {{SIZE}}{{UNIT}} !important;',
					],
				]
			);

			$this->add_control(
				'right_arrow_vertically',
				[
					'label'      => esc_html__( 'Right Arrow Position Vertically ', 'absolute-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 1000,
							'step' => 5,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default'    => [
						'unit' => '%',
						'size' => 50,
					],
					'selectors'  => [
						'{{WRAPPER}} .elementor-swiper-button-next.absp-nav-next' => 'top: {{SIZE}}{{UNIT}} !important;',
					],
				]
			);

			$this->add_control(
				'right_arrow_horizontally',
				[
					'label'      => esc_html__( 'Right Arrow Position Horizontally', 'absolute-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range'      => [
						'px' => [
							'min'  => -500,
							'max'  => 1000,
							'step' => 5,
						],
						'%'  => [
							'min' => -100,
							'max' => 100,
						],
					],
					'default'    => [
						'unit' => '%',
						'size' => 1,
					],
					'selectors'  => [
						'{{WRAPPER}} .elementor-swiper-button-next.absp-nav-next' => 'right: {{SIZE}}{{UNIT}} !important;',
					],
				]
			);


			$this->start_controls_tabs( 'navigation_button_style' );

			$this->start_controls_tab(
				'navigation_button_normal',
				[
					'label' => esc_html__( 'Normal', 'absolute-addons' ),
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'slider_navigation_background',
					'label'    => esc_html__( 'Navigation Background', 'absolute-addons' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .absp-widget .elementor-swiper-button ',
				)
			);

			$this->add_control(
				'slider_navigation_button_color',
				[
					'label'     => esc_html__( 'Navigation Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-swiper-button i' => 'color: {{VALUE}} !important;',
					],
				]
			);

			$this->add_responsive_control(
				'slider_navigation_button_width',
				[
					'label'      => esc_html__( 'Navigation Button Width', 'absolute-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'rem', 'em', '%' ],
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 1500,
							'step' => 1,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .elementor-swiper-button ' => 'width: {{SIZE}}{{UNIT}} !important;',

					],
				]
			);

			$this->add_responsive_control(
				'slider_navigation_button_height',
				[
					'label'      => esc_html__( 'Navigation Button Height', 'absolute-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'rem', 'em', '%' ],
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 1500,
							'step' => 1,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .elementor-swiper-button ' => 'height: {{SIZE}}{{UNIT}} !important;',

					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'slider_navigation_box_shadow',
					'label'    => esc_html__( 'Navigation Box shadow', 'absolute-addons' ),
					'selector' => '{{WRAPPER}} .elementor-swiper-button ',
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'      => 'slider_navigation_border',
					'selector'  => '{{WRAPPER}} .elementor-swiper-button ',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'slider_navigation_button_border_color',
				[
					'label'     => esc_html__( 'Navigation Border Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-swiper-button ' => 'border-color: {{VALUE}} !important;',
					],
					'condition' => [
						'slider_navigation_border!' => '',
					],
				]
			);

			$this->add_control(
				'slider_navigation_button_border_radius',
				[
					'label'      => esc_html__( 'Navigation button Border Radius', 'absolute-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .elementor-swiper-button ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
					'separator'  => 'after',
				]
			);

			$this->add_responsive_control(
				'slider_navigation_button_padding',
				[
					'label'      => esc_html__( 'Navigation Button Padding', 'absolute-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'rem', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .elementor-swiper-button ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);

			$this->add_responsive_control(
				'navigation_button_margin',
				[
					'label'      => esc_html__( 'Navigation Button Margin', 'absolute-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'rem', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .elementor-swiper-button ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'nav_button_hover',
				[
					'label' => __( 'Hover', 'absolute-addons' ),
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'nav_hover_background',
					'label'    => esc_html__( 'Navigation Background', 'absolute-addons' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .elementor-swiper-button:hover',
				)
			);

			$this->add_control(
				'nav_button_hover_color',
				[
					'label'     => esc_html__( 'Navigation Hover Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-swiper-button:hover i' => 'color: {{VALUE}} !important;',
					],
				]
			);

			$this->add_control(
				'nav_button_border_hover_color',
				[
					'label'     => esc_html__( 'Navigation Hover Border Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-swiper-button:hover' => 'border-color: {{VALUE}} !important;',
					],
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();

			$this->start_controls_section(
				'slider_dots_position',
				array(
					'label'     => esc_html__( 'Dots Style', 'absolute-addons' ),
					'condition' => $options['slider_dots_position']['condition'],
					'tab'       => Controls_Manager::TAB_STYLE,
				)
			);

			$this->start_controls_tabs( 'dot_button_style' );

			$this->start_controls_tab(
				'dot_button_normal',
				[
					'label' => esc_html__( 'Normal', 'absolute-addons' ),
				]
			);

			$this->add_control(
				'dots_color',
				[
					'label'     => __( 'Dots Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => $options['dots_color']['default'],
					'selectors' => $options['dots_color']['selectors'],
					'condition' => [
						'navigation' => [ 'dots', 'both' ],
					],
				]
			);


			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'      => 'dots_border_color',
					'label'     => esc_html__( 'Active Dot Border', 'absolute-addons' ),
					'selector'  => '{{WRAPPER}} .absp-widget .swiper-pagination-bullet',
					'condition' => [
						'navigation' => [ 'dots', 'both' ],
					],
				]
			);

			$this->add_control(
				'dots_size',
				[
					'label'     => __( 'Dots Size', 'absolute-addons' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => $options['dots_size']['range'],
					'selectors' => $options['dots_size']['selectors'],
					'condition' => [
						'navigation' => [ 'dots', 'both' ],
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'dot_button_active',
				[
					'label' => esc_html__( 'Active', 'absolute-addons' ),
				]
			);


			$this->add_control(
				'dots_active_color',
				[
					'label'     => __( 'Dots Active Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => $options['dots_active_color']['default'],
					'selectors' => $options['dots_active_color']['selectors'],
					'condition' => [
						'navigation' => [ 'dots', 'both' ],
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'      => 'dots_active_border_color',
					'label'     => esc_html__( 'Active Dot Border', 'absolute-addons' ),
					'selector'  => '{{WRAPPER}} .absp-widget .swiper-pagination-bullet.swiper-pagination-bullet-active',
					'condition' => [
						'navigation' => [ 'dots', 'both' ],
					],
				]
			);



			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'dot_gaps',
				[
					'label'      => esc_html__( 'Dots Gap', 'absolute-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'separator'  => 'before',
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 50,
							'step' => 1,
						],
					],
					'default'    => [
						'unit' => 'px',
						'size' => 5,
					],
					'selectors'  => [
						'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}px !important;',
					],
				]
			);

			$this->add_control(
				'dot_horizontally',
				[
					'label'      => esc_html__( 'Dot Position Horizontally', 'absolute-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range'      => [
						'px' => [
							'min'  => -500,
							'max'  => 1000,
							'step' => 5,
						],
						'%'  => [
							'min' => -100,
							'max' => 100,
						],
					],
					'default'    => [
						'unit' => '%',
						'size' => 0,
					],
					'selectors'  => [
						'{{WRAPPER}} .swiper-pagination' => 'left: {{SIZE}}{{UNIT}} !important;',
					],
				]
			);

			$this->add_control(
				'dot_vertically',
				[
					'label'      => esc_html__( 'Dot Position Vertically ', 'absolute-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ '%' ],
					'range'      => [
						'px' => [
							'min'  => -100,
							'max'  => 1000,
							'step' => 5,
						],
						'%'  => [
							'min' => -20,
							'max' => 120,
						],
					],
					'default'    => [
						'unit' => '%',
						'size' => 100,
					],
					'selectors'  => [
						'{{WRAPPER}} .swiper-pagination' => 'top: {{SIZE}}{{UNIT}};transform: translateY(-{{SIZE}}{{UNIT}})',
					],
				]
			);



			$this->end_controls_section();

		}

		protected $slider_dot;
		protected $slider_next;
		protected $slider_prev;

		protected function get_slider_attributes( $settings = [], $spacing_between = 0 ) {

			if ( empty( $settings ) ) {
				$settings = $this->get_settings_for_display();
			}

			$this->slider_dot  = wp_unique_id( 'nav-dot-' );
			$this->slider_next = wp_unique_id( 'nav-next-' );
			$this->slider_prev = wp_unique_id( 'nav-prev-' );

			if ( ( ! empty( $settings['spacing_between']['size'] ) && $settings['spacing_between']['size'] ) ) {
				$spacing_between = absint( $settings['spacing_between']['size'] );
			}

			$slides_to_show_mobile = ( ! empty( $settings['slides_to_show_mobile'] ) ) ? ( $settings['slides_to_show_mobile'] ) : ( $settings['slides_to_show'] ) ;
			$slides_to_show_tablet = ( ! empty( $settings['slides_to_show_mobile'] ) ) ? ( $settings['slides_to_show_tablet'] ) : ( $settings['slides_to_show'] ) ;
			$slides_to_scroll_mobile = ( ! empty( $settings['slides_to_scroll_mobile'] ) ) ? ( $settings['slides_to_scroll_mobile'] ) : ( $settings['slides_to_scroll'] ) ;
			$slides_to_scroll_tablet = ( ! empty( $settings['slides_to_scroll_tablet'] ) ) ? ( $settings['slides_to_scroll_tablet'] ) : ( $settings['slides_to_scroll'] ) ;


			return [
				'data-slides-per-view'         => esc_attr( $settings['slides_to_show'] ),
				'data-slides-per-group'        => esc_attr( $settings['slides_to_scroll'] ),
				'data-slides-per-view-mobile'  => esc_attr( $slides_to_show_mobile ),
				'data-slides-per-group-mobile' => esc_attr( $slides_to_scroll_mobile ),
				'data-slides-per-view-tablet'  => esc_attr( $slides_to_show_tablet ),
				'data-slides-per-group-tablet' => esc_attr( $slides_to_scroll_tablet ),
				'data-slides-center'           => esc_attr( $settings['slides_center'] ),
				'data-autoplay'                => esc_attr( $settings['autoplay'] ),
				'data-autoplay-delay'          => esc_attr( $settings['autoplay_delay'] ),
				'data-effect'                  => esc_attr( $settings['effect'] ),
				'data-slides-loop'             => esc_attr( $settings['slides_loop'] ),
				'dir'                          => esc_attr( $settings['direction'] ),
				'data-space-between'           => esc_attr( $spacing_between ),
				'data-slides-dot'              => esc_attr( $this->slider_dot ),
				'data-slides-next'             => esc_attr( $this->slider_next ),
				'data-slides-prev'             => esc_attr( $this->slider_prev ),
			];
		}

		/**
		 * Slider Nav.
		 *
		 * @param array $settings settings
		 */
		protected function slider_nav( $settings = [] ) {
			if ( empty( $settings ) ) {
				$settings = $this->get_settings_for_display();
			}
			if ( in_array( $settings['navigation'], [ 'dots', 'both' ], TRUE ) ) {
				?>
				<div class="swiper-pagination  <?php echo esc_attr( $this->slider_dot ); ?>"></div>
				<?php
			}
			if ( in_array( $settings['navigation'], [ 'arrows', 'both' ], TRUE ) ) {
				?>
				<div class="">
					<div
						class="elementor-swiper-button elementor-swiper-button-prev absp-nav-prev <?php echo esc_attr( $this->slider_prev ); ?>">
						<?php Icons_Manager::render_icon( $settings['navigation_prev_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						<span class="elementor-screen-only"><?php esc_html_e( 'Previous', 'absolute-addons' ); ?></span>
					</div>
					<div
						class="elementor-swiper-button elementor-swiper-button-next absp-nav-next <?php echo esc_attr( $this->slider_next ); ?>">
						<?php Icons_Manager::render_icon( $settings['navigation_next_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						<span class="elementor-screen-only"><?php esc_html_e( 'Next', 'absolute-addons' ); ?></span>
					</div>
				</div>
				<?php
			}
		}
	}

// End of file trait-absp-slider-controller.php.
