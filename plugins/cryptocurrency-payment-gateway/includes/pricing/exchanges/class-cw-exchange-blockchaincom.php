<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Blockchain.com Exchange Rates Class
 *
 * @category   CryptoWoo
 * @package    Exchange
 * @subpackage ExchangeBase
 * Author: CryptoWoo AS
 * Author URI: https://cryptowoo.com
 */
class CW_Exchange_BlockchainCom extends CW_Exchange_Base {

	/**
	 *
	 * Get the exchange name in nice format.
	 *
	 * @return string
	 */
	public function get_exchange_nicename() : string {
		return 'Blockchain.com';
	}

	/**
	 * Get the exchange API URL with format
	 *
	 * @return string
	 */
	protected function get_exchange_url_format() : string {
		return 'https://api.blockchain.com/v3/exchange/tickers/%s';
	}

	/**
	 * Get the exchange price index (last index)
	 *
	 * @return string
	 */
	protected function get_exchange_price_index() : string {
		return 'last_trade_price';
	}

	/**
	 * Get the formatting of currency pair for exchange API
	 *
	 * @return string
	 */
	protected function get_pair_format() : string {
		return '%2$s-%1$s';
	}

	/**
	 *
	 * Get the stale index in exchange result.
	 * Default is '' (no stale index in result)
	 *
	 * @return string
	 */
	protected function get_exchange_stale_index() : string {
		return 'has_no_volume';
	}

	/**
	 *
	 * Is the exchange rate search pair uppercase or lowercase in the api url?
	 *
	 * @return bool
	 */
	protected function search_pair_is_uppercase() : bool {
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
		$price_data->{$this->get_exchange_stale_index()} = empty( $price_data->volume_24h );

		return $price_data;
	}
}
