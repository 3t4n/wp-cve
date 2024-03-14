<?php
/**
 * Product category options array.
 *
 * A formated array of product options used for the order sync select.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 1.10.1
 *
 * @return array
 */

function get_product_category_options_array() {

	$categories = get_categories( [
		'taxonomy'     => 'product_cat',
		'orderby'      => 'name',
		'hide_empty'   => false
	] );

	$_categories     = [];
	$_categories[-1] = __( 'Allow All Categories', 'wp-data-sync' );

	foreach ( $categories as $category ) {
		$_categories[ $category->term_id ] = $category->name;
	}

	return $_categories;

}