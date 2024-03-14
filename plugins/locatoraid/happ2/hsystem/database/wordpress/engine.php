<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
if( ! class_exists('HC_Database_Engine_Wordpress') ){
class HC_Database_Engine_Wordpress {
	private $wpdb = NULL;

	public function __construct( $wpdb )
	{
		$this->wpdb = $wpdb;
	}

	public function get_error()
	{
		return $this->wpdb->last_error;
	}

	public function query( $sql )
	{
		$is_select = FALSE;
		if ( preg_match( '/^\s*(select|show)\s/i', $sql ) ) {
			$is_select = TRUE;
		}

		if( $is_select ){
			$return = $this->wpdb->get_results( $sql, ARRAY_A );
		}
		else {
			// echo "NOT SELECT: '$sql'<br>";
			$return = $this->wpdb->query( $sql );
		}

		return $return;
	}

	public function insert_id()
	{
		return $this->wpdb->insert_id;
	}

	public function queries()
	{
		return $this->wpdb->queries;
	}
}
}