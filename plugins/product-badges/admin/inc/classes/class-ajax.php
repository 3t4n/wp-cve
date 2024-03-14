<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Lion_Badge_Ajax {
	public function __construct() {
		add_action( 'wp_ajax_lion_badges_get_products', array( $this, 'admin_get_products_ajax_callback' ) );
		add_action( 'wp_ajax_lion_badges_get_product_categories', array( $this, 'admin_get_product_categories_ajax_callback' ) );
	}

	/*
	 * Get products for select2 search dropdown
	 */
	public function admin_get_products_ajax_callback() {
		check_ajax_referer( 'lion_badges_get_products', 'security' );

		global $wpdb;

		$data = array();

		if ( ! $_POST['search'] )
			$_POST['search'] = '';

		$search = esc_sql( $_POST['search'] );

		$result = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = 'product' AND post_title LIKE %s", $search . '%' ) );

		if ( $result ) {
			foreach( $result as $product ) {
				$data[] = array( 
					'id' => absint( $product->ID ),
					'text' => esc_attr( $product->post_title ) 
				);
			}
		}

		echo json_encode( $data );
		exit;
	}

	/*
	 * Get categories for select2 search dropdown
	 */
	public function admin_get_product_categories_ajax_callback() {
		check_ajax_referer( 'lion_badges_get_product_categories', 'security' );

		global $wpdb;

		$data = array();

		if ( ! $_POST['search'] )
			$_POST['search'] = '';

		$search = esc_sql( $_POST['search'] );

		$result = $wpdb->get_results( $wpdb->prepare( "SELECT {$wpdb->terms}.term_id, {$wpdb->terms}.name, {$wpdb->terms}.slug FROM {$wpdb->terms} LEFT JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id WHERE {$wpdb->term_taxonomy}.taxonomy = '%s' AND {$wpdb->terms}.name LIKE %s", 'product_cat', $search . '%' ) );

		if ( $result ) {
			foreach( $result as $category ) {
				$data[] = array( 
					'id' => absint( $category->term_id ),
					'text' => esc_attr( $category->name ) 
				);
			}
		}

		echo json_encode( $data );
		exit;
	}
}

new Lion_Badge_Ajax();

