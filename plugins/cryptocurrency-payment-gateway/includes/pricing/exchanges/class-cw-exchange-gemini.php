<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Gemini Exchange Rates Class
 *
 * @category   CryptoWoo
 * @package    Exchange
 * @subpackage ExchangeBase
 * Author: CryptoWoo AS
 * Author URI: https://cryptowoo.com
 */
class CW_Exchange_Gemini extends CW_Exchange_Base {

	/**
	 * Get the exchange API URL with format
	 *
	 * @return string
	 */
	protected function get_exchange_url_format() : string {
		return 'https://api.gemini.com/v2/ticker/%s';
	}

	/**
	 * Get the exchange price index (last index)
	 *
	 * @return string
	 */
	protected function get_exchange_price_index() : string {
		return 'close';
	}

	/**
	 * Get the formatting of currency pair for exchange API
	 *
	 * @return string
	 */
	protected function get_pair_format() : string {
		return '%2$s%1$s';
	}
}
