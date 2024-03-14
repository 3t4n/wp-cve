<?php
/**
 * Handle payment import.
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Import
 */

namespace EverAccounting\Import;

use EverAccounting\Abstracts\CSV_Importer;
use EverAccounting\Models\Currency;

defined( 'ABSPATH' ) || exit();


/**
 * Class Payments
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Import
 */
class Payments extends CSV_Importer {
	/**
	 * Get supported key and readable label.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_headers() {
		return eaccounting_get_io_headers( 'payment' );
	}

	/**
	 * Return the required key to import item.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function get_required() {
		return array( 'payment_date', 'currency_code', 'account_name', 'category_name', 'payment_method' );
	}

	/**
	 * Get formatting callback.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_formatting_callback() {
		return array(
			'payment_date'   => array( $this, 'parse_date_field' ),
			'amount'         => array( $this, 'parse_text_field' ),
			'currency_code'  => array( $this, 'parse_currency_code_field' ),
			'currency_rate'  => array( $this, 'parse_float_field' ),
			'account_name'   => array( $this, 'parse_text_field' ),
			'vendor_name'    => array( $this, 'parse_text_field' ),
			'category_name'  => array( $this, 'parse_text_field' ),
			'description'    => array( $this, 'parse_description_field' ),
			'payment_method' => array( $this, 'parse_text_field' ),
			'reference'      => array( $this, 'parse_text_field' ),
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
		if ( empty( $data['payment_date'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Payment Date', 'wp-ever-accounting' ) );
		}
		if ( empty( $data['account_name'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Account Name', 'wp-ever-accounting' ) );
		}
		if ( empty( $data['currency_code'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Currency Code', 'wp-ever-accounting' ) );
		}
		if ( empty( $data['category_name'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Category Name', 'wp-ever-accounting' ) );
		}
		if ( empty( $data['payment_method'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Payment Method', 'wp-ever-accounting' ) );
		}

		$category    = eaccounting_get_categories(
			array(
				'search'      => $data['category_name'],
				'search_cols' => array( 'name' ),
				'type'        => 'expense',
			)
		);
		$category    = ! empty( $category ) ? reset( $category ) : '';
		$category_id = ! empty( $category ) ? $category->get_id() : '';

		$currency = new Currency( array( 'code' => $data['currency_code'] ) );

		$account               = eaccounting_get_accounts(
			array(
				'search'      => $data['account_name'],
				'search_cols' => array( 'name' ),
			)
		);
		$account               = ! empty( $account ) ? reset( $account ) : '';
		$account_id            = ! empty( $account ) ? $account->get_id() : '';
		$account_currency_code = ! empty( $account ) ? $account->get_currency_code() : '';

		$vendor    = ( '' !== $data['vendor_name'] ) ? eaccounting_get_vendors(
			array(
				'search'      => $data['vendor_name'],
				'search_cols' => array( 'name' ),
			)
		) : '';
		$vendor    = ! empty( $vendor ) ? reset( $vendor ) : '';
		$vendor_id = ! empty( $vendor ) ? $vendor->get_id() : '';

		if ( empty( $category_id ) ) {
			return new \WP_Error( 'invalid_props', __( 'Category does not exist.', 'wp-ever-accounting' ) );
		}

		if ( ! $currency->exists() ) {
			return new \WP_Error( 'invalid_props', __( 'Currency Code not exists', 'wp-ever-accounting' ) );
		}

		if ( empty( $account_id ) ) {
			return new \WP_Error( 'invalid_props', __( 'Transaction associated account is not exist.', 'wp-ever-accounting' ) );
		}

		if ( $data['currency_code'] !== $account_currency_code ) {
			return new \WP_Error( 'invalid_props', __( 'Account currency code does not match with provided currency code.', 'wp-ever-accounting' ) );
		}

		$data['category_id'] = $category_id;
		$data['account_id']  = $account_id;
		$data['type']        = 'expense';
		$data['contact_id']  = $vendor_id;

		return eaccounting_insert_payment( $data );
	}

}
