<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Haskoin Block Explorer API Class
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage BlockExplorerAPI
 * @author     CryptoWoo AS
 */
abstract class CW_Block_Explorer_API_Haskoin extends CW_Block_Explorer_Base {

	/**
	 *
	 * Get the formatting of currency pair for exchange API
	 *
	 * @return string
	 */
	protected function get_txs_endpoint_format() : string {
		// Argument filter 2 means "Only incoming transactions (received) (result + fee > 0)".
		// Argument txidindex true means "Use string with hex-encoded txid in tx_index field".
		// Argument cashaddr false means "Show Bitcoin Cash addresses in CashAddr format".
		return '%1$s/blockchain/multiaddr?active=%2$s&cashaddr=false&txidindex=true&filter=2';
	}

	/**
	 *
	 * Get the block explorer api block height endpoint format
	 *
	 * @return string
	 */
	protected function get_block_height_endpoint_format() : string {
		return '%1$s/blockchain/q/getblockcount';
	}

	/**
	 *
	 * Get the block explorer block hash api endpoint format
	 *
	 * @return string
	 */
	protected function get_block_hash_endpoint_format() : string {
		return '%1$s/blockchain/rawblock/%2$d?format=json';
	}

	/**
	 *
	 * Get the block explorer txs key name
	 *
	 * @return string
	 */
	protected function get_txs_key_name() : string {
		return 'txs';
	}

	/**
	 *
	 * Get the block explorer block height key name
	 *
	 * @return string
	 */
	protected function get_block_height_key_name() : string {
		return '';
	}

	/**
	 *
	 * Get the block explorer block hash key name
	 *
	 * @return string
	 */
	protected function get_block_hash_key_name() : string {
		return 'hash';
	}

	/**
	 *
	 * Get the block explorer txs txid key name
	 *
	 * @return string
	 */
	protected function get_tx_txid_key_name() : string {
		return 'hash';
	}

	/**
	 *
	 * Get the block explorer txs confirms key name
	 *
	 * @return array
	 */
	protected function get_tx_confirms_key_name() : string {
		return '';
	}

	/**
	 *
	 * Get the block explorer txs amount key name
	 *
	 * @return string
	 */
	protected function get_tx_amount_key_name() : string {
		return 'result';
	}

	/**
	 *
	 * Get the block explorer txs locktime key name
	 *
	 * @return string
	 */
	protected function get_tx_locktime_key_name() : string {
		return 'lock_time';
	}

	/**
	 *
	 * Get the block explorer txs timestamp key name
	 *
	 * @return string
	 */
	protected function get_tx_timestamp_key_name() : string {
		return 'time';
	}

	/**
	 *
	 * Get the block explorer tx block height key name
	 *
	 * @return string
	 */
	protected function get_tx_block_height_key_name() : string {
		return 'block_height';
	}

	/**
	 *
	 * Get the block explorer tx double spend key name
	 *
	 * @return string
	 */
	protected function get_tx_double_spend_key_name() : string {
		return 'double_spend';
	}

	/**
	 *
	 * Get the block explorer tx inputs key name
	 *
	 * @return string
	 */
	protected function get_tx_inputs_key_name() : string {
		return 'inputs';
	}

	/**
	 *
	 * Get the block explorer tx outputs key name
	 *
	 * @return string
	 */
	protected function get_tx_outputs_key_name() : string {
		return 'out';
	}

	/**
	 *
	 * Get the block explorer tx input sequence key name
	 *
	 * @return string
	 */
	protected function get_tx_input_sequence_key_name() : string {
		return 'sequence';
	}

	/**
	 *
	 * Get the block explorer txs address key name
	 *
	 * @return string
	 */
	protected function get_tx_address_key_name() : string {
		return 'address';
	}

	/**
	 *
	 * Get the block explorer max txs allowed in api call
	 *
	 * @return int
	 */
	public function get_api_max_allowed_addresses() : int {
		// Important: do not change this before we can differ addresses from txs in Blockchain.info response, and update code!
		return 1;
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
		foreach ( $txs_data as $index => & $tx ) {
			// Remove the outgoing txs (negative amount change).
			if ( 0 > $tx->{$this->get_tx_amount_key_name()} ) {
				unset( $txs_data[ $index ] );
				continue;
			}

			// We only query one address from Blockchain.info for now so lets make it simple and att it to tx result.
			$tx->{$this->get_tx_address_key_name()} = $this->get_current_address();

		}

		// Reindex the array (index 0, 1, 2 etc).
		return array_values( $txs_data );
	}
}
