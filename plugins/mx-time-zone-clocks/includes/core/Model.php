<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Model class
*/
class MXMTZC_Model
{

	private $wpdb;

	/**
	* Table name
	*/
	protected $table = MXMTZC_TABLE_SLUG;

	/**
	* fields
	*/
	protected $fields = '*';

	/*
	* Model constructor
	*/
	public function __construct()
	{
		
		global $wpdb;

    	$this->wpdb = $wpdb;    	

	}	

	/**
	* select row from the database
	*/
	public function mxmtzc_get_row( $table = NULL, $wher_name = 'name', $wher_value = 'value' )
	{

		$table_name = $this->wpdb->prefix . $this->table;

		if( $table !== NULL ) {

			$table_name = $table;

		}

		$get_row = $this->wpdb->get_row( "SELECT $this->fields FROM $table_name WHERE $wher_name = $wher_value" );

		return $get_row;
		
	}

	/**
	* get results from the database
	*/
	public function mxmtzc_get_results( $table = false, $wher_name = NULL, $wher_value = 1 )
	{

		$table_name = $this->wpdb->prefix . $this->table;

		if( $table !== false ) {

			$table_name = $table;

		}

		if( $wher_name !== NULL ) {

			$results = $this->wpdb->get_results( "SELECT $this->fields FROM $table_name WHERE $wher_name = $wher_value" );

		} else {

			$results = $this->wpdb->get_results( "SELECT $this->fields FROM $table_name" );

		}		

		return $results;
		
	}

}