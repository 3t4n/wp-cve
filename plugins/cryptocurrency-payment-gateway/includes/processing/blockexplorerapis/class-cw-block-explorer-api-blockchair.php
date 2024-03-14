<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Blockchair Block Explorer API Class
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage BlockExplorerAPI
 * @author     CryptoWoo AS
 */
abstract class CW_Block_Explorer_API_Blockchair extends CW_Block_Explorer_Base {

	/**
	 * Get the block explorer api block height endpoint format
	 *
	 * @return string
	 */
	protected function get_block_height_endpoint_format() : string {
		return 'https://api.blockchair.com/%1$s/stats' . ( $this->has_api_key() ? '?key=%3$s' : '' );
	}

	/**
	 * Get the block explorer tx api endpoint format
	 *
	 * @return string
	 */
	protected function get_txs_endpoint_format() : string {
		return '%1$s/dashboards/address/%2$s/?transaction_details=true' . ( $this->has_api_key() ? '&key=%3$s' : '' );
	}

	/**
	 * Get the block explorer block hash api endpoint format
	 *
	 * @return string
	 */
	protected function get_block_hash_endpoint_format() : string {
		return '%1$s/dashboards/block/%2$d' . ( $this->has_api_key() ? '?key=%3$s' : '' );
	}

	/**
	 * Get the block explorer txs key name
	 *
	 * @return string
	 */
	protected function get_txs_key_name() : string {
		return 'transactions';
	}

	/**
	 * Get the block explorer txs txid key name
	 *
	 * @return string
	 */
	protected function get_tx_txid_key_name() : string {
		return 'hash';
	}

	/**
	 * Get the block explorer txs confirms key name
	 *
	 * @return string
	 */
	protected function get_tx_confirms_key_name() : string {
		return '';
	}

	/**
	 * Get the block explorer txs amount key name
	 *
	 * @return string
	 */
	protected function get_tx_amount_key_name() : string {
		return 'balance_change';
	}

	/**
	 * Get the block explorer txs locktime key name
	 *
	 * @return string
	 */
	protected function get_tx_locktime_key_name() : string {
		return '';
	}

	/**
	 * Get the block explorer txs timestamp key name
	 *
	 * @return string
	 */
	protected function get_tx_timestamp_key_name() : string {
		return 'time';
	}

	/**
	 * Get the block explorer txs address key name
	 *
	 * @return string
	 */
	protected function get_tx_address_key_name() : string {
		return 'address';
	}

	/**
	 * Get the block explorer tx block height key name
	 *
	 * @return string
	 */
	protected function get_tx_block_height_key_name() : string {
		return 'block_id';
	}

	/**
	 * Get the block explorer tx double spend key name
	 *
	 * @return string
	 */
	protected function get_tx_double_spend_key_name() : string {
		return '';
	}

	/**
	 * Get the block explorer tx inputs key name
	 *
	 * @return string
	 */
	protected function get_tx_inputs_key_name() : string {
		return '';
	}

	/**
	 * Get the block explorer tx outputs key name
	 *
	 * @return string
	 */
	protected function get_tx_outputs_key_name() : string {
		return '';
	}

	/**
	 * Get the block explorer tx input sequence key name
	 *
	 * @return string
	 */
	protected function get_tx_input_sequence_key_name() : string {
		return '';
	}

	/**
	 * Get the block explorer block height key name
	 *
	 * @return string
	 */
	protected function get_block_height_key_name() : string {
		return 'blocks';
	}

	/**
	 * Get the block explorer block hash key name
	 *
	 * @return string
	 */
	protected function get_block_hash_key_name() : string {
		return 'hash';
	}

	/**
	 *
	 * Get the getInfo key name
	 *
	 * @return string
	 */
	protected function get_getinfo_key_name() : string {
		return 'data';
	}

	/**
	 * Get the block explorer max txs allowed in api call
	 *
	 * @return int
	 */
	public function get_api_max_allowed_addresses() : int {
		return 1;
	}

	/**
	 *
	 * Format the data from block explorer result to default data format
	 *
	 * @param stdClass|array $block_explorer_data Json decoded result from block explorer api call.
	 *
	 * @return stdClass|array
	 */
	protected function format_result_from_block_explorer( $block_explorer_data ) {
		if ( isset( $block_explorer_data->{$this->get_getinfo_key_name()} ) ) {
			$block_explorer_data       = $block_explorer_data->{$this->get_getinfo_key_name()};
			$current_address_lowercase = strtolower( $this->get_current_address() );
			if ( isset( $block_explorer_data->{$current_address_lowercase} ) ) {
				// Add address to all txs data.
				foreach ( $block_explorer_data->{$current_address_lowercase}->{$this->get_txs_key_name()} as & $tx ) {
					$tx->{$this->get_tx_address_key_name()} = $this->get_current_address();
				}
				return $block_explorer_data->{$current_address_lowercase};
			}
			return $block_explorer_data;
		}

		return $block_explorer_data;
	}

	/**
	 *
	 * Format the data from block explorer txs result to default data format
	 *
	 * @param stdClass|array $txs_data Json decoded txs result from block explorer api call.
	 *
	 * @return stdClass|array
	 */
	protected function format_txs_result_from_block_explorer( $txs_data ) {
		if ( ! is_array( $txs_data ) ) {
			return $txs_data;
		}

		foreach ( $txs_data as $key => & $tx ) {
			// Negative amounts means outgoing txs so we will remove any of those.
			if ( isset( $tx->{$this->get_tx_amount_key_name()} ) && $tx->{$this->get_tx_amount_key_name()} < 0 ) {
				unset( $txs_data[ $key ] );
				continue;
			}

			// Convert time string to timestamp.
			$tx->{$this->get_tx_timestamp_key_name()} = $this->convert_iso_to_timestamp( $tx->{$this->get_tx_timestamp_key_name()}, 'Y-m-d H:i:s' );
		}

		return $txs_data;
	}
}
