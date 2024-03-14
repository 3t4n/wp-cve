<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_pricing_table_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_pricing_table_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Pricing_Table_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_pricing_table_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Pricing_Table_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_pricing_table_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_pricing_table_extra_options', array(), $this ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/pricing-table' );
			$this->set_base( 'qi_addons_for_elementor_pricing_table' );
			$this->set_name( esc_html__( 'Pricing Table', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that adds pricing table element', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Business', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/pricing-table/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#pricing_table' );
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
					'field_type'    => 'select',
					'name'          => 'featured_table',
					'title'         => esc_html__( 'Featured Table', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes' ),
					'default_value' => 'no',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'choose',
					'name'       => 'alignment',
					'title'      => esc_html__( 'Alignment', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false, array( 'right' ) ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-pricing-table' => 'text-align: {{VALUE}};',
						'{{WRAPPER}} .qodef-period--side:not(.qodef-layout--simple) .qodef-m-price' => 'justify-content: {{VALUE}};',
						'{{WRAPPER}} .qodef-period--bottom:not(.qodef-layout--simple) .qodef-m-price' => 'align-items: {{VALUE}};',
					),
					'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'table_background_color',
					'title'      => esc_html__( 'Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-pricing-table' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'border',
					'name'       => 'table_border',
					'title'      => esc_html__( 'Border', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-pricing-table',
					'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'table_border_radius',
					'title'      => esc_html__( 'Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-pricing-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'box_shadow',
					'name'       => 'table_box_shadow',
					'title'      => esc_html__( 'Box Shadow', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-pricing-table',
					'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
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
					'field_type' => 'select',
					'name'       => 'title_tag',
					'title'      => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-title' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-title',
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'title_margin',
					'title'      => esc_html__( 'Title Margin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'number',
					'name'          => 'price',
					'title'         => esc_html__( 'Price', 'qi-addons-for-elementor' ),
					'default_value' => 35,
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'price_color',
					'title'      => esc_html__( 'Price Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-price-value' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'price_typography',
					'title'      => esc_html__( 'Price Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-price-value',
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'currency',
					'title'         => esc_html__( 'Currency', 'qi-addons-for-elementor' ),
					'default_value' => '$',
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'currency_color',
					'title'      => esc_html__( 'Currency Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-price-currency' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'currency_typography',
					'title'      => esc_html__( 'Currency Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-price-currency',
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'currency_position',
					'title'         => esc_html__( 'Currency Position', 'qi-addons-for-elementor' ),
					'options'       => array(
						'row'         => esc_html__( 'Left from Price', 'qi-addons-for-elementor' ),
						'row-reverse' => esc_html__( 'Right from Price', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'row',
					'selectors'     => array(
						'{{WRAPPER}} .qodef-m-price-wrapper' => 'flex-direction: {{VALUE}};',
					),
					'group'         => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'period',
					'title'         => esc_html__( 'Period', 'qi-addons-for-elementor' ),
					'default_value' => '/mo',
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'period_position',
					'title'         => esc_html__( 'Period Position', 'qi-addons-for-elementor' ),
					'options'       => array(
						'side'   => esc_html__( 'Side', 'qi-addons-for-elementor' ),
						'bottom' => esc_html__( 'Bottom', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'side',
					'group'         => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'period_color',
					'title'      => esc_html__( 'Period Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-price-period' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'period_typography',
					'title'      => esc_html__( 'Period Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-price-period',
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'price_margin',
					'title'      => esc_html__( 'Price Margin', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-price-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'currency_margin',
					'title'      => esc_html__( 'Currency Margin', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-price-currency' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'period_margin',
					'title'      => esc_html__( 'Period Margin', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-price-period' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'text',
					'name'          => 'label',
					'title'         => esc_html__( 'Label', 'qi-addons-for-elementor' ),
					'default_value' => esc_html__( 'New', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'label_type',
					'title'         => esc_html__( 'Label Type', 'qi-addons-for-elementor' ),
					'options'       => array(
						'badge'  => esc_html__( 'Badge', 'qi-addons-for-elementor' ),
						'ribbon' => esc_html__( 'Ribbon', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'badge',
					'group'         => esc_html__( 'Label Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'label_color',
					'title'      => esc_html__( 'Label Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-label' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Label Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'label_background_color',
					'title'      => esc_html__( 'Label Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-label' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Label Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'label_typography',
					'title'      => esc_html__( 'Label Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-label',
					'group'      => esc_html__( 'Label Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'label_padding',
					'title'      => esc_html__( 'Label Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Label Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'label_border_radius',
					'title'      => esc_html__( 'Label Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-label-type--badge .qodef-m-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'label_type' => array(
								'values'        => 'badge',
								'default_value' => 'badge',
							),
						),
					),
					'group'      => esc_html__( 'Label Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'         => 'dimensions',
					'name'               => 'label_position',
					'title'              => esc_html__( 'Label Position', 'qi-addons-for-elementor' ),
					'size_units'         => array( 'px', '%', 'em' ),
					'allowed_dimensions' => array( 'top', 'right' ),
					'responsive'         => true,
					'selectors'          => array(
						'{{WRAPPER}} .qodef-m-label' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}};',
					),
					'group'              => esc_html__( 'Label Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'enable_separator',
					'title'      => esc_html__( 'Enable Separator', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'list_type',
					'title'         => esc_html__( 'List Type', 'qi-addons-for-elementor' ),
					'options'       => array(
						'unordered' => esc_html__( 'Unordered', 'qi-addons-for-elementor' ),
						'ordered'   => esc_html__( 'Ordered', 'qi-addons-for-elementor' ),
						'none'      => esc_html__( 'None', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'unordered',
					'group'         => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'list_style_position',
					'title'         => esc_html__( 'List Style Position', 'qi-addons-for-elementor' ),
					'options'       => array(
						'outside' => esc_html__( 'Outside', 'qi-addons-for-elementor' ),
						'inside'  => esc_html__( 'Inside', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'inside',
					'dependency'    => array(
						'hide' => array(
							'list_type' => array(
								'values'        => 'none',
								'default_value' => 'unordered',
							),
						),
					),
					'group'         => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'icons',
					'name'          => 'icon',
					'title'         => esc_html__( 'Items Icon', 'qi-addons-for-elementor' ),
					'dependency'    => array(
						'show' => array(
							'list_type' => array(
								'values'        => 'unordered',
								'default_value' => 'unordered',
							),
						),
					),
					'default_value' => array(),
					'group'         => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'icon_color',
					'title'      => esc_html__( 'Items Icon Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-item .qodef-e-icon' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'list_type' => array(
								'values'        => 'unordered',
								'default_value' => 'unordered',
							),
						),
					),
					'group'      => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_size',
					'title'      => esc_html__( 'Items Icon Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-list-style-icon .qodef-e-item .qodef-e-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'list_type' => array(
								'values'        => 'unordered',
								'default_value' => 'unordered',
							),
						),
					),
					'group'      => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_margin_right',
					'title'      => esc_html__( 'Items Icon Margin Right', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em', '%' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-list-style-icon .qodef-e-item .qodef-e-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'list_type' => array(
								'values'        => 'unordered',
								'default_value' => 'unordered',
							),
						),
					),
					'group'      => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'icon_vertical_offset',
					'title'      => esc_html__( 'Items Icon Vertical Offset', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -50,
							'max' => 50,
						),
						'em' => array(
							'min' => -10,
							'max' => 10,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-list-style-icon .qodef-e-item .qodef-e-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
					),
					'dependency' => array(
						'show' => array(
							'list_type' => array(
								'values'        => 'unordered',
								'default_value' => 'unordered',
							),
						),
					),
					'group'      => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_text' => esc_html__( 'Lorem ipsum dolor et sit amet', 'qi-addons-for-elementor' ),
						),
						array(
							'item_text' => esc_html__( 'Lorem ipsum dolor et sit amet', 'qi-addons-for-elementor' ),
						),
						array(
							'item_text' => esc_html__( 'Lorem ipsum dolor et sit amet', 'qi-addons-for-elementor' ),
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'text',
							'name'          => 'item_text',
							'title'         => esc_html__( 'Text', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Lorem ipsum dolor et sit amet', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type'    => 'select',
							'name'          => 'item_excluded',
							'title'         => esc_html__( 'Excluded', 'qi-addons-for-elementor' ),
							'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
							'default_value' => 'no',
						),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'item_color',
					'title'      => esc_html__( 'Items Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-content' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Items Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'excluded_item_color',
					'title'      => esc_html__( 'Excluded Items Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-content .qodef--excluded' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Items Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'items_typography',
					'title'      => esc_html__( 'Items Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-m-content',
					'group'      => esc_html__( 'Items Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->import_shortcode_options(
				array(
					'shortcode_base'    => 'qi_addons_for_elementor_button',
					'exclude'           => array( 'custom_class' ),
					'additional_params' => array(
						'nested_group' => esc_html__( 'Button', 'qi-addons-for-elementor' ),
					),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'table_padding',
					'title'      => esc_html__( 'Table Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-pricing-table .qodef-m-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'dependency' => array(
						'hide' => array(
							'layout' => array(
								'values'        => 'with-icon',
								'default_value' => 'standard',
							),
						),
					),
					'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'table_content_padding',
					'title'      => esc_html__( 'Table Content Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-pricing-table .qodef-m-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'dependency' => array(
						'hide' => array(
							'layout' => array(
								'values'        => 'with-icon',
								'default_value' => 'standard',
							),
						),
					),
					'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'item_margin_bottom',
					'title'      => esc_html__( 'Item Margin Bottom', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-content li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Items Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'button_margin_top',
					'title'      => esc_html__( 'Button Margin Top', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-button .qodef-qi-button' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
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
			$this->map_extra_options();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes']   = $this->get_holder_classes( $atts );
			$atts['button_params']    = $this->generate_button_params( $atts );
			$atts['list_tag']         = ( 'unordered' === $atts['list_type'] ) ? 'ul' : 'ol';
			$atts['items']            = $this->parse_repeater_items( $atts['children'] );
			$atts['separator_params'] = $this->generate_separator_params( $atts );

			return apply_filters( 'qi_addons_for_elementor_filter_pricing_table_render_template', qi_addons_for_elementor_get_template_part( 'shortcodes/pricing-table', 'variations/' . $atts['layout'] . '/templates/' . $atts['layout'], '', $atts ), $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-pricing-table';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['featured_table'] ) && 'yes' === $atts['featured_table'] ? 'qodef-status--featured' : 'qodef-status--regular';
			$holder_classes[] = ! empty( $atts['list_style_position'] ) ? 'qodef-list-style--' . $atts['list_style_position'] : '';
			$holder_classes[] = ! empty( $atts['list_type'] ) ? 'qodef-list-type--' . $atts['list_type'] : '';
			$holder_classes[] = ! empty( $atts['list_type'] ) && ( 'unordered' === $atts['list_type'] ) && ! empty( $atts['icon']['value'] ) ? 'qodef-list-style-icon' : '';
			$holder_classes[] = ! empty( $atts['label_type'] ) ? 'qodef-label-type--' . $atts['label_type'] : '';
			$holder_classes[] = ! empty( $atts['period_position'] ) ? 'qodef-period--' . $atts['period_position'] : '';

			return implode( ' ', $holder_classes );
		}

		private function generate_button_params( $atts ) {
			$params = array();

			if ( ! empty( $atts['button_text'] ) || ! empty( $atts['button_icon']['value'] ) ) {
				$params = $this->populate_imported_shortcode_atts(
					array(
						'shortcode_base' => 'qi_addons_for_elementor_button',
						'exclude'        => array( 'custom_class' ),
						'atts'           => $atts,
					)
				);
			}

			return $params;
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
