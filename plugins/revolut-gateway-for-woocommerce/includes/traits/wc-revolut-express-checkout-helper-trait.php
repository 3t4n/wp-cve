<?php
/**
 * Revolut Helper
 *
 * Helper class for required tools.
 *
 * @package WooCommerce
 * @category Payment Gateways
 * @author Revolut
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Gateway_Revolut_Helper_Trait trait.
 */
trait WC_Gateway_Revolut_Express_Checkout_Helper_Trait {

	/**
	 * Free shipping.
	 *
	 * @var array
	 */
	public static $free_shipping = array(
		array(
			'id'          => 'free_shipping',
			'amount'      => '0',
			'description' => '',
			'label'       => 'SHIPPING',
		),
	);


	/**
	 * Calculate shipping
	 *
	 * @param array $address customer address info.
	 */
	public function calculate_shipping( $address = array() ) {
		$country   = $address['country'];
		$state     = $this->convert_state_name_to_id( $address['country'], $address['state'] );
		$postcode  = $address['postcode'];
		$city      = $address['city'];
		$address_1 = $address['address'];
		$address_2 = $address['address_2'];

		WC()->shipping->reset_shipping();

		if ( $postcode && WC_Validation::is_postcode( $postcode, $country ) ) {
			$postcode = wc_format_postcode( $postcode, $country );
		}

		if ( $country ) {
			WC()->customer->set_location( $country, $state, $postcode, $city );
			WC()->customer->set_shipping_location( $country, $state, $postcode, $city );
		} else {
			WC()->customer->set_billing_address_to_base();
			WC()->customer->set_shipping_address_to_base();
		}

		WC()->customer->set_calculated_shipping( true );
		WC()->customer->save();

		$packages = array();
		$package  = array();

		$package['contents']                 = WC()->cart->get_cart();
		$package['contents_cost']            = 0;
		$package['applied_coupons']          = WC()->cart->applied_coupons;
		$package['user']['ID']               = get_current_user_id();
		$package['destination']['country']   = $country;
		$package['destination']['state']     = $state;
		$package['destination']['postcode']  = $postcode;
		$package['destination']['city']      = $city;
		$package['destination']['address']   = $address_1;
		$package['destination']['address_2'] = $address_2;

		foreach ( WC()->cart->get_cart() as $item ) {
			if ( $item['data']->needs_shipping() ) {
				if ( isset( $item['line_total'] ) ) {
					$package['contents_cost'] += $item['line_total'];
				}
			}
		}

		$packages[0] = $package;
		$packages    = apply_filters( 'woocommerce_cart_shipping_packages', $packages );

		WC()->shipping->calculate_shipping( $packages );
	}

	/**
	 * Get shipping options
	 *
	 * @param array $shipping_address customer address info.
	 */
	public function get_shipping_options( $shipping_address ) {
		$shipping_options = array();

		$GLOBALS['wp']->query_vars['rest_route'] = 'wc/store/v3/cart';

		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
		$this->calculate_shipping( $shipping_address );
		WC()->cart->calculate_totals();

		$packages = WC()->shipping->get_packages();

		if ( empty( $packages ) && ! WC()->customer->has_calculated_shipping() ) {
			return $shipping_options;
		}

		$package = $packages[0];

		if ( empty( $package ) || empty( $package['rates'] ) ) {
			return $shipping_options;
		}

		foreach ( $package['rates'] as $rate ) {
			$shipping_cost = (float) $rate->get_cost() + (float) $rate->get_shipping_tax();
			$shipping_cost = wc_format_decimal( $shipping_cost, wc_get_price_decimals() );

			$shipping_options[] = array(
				'id'          => $rate->id,
				'label'       => $rate->label . ' ' . $shipping_cost . ' ' . get_woocommerce_currency(),
				'description' => '',
				'amount'      => $shipping_cost * 100,
			);
		}

		if ( isset( $shipping_options[0] ) ) {
			if ( isset( $chosen_shipping_methods[0] ) ) {
				$chosen_method_id         = $chosen_shipping_methods[0];
				$compare_shipping_options = function ( $a, $b ) use ( $chosen_method_id ) {
					if ( $a['id'] === $chosen_method_id ) {
						return -1;
					}

					if ( $b['id'] === $chosen_method_id ) {
						return 1;
					}

					return 0;
				};
				usort( $shipping_options, $compare_shipping_options );
			}

			$first_shipping_method_id = $shipping_options[0]['id'];
			$this->update_shipping_method( array( $first_shipping_method_id ) );
		}

		WC()->cart->calculate_totals();

		return $shipping_options;
	}

