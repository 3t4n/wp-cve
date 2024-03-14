<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_add_to_cart_shortcode' ) ) {
	/**
	 * Function that is adding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_add_to_cart_shortcode( $shortcodes ) {
		$shortcodes[] = 'QiAddonsForElementor_Add_To_Cart_Button_Shortcode';

		return $shortcodes;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_shortcodes', 'qi_addons_for_elementor_add_add_to_cart_shortcode' );
}

if ( class_exists( 'QiAddonsForElementor_Shortcode' ) ) {
	class QiAddonsForElementor_Add_To_Cart_Button_Shortcode extends QiAddonsForElementor_Shortcode {

		public function __construct() {
			parent::__construct();
		}

		public function map_shortcode() {
			$this->set_shortcode_path( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_URL_PATH . '/woocommerce/shortcodes/add-to-cart-button' );
			$this->set_base( 'qi_addons_for_elementor_add_to_cart_button' );
			$this->set_name( esc_html__( 'Add to Cart Button', 'qi-addons-for-elementor' ) );
			$this->set_description( esc_html__( 'Shortcode that displays add to cart button', 'qi-addons-for-elementor' ) );
			$this->set_category( esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' ) );
			$this->set_subcategory( esc_html__( 'WooCommerce', 'qi-addons-for-elementor' ) );
			$this->set_demo( 'https://qodeinteractive.com/qi-addons-for-elementor/add-to-cart-button/' );
			$this->set_documentation( 'https://qodeinteractive.com/qi-addons-for-elementor/documentation/#add_to_cart_button' );
			$this->set_option(
				array(
					'field_type' => 'text',
					'name'       => 'custom_class',
					'title'      => esc_html__( 'Custom Class', 'qi-addons-for-elementor' ),
				)
			);

			$this->set_option(
				array(
					'field_type'  => 'text',
					'name'        => 'product_id',
					'title'       => esc_html__( 'Product ID', 'qi-addons-for-elementor' ),
					'description' => esc_html__( 'Enter ID of the product used to add to cart', 'qi-addons-for-elementor' ),
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
			$this->map_extra_options();
		}

		public static function call_shortcode( $params ) {
			$html = qi_addons_for_elementor_framework_call_shortcode( 'qi_addons_for_elementor_add_to_cart', $params );
			$html = str_replace( "\n", '', $html );

			return $html;
		}

		public function render( $options, $content = null ) {
			parent::render( $options );

			$atts = $this->get_atts();

			if ( empty( $atts['product_id'] ) ) {
				return esc_html__( 'Please enter ID of the product', 'qi-addons-for-elementor' );
			}

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['button_params']  = $this->generate_button_params( $atts );

			return qi_addons_for_elementor_get_template_part( 'plugins/woocommerce/shortcodes/add-to-cart-button', 'templates/add-to-cart', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-qi-woo-shortcode-add-to-cart';

			return implode( ' ', $holder_classes );
		}

		public function generate_button_params( $atts ) {
			$product = wc_get_product( $atts['product_id'] );

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
