<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Haskoin.com Haskoin Block Explorer API Class
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage BlockExplorerAPI
 * @author     CryptoWoo AS
 */
class CW_Block_Explorer_Haskoin extends CW_Block_Explorer_API_Haskoin {

	/**
	 *
	 * Get the block explorer API URL with format
	 *
	 * @return string
	 */
	protected function get_base_url() : string {
		return 'https://api.haskoin.com/';
	}

	/**
	 *
	 * Get the block explorer supported currencies
	 *
	 * @return string[]
	 */
	protected function get_supported_currencies(): array {
		return array( 'BTC', 'BCH', 'BTCTEST', 'BCHTEST' );
	}
}
