<?php

#[AllowDynamicProperties] 

  class WFACP_Compatibility_Subscription {

	public function __construct() {

		add_filter( 'wfacp_show_product_price', [ $this, 'stop_printing_price' ], 10, 2 );
		add_action( 'wfacp_show_product_price_placeholder', [ $this, 'display_price' ], 10, 3 );
		add_action( 'wfacp_before_product_switcher_html', [ $this, 'before' ] );
		add_action( 'wfacp_after_product_switcher_html', [ $this, 'after' ] );
		add_action( 'wfacp_after_coupon_html', [ $this, 'add_hidden_html' ] );

		$this->remove_filter();
	}

	public function before() {

		add_filter( 'woocommerce_subscriptions_product_limitation', [ $this, 'allow_product_purchasable' ], 9999 );
	}

	public function after() {
		remove_filter( 'woocommerce_subscriptions_product_limitation', [ $this, 'allow_product_purchasable' ], 9999 );
	}

	public function add_hidden_html( $coupon ) {
		if ( ! class_exists( 'WC_Subscriptions_Cart', false ) || ! method_exists( 'WC_Subscriptions_Cart', 'all_cart_items_have_free_trial' ) ) {
			return;
		}

		if ( WC_Subscriptions_Cart::all_cart_items_have_free_trial() && in_array( wcs_get_coupon_property( $coupon, 'discount_type' ), array(
				'recurring_fee',
				'recurring_percent'
			) ) ) {
			echo '<span class="wcs-hidden-coupon" type="hidden"></span>';
		}
	}

	public function remove_filter() {
		remove_filter( 'woocommerce_cart_totals_coupon_html', [ 'WC_Subscriptions_Coupon', 'mark_recurring_coupon_in_initial_cart_for_hiding' ], 10 );
	}

	/**
	 * @param $status
	 * @param $product \WC_Product;
	 *
	 */
	public function allow_product_purchasable() {
		return true;
	}

	public static function is_enabled() {
		return class_exists( 'WC_Subscriptions' );
	}

	/**
	 * @param $status boolean
	 * @param $pro WC_Product
	 *
	 * @return bool
	 */
	public function stop_printing_price( $status, $pro ) {
		if ( in_array( $pro->get_type(), WFACP_Common::get_subscription_product_type() ) ) {
			$status = false;
		}

		return $status;
	}

	/**
	 * @param $pro WC_Product
	 * @param $cart_item_key String
	 * @param $price_data []
	 */
	public function display_price( $pro, $cart_item_key, $price_data ) {
		/**
		 * @var $pro WC_Product
		 */
		if ( in_array( $pro->get_type(), WFACP_Common::get_subscription_product_type() ) ) {
			/**
			 * @var $temp WC_Product
			 */
			$temp                  = wc_get_product( $pro->get_id() );
			$s_price_data          = $price_data;
			$s_price_data['price'] = $s_price_data['regular_org'];
			$main_product_price    = WFACP_Common::get_subscription_price( $temp, $s_price_data );
			if ( '' !== $cart_item_key ) {
				$price_html = $price_data['price'];
			} else {

				$price_html = WFACP_Common::get_subscription_price( $pro, $price_data );
			}

			if ( $main_product_price == $price_html || $price_html > $main_product_price ) {
				echo wc_price( $price_html );
			} else {
				echo wc_format_sale_price( $main_product_price, $price_html );
			}
		}
	}
}

add_action( 'wfacp_after_template_found', function () {
	if ( ! WFACP_Compatibility_Subscription::is_enabled() ) {
		return;
	}
	if ( ! function_exists( 'wcs_cart_coupon_remove_link_html' ) ) {
		if ( version_compare( WFACP_Common_Helper::get_subscription_version(), '2.4.0', '<' ) ) {
			function wcs_cart_coupon_remove_link_html( $coupon ) {
				$html = '<a href="' . esc_url( add_query_arg( 'remove_coupon', urlencode( wcs_get_coupon_property( $coupon, 'code' ) ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( wcs_get_coupon_property( $coupon, 'code' ) ) . '">' . __( '[Remove]', 'woocommerce-subscriptions' ) . '</a>';
				echo wp_kses( $html, array_replace_recursive( wp_kses_allowed_html( 'post' ), array( 'a' => array( 'data-coupon' => true ) ) ) );
			}
		}
	}
	new WFACP_Compatibility_Subscription();

} );