<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Us_Cart {
	protected static $settings;
	public static $rule, $cache=array();

	public function __construct() {
		self::$settings = new  VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data();
		if ( ! self::$settings->enable( 'us_' ) ) {
			return;
		}
		// check for existing item in cart.
		add_filter( 'woocommerce_add_to_cart_sold_individually_found_in_cart', array( __CLASS__, 'viwcuf_woocommerce_add_to_cart_sold_individually_found_in_cart' ), PHP_INT_MAX, 5 );
		//remove product upsell in cart
		add_filter( 'woocommerce_after_calculate_totals', array( __CLASS__, 'viwcuf_us_woocommerce_after_calculate_totals' ));

		//change product quantity to add to cart
		add_filter( 'woocommerce_add_to_cart_quantity', array( __CLASS__, 'viwcuf_us_woocommerce_add_to_cart_quantity' ), PHP_INT_MAX, 2 );

		// change product quantity on cart page, wcaio sidebar cart
		add_filter( 'woocommerce_cart_item_quantity', array( __CLASS__, 'viwcuf_us_woocommerce_cart_item_quantity' ), PHP_INT_MAX, 3 );
		add_filter( 'vi_wcaio_mini_cart_pd_qty', array( __CLASS__, 'viwcuf_us_wcaio_mini_cart_pd_qty' ), PHP_INT_MAX, 3 );

		//set new price
		add_filter( 'woocommerce_add_cart_item_data', array( __CLASS__, 'viwcuf_us_woocommerce_add_cart_item_data' ), PHP_INT_MAX, 4 );
		add_filter( 'woocommerce_get_cart_item_from_session', array( __CLASS__, 'viwcuf_us_mark_as_cart_item' ), 10, 1 );
		add_filter( 'woocommerce_product_get_price', array( $this, 'viwcuf_us_product_get_price' ), PHP_INT_MAX, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'viwcuf_us_product_get_price' ), PHP_INT_MAX, 2 );
		add_filter( 'viredis_get_price', array( $this, 'viredis_get_price' ), PHP_INT_MAX, 5 );
	}
	public static function viwcuf_woocommerce_add_to_cart_sold_individually_found_in_cart($result, $product_id, $variation_id, $cart_item_data, $cart_id ){
		if (empty($cart_item_data['viwcuf_us_product'])&& !$result){
			$result = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Frontend::get_pd_qty_in_cart($product_id,'viwcuf_us_product');
		}
		return $result;
	}

	public static function viwcuf_us_woocommerce_after_calculate_totals( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return $cart;
		}
		if ( $cart->is_empty() ) {
			return $cart;
		}
		self::$rule = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Frontend::get_rules( 'us_' );
		$prefix   = self::$settings::get_data_prefix();
		$old_rule = WC()->session->get( 'viwcuf_us_rule', '' );
		$has_new_rule = !self::$rule || !$old_rule ||  self::$rule != ($old_rule[ $prefix ] ??'');
		if ( $has_new_rule ) {
			VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Upsell_Funnel::remove_session();
			$index = self::$rule ? array_search( self::$rule, self::$settings->get_params( 'us_ids' ) ):'';
			if ( $index !== false ) {
				WC()->session->set( 'viwcuf_us_rule', [ $prefix => self::$rule ] );
				WC()->session->set( 'viwcuf_us_rule_info', array(
					'discount_type'   => self::$settings->get_current_setting( 'us_discount_type', $index ),
					'discount_amount' => self::$settings->get_current_setting( 'us_discount_amount', $index ),
					'quantity_limit'  => 1,
				) );
			}
		}
		$rule_id     = WC()->session->get( 'viwcuf_us_rule', '' );
		if (isset($index) && $index !== false) {
			$product_type = self::$settings->get_current_setting( 'us_product_type', $index, 3 );
			$product_ids  = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Upsell_Funnel::get_us_product_ids( self::$rule, self::$settings, $product_type, '' );
			if ( empty( $product_ids ) ) {
				WC()->session->__unset( 'viwcuf_us_recommend_pd_ids' );
			} else {
				$product_ids = is_array( $product_ids ) ? implode( ',', $product_ids ) : $product_ids;
				WC()->session->set( 'viwcuf_us_recommend_pd_ids', $product_ids );
			}
		}
		$product_ids = WC()->session->get( 'viwcuf_us_recommend_pd_ids', '' );
		if ( empty( $rule_id ) || ! $product_ids ) {
			foreach ( $cart->get_cart() as $key => $item ) {
				if ( isset( $item['viwcuf_us_product'] ) ) {
					$cart->remove_cart_item( $key );
				}
			}
		} else {
			$product_ids = explode( ',', $product_ids );
			$cart_items  = $cart->get_cart();
			foreach ( $cart_items as $key => $cart_item ) {
				if ( ! isset( $cart_item['viwcuf_us_product'] ) ) {
					continue;
				}
				if ( ! in_array( $cart_item['product_id'], $product_ids ) && ! in_array( $cart_item['variation_id'], $product_ids ) ) {
					$cart->remove_cart_item( $key );
				}
			}
		}
		return $cart;
	}

	public static function viwcuf_us_woocommerce_add_to_cart_quantity( $quantity, $product_id ) {
		if ( isset( $_REQUEST['viwcuf_us_product_id'] ) ) {
			$qty = isset($_REQUEST['quantity']) ? floatval(sanitize_text_field($_REQUEST['quantity'])): 1;
			$limit = isset($_REQUEST['viwcuf_us_quantity']) ? floatval(sanitize_text_field($_REQUEST['viwcuf_us_quantity'])): 0;
			if ( ! $limit || ($limit > 0 && $limit < $qty)  ) {
				$quantity = - 1;
				wc_add_notice( apply_filters( 'vi-wcuf-i18n_make_error_us_qty_text', esc_html__( 'The number of these items in the cart is maximum', 'checkout-upsell-funnel-for-woo' ), $product_id, $limit ), 'error' );
			}
		}

		return $quantity;
	}

	public static function viwcuf_us_wcaio_mini_cart_pd_qty( $product_quantity, $cart_item_key, $cart_item ) {
		if ( empty( $cart_item['viwcuf_us_product'] ) ) {
			return $product_quantity;
		}
		$rule_id   = WC()->session->get( 'viwcuf_us_rule', '' );
		if ( empty( $rule_id ) ) {
			return $product_quantity;
		}
		$product_quantity = sprintf( '<div class="vi-wcaio-sidebar-cart-pd-quantity vi-wcaio-hidden"><input type="hidden" name="viwcaio_cart[%s][qty]" value="1"></div>', $cart_item_key );
		return $product_quantity = apply_filters( 'viwcuf_us_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
	}

	public static function viwcuf_us_woocommerce_cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ) {
		if ( empty( $cart_item['viwcuf_us_product'] ) ) {
			return $product_quantity;
		}
		$rule_id   = WC()->session->get( 'viwcuf_us_rule', '' );
		if ( empty( $rule_id ) ) {
			return $product_quantity;
		}
		$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );

		return $product_quantity = apply_filters( 'viwcuf_us_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
	}

	public static function viwcuf_us_woocommerce_add_cart_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
		if ( isset( $_REQUEST['viwcuf_us_product_id'] ) ) {
			$cart_item_data['viwcuf_us_product'] = array(
				'product_id' => isset( $_REQUEST['product_id'] ) ? viwcuf_sanitize_fields( $_REQUEST['product_id'] ) : '',
			);
		}

		return $cart_item_data;
	}

	public static function viwcuf_us_mark_as_cart_item( $cart_item_data ) {
		if ( isset( $cart_item_data['viwcuf_us_product'] ) ) {
			$cart_item_data['data']->viwcuf_us_product = $cart_item_data['key'];
		}

		return $cart_item_data;
	}

	public function viredis_get_price( $current_price, $price, $product, $rules, $product_qty ) {
		if ( ! $product ) {
			return $current_price;
		}
		$viwcuf_us_product = $product->viwcuf_us_product ?? '';
		if ( ! $viwcuf_us_product ) {
			return $current_price;
		}
		self::$cache[ 'viredis_get_price_' . ( $product_id = $product->get_id() ) ] = true;
		$current_price                                                              = $this->viwcuf_us_product_get_price( $current_price, $product );
		unset( self::$cache[ 'viredis_get_price_' . $product_id ] );
		return $current_price;
	}
	public function viwcuf_us_product_get_price( $price, $product ) {
		if ( ! $price || ! $product ) {
			return $price;
		}
		if ( ! did_action( 'woocommerce_cart_loaded_from_session' ) ) {
			return $price;
		}
		if ( ! empty( $product->viredis_cart_item ) && ! isset( self::$cache[ 'viredis_get_price_' . $product->get_id() ] ) ) {
			return $price;
		}
		$viwcuf_us_product = $product->viwcuf_us_product ?? '';
		if ( ! $viwcuf_us_product ) {
			return $price;
		}
		if ( isset( self::$cache[ $viwcuf_us_product ][ $price ] ) ) {
			return self::$cache[ $viwcuf_us_product ][ $price ];
		}
		$rule_info = WC()->session->get( 'viwcuf_us_rule_info', array() );
		if ( $rule_info && is_array( $rule_info ) && count( $rule_info ) ) {
			$discount_type   = $rule_info['discount_type'] ?? '';
			$discount_amount = $rule_info['discount_amount'] ?? 0;
			$regular_price   = in_array( $discount_type, [ '1', '2' ] ) ? (float) $product->get_regular_price() : $price;
			$discount_amount = $discount_amount ?: 0;
			$new_price       = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Frontend::set_new_price_pd( $price, $regular_price, $discount_type, $discount_amount );
		}

		return self::$cache[ $viwcuf_us_product ][ $price ] = apply_filters( 'viwcuf_us_product_get_price', $new_price ?? $price, $product );
	}

}