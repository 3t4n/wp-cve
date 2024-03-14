<?php
namespace CTXFeed\V5\Compatibility;
use Aelia\WC\CurrencySwitcher\WC36\WC_Aelia_CurrencyPrices_Manager;

class MultiCurrency {

	public function __construct() {

		add_action( 'woo_feed_before_product_loop', [$this,'woo_feed_pro_apply_hooks_before_product_loop'], 10, 3 );
		add_action( 'woo_feed_after_product_loop', [$this,'woo_feed_pro_remove_hooks_after_product_loop'], 10, 3 );

		add_filter( 'woo_feed_filter_product_regular_price', [$this,'woo_feed_filter_price_for_currency_switcher'], 10, 5 );
		add_filter( 'woo_feed_filter_product_price', [$this,'woo_feed_filter_price_for_currency_switcher'], 10, 5 );
		add_filter( 'woo_feed_filter_product_sale_price', [$this,'woo_feed_filter_price_for_currency_switcher'], 10, 5 );
		add_filter( 'woo_feed_filter_product_regular_price_with_tax', [$this,'woo_feed_filter_price_for_currency_switcher'], 10, 5 );
		add_filter( 'woo_feed_filter_product_price_with_tax', [$this,'woo_feed_filter_price_for_currency_switcher'], 10, 5 );
		add_filter( 'woo_feed_filter_product_sale_price_with_tax', [$this,'woo_feed_filter_price_for_currency_switcher'], 10, 5 );
	}


	/**
	 * Apply Hooks Before Looping through ProductIds
	 *
	 * @param int[] $productIds product id array.
	 * @param array $feedConfig feed config array.
	 */
	public function woo_feed_pro_apply_hooks_before_product_loop( $productIds, $feedRules, $config ) {

		if(is_object($config)){
			// Aelia Currency Switcher support.
			$currency_code = $config->get_feed_currency();
			$GLOBALS['woo_feed_get_single_feed_default_currency'] = get_woocommerce_currency();

			add_filter(
				'wc_aelia_cs_selected_currency',
				function ( $selected_currency ) use ( $currency_code ) {
					return $currency_code;
				},
				999
			);

			// WooCommerce Currency Switcher by realmag777 support.
			if ( class_exists( 'woocommerce_wpml' ) && wcml_is_multi_currency_on() ) {
				//when wpml and woocs both is installed and wpml is enabled, woocs change currency by it's own filter, filter is removed here
				if ( class_exists( 'WOOCS' ) ) {
					global $WOOCS;
					remove_filter( 'woocommerce_currency', array( $WOOCS, 'get_woocommerce_currency' ), 9999 );
				}
			} elseif ( class_exists( 'WOOCS' ) ) {
				global $WOOCS;
				$currency_code = $WOOCS->default_currency;
				if ( $currency_code != $config->get_feed_currency() ) {
					$WOOCS->set_currency( $config->get_feed_currency() );
				} else {
					$WOOCS->set_currency( $currency_code );
				}
			}

//			if (is_plugin_active('wc-dynamic-pricing-and-discounts/wc-dynamic-pricing-and-discounts.php')) {
//				error_log(print_r("right_press_add",true));
//				// RightPress dynamic pricing support.
//				add_filter( 'rightpress_product_price_shop_change_prices_in_backend', '__return_true', 999 );
//				add_filter( 'rightpress_product_price_shop_change_prices_before_cart_is_loaded', '__return_true', 999 );
//			}

			// WooCommerce Out of Stock visibility override
			if ( $config->get_outofstock_visibility() && '1' == $config->get_outofstock_visibility() ) {
				// just return false as wc expect the value should be 'yes' with eqeqeq (===) operator.
				add_filter( 'pre_option_woocommerce_hide_out_of_stock_items', '__return_false', 999 );
			}
		}

	}

	/**
	 * Remove Applied Hooks Looping through ProductIds
	 *
	 * @param int[] $productIds
	 * @param array $feedConfig the feed array.
	 *
	 * @see woo_feed_apply_hooks_before_product_loop
	 */
	public function woo_feed_pro_remove_hooks_after_product_loop( $productIds, $feedRules, $config ) {
		if(is_object($config)) {
			// Aelia Currency Switcher support.
			global $woo_feed_get_single_feed_default_currency; //get previously saved currency (default currency)
			$currency_code =  $config->get_feed_currency();
			//$currency_code = $woo_feed_get_single_feed_default_currency;

			add_filter(
				'wc_aelia_cs_selected_currency',
				function ( $selected_currency ) use ( $currency_code ) {
					return $currency_code;
				},
				999
			);

			// WooCommerce Currency Switcher by realmag777 support.
			if ( class_exists( 'WOOCS' ) ) {
				global $WOOCS;
				$currency_code = $WOOCS->default_currency;
				if ( $currency_code != $config->get_feed_currency() ) {
					$WOOCS->set_currency( $config->get_feed_currency() );
				} else {
					$WOOCS->set_currency( $currency_code );
				}
			}
//
//			if (is_plugin_active('wc-dynamic-pricing-and-discounts/wc-dynamic-pricing-and-discounts.php')) {
//				error_log(print_r("right_press_remove",true));
//				// RightPress dynamic pricing support.
//				remove_filter( 'rightpress_product_price_shop_change_prices_in_backend', '__return_true', 999 );
//				remove_filter( 'rightpress_product_price_shop_change_prices_before_cart_is_loaded', '__return_true', 999 );
//			}

			// WooCommerce Out of Stock visibility override
			if ( $config->get_outofstock_visibility() && '1' == $config->get_outofstock_visibility() ) {
				remove_filter( 'pre_option_woocommerce_hide_out_of_stock_items', '__return_false', 999 );
			}
		}
	}

