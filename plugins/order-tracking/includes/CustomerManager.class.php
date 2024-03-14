<?php
/**
 * Class to handle all customer database interactions for the Order Tracking plugin
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdotpCustomerManager' ) ) {
class ewdotpCustomerManager {

	// The name of the customers table, set in the constructor
	public $customers_table_name;

	// The name of the meta table, set in the constructor
	public $meta_table_name;

	// Array containing the arguments for the query
	public $args = array();

	// Array containing retrieved customer objects
	public $customers = array();

	public function __construct() {
		global $wpdb;

		$this->customers_table_name = $wpdb->prefix . "EWD_OTP_Customers";
		$this->meta_table_name = $wpdb->prefix . "EWD_OTP_Fields_Meta";

		if ( get_transient( 'ewd-otp-update-tables' ) ) { 
			
			add_action( 'plugins_loaded', array( $this, 'create_tables' ) );
		}
	} 

	/**
	 * Creates the tables used to store customers and their meta information
	 * @since 3.0.0
	 */
	public function create_tables() {

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$sql = "CREATE TABLE $this->customers_table_name (
  			Customer_ID mediumint(9) NOT NULL AUTO_INCREMENT,
			Customer_Number text DEFAULT '' NOT NULL,
			Customer_Name text DEFAULT '' NOT NULL,
			Sales_Rep_ID mediumint(9) DEFAULT 0 NOT NULL,
			Customer_WP_ID mediumint(9) DEFAULT 0 NOT NULL,
			Customer_FEUP_ID mediumint(9) DEFAULT 0 NOT NULL,
			Customer_Email text DEFAULT '' NOT NULL,
			Customer_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
  			UNIQUE KEY id (Customer_ID)
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

    	$this->copy_customer_id_to_number_field();
	}

	/**
	 * Copies the customer ID database field to the number field, when the number field is blank
	 * @since 3.2.0
	 */
	public function copy_customer_id_to_number_field() {
		global $wpdb;

		$wpdb->query( "UPDATE $this->customers_table_name SET Customer_Number=Customer_ID WHERE Customer_Number=''" );
	}

	/**
	 * Returns a single customer given their customer ID
	 * @since 3.0.0
	 */
	public function get_customer_from_id( $customer_id ) {
		global $wpdb;

		$db_customer = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->customers_table_name WHERE Customer_ID=%d", $customer_id ) );

		return $db_customer;
	}

	/**
	 * Returns a customer's ID given their WP ID
	 * @since 3.0.0
	 */
	public function get_customer_id_from_wp_id( $wp_id ) {
		global $wpdb;

		$customer_id = $wpdb->get_var( $wpdb->prepare( "SELECT Customer_ID FROM $this->customers_table_name WHERE Customer_WP_ID=%d", $wp_id ) );

		return $customer_id;
	}

	/**
	 * Returns a customer's ID given their FEUP ID
	 * @since 3.0.0
	 */
	public function get_customer_id_from_feup_id( $feup_id ) {
		global $wpdb;

		$customer_id = $wpdb->get_var( $wpdb->prepare( "SELECT Customer_ID FROM $this->customers_table_name WHERE Customer_FEUP_ID=%d", $feup_id ) );

		return $customer_id;
	}

	/**
	 * Returns a customer's ID given their number
	 * @since 3.0.0
	 */
	public function get_customer_id_from_number( $customer_number ) {
		global $wpdb;

		$customer_id = $wpdb->get_var( $wpdb->prepare( "SELECT Customer_ID FROM $this->customers_table_name WHERE Customer_Number=%s", $customer_number ) );

		return $customer_id;
	}

	/**
	 * Returns a customer's ID given their name
	 * @since 3.0.0
	 */
	public function get_customer_id_from_name( $customer_name ) {
		global $wpdb;

		$customer_id = $wpdb->get_var( $wpdb->prepare( "SELECT Customer_ID FROM $this->customers_table_name WHERE Customer_Name=%s", $customer_name ) );

		return $customer_id;
	}

	/**
	 * Returns a customer's ID given their email
	 * @since 3.0.0
	 */
	public function get_customer_id_from_email( $email ) {
		global $wpdb;

		$customer_id = $wpdb->get_var( $wpdb->prepare( "SELECT Customer_ID FROM $this->customers_table_name WHERE Customer_Email=%s", $email ) );

		return $customer_id;
	}

	/**
	 * Returns customers matching the arguments supplied
	 * @since 3.0.0
	 */
	public function get_matching_customers( $args ) {

		$this->customers = array();

		$defaults = array(
			'customers_per_page'	=> 20,
			'order'					=> 'ASC',
			'paged'					=> 1,
		);

		$this->args = wp_parse_args( $args, $defaults );

		$this->run_query();

		return $this->customers;
	}

	/**
	 * Return the counts for customers being displayed on the admin customers page
	 * @since 3.0.0
	 */
	public function get_customer_counts( $args ) {
		global $wpdb;

		$query_string = "SELECT count( * ) AS num_customers
			FROM $this->customers_table_name
			WHERE 1=%d
		";

		$query_args = array( 1 );

		if ( ! empty( $args['name'] ) ) {

			$query_string .= " AND Customer_Name=%s";
			$query_args[] = sanitize_text_field( $args['name'] );
		}

		if ( ! empty( $args['email'] ) ) {

			$query_string .= " AND Customer_Email=%s";
			$query_args[] = sanitize_text_field( $args['email'] );
		}

		$result = $wpdb->get_results( $wpdb->prepare( $query_string, $query_args ) );

		$counts = array(
			'total'	=> $result[0]->num_customers
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

		$query_string = "SELECT * FROM $this->customers_table_name WHERE 1=%d";

		$query_args = array( 1 );

		if ( ! empty( $args['id'] ) ) {

			$query_string .= " AND Customer_ID=%d";
			$query_args[] = intval( $args['id'] );
		}

		if ( ! empty( $args['number'] ) ) {

			$query_string .= " AND Customer_Number=%s";
			$query_args[] = $args['number'];
		}

		if ( ! empty( $args['name'] ) ) {

			$query_string .= " AND Customer_Name=%s";
			$query_args[] = $args['name'];
		}

		if ( ! empty( $args['wp_id'] ) ) {

			$query_string .= " AND Customer_WP_ID=%d";
			$query_args[] = intval( $args['wp_id'] );
		}

		if ( ! empty( $args['feup_id'] ) ) {

			$query_string .= " AND Customer_FEUP_ID=%d";
			$query_args[] = intval( $args['feup_id'] );
		}

		if ( ! empty( $args['email'] ) ) {

			$query_string .= " AND Customer_Email=%s";
			$query_args[] = $args['email'];
		}

		if ( ! empty( $args['sales_rep'] ) ) {

			$query_string .= " AND Sales_Rep_ID=%d";
			$query_args[] = intval( $args['sales_rep'] );
		}

		if ( ! empty( $args['orderby'] ) ) {
			
			$query_string .= ' ORDER BY ' . esc_sql( $args['orderby'] ) . ' ' . ( strtolower( $args['order'] ) == 'desc' ? 'DESC' : 'ASC' );
		}

		if ( $args['customers_per_page'] > 0 ) {

			$query_string .= ' LIMIT ' . intval( ( $args['paged'] - 1 ) * $args['customers_per_page'] ) . ', ' . intval( $args['customers_per_page'] );
		}

		$db_customers = $wpdb->get_results( $wpdb->prepare( $query_string, $query_args ) );

		foreach ( $db_customers as $db_customer ) {

			$customer = new ewdotpCustomer();

			$customer->load_customer( $db_customer );

			$this->customers[] = $customer;
		}
	}

	/**
	 * Returns the value for a given field/customer id pair
	 * @since 3.0.0
	 */
	public function get_customer_field( $field, $customer_id ) {
		global $wpdb;

		$db_customer =  $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->customers_table_name WHERE Customer_ID=%d", $customer_id ) );

		$customer = new ewdotpCustomer();
		$customer->load_customer( $db_customer );

		return ! empty( $customer->$field ) ? $customer->$field : '';
	}

	/**
	 * Returns the value for a given custom_field/customer pair
	 * @since 3.0.0
	 */
	public function get_field_value( $custom_field_id, $customer_id ) {
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare( "SELECT Meta_Value FROM $this->meta_table_name WHERE Field_ID=%d AND Customer_ID=%d", $custom_field_id, $customer_id ) );
	}


	/**
	 * Accepts a customer object, inserts it into the database, and returns the ID of the newly inserted customer
	 * @since 3.0.0
	 */
	public function insert_customer( $customer ) {
		global $wpdb;
		global $ewd_otp_controller;

		$query_args = array(
			'Customer_Number' 		=> ! empty( $customer->number ) ? $customer->number : '',
			'Customer_Name' 		=> ! empty( $customer->name ) ? $customer->name : '',
			'Customer_Email' 		=> ! empty( $customer->email ) ? $customer->email : '',
			'Sales_Rep_ID' 			=> ! empty( $customer->sales_rep ) ? $customer->sales_rep : 0,
			'Customer_WP_ID' 		=> ! empty( $customer->wp_id ) ? $customer->wp_id : 0,
			'Customer_FEUP_ID'		=> ! empty( $customer->feup_id ) ? $customer->feup_id : 0,
			'Customer_Created' 		=> date( 'Y-m-d H:i:s' ),
		);

		$wpdb->insert(
			$this->customers_table_name,
			$query_args
		);
		
		$customer_id = $wpdb->insert_id;

		if ( ! $customer_id ) { return $customer_id; }

		$custom_fields = $ewd_otp_controller->settings->get_customer_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			if ( empty( $customer->custom_fields[ $custom_field->id ] ) ) { continue; }

			$query_args = array(
				'Field_ID'			=> $custom_field->id,
				'Customer_ID'		=> $customer_id,
				'Meta_Value'		=> $customer->custom_fields[ $custom_field->id ]
			);

			$wpdb->insert(
				$this->meta_table_name,
				$query_args
			);
		}

		return $customer_id;
	}

	/**
	 * Accepts a customer object, updates it in the database, and returns the ID if successful or false otherwise
	 * @since 3.0.0
	 */
	public function update_customer( $customer ) {
		global $wpdb;
		global $ewd_otp_controller;
		
		if ( empty( $customer->id ) ) { return false; }

		$query_args = array(
			'Customer_Number' 		=> ! empty( $customer->number ) ? $customer->number : '',
			'Customer_Name' 		=> ! empty( $customer->name ) ? $customer->name : '',
			'Customer_Email' 		=> ! empty( $customer->email ) ? $customer->email : '',
			'Sales_Rep_ID' 			=> ! empty( $customer->sales_rep ) ? $customer->sales_rep : 0,
			'Customer_WP_ID' 		=> ! empty( $customer->wp_id ) ? $customer->wp_id : 0,
			'Customer_FEUP_ID'		=> ! empty( $customer->feup_id ) ? $customer->feup_id : 0,
		);

		$wpdb->update(
			$this->customers_table_name,
			$query_args,
			array( 'Customer_ID' => $customer->id )
		);

		$custom_fields = $ewd_otp_controller->settings->get_customer_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$wpdb->get_var( $wpdb->prepare( "SELECT Meta_Value from $this->meta_table_name WHERE Field_ID=%d AND Customer_ID=%d ORDER BY Meta_ID DESC", $custom_field->id, $customer->id ) );

			$update = $wpdb->num_rows ? true : false;

			if ( empty( $customer->custom_fields[ $custom_field->id ] ) ) { 

				$where_args = array(
					'Field_ID'		=> $custom_field->id,
					'Customer_ID'	=> $customer->id,
				);

				$wpdb->delete(
					$this->meta_table_name,
					$where_args 
				);
			}
			elseif ( $update ) {
				
				$query_args = array(
					'Meta_Value' 	=> $customer->custom_fields[ $custom_field->id ]
				);

				$where_args = array(
					'Field_ID'		=> $custom_field->id,
					'Customer_ID'	=> $customer->id,
				);

				$wpdb->update(
					$this->meta_table_name,
					$query_args,
					$where_args
				);
			}
			else {
				
				$query_args = array(
					'Meta_Value' 	=> $customer->custom_fields[ $custom_field->id ],
					'Field_ID'		=> $custom_field->id,
					'Customer_ID'	=> $customer->id,
				);

				$wpdb->insert(
					$this->meta_table_name,
					$query_args
				);
			}
		}

		return $customer->id;
	}

	/**
	 * Accepts a customer id, deletes the corresponding customer
	 * @since 3.0.0
	 */
	public function delete_customer( $customer_id ) {
		global $wpdb;

		$wpdb->delete(
			$this->customers_table_name,
			array( 'Customer_ID' => $customer_id )
		);

		$wpdb->delete(
			$this->meta_table_name,
			array( 'Customer_ID' => $customer_id )
		);
	}

	/**
	 * Accepts an email and customer id, and verify that they match the saved data,
	 * @since 3.0.0
	 */
	public function verify_customer_email( $email, $customer_id ) {
		global $wpdb;

		$customer_email = $wpdb->get_var( $wpdb->prepare( "SELECT Customer_Email FROM $this->customers_table_name WHERE Customer_ID=%d", $customer_id ) );

		if ( $customer_email == $email ) { 

			return true;
		}

		return false;
	}
}
}