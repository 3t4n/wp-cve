<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WooMulti_Curcy {
	public $instance = null;
	private $woo_multi_currency_data = null;

	/**
	 * @var WOOMULTI_CURRENCY_Frontend_Price
	 */
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		add_filter( 'wfacp_product_raw_data', [ $this, 'wfacp_product_raw_data' ], 10, 2 );
		add_filter( 'wfacp_discount_amount_data', [ $this, 'wfacp_discount_amount_data' ], 10, 2 );
		add_filter( 'wfacp_product_switcher_price_data', [ $this, 'wfacp_product_switcher_price_data' ], 10, 2 );
		add_action( 'wfacp_after_discount_added_to_item', [ $this, 'remove_actions' ] );

	}

	public function action() {
		$this->instance = WFACP_Common::remove_actions( 'wp_footer', 'WOOMULTI_CURRENCY_Frontend_Design', 'show_action' );
		if ( ! is_null( $this->instance ) && is_object( $this->instance ) && $this->instance instanceof WOOMULTI_CURRENCY_Frontend_Design ) {
			add_action( 'wfacp_footer_before_print_scripts', array( $this->instance, 'show_action' ) );
		}
	}

	/**
	 * @return WOOMULTI_CURRENCY_Data
	 */
	private function get_currency_instance() {
		if ( is_null( $this->woo_multi_currency_data ) && class_exists( 'WOOMULTI_CURRENCY_Data' ) ) {
			$this->woo_multi_currency_data = WOOMULTI_CURRENCY_Data::get_ins();
		}

		return $this->woo_multi_currency_data;
	}

	/**
	 * @param $raw_data
	 * @param $product WC_Product;
	 *
	 * @return mixed
	 */
	public function wfacp_product_raw_data( $raw_data, $product ) {
		$settings = $this->get_currency_instance();
		if ( is_null( $settings ) ) {
			return $raw_data;
		}
		$current_currency = $settings->get_current_currency();
		$fixed_price      = $settings->check_fixed_price();
		if ( ! $fixed_price ) {
			return $raw_data;
		}
		$regular_price_wmcp = json_decode( get_post_meta( $product->get_id(), '_regular_price_wmcp', true ), true );
		$sale_price_wmcp    = json_decode( get_post_meta( $product->get_id(), '_sale_price_wmcp', true ), true );
		if ( ! isset( $regular_price_wmcp[ $current_currency ] ) || $regular_price_wmcp[ $current_currency ] < 0 ) {
			return $raw_data;
		}
		$raw_data['regular_price'] = $regular_price_wmcp[ $current_currency ];
		if ( $raw_data['regular_price'] > 0 ) {
			$sale_price = ! is_null( $sale_price_wmcp ) && isset( $sale_price_wmcp[ $current_currency ] ) ? $sale_price_wmcp[ $current_currency ] : 0;
			if ( $sale_price > 0 ) {
				$raw_data['price']      = $sale_price;
				$raw_data['sale_price'] = $sale_price;
			} else {
				$raw_data['price'] = $raw_data['regular_price'];
			}
		}
		$this->remove_actions();

		return $raw_data;
	}

	public function remove_actions( $item = [] ) {
		$instance = WFACP_Common::remove_actions( 'woocommerce_product_get_regular_price', 'WOOMULTI_CURRENCY_Frontend_Price', 'woocommerce_product_get_regular_price' );
		if ( ! $instance instanceof WOOMULTI_CURRENCY_Frontend_Price ) {
			return $item;
		}
		remove_filter( 'woocommerce_product_get_sale_price', [ $instance, 'woocommerce_product_get_sale_price' ], 99 );
		remove_filter( 'woocommerce_product_get_price', [ $instance, 'woocommerce_product_get_price' ], 99 );
		remove_filter( 'woocommerce_product_variation_get_price', [ $instance, 'woocommerce_product_variation_get_price' ], 99 );
		remove_filter( 'woocommerce_product_variation_get_regular_price', [ $instance, 'woocommerce_product_variation_get_regular_price' ], 99 );
		remove_filter( 'woocommerce_product_variation_get_sale_price', [ $instance, 'woocommerce_product_variation_get_sale_price' ], 99 );

		return $item;
	}

	public function wfacp_discount_amount_data( $discount_amount, $discount_type ) {
		$settings = $this->get_currency_instance();
		if ( is_null( $settings ) ) {
			return $discount_amount;
		}
		switch ( $discount_type ) {
			case 'fixed_discount_reg':
				$discount_amount = wmc_get_price( $discount_amount );
				break;
			case 'fixed_discount_sale':
				$discount_amount = wmc_get_price( $discount_amount );
				break;
		}

		return $discount_amount;
	}

	/**
	 * @param $price_data
	 * @param $pro WC_Product;
	 *
	 * @return mixed
	 */
	public function wfacp_product_switcher_price_data( $price_data, $pro ) {
		$price_data['regular_org'] = $pro->get_regular_price( 'edit' );
		$price_data['price']       = $pro->get_price( 'edit' );

		return $price_data;
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WooMulti_Curcy(), 'WooMulti' );

