<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Blockchain.info Haskoin Block Explorer API Class
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage BlockExplorerAPI
 * @author     CryptoWoo AS
 */
class CW_Block_Explorer_BlockchainInfo extends CW_Block_Explorer_API_Haskoin {


	/**
	 *
	 * Get the block explorer name in nice format.
	 *
	 * @return string
	 */
	public function get_nicename() : string {
		return 'Blockchain.info';
	}

	/**
	 *
	 * Get the block explorer API URL with format
	 *
	 * @return string
	 */
	protected function get_base_url() : string {
		return 'https://api.blockchain.info/haskoin-store/';
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
