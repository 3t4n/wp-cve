<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_workflow_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_workflow_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Timeline';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_workflow_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Timeline extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_timeline_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_timeline_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/timeline' );
			$this->set_base( 'qi_addons_for_elementor_timeline' );
			$this->set_name( esc_html__( 'Timeline Showcase', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds timeline showcase element', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Showcase', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/timeline-showcase/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#timeline_showcase' );
			$this->set_necessary_styles( qi_addons_for_elementor_icon_necessary_styles() );

			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);

			$layouts_type     = apply_filters( 'qi_addons_for_elementor_filter_timeline_layouts_type', array() );
			$horizontal_types = $this->get_all_types( $layouts_type, 'horizontal' );
			$vertical_types   = $this->get_all_types( $layouts_type, 'vertical' );
			$options_map      = qi_addons_for_elementor_get_variations_options_map( $this->get_layouts() );

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'layout',
					'title'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
					'options'       => $this->get_layouts(),
					'default_value' => 'vertical-side',
					'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'layout',
					'title'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
					'options'       => $this->get_layouts(),
					'default_value' => 'horizontal-alternating',
					'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] ),
				)
			);

			$placeholder = get_option( 'qi_addons_for_elementor_placeholder_image' );

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'image' => $placeholder,
							'title' => esc_html__( 'Example Title 1', 'qi-addons-for-elementor' ),
							'date'  => '1980',
							'text'  => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
						array(
							'image' => $placeholder,
							'title' => esc_html__( 'Example Title 2', 'qi-addons-for-elementor' ),
							'date'  => '1983',
							'text'  => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
						array(
							'image' => $placeholder,
							'title' => esc_html__( 'Example Title 3', 'qi-addons-for-elementor' ),
							'date'  => '1989',
							'text'  => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
					),
					'items'         => array(
						array(
							'field_type' => 'icons',
							'name'       => 'icon',
							'title'      => esc_html__( 'Point Icon', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'image',
							'name'          => 'image',
							'title'         => esc_html__( 'Image', 'qi-addons-for-elementor' ),
							'default_value' => $placeholder,
						),
						array(
							'field_type'    => 'text',
							'name'          => 'title',
							'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Example Title', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'text',
							'name'          => 'date',
							'title'         => esc_html__( 'Date', 'qi-addons-for-elementor' ),
							'dynamic'       => false,
							'default_value' => '2005',
						),
						array(
							'field_type'    => 'textarea',
							'name'          => 'text',
							'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
							'default_value' => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
						array(
							'field_type' => 'link',
							'name'       => 'link',
							'title'      => esc_html__( 'Link', 'qi-addons-for-elementor' ),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'appear_animation',
					'title'      => esc_html__( 'Enable Appear Animation', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
					'group'      => esc_html__( 'Appear Animation', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'image_proportion',
					'default_value' => 'full',
					'title'         => esc_html__( 'Image Proportions', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'list_image_dimension', false, array( 'custom' ) ),
					'group'         => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag', false ),
					'default_value' => 'h4',
					'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_hover_color',
					'title'      => esc_html__( 'Title Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title:hover' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-title',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_margin_bottom',
					'title'      => esc_html__( 'Title Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'  => 'dimensions',
					'name'        => 'title_padding',
					'title'       => esc_html__( 'Title Padding', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Top/bottom and left/right padding will change sides for vertical separated type on every other item', 'qi-addons-for-elementor' ),
					'size_units'  => array( 'px', '%', 'em' ),
					'responsive'  => true,
					'selectors'   => array(
						'{{WRAPPER}} .qodef-e-title'                                       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-reverse-padding .qodef-obverse .qodef-e-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-reverse-padding .qodef-reverse .qodef-e-title' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
					),
					'group'       => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'date_color',
					'title'      => esc_html__( 'Date Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-date' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'date_typography',
					'title'      => esc_html__( 'Date Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-date',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'date_margin_bottom',
					'title'      => esc_html__( 'Date Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -100,
							'max' => 100,
						),
						'%'  => array(
							'min' => -100,
							'max' => 100,
						),
						'em' => array(
							'min' => -10,
							'max' => 10,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-date' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-text' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-text',
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'text_margin_bottom',
					'title'      => esc_html__( 'Text Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'  => 'dimensions',
					'name'        => 'text_padding',
					'title'       => esc_html__( 'Text Padding', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Left/right padding will change sides for vertical separated type on every other item', 'qi-addons-for-elementor' ),
					'size_units'  => array( 'px', '%', 'em' ),
					'responsive'  => true,
					'selectors'   => array(
						'{{WRAPPER}} .qodef-e-text'                                       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-reverse-padding .qodef-obverse .qodef-e-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-reverse-padding .qodef-reverse .qodef-e-text' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
					),
					'group'       => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'slider',
					'name'          => 'space_between_items',
					'title'         => esc_html__( 'Space Between Items', 'qi-addons-for-elementor' ),
					'size_units'    => array( 'px', 'em' ),
					'range'         => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive'    => true,
					'selectors'     => array(
						'{{WRAPPER}} .qodef-timeline--vertical .qodef-e-item:not(:last-child)' => 'padding-bottom: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-timeline--horizontal .qodef-e-item'                => 'padding: 0 calc({{SIZE}}{{UNIT}}/2);',
					),
					'default_value' => array(
						'unit' => 'px',
						'size' => '30',
					),
					'group'         => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'image_border_radius',
					'title'      => esc_html__( 'Image Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};;',
					),
					'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'columns',
					'title'         => esc_html__( 'Number of Columns', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_number', true ),
					'default_value' => '3',
					'dependency'    => array(
						'show' => array(
							'layout' => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'         => esc_html__( 'Columns', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'columns_responsive',
					'title'         => esc_html__( 'Columns Responsive', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'columns_responsive' ),
					'default_value' => 'predefined',
					'dependency'    => array(
						'show' => array(
							'layout' => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'         => esc_html__( 'Columns', 'qi-addons-for-elementor' ),
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
						'show'     => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => '3',
							),
							'layout'             => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'         => esc_html__( 'Columns', 'qi-addons-for-elementor' ),
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
						'show'     => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => '3',
							),
							'layout'             => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'         => esc_html__( 'Columns', 'qi-addons-for-elementor' ),
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
						'show'     => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => '3',
							),
							'layout'             => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'         => esc_html__( 'Columns', 'qi-addons-for-elementor' ),
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
						'show'     => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => '3',
							),
							'layout'             => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'         => esc_html__( 'Columns', 'qi-addons-for-elementor' ),
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
						'show'     => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => '3',
							),
							'layout'             => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'         => esc_html__( 'Columns', 'qi-addons-for-elementor' ),
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
						'show'     => array(
							'columns_responsive' => array(
								'values'        => 'custom',
								'default_value' => '3',
							),
							'layout'             => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'         => esc_html__( 'Columns', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'line_type',
					'title'      => esc_html__( 'Line Type', 'qi-addons-for-elementor' ),
					'options'    => array(
						'outside' => esc_html__( 'Outside', 'qi-addons-for-elementor' ),
						'inside'  => esc_html__( 'Inside', 'qi-addons-for-elementor' ),
					),
					'dependency' => array(
						'show' => array(
							'layout' => array(
								'values'        => $vertical_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'      => esc_html__( 'Line & Point Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'line_color',
					'title'      => esc_html__( 'Line Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-line' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-point--diamond .qodef-e-point-holder:before' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-point--diamond .qodef-e-point-holder:after' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Line & Point Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'line_thickness',
					'title'      => esc_html__( 'Line Thickness', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-timeline--vertical .qodef-e-line-holder'                                => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-timeline--vertical.qodef-point--diamond .qodef-e-point-holder:before'   => 'height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-timeline--vertical.qodef-point--diamond .qodef-e-point-holder:after'    => 'height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-timeline--horizontal .qodef-e-line-holder'                              => 'height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-timeline--horizontal.qodef-point--diamond .qodef-e-point-holder:before' => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-timeline--horizontal.qodef-point--diamond .qodef-e-point-holder:after'  => 'width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Line & Point Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'point_type',
					'title'      => esc_html__( 'Point Type', 'qi-addons-for-elementor' ),
					'options'    => array(
						'standard' => esc_html__( 'Standard', 'qi-addons-for-elementor' ),
						'diamond'  => esc_html__( 'Diamond', 'qi-addons-for-elementor' ),
					),
					'group'      => esc_html__( 'Line & Point Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'point_position',
					'title'      => esc_html__( 'Point Position', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-timeline--vertical .qodef-e-point-holder' => 'top: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-timeline--vertical.qodef-line--inside .qodef-e-line-holder' => 'top: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'layout' => array(
								'values'        => $vertical_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'      => esc_html__( 'Line & Point Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'point_diamond_lines_size',
					'title'      => esc_html__( 'Diamond Lines Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-timeline--vertical.qodef-point--diamond .qodef-e-point-holder:before'   => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-timeline--vertical.qodef-point--diamond .qodef-e-point-holder:after'    => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-timeline--horizontal.qodef-point--diamond .qodef-e-point-holder:before' => 'height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-timeline--horizontal.qodef-point--diamond .qodef-e-point-holder:after'  => 'height: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'point_type' => array(
								'values'        => 'diamond',
								'default_value' => 'standard',
							),
						),
					),
					'group'      => esc_html__( 'Line & Point Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'point_size',
					'title'      => esc_html__( 'Point Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-point-holder' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Line & Point Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'point_color',
					'title'      => esc_html__( 'Point Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-point' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Line & Point Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'point_border_radius',
					'title'      => esc_html__( 'Point Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-point' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Line & Point Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_size',
					'title'      => esc_html__( 'Icon Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-point .qodef-e-icon-holder' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Line & Point Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_color',
					'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-point .qodef-e-icon-holder' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Line & Point Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'slider_navigation_arrow_prev',
					'title'         => esc_html__( 'Navigation Arrow Previous', 'qi-addons-for-elementor' ),
					'default_value' => array(),
					'dependency'    => array(
						'show' => array(
							'layout' => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'         => esc_html__( 'Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'slider_navigation_arrow_next',
					'title'         => esc_html__( 'Navigation Arrow Next', 'qi-addons-for-elementor' ),
					'default_value' => array(),
					'dependency'    => array(
						'show' => array(
							'layout' => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'         => esc_html__( 'Navigation Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'slider_navigation_arrows_color',
					'title'      => esc_html__( 'Navigation Arrow Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-nav-next' => 'color: {{VALUE}}',
						'{{WRAPPER}} .qodef-nav-prev' => 'color: {{VALUE}}',
					),
					'dependency' => array(
						'show' => array(
							'layout' => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'      => esc_html__( 'Navigation Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-nav-prev' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-nav-next' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'layout' => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'      => esc_html__( 'Navigation Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'slider_navigation_arrows_offset',
					'title'      => esc_html__( 'Navigation Arrow Offset', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 300,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-nav-prev' => 'left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-nav-next' => 'right: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'layout' => array(
								'values'        => $horizontal_types,
								'default_value' => 'vertical-side',
							),
						),
					),
					'group'      => esc_html__( 'Navigation Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->map_extra_options();
		}

		public function load_assets() {
			parent::load_assets();

			qi_addons_for_elementor_icon_load_assets();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['type'] = apply_filters( 'qi_addons_for_elementor_filter_timeline_layouts_type', array() )[ $atts['layout'] ];

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['holder_data']    = json_encode( $this->get_holder_data( $atts ) );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );
			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/timeline', 'templates/timeline', $atts['type'], $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-timeline';
			$holder_classes[] = ! empty( $atts['type'] ) ? 'qodef-timeline--' . $atts['type'] : '';
			$holder_classes[] = ( 'horizontal' === $atts['type'] ) ? 'qodef-layout--columns' : '';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-timeline-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['line_type'] ) ? 'qodef-line--' . $atts['line_type'] : '';
			$holder_classes[] = ! empty( $atts['point_type'] ) ? 'qodef-point--' . $atts['point_type'] : '';
			$holder_classes[] = 'yes' === $atts['appear_animation'] ? 'qodef-qi--has-appear ' : '';

			$reverse_padding_layout = apply_filters( 'qi_addons_for_elementor_filter_timeline_reverse_padding_layouts', array() );
			$holder_classes[]       = in_array( $atts['layout'], $reverse_padding_layout, true ) ? 'qodef-reverse-padding' : '';

			return implode( ' ', $holder_classes );
		}

		public function get_item_classes( $atts ) {
			$item_classes = $this->init_item_classes();

			$item_classes[] = 'qodef-e-item';
			$item_classes[] = ( 'horizontal' === $atts['type'] ) ? 'qodef-grid-item' : '';

			return implode( ' ', $item_classes );
		}

		private function get_holder_data( $atts ) {
			$data = array();

			$data['colNum'] = ! empty( $atts['columns'] ) ? $atts['columns'] : '3';

			if ( ! empty( $atts['columns_responsive'] ) && 'custom' === $atts['columns_responsive'] ) {
				$data['colNum1440'] = ! empty( $atts['columns_1440'] ) ? $atts['columns_1440'] : $atts['columns'];
				$data['colNum1366'] = ! empty( $atts['columns_1366'] ) ? $atts['columns_1366'] : $atts['columns'];
				$data['colNum1024'] = ! empty( $atts['columns_1024'] ) ? $atts['columns_1024'] : $atts['columns'];
				$data['colNum768']  = ! empty( $atts['columns_768'] ) ? $atts['columns_768'] : $atts['columns'];
				$data['colNum680']  = ! empty( $atts['columns_680'] ) ? $atts['columns_680'] : $atts['columns'];
				$data['colNum480']  = ! empty( $atts['columns_480'] ) ? $atts['columns_480'] : $atts['columns'];
			} else {
				$data['colNum1440'] = $data['colNum'];
				$data['colNum1366'] = $data['colNum'];
				$data['colNum1024'] = $data['colNum'];
				$data['colNum768']  = $data['colNum'];

				if ( 6 <= $data['colNum'] ) {
					$data['colNum1440'] = '5';
				}
				if ( 5 <= $data['colNum'] ) {
					$data['colNum1366'] = '4';
				}
				if ( 4 <= $data['colNum'] ) {
					$data['colNum1024'] = '3';
				}
				if ( 3 <= $data['colNum'] ) {
					$data['colNum768'] = '2';
				}
				$data['colNum680'] = '1';
				$data['colNum480'] = '1';
			}

			return $data;
		}

		private function get_all_types( $layouts, $type = 'horizontal' ) {
			$layout_types = array();

			foreach ( $layouts as $key => $layout ) {
				if ( $type === $layout ) {
					$layout_types[] = $key;
				}
			}

			return $layout_types;
		}
	}
}
