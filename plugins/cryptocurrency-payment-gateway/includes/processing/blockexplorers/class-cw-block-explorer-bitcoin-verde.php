<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Bitcoin Verde Block Explorer API Class
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage BlockExplorerAPI
 * @author     CryptoWoo AS
 */
class CW_Block_Explorer_Bitcoin_Verde extends CW_Block_Explorer_API_Bitcoin_Verde {


	/**
	 *
	 * Get the block explorer API URL with format
	 *
	 * @return string
	 */
	protected function get_base_url() : string {
		return 'https://explorer.bitcoinverde.org/api/v1/';
	}

	/**
	 *
	 * Get the block explorer supported currencies
	 *
	 * @return string[]
	 */
	protected function get_supported_currencies(): array {
		// TODO: Ethereum has different api url and key names in api responses, keeping disabled until done.
		return array( 'BCH' );
	}
}
