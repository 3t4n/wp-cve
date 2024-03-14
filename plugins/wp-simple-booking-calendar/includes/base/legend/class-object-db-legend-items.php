<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class that handles database queries for the Legend Items
 *
 */
Class WPSBC_Object_DB_Legend_Items extends WPSBC_Object_DB {

	/**
	 * Construct
	 *
	 */
	public function __construct() {

		global $wpdb;

		$this->table_name 		 = $wpdb->prefix . 'wpsbc_legend_items';
		$this->primary_key 		 = 'id';
		$this->context 	  		 = 'legend_item';
		$this->query_object_type = 'WPSBC_Legend_Item';

	}


	/**
	 * Return the table columns 
	 *
	 */
	public function get_columns() {

		return array(
			'id' 		  => '%d',
			'type'		  => '%s',
			'name' 		  => '%s',
			'color' 	  => '%s',
			'color_text'  => '%s',
			'is_default'  => '%d',
			'is_visible'  => '%d',
			'calendar_id' => '%d'
		);

	}


	/**
	 * Returns an array of WPSBC_Legend_Items objects from the database
	 *
	 * @param array $args
	 * @param bool  $count - whether to return just the count for the query or not
	 *
	 * @return mixed array|int
	 *
	 */
	public function get_legend_items( $args = array(), $count = false ) {

		$defaults = array(
			'number'   	  => -1,
			'offset'   	  => 0,
			'orderby'  	  => 'id',
			'order'    	  => 'ASC',
			'calendar_id' => 0
		);

		$args = wp_parse_args( $args, $defaults );

		// Number args
		if( $args['number'] < 1 )
			$args['number'] = 999999;

		// Where clause
		$where = '';

		// Calendar ID where clause
		if( ! empty( $args['calendar_id'] ) ) {

			$calendar_id = absint( $args['calendar_id'] );
			$where .= "WHERE calendar_id = {$calendar_id}";

		}

		// Is visible where clause
		if( isset( $args['is_visible'] ) ) {

			$is_visible = ( ! empty( $args['is_visible'] ) ? 1 : 0 );
			$where .= " AND is_visible = {$is_visible}";

		}

		// Is default where clause
		if( isset( $args['is_default'] ) ) {

			$is_default = ( ! empty( $args['is_default'] ) ? 1 : 0 );
			$where .= " AND is_default = {$is_default}";

		}

		$where .= " AND type = 'single'";

		// Orderby
		$orderby = sanitize_text_field( $args['orderby'] );

		// Order
		$order = ( 'DESC' === strtoupper( $args['order'] ) ? 'DESC' : 'ASC' );

		$clauses = compact( 'where', 'orderby', 'order', 'count' );

		$results = $this->get_results( $clauses, $args, 'wpsbc_get_legend_item' );

		/**
		 * Filter the legend items results just before returning
		 *
		 * @param array $results
		 * @param array $args
		 * @param bool  $count
		 *
		 */
		$results = apply_filters( 'wpsbc_get_legend_items', $results, $args, $count );

		return $results;

	}


	/**
	 * Creates and updates the database table for the legend items
	 *
	 */
	public function create_table() {

		global $wpdb;

		$table_name 	 = $this->table_name;
		$charset_collate = $wpdb->get_charset_collate();

		$query = "CREATE TABLE {$table_name} (
			id bigint(10) NOT NULL AUTO_INCREMENT,
			type varchar(255) NOT NULL,
			name text NOT NULL,
			color text NOT NULL,
			color_text text,
			is_default tinyint(1) NOT NULL,
			is_visible tinyint(1) NOT NULL,
			calendar_id bigint(10) NOT NULL,
			PRIMARY KEY  id (id)
		) {$charset_collate};";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $query );

	}

}