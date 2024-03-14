<?php
/**
 * Handle items import.
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Import
 */

namespace EverAccounting\Import;

use EverAccounting\Abstracts\CSV_Importer;

defined( 'ABSPATH' ) || exit();


/**
 * Class Items
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Import
 */
class Items extends CSV_Importer {
	/**
	 * Get supported key and readable label.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_headers() {
		return eaccounting_get_io_headers( 'item' );
	}


	/**
	 * Return the required key to import item.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function get_required() {
		return array( 'name', 'sale_price', 'purchase_price' );
	}

	/**
	 * Get formatting callback.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_formatting_callback() {
		return array(
			'name'           => array( $this, 'parse_text_field' ),
			'category_name'  => array( $this, 'parse_text_field' ),
			'sale_price'     => array( $this, 'parse_float_field' ),
			'purchase_price' => array( $this, 'parse_float_field' ),
			'sales_tax'      => array( $this, 'parse_text_field' ),
			'purchase_tax'   => array( $this, 'parse_text_field' ),
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
			return new \WP_Error( 'empty_prop', __( 'Empty Item Name', 'wp-ever-accounting' ) );
		}

		if ( empty( $data['sale_price'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Sale Price', 'wp-ever-accounting' ) );
		}

		if ( empty( $data['purchase_price'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Purchase Price', 'wp-ever-accounting' ) );
		}

		$category    = eaccounting_get_categories(
			array(
				'search' => $data['category_name'],
				'type'   => 'item',
			)
		);
		$category    = ! empty( $category ) ? reset( $category ) : '';
		$category_id = ! empty( $category ) ? $category->get_id() : '';

		$data['category_id'] = $category_id;

		return eaccounting_insert_item( $data );
	}
}
