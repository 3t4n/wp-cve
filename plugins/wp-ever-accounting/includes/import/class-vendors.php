<?php
/**
 * Handle vendors import.
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Import
 */

namespace EverAccounting\Import;

use EverAccounting\Abstracts\CSV_Importer;

defined( 'ABSPATH' ) || exit();


/**
 * Class Vendors
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Import
 */
class Vendors extends CSV_Importer {
	/**
	 * Get supported key and readable label.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_headers() {
		return eaccounting_get_io_headers( 'vendor' );
	}


	/**
	 * Return the required key to import item.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function get_required() {
		return array( 'name', 'currency_code' );
	}

	/**
	 * Get formatting callback.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_formatting_callback() {
		return array(
			'email'         => 'sanitize_email',
			'birth_date'    => array( $this, 'parse_date_field' ),
			'address'       => array( $this, 'parse_description_field' ),
			'country'       => array( $this, 'parse_country_field' ),
			'website'       => 'esc_url_raw',
			'currency_code' => array( $this, 'parse_currency_code_field' ),
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
			return new \WP_Error( 'empty_prop', __( 'Empty Name', 'wp-ever-accounting' ) );
		}
		if ( empty( $data['currency_code'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Currency Code', 'wp-ever-accounting' ) );
		}

		$data['type'] = 'vendor';

		return eaccounting_insert_vendor( $data );
	}
}
