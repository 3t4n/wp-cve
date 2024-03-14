<?php
/**
 * Class to handle all sales rep database interactions for the Order Tracking plugin
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdotpSalesRepManager' ) ) {
class ewdotpSalesRepManager {

	// The name of the sales rep table, set in the constructor
	public $sales_reps_table_name;

	// The name of the meta table, set in the constructor
	public $meta_table_name;

	// Array containing the arguments for the query
	public $args = array();

	// Array containing retrieved sales rep objects
	public $sales_reps = array();

	public function __construct() {
		global $wpdb;

		$this->sales_reps_table_name = $wpdb->prefix . "EWD_OTP_Sales_Reps";
		$this->meta_table_name = $wpdb->prefix . "EWD_OTP_Fields_Meta";

		if ( get_transient( 'ewd-otp-update-tables' ) ) { 
			
			add_action( 'plugins_loaded', array( $this, 'create_tables' ) );
		}
	} 

	/**
	 * Creates the tables used to store sales rep and their meta information
	 * @since 3.0.0
	 */
	public function create_tables() {

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$sql = "CREATE TABLE $this->sales_reps_table_name (
  			Sales_Rep_ID mediumint(9) NOT NULL AUTO_INCREMENT,
			Sales_Rep_Number text DEFAULT '' NOT NULL,
			Sales_Rep_First_Name text DEFAULT '' NOT NULL,
			Sales_Rep_Last_Name text DEFAULT '' NOT NULL,
			Sales_Rep_Email text DEFAULT '' NOT NULL,
			Sales_Rep_Phone_Number text DEFAULT '' NOT NULL,
			Sales_Rep_WP_ID mediumint(9) DEFAULT 0 NOT NULL,
			Sales_Rep_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
  			UNIQUE KEY id (Sales_Rep_ID)
    		)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   		
   		dbDelta($sql);

    	$sql = "CREATE TABLE $this->meta_table_name (
  			Meta_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  			Field_ID mediumint(9) DEFAULT '0',
			Order_ID mediumint(9) DEFAULT '0',
			Customer_ID mediumint(9) DEFAULT '0',
			Sales_Rep_ID mediumint(9) DEFAULT '0',
			Meta_Value text DEFAULT '' NOT NULL,
  			UNIQUE KEY id (Meta_ID)
    		)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

   		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    	
    	dbDelta($sql);

    	$this->copy_sales_rep_id_to_number_field();
	}

	/**
	 * Copies the sales rep ID database field to the number field, when the number field is blank
	 * @since 3.2.0
	 */
	public function copy_sales_rep_id_to_number_field() {
		global $wpdb;

		$wpdb->query( "UPDATE $this->sales_reps_table_name SET Sales_Rep_Number=Sales_Rep_ID WHERE Sales_Rep_Number=''" );
	}

	/**
	 * Returns a single sales rep given their sales rep ID
	 * @since 3.0.0
	 */
	public function get_sales_rep_from_id( $sales_rep_id ) {
		global $wpdb;

		$db_sales_rep = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->sales_reps_table_name WHERE Sales_Rep_ID=%d", $sales_rep_id ) );

		return $db_sales_rep;
	}

	/**
	 * Returns a single sales rep given their WP ID
	 * @since 3.0.0
	 */
	public function get_sales_rep_from_wp_id( $wp_id ) {
		global $wpdb;

		$db_sales_rep = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->sales_reps_table_name WHERE Sales_Rep_WP_ID=%d", $wp_id ) );

		return $db_sales_rep;
	}

	/**
	 * Returns a sales rep's ID given their WP ID
	 * @since 3.0.0
	 */
	public function get_sales_rep_id_from_wp_id( $wp_id ) {
		global $wpdb;

		$sales_rep_id = $wpdb->get_var( $wpdb->prepare( "SELECT Sales_Rep_ID FROM $this->sales_reps_table_name WHERE Sales_Rep_WP_ID=%d", $wp_id ) );

		return $sales_rep_id;
	}

	/**
	 * Returns a sales rep's ID given their WP ID
	 * @since 3.0.0
	 */
	public function get_sales_rep_id_from_number( $sales_rep_number ) {
		global $wpdb;

		$sales_rep_id = $wpdb->get_var( $wpdb->prepare( "SELECT Sales_Rep_ID FROM $this->sales_reps_table_name WHERE Sales_Rep_Number=%d", $sales_rep_number ) );

		return $sales_rep_id;
	}

	/**
	 * Returns sales reps matching the arguments supplied
	 * @since 3.0.0
	 */
	public function get_matching_sales_reps( $args ) {

		$this->sales_reps = array();

		$defaults = array(
			'sales_reps_per_page'	=> 20,
			'order'					=> 'ASC',
			'paged'					=> 1,
		);

		$this->args = wp_parse_args( $args, $defaults );

		$this->run_query();

		return $this->sales_reps;
	}

	/**
	 * Return the counts for sales reps being displayed on the admin sales reps page
	 * @since 3.0.0
	 */
	public function get_sales_rep_counts( $args ) {
		global $wpdb;

		$query_string = "SELECT count( * ) AS num_sales_reps
			FROM $this->sales_reps_table_name
			WHERE 1=%d
		";

		$query_args = array(1);

		if ( ! empty( $args['first_name'] ) ) {

			$query_string .= " AND Sales_Rep_First_Name=%s";
			$query_args[] = sanitize_text_field( $args['first_name'] );
		}

		if ( ! empty( $args['last_name'] ) ) {

			$query_string .= " AND Sales_Rep_Last_Name=%s";
			$query_args[] = sanitize_text_field( $args['last_name'] );
		}

		if ( ! empty( $args['email'] ) ) {

			$query_string .= " AND Sales_Rep_Email=%s";
			$query_args[] = sanitize_text_field( $args['email'] );
		}

		$result = $wpdb->get_results( $wpdb->prepare( $query_string, $query_args ) );

		$counts = array(
			'total'	=> $result[0]->num_sales_reps
		);

		return $counts;
	}

	/**
	 * Create and run the SQL query based on the arguments received
	 * @since 3.0.0
	 */
	public function run_query() {
		global $wpdb;

		$args = $this->args;

		$query_string = "SELECT * FROM $this->sales_reps_table_name WHERE 1=%d";

		$query_args = array( 1 );

		if ( ! empty( $args['id'] ) ) {

			$query_string .= " AND Sales_Rep_ID=%d";
			$query_args[] = intval( $args['id'] );
		}

		if ( ! empty( $args['number'] ) ) {

			$query_string .= " AND Sales_Rep_Number=%s";
			$query_args[] = $args['number'];
		}

		if ( ! empty( $args['first_name'] ) ) {

			$query_string .= " AND Sales_Rep_First_Name=%s";
			$query_args[] = $args['first_name'];
		}

		if ( ! empty( $args['last_name'] ) ) {

			$query_string .= " AND Sales_Rep_Last_Name=%s";
			$query_args[] = $args['last_name'];
		}

		if ( ! empty( $args['wp_id'] ) ) {

			$query_string .= " AND Sales_Rep_WP_ID=%d";
			$query_args[] = intval( $args['wp_id'] );
		}

		if ( ! empty( $args['email'] ) ) {

			$query_string .= " AND Sales_Rep_Email=%s";
			$query_args[] = $args['email'];
		}

		if ( ! empty( $args['orderby'] ) ) {
			
			$query_string .= ' ORDER BY ' . esc_sql( $args['orderby'] ) . ' ' . ( strtolower( $args['order'] ) == 'desc' ? 'DESC' : 'ASC' );
		}

		if ( $args['sales_reps_per_page'] > 0 ) {

			$query_string .= ' LIMIT ' . intval( ( $args['paged'] - 1 ) * $args['sales_reps_per_page'] ) . ', ' . intval( $args['sales_reps_per_page'] );
		}

		$db_sales_reps = $wpdb->get_results( $wpdb->prepare( $query_string, $query_args ) );

		foreach ( $db_sales_reps as $db_sales_rep ) {

			$sales_rep = new ewdotpSalesRep();

			$sales_rep->load_sales_rep( $db_sales_rep );

			$this->sales_reps[] = $sales_rep;
		}
	}

	/**
	 * Returns the value for a given field/sales rep id pair
	 * @since 3.0.0
	 */
	public function get_sales_rep_field( $field, $sales_rep_id ) {
		global $wpdb;

		$db_sales_rep =  $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->sales_reps_table_name WHERE Sales_Rep_ID=%d", $sales_rep_id ) );

		$sales_rep = new ewdotpSalesRep();
		$sales_rep->load_sales_rep( $db_sales_rep );

		return ! empty( $sales_rep->$field ) ? $sales_rep->$field : '';
	}

	/**
	 * Returns the value for a given custom_field/sales rep pair
	 * @since 3.0.0
	 */
	public function get_field_value( $custom_field_id, $sales_rep_id ) {
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare( "SELECT Meta_Value FROM $this->meta_table_name WHERE Field_ID=%d AND Sales_Rep_ID=%d", $custom_field_id, $sales_rep_id ) );
	}


	/**
	 * Accepts a sales rep object, inserts it into the database, and returns the ID of the newly inserted sales rep
	 * @since 3.0.0
	 */
	public function insert_sales_rep( $sales_rep ) {
		global $wpdb;
		global $ewd_otp_controller;

		$query_args = array(
			'Sales_Rep_Number' 			=> ! empty( $sales_rep->number ) ? $sales_rep->number : '',
			'Sales_Rep_First_Name' 		=> ! empty( $sales_rep->first_name ) ? $sales_rep->first_name : '',
			'Sales_Rep_Last_Name' 		=> ! empty( $sales_rep->last_name ) ? $sales_rep->last_name : '',
			'Sales_Rep_Email' 			=> ! empty( $sales_rep->email ) ? $sales_rep->email : '',
			'Sales_Rep_Phone_Number'	=> ! empty( $sales_rep->phone_number ) ? $sales_rep->phone_number : '',
			'Sales_Rep_WP_ID' 			=> ! empty( $sales_rep->wp_id ) ? $sales_rep->wp_id : 0,
			'Sales_Rep_Created' 		=> date( 'Y-m-d H:i:s' ),
		);

		$wpdb->insert(
			$this->sales_reps_table_name,
			$query_args
		);
		
		$sales_rep_id = $wpdb->insert_id;

		if ( ! $sales_rep_id ) { return $sales_rep_id; }

		$custom_fields = $ewd_otp_controller->settings->get_sales_rep_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			if ( empty( $sales_rep->custom_fields[ $custom_field->id ] ) ) { continue; }

			$query_args = array(
				'Field_ID'			=> $custom_field->id,
				'Sales_Rep_ID'		=> $sales_rep_id,
				'Meta_Value'		=> $sales_rep->custom_fields[ $custom_field->id ]
			);

			$wpdb->insert(
				$this->meta_table_name,
				$query_args
			);
		}

		return $sales_rep_id;
	}

	/**
	 * Accepts a sales rep object, updates it in the database, and returns the ID if successful or false otherwise
	 * @since 3.0.0
	 */
	public function update_sales_rep( $sales_rep ) {
		global $wpdb;
		global $ewd_otp_controller;

		if ( empty( $sales_rep->id ) ) { return false; }

		$query_args = array(
			'Sales_Rep_Number' 			=> ! empty( $sales_rep->number ) ? $sales_rep->number : '',
			'Sales_Rep_First_Name' 		=> ! empty( $sales_rep->first_name ) ? $sales_rep->first_name : '',
			'Sales_Rep_Last_Name' 		=> ! empty( $sales_rep->last_name ) ? $sales_rep->last_name : '',
			'Sales_Rep_Email' 			=> ! empty( $sales_rep->email ) ? $sales_rep->email : '',
			'Sales_Rep_Phone_Number'	=> ! empty( $sales_rep->phone_number ) ? $sales_rep->phone_number : '',
			'Sales_Rep_WP_ID' 			=> ! empty( $sales_rep->wp_id ) ? $sales_rep->wp_id : 0,
		);

		$wpdb->update(
			$this->sales_reps_table_name,
			$query_args,
			array( 'Sales_Rep_ID' => $sales_rep->id )
		);

		$custom_fields = $ewd_otp_controller->settings->get_sales_rep_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$wpdb->get_var( $wpdb->prepare( "SELECT Meta_Value from $this->meta_table_name WHERE Field_ID=%d AND Sales_Rep_ID=%d ORDER BY Meta_ID DESC", $custom_field->id, $sales_rep->id ) );

			$update = $wpdb->num_rows ? true : false;

			if ( empty( $sales_rep->custom_fields[ $custom_field->id ] ) ) { 

				$where_args = array(
					'Field_ID'		=> $custom_field->id,
					'Sales_Rep_ID'	=> $sales_rep->id,
				);

				$wpdb->delete(
					$this->meta_table_name,
					$where_args 
				);
			}
			elseif ( $update ) {
				
				$query_args = array(
					'Meta_Value' 	=> $sales_rep->custom_fields[ $custom_field->id ]
				);

				$where_args = array(
					'Field_ID'		=> $custom_field->id,
					'Sales_Rep_ID'	=> $sales_rep->id,
				);

				$wpdb->update(
					$this->meta_table_name,
					$query_args,
					$where_args
				);
			}
			else {
				
				$query_args = array(
					'Meta_Value' 	=> $sales_rep->custom_fields[ $custom_field->id ],
					'Field_ID'		=> $custom_field->id,
					'Sales_Rep_ID'	=> $sales_rep->id,
				);

				$wpdb->insert(
					$this->meta_table_name,
					$query_args
				);
			}
		}

		return $sales_rep->id;
	}

	/**
	 * Accepts a sales rep id, deletes the corresponding sales rep
	 * @since 3.0.0
	 */
	public function delete_sales_rep( $sales_rep_id ) {
		global $wpdb;

		$wpdb->delete(
			$this->sales_reps_table_name,
			array( 'Sales_Rep_ID' => $sales_rep_id )
		);

		$wpdb->delete(
			$this->meta_table_name,
			array( 'Sales_Rep_ID' => $sales_rep_id )
		);
	}

	/**
	 * Accepts an email and sales rep id, and verify that they match the saved data,
	 * @since 3.0.0
	 */
	public function verify_sales_rep_email( $email, $sales_rep_id ) {
		global $wpdb;

		$sales_rep_email = $wpdb->get_var( $wpdb->prepare( "SELECT Sales_Rep_Email FROM $this->sales_reps_table_name WHERE Sales_Rep_ID=%d", $sales_rep_id ) );

		if ( $sales_rep_email == $email ) { 

			return true;
		}

		return false;
	}
}
}