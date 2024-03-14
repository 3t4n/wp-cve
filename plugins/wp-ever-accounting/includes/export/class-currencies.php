<?php
/**
 * Handle currency export.
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Export
 */

namespace EverAccounting\Export;

use EverAccounting\Abstracts\CSV_Exporter;

defined( 'ABSPATH' ) || exit();

/**
 * Class Currencies
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Export
 */
class Currencies extends CSV_Exporter {

	/**
	 * Our export type. Used for export-type specific filters/actions.
	 *
	 * @since 1.0.2
	 * @var string
	 */
	public $export_type = 'currencies';


	/**
	 * Return an array of columns to export.
	 *
	 * @return array
	 * @since  1.0.2
	 */
	public function get_columns() {
		return eaccounting_get_io_headers( 'currency' );
	}

	/**
	 * Get export data.
	 *
	 * @return array
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
		$args  = apply_filters( 'eaccounting_currency_export_query_args', $args );
		$items = eaccounting_get_currencies( $args );
		$rows  = array();

		foreach ( $items as $item ) {
			$rows[] = $this->generate_row_data( $item );
		}

		return $rows;
	}


	/**
	 * Take a currency and generate row data from it for export.
	 *
	 * @param \EverAccounting\Models\Currency $item Currency object.
	 *
	 * @return array
	 */
	protected function generate_row_data( $item ) {
		$props = array();
		foreach ( $this->get_columns() as $column => $label ) {
			$value = null;
			switch ( $column ) {
				case 'name':
					$value = $item->get_name();
					break;
				case 'code':
					$value = $item->get_code();
					break;
				case 'rate':
					$value = $item->get_rate();
					break;
				case 'precision':
					$value = $item->get_precision();
					break;
				case 'symbol':
					$value = $item->get_symbol();
					break;
				case 'position':
					$value = $item->get_position();
					break;
				case 'decimal_separator':
					$value = $item->get_decimal_separator();
					break;
				case 'thousand_separator':
					$value = $item->get_thousand_separator();
					break;
				case 'enabled':
					$value = $item->get_enabled();
					break;
				default:
					$value = apply_filters( 'eaccounting_currency_csv_row_item', '', $column, $item, $this );
			}

			$props[ $column ] = $value;
		}

		return $props;
	}
}
