<?php
/**
 * Handle category import.
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Import
 */

namespace EverAccounting\Import;

use EverAccounting\Abstracts\CSV_Importer;

defined( 'ABSPATH' ) || exit();


/**
 * Class Categories
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Import
 */
class Categories extends CSV_Importer {
	/**
	 * Get supported key and readable label.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_headers() {
		return eaccounting_get_io_headers( 'category' );
	}

	/**
	 * Return the required key to import item.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	public function get_required() {
		return array( 'name', 'type' );
	}

	/**
	 * Get formatting callback.
	 *
	 * @return array
	 * @since 1.0.2
	 */
	protected function get_formatting_callback() {
		return array(
			'name'  => array( $this, 'parse_text_field' ),
			'type'  => array( $this, 'parse_text_field' ),
			'color' => array( $this, 'parse_text_field' ),
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
		if ( empty( $data['type'] ) ) {
			return new \WP_Error( 'empty_prop', __( 'Empty Type', 'wp-ever-accounting' ) );
		}

		$category_exists = eaccounting_get_categories(
			array(
				'search' => $data['name'],
				'type'   => $data['type'],
			)
		);
		$category_id     = ! empty( $category_exists ) ? $category_exists[0]->get_id() : '';

		if ( ! empty( $category_id ) ) {
			return new \WP_Error( 'invalid_props', __( 'Category already exists.', 'wp-ever-accounting' ) );
		}

		return eaccounting_insert_category( $data );
	}

}
