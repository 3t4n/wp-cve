<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}// Exit if accessed directly

/**
 * Bitcoin Verde Block Explorer API Class (supports Bitcore Insight version 8)
 *
 * @category   CryptoWoo
 * @package    OrderProcessing
 * @subpackage BlockExplorerAPI
 * @author     CryptoWoo AS
 */
abstract class CW_Block_Explorer_API_Bitcoin_Verde extends CW_Block_Explorer_Base {

	/**
	 *
	 * Get the formatting of currency pair for exchange API
	 *
	 * @return string
	 */
	protected function get_txs_endpoint_format() : string {
		return 'search/?query=%2$s';
	}

	/**
	 *
	 * Get the block explorer api block height endpoint format
	 *
	 * @return string
	 */
	protected function get_block_height_endpoint_format() : string {
		return 'status';
	}

	/**
	 *
	 * Get the block explorer block hash api endpoint format
	 *
	 * @return string
	 */
	protected function get_block_hash_endpoint_format() : string {
		return 'blocks?blockHeight=%2$d&maxBlockCount=1';
	}

	/**
	 *
	 * Get the block explorer block height by block hash api endpoint format
	 *
	 * @return string
	 */
	protected function get_block_height_by_block_hash_endpoint_format() : string {
		return 'search?query=%1$s';
	}

	/**
	 *
	 * Get the block explorer double spend proofs api endpoint format
	 *
	 * @return string
	 */
	protected function get_double_spend_proofs_endpoint_format(): string {
		return 'transactions/%1$s/double-spend-proofs';
	}

	/**
	 *
	 * Get the block explorer txs key name
	 *
	 * @return string
	 */
	protected function get_txs_key_name() : string {
		return 'transactions';
	}

