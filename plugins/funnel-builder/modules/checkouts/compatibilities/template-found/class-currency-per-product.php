<?php

/**
 * Currency Per product by Tyche
 *
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_WC_Cpp
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_WC_Cpp {
	public function __construct() {
		add_action( 'wfacp_after_template_found', [ $this, 'attach_action' ] );
		add_filter( 'wfacp_product_switcher_price_data', [ $this, 'wfacp_product_switcher_price_data' ], 10, 2 );
	}

	public static function is_enable() {
		return class_exists( 'Alg_WC_CPP' );
	}

	public function attach_action() {
		$instance = Alg_WC_CPP::instance();
		if ( ! property_exists( $instance, 'core' ) || ! $instance->core instanceof Alg_WC_CPP_Core || ! function_exists( 'alg_wc_cpp_get_currency_exchange_rate' ) ) {
			return;
		}

		$convert = ( 'convert_shop_default' === get_option( 'alg_wc_cpp_shop_behaviour', 'show_in_different' ) );
		if ( true === $convert || WFACP_Core()->public->is_checkout_override() ) {
			return;
		}

		$instance = $instance->core;
		if ( method_exists( $instance, 'change_price' ) ) {
			$price_filter = ( ALG_WC_CPP_IS_WC_VERSION_BELOW_3_0_0 ? 'woocommerce_get_regular_price' : 'woocommerce_product_get_regular_price' );
			add_filter( $price_filter, array( $instance, 'change_price' ), PHP_INT_MAX, 2 );
			add_filter( 'woocommerce_product_variation_get_regular_price', array( $instance, 'change_price' ), PHP_INT_MAX, 2 );
			add_filter( 'woocommerce_variation_prices_regular_price', array( $instance, 'change_price' ), PHP_INT_MAX, 2 );
			// Sale price
			$price_filter = ( ALG_WC_CPP_IS_WC_VERSION_BELOW_3_0_0 ? 'woocommerce_get_sale_price' : 'woocommerce_product_get_sale_price' );
			add_filter( $price_filter, array( $instance, 'change_price' ), PHP_INT_MAX, 2 );

			add_filter( 'woocommerce_product_variation_get_sale_price', array( $instance, 'change_price' ), PHP_INT_MAX, 2 );
			add_filter( 'woocommerce_variation_prices_sale_price', array( $instance, 'change_price' ), PHP_INT_MAX, 2 );
			// Variation price
			add_filter( 'woocommerce_variation_prices_price', array( $instance, 'change_price' ), PHP_INT_MAX, 2 );
		}
		if ( method_exists( $instance, 'get_variation_prices_hash' ) ) {
			// Variation hash
			add_filter( 'woocommerce_get_variation_prices_hash', array( $instance, 'get_variation_prices_hash' ), PHP_INT_MAX, 3 );
		}
	}

	/**
	 * @param $price_data
	 * @param $pro WC_Product;
	 *
	 * @return mixed
	 */
	public function wfacp_product_switcher_price_data( $price_data, $pro ) {
		$instance = Alg_WC_CPP::instance();
		if ( ! property_exists( $instance, 'core' ) || ! $instance->core instanceof Alg_WC_CPP_Core || ! function_exists( 'alg_wc_cpp_get_currency_exchange_rate' ) ) {
			return $price_data;
		}

		$exchange_rate = alg_wc_cpp_get_currency_exchange_rate( $instance->core->get_product_currency( alg_wc_cpp_get_product_id_or_variation_parent_id( $pro ) ) );
		$rg_price      = $pro->get_regular_price();
		$exchange_rate = 1;
		if ( $rg_price > 0 ) {
			$price_data['regular_org'] = (float) $rg_price * $exchange_rate;
		}
		$price = $pro->get_price();


		if ( $price > 0 ) {
			$price_data['price'] = (float) $price * $exchange_rate;
		}

		return $price_data;
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_Cpp(), 'alg_wc_cpp' );

