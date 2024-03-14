<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Ob_Cart {
	protected $settings, $cache;
	public static $rules;

	public function __construct() {
		$this->settings = new  VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data();
		if ( ! $this->settings->enable( 'ob_' ) ) {
			return;
		}
		// check for existing item in cart.
		add_filter( 'woocommerce_add_to_cart_sold_individually_found_in_cart', array( __CLASS__, 'viwcuf_woocommerce_add_to_cart_sold_individually_found_in_cart' ), PHP_INT_MAX, 5 );
		//remove product ob in cart
		add_filter( 'woocommerce_before_calculate_totals', array( $this, 'viwcuf_ob_woocommerce_before_calculate_totals' ), 10, 1 );

		// change product quantity on cart page, wcaio sidebar cart
		add_filter( 'woocommerce_cart_item_quantity', array( $this, 'viwcuf_ob_woocommerce_cart_item_quantity' ), PHP_INT_MAX, 3 );
		add_filter( 'vi_wcaio_mini_cart_pd_qty', array( $this, 'viwcuf_ob_wcaio_mini_cart_pd_qty' ), PHP_INT_MAX, 3 );
		
		//mark as ob product
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'viwcuf_ob_woocommerce_add_cart_item_data' ), PHP_INT_MAX, 4 );
	}
	public static function viwcuf_woocommerce_add_to_cart_sold_individually_found_in_cart($result, $product_id, $variation_id, $cart_item_data, $cart_id ){
		if (empty($cart_item_data['viwcuf_ob_product'])&& !$result){
			$result = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Frontend::get_pd_qty_in_cart($product_id,'viwcuf_ob_product');
		}
		return $result;
	}

	public function viwcuf_ob_woocommerce_before_calculate_totals( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return $cart;
		}
		if ( $cart->is_empty() ) {
			return $cart;
		}
		if ( ! wp_doing_ajax() ) {
			return $cart;
		}
		$rule_ids    = $this->settings->get_params( 'ob_ids' );
		self::$rules = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Frontend::get_rules( 'ob_' );
		$cart_items  = $cart->get_cart();
		foreach ( $cart_items as $key => $cart_item ) {
			if ( isset( $cart_item['viwcuf_ob_product'] ) ) {
				$rule_id = $cart_item['viwcuf_ob_product']['rule_id'] ?? '';
				if ( ! $rule_id || ! in_array( $rule_id, self::$rules ) ) {
					$cart->remove_cart_item( $key );
					continue;
				}
				$index = array_search( $rule_id, $rule_ids );
				if ( $index === false || ! $this->settings->get_current_setting( 'ob_active', $index, '' ) ) {
					$cart->remove_cart_item( $key );
					continue;
				}
				$product_id = $this->settings->get_current_setting( 'ob_product', $index, '' );
				if ( ! $product_id || ( $product_id != $cart_item['product_id'] && $product_id != $cart_item['variation_id'] ) ) {
					$cart->remove_cart_item( $key );
					continue;
				}
			}
		}

		return $cart;
	}

	public function viwcuf_ob_woocommerce_cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ) {
		if ( empty( $cart_item['viwcuf_ob_product'] ) ) {
			return $product_quantity;
		}
		$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );

		return $product_quantity = apply_filters( 'viwcuf_ob_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
	}

	public function viwcuf_ob_wcaio_mini_cart_pd_qty( $product_quantity, $cart_item_key, $cart_item ) {
		if ( empty( $cart_item['viwcuf_ob_product'] ) ) {
			return $product_quantity;
		}
		$product_quantity = sprintf( '<div class="vi-wcaio-sidebar-cart-pd-quantity vi-wcaio-hidden"><input type="hidden" name="viwcaio_cart[%s][qty]" value="1"></div>', $cart_item_key);

		return $product_quantity = apply_filters( 'viwcuf_ob_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
	}
	public function viwcuf_ob_woocommerce_add_cart_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
		if ( isset( $_REQUEST['viwcuf_ob_product_id'], $_REQUEST['viwcuf_ob_info'] ) ) {
			$cart_item_data['viwcuf_ob_product'] = viwcuf_sanitize_fields( $_REQUEST['viwcuf_ob_info'] );
		}
		
		return $cart_item_data;
	}

}