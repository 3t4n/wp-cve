<?php

namespace CTXFeed\V5\Compatibility;

use Wdr\App\Controllers\Configuration;
use function WPML\FP\partialRight;

class WooDiscountRulesFlycart
{

	public function woo_discount_rules_flycart( $price, $product, $config, $price_type ) {

		$base_price               = $price;

		$wpml_active_currency_status = ( is_plugin_active( 'woocommerce-multilingual/wpml-woocommerce.php' ) && $config->get_feed_currency() !== get_woocommerce_currency() );
		if ( $wpml_active_currency_status ) {
			//Wpml custom price start
			$wcmlCurrency  = new WCMLCurrency();
			$original_id = $wcmlCurrency->woo_feed_wpml_get_original_post_id( $product->get_id() );

			$wpml_regular_price = get_post_meta($original_id, '_regular_price_' . $config->get_feed_currency(), false );
			$wpml_sale_price = get_post_meta($original_id, '_sale_price_' . $config->get_feed_currency(), false );
			$wpml_data     = get_option( '_wcml_settings' );
			$exchange_rate = $wpml_data['currency_options'][ $config->get_feed_currency() ]['rate'];

			if( count( $wpml_regular_price ) >= 1 ) {
				$wpml_regular_price = floatval($wpml_regular_price[0]) / floatval( $exchange_rate );
				$wpml_sale_price = floatval($wpml_sale_price[0]) / floatval( $exchange_rate );
			}
			//Wpml custom price end
			if ( $exchange_rate !== 0 ) {
				$exchange_rate = $base_price = floatval( $price ) / floatval( $exchange_rate );
			}
		} else {
			$exchange_rate = $product->get_regular_price();
		}

		if ( class_exists( 'Wdr\App\Controllers\Configuration' ) ) {
			$discount_config = Configuration::getInstance()->getConfig( 'calculate_discount_from', 'sale_price' );
			if ( isset( $discount_config ) && ! empty( $discount_config ) ) {
				if ( 'regular_price' === $discount_config ) {
					$price = $product->get_regular_price();
					if( $wpml_active_currency_status ) {
						$price = $wpml_regular_price;
					}
				} elseif ( 'sale_price' === $discount_config ) {
					$price = $product->get_sale_price();
					if( $wpml_active_currency_status ) {
						$price = $wpml_sale_price;
					}
				}
				else {
					$price = $exchange_rate;
				}
			}
			else {
				$price = $exchange_rate;
			}

			if ( $product->is_type( 'variable' ) ) {
				$min = $product->get_variation_price( 'min', false );
				$max = $product->get_variation_price( 'max', false );

				$price = $min;
				if ( $max === $base_price ) {
					$price = $max;
				}
			}

			$price = apply_filters( 'advanced_woo_discount_rules_get_product_discount_price_from_custom_price', false, $product, 1, $price, 'discounted_price', true, true );

			if ( empty( $price ) ) {
				$price = $base_price;
			}

			$price = apply_filters( 'wcml_raw_price_amount', $price, $config->get_feed_currency() );
		}

		return $price;
	}

}
