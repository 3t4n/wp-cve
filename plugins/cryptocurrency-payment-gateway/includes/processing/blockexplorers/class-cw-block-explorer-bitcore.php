<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Bitcore Insight Block Explorer API Class
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage BlockExplorerAPI
 * @author     CryptoWoo AS
 */
class CW_Block_Explorer_Bitcore extends CW_Block_Explorer_API_Bitcore_Insight {


	/**
	 *
	 * Get the block explorer API URL with format
	 *
	 * @return string
	 */
	protected function get_base_url() : string {
		if ( 'ETH' !== $this->get_search_currency() ) {
			return 'https://api.bitcore.io/api/';
		}

		return 'https://api-eth.bitcore.io/api/';
	}

	/**
	 *
	 * Get the block explorer supported currencies
	 *
	 * @return string[]
	 */
	protected function get_supported_currencies(): array {
		// TODO: Ethereum has different api url and key names in api responses, keeping disabled until done.
		return array( 'BTC', 'BCH', 'LTC', /*'ETH',*/ 'DOGE' );
	}
}