	/**
	 * Update shipping method in WC cart.
	 *
	 * @param array $shipping_methods shipping method list.
	 */
	public function update_shipping_method( $shipping_methods ) {
		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );

		if ( is_array( $shipping_methods ) ) {
			foreach ( $shipping_methods as $i => $value ) {
				$chosen_shipping_methods[ $i ] = wc_clean( $value );
			}
		}

		WC()->session->set( 'chosen_shipping_methods', $chosen_shipping_methods );

		WC()->cart->calculate_totals();
	}

	/**
	 * Get Required parameters for Revolut order.
	 */
	public function get_revolut_order_descriptor() {
		if ( $this->is_product() ) {
			$product = $this->get_product_data();
			return new WC_Revolut_Order_Descriptor( $product['productPrice'], get_woocommerce_currency(), null );
		}

		return new WC_Revolut_Order_Descriptor( WC()->cart->get_total( '' ), get_woocommerce_currency(), null );
	}

	/**
	 * Check is product page.
	 */
	public function is_product() {
		if ( is_product() || wc_post_content_has_shortcode( 'product_page' ) ) {
			$product = $this->get_product();
			if ( 'subscription' !== $product->get_type() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get product data.
	 */
	public function get_product_data() {
		if ( ! $this->is_product() ) {
			return false;
		}

		$product = $this->get_product();

		if ( 'variable' === $product->get_type() ) {
			$variation_attributes = $product->get_variation_attributes();
			$attributes           = array();

			foreach ( $variation_attributes as $attribute_name => $attribute_values ) {
				$attribute_key = 'attribute_' . sanitize_title( $attribute_name );

				if ( isset( $_GET[ $attribute_key ] ) ) { // phpcs:ignore
					$attributes[ $attribute_key ] = sanitize_text_field( wp_unslash( $_GET[ $attribute_key ] ) ); // phpcs:ignore
				} else {
					$attributes[ $attribute_key ] = $product->get_variation_default_attribute( $attribute_name );
				}
			}

			$data_store   = WC_Data_Store::load( 'product' );
			$variation_id = $data_store->find_matching_product_variation( $product, $attributes );

			if ( ! empty( $variation_id ) ) {
				$product = wc_get_product( $variation_id );
			}
		}

		$data                      = array();
		$data['productPrice']      = $this->get_product_price( $product );
		$data['shipping_required'] = ( wc_shipping_enabled() && $product->needs_shipping() && 0 !== wc_get_shipping_method_count( true ) );

		return $data;
	}

		/**
		 * Get product price
		 *
		 * @param object $product WooCommerce product.
		 */
	public function get_product_price( $product ) {
		$product_price = $product->get_price();
		if ( 'subscription' === $product->get_type() && class_exists( 'WC_Subscriptions_Product' ) ) {
			$product_price = $product->get_price() + WC_Subscriptions_Product::get_sign_up_fee( $product );
		}

		return $product_price;
	}

	/**
	 * Get product object.
	 */
	public function get_product() {
		global $post;

		if ( is_product() ) {
			return wc_get_product( $post->ID );
		} elseif ( wc_post_content_has_shortcode( 'product_page' ) ) {
			// Get id from product_page shortcode.
			preg_match( '/\[product_page id="(?<id>\d+)"\]/', $post->post_content, $shortcode_match );

			if ( ! isset( $shortcode_match['id'] ) ) {
				return false;
			}

			return wc_get_product( $shortcode_match['id'] );
		}

		return false;
	}

	/**
	 * Get redirect URL.
	 */
	public function get_redirect_url() {
		global $post;

		if ( is_product() ) {
			return get_permalink( $post->ID );
		} elseif ( wc_post_content_has_shortcode( 'product_page' ) ) {
			// Get id from product_page shortcode.
			preg_match( '/\[product_page id="(?<id>\d+)"\]/', $post->post_content, $shortcode_match );

			if ( isset( $shortcode_match['id'] ) ) {
				return get_permalink( $shortcode_match['id'] );
			}
		}

		if ( is_cart() ) {
			return wc_get_cart_url();
		}

		return wc_get_checkout_url();
	}

	/**
	 * Get parameters
	 */
	public function get_wc_revolut_payment_request_params() {
		try {
			$revolut_public_id = $this->create_express_checkout_public_id();
			$total             = WC()->cart->get_total( '' );
			$currency          = get_woocommerce_currency();
			$total             = $this->get_revolut_order_total( $total, $currency );

			$revolut_payment_request_settings = get_option( 'woocommerce_revolut_payment_request_settings', array() );
			$revolut_pay_settings             = get_option( 'woocommerce_revolut_pay_settings', array() );

			return array(
				'total'                         => $total,
				'currency'                      => $currency,
				'locale'                        => $this->get_lang_iso_code(),
				'publicToken'                   => $this->get_merchant_public_api_key(),
				'ajax_url'                      => WC_AJAX::get_endpoint( '%%wc_revolut_gateway_ajax_endpoint%%' ),
				'revolut_public_id'             => $revolut_public_id,
				'revolut_pay_origin_url'        => str_replace( array( 'https://', 'http://' ), '', get_site_url() ),
				'revolut_pay_button_theme'      => ! empty( $revolut_pay_settings['revolut_pay_button_theme'] ) ? $revolut_pay_settings['revolut_pay_button_theme'] : '',
				'revolut_pay_button_size'       => ! empty( $revolut_pay_settings['revolut_pay_button_size'] ) ? $revolut_pay_settings['revolut_pay_button_size'] : '',
				'revolut_pay_button_radius'     => ! empty( $revolut_pay_settings['revolut_pay_button_radius'] ) ? $revolut_pay_settings['revolut_pay_button_radius'] : '',
				'payment_request_button_type'   => ! empty( $revolut_payment_request_settings['payment_request_button_type'] ) ? $revolut_payment_request_settings['payment_request_button_type'] : '',
				'payment_request_button_theme'  => ! empty( $revolut_payment_request_settings['payment_request_button_theme'] ) ? $revolut_payment_request_settings['payment_request_button_theme'] : '',
				'payment_request_button_radius' => ! empty( $revolut_payment_request_settings['payment_request_button_radius'] ) ? $revolut_payment_request_settings['payment_request_button_radius'] : '',
				'payment_request_button_size'   => ! empty( $revolut_payment_request_settings['payment_request_button_size'] ) ? $revolut_payment_request_settings['payment_request_button_size'] : '',
				'shipping_options'              => array(),
				'nonce'                         => array(
					'payment'                     => wp_create_nonce( 'wc-revolut-payment-request' ),
					'shipping'                    => wp_create_nonce( 'wc-revolut-payment-request-shipping' ),
					'update_shipping'             => wp_create_nonce( 'wc-revolut-update-shipping-method' ),
					'update_order_total'          => wp_create_nonce( 'wc-revolut-update-order-total' ),
					'load_order_data'             => wp_create_nonce( 'wc-revolut-load-order-data' ),
					'create_order'                => wp_create_nonce( 'wc-revolut-create-order' ),
					'cancel_order'                => wp_create_nonce( 'wc-revolut-cancel-order' ),
					'get_express_checkout_params' => wp_create_nonce( 'wc-revolut-get-express-checkout-params' ),
					'get_payment_request_params'  => wp_create_nonce( 'wc-revolut-get-payment-request-params' ),
					'checkout'                    => wp_create_nonce( 'woocommerce-process_checkout' ),
					'add_to_cart'                 => wp_create_nonce( 'wc-revolut-pr-add-to-cart' ),
					'get_selected_product_data'   => wp_create_nonce( 'wc-revolut-get-selected-product-data' ),
					'log_errors'                  => wp_create_nonce( 'wc-revolut-log-errors' ),
					'set_error_message'           => wp_create_nonce( 'wc-revolut-set-error-message' ),
					'clear_cart'                  => wp_create_nonce( 'wc-revolut-clear-cart' ),
					'process_payment_result'      => wp_create_nonce( 'wc-revolut-process-payment-result' ),
				),
				'is_product_page'               => $this->is_product(),
				'redirect_url'                  => $this->get_redirect_url(),
				'is_cart_page'                  => is_cart(),
				'product'                       => $this->get_product_data(),
				'shipping_required'             => $this->is_shipping_required(),
				'free_shipping_option'          => self::$free_shipping,
				'error_messages'                => array(
					'checkout_general'   => __( 'Something went wrong while processing the order. Please try again', 'revolut-gateway-for-woocommerce' ),
					'cart_create_failed' => __( 'An error occurred while creating WooCommerce cart', 'revolut-gateway-for-woocommerce' ),
				),
			);
		} catch ( Exception $e ) {
			$this->log_error( 'get_wc_revolut_payment_request_params : ' . $e->getMessage() );
		}
	}

	/**
	 * Create Revolut order for express checkout.
	 */
	public function create_express_checkout_public_id() {
		$revolut_public_id = $this->get_revolut_express_checkout_public_id();
		$descriptor        = $this->get_revolut_order_descriptor();
		if ( null === $revolut_public_id ) {
			$revolut_public_id = $this->create_revolut_order( $descriptor, true );
			$this->set_revolut_express_checkout_public_id( $revolut_public_id );
		} else {
			$revolut_public_id = $this->update_revolut_order( $descriptor, $revolut_public_id, true );
			$this->set_revolut_express_checkout_public_id( $revolut_public_id );
		}

		return $revolut_public_id;
	}

	/**
	 * Check if shipping required for the order.
	 */
	public function is_shipping_required() {
		if ( $this->is_product() ) {
			$product = $this->get_product_data();
			return isset( $product['shipping_required'] ) ? $product['shipping_required'] : false;
		}

		if ( is_cart() ) {
			return ! is_null( WC()->cart ) && WC()->cart->needs_shipping();
		}

		return false;
	}

	/**
	 * Check is page supports payment request button
	 *
	 * @param array $payment_request_button_locations configuration.
	 */
	public function page_supports_payment_request_button( $payment_request_button_locations ) {

		if ( empty( $payment_request_button_locations ) ) {
			$payment_request_button_locations = array();
		}

		if ( ! is_cart() && ! $this->is_product() ) {
			return false;
		}

		if ( $this->is_product() && ! in_array( 'product', $payment_request_button_locations, true ) ) {
			return false;
		}

		if ( is_cart() && ! in_array( 'cart', $payment_request_button_locations, true ) ) {
			return false;
		}

		if ( $this->is_subscription_product() ) {
			return false;
		}

		if ( ! $this->check_order_creation_possible() ) {
			return false;
		}

		return true;
	}

	/**
	 * Check is subscription product.
	 */
	public function is_subscription_product() {
		if ( ! class_exists( 'WC_Subscriptions_Product' ) ) {
			return false;
		}

		if ( $this->is_product() ) {
			$product = $this->get_product();
			if ( WC_Subscriptions_Product::is_subscription( $product ) ) {
				return true;
			}
		}

		if ( is_cart() ) {
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				if ( WC_Subscriptions_Product::is_subscription( $_product ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check is creating WooCommerce order possible.
	 */
	public function check_order_creation_possible() {
		if ( is_user_logged_in() || ! $this->check_authentication_required() ) {
			return true;
		}

		return false;
	}

	/**
	 * Covert Revolut address type into the required form of express checkout.
	 *
	 * @param array $address from api.
	 */
	public function convert_revolut_address_to_express_checkout_address( $address ) {
		$address['country']     = $address['country_code'];
		$address['addressLine'] = array( $address['street_line_1'], '' );
		$address['address']     = $address['street_line_1'];
		$address['address_2']   = '';
		$address['postalCode']  = $address['postcode'];
		$address['region']      = ! empty( $address['region'] ) ? $address['region'] : '';
		$address['state']       = $address['region'];

		return $address;
	}

	/**
	 * Check if authentication is required.
	 */
	public function check_authentication_required() {
		if ( 'no' === get_option( 'woocommerce_enable_guest_checkout', 'yes' ) && ! $this->check_account_creation_possible() ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if account creation possible
	 */
	public function check_account_creation_possible() {
		return (
			'yes' === get_option( 'woocommerce_enable_signup_and_login_from_checkout', 'no' ) &&
			'yes' === get_option( 'woocommerce_registration_generate_username', 'yes' ) &&
			'yes' === get_option( 'woocommerce_registration_generate_password', 'yes' )
		);
	}

	/**
	 * Load sate id by name if the api sends state name.
	 *
	 * @param string $country_id id country.
	 * @param string $state_name state name.
	 */
	public function convert_state_name_to_id( $country_id, $state_name ) {
		$wc_states = WC()->countries->get_states( $country_id );
		if ( empty( $wc_states ) || empty( $state_name ) ) {
			return $state_name;
		}

		foreach ( $wc_states as $state_id => $wc_state_name ) {
			if ( strtolower( $wc_state_name ) === strtolower( $state_name ) || strtolower( $state_id ) === strtolower( $state_name ) ) {
				return $state_id;
			}
		}

		// if the standard search fails search county by removing Co prefix for Irish states.
		if ( 'IE' === $country_id ) {
			$state_name = str_replace( 'Co. ', '', $state_name );

			foreach ( $wc_states as $state_id => $wc_state_name ) {
				if ( strtolower( $wc_state_name ) === strtolower( $state_name ) || strtolower( $state_id ) === strtolower( $state_name ) ) {
					return $state_id;
				}
			}
		}

		return $state_name;
	}
}
