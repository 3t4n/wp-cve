<?php

/**
 * class CategoryHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      2.7.0
 *
 */

namespace AppBuilder\Hooks;

defined( 'ABSPATH' ) || exit;

class CategoryHook {

	public function __construct() {
		add_action( 'saved_product_cat', array( $this, 'saved_product_cat' ), 10, 3 );
	}

	/**
	 * Action fires once a product category has been saved.
	 *
	 * @param int $term_id
	 * @param int $tt_id
	 * @param bool $update
	 */
	public function saved_product_cat( int $term_id, int $tt_id, bool $update ) {
		$result = wp_cache_get( 'app-builder-category-key', 'app-builder' );
		if ( $result ) {
			wp_cache_delete( $result, 'app-builder' );
		}
	}
}
