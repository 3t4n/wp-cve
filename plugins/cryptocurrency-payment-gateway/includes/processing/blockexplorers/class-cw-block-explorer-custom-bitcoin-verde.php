<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Custom Bitcoin Verde Block Explorer API Class
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage BlockExplorerAPI
 * @author     CryptoWoo AS
 */
class CW_Block_Explorer_Custom_Bitcoin_Verde extends CW_Block_Explorer_API_Bitcoin_Verde {


	/**
	 *
	 * Get the block explorer API URL with format
	 *
	 * @return string
	 */
	protected function get_base_url() : string {
		$lc_currency = strtolower( str_replace( 'TEST', '', $this->get_currency_name() ) );

		return cw_get_option( "custom_bitcoin_verde_api_$lc_currency" );
	}

	/**
	 *
	 * Get the block explorer supported currencies
	 *
	 * @return string[]
	 */
	protected function get_supported_currencies(): array {
		return array( $this->get_currency_name() );
	}
}
