<?php
/**
 * Handle accounts import.
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Import
 */

namespace EverAccounting\Import;

use EverAccounting\Abstracts\CSV_Importer;

defined( 'ABSPATH' ) || exit();


/**
 * Class Accounts
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Import
 */
class Accounts extends CSV_Importer {
	/**
	 * Get supported key and readable label.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_headers() {
		return eaccounting_get_io_headers( 'account' );
	}


	/**
	 * Return the required key to import item.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function get_required() {
		return array( 'name', 'number', 'currency_code' );
	}

	/**
	 * Get formatting callback.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_formatting_callback() {
		return array(
			'name'            => array( $this, 'parse_text_field' ),
			'number'          => array( $this, 'parse_text_field' ),
			'currency_code'   => array( $this, 'parse_currency_code_field' ),
			'opening_balance' => array( $this, 'parse_float_field' ),
			'bank_name'       => array( $this, 'parse_text_field' ),
			'bank_phone'      => array( $this, 'parse_text_field' ),
			'bank_address'    => array( $this, 'parse_description_field' ),
		);
	}

	/**
	 * Process a single item and save.
	 *
	 * @param array $data Raw CSV data.
	 *
	 * @return string|\WP_Error
	 */
	protected function import_item( $data ) {
		if ( empty( $data['name'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Account Name', 'wp-ever-accounting' ) );
		}

		if ( empty( $data['number'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Account Number', 'wp-ever-accounting' ) );
		}

		if ( empty( $data['currency_code'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Currency Code', 'wp-ever-accounting' ) );
		}

		$currency = new \EverAccounting\Models\Currency( array( 'code' => $data['currency_code'] ) );

		if ( ! $currency->exists() ) {
			return new \WP_Error( 'invalid_prop', __( 'Currency with provided code does not not exist.', 'wp-ever-accounting' ) );
		}

		return eaccounting_insert_account( $data );
	}
}