	/**
	 * Currency Convert for Currency Switcher
	 *
	 * @param float $price Product Price
	 * @param WC_Product $product Product Object
	 * @param array $config Feed Config
	 *
	 * @return float|string
	 */
	public function woo_feed_filter_price_for_currency_switcher( $price, $product, $config, $tax, $price_type ) {

		// when currency switcher plugin by wp wham exists
		if ( is_plugin_active( 'currency-switcher-woocommerce/currency-switcher-woocommerce.php' ) ) {

			if ($config->get_feed_currency() !== get_woocommerce_currency() ) {

				if ( ! empty( $price ) ) {
					$price = alg_get_product_price_by_currency( $price, $config->get_feed_currency() );
					$price = $config->get_number_format();
				}
			}
		} elseif ( is_plugin_active( 'woocommerce-multicurrency/woocommerce-multicurrency.php' ) ) {
			// compatibility with Woocommerce Multi Currency by TIV.NET INC
			if ( $config->get_feed_currency() !== get_woocommerce_currency() ) {
				$currency = get_woocommerce_currency();

				if ( ! empty( $price ) && class_exists( '\WOOMC\API' ) ) {
					$default_currency = \WOOMC\API::default_currency();
					$price            = \WOOMC\API::convert( $price, $config->get_feed_currency() , $currency );
				}
			}
		}

		// when WooCommerce Multi Currency plugin by VillaTheme exists
		if ( is_plugin_active( 'woo-multi-currency/woo-multi-currency.php' ) || is_plugin_active( 'woocommerce-multi-currency/woocommerce-multi-currency.php' ) ) {
			$price = $main_price = wmc_get_price( $price, $config->get_feed_currency() );
			$wmc_currency_params = get_option( 'woo_multi_currency_params' );

			$regular_price = wmc_adjust_fixed_price( json_decode( get_post_meta( $product->get_id(), '_regular_price_wmcp', true ), true ) );
			$sale_price    = wmc_adjust_fixed_price( json_decode( get_post_meta( $product->get_id(), '_sale_price_wmcp', true ), true ) );

			$woocommerce_currency = get_option( 'woocommerce_currency' );

			if( is_plugin_active( 'woo-multi-currency/woo-multi-currency.php' ) ) {
				if ( $config->get_feed_currency() !== $woocommerce_currency ) {
					if( isset( $wmc_currency_params['enable_fixed_price'] ) && $wmc_currency_params['enable_fixed_price'] == 1 ) {
						$price = $this->woo_feed_get_curreny_fixed_price( $price, $product, $config , $regular_price , $sale_price, $price_type);
						$price = ! $price ? $main_price : $price;
					}
				}
			}

			if( is_plugin_active( 'woocommerce-multi-currency/woocommerce-multi-currency.php' ) ) {
				if ( $config->get_feed_currency() !== $woocommerce_currency ) {
					if( isset( $wmc_currency_params['enable_fixed_price'] ) && $wmc_currency_params['enable_fixed_price'] == 1 ) {
						$price = $this->woo_feed_get_curreny_fixed_price( $price, $product, $config , $regular_price , $sale_price, $price_type);
						$price = ! $price ? $main_price : $price;
					}
				}
			}
		}


		if ( is_plugin_active( 'woocommerce-aelia-currencyswitcher/woocommerce-aelia-currencyswitcher.php' ) ) {
			/**
			 * Returns the price of a product in a specific currency.
			 *
			 * @param float $product_price
			 * @param integer $product_id
			 * @param string $currency
			 * @param string $price_type
			 * @return float
			 */
			$aelia_CurrencyPrices_Manager = new WC_Aelia_CurrencyPrices_Manager;
			$price = $aelia_CurrencyPrices_Manager->wc_aelia_cs_get_product_price( $price, $product->get_id(), $config->get_feed_currency(), $price_type );
		}

		return $price;
	}

	public function woo_feed_get_curreny_fixed_price( $price, $product, $config, $regular_price, $sale_price , $price_type ){
		if ( $price_type === 'price' && ! empty( $regular_price ) ) {
			$price = $regular_price[ $config->get_feed_currency() ];
		} else if ( $price_type === 'sale_price' && ! empty( $sale_price ) ) {
			$price = $sale_price[ $config->get_feed_currency() ];
		}

		return $price;
	}
}
