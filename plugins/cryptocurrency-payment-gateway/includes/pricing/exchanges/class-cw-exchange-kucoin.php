<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * KuCoin Exchange Rates Class
 *
 * @category   CryptoWoo
 * @package    Exchange
 * @subpackage ExchangeBase
 * Author: CryptoWoo AS
 * Author URI: https://cryptowoo.com
 */
class CW_Exchange_KuCoin extends CW_Exchange_Base {

	/**
	 *
	 * Get the exchange API URL
	 *
	 * @return string
	 */
	protected function get_exchange_url_format() : string {
		return 'https://api.kucoin.com/api/v1/market/orderbook/level1?symbol=%s';
	}

	/**
	 *
	 * Get the exchange price index (last index)
	 *
	 * @return string
	 */
	protected function get_exchange_price_index() : string {
		return 'price';
	}

	/**
	 *
	 * Get the formatting of currency pair for exchange API
	 *
	 * @return string
	 */
	protected function get_pair_format() : string {
		return '%2$s-%1$s';
	}

	/**
	 *
	 * Is the exchange rate search pair uppercase or lowercase in the api url?
	 * Default is lower case.
	 *
	 * @return bool
	 */
	protected function search_pair_is_uppercase(): bool {
		return true;
	}

	/**
	 *
	 * Format the price data from exchange result to default data format
	 *
	 * @param stdClass $price_data Json decoded result from exchange api call.
	 *
	 * @return stdClass
	 */
	protected function format_price_data_from_exchange( stdClass $price_data ) : stdClass {
		if ( isset( $price_data->data ) && $price_data->data instanceof stdClass ) {
			if ( isset( $price_data->data->time ) ) {
				$price_data->data->timestamp = $price_data->data->time;
			}

			return $price_data->data;
		}

		return $price_data;
	}
}
