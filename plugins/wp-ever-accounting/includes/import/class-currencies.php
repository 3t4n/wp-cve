<?php
/**
 * Handle currency import.
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
 * Class Currencies
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Import
 */
class Currencies extends CSV_Importer {
	/**
	 * Get supported key and readable label.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_headers() {
		return eaccounting_get_io_headers( 'currency' );
	}

	/**
	 * Return the required key to import item.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function get_required() {
		return array( 'name', 'code' );
	}

	/**
	 * Get formatting callback.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_formatting_callback() {
		return array(
			'name'               => array( $this, 'parse_text_field' ),
			'code'               => array( $this, 'parse_text_field' ),
			'precision'          => array( $this, 'parse_float_field' ),
			'symbol'             => array( $this, 'parse_text_field' ),
			'position'           => array( $this, 'parse_text_field' ),
			'decimal_separator'  => array( $this, 'parse_text_field' ),
			'thousand_separator' => array( $this, 'parse_text_field' ),
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
		if ( empty( $data['code'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Currency Code', 'wp-ever-accounting' ) );
		}

		$currency = new Currency( array( 'code' => $data['code'] ) );
		if ( $currency->exists() ) {
			return new \WP_Error( 'empty_prop', __( 'Currency already exists', 'wp-ever-accounting' ) );
		}

		return eaccounting_insert_currency( $data );
	}
}
