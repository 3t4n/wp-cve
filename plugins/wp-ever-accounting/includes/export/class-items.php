<?php
/**
 * Handle items export.
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Export
 */

namespace EverAccounting\Export;

use EverAccounting\Abstracts\CSV_Exporter;

defined( 'ABSPATH' ) || exit();


/**
 * Class Items
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Export
 */
class Items extends CSV_Exporter {

	/**
	 * Our export type. Used for export-type specific filters/actions.
	 *
	 * @since 1.0.2
	 * @var string
	 */
	public $export_type = 'items';


	/**
	 * Return an array of columns to export.
	 *
	 * @return array
	 * @since  1.0.2
	 */
	public function get_columns() {
		return eaccounting_get_io_headers( 'item' );
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
		$args  = apply_filters( 'eaccounting_item_export_query_args', $args );
		$items = eaccounting_get_items( $args );
		$rows  = array();

		foreach ( $items as $item ) {
			$rows[] = $this->generate_row_data( $item );
		}

		return $rows;
	}

	/**
	 * Take a item and generate row data from it for export.
	 *
	 * @param \EverAccounting\Models\Item $item Item object.
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
				case 'category_name':
					$category = eaccounting_get_category( $item->get_category_id() );
					$value    = $category ? $category->get_name() : '';
					break;
				case 'sale_price':
					$value = $item->get_sale_price();
					break;
				case 'purchase_price':
					$value = $item->get_purchase_price();
					break;
				case 'sales_tax':
					$value = $item->get_sales_tax();
					break;
				case 'purchase_tax':
					$value = $item->get_purchase_tax();
					break;
				default:
					$value = apply_filters( 'eaccounting_item_csv_row_item', '', $column, $item, $this );
			}

			$props[ $column ] = $value;
		}

		return $props;
	}
}
