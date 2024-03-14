<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Blockchair.com Block Explorer API Class
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage BlockExplorerAPI
 * @author     CryptoWoo AS
 */
class CW_Block_Explorer_Blockchair extends CW_Block_Explorer_API_Blockchair {

	/**
	 * Get the block explorer API URL with format
	 *
	 * @return string
	 */
	protected function get_base_url() : string {
		return 'https://api.blockchair.com/';
	}

	/**
	 * Get the block explorer supported currencies
	 *
	 * @return string[]
	 */
	protected function get_supported_currencies() : array {
		return array( 'BTC', 'BCH', 'ETH', 'LTC', 'BSV', 'DOGE', 'DASH', 'GRS', 'XLM', 'XMR', 'ADA', 'ZEC', 'XIN', 'XTZ', 'EOS', 'XEC' );
	}

	/**
	 *
	 * Create and return search currency ID for exchange API (for exchanges that use ID instead of currency code)
	 * Default is empty array because most exchanges does not use ids but currency code.
	 * Note that this function should be set to final in exchange classes if used.
	 *
	 * @return array
	 */
	protected function get_search_currency_ids() : array {
		return array(
			'BTC'  => 'bitcoin',
			'BCH'  => 'bitcoin-cash',
			'ETH'  => 'ethereum',
			'LTC'  => 'litecoin',
			'BSV'  => 'bitcoin-sv',
			'DOGE' => 'dogecoin',
			'DASH' => 'dash',
			'XRP'  => 'ripple',
			'GRS'  => 'groestlcoin',
			'XLM'  => 'stellar',
			'XMR'  => 'monero',
			'ADA'  => 'cardano',
			'ZEC'  => 'zcash',
			'XIN'  => 'mixin',
			'XTZ'  => 'tezos',
			'EOS'  => 'eos',
			'XEC'  => 'ecash',
		);
	}
}
