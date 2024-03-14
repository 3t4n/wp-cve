<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_pricing_list_list_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_pricing_list_list_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Pricing_List_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_pricing_list_list_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Pricing_List_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_pricing_list_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_pricing_list_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_URL_PATH . '/pricing-list' );
			$this->set_base( 'qi_addons_for_elementor_pricing_list' );
			$this->set_name( esc_html__( 'Pricing List', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays pricing list', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'Business', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/pricing-list/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#pricing_list' );
			$this->set_video( 'https://www.youtube.com/watch?v=OaR9u5JxYo8' );
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
					'field_type' => 'select',
					'name'       => 'title_tag',
					'title'      => esc_html__( 'Title Tag', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'title_tag' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'title_color',
					'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-heading-title' => 'color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'title_typography',
					'title'      => esc_html__( 'Title Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-heading-title',
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'price_color',
					'title'      => esc_html__( 'Price Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-heading-price' => 'color: {{VALUE}};',
						'{{WRAPPER}} .qodef-e-heading-discount-price' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'price_typography',
					'title'      => esc_html__( 'Price Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-heading-price, {{WRAPPER}} .qodef-e-heading-discount-price',
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'price_background_color',
					'title'      => esc_html__( 'Price Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-heading-price' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .qodef-e-heading-discount-price' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'price_padding',
					'title'      => esc_html__( 'Price Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-heading-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-e-heading-discount-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'price_border_radius',
					'title'      => esc_html__( 'Price Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-heading-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-e-heading-discount-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'line_color',
					'title'      => esc_html__( 'Line Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-heading-line' => 'border-bottom-color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'description_color',
					'title'      => esc_html__( 'Description Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-description' => 'color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'description_typography',
					'title'      => esc_html__( 'Description Typography', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-description',
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'line_type',
					'title'         => esc_html__( 'Line Type', 'qi-addons-for-elementor' ),
					'options'       => array(
						'solid'   => esc_html__( 'Solid', 'qi-addons-for-elementor' ),
						'dashed'  => esc_html__( 'Dashed', 'qi-addons-for-elementor' ),
						'dotted'  => esc_html__( 'Dotted', 'qi-addons-for-elementor' ),
						'pattern' => esc_html__( 'Pattern', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'solid',
					'group'         => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'line_color',
					'title'      => esc_html__( 'Line Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-heading-line' => 'border-color: {{VALUE}};',
					),
					'dependency' => array(
						'hide' => array(
							'line_type' => array(
								'values'        => array( 'pattern' ),
								'default_value' => 'solid',
							),
						),
					),
					'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'image',
					'name'       => 'line_pattern',
					'title'      => esc_html__( 'Line Pattern', 'qi-addons-for-elementor' ),
					'dependency' => array(
						'show' => array(
							'line_type' => array(
								'values'        => array( 'pattern' ),
								'default_value' => 'solid',
							),
						),
					),
					'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'line_thickness',
					'title'      => esc_html__( 'Line Thickness', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-heading-line' => 'border-width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .qodef-pricing-line--pattern .qodef-e-heading-line' => 'height: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'         => 'dimensions',
					'name'               => 'line_margin',
					'title'              => esc_html__( 'Line Margin', 'qi-addons-for-elementor' ),
					'size_units'         => array( 'px', '%', 'em' ),
					'allowed_dimensions' => array( 'left', 'right' ),
					'responsive'         => true,
					'selectors'          => array(
						'{{WRAPPER}} .qodef-e-heading-line' => 'margin: 0 {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
					),
					'group'              => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'description_margin_top',
					'title'      => esc_html__( 'Description Margin Top', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-description' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'image_margin',
					'title'      => esc_html__( 'Image Margin', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-layout--image-before .qodef-m-item .qodef-e-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-layout--standard .qodef-m-item .qodef-e-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'item_space',
					'title'      => esc_html__( 'Item Spacing', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'vw' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'General Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'enable_item_separator',
					'title'         => esc_html__( 'Enable Item Separator', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'default_value' => 'no',
					'group'         => esc_html__( 'General Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'separator_top_spacing',
					'title'      => esc_html__( 'Separator Top Spacing', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'vw' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-item:not(:last-child)' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'enable_item_separator' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'General Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'separator_color',
					'title'      => esc_html__( 'Separator Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-item:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'enable_item_separator' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'General Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'separator_thickness',
					'title'      => esc_html__( 'Separator Thickness', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'enable_item_separator' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'      => esc_html__( 'General Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'separator_type',
					'title'         => esc_html__( 'Separator Type', 'qi-addons-for-elementor' ),
					'options'       => array(
						'solid'  => esc_html__( 'Solid', 'qi-addons-for-elementor' ),
						'dashed' => esc_html__( 'Dashed', 'qi-addons-for-elementor' ),
						'dotted' => esc_html__( 'Dotted', 'qi-addons-for-elementor' ),
					),
					'default_value' => 'solid',
					'selectors'     => array(
						'{{WRAPPER}} .qodef-m-item:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					),
					'dependency'    => array(
						'show' => array(
							'enable_item_separator' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
					'group'         => esc_html__( 'General Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'button_margin_top',
					'title'      => esc_html__( 'Button Margin Top', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-m-button' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'General Style', 'qi-addons-for-elementor' ),
				)
			);

			$placeholder = get_option( 'qi_addons_for_elementor_placeholder_image' );

			$this->set_option(
				array(
					'field_type'    => 'repeater',
					'name'          => 'children',
					'title'         => esc_html__( 'Menu Items', 'qi-addons-for-elementor' ),
					'default_value' => array(
						array(
							'item_image'       => $placeholder,
							'item_title'       => esc_html__( 'Item Title 1', 'qi-addons-for-elementor' ),
							'item_description' => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'item_price'       => '$30',
						),
						array(
							'item_image'       => $placeholder,
							'item_title'       => esc_html__( 'Item Title 2', 'qi-addons-for-elementor' ),
							'item_description' => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'item_price'       => '$30',
						),
						array(
							'item_image'       => $placeholder,
							'item_title'       => esc_html__( 'Item Title 3', 'qi-addons-for-elementor' ),
							'item_description' => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
							'item_price'       => '$30',
						),
					),
					'items'         => array(
						array(
							'field_type'    => 'image',
							'name'          => 'item_image',
							'title'         => esc_html__( 'Item Image', 'qi-addons-for-elementor' ),
							'default_value' => $placeholder,
						),
						array(
							'field_type'    => 'text',
							'name'          => 'item_title',
							'title'         => esc_html__( 'Title', 'qi-addons-for-elementor' ),
							'default_value' => esc_html__( 'Item Title', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_title_color',
							'title'      => esc_html__( 'Title Color', 'qi-addons-for-elementor' ),
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}}  .qodef-e-heading-title' => 'color: {{VALUE}};',
							),
						),
						array(
							'field_type'    => 'textarea',
							'name'          => 'item_description',
							'title'         => esc_html__( 'Description', 'qi-addons-for-elementor' ),
							'default_value' => qi_addons_for_elementor_get_example_text( 'excerpt_short' ),
						),
						array(
							'field_type'    => 'text',
							'name'          => 'item_price',
							'title'         => esc_html__( 'Price', 'qi-addons-for-elementor' ),
							'default_value' => '$30',
						),
						array(
							'field_type' => 'text',
							'name'       => 'item_discount_price',
							'title'      => esc_html__( 'Price with Discount', 'qi-addons-for-elementor' ),
						),
						array(
							'field_type' => 'color',
							'name'       => 'item_price_color',
							'title'      => esc_html__( 'Price Color', 'qi-addons-for-elementor' ),
							'selectors'  => array(
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-heading-price'          => 'color: {{VALUE}};',
								'{{WRAPPER}} {{CURRENT_ITEM}} .qodef-e-heading-discount-price' => 'color: {{VALUE}};',
							),
						),
					),
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

			$this->map_extra_options();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['border_style']   = $this->get_border_style( $atts );
			$atts['button_params']  = $this->generate_button_params( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );

			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'shortcodes/pricing-list', 'templates/holder', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-pricing-list';
			$holder_classes[] = ! empty( $atts['line_type'] ) ? 'qodef-pricing-line--' . $atts['line_type'] : '';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = 'yes' === $atts['enable_item_separator'] ? 'qodef-with-separator' : '';

			return implode( ' ', $holder_classes );
		}

		private function get_border_style( $atts ) {
			$border_style = '';

			if ( ! empty( $atts['line_pattern'] ) ) {
				$border_style = 'background-image: url(' . qi_addons_for_elementor_get_attachment_image_url( $atts['line_pattern'], 'full' ) . ')';
			}

			return $border_style;
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
	}
}
