<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class that handles database queries for the Calendars
 *
 */
Class WPSBC_Object_DB_Calendars extends WPSBC_Object_DB {

	/**
	 * Construct
	 *
	 */
	public function __construct() {

		global $wpdb;

		$this->table_name 		 = $wpdb->prefix . 'wpsbc_calendars';
		$this->primary_key 		 = 'id';
		$this->context 	  		 = 'calendar';
		$this->query_object_type = 'WPSBC_Calendar';

	}


	/**
	 * Return the table columns 
	 *
	 */
	public function get_columns() {

		return array(
			'id' 		    => '%d',
			'name' 		    => '%s',
			'date_created' 	=> '%s',
			'date_modified' => '%s',
			'status'		=> '%s',
			'ical_hash'		=> '%s'
		);

	}


	/**
	 * Returns an array of WPSBC_Calendar objects from the database
	 *
	 * @param array $args
	 * @param bool  $count - whether to return just the count for the query or not
	 *
	 * @return mixed array|int
	 *
	 */
	public function get_calendars( $args = array(), $count = false ) {

		$defaults = array(
			'number'    => -1,
			'offset'    => 0,
			'orderby'   => 'id',
			'order'     => 'DESC',
			'include'   => array(),
			'search'	=> ''
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * Filter the query arguments just before making the db call
		 *
		 * @param array $args
		 *
		 */
		$args = apply_filters( 'wpsbc_get_calendars_args', $args );

		// Number args
		if( $args['number'] < 1 )
			$args['number'] = 999999;

		// Where clause
		$where = "WHERE 1=1";

		// Status where clause
		if( ! empty( $args['status'] ) ) {

			$status = sanitize_text_field( $args['status'] );
			$where .= " AND status = '{$status}'";

		}

		// iCalendar hash where clause
		if( ! empty( $args['ical_hash'] ) ) {

			$ical_hash = sanitize_text_field( $args['ical_hash'] );
			$where .= " AND ical_hash = '{$ical_hash}'";

		}

		// Include where clause
		if( ! empty( $args['include'] ) ) {

			$include = implode( ',', $args['include'] );
			$where  .= " AND id IN({$include})";

		}

		// Include search
		if( ! empty( $args['search'] ) ) {

			$search = sanitize_text_field( $args['search'] );
			$where  .= " AND name LIKE '%%{$search}%%'";

		}

		// Orderby
		$orderby = in_array($args['orderby'], array('id', 'name', 'date_created', 'date_modified', 'status')) ? $args['orderby'] : 'id';

		// Order
		$order = ( 'DESC' === strtoupper( $args['order'] ) ? 'DESC' : 'ASC' );

		$clauses = compact( 'where', 'orderby', 'order', 'count' );

		$results = $this->get_results( $clauses, $args, 'wpsbc_get_calendar' );

		return $results;

	}


	/**
	 * Creates and updates the database table for the calendars
	 *
	 */
	public function create_table() {

		global $wpdb;

		$table_name 	 = $this->table_name;
		$charset_collate = $wpdb->get_charset_collate();

		$query = "CREATE TABLE {$table_name} (
			id bigint(10) NOT NULL AUTO_INCREMENT,
			name text NOT NULL,
			date_created datetime NOT NULL,
			date_modified datetime NOT NULL,
			status text NOT NULL,
			ical_hash text NOT NULL,
			PRIMARY KEY  id (id)
		) {$charset_collate};";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $query );

	}

}