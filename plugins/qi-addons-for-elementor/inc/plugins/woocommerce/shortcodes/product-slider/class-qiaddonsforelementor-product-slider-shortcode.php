<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_slider_shortcode' ) ) {
	/**
	 * Function that is adding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_slider_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Product_Slider_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_product_slider_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_List_Shortcode' ) ) {
	class QiAddonsForElementor_Product_Slider_Shortcode extends QiAddonsForElementor_Slider_Shortcode {

		public function __construct() {
			$this->set_post_type( 'product' );
			$this->set_post_type_taxonomy( 'product_cat' );
			$this->set_post_type_additional_taxonomies( array( 'product_tag', 'product_type' ) );
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_product_slider_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_product_slider_extra_options', array() ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_URL_PATH . '/woocommerce/shortcodes/product-slider' );
			$this->set_base( 'qi_addons_for_elementor_product_slider' );
			$this->set_name( esc_html__( 'Product Slider', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays slider of products', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'WooCommerce', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/product-slider/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#6_product_slider' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->map_slider_options();
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'zoom_centered_item',
					'title'      => esc_html__( 'Zoom Centered Item', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes' ),
					'dependency' => array(
						'show' => array(
							'centered_slides' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
				)
			);
			$this->map_query_options( array( 'post_type' => $this->get_post_type() ) );
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'filterby',
					'title'         => esc_html__( 'Filter By', 'qi-addons-for-elementor' ),
					'options'       => array(
						''             => esc_html__( 'Default', 'qi-addons-for-elementor' ),
						'on_sale'      => esc_html__( 'On Sale', 'qi-addons-for-elementor' ),
						'featured'     => esc_html__( 'Featured', 'qi-addons-for-elementor' ),
						'top_rated'    => esc_html__( 'Top Rated', 'qi-addons-for-elementor' ),
						'best_selling' => esc_html__( 'Best Selling', 'qi-addons-for-elementor' ),
					),
					'default_value' => '',
					'group'         => esc_html__( 'Query', 'qi-addons-for-elementor' ),
					'dependency'    => array(
						'show' => array(
							'additional_params' => array(
								'values'        => '',
								'default_value' => '',
							),
						),
					),
				)
			);
			$this->map_layout_options( array( 'layouts' => $this->get_layouts() ) );
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'show_category',
					'title'      => esc_html__( 'Show Category', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'group'      => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'    => 'select',
					'name'          => 'show_rating',
					'title'         => esc_html__( 'Show Rating', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'default_value' => '',
					'group'         => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'show_mark',
					'title'      => esc_html__( 'Show Sale/Sold Mark', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no' ),
					'group'      => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'content_padding',
					'title'      => esc_html__( 'Content Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-product-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .qodef-image-content'     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'content_background_color',
					'title'      => esc_html__( 'Content Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-product-content' => 'background-color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'category_color',
					'title'      => esc_html__( 'Category Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-product-categories a' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'hide' => array(
							'show_category' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Category Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'category_hover_color',
					'title'      => esc_html__( 'Category Hover Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-product-categories a:hover' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'hide' => array(
							'show_category' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Category Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'category_typography',
					'title'      => esc_html__( 'Category Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-e-product-categories',
					'dependency' => array(
						'hide' => array(
							'show_category' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Category Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'price_color',
					'title'      => esc_html__( 'Price Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-woo-product-price' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'old_price_color',
					'title'      => esc_html__( 'Old Price Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-woo-product-price del' => 'color: {{VALUE}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'price_typography',
					'title'      => esc_html__( 'Price Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-woo-product-price',
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'currency_font_size',
					'title'      => esc_html__( 'Currency Font Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .woocommerce-Price-currencySymbol' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'currency_offset',
					'title'      => esc_html__( 'Currency Top Offset', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'range'      => array(
						'px' => array(
							'min' => -30,
							'max' => 30,
						),
						'em' => array(
							'min' => -3,
							'max' => 3,
						),
					),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .woocommerce-Price-currencySymbol' => 'top: {{SIZE}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Price Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'rating_color',
					'title'      => esc_html__( 'Rating Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-ratings .qodef-m-star' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'show' => array(
							'show_rating' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Rating Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'slider',
					'name'       => 'rating_size',
					'title'      => esc_html__( 'Rating Icons Size', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-ratings .qodef-m-star' => 'font-size: {{SIZE}}{{UNIT}};',
					),
					'dependency' => array(
						'show' => array(
							'show_rating' => array(
								'values'        => 'yes',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Rating Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->import_shortcode_options(
				array(
					'shortcode_base'    => 'qi_addons_for_elementor_button',
					'exclude'           => array( 'custom_class', 'button_text', 'button_link' ),
					'additional_params' => array(
						'nested_group' => esc_html__( 'Button', 'qi-addons-for-elementor' ),
					),
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
						'{{WRAPPER}} .qodef-e-product-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'start_controls_tabs',
					'name'       => 'image_style_tabs',
					'title'      => esc_html__( 'Image Start', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'    => 'checkbox',
					'name'          => 'image_full_height',
					'title'         => esc_html__( 'Image Full Height', 'qi-addons-for-elementor' ),
					'group'         => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
					'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
					'default_value' => 'no',
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'image_tab_normal',
					'title'      => esc_html__( 'Normal', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'overlay_color',
					'title'      => esc_html__( 'Overlay Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e-product-image-holder:after' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'image_tab_normal_end',
					'title'      => esc_html__( 'Normal End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'start_controls_tab',
					'name'       => 'image_tab_hover',
					'title'      => esc_html__( 'Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'overlay_hover_color',
					'title'      => esc_html__( 'Overlay Hover Color', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e:hover .qodef-e-product-image-holder:after' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'image_hover',
					'title'      => esc_html__( 'Image Image Hover', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
					'options'    => array(
						'zoom'     => esc_html__( 'Zoom In', 'qi-addons-for-elementor' ),
						'zoom-out' => esc_html__( 'Zoom Out', 'qi-addons-for-elementor' ),
						'move'     => esc_html__( 'Move', 'qi-addons-for-elementor' ),
						''         => esc_html__( 'None', 'qi-addons-for-elementor' ),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'image_zoom_origin',
					'title'      => esc_html__( 'Image Image Hover Zoom Origin', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
					'options'    => array(
						''       => esc_html__( 'Center', 'qi-addons-for-elementor' ),
						'top'    => esc_html__( 'Top', 'qi-addons-for-elementor' ),
						'bottom' => esc_html__( 'Bottom', 'qi-addons-for-elementor' ),
						'left'   => esc_html__( 'Left', 'qi-addons-for-elementor' ),
						'right'  => esc_html__( 'Right', 'qi-addons-for-elementor' ),
					),
					'dependency' => array(
						'show' => array(
							'image_hover' => array(
								'values'        => array( 'zoom', 'zoom-out' ),
								'default_value' => 'zoom',
							),
						),
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'end_controls_tab',
					'name'       => 'image_tab_hover_end',
					'title'      => esc_html__( 'Hover End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'mark_padding',
					'title'      => esc_html__( 'Mark Padding', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-woo-product-mark' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'dependency' => array(
						'hide' => array(
							'show_mark' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Mark Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'sale_mark_color',
					'title'      => esc_html__( 'Sale Mark Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-woo-product-mark.qodef-woo-onsale' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'hide' => array(
							'show_mark' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Mark Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'sale_mark_background_color',
					'title'      => esc_html__( 'Sale Mark Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-woo-product-mark.qodef-woo-onsale' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'hide' => array(
							'show_mark' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Mark Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'sold_mark_color',
					'title'      => esc_html__( 'Sold Mark Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-woo-product-mark.qodef-out-of-stock' => 'color: {{VALUE}};',
					),
					'dependency' => array(
						'hide' => array(
							'show_mark' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Mark Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'color',
					'name'       => 'sold_mark_background_color',
					'title'      => esc_html__( 'Sold Mark Background Color', 'qi-addons-for-elementor' ),
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-woo-product-mark.qodef-out-of-stock' => 'background-color: {{VALUE}};',
					),
					'dependency' => array(
						'hide' => array(
							'show_mark' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Mark Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'typography',
					'name'       => 'mark_typography',
					'title'      => esc_html__( 'Mark Typography', 'qi-addons-for-elementor' ),
					'selector'   => '{{WRAPPER}} .qodef-qi-woo-product-mark',
					'dependency' => array(
						'hide' => array(
							'show_mark' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Mark Style', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'dimensions',
					'name'       => 'mark_border_radius',
					'title'      => esc_html__( 'Mark Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-qi-woo-product-mark' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'dependency' => array(
						'hide' => array(
							'show_mark' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'group'      => esc_html__( 'Mark Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'         => 'dimensions',
					'name'               => 'mark_offset',
					'title'              => esc_html__( 'Mark Offset', 'qi-addons-for-elementor' ),
					'allowed_dimensions' => array( 'top', 'right' ),
					'size_units'         => array( 'px', '%' ),
					'responsive'         => true,
					'selectors'          => array(
						'{{WRAPPER}} .qodef-qi-woo-product-mark' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}};',
					),
					'dependency'         => array(
						'hide' => array(
							'show_mark' => array(
								'values'        => 'no',
								'default_value' => '',
							),
						),
					),
					'group'              => esc_html__( 'Mark Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->map_extra_options();
		}

		public static function call_shortcode( $params ) {
			$html = qi_addons_for_elementor_framework_call_shortcode( 'qi_addons_for_elementor_product_slider', $params );
			$html = str_replace( "\n", '', $html );

			return $html;
		}

		public function render( $options, $content = null ) {
			parent::render( $options );

			$atts = $this->get_atts();

			$atts['post_type'] = $this->get_post_type();

			// Additional query args
			$atts['additional_query_args'] = $this->get_additional_query_args( $atts );

			$atts['unique']         = wp_unique_id();
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['query_result']   = new \WP_Query( qi_addons_for_elementor_get_query_params( $atts ) );
			$atts['slider_attr']    = $this->get_slider_data( $atts );

			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'plugins/woocommerce/shortcodes/product-slider', 'templates/content', '', $atts );
		}

		public function get_additional_query_args( $atts ) {
			$args = parent::get_additional_query_args( $atts );

			if ( ! empty( $atts['filterby'] ) ) {
				switch ( $atts['filterby'] ) {
					case 'on_sale':
						$args['no_found_rows'] = 1;
						$args['post__in']      = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
						break;
					case 'featured':
						$args['tax_query'] = WC()->query->get_tax_query();

						$args['tax_query'][] = array(
							'taxonomy'         => 'product_visibility',
							'terms'            => 'featured',
							'field'            => 'name',
							'operator'         => 'IN',
							'include_children' => false,
						);
						break;
					case 'top_rated':
						$args['meta_key'] = '_wc_average_rating';
						$args['order']    = 'DESC';
						$args['orderby']  = 'meta_value_num';
						break;
					case 'best_selling':
						$args['meta_key'] = 'total_sales';
						$args['order']    = 'DESC';
						$args['orderby']  = 'meta_value_num';
						break;
				}
			}

			if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
				$args['meta_query']   = array( 'relation' => 'AND' );
				$args['meta_query'][] = array(
					'key'   => '_stock_status',
					'value' => 'instock',
				);
			}

			return $args;
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-addons-woo-shortcode';
			$holder_classes[] = 'qodef-qi-woo-shortcode-product-slider';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-item-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['image_hover'] ) ? 'qodef-image--hover-' . $atts['image_hover'] : '';
			$holder_classes[] = ! empty( $atts['image_zoom_origin'] ) ? 'qodef-image--hover-from-' . $atts['image_zoom_origin'] : '';
			$holder_classes[] = ( 'yes' === $atts['image_full_height'] ) ? 'qodef-image-full-height' : '';
			$holder_classes[] = ! empty( $atts['zoom_centered_item'] && 'yes' === $atts['zoom_centered_item'] ) ? 'qodef-zoom-centered-item' : '';

			$list_classes   = $this->get_slider_classes( $atts );
			$holder_classes = array_merge( $holder_classes, $list_classes );

			return implode( ' ', $holder_classes );
		}

		public function get_item_classes( $atts ) {
			$item_classes      = $this->init_item_classes();
			$list_item_classes = $this->get_slider_item_classes( $atts );

			$item_classes = array_merge( $item_classes, $list_item_classes );

			return implode( ' ', $item_classes );
		}

		public function generate_button_params( $atts ) {
			$product = qi_addons_for_elementor_woo_get_global_product();

			if ( $product ) {
				$defaults = array(
					'quantity'   => 1,
					'class'      => implode(
						' ',
						array_filter(
							array(
								'button',
								'product_type_' . $product->get_type(),
								$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
								$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
							)
						)
					),
					'attributes' => array(
						'data-product_id'  => $product->get_id(),
						'data-product_sku' => $product->get_sku(),
						'aria-label'       => $product->add_to_cart_description(),
						'rel'              => 'nofollow',
					),
				);

				$args = apply_filters( 'woocommerce_loop_add_to_cart_args', $defaults, $product );

				if ( isset( $args['attributes']['aria-label'] ) ) {
					$args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
				}

				if ( ! empty( $args['class'] ) ) {
					$atts['custom_class'] = $args['class'];
				}

				if ( count( $args['attributes'] ) ) {
					$atts['custom_attrs']                  = $args['attributes'];
					$atts['custom_attrs']['data-quantity'] = $args['quantity'];
				}

				$atts['button_link'] = array();

				$atts['button_link']['url'] = $product->add_to_cart_url();
				$atts['button_text']        = $product->add_to_cart_text();

				return $this->populate_imported_shortcode_atts(
					array(
						'shortcode_base' => 'qi_addons_for_elementor_button',
						'exclude'        => array( 'button_target', 'not_custom_class' ),
						'atts'           => $atts,
					)
				);
			}

			return array();
		}
	}
}
