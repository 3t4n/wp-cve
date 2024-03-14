<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_business_hours_list_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_business_hours_list_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Business_Hours_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_business_hours_list_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Business_Hours_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_business_hours_layouts', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/business-hours' );
			$this->set_base( 'qi_addons_for_elementor_business_hours' );
			$this->set_name( esc_html__( 'Working Hours', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays working hours', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Business', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/working-hours/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#working_hours' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$options_map = qi_addons_for_elementor_get_variations_options_map( $this->get_layouts() );
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'layout',
					'title'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
					'options'       => $this->get_layouts(),
					'default_value' => 'standard',
					'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'subtitle',
					'title'         => esc_html__( 'Subtitle', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text( 'subtitle' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'title',
					'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text( 'title' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'html',
					'name'          => 'text',
					'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
					'default_value' => qi_addons_for_elementor_get_example_text(),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_responsive',
					'title'         => esc_html__( 'Enable Column Responsive Layout', 'qi-addons-for-elementor' ),
					'options'       => array(
						'no'  => esc_html__( 'Never', 'qi-addons-for-elementor' ),
						'768' => esc_html__( 'Under 768px', 'qi-addons-for-elementor' ),
						'680' => esc_html__( 'Under 680px', 'qi-addons-for-elementor' ),
						'480' => esc_html__( 'Under 480px', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'no',
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_separator',
					'title'         => esc_html__( 'Enable Separator', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'default_value' => 'no',
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Menu Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_day_text'   => esc_html__( 'Monday', 'qi-addons-for-elementor' ),
							'item_hours_text' => esc_html__( '09:00 - 17:00', 'qi-addons-for-elementor' ),
						),
						array(
							'item_day_text'   => esc_html__( 'Tuesday', 'qi-addons-for-elementor' ),
							'item_hours_text' => esc_html__( '09:00 - 17:00', 'qi-addons-for-elementor' ),
						),
						array(
							'item_day_text'   => esc_html__( 'Wednesday', 'qi-addons-for-elementor' ),
							'item_hours_text' => esc_html__( '09:00 - 17:00', 'qi-addons-for-elementor' ),
						),
						array(
							'item_day_text'   => esc_html__( 'Thursday', 'qi-addons-for-elementor' ),
							'item_hours_text' => esc_html__( '09:00 - 17:00', 'qi-addons-for-elementor' ),
						),
						array(
							'item_day_text'   => esc_html__( 'Friday', 'qi-addons-for-elementor' ),
							'item_hours_text' => esc_html__( '09:00 - 17:00', 'qi-addons-for-elementor' ),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'select',
							'name'          => 'enable_item_icon',
							'title'         => esc_html__( 'Enable Icon', 'qi-addons-for-elementor' ),
							'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
							'default_value' => 'no',
						),
						array(
							'field_type' => 'icons',
							'name'       => 'item_icon',
							'title'      => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
							'dependency' => array(
								'show' => array(
									'enable_item_icon' => array(
										'values'        => 'yes',
										'default_value' => 'no',
									),
								),
							),
						),
						array(
							'field_type'    => 'text',
							'name'          => 'item_day_text',
							'title'         => esc_html__( 'Name of the Day', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Saturday', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'text',
							'name'          => 'item_hours_text',
							'title'         => esc_html__( 'Working Hours', 'qi-addons-for-elementor' ),
							'dynamic'       => false,
							'default_value' => esc_html__( '09:00 - 17:00', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_color',
							'title'      => esc_html__( 'Item Color', 'qi-addons-for-elementor' ),
							'selectors'  => array(
								'{{WRAPPER}} .qodef-qi-business-hours {{CURRENT_ITEM}} .qodef-e-day'   => 'color: {{VALUE}};',
								'{{WRAPPER}} .qodef-qi-business-hours {{CURRENT_ITEM}} .qodef-e-hours' => 'color: {{VALUE}};',
								'{{WRAPPER}} .qodef-qi-business-hours {{CURRENT_ITEM}} .qodef-e-icon'  => 'color: {{VALUE}};',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_color',
					'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-e-icon' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
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
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-e-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_icon_day',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'day_tag',
					'title'         => esc_html__( 'Day Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h5',
					'group'         => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'day_color',
					'title'      => esc_html__( 'Day Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-e-day' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'day_typography',
					'title'      => esc_html__( 'Day Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-business-hours .qodef-e-day',
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_day_hours',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'hours_color',
					'title'      => esc_html__( 'Hours Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-e-hours' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'hours_typography',
					'title'      => esc_html__( 'Hours Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-business-hours .qodef-e-hours',
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_hours_line',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'line_type',
					'title'         => esc_html__( 'Line Type', 'qi-addons-for-elementor' ),
					'options'       => array(
						'between' => esc_html__( 'Between', 'qi-addons-for-elementor' ),
						'below'   => esc_html__( 'Below', 'qi-addons-for-elementor' ),
						'none'    => esc_html__( 'None', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'between',
					'group'         => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'line_border_between',
					'title'      => esc_html__( 'Line Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-business-hours.qodef-line-type--between .qodef-e-line',
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'line_type' => array(
								'values'        => 'between',
								'default_value' => 'between',
							),
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type'      => 'border',
					'name'            => 'line_border_below',
					'title'           => esc_html__( 'Line Border', 'qi-addons-for-elementor' ),
					'selector'        => '{{WRAPPER}} .qodef-qi-business-hours.qodef-line-type--below .qodef-e-item',
					'group'           => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
					'dependency'      => array(
						'show' => array(
							'line_type' => array(
								'values'        => 'below',
								'default_value' => 'between',
							),
						),
					),
					'exclude_options' => array( 'width' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'line_border_below_width',
					'title'      => esc_html__( 'Line Border Width', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours.qodef-line-type--below .qodef-e-item' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Item Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'background',
					'name'       => 'holder_background',
					'title'      => esc_html__( 'Holder Background', 'qi-addons-for-elementor' ),
					'types'      => array( 'classic', 'gradient', 'video' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-business-hours',
					'group'      => esc_html__( 'General Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'business_border',
					'title'      => esc_html__( 'Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-business-hours',
					'group'      => esc_html__( 'General Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'title_tag',
					'title'         => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h2',
					'group'         => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-m-title' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-business-hours .qodef-m-title',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_title_subtitle',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'subtitle_tag',
					'title'         => esc_html__( 'Subtitle Tag', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h5',
					'group'         => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'subtitle_color',
					'title'      => esc_html__( 'Subtitle Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-m-subtitle' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'subtitle_typography',
					'title'      => esc_html__( 'Subtitle Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-business-hours .qodef-m-subtitle',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_subtitle_text',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'text_color',
					'title'      => esc_html__( 'Text Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-m-text' => 'color: {{VALUE}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'text_typography',
					'title'      => esc_html__( 'Text Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-business-hours .qodef-m-text',
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'text_underline',
					'title'         => esc_html__( 'Enable Text Underline', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'default_value' => 'yes',
					'group'         => esc_html__( 'Text Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->import_shortcode_options(
				array(
					'shortcode_base'    => 'qi_addons_for_elementor_separator',
					'exclude'           => array( 'custom_class' ),
					'additional_params' => array(
						'nested_group' => esc_html__( 'Separator', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'space_between',
					'title'      => esc_html__( 'Space Between Items', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-e-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'items_padding',
					'title'      => esc_html__( 'Items Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-e-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_items_holder',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'holder_padding',
					'title'      => esc_html__( 'Holder Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'divider',
					'name'       => 'item_divider_holder_text',
					'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'subtitle_margin',
					'title'      => esc_html__( 'Subtitle Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-m-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'title_margin',
					'title'      => esc_html__( 'Title Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-m-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'text_margin',
					'title'      => esc_html__( 'Text Margin Top', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-business-hours .qodef-m-text' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes']   = $this->get_holder_classes( $atts );
			$atts['items']            = $this->parse_repeater_items( $atts['children'] );
			$atts['separator_params'] = $this->generate_separator_params( $atts );

			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/business-hours', 'templates/holder', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-business-hours';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['line_type'] ) ? 'qodef-line-type--' . $atts['line_type'] : '';
			$holder_classes[] = ! empty( $atts['enable_responsive'] ) ? 'qodef-resposive--' . $atts['enable_responsive'] : '';
			$holder_classes[] = 'yes' === $atts['text_underline'] ? 'qodef-text-underline' : '';

			return implode( ' ', $holder_classes );
		}

		private function generate_separator_params( $atts ) {
			$params = array();

			if ( 'yes' === $atts['enable_separator'] ) {
				$params = $this->populate_imported_shortcode_atts(
					array(
						'shortcode_base' => 'qi_addons_for_elementor_separator',
						'exclude'        => array( 'custom_class' ),
						'atts'           => $atts,
					)
				);
			}

			return $params;
		}
	}
}
