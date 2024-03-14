<?php

abstract class QiAddonsForElementor_Slider_Shortcode extends QiAddonsForElementor_Framework_Shortcode {
	private $post_type;
	private $post_type_taxonomy;
	private $post_type_additional_taxonomies = array();
	private $layouts = array();
	private $extra_options = array();

	public function __construct() {
		parent::__construct();

		$this->register_list_scripts();
	}

	public function get_post_type() {
		return $this->post_type;
	}

	public function set_post_type( $post_type ) {
		$this->post_type = $post_type;
	}

	public function get_post_type_taxonomy() {
		return $this->post_type_taxonomy;
	}

	public function set_post_type_taxonomy( $post_type_taxonomy ) {
		$this->post_type_taxonomy = $post_type_taxonomy;
	}

	public function get_post_type_additional_taxonomies() {
		return $this->post_type_additional_taxonomies;
	}

	public function set_post_type_additional_taxonomies( $post_type_additional_taxonomies ) {
		$this->post_type_additional_taxonomies = $post_type_additional_taxonomies;
	}

	public function get_layouts() {
		return $this->layouts;
	}

	public function set_layouts( $layouts ) {
		$this->layouts = $layouts;
	}

	public function get_extra_options() {
		return $this->extra_options;
	}

	public function set_extra_options( $extra_options ) {
		$this->extra_options = $extra_options;
	}