	/**
	 *
	 * Get the block explorer block height key name
	 *
	 * @return string
	 */
	protected function get_block_height_key_name() : string {
		return 'blockHeight';
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
	 * Get the getInfo key name
	 *
	 * @return string
	 */
	protected function get_getinfo_key_name() : string {
		return 'statistics';
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
		return 'confirms';
	}

	/**
	 *
	 * Get the block explorer txs amount key name
	 *
	 * @return string
	 */
	protected function get_tx_amount_key_name() : string {
		return 'amount';
	}

	/**
	 *
	 * Get the block explorer txs locktime key name
	 *
	 * @return string
	 */
	protected function get_tx_locktime_key_name() : string {
		return 'lockTime';
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
		return 'height';
	}

	/**
	 *
	 * Get the block explorer tx double spend key name
	 *
	 * @return string
	 */
	protected function get_tx_double_spend_key_name() : string {
		return 'wasDoubleSpent';
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
		return 'outputs';
	}

	/**
	 *
	 * Get the block explorer tx input sequence key name
	 *
	 * @return string
	 */
	protected function get_tx_input_sequence_key_name() : string {
		return 'sequenceNumber';
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
	 * Is the block explorer crypto amount in satoshi (1e8)?
	 * Default is true (amount is satoshi ).
	 *
	 * @return bool
	 */
	protected function amount_from_api_is_satoshi(): bool {
		return true;
	}

	/**
	 *
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
		if ( ! is_object( $block_explorer_data ) ) {
			return $block_explorer_data;
		}

		$object_type = isset( $block_explorer_data->objectType ) ? $block_explorer_data->objectType : ''; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

		if ( isset( $block_explorer_data->object ) ) {
			$block_explorer_data = $block_explorer_data->object;
		}

		if ( isset( $block_explorer_data->statistics ) ) {
			$block_explorer_data = $block_explorer_data->statistics;
		}

		if ( isset( $block_explorer_data->blockHeaders ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$block_explorer_data = $block_explorer_data->blockHeaders; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			if ( is_array( $block_explorer_data ) && ! empty( $block_explorer_data ) ) {
				$block_explorer_data = current( $block_explorer_data );
			}
		}

		// Remove transactions in block result to avoid returning txs instead of block height in validate_api_result().
		if ( 'BLOCK' === $object_type && isset( $block_explorer_data->{$this->get_txs_key_name()} ) ) {
			unset( $block_explorer_data->{$this->get_txs_key_name()} );
		}

		// We will only proceed to format txs from block explorer result if this is an address object result with txs.
		if ( 'ADDRESS' !== $object_type ) {
			return $block_explorer_data;
		}
		if ( ! isset( $block_explorer_data->{$this->get_txs_key_name()} ) || ! is_array( $block_explorer_data->{$this->get_txs_key_name()} ) ) {
			return $block_explorer_data;
		}

		foreach ( $block_explorer_data->{$this->get_txs_key_name()} as & $txs_data ) {
			// Calculate the amount by output.
			if ( isset( $txs_data->outputs ) ) {
				$txs_data->{$this->get_tx_amount_key_name()} = $this->get_sum_outputs( $this->get_current_address(), $txs_data->outputs );
			}

			// Add address to the transaction result.
			$txs_data->{$this->get_tx_address_key_name()} = $this->get_current_address();

			// Extract sequence number from object to convert to expected format.
			if ( isset( $txs_data->{$this->get_tx_inputs_key_name()} ) && is_array( $txs_data->{$this->get_tx_inputs_key_name()} ) ) {
				foreach ( $txs_data->{$this->get_tx_inputs_key_name()} as $input ) {
					if ( isset( $input->{$this->get_tx_input_sequence_key_name()} ) ) {
						$sequence_number_object = & $input->{$this->get_tx_input_sequence_key_name()};
						if ( $sequence_number_object instanceof stdClass && isset( $sequence_number_object->value ) ) {
							$sequence_number_object = $sequence_number_object->value;
						}
					}
				}
			}

			// Extract locktime from object to convert to expected format.
			if ( isset( $txs_data->{$this->get_tx_locktime_key_name()} ) && is_object( $txs_data->{$this->get_tx_locktime_key_name()} ) ) {
				$locktime_object = & $txs_data->{$this->get_tx_locktime_key_name()};
				if ( $locktime_object instanceof stdClass && isset( $locktime_object->value ) ) {
					$locktime_object = $locktime_object->value;
				}
			}

			// Convert wasDoubleSpent null value to false to ensure the filter does not remove this variable from the resultset.
			if ( property_exists( $txs_data, $this->get_tx_double_spend_key_name() ) && null === $txs_data->{$this->get_tx_double_spend_key_name()} ) {
				$txs_data->{$this->get_tx_double_spend_key_name()} = (bool) $txs_data->{$this->get_tx_double_spend_key_name()};
			}

			// Block explorer processing class calculates the number of confirms from the block height of the completed tx vs current height, so lets just set it to null now.
			if ( ! isset( $txs_data->{$this->get_tx_confirms_key_name()} ) ) {
				$txs_data->{$this->get_tx_confirms_key_name()} = null;
			}

			// Set the time and height initially to unset as we will get this from the block data in the separate api call below if the tx is in a block.
			$txs_data->{$this->get_tx_timestamp_key_name()}    = false; // Timestamp false is used in tx analysis for unknown time of tx.
			$txs_data->{$this->get_tx_block_height_key_name()} = -1; // Block height -1 is used in tx analysis for unconfirmed txs.

			// Find the timestamp, and the block height that the tx was confirmed in for calculation of number of confirms.
			if ( isset( $txs_data->blocks ) && is_array( $txs_data->blocks ) && ! empty( $txs_data->blocks ) ) {
				$hash       = current( $txs_data->blocks );
				$block_data = $this->get_block_data_by_block_hash( $hash );
				if ( is_object( $block_data ) ) {
					if ( isset( $block_data->height ) && is_integer( $block_data->height ) ) {
						$txs_data->{$this->get_tx_block_height_key_name()} = $block_data->height;
					}
					if ( isset( $block_data->timestamp ) && is_object( $block_data->timestamp ) ) {
						$timestamp_object = $block_data->timestamp;
						if ( $timestamp_object instanceof stdClass && isset( $timestamp_object->value ) ) {
							$txs_data->{$this->get_tx_timestamp_key_name()} = $timestamp_object->value;
						}
					}
				}
			}
		}

		return $block_explorer_data;
	}

	/**
	 * Get block data by block hash from block explorer api
	 *
	 * @param string $hash The block hash to lookup block height from.
	 *
	 * @return int|false
	 */
	public function get_block_data_by_block_hash( $hash ) {
		return $this->get_api_data( $this->get_block_height_by_block_hash_url( $hash ), '', __FUNCTION__ );
	}

	/**
	 * Get the formatted block height by block hash URL
	 *
	 * @param string $hash The block hash to lookup block height from.
	 *
	 * @return string
	 */
	public function get_block_height_by_block_hash_url( $hash ) : string {
		return $this->format_api_url( $this->get_block_height_by_block_hash_endpoint_format(), $hash );
	}

	/**
	 * Get double spend proofs by transaction id from block explorer api
	 *
	 * @param string $tx_id The tx_id to lookup double spend proofs from.
	 *
	 * @return bool|null
	 */
	public function get_is_double_spend( $tx_id ) {
		// Description: https://www.reddit.com/r/btc/comments/mgfuer/bitcoin_verde_has_finished_our_first_feature_of/gsv3ebw/?context=3.
		$data = $this->get_api_data( $this->get_double_spend_proofs_url( $tx_id ), '', __FUNCTION__ );

		// Convert double spend proofs to correct format if this is a double spend proofs result.
		// Not success indicates the tx was not the one first seen by the node, and likely the actual double-spend tx.
		if ( isset( $data->wasSuccess ) && ! $data->wasSuccess ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			return true;
		}

		// Empty array in doubleSpendProofs means no double-spend detected.
		if ( isset( $data->doubleSpendProofs ) && is_array( $data->doubleSpendProofs ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			return ! empty( $data->doubleSpendProofs ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		}

		// Returning null to indicate we do not know if the result was double-spend false or true. TODO: error logging.
		return null;
	}

	/**
	 *
	 * Get the formatted double spend proofs URL
	 *
	 * @param string $txid The txid to lookup double spend proofs from.
	 *
	 * @return string
	 */
	public function get_double_spend_proofs_url( string $txid ) : string {
		return $this->format_api_url( $this->get_double_spend_proofs_endpoint_format(), $txid );
	}

	/**
	 * Add up all output amounts in "outputs" from Bitcoin Verde API response objects and convert to integer.
	 *
	 * @param string $address The cryptocurrency address in output to look for.
	 * @param array  $outputs The array of outputs in the transaction.
	 *
	 * @return int
	 */
	protected function get_sum_outputs( $address, $outputs = array() ) {
		$amount_received = 0;

		foreach ( $outputs as $output ) {
			$output_address = is_string( $output->{$this->get_tx_address_key_name()} ) ? $output->{$this->get_tx_address_key_name()} : '';
			if ( $address === $output_address && isset( $output->{$this->get_tx_amount_key_name()} ) ) {
				$amount_received += (int) round( (float) $output->{$this->get_tx_amount_key_name()} );
			}
		}

		return $amount_received;
	}
}
