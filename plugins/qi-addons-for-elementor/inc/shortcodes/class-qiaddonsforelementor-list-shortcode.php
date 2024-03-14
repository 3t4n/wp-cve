<?php

abstract class QiAddonsForElementor_List_Shortcode extends QiAddonsForElementor_Framework_Shortcode {
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

	public function map_list_options( $params = array() ) {
		$group               = isset( $params['group'] ) ? $params['group'] : null;
		$exclude_option      = isset( $params['exclude_option'] ) ? $params['exclude_option'] : array();
		$include_option      = isset( $params['include_option'] ) ? $params['include_option'] : array();
		$exclude_proportions = isset( $params['exclude_proportions'] ) ? $params['exclude_proportions'] : array();
		$exclude_behavior    = isset( $params['exclude_behavior'] ) ? $params['exclude_behavior'] : array();
		$exclude_columns     = isset( $params['exclude_columns'] ) ? $params['exclude_columns'] : array();
		$include_columns     = isset( $params['include_columns'] ) ? $params['include_columns'] : array();

		if ( empty( $exclude_behavior ) || ! in_array( 'behavior', $exclude_behavior, true ) ) {
			$field_type    = 'select';
			$default_value = 'columns';
			$list_behavior = qi_addons_for_elementor_get_select_type_options_pool( 'list_behavior', false, $exclude_behavior );
			if ( 1 >= count( $list_behavior ) ) {
				$field_type    = 'hidden';
				$default_value = array_keys( $list_behavior )[0];
			}

			$this->set_option(
				array(
					'field_type'    => $field_type,
					'name'          => 'behavior',
					'title'         => esc_html__( 'List Appearance', 'qi-addons-for-elementor' ),
					'options'       => $list_behavior,
					'default_value' => $default_value,
					'group'         => $group,
				)
			);
		}
		if ( ( empty( $exclude_behavior ) || ! in_array( 'masonry', $exclude_behavior, true ) ) ) {

			$field_type    = 'select';
			$default_value = 'original';

			$masonry_images_proportion = qi_addons_for_elementor_get_select_type_options_pool( 'masonry_images_proportion', false, $exclude_proportions );
			if ( 1 >= count( $masonry_images_proportion ) ) {
				$field_type    = 'hidden';
				$default_value = array_keys( $masonry_images_proportion )[0];
			}

			$this->set_option(
				array(
					'field_type'    => $field_type,
					'name'          => 'masonry_images_proportion',
					'title'         => esc_html__( 'Image Proportions', 'qi-addons-for-elementor' ),
					'options'       => $masonry_images_proportion,
					'default_value' => $default_value,
					'group'         => $group,
					'dependency'    => array(
						'show' => array(
							'behavior' => array(
								'values'        => 'masonry',
								'default_value' => 'columns',
							),
						),
					),
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
					'dependency'    => array(
						'show' => array(
							'behavior' => array(
								'values'        => array( '', 'columns', 'slider' ),
								'default_value' => 'columns',
							),
						),
					),
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
					'field_type'    => 'select',
					'name'          => 'columns',
					'title'         => esc_html__( 'Number of Columns', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_number', true, $exclude_columns, $include_columns ),
					'default_value' => '3',
					'group'         => $group,
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
						'show' => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => 'predefined',
							),
						),
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
						'show' => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => 'predefined',
							),
						),
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
						'show' => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => 'predefined',
							),
						),
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
						'show' => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => 'predefined',
							),
						),
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
						'show' => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => 'predefined',
							),
						),
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
						'show' => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => 'predefined',
							),
						),
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
						'show' => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => 'predefined',
							),
						),
					),
					'group'         => $group,
				)
			);
		}

		if ( empty( $exclude_option ) || ! in_array( 'space', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'space',
					'title'      => esc_html__( 'Space Between Items', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'vw', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-grid > .qodef-grid-inner'                                                  => 'gap: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-qi-grid.qodef-borders--between > .qodef-grid-inner > .qodef-grid-item:before' => 'bottom: calc( -{{SIZE}}{{UNIT}}/2 );',
						'{{WRAPPER}} .qodef-qi-grid.qodef-borders--between > .qodef-grid-inner > .qodef-grid-item:after'  => 'right: calc( -{{SIZE}}{{UNIT}}/2 );',
						'{{WRAPPER}} .qodef-qi-grid.qodef-borders--all > .qodef-grid-inner > .qodef-grid-item'            => 'padding: {{SIZE}}{{UNIT}};',
					),
					'group'      => $group,
				)
			);
		}

		if ( empty( $exclude_option ) || ! in_array( 'enable_pagination', $exclude_option, true ) ) {
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_pagination',
					'title'         => esc_html__( 'Enable Pagination', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'default_value' => 'no',
					'group'         => $group,
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'pagination_typography',
					'title'      => esc_html__( 'Pagination Typography', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'selector'   => '{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers',
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'pagination_style_tabs',
					'title'      => esc_html__( 'Pagination Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'pagination_style_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'pagination_color',
					'title'      => esc_html__( 'Color', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'pagination_background_color',
					'title'      => esc_html__( 'Background Color', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'pagination_style_tab_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'pagination_style_tab_hover',
					'title'      => esc_html__( 'Active/Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'pagination_hover_color',
					'title'      => esc_html__( 'Color', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers.current' => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard a.page-numbers:hover'   => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'pagination_hover_background_color',
					'title'      => esc_html__( 'Background Color', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers.current' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers:hover'   => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'pagination_style_tab_hover_end',
					'title'      => esc_html__( 'Active/Hover End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tabs',
					'name'       => 'pagination_style_tabs_end',
					'title'      => esc_html__( 'Pagination End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_pagination_style_end',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'pagination_arrow_prev',
					'title'         => esc_html__( 'Pagination Arrow Previous', 'qi-addons-for-elementor' ),
					'dependency'    => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'default_value' => array(),
					'group'         => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'pagination_arrow_next',
					'title'         => esc_html__( 'Pagination Arrow Next', 'qi-addons-for-elementor' ),
					'dependency'    => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'default_value' => array(),
					'group'         => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'pagination_arrows_size',
					'title'      => esc_html__( 'Pagination Arrows Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'dependency' => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers.next' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers.prev' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'pagination_border_radius',
					'title'      => esc_html__( 'Pagination Item Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'dependency' => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'pagination_width',
					'title'      => esc_html__( 'Pagination Item Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'dependency' => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers' => 'width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'pagination_height',
					'title'      => esc_html__( 'Pagination Item Height', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'dependency' => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers' => 'height: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'pagination_spacing',
					'title'      => esc_html__( 'Space Between Pagination Items', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'dependency' => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-addons-m-pagination.qodef--standard .page-numbers' => 'margin: 0 calc({{SIZE}}{{UNIT}}/2);',
					),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'pagination_margin_top',
					'title'      => esc_html__( 'Pagination Margin Top', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'hide' => array(
							'enable_pagination' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => - 150,
							'max' => 300,
						),
						'%'  => array(
							'min' => - 100,
							'max' => 300,
						),
						'em' => array(
							'min' => - 30,
							'max' => 30,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-addons-m-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Pagination Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		if ( empty( $exclude_behavior ) || ! in_array( 'slider', $exclude_behavior, true ) ) {
			$params['dependency'] = array(
				'show' => array(
					'behavior' => array(
						'values'        => 'slider',
						'default_value' => 'columns',
					),
				),
			);
			$this->map_slider_options( $params );
		}

		// Include Options

		if ( ! empty( $include_option ) || in_array( 'enable_zigzag', $include_option, true ) ) {
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_zigzag',
					'title'         => esc_html__( 'Enable Zigzag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'default_value' => 'no',
					'group'         => $group,
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'slider',
					'name'          => 'zigzag_amount',
					'title'         => esc_html__( 'Zigzag Amount', 'qi-addons-for-elementor' ),
					'size_units'    => array( 'px', '%' ),
					'responsive'    => true,
					'default_value' => array(
						'size' => 30,
						'unit' => 'px',
					),
					'dependency'    => array(
						'hide' => array(
							'enable_zigzag' => array(
								'values'        => 'no',
								'default_value' => 'no',
							),
						),
					),
					'selectors'     => array(
						'{{WRAPPER}} .qodef-grid-inner >.qodef-e:nth-of-type(even) > *' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'         => $group,
				)
			);
		}

		do_action( 'qi_addons_for_elementor_action_map_additional_options', $this, $group, $include_option );
	}

	public function map_slider_options( $params = array() ) {
		$group      = isset( $params['group'] ) ? $params['group'] : null;
		$dependency = isset( $params['dependency'] ) ? $params['dependency'] : array();

		$this->set_option(
			array(
				'field_type' => 'select',
				'name'       => 'slider_loop',
				'title'      => esc_html__( 'Enable Slider Loop', 'qi-addons-for-elementor' ),
				'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
				'dependency' => $dependency,
				'group'      => $group,
			)
		);
		$this->set_option(
			array(
				'field_type' => 'select',
				'name'       => 'slider_autoplay',
				'title'      => esc_html__( 'Enable Slider Autoplay', 'qi-addons-for-elementor' ),
				'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
				'dependency' => $dependency,
				'group'      => $group,
			)
		);
		$this->set_option(
			array(
				'field_type'  => 'text',
				'name'        => 'slider_speed',
				'title'       => esc_html__( 'Slide Duration', 'qi-addons-for-elementor' ),
				'description' => esc_html__( 'Default value is 5000 (ms)', 'qi-addons-for-elementor' ),
				'dependency'  => $dependency,
				'group'       => $group,
			)
		);
		$this->set_option(
			array(
				'field_type'  => 'text',
				'name'        => 'slider_speed_animation',
				'title'       => esc_html__( 'Slide Animation Duration', 'qi-addons-for-elementor' ),
				'description' => esc_html__( 'Speed of slide animation in milliseconds. Default value is 800.', 'qi-addons-for-elementor' ),
				'dependency'  => $dependency,
				'group'       => $group,
			)
		);
		$this->set_option(
			array(
				'field_type' => 'select',
				'name'       => 'slider_navigation',
				'title'      => esc_html__( 'Enable Slider Navigation', 'qi-addons-for-elementor' ),
				'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
				'dependency' => $dependency,
				'group'      => $group,
			)
		);
		$this->set_option(
			array(
				'field_type' => 'select',
				'name'       => 'slider_pagination',
				'title'      => esc_html__( 'Enable Slider Pagination', 'qi-addons-for-elementor' ),
				'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
				'dependency' => $dependency,
				'group'      => $group,
			)
		);
	}

	public function get_list_classes( $atts ) {
		$holder_classes = array();

		$holder_classes[] = 'qodef-qi-grid';
		$holder_classes[] = ! empty( $atts['behavior'] ) ? 'qodef-layout--qi-' . $atts['behavior'] : 'qodef-layout--qi-columns';
		$holder_classes[] = ! empty( $atts['behavior'] ) && 'masonry' === $atts['behavior'] && ! empty( $atts['masonry_images_proportion'] ) && 'fixed' === $atts['masonry_images_proportion'] ? 'qodef-items--fixed' : '';
		$holder_classes[] = ! empty( $atts['columns'] ) ? 'qodef-col-num--' . $atts['columns'] : '';
		$holder_classes[] = ! empty( $atts['borders'] ) ? 'qodef-borders--' . $atts['borders'] : '';

		$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-item-layout--' . $atts['layout'] : '';

		if ( ! empty( $atts['columns_responsive'] ) && 'custom' === $atts['columns_responsive'] ) {
			$holder_classes[] = 'qodef-responsive--custom';
			$holder_classes[] = ! empty( $atts['columns_1680'] ) ? 'qodef-col-num--1680--' . $atts['columns_1680'] : 'qodef-col-num--1680--' . $atts['columns'];
			$holder_classes[] = ! empty( $atts['columns_1440'] ) ? 'qodef-col-num--1440--' . $atts['columns_1440'] : 'qodef-col-num--1440--' . $atts['columns'];
			$holder_classes[] = ! empty( $atts['columns_1366'] ) ? 'qodef-col-num--1366--' . $atts['columns_1366'] : 'qodef-col-num--1366--' . $atts['columns'];
			$holder_classes[] = ! empty( $atts['columns_1024'] ) ? 'qodef-col-num--1024--' . $atts['columns_1024'] : 'qodef-col-num--1024--' . $atts['columns'];
			$holder_classes[] = ! empty( $atts['columns_768'] ) ? 'qodef-col-num--768--' . $atts['columns_768'] : 'qodef-col-num--768--' . $atts['columns'];
			$holder_classes[] = ! empty( $atts['columns_680'] ) ? 'qodef-col-num--680--' . $atts['columns_680'] : 'qodef-col-num--680--' . $atts['columns'];
			$holder_classes[] = ! empty( $atts['columns_480'] ) ? 'qodef-col-num--480--' . $atts['columns_480'] : 'qodef-col-num--480--' . $atts['columns'];
		} else {
			$holder_classes[] = 'qodef-responsive--predefined';
		}

		$holder_classes = apply_filters( 'qi_addons_for_elementor_filter_list_classes', $holder_classes, $atts );

		return $holder_classes;
	}

	public function get_list_item_classes( $atts ) {
		$item_classes = array();

		$item_classes[] = ! empty( $atts['behavior'] ) && 'slider' === $atts['behavior'] ? 'swiper-slide' : 'qodef-grid-item';

		if ( ! empty( $atts['image_dimension'] ) ) {
			$item_classes[] = $atts['image_dimension']['class'];
		}

		return $item_classes;
	}

	public function get_list_item_image_dimension( $atts ) {
		$image_dimension = array();

		if ( ! empty( $atts['behavior'] ) && 'masonry' === $atts['behavior'] && ! empty( $atts['masonry_images_proportion'] ) && 'fixed' === $atts['masonry_images_proportion'] ) {
			$masonry_image_dimension_name = 'qodef_masonry_image_dimension_' . str_replace( '-', '_', $atts['post_type'] );
			$image_dimension              = qi_addons_for_elementor_get_custom_image_size_meta( 'meta-box', $masonry_image_dimension_name, get_the_ID() );
		}

		if ( ! empty( $atts['behavior'] ) && in_array( $atts['behavior'], array( 'columns', 'slider' ), true ) ) {
			$image_dimension = array(
				'size'  => $atts['images_proportion'],
				'class' => qi_addons_for_elementor_get_custom_image_size_class_name( $atts['images_proportion'] ),
			);
		}

		return $image_dimension;
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
							array(
								' ',
								' ',
							),
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
		$scripts      = $this->get_scripts();
		$list_scripts = apply_filters( 'qi_addons_for_elementor_filter_register_list_shortcode_scripts', isset( $scripts ) ? $scripts : array() );

		$this->set_scripts( $list_scripts );
	}

	public function load_assets() {
		do_action( 'qi_addons_for_elementor_action_list_shortcodes_load_assets', $this->get_atts() );
	}
}