	public function map_slider_options( $params = array() ) {
		$group                           = isset( $params['group'] ) ? $params['group'] : null;
		$exclude_option                  = isset( $params['exclude_option'] ) ? $params['exclude_option'] : array();
		$include_option                  = isset( $params['include_option'] ) ? $params['include_option'] : array();
		$exclude_columns                 = isset( $params['exclude_columns'] ) ? $params['exclude_columns'] : array();
		$include_columns                 = isset( $params['include_columns'] ) ? $params['include_columns'] : array();
		$direction_horizontal_dependancy = array();
		$direction_vertical_dependancy   = array();

		if ( ! empty( $include_option ) && in_array( 'direction', $include_option, true ) ) {
			$direction_horizontal_dependancy = array(
				'show' => array(
					'direction' => array(
						'values'        => 'horizontal',
						'default_value' => 'horizontal',
					),
				),
			);

			$direction_vertical_dependancy = array(
				'show' => array(
					'direction' => array(
						'values'        => 'vertical',
						'default_value' => 'horizontal',
					),
				),
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'direction',
					'title'      => esc_html__( 'Direction', 'qi-addons-for-elementor' ),
					'options'    => array(
						'horizontal' => esc_html__( 'Horizontal', 'qi-addons-for-elementor' ),
						'vertical'   => esc_html__( 'Vertical', 'qi-addons-for-elementor' ),
					),
					'group'      => $group,
				)
			);
		}
		if ( ! empty( $include_option ) && in_array( 'vertical', $include_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'hidden',
					'name'       => 'direction',
					'title'      => esc_html__( 'Direction', 'qi-addons-for-elementor' ),
					'options'    => array(
						'vertical' => esc_html__( 'Vertical', 'qi-addons-for-elementor' ),
					),
					'group'      => $group,
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'vertical_slider_height',
					'title'      => esc_html__( 'Vertical Slider Height', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 1440,
						),
						'%'  => array(
							'min' => 0,
							'max' => 100,
						),
						'vh' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'responsive' => true,
					'dependency' => array(
						'relation' => 'and',
						'hide'     => array(
							'enable_focus_in_viewport_vertical' => array(
								'values'        => 'yes',
								'default_value' => 'yes',
							),
							'slider_loop'                       => array(
								'values'        => 'no',
								'default_value' => 'yes',
							),
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .swiper-container-vertical' => 'height: {{SIZE}}{{UNIT}} !important;',
						'{{WRAPPER}} .swiper-vertical'           => 'height: {{SIZE}}{{UNIT}} !important;',
					),
					'group'      => $group,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'checkbox',
					'name'          => 'enable_focus_in_viewport_vertical',
					'title'         => esc_html__( 'Enable Focus When in Viewport', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
					'default_value' => 'yes',
					'dependency'    => array(
						'show' => array(
							'slider_loop' => array(
								'values'        => 'no',
								'default_value' => 'no',
							),
						),
					),
					'group'         => esc_html__( 'Slider Settings', 'qi-addons-for-elementor' ),
				)
			);
		}
		if ( ! empty( $include_option ) && in_array( 'slider-height', $include_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'slider_height',
					'title'      => esc_html__( 'Slider Height', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'vh' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 1440,
						),
						'%'  => array(
							'min' => 0,
							'max' => 100,
						),
						'vh' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-swiper-container'                   => 'height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-swiper-container .swiper-slide img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
					),
					'group'      => $group,
				)
			);
		}
		if ( empty( $exclude_option ) || ! in_array( 'loop', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'slider_loop',
					'title'      => esc_html__( 'Enable Slider Loop', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
					'group'      => $group,
				)
			);
		}
		if ( empty( $exclude_option ) || ! in_array( 'centered', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'centered_slides',
					'title'      => esc_html__( 'Enable Centered Slides', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'group'      => $group,
				)
			);
		}
		if ( empty( $exclude_option ) || ! in_array( 'autoplay', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'slider_autoplay',
					'title'      => esc_html__( 'Enable Slider Autoplay', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
					'group'      => $group,
				)
			);
		}
		$this->set_option(
			array(
				'field_type'  => 'text',
				'name'        => 'slider_speed',
				'title'       => esc_html__( 'Slide Duration', 'qi-addons-for-elementor' ),
				'description' => esc_html__( 'Default value is 5000 (ms)', 'qi-addons-for-elementor' ),
				'group'       => $group,
			)
		);
		if ( empty( $exclude_option ) || ! in_array( 'slider_speed_animation', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type'  => 'text',
					'name'        => 'slider_speed_animation',
					'title'       => esc_html__( 'Slide Animation Duration', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Speed of slide animation in milliseconds. Default value is 800.', 'qi-addons-for-elementor' ),
					'group'       => $group,
				)
			);
		}

		if ( empty( $exclude_option ) || ! in_array( 'slider_navigation', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'slider_navigation',
					'title'      => esc_html__( 'Enable Slider Navigation', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'group'      => $group,
				)
			);
		}

		if ( empty( $exclude_option ) || ! in_array( 'slider_pagination', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'slider_pagination',
					'title'      => esc_html__( 'Enable Slider Pagination', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'group'      => $group,
				)
			);
		}

		if ( empty( $exclude_option ) || ! in_array( 'images_proportion', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'images_proportion',
					'default_value' => 'full',
					'title'         => esc_html__( 'Image Proportions', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'list_image_dimension', false ),
					'group'         => $group,
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'custom_image_width',
					'title'       => esc_html__( 'Custom Image Width', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image width in px', 'qi-addons-for-elementor' ),
					'group'       => $group,
					'dependency'  => array(
						'show' => array(
							'images_proportion' => array(
								'values'        => 'custom',
								'default_value' => 'full',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'number',
					'name'        => 'custom_image_height',
					'title'       => esc_html__( 'Custom Image Height', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter image height in px', 'qi-addons-for-elementor' ),
					'group'       => $group,
					'dependency'  => array(
						'show' => array(
							'images_proportion' => array(
								'values'        => 'custom',
								'default_value' => 'full',
							),
						),
					),
				)
			);
		}

		if ( empty( $exclude_option ) || ! in_array( 'columns', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'partial_columns',
					'title'      => esc_html__( 'Enable Partial Columns', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'group'      => $group,
					'dependency' => $direction_horizontal_dependancy,
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'partial_columns_value',
					'title'      => esc_html__( 'Partial Columns Value', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min'  => 0.1,
							'max'  => 0.9,
							'step' => 0.1,
						),
					),
					'responsive' => false,
					'dependency' => array(
						'relation' => 'and',
						'show'     => array_merge_recursive(
							array(
								'show' => array(
									'partial_columns' => array(
										'values'        => 'yes',
										'default_value' => 'no',
									),
								),
							),
							$direction_horizontal_dependancy
						)['show'],
					),
					'group'      => $group,
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'disable_partial_columns_under',
					'title'      => esc_html__( 'Disable Partial Columns Under', 'qi-addons-for-elementor' ),
					'options'    => array(
						''     => esc_html__( 'Never', 'qi-addons-for-elementor' ),
						'1024' => esc_html__( '1024', 'qi-addons-for-elementor' ),
						'768'  => esc_html__( '768', 'qi-addons-for-elementor' ),
						'680'  => esc_html__( '680', 'qi-addons-for-elementor' ),
						'480'  => esc_html__( '480', 'qi-addons-for-elementor' ),
					),
					'dependency' => array(
						'relation' => 'and',
						'show'     => array_merge_recursive(
							array(
								'show' => array(
									'partial_columns' => array(
										'values'        => 'yes',
										'default_value' => 'no',
									),
								),
							),
							$direction_horizontal_dependancy
						)['show'],
					),
					'group'      => $group,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'columns',
					'title'         => esc_html__( 'Number of Columns', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_number', true, $exclude_columns, $include_columns ),
					'default_value' => '3',
					'group'         => $group,
					'dependency'    => $direction_horizontal_dependancy,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'columns_responsive',
					'title'         => esc_html__( 'Columns Responsive', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_responsive', false ),
					'default_value' => 'predefined',
					'group'         => $group,
					'dependency'    => $direction_horizontal_dependancy,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'columns_1680',
					'title'         => esc_html__( 'Number of Columns 1441px - 1680px', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_number' ),
					'default_value' => '3',
					'dependency'    => array(
						'relation' => 'and',
						'show'     => array_merge_recursive(
							array(
								'show' => array(
									'columns_responsive' => array(
										'values'        => 'custom',
										'default_value' => 'predefined',
									),
								),
							),
							$direction_horizontal_dependancy
						)['show'],
					),
					'group'         => $group,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'columns_1440',
					'title'         => esc_html__( 'Number of Columns 1367px - 1440px', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_number' ),
					'default_value' => '3',
					'dependency'    => array(
						'relation' => 'and',
						'show'     => array_merge_recursive(
							array(
								'show' => array(
									'columns_responsive' => array(
										'values'        => 'custom',
										'default_value' => 'predefined',
									),
								),
							),
							$direction_horizontal_dependancy
						)['show'],
					),
					'group'         => $group,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'columns_1366',
					'title'         => esc_html__( 'Number of Columns 1025px - 1366px', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_number' ),
					'default_value' => '3',
					'dependency'    => array(
						'relation' => 'and',
						'show'     => array_merge_recursive(
							array(
								'show' => array(
									'columns_responsive' => array(
										'values'        => 'custom',
										'default_value' => 'predefined',
									),
								),
							),
							$direction_horizontal_dependancy
						)['show'],
					),
					'group'         => $group,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'columns_1024',
					'title'         => esc_html__( 'Number of Columns 769px - 1024px', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_number' ),
					'default_value' => '3',
					'dependency'    => array(
						'relation' => 'and',
						'show'     => array_merge_recursive(
							array(
								'show' => array(
									'columns_responsive' => array(
										'values'        => 'custom',
										'default_value' => 'predefined',
									),
								),
							),
							$direction_horizontal_dependancy
						)['show'],
					),
					'group'         => $group,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'columns_768',
					'title'         => esc_html__( 'Number of Columns 681px - 768px', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_number' ),
					'default_value' => '3',
					'dependency'    => array(
						'relation' => 'and',
						'show'     => array_merge_recursive(
							array(
								'show' => array(
									'columns_responsive' => array(
										'values'        => 'custom',
										'default_value' => 'predefined',
									),
								),
							),
							$direction_horizontal_dependancy
						)['show'],
					),
					'group'         => $group,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'columns_680',
					'title'         => esc_html__( 'Number of Columns 481px - 680px', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_number' ),
					'default_value' => '3',
					'dependency'    => array(
						'relation' => 'and',
						'show'     => array_merge_recursive(
							array(
								'show' => array(
									'columns_responsive' => array(
										'values'        => 'custom',
										'default_value' => 'predefined',
									),
								),
							),
							$direction_horizontal_dependancy
						)['show'],
					),
					'group'         => $group,
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'columns_480',
					'title'         => esc_html__( 'Number of Columns 0 - 480px', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_number' ),
					'default_value' => '3',
					'dependency'    => array(
						'relation' => 'and',
						'show'     => array_merge_recursive(
							array(
								'show' => array(
									'columns_responsive' => array(
										'values'        => 'custom',
										'default_value' => 'predefined',
									),
								),
							),
							$direction_horizontal_dependancy
						)['show'],
					),
					'group'         => $group,
				)
			);
		}

		if ( empty( $exclude_option ) || ! in_array( 'space', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type'     => 'slider',
					'name'           => 'space',
					'title'          => esc_html__( 'Space Between Items', 'qi-addons-for-elementor' ),
					'range'          => array(
						'px' => array(
							'min'  => 0,
							'max'  => 300,
							'step' => 5,
						),
					),
					'responsive'     => true,
					'map_responsive' => true,
					'default_value'  => array(
						'unit' => 'px',
						'size' => 30,
					),
					'group'          => $group,
				)
			);
		}

		if ( empty( $exclude_option ) || ! in_array( 'slider_navigation', $exclude_option, true ) ) {

			if ( empty( $exclude_option ) || ! in_array( 'slider_navigation_position', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'select',
						'name'       => 'slider_navigation_position',
						'title'      => esc_html__( 'Navigation Position', 'qi-addons-for-elementor' ),
						'options'    => array(
							'inside'   => esc_html__( 'Inside', 'qi-addons-for-elementor' ),
							'outside'  => esc_html__( 'Outside', 'qi-addons-for-elementor' ),
							'together' => esc_html__( 'Together', 'qi-addons-for-elementor' ),
						),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'hide_slider_navigation', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'select',
						'name'       => 'hide_slider_navigation',
						'title'      => esc_html__( 'Hide Navigation', 'qi-addons-for-elementor' ),
						'options'    => array(
							''     => esc_html__( 'Default', 'qi-addons-for-elementor' ),
							'1024' => esc_html__( 'Below 1024px', 'qi-addons-for-elementor' ),
							'768'  => esc_html__( 'Below 768px', 'qi-addons-for-elementor' ),
							'680'  => esc_html__( 'Below 680px', 'qi-addons-for-elementor' ),
						),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_navigation_vertical_offset', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_navigation_vertical_offset',
						'title'      => esc_html__( 'Navigation Vertical Offset', 'qi-addons-for-elementor' ),
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation'          => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => 'together',
									'default_value' => '',
								),
							),
						),
						'size_units' => array( 'px', '%', 'em' ),
						'range'      => array(
							'px' => array(
								'min' => - 300,
								'max' => 300,
							),
							'%'  => array(
								'min' => - 300,
								'max' => 300,
							),
							'em' => array(
								'min' => - 30,
								'max' => 30,
							),
						),
						'responsive' => true,
						'selectors'  => array(
							'{{WRAPPER}} .swiper-button-next'                              => 'top: calc(50% + {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .swiper-button-prev'                              => 'top: calc(50% + {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .swiper-container-vertical .swiper-button-prev'   => 'top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-container-vertical ~ .swiper-button-prev' => 'top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-container-vertical .swiper-button-next'   => 'bottom: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-container-vertical ~ .swiper-button-next' => 'bottom: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-vertical .swiper-button-prev'             => 'top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-vertical ~ .swiper-button-prev'           => 'top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-vertical .swiper-button-next'             => 'bottom: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-vertical ~ .swiper-button-next'           => 'bottom: {{SIZE}}{{UNIT}};',
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_navigation_horizontal_offset', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_navigation_horizontal_offset',
						'title'      => esc_html__( 'Navigation Horizontal Offset', 'qi-addons-for-elementor' ),
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation'          => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => 'together',
									'default_value' => '',
								),
							),
						),
						'size_units' => array( 'px', '%', 'em' ),
						'range'      => array(
							'px' => array(
								'min' => - 300,
								'max' => 300,
							),
							'%'  => array(
								'min' => - 300,
								'max' => 300,
							),
							'em' => array(
								'min' => - 30,
								'max' => 30,
							),
						),
						'responsive' => true,
						'selectors'  => array(
							'{{WRAPPER}} .qodef-qi-swiper-container:not(.swiper-container-vertical):not(.swiper-vertical) > .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-qi-swiper-container:not(.swiper-container-vertical):not(.swiper-vertical) > .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-qi-swiper-container:not(.swiper-container-vertical):not(.swiper-vertical) ~ .swiper-button-next' => 'right: calc(-1*{{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .qodef-qi-swiper-container:not(.swiper-container-vertical):not(.swiper-vertical) ~ .swiper-button-prev' => 'left: calc(-1*{{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .swiper-container-vertical > .swiper-button-next'                                                       => 'left: calc(50% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .swiper-container-vertical > .swiper-button-prev'                                                       => 'left: calc(50% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .swiper-container-vertical ~ .swiper-button-next'                                                       => 'left: calc(50% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .swiper-container-vertical ~ .swiper-button-prev'                                                       => 'left: calc(50% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .swiper-vertical > .swiper-button-next'                                                                 => 'left: calc(50% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .swiper-vertical > .swiper-button-prev'                                                                 => 'left: calc(50% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .swiper-vertical ~ .swiper-button-next'                                                                 => 'left: calc(50% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .swiper-vertical ~ .swiper-button-prev'                                                                 => 'left: calc(50% - {{SIZE}}{{UNIT}});',
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_navigation_together_alignment', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'select',
						'name'       => 'slider_navigation_together_alignment',
						'title'      => esc_html__( 'Navigation Alignment', 'qi-addons-for-elementor' ),
						'options'    => array(
							'flex-start' => esc_html__( 'Left', 'qi-addons-for-elementor' ),
							'flex-end'   => esc_html__( 'Right', 'qi-addons-for-elementor' ),
						),
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation'          => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => array( 'inside', 'outside' ),
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_navigation_together_vertical_position', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type'    => 'select',
						'name'          => 'slider_navigation_together_vertical_position',
						'title'         => esc_html__( 'Navigation Vertical Position', 'qi-addons-for-elementor' ),
						'description'   => esc_html__( 'Doesn\'t affect vertical sliders', 'qi-addons-for-elementor' ),
						'options'       => array(
							'bottom' => esc_html__( 'Bottom', 'qi-addons-for-elementor' ),
							'top'    => esc_html__( 'Top', 'qi-addons-for-elementor' ),
						),
						'default_value' => 'bottom',
						'dependency'    => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => array( 'inside', 'outside' ),
									'default_value' => '',
								),
							),
						),
						'prefix_class'  => 'qodef-navigation-together--',
						'group'         => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_navigation_together_space_between', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_navigation_together_space_between',
						'title'      => esc_html__( 'Space Between Arrows', 'qi-addons-for-elementor' ),
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => array( 'inside', 'outside' ),
									'default_value' => '',
								),
							),
						),
						'size_units' => array( 'px', 'em' ),
						'responsive' => true,
						'selectors' => array(
							'{{WRAPPER}} .qodef-swiper-together-inner > .swiper-button-prev'                                                         => 'margin-right: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} .swiper-container-vertical .qodef-swiper-together-nav .qodef-swiper-together-inner > .swiper-button-prev'   => 'margin: 0 0 {{SIZE}}{{UNIT}} 0 !important;',
							'{{WRAPPER}} .swiper-container-vertical ~ .qodef-swiper-together-nav .qodef-swiper-together-inner > .swiper-button-prev' => 'margin: 0 0 {{SIZE}}{{UNIT}} 0 !important;',
							'{{WRAPPER}} .swiper-vertical .qodef-swiper-together-nav .qodef-swiper-together-inner > .swiper-button-prev'             => 'margin: 0 0 {{SIZE}}{{UNIT}} 0 !important;',
							'{{WRAPPER}} .swiper-vertical ~ .qodef-swiper-together-nav .qodef-swiper-together-inner > .swiper-button-prev'           => 'margin: 0 0 {{SIZE}}{{UNIT}} 0 !important;',
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_navigation_together_margin_top', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_navigation_together_margin_top',
						'title'      => esc_html__( 'Navigation Vertical Offset', 'qi-addons-for-elementor' ),
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => array( 'inside', 'outside' ),
									'default_value' => '',
								),
							),
						),
						'range'      => array(
							'px' => array(
								'min' => - 300,
								'max' => 300,
							),
							'em' => array(
								'min' => - 30,
								'max' => 30,
							),
						),
						'size_units' => array( 'px', 'em' ),
						'responsive' => true,
						'selectors'  => array(
							'{{WRAPPER}} .qodef-swiper-together-nav'                                                           => 'margin-top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-container-vertical .qodef-swiper-together-nav .qodef-swiper-together-inner'   => 'margin-top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-container-vertical ~ .qodef-swiper-together-nav .qodef-swiper-together-inner' => 'margin-top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-vertical .qodef-swiper-together-nav .qodef-swiper-together-inner'             => 'margin-top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-vertical ~ .qodef-swiper-together-nav .qodef-swiper-together-inner'           => 'margin-top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}}.qodef-navigation-together--top .qodef-swiper-together-nav'                            => 'margin-bottom: {{SIZE}}{{UNIT}};',
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
			}
			if ( empty( $exclude_option ) || ! in_array( 'slider_navigation_together_horizontal_offset', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_navigation_together_horizontal_offset',
						'title'      => esc_html__( 'Navigation Horizontal Offset', 'qi-addons-for-elementor' ),
						'range'      => array(
							'px' => array(
								'min' => - 300,
								'max' => 300,
							),
							'%'  => array(
								'min' => - 100,
								'max' => 100,
							),
							'em' => array(
								'min' => - 50,
								'max' => 50,
							),
						),
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation'          => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => array( 'inside', 'outside' ),
									'default_value' => '',
								),
							),
						),
						'size_units' => array( 'px', '%', 'em' ),
						'responsive' => true,
						'selectors'  => array(
							'{{WRAPPER}} .qodef-swiper-together-nav'                                                                     => 'left: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-container-vertical.qodef-navigation-alignment--flex-start ~ .qodef-swiper-together-nav' => 'left: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} .swiper-container-vertical.qodef-navigation-alignment--flex-end ~ .qodef-swiper-together-nav'   => 'right: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} .swiper-container-vertical.qodef-navigation-alignment--flex-start .qodef-swiper-together-nav'   => 'left: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} .swiper-container-vertical.qodef-navigation-alignment--flex-end .qodef-swiper-together-nav'     => 'right: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} .swiper-vertical.qodef-navigation-alignment--flex-start ~ .qodef-swiper-together-nav'           => 'left: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} .swiper-vertical.qodef-navigation-alignment--flex-end ~ .qodef-swiper-together-nav'             => 'right: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} .swiper-vertical.qodef-navigation-alignment--flex-start .qodef-swiper-together-nav'             => 'left: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} .swiper-vertical.qodef-navigation-alignment--flex-end .qodef-swiper-together-nav'               => 'right: {{SIZE}}{{UNIT}} !important;',
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_navigation_arrows', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type'    => 'icons',
						'name'          => 'slider_navigation_arrow_prev',
						'title'         => esc_html__( 'Navigation Arrow Previous', 'qi-addons-for-elementor' ),
						'dependency'    => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'default_value' => array(),
						'group'         => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type'    => 'icons',
						'name'          => 'slider_navigation_arrow_next',
						'title'         => esc_html__( 'Navigation Arrow Next', 'qi-addons-for-elementor' ),
						'dependency'    => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'default_value' => array(),
						'group'         => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_navigation_arrows_style', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'divider',
						'name'       => 'item_divider_navigation_style',
						'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'navigation_together_holder_width',
						'title'      => esc_html__( 'Navigation Holder Width', 'qi-addons-for-elementor' ),
						'size_units' => array( 'px', '%', 'em' ),
						'range'      => array(
							'px' => array(
								'min' => 0,
								'max' => 300,
							),
						),
						'responsive' => true,
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation'          => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => array( 'inside', 'outside' ),
									'default_value' => '',
								),
							),
						),
						'selectors'  => array(
							'{{WRAPPER}} .qodef-swiper-together-inner' => 'width: {{SIZE}}{{UNIT}};',
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'navigation_together_holder_height',
						'title'      => esc_html__( 'Navigation Holder Height', 'qi-addons-for-elementor' ),
						'size_units' => array( 'px', '%', 'em' ),
						'range'      => array(
							'px' => array(
								'min' => 0,
								'max' => 300,
							),
						),
						'responsive' => true,
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation'          => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => array( 'inside', 'outside' ),
									'default_value' => '',
								),
							),
						),
						'selectors'  => array(
							'{{WRAPPER}} .qodef-swiper-together-inner' => 'height: {{SIZE}}{{UNIT}};',
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'color',
						'name'       => 'navigation_together_holder_background_color',
						'title'      => esc_html__( 'Navigation Background Color', 'qi-addons-for-elementor' ),
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation'          => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => array( 'inside', 'outside' ),
									'default_value' => '',
								),
							),
						),
						'selectors'  => array(
							'{{WRAPPER}} .qodef-swiper-together-inner' => 'background-color: {{VALUE}};',
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'border',
						'name'       => 'navigation_together_holder_border',
						'title'      => esc_html__( 'Navigation Border', 'qi-addons-for-elementor' ),
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation'          => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => array( 'inside', 'outside' ),
									'default_value' => '',
								),
							),
						),
						'selector'   => '{{WRAPPER}} .qodef-swiper-together-inner',
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'dimensions',
						'name'       => 'navigation_together_holder_border_radius',
						'title'      => esc_html__( 'Navigation Border Radius', 'qi-addons-for-elementor' ),
						'size_units' => array( 'px', '%' ),
						'responsive' => true,
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation'          => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => array( 'inside', 'outside' ),
									'default_value' => '',
								),
							),
						),
						'selectors'  => array(
							'{{WRAPPER}} .qodef-swiper-together-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'divider',
						'name'       => 'item_divider_navigation_together_style',
						'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_navigation'          => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_navigation_position' => array(
									'values'        => array( 'inside', 'outside' ),
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'start_controls_tabs',
						'name'       => 'arrows_style_tabs',
						'title'      => esc_html__( 'Arrows Start', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'start_controls_tab',
						'name'       => 'arrows_style_tab_normal',
						'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'color',
						'name'       => 'slider_navigation_arrows_color',
						'title'      => esc_html__( 'Navigation Arrow Color', 'qi-addons-for-elementor' ),
						'selectors'  => array(
							'{{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}}',
							'{{WRAPPER}} .swiper-button-prev' => 'color: {{VALUE}}',
						),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'color',
						'name'       => 'slider_navigation_arrows_background_color',
						'title'      => esc_html__( 'Navigation Arrow Background Color', 'qi-addons-for-elementor' ),
						'selectors'  => array(
							'{{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}}',
							'{{WRAPPER}} .swiper-button-prev' => 'background-color: {{VALUE}}',
						),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'end_controls_tab',
						'name'       => 'arrows_style_tab_normal_end',
						'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'start_controls_tab',
						'name'       => 'arrows_style_tab_hover',
						'title'      => esc_html__( 'Hover', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'color',
						'name'       => 'slider_navigation_arrows_hover_color',
						'title'      => esc_html__( 'Navigation Arrow Hover Color', 'qi-addons-for-elementor' ),
						'selectors'  => array(
							'{{WRAPPER}} .swiper-button-next:hover' => 'color: {{VALUE}}',
							'{{WRAPPER}} .swiper-button-prev:hover' => 'color: {{VALUE}}',
						),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'color',
						'name'       => 'slider_navigation_arrows_hover_background_color',
						'title'      => esc_html__( 'Navigation Arrow Background Hover Color', 'qi-addons-for-elementor' ),
						'selectors'  => array(
							'{{WRAPPER}} .swiper-button-next:hover' => 'background-color: {{VALUE}}',
							'{{WRAPPER}} .swiper-button-prev:hover' => 'background-color: {{VALUE}}',
						),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'select',
						'name'       => 'navigation_hover_move',
						'title'      => esc_html__( 'Enable Hover Arrow Move', 'qi-addons-for-elementor' ),
						'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'end_controls_tab',
						'name'       => 'arrows_style_tab_hover_end',
						'title'      => esc_html__( 'Hover End', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'end_controls_tabs',
						'name'       => 'arrows_style_tabs_end',
						'title'      => esc_html__( 'Arrows End', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'divider',
						'name'       => 'item_divider_navigation_style_end',
						'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_navigation_arrows_size',
						'title'      => esc_html__( 'Navigation Arrow Size', 'qi-addons-for-elementor' ),
						'size_units' => array( 'px', '%', 'em' ),
						'responsive' => true,
						'selectors'  => array(
							'{{WRAPPER}} .swiper-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
						),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_navigation_arrows_holder_width',
						'title'      => esc_html__( 'Navigation Arrow Holder Width', 'qi-addons-for-elementor' ),
						'size_units' => array( 'px', '%', 'em' ),
						'responsive' => true,
						'selectors'  => array(
							'{{WRAPPER}} .swiper-button-next' => 'width: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}} !important;',
						),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_navigation_arrows_holder_height',
						'title'      => esc_html__( 'Navigation Arrow Holder Height', 'qi-addons-for-elementor' ),
						'size_units' => array( 'px', '%', 'em' ),
						'responsive' => true,
						'selectors'  => array(
							'{{WRAPPER}} .swiper-button-next' => 'height: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} .swiper-button-prev' => 'height: {{SIZE}}{{UNIT}} !important;',
						),
						'dependency' => array(
							'hide' => array(
								'slider_navigation' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Navigation Style', 'qi-addons-for-elementor' ),
					)
				);
			}
		}

		if ( empty( $exclude_option ) || ! in_array( 'slider_pagination', $exclude_option, true ) ) {

			if ( empty( $exclude_option ) || ! in_array( 'slider_pagination_position', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'select',
						'name'       => 'slider_pagination_position',
						'title'      => esc_html__( 'Pagination Position', 'qi-addons-for-elementor' ),
						'options'    => array(
							'inside'  => esc_html__( 'Inside', 'qi-addons-for-elementor' ),
							'outside' => esc_html__( 'Outside', 'qi-addons-for-elementor' ),
						),
						'dependency' => array(
							'hide' => array(
								'slider_pagination' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_pagination_alignment', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'select',
						'name'       => 'slider_pagination_alignment',
						'title'      => esc_html__( 'Pagination Alignment', 'qi-addons-for-elementor' ),
						'options'    => array(
							''       => esc_html__( 'Default', 'qi-addons-for-elementor' ),
							'start'  => esc_html__( 'Start', 'qi-addons-for-elementor' ),
							'center' => esc_html__( 'Center', 'qi-addons-for-elementor' ),
							'end'    => esc_html__( 'End', 'qi-addons-for-elementor' ),
						),
						'dependency' => array(
							'hide' => array(
								'slider_pagination' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_pagination_numbers', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type'    => 'checkbox',
						'name'          => 'slider_pagination_numbers',
						'title'         => esc_html__( 'Enable Numbers', 'qi-addons-for-elementor' ),
						'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
						'default_value' => 'no',
						'dependency'    => array(
							'hide' => array(
								'slider_pagination' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'         => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'color',
						'name'       => 'slider_pagination_numbers_color',
						'title'      => esc_html__( 'Numbers Color', 'qi-addons-for-elementor' ),
						'selectors'  => array(
							'{{WRAPPER}} .qodef--pagination-numbers ~ .swiper-pagination-bullets .swiper-pagination-bullet:before' => 'color: {{VALUE}};',
							'{{WRAPPER}} .qodef--pagination-numbers>.swiper-pagination-bullets .swiper-pagination-bullet:before' => 'color: {{VALUE}};',
						),
						'dependency' => array(
							'show' => array(
								'slider_pagination_numbers' => array(
									'values'        => 'yes',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'typography',
						'name'       => 'slider_pagination_numbers_typography',
						'title'      => esc_html__( 'Numbers Typography', 'qi-addons-for-elementor' ),
						'dependency' => array(
							'show' => array(
								'slider_pagination_numbers' => array(
									'values'        => 'yes',
									'default_value' => '',
								),
							),
						),
						'selector'   => '{{WRAPPER}} .qodef--pagination-numbers ~ .swiper-pagination-bullets .swiper-pagination-bullet:before, {{WRAPPER}} .qodef--pagination-numbers>.swiper-pagination-bullets .swiper-pagination-bullet:before',
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_pagination_offset', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_pagination_offset',
						'title'      => esc_html__( 'Pagination Offset', 'qi-addons-for-elementor' ),
						'size_units' => array( 'px', '%', 'vh' ),
						'range'      => array(
							'px' => array(
								'min' => 0,
								'max' => 300,
							),
							'%'  => array(
								'min' => 0,
								'max' => 100,
							),
							'vh' => array(
								'min' => 0,
								'max' => 10,
							),
						),
						'responsive' => true,
						'selectors'  => array(
							'{{WRAPPER}} .qodef-qi-swiper-container.qodef-pagination--inside > .swiper-pagination'  => 'bottom: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-swiper-pagination-outside'                                          => 'margin-top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-container-vertical ~ .qodef-swiper-pagination-outside'             => 'margin-top: 0; margin-left: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-qi-swiper-container.swiper-container-vertical > .swiper-pagination' => 'bottom: auto; right: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .swiper-vertical ~ .qodef-swiper-pagination-outside'                       => 'margin-top: 0; margin-left: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-qi-swiper-container.swiper-vertical > .swiper-pagination'           => 'bottom: auto; right: {{SIZE}}{{UNIT}};',
						),
						'dependency' => array(
							'hide' => array(
								'slider_pagination' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_pagination_offset_from_edge', $exclude_option, true ) || ! in_array( 'slider_pagination_alignment', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_pagination_offset_from_edge',
						'title'      => esc_html__( 'Pagination Offset from Edge', 'qi-addons-for-elementor' ),
						'size_units' => array( 'px', '%' ),
						'range'      => array(
							'px' => array(
								'min' => 0,
								'max' => 300,
							),
							'%'  => array(
								'min' => 0,
								'max' => 100,
							),
							'vh' => array(
								'min' => 0,
								'max' => 10,
							),
						),
						'responsive' => true,
						'selectors'  => array(
							'{{WRAPPER}} .qodef-pagination-alignment--start.swiper-container-horizontal > .swiper-pagination-bullets' => 'left: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-pagination-alignment--start.swiper-container-horizontal ~ .swiper-pagination-bullets' => 'left: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-pagination-alignment--end.swiper-container-horizontal > .swiper-pagination-bullets'   => 'width: calc(100% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .qodef-pagination-alignment--end.swiper-container-horizontal ~ .swiper-pagination-bullets'   => 'width: calc(100% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .qodef-pagination-alignment--start.swiper-container-vertical > .swiper-pagination-bullets'   => 'top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-pagination-alignment--start.swiper-container-vertical ~ .swiper-pagination-bullets'   => 'top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-pagination-alignment--end.swiper-container-vertical > .swiper-pagination-bullets'     => 'top: calc(100% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .qodef-pagination-alignment--end.swiper-container-vertical ~ .swiper-pagination-bullets'     => 'top: calc(100% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .qodef-pagination-alignment--start.swiper-horizontal > .swiper-pagination-bullets' => 'left: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-pagination-alignment--start.swiper-horizontal ~ .swiper-pagination-bullets' => 'left: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-pagination-alignment--end.swiper-horizontal > .swiper-pagination-bullets'   => 'width: calc(100% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .qodef-pagination-alignment--end.swiper-horizontal ~ .swiper-pagination-bullets'   => 'width: calc(100% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .qodef-pagination-alignment--start.swiper-vertical > .swiper-pagination-bullets'   => 'top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-pagination-alignment--start.swiper-vertical ~ .swiper-pagination-bullets'   => 'top: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .qodef-pagination-alignment--end.swiper-vertical > .swiper-pagination-bullets'     => 'top: calc(100% - {{SIZE}}{{UNIT}});',
							'{{WRAPPER}} .qodef-pagination-alignment--end.swiper-vertical ~ .swiper-pagination-bullets'     => 'top: calc(100% - {{SIZE}}{{UNIT}});',
						),
						'dependency' => array(
							'relation' => 'or',
							'hide'     => array(
								'slider_pagination' => array(
									'values'        => 'no',
									'default_value' => '',
								),
								'slider_pagination_alignment' => array(
									'values'        => array( 'center', '' ),
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
			}

			if ( empty( $exclude_option ) || ! in_array( 'slider_pagination_style', $exclude_option, true ) ) {
				$this->set_option(
					array(
						'field_type' => 'start_controls_tabs',
						'name'       => 'pagination_tabs',
						'title'      => esc_html__( 'Tabs Start', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'start_controls_tab',
						'name'       => 'pagination_tab_normal',
						'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);

				$this->set_option(
					array(
						'field_type' => 'color',
						'name'       => 'slider_pagination_color',
						'title'      => esc_html__( 'Pagination Color', 'qi-addons-for-elementor' ),
						'selectors'  => array(
							'{{WRAPPER}} .swiper-pagination-bullet' => 'color: {{VALUE}};',
						),
						'dependency' => array(
							'hide' => array(
								'slider_pagination' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'border',
						'name'       => 'slider_pagination_border',
						'title'      => esc_html__( 'Pagination Border', 'qi-addons-for-elementor' ),
						'selector'   => '{{WRAPPER}} .swiper-pagination-bullet',
						'dependency' => array(
							'hide' => array(
								'slider_pagination' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'end_controls_tab',
						'name'       => 'pagination_tab_normal_end',
						'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'start_controls_tab',
						'name'       => 'pagination_tab_active',
						'title'      => esc_html__( 'Active', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'color',
						'name'       => 'slider_pagination_active_color',
						'title'      => esc_html__( 'Pagination Active Color', 'qi-addons-for-elementor' ),
						'selectors'  => array(
							'{{WRAPPER}} .swiper-pagination-bullet-active' => 'color: {{VALUE}};',
							'{{WRAPPER}} .swiper-pagination-bullet:hover'  => 'color: {{VALUE}};',
						),
						'dependency' => array(
							'hide' => array(
								'slider_pagination' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'border',
						'name'       => 'slider_pagination_active_border',
						'title'      => esc_html__( 'Pagination Active Border', 'qi-addons-for-elementor' ),
						'selector'   => '{{WRAPPER}} .swiper-pagination-bullet-active, {{WRAPPER}} .swiper-pagination-bullet:hover',
						'dependency' => array(
							'hide' => array(
								'slider_pagination' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'end_controls_tab',
						'name'       => 'pagination_tab_active_end',
						'title'      => esc_html__( 'Active End', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'end_controls_tabs',
						'name'       => 'pagination_tabs_end',
						'title'      => esc_html__( 'Tabs End', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'divider',
						'name'       => 'item_divider_pagination_style',
						'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_pagination_size',
						'title'      => esc_html__( 'Pagination Size', 'qi-addons-for-elementor' ),
						'size_units' => array( 'px', '%', 'em' ),
						'responsive' => true,
						'selectors'  => array(
							'{{WRAPPER}} .swiper-pagination-bullet'                                 => 'width: calc({{SIZE}}{{UNIT}}*1.4); height: calc({{SIZE}}{{UNIT}}*1.4);',
							'{{WRAPPER}} .swiper-container-horizontal > .swiper-pagination-bullets' => 'min-height: calc({{SIZE}}{{UNIT}}*1.4);',
							'{{WRAPPER}} .swiper-horizontal > .swiper-pagination-bullets'           => 'min-height: calc({{SIZE}}{{UNIT}}*1.4);',
						),
						'dependency' => array(
							'hide' => array(
								'slider_pagination' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'slider',
						'name'       => 'slider_pagination_space_between_bullets',
						'title'      => esc_html__( 'Space Between Bullets', 'qi-addons-for-elementor' ),
						'size_units' => array( 'px', '%', 'em' ),
						'responsive' => true,
						'selectors'  => array(
							/*multiplied with .72 because of the default bullet size, and divided by 2 because of the margins on both sides of bullet*/
							'{{WRAPPER}} .swiper-container-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet'                         => 'margin: 0 calc({{SIZE}}{{UNIT}}*0.72/2);',
							'{{WRAPPER}} .swiper-container-vertical>.swiper-pagination-bullets .swiper-pagination-bullet'                           => 'margin: calc({{SIZE}}{{UNIT}}*0.72/2) 0;',
							'{{WRAPPER}} .qodef-swiper-pagination-outside .swiper-pagination-bullet'                                                => 'margin: 0 calc({{SIZE}}{{UNIT}}*0.72/2);',
							'{{WRAPPER}} .swiper-container-vertical ~ .qodef-swiper-pagination-outside.swiper-pagination .swiper-pagination-bullet' => 'margin: calc({{SIZE}}{{UNIT}}*0.72/2) 0;',
							'{{WRAPPER}} .swiper-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet'                                   => 'margin: 0 calc({{SIZE}}{{UNIT}}*0.72/2);',
							'{{WRAPPER}} .swiper-vertical>.swiper-pagination-bullets .swiper-pagination-bullet'                                     => 'margin: calc({{SIZE}}{{UNIT}}*0.72/2) 0;',
							'{{WRAPPER}} .swiper-vertical ~ .qodef-swiper-pagination-outside.swiper-pagination .swiper-pagination-bullet'           => 'margin: calc({{SIZE}}{{UNIT}}*0.72/2) 0;',
						),
						'dependency' => array(
							'hide' => array(
								'slider_pagination' => array(
									'values'        => 'no',
									'default_value' => '',
								),
							),
						),
						'group'      => esc_html__( 'Slider Pagination Style', 'qi-addons-for-elementor' ),
					)
				);
			}
		}
	}

	public function get_slider_classes( $atts ) {
		$holder_classes = array();

		$holder_classes[] = 'qodef-qi-grid';
		$holder_classes[] = 'qodef-qi-swiper-container';

		$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-item-layout--' . $atts['layout'] : '';
		$holder_classes[] = ! empty( $atts['slider_navigation_position'] ) ? 'qodef-navigation--' . $atts['slider_navigation_position'] : '';
		$holder_classes[] = ! empty( $atts['slider_navigation_position'] ) && 'together' === $atts['slider_navigation_position'] && ! empty( $atts['slider_navigation_together_alignment'] ) ? 'qodef-navigation-alignment--' . $atts['slider_navigation_together_alignment'] : '';
		$holder_classes[] = ! empty( $atts['slider_pagination_position'] ) ? 'qodef-pagination--' . $atts['slider_pagination_position'] : '';
		$holder_classes[] = ! empty( $atts['slider_pagination_alignment'] ) ? 'qodef-pagination-alignment--' . $atts['slider_pagination_alignment'] : '';
		$holder_classes[] = ! empty( $atts['hide_slider_navigation'] ) ? 'qodef-hide-navigation--' . $atts['hide_slider_navigation'] : '';
//		$holder_classes[] = ! empty( $atts['enable_focus_in_viewport_vertical'] ) && 'yes' === $atts['enable_focus_in_viewport_vertical'] ? 'qodef--focus-in-viewport' : '';
		$holder_classes[] = ! empty( $atts['slider_pagination_numbers'] ) && 'yes' === $atts['slider_pagination_numbers'] ? 'qodef--pagination-numbers' : '';
		$holder_classes[] = isset( $atts['navigation_hover_move'] ) && 'yes' === $atts['navigation_hover_move'] ? 'qodef-navigation--hover-move' : '';

		return $holder_classes;
	}

	public function get_slider_item_classes( $atts ) {
		$item_classes = array();

		$item_classes[] = 'swiper-slide';

		if ( ! empty( $atts['image_dimension'] ) ) {
			$item_classes[] = $atts['image_dimension']['class'];
		}

		return $item_classes;
	}

	public function get_slider_item_image_dimension( $atts ) {
		$image_dimension = array(
			'size'  => $atts['images_proportion'],
			'class' => qi_addons_for_elementor_get_custom_image_size_class_name( $atts['images_proportion'] ),
		);

		return $image_dimension;
	}

	public function get_slider_data( $atts, $include = array() ) {
		$data = array();

		$partial_columns = isset( $atts['partial_columns'] ) ? 'no' !== $atts['partial_columns'] : false;
		if ( $partial_columns ) {
			$partial_value = isset( $atts['partial_columns_value'] ) ? round( floatval( $atts['partial_columns_value']['size'] ), 2 ) : 0.5;
		} else {
			$partial_value = 0;
		}

		$data['direction']           = isset( $atts['direction'] ) ? $atts['direction'] : 'horizontal';
		$data['slidesPerView']       = isset( $atts['columns'] ) ? $atts['columns'] : 1;
		$data['spaceBetween']        = isset( $atts['space']['size'] ) ? $atts['space']['size'] : 0;
		$data['spaceBetweenTablet']  = isset( $atts['space_tablet']['size'] ) ? $atts['space_tablet']['size'] : $data['spaceBetween'];
		$data['spaceBetweenMobile']  = isset( $atts['space_mobile']['size'] ) ? $atts['space_mobile']['size'] : $data['spaceBetweenTablet'];
		$data['effect']              = isset( $atts['effect'] ) ? $atts['effect'] : '';
		$data['loop']                = isset( $atts['slider_loop'] ) ? 'no' !== $atts['slider_loop'] : true;
		$data['autoplay']            = isset( $atts['slider_autoplay'] ) ? 'no' !== $atts['slider_autoplay'] : true;
		$data['centeredSlides']      = isset( $atts['centered_slides'] ) ? 'no' !== $atts['centered_slides'] : false;
		$data['speed']               = isset( $atts['slider_speed'] ) ? $atts['slider_speed'] : '';
		$data['speedAnimation']      = isset( $atts['slider_speed_animation'] ) ? $atts['slider_speed_animation'] : '';
		$data['outsideNavigation']   = isset( $atts['slider_navigation_position'] ) && ( 'outside' === $atts['slider_navigation_position'] || 'together' === $atts['slider_navigation_position'] ) ? 'yes' : 'no';
		$data['outsidePagination']   = isset( $atts['slider_pagination_position'] ) && ( 'outside' === $atts['slider_pagination_position'] ) ? 'yes' : 'no';
		$data['unique']              = isset( $atts['unique'] ) ? $atts['unique'] : '';
		$data['partialValue']        = $partial_value;
		$data['disablePartialValue'] = isset( $atts['disable_partial_columns_under'] ) ? $atts['disable_partial_columns_under'] : '';

		if ( ! empty( $atts['columns_responsive'] ) && 'custom' === $atts['columns_responsive'] ) {
			$data['customStages']      = true;
			$data['slidesPerView1680'] = ! empty( $atts['columns_1680'] ) ? $atts['columns_1680'] : $atts['columns'];
			$data['slidesPerView1440'] = ! empty( $atts['columns_1440'] ) ? $atts['columns_1440'] : $atts['columns'];
			$data['slidesPerView1366'] = ! empty( $atts['columns_1366'] ) ? $atts['columns_1366'] : $atts['columns'];
			$data['slidesPerView1024'] = ! empty( $atts['columns_1024'] ) ? $atts['columns_1024'] : $atts['columns'];
			$data['slidesPerView768']  = ! empty( $atts['columns_768'] ) ? $atts['columns_768'] : $atts['columns'];
			$data['slidesPerView680']  = ! empty( $atts['columns_680'] ) ? $atts['columns_680'] : $atts['columns'];
			$data['slidesPerView480']  = ! empty( $atts['columns_480'] ) ? $atts['columns_480'] : $atts['columns'];
		}

		if ( ! empty( $include ) ) {
			foreach ( $include as $key => $value ) {
				if ( ! array_key_exists( $key, $data ) ) {
					$data[ $key ] = $value;
				}
			}
		}

		return json_encode( $data );
	}

	public function map_query_options( $params = array() ) {
		$group                = isset( $params['group'] ) ? $params['group'] : esc_html__( 'Query', 'qi-addons-for-elementor' );
		$post_type            = isset( $params['post_type'] ) ? $params['post_type'] : 'post';
		$taxonomies_formatted = array();
		$exclude_option       = isset( $params['exclude_option'] ) ? $params['exclude_option'] : array();
		$exclude_order_by     = isset( $params['exclude_order_by'] ) ? $params['exclude_order_by'] : array();
		$include_order_by     = isset( $params['include_order_by'] ) ? $params['include_order_by'] : array();

		if ( ! empty( $post_type ) ) {
			$main_taxonomy = $this->get_post_type_taxonomy();
			$taxonomies    = array_filter( array_merge( array( ! empty( $main_taxonomy ) ? $main_taxonomy : '' ), $this->get_post_type_additional_taxonomies() ) );

			if ( ! empty( $taxonomies ) ) {
				foreach ( $taxonomies as $taxonomy ) {
					$taxonomies_formatted[ $taxonomy ] = ucwords(
						str_replace(
							array( '_', '-' ),
							array( ' ', ' ' ),
							$taxonomy
						)
					);
				}
			}
		}

		$this->set_option(
			array(
				'field_type'    => 'text',
				'name'          => 'posts_per_page',
				'title'         => esc_html__( 'Posts per Page', 'qi-addons-for-elementor' ),
				'default_value' => '9',
				'dynamic'       => false,
				'group'         => $group,
			)
		);
		$this->set_option(
			array(
				'field_type'    => 'select',
				'name'          => 'orderby',
				'title'         => esc_html__( 'Order By', 'qi-addons-for-elementor' ),
				'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'order_by', true, $exclude_order_by, $include_order_by ),
				'default_value' => 'date',
				'group'         => $group,
			)
		);
		$this->set_option(
			array(
				'field_type'    => 'select',
				'name'          => 'order',
				'title'         => esc_html__( 'Order', 'qi-addons-for-elementor' ),
				'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'order' ),
				'default_value' => 'DESC',
				'group'         => $group,
			)
		);

		if ( empty( $exclude_option ) || ! in_array( 'additional_params', $exclude_option, true ) ) {

			do_action( 'qi_addons_for_elementor_action_map_query_options_before_additional', $group );

			$additional_params = apply_filters(
				'qi_addons_for_elementor_filter_map_additional_query_params',
				array(
					''       => esc_html__( 'No', 'qi-addons-for-elementor' ),
					'id'     => esc_html__( 'Post IDs', 'qi-addons-for-elementor' ),
					'tax'    => esc_html__( 'Taxonomy Slug', 'qi-addons-for-elementor' ),
					'author' => esc_html__( 'Author Name', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'additional_params',
					'title'      => esc_html__( 'Additional Params', 'qi-addons-for-elementor' ),
					'options'    => $additional_params,
					'group'      => $group,
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'text',
					'name'        => 'post_ids',
					'title'       => esc_html__( 'Posts IDs', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Separate post IDs with commas', 'qi-addons-for-elementor' ),
					'group'       => $group,
					'dependency'  => array(
						'show' => array(
							'additional_params' => array(
								'values'        => 'id',
								'default_value' => '',
							),
						),
					),
				)
			);
			if ( ! empty( $taxonomies_formatted ) ) {
				$this->set_option(
					array(
						'field_type' => 'select',
						'name'       => 'tax',
						'title'      => esc_html__( 'Taxonomy', 'qi-addons-for-elementor' ),
						'options'    => $taxonomies_formatted,
						'group'      => $group,
						'dependency' => array(
							'show' => array(
								'additional_params' => array(
									'values'        => 'tax',
									'default_value' => '',
								),
							),
						),
					)
				);
				$this->set_option(
					array(
						'field_type' => 'text',
						'name'       => 'tax_slug',
						'title'      => esc_html__( 'Taxonomy Slug', 'qi-addons-for-elementor' ),
						'group'      => $group,
						'dependency' => array(
							'show' => array(
								'additional_params' => array(
									'values'        => 'tax',
									'default_value' => '',
								),
							),
						),
					)
				);
				$this->set_option(
					array(
						'field_type'  => 'text',
						'name'        => 'tax__in',
						'title'       => esc_html__( 'Taxonomy IDs', 'qi-addons-for-elementor' ),
						'description' => esc_html__( 'Separate taxonomy IDs with commas', 'qi-addons-for-elementor' ),
						'group'       => $group,
						'dependency'  => array(
							'show' => array(
								'additional_params' => array(
									'values'        => 'tax',
									'default_value' => '',
								),
							),
						),
					)
				);
			}
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'author_slug',
					'title'      => esc_html__( 'Author Slug', 'qi-addons-for-elementor' ),
					'group'      => $group,
					'dependency' => array(
						'show' => array(
							'additional_params' => array(
								'values'        => 'author',
								'default_value' => '',
							),
						),
					),
				)
			);

			do_action( 'qi_addons_for_elementor_action_map_query_options_after_additional', $group );
		}
	}

	public function get_additional_query_args( $atts ) {
		$args = array();

		if ( ! empty( $atts['additional_params'] ) && 'id' === $atts['additional_params'] ) {
			$post_ids         = explode( ',', $atts['post_ids'] );
			$args['orderby']  = 'post__in';
			$args['post__in'] = $post_ids;
		}

		if ( ! empty( $atts['additional_params'] ) && 'tax' === $atts['additional_params'] ) {
			$taxonomy_values = array();

			$slug = isset( $atts['tax_slug'] ) ? $atts['tax_slug'] : '';
			$ids  = isset( $atts['tax__in'] ) ? $atts['tax__in'] : '';

			if ( ! empty( $ids ) && empty( $slug ) ) {
				$taxonomy_values['field'] = 'term_id';
				$taxonomy_values['terms'] = is_array( $ids ) ? array_map( 'intval', $ids ) : array_map( 'intval', explode( ',', str_replace( ' ', '', $ids ) ) );
			} elseif ( ! empty( $slug ) ) {
				$taxonomy_values['field'] = 'slug';
				$taxonomy_values['terms'] = $slug;
			}

			if ( ! empty( $atts['tax'] ) && ! empty( $taxonomy_values ) ) {
				$args['tax_query'] = array( array_merge( array( 'taxonomy' => $atts['tax'] ), $taxonomy_values ) );
			}
		}

		if ( ! empty( $atts['additional_params'] ) && 'author' === $atts['additional_params'] ) {
			$args['author_name'] = $atts['author_slug'];
		}

		$args = apply_filters( 'qi_addons_for_elementor_filter_additional_query_args', $args, $atts, $this->get_post_type() );

		return $args;
	}

	public function map_layout_options( $params = array() ) {
		$layouts                 = isset( $params['layouts'] ) ? $params['layouts'] : array();
		$exclude_option          = isset( $params['exclude_option'] ) ? $params['exclude_option'] : array();
		$default_value_title_tag = isset( $params['default_value_title_tag'] ) ? $params['default_value_title_tag'] : 'h5';

		$layout_visibility_field_type = sizeof( $layouts ) > 1 ? 'select' : 'hidden';

		$default_value = '';
		if ( ! empty( $layouts ) ) {
			reset( $layouts );
			$default_value = key( $layouts );
		}

		$this->set_option(
			array(
				'field_type'    => $layout_visibility_field_type,
				'name'          => 'layout',
				'title'         => esc_html__( 'Item Layout', 'qi-addons-for-elementor' ),
				'options'       => $layouts,
				'default_value' => $default_value,
				'group'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
			)
		);

		if ( empty( $exclude_option ) || ! in_array( 'title_tag', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => $default_value_title_tag,
					'group'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
		}

		if ( empty( $exclude_option ) || ! in_array( 'title_color', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-shortcode .qodef-e-title'   => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-shortcode .qodef-e-title a' => 'color: {{VALUE}};',
					),
				)
			);
		}

		if ( empty( $exclude_option ) || ! in_array( 'title_hover_color', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_hover_color',
					'title'      => esc_html__( 'Title Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-shortcode .qodef-e-title:hover'   => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-shortcode .qodef-e-title:hover a' => 'color: {{VALUE}};',
					),
				)
			);
		}

		if ( empty( $exclude_option ) || ! in_array( 'title_typography', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-shortcode .qodef-e-title',
				)
			);
		}
	}

	public function map_extra_options() {
		$extra_options = $this->get_extra_options();

		if ( ! empty( $extra_options ) ) {
			foreach ( $extra_options as $option ) {
				$this->set_option( $option );
			}
		}
	}

	public function register_list_scripts() {
		$scripts = $this->get_scripts();

		$scripts['swiper'] = array( 'registered' => true ); //enqueue swiper for admin
		$list_scripts      = apply_filters( 'qi_addons_for_elementor_filter_register_slider_shortcode_scripts', isset( $scripts ) ? $scripts : array() );

		$this->set_scripts( $list_scripts );
	}

	public function load_assets() {
		wp_enqueue_script( 'swiper' ); //enqueue swiper for frontend
		do_action( 'qi_addons_for_elementor_action_slider_shortcodes_load_assets', $this->get_atts() );
	}
}
