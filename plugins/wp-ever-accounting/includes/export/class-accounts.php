<?php
/**
 * Handle accounts export.
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Export
 */

namespace EverAccounting\Export;

use EverAccounting\Abstracts\CSV_Exporter;

defined( 'ABSPATH' ) || exit();

/**
 * Class Accounts
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Export
 */
class Accounts extends CSV_Exporter {

	/**
	 * Our export type. Used for export-type specific filters/actions.
	 *
	 * @since 1.0.2
	 * @var string
	 */
	public $export_type = 'accounts';


	/**
	 * Return an array of columns to export.
	 *
	 * @return array
	 * @since  1.0.2
	 */
	public function get_columns() {
		return eaccounting_get_io_headers( 'account' );
	}

	/**
	 * Get rows.
	 *
	 * @since 1.0.2
	 */
	public function get_rows() {
		$args  = array(
			'per_page' => $this->limit,
			'page'     => $this->page,
			'orderby'  => 'id',
			'order'    => 'ASC',
			'return'   => 'objects',
			'number'   => - 1,
		);
		$args  = apply_filters( 'eaccounting_account_export_query_args', $args );
		$items = eaccounting_get_accounts( $args );
		$rows  = array();

		foreach ( $items as $item ) {
			$rows[] = $this->generate_row_data( $item );
		}

		return $rows;
	}


	/**
	 * Take a product and generate row data from it for export.
	 *
	 * @param \EverAccounting\Models\Account $item Account object.
	 *
	 * @return array
	 */
	protected function generate_row_data( $item ) {
		$props = [];
		foreach ( $this->get_columns() as $column => $label ) {
			$value = null;
			switch ( $column ) {
				case 'name':
					$value = $item->get_name();
					break;
				case 'number':
					$value = $item->get_number();
					break;
				case 'currency_code':
					$value = $item->get_currency_code();
					break;
				case 'opening_balance':
					$value = $item->get_opening_balance();
					break;
				case 'bank_name':
					$value = $item->get_bank_name();
					break;
				case 'bank_phone':
					$value = $item->get_bank_phone();
					break;
				case 'bank_address':
					$value = $item->get_bank_address();
					break;
				case 'enabled':
					$value = $item->get_enabled();
					break;
				default:
					$value = apply_filters( 'eaccounting_account_csv_row_item', '', $column, $item, $this );
			}

			$props[ $column ] = $value;
		}

		return $props;
	}
}
