<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Aelia_CS {

	public function __construct() {

		add_filter( 'wfacp_product_raw_data', [ $this, 'wfacp_product_raw_data' ], 10, 2 );
		add_filter( 'wfacp_discount_regular_price_data', [ $this, 'wfacp_discount_regular_price_data' ] );
		add_filter( 'wfacp_discount_price_data', [ $this, 'wfacp_discount_price_data' ] );
		add_filter( 'wfacp_product_switcher_price_data', [ $this, 'wfacp_product_switcher_price_data' ], 10, 2 );
		add_filter( 'wfacp_discount_amount_data', [ $this, 'wfacp_discount_amount_data' ], 10, 2 );
	}

	/**
	 * @hooked into `wcct_deal_amount_fixed_amount_{$type}` | `wcct_regular_price_event_value_fixed`
	 * Modifies the amount for the fixed discount given by the admin in the currency selected.
	 *
	 * @param integer|float $price wfacp_discount_price_data
	 *
	 * @return float
	 */
	public function alter_fixed_amount( $price, $currency = null ) {
		return $this->get_price_in_currency( $price, $currency );
	}

	/**
	 * Basic integration with WooCommerce Currency Switcher, developed by Aelia
	 * (http://aelia.co). This method can be used by any 3rd party plugin to
	 * return prices converted to the active currency.
	 *
	 * Need a consultation? Find us on Codeable: https://aelia.co/hire_us
	 *
	 * @param double price The source price.
	 * @param string to_currency The target currency. If empty, the active currency
	 * will be taken.
	 * @param string from_currency The source currency. If empty, WooCommerce base
	 * currency will be taken.
	 *
	 * @return double The price converted from source to destination currency.
	 * @author Aelia <support@aelia.co>
	 * @link https://aelia.co
	 */
	public function get_price_in_currency( $price, $to_currency = null, $from_currency = null ) {
		// If source currency is not specified, take the shop's base currency as a default
		if ( empty( $from_currency ) ) {
			$from_currency = get_option( 'woocommerce_currency' );
		}
		// If target currency is not specified, take the active currency as a default.
		// The Currency Switcher sets this currency automatically, based on the context. Other
		// plugins can also override it, based on their own custom criteria, by implementing
		// a filter for the "woocommerce_currency" hook.
		//
		// For example, a subscription plugin may decide that the active currency is the one
		// taken from a previous subscription, because it's processing a renewal, and such
		// renewal should keep the original prices, in the original currency.
		if ( empty( $to_currency ) ) {
			$to_currency = get_woocommerce_currency();
		}

		// Call the currency conversion filter. Using a filter allows for loose coupling. If the
		// Aelia Currency Switcher is not installed, the filter call will return the original
		// amount, without any conversion being performed. Your plugin won't even need to know if
		// the multi-currency plugin is installed or active
		return apply_filters( 'wc_aelia_cs_convert', $price, $from_currency, $to_currency );
	}

	public function wfacp_discount_regular_price_data( $price ) {

		return $this->get_price_in_currency( $price );

	}


	public function wfacp_discount_price_data( $price ) {
		return $this->get_price_in_currency( $price );
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

	public function wfacp_discount_amount_data( $discount_amount, $discount_type ) {
		switch ( $discount_type ) {
			case 'fixed_discount_reg':
				$discount_amount = $this->get_price_in_currency( $discount_amount );
				break;
			case 'fixed_discount_sale':
				$discount_amount = $this->get_price_in_currency( $discount_amount );
				break;
		}

		return $discount_amount;
	}

	public function wfacp_product_raw_data( $raw_data, $product ) {


		if ( ! $product instanceof WC_Product ) {
			return $raw_data;
		}
		$instance = Aelia\WC\CurrencySwitcher\WC27\WC_Aelia_CurrencyPrices_Manager::instance();
		$currency = $instance->get_selected_currency();

		$product_regular_prices_in_currency = [];
		$product_sale_prices_in_currency    = [];
		// For variation type of product
		if ( in_array( $product->get_type(), WFACP_Common::get_variation_product_type() ) ) {

			$temp_regular = get_post_meta( $product->get_id(), 'variable_regular_currency_prices', true );
			if ( '' !== $temp_regular ) {
				$temp_regular = json_decode( $temp_regular, true );
				if ( is_array( $temp_regular ) ) {
					$product_regular_prices_in_currency = $temp_regular;
				}
			}
			$temp_sale = get_post_meta( $product->get_id(), 'variable_regular_currency_prices', true );
			if ( '' !== $temp_sale ) {
				$temp_sale = json_decode( $temp_sale, true );

				if ( is_array( $temp_sale ) ) {
					$product_sale_prices_in_currency = $temp_sale;
				}
			}
		} else {
			$product_sale_prices_in_currency = $product->get_meta( '_sale_currency_prices' );
			if ( '' !== $product_sale_prices_in_currency ) {
				$product_sale_prices_in_currency = json_decode( $product_sale_prices_in_currency, true );
			}
			$product_regular_prices_in_currency = $product->get_meta( '_regular_currency_prices' );
			if ( '' !== $product_regular_prices_in_currency ) {
				$product_regular_prices_in_currency = json_decode( $product_regular_prices_in_currency, true );
			}
		}

		$regular_price = isset( $product_regular_prices_in_currency[ $currency ] ) ? $product_regular_prices_in_currency[ $currency ] : null;
		$sale_price    = isset( $product_sale_prices_in_currency[ $currency ] ) ? $product_sale_prices_in_currency[ $currency ] : null;

		if ( ! is_null( $regular_price ) ) {

			remove_action( 'wfacp_discount_regular_price_data', [ $this, 'wfacp_discount_regular_price_data' ] );
			remove_action( 'wfacp_discount_price_data', [ $this, 'wfacp_discount_price_data' ] );

			$raw_data['regular_price'] = $regular_price;
			$raw_data['price']         = $regular_price;
		}
		if ( ! is_null( $sale_price ) ) {
			$raw_data['sale_price'] = $sale_price;
			$raw_data['price']      = $sale_price;
		}

		return $raw_data;
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Aelia_CS(), 'aelia_cs' );

