<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_category_list_shortcode' ) ) {
	/**
	 * Function that is adding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_category_list_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Product_Category_List_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_product_category_list_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_List_Shortcode' ) ) {
	class QiAddonsForElementor_Product_Category_List_Shortcode extends QiAddonsForElementor_List_Shortcode {

		public function __construct() {
			$this->set_post_type( 'product' );
			$this->set_post_type_taxonomy( 'product_cat' );
			$this->set_layouts( apply_filters( 'qi_addons_for_elementor_filter_product_category_list_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'qi_addons_for_elementor_filter_product_category_list_extra_options', array(), $this ) );

			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_URL_PATH . '/woocommerce/shortcodes/product-category-list' );
			$this->set_base( 'qi_addons_for_elementor_product_category_list' );
			$this->set_name( esc_html__( 'Product Category List', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays list of product categories', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'WooCommerce', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/product-category-list/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#4_product_category_list' );
			$this->set_video( 'https://www.youtube.com/watch?v=GVvdJh6TJ1Y' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);
			$this->map_list_options(
				array(
					'exclude_option' => array(
						'enable_pagination',
					),
					'include_option' => array( 'enable_zigzag' ),
				)
			);
			$this->map_query_options(
				array(
					'exclude_option' => array( 'additional_params' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'hide_empty',
					'title'      => esc_html__( 'Hide Empty', 'qi-addons-for-elementor' ),
					'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes', false ),
					'group'      => esc_html__( 'Query', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'additional_params',
					'title'      => esc_html__( 'Additional Params', 'qi-addons-for-elementor' ),
					'options'    => array(
						''   => esc_html__( 'No', 'qi-addons-for-elementor' ),
						'id' => esc_html__( 'Taxonomy IDs', 'qi-addons-for-elementor' ),
					),
					'group'      => esc_html__( 'Query', 'qi-addons-for-elementor' ),
				)
			);
			$this->set_option(
				array(
					'field_type'  => 'text',
					'name'        => 'taxonomy_ids',
					'title'       => esc_html__( 'Taxonomy IDs', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Separate taxonomy IDs with commas', 'qi-addons-for-elementor' ),
					'group'       => esc_html__( 'Query', 'qi-addons-for-elementor' ),
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
			$this->map_layout_options(
				array(
					'layouts'        => $this->get_layouts(),
					'exclude_option' => array( 'title_hover_color' ),
				)
			);
			$this->import_shortcode_options(
				array(
					'shortcode_base'    => 'qi_addons_for_elementor_button',
					'exclude'           => array( 'custom_class', 'button_link' ),
					'additional_params' => array(
						'nested_group' => esc_html__( 'Button', 'qi-addons-for-elementor' ),
					),
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
						'{{WRAPPER}} .qodef-e-img-holder:after' => 'background-color: {{VALUE}};',
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
						'{{WRAPPER}} .qodef-e:hover .qodef-e-img-holder:after' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->set_option(
				array(
					'field_type' => 'select',
					'name'       => 'image_hover',
					'title'      => esc_html__( 'Image Hover', 'qi-addons-for-elementor' ),
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
					'title'      => esc_html__( 'Image Hover Zoom Origin', 'qi-addons-for-elementor' ),
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
					'field_type' => 'dimensions',
					'name'       => 'border_radius',
					'title'      => esc_html__( 'Border Radius', 'qi-addons-for-elementor' ),
					'size_units' => array( 'px', '%', 'em' ),
					'responsive' => true,
					'selectors'  => array(
						'{{WRAPPER}} .qodef-e' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden; z-index: 1;',
					),
					'group'      => esc_html__( 'Border Style', 'qi-addons-for-elementor' ),
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
					'field_type' => 'end_controls_tabs',
					'name'       => 'image_style_tabs_end',
					'title'      => esc_html__( 'Icon End', 'qi-addons-for-elementor' ),
					'group'      => esc_html__( 'Image Style', 'qi-addons-for-elementor' ),
				)
			);
			$this->map_extra_options();
		}

		public function render( $options, $content = null ) {
			parent::render( $options );

			$atts = $this->get_atts();

			$atts['post_type']      = $this->get_post_type();
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['taxonomy_items'] = get_terms( qi_addons_for_elementor_get_custom_post_type_taxonomy_query_args( $atts, array( 'taxonomy' => $this->get_post_type_taxonomy() ) ) );

			$atts['this_shortcode'] = $this;

			return qi_addons_for_elementor_get_template_part( 'plugins/woocommerce/shortcodes/product-category-list', 'templates/content', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-addons-woo-shortcode';
			$holder_classes[] = 'qodef-qi-woo-product-category-list';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-item-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['image_hover'] ) ? 'qodef-image--hover-' . $atts['image_hover'] : '';
			$holder_classes[] = ! empty( $atts['image_zoom_origin'] ) ? 'qodef-image--hover-from-' . $atts['image_zoom_origin'] : '';

			$list_classes   = $this->get_list_classes( $atts );
			$holder_classes = array_merge( $holder_classes, $list_classes );

			return implode( ' ', $holder_classes );
		}

		public function get_item_classes( $atts ) {
			$item_classes      = $this->init_item_classes();
			$list_item_classes = $this->get_list_item_classes( $atts );

			$item_classes = array_merge( $item_classes, $list_item_classes );

			return implode( ' ', $item_classes );
		}

		public function generate_button_params( $atts ) {
			$button_params = array();

			if ( isset( $atts['category_slug'] ) ) {
				$atts['button_link']        = array();
				$atts['button_link']['url'] = get_term_link( $atts['category_slug'], 'product_cat' );

				if ( empty( $atts['button_text'] ) ) {
					$atts['button_text'] = esc_html__( 'View More', 'qi-addons-for-elementor' );
				}

				$button_params = $this->populate_imported_shortcode_atts(
					array(
						'shortcode_base' => 'qi_addons_for_elementor_button',
						'exclude'        => array( 'custom_class' ),
						'atts'           => $atts,
					)
				);
			}

			return $button_params;
		}
	}
}
