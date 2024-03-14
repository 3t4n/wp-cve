<?php
/**
 * Class to handle all order database interactions for the Order Tracking plugin
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdotpOrderManager' ) ) {
class ewdotpOrderManager {

	// The name of the orders table, set in the constructor
	public $orders_table_name;

	// The name of the orders statuses table, set in the constructor
	public $order_statuses_table_name;

	// The name of the meta table, set in the constructor
	public $meta_table_name;

	// Array containing the arguments for the query
	public $args = array();

	// Array containing retrieved order objects
	public $orders = array();

	public function __construct() {
		global $wpdb;

		$this->orders_table_name = $wpdb->prefix . "EWD_OTP_Orders";
		$this->order_statuses_table_name = $wpdb->prefix . "EWD_OTP_Order_Statuses";
		$this->meta_table_name = $wpdb->prefix . "EWD_OTP_Fields_Meta";

		if ( get_transient( 'ewd-otp-update-tables' ) ) { 
			
			add_action( 'plugins_loaded', array( $this, 'create_tables' ) );
		}
	} 

	/**
	 * Creates the tables used to store orders and their meta information
	 * @since 3.0.0
	 */
	public function create_tables() {

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$sql = "CREATE TABLE $this->orders_table_name (
  			Order_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  			Order_Name text DEFAULT '' NOT NULL,
			Order_Number text DEFAULT '' NOT NULL,
			Order_Status text DEFAULT '' NOT NULL,
			Order_External_Status text DEFAULT '' NOT NULL,
			Order_Location text DEFAULT '' NOT NULL,
			Order_Notes_Public text DEFAULT '' NOT NULL,
			Order_Notes_Private text DEFAULT '' NOT NULL,
			Order_Customer_Notes text DEFAULT '' NOT NULL,
			Order_Email text DEFAULT '' NOT NULL,
			Order_Phone_Number text DEFAULT '' NOT NULL,
			Sales_Rep_ID mediumint(9) DEFAULT 0 NOT NULL,
			Customer_ID mediumint(9) DEFAULT 0 NOT NULL,
			WooCommerce_ID mediumint(9) DEFAULT 0 NOT NULL,
			Zendesk_ID mediumint(9) DEFAULT 0 NOT NULL,
			Order_Status_Updated datetime DEFAULT '0000-00-00 00:00:00' NULL,
			Order_Display text DEFAULT '' NOT NULL,
			Order_Payment_Price text DEFAULT '' NOT NULL,
			Order_Payment_Completed text DEFAULT '' NOT NULL,
			Order_PayPal_Receipt_Number text DEFAULT '' NOT NULL,
			Order_View_Count mediumint(9) DEFAULT 0 NOT NULL,
			Order_Tracking_Link_Clicked text DEFAULT '' NOT NULL,
			Order_Tracking_Link_Code text DEFAULT '' NOT NULL,
  			UNIQUE KEY id (Order_ID)
    		)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   		
   		dbDelta($sql);


   		$sql = "CREATE TABLE $this->order_statuses_table_name (
  			Order_Status_ID mediumint(9) NOT NULL AUTO_INCREMENT,
			Order_ID mediumint(9) DEFAULT 0 NOT NULL,
			Order_Status text DEFAULT '' NOT NULL,
			Order_Location text DEFAULT '' NOT NULL,
			Order_Internal_Status text DEFAULT '' NOT NULL,
			Order_Status_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
  			UNIQUE KEY id (Order_Status_ID)
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
	}

	/**
	 * Returns a single order given its order ID
	 * @since 3.0.0
	 */
	public function get_order_from_id( $order_id ) {
		global $wpdb;

		$db_order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->orders_table_name WHERE Order_ID=%d", $order_id ) );

		return $db_order;
	}

	/**
	 * Returns a single order given its Zendesk ID
	 * @since 3.0.0
	 */
	public function get_order_from_zendesk_id( $zendesk_id ) {
		global $wpdb;

		$db_order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->orders_table_name WHERE Zendesk_ID=%d", $zendesk_id ) );

		return ! empty( $db_order ) ? $db_order : null;
	}

	/**
	 * Returns a single order given its WooCommerce ID
	 * @since 3.0.0
	 */
	public function get_order_from_woocommerce_id( $post_id ) {
		global $wpdb;

		$db_order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->orders_table_name WHERE WooCommerce_ID=%d", $post_id ) );

		return ! empty( $db_order ) ? $db_order : null;
	}

	/**
	 * Returns a single order given its order number
	 * @since 3.0.0
	 */
	public function get_order_from_tracking_number( $order_number ) {
		global $wpdb;

		$db_order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->orders_table_name WHERE Order_Number=%s", $order_number ) );

		return ! empty( $db_order ) ? $db_order : null;
	}

	/**
	 * Returns orders matching the arguments supplied
	 * @since 3.0.0
	 */
	public function get_matching_orders( $args ) {

		$this->orders = array();

		$defaults = array(
			'orders_per_page'		=> 20,
			'order'					=> 'ASC',
			'paged'					=> 1,
			'date_range'			=> '',
		);

		$this->args = wp_parse_args( $args, $defaults );

		$this->prepare_args();

		$this->run_query();

		return $this->orders;
	}

	/**
	 * Return the counts for orders being displayed on the admin orders page
	 * @since 3.0.0
	 */
	public function get_order_counts( $args ) {
		global $wpdb;
		global $ewd_otp_controller;

		$this->args = $args;

		$this->prepare_args();

		$args = $this->args;

		$query_string = "SELECT Order_Status, count( * ) AS num_orders
			FROM $this->orders_table_name
			WHERE 1=%d
		";

		$query_args = array(1);

		if ( ! empty( $args['number'] ) ) {

			$query_string .= " AND Order_Number=%s";
			$query_args[] = sanitize_text_field( $args['number'] );
		}

		if ( ! empty( $args['display'] ) ) {

			$query_string .= " AND Order_Display=%s";
			$query_args[] = 'Yes';
		}

		if ( ! empty( $args['after'] ) ) {

			$query_string .= " AND Order_Status_Updated>=%s";
			$query_args[] = $args['after'];
		}

		if ( ! empty( $args['before'] ) ) {

			$query_string .= " AND Order_Status_Updated<=%s";
			$query_args[] = $args['before'];
		}

		if ( ! empty( $args['date'] ) ) {

			$query_string .= " AND DATE(Order_Status_Updated)=%s";
			$query_args[] = $args['date'];
		}

		$query_string .= " GROUP BY Order_Status";

		$count_results = $wpdb->get_results( $wpdb->prepare( $query_string, $query_args ) );
		
		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

		$counts = array();

		foreach( $statuses as $status ) {

			$counts[ sanitize_title( $status->status, '', 'ewd_otp' ) ] = 0;
		}

		foreach ( $count_results as $count ) {

			$counts[ sanitize_title( $count->Order_Status, '', 'ewd_otp' ) ] = $count->num_orders;
		}

		$counts['total'] = array_sum( $counts );

		return $counts;
	}

	/**
	 * Prepares the arguments before the query is run
	 * @since 3.0.0
	 */
	public function prepare_args() {

		$args = $this->args;

		if ( is_string( $args['date_range'] ) ) {

			if ( !empty( $args['start_date'] ) || !empty( $args['end_date'] ) ) {

				if ( !empty( $args['start_date'] ) ) {
					$args['after'] = sanitize_text_field( $args['start_date'] ) . ( ( isset( $args['start_time'] ) and $args['start_time'] ) ? $args['start_time'] : '' );
				}

				if ( !empty( $args['end_date'] ) ) {
					$args['before'] = sanitize_text_field( $args['end_date'] ) . ( ( isset( $args['end_time'] ) and $args['end_time'] ) ? $args['end_time'] : ' 23:59' );
				}
			} elseif ( $args['date_range'] === 'today' ) {

				$args['after'] = date( 'Y-m-d H:i:s', strtotime( 'today midnight' ) );
				$args['before'] = date( 'Y-m-d H:i:s', strtotime( 'tomorrow midnight' ) );

			} elseif ( $args['date_range'] === 'week' ) {

				$args['after'] = date( 'Y-m-d H:i:s', strtotime( 'monday this week' ) );
				$args['before'] = date( 'Y-m-d H:i:s', strtotime( 'sunday this week' ) );
			} elseif ( $args['date_range'] === 'past' ) {
				
				$args['before'] = date( 'Y-m-d H:i:s', strtotime( 'now' ) );
			}
		}

		$this->args = $args;

		return $this->args;
	}

	/**
	 * Create and run the SQL query based on the arguments received
	 * @since 3.0.0
	 */
	public function run_query() {
		global $wpdb;

		$args = $this->args;

		$query_string = "SELECT * FROM $this->orders_table_name WHERE 1=%d";

		$query_args = array( 1 );

		if ( ! empty( $args['id'] ) ) {

			$query_string .= " AND Order_ID=%d";
			$query_args[] = intval( $args['id'] );
		}

		if ( ! empty( $args['name'] ) ) {

			$query_string .= " AND Order_Name=%s";
			$query_args[] = $args['name'];
		}

		if ( ! empty( $args['number'] ) ) {

			$query_string .= " AND Order_Number=%s";
			$query_args[] = $args['number'];
		}

		if ( ! empty( $args['location'] ) ) {

			$query_string .= " AND Order_Location=%s";
			$query_args[] = $args['location'];
		}

		if ( ! empty( $args['status'] ) ) {

			if( is_array( $args['status'] ) ) {
				$stts_plchldr = implode( ', ', array_fill( 0, count( $args['status'] ), '%s' ) );
				$query_string .= " AND Order_Status IN ( $stts_plchldr )";
				$query_args   = array_merge( $query_args, $args['status'] );
			}
			else {
				$query_string .= " AND Order_Status=%s";
				$query_args[] = $args['status'];
			}
		}

		if ( ! empty( $args['email'] ) ) {

			$query_string .= " AND Order_Email=%s";
			$query_args[] = $args['email'];
		}

		if ( ! empty( $args['customer'] ) ) {

			if( is_array( $args['customer'] ) ) {
				$cstm_plchldr = implode( ', ', array_fill( 0, count( $args['customer'] ), '%d' ) );
				$query_string .= " AND Customer_ID IN ( $cstm_plchldr )";
				$query_args   = array_merge( $query_args, $args['customer'] );
			}
			else {
				$query_string .= " AND Customer_ID=%d";
				$query_args[] = intval( $args['customer'] );
			}
		}

		if ( ! empty( $args['sales_rep'] ) ) {

			if( is_array( $args['sales_rep'] ) ) {
				$sls_rp_plchldr = implode( ', ', array_fill( 0, count( $args['sales_rep'] ), '%d' ) );
				$query_string .= " AND Sales_Rep_ID IN ( $sls_rp_plchldr )";
				$query_args   = array_merge( $query_args, $args['sales_rep'] );
			}
			else {
				$query_string .= " AND Sales_Rep_ID=%d";
				$query_args[] = intval( $args['sales_rep'] );
			}
		}

		if ( ! empty( $args['display'] ) ) {

			if ( strtolower( $args['display'] ) == 'no' ){

				$query_string .= " AND Order_Display=%s";
				$query_args[] = 'No';
			}
			else {
				
				$query_string .= " AND Order_Display=%s";
				$query_args[] = 'Yes';
			}
		}

		if ( ! empty( $args['payment_completed'] ) ) {

			$query_string .= " AND Order_Payment_Completed=%s";
			$query_args[] = $args['payment_completed'];
		}

		if ( ! empty( $args['after'] ) ) {

			$query_string .= " AND Order_Status_Updated>=%s";
			$query_args[] = $args['after'];
		}

		if ( ! empty( $args['before'] ) ) {

			$query_string .= " AND Order_Status_Updated<=%s";
			$query_args[] = $args['before'];
		}

		if ( ! empty( $args['date'] ) ) {

			$query_string .= " AND DATE(Order_Status_Updated)=%s";
			$query_args[] = $args['date'];
		}

		if ( ! empty( $args['orderby'] ) ) {
			
			$query_string .= ' ORDER BY ' . esc_sql( $args['orderby'] ) . ' ' . ( strtolower( $args['order'] ) == 'desc' ? 'DESC' : 'ASC' );
		}

		if ( $args['orders_per_page'] > 0 ) {

			$query_string .= ' LIMIT ' . intval( ( $args['paged'] - 1 ) * $args['orders_per_page'] ) . ', ' . intval( $args['orders_per_page'] );
		}

		$db_orders = $wpdb->get_results( $wpdb->prepare( $query_string, $query_args ) );

		foreach ( $db_orders as $db_order ) {

			$order = new ewdotpOrder();

			$order->load_order( $db_order );

			$this->orders[] = $order;
		}
	}

	/**
	 * Returns the value for a given field/order id pair
	 * @since 3.0.0
	 */
	public function get_order_status_history( $order_id ) {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->order_statuses_table_name WHERE Order_ID=%d ORDER BY Order_Status_Created", $order_id ) ); 
	}

	/**
	 * Returns the value for a given field/order id pair
	 * @since 3.0.0
	 */
	public function get_order_field( $field, $order_id ) {
		global $wpdb;

		$db_order =  $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->orders_table_name WHERE Order_ID=%d", $order_id ) );

		$order = new ewdotpOrder();
		$order->load_order( $db_order );

		return ! empty( $order->$field ) ? $order->$field : '';
	}

	/**
	 * Returns the value for a given custom_field/order pair
	 * @since 3.0.0
	 */
	public function get_field_value( $custom_field_id, $order_id ) {
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare( "SELECT Meta_Value FROM $this->meta_table_name WHERE Field_ID=%d AND Order_ID=%d ORDER BY Meta_ID DESC", $custom_field_id, $order_id ) );
	}

	/**
	 * Accepts an order object, inserts it into the database, and returns the ID of the newly inserted order
	 * @since 3.0.0
	 */
	public function insert_order( $order ) {
		global $wpdb;
		global $ewd_otp_controller;

		$query_args = array(
			'Order_Name' 						=> ! empty( $order->name ) ? $order->name : '',
			'Order_Number' 						=> ! empty( $order->number ) ? $order->number : '',
			'Order_Status' 						=> ! empty( $order->status ) ? $order->status : '',
			'Order_External_Status' 			=> ! empty( $order->external_status ) ? $order->external_status : '',
			'Order_Location' 					=> ! empty( $order->location ) ? $order->location : '',
			'Order_Notes_Public' 				=> ! empty( $order->notes_public ) ? $order->notes_public : '',
			'Order_Notes_Private' 				=> ! empty( $order->notes_private ) ? $order->notes_private : '',
			'Order_Customer_Notes' 				=> ! empty( $order->customer_notes ) ? $order->customer_notes : '',
			'Order_Email' 						=> ! empty( $order->email ) ? $order->email : '',
			'Order_Phone_Number'				=> ! empty( $order->phone_number ) ? $order->phone_number : '',
			'Customer_ID' 						=> ! empty( $order->customer ) ? $order->customer : 0,
			'Sales_Rep_ID' 						=> ! empty( $order->sales_rep ) ? $order->sales_rep : 0,
			'WooCommerce_ID' 					=> ! empty( $order->woocommerce_id ) ? $order->woocommerce_id : 0,
			'Zendesk_ID' 						=> ! empty( $order->zendesk_id ) ? $order->zendesk_id : 0,
			'Order_Status_Updated' 				=> ! empty( $order->status_updated ) ? $order->status_updated : date( 'Y-m-d H:i:s' ),
			'Order_Display'						=> ! empty( $order->display ) ? 'Yes' : 'No',
			'Order_Payment_Price' 				=> ! empty( $order->payment_price ) ? $order->payment_price : '',
			'Order_Payment_Completed' 			=> ! empty( $order->payment_completed ) ? $order->payment_completed : '',
			'Order_PayPal_Receipt_Number' 		=> ! empty( $order->paypal_receipt_number ) ? $order->paypal_receipt_number : '',
			'Order_View_Count' 					=> ! empty( $order->views ) ? $order->views : 0,
			'Order_Tracking_Link_Clicked' 		=> ! empty( $order->tracking_link_clicked ) ? 'Yes' : 'No',
			'Order_Tracking_Link_Code' 			=> ! empty( $order->tracking_link_code ) ? $order->tracking_link_code : '',
		);

		$wpdb->insert(
			$this->orders_table_name,
			$query_args
		);
		
		$order_id = $wpdb->insert_id;

		if ( ! $order_id ) { return $order_id; }

		$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			if ( empty( $order->custom_fields[ $custom_field->id ] ) ) { continue; }

			$query_args = array(
				'Field_ID'			=> $custom_field->id,
				'Order_ID'			=> $order_id,
				'Meta_Value'		=> $order->custom_fields[ $custom_field->id ]
			);

			$wpdb->insert(
				$this->meta_table_name,
				$query_args
			);
		}

		return $order_id;
	}

	/**
	 * Accepts an order object, updates it in the database, and returns the ID if successful or false otherwise
	 * @since 3.0.0
	 */
	public function update_order( $order ) {
		global $wpdb;
		global $ewd_otp_controller;

		if ( empty( $order->id ) ) { return false; }

		$query_args = array(
			'Order_Name' 						=> ! empty( $order->name ) ? $order->name : '',
			'Order_Number' 						=> ! empty( $order->number ) ? $order->number : '',
			'Order_Status' 						=> ! empty( $order->status ) ? $order->status : '',
			'Order_External_Status' 			=> ! empty( $order->external_status ) ? $order->external_status : '',
			'Order_Location' 					=> ! empty( $order->location ) ? $order->location : '',
			'Order_Notes_Public' 				=> ! empty( $order->notes_public ) ? $order->notes_public : '',
			'Order_Notes_Private' 				=> ! empty( $order->notes_private ) ? $order->notes_private : '',
			'Order_Customer_Notes' 				=> ! empty( $order->customer_notes ) ? $order->customer_notes : '',
			'Order_Email' 						=> ! empty( $order->email ) ? $order->email : '',
			'Order_Phone_Number'				=> ! empty( $order->phone_number ) ? $order->phone_number : '',
			'Customer_ID' 						=> ! empty( $order->customer ) ? $order->customer : 0,
			'Sales_Rep_ID' 						=> ! empty( $order->sales_rep ) ? $order->sales_rep : 0,
			'WooCommerce_ID' 					=> ! empty( $order->woocommerce_id ) ? $order->woocommerce_id : 0,
			'Zendesk_ID' 						=> ! empty( $order->zendesk_id ) ? $order->zendesk_id : 0,
			'Order_Status_Updated' 				=> ! empty( $order->status_updated ) ? $order->status_updated : date( 'Y-m-d H:i:s' ),
			'Order_Display'						=> ! empty( $order->display ) ? 'Yes' : 'No',
			'Order_Payment_Price' 				=> ! empty( $order->payment_price ) ? $order->payment_price : '',
			'Order_Payment_Completed' 			=> ! empty( $order->payment_completed ) ? 'Yes' : 'No',
			'Order_PayPal_Receipt_Number' 		=> ! empty( $order->paypal_receipt_number ) ? $order->paypal_receipt_number : '',
			'Order_View_Count' 					=> ! empty( $order->views ) ? $order->views : 0,
			'Order_Tracking_Link_Clicked' 		=> ! empty( $order->tracking_link_clicked ) ? 'Yes' : 'No',
			'Order_Tracking_Link_Code' 			=> ! empty( $order->tracking_link_code ) ? $order->tracking_link_code : '',
		);

		$wpdb->update(
			$this->orders_table_name,
			$query_args,
			array( 'Order_ID' => $order->id )
		);

		$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$wpdb->get_var( $wpdb->prepare( "SELECT Meta_Value from $this->meta_table_name WHERE Field_ID=%d AND Order_ID=%d ORDER BY Meta_ID DESC", $custom_field->id, $order->id ) );

			$update = $wpdb->num_rows ? true : false;

			if ( empty( $order->custom_fields[ $custom_field->id ] ) ) { 

				$where_args = array(
					'Field_ID'	=> $custom_field->id,
					'Order_ID'	=> $order->id,
				);

				$wpdb->delete(
					$this->meta_table_name,
					$where_args 
				);
			}
			elseif ( $update ) {
				
				$query_args = array(
					'Meta_Value' 	=> $order->custom_fields[ $custom_field->id ]
				);

				$where_args = array(
					'Field_ID'	=> $custom_field->id,
					'Order_ID'	=> $order->id,
				);

				$wpdb->update(
					$this->meta_table_name,
					$query_args,
					$where_args
				);
			}
			else {
				
				$query_args = array(
					'Meta_Value' 	=> $order->custom_fields[ $custom_field->id ],
					'Field_ID'		=> $custom_field->id,
					'Order_ID'		=> $order->id,
				);

				$wpdb->insert(
					$this->meta_table_name,
					$query_args
				);
			}
		}

		return $order->id;
	}

	/**
	 * Adds a new order status to the database
	 * @since 3.0.0
	 */
	public function update_order_status( $order ) {
		global $wpdb;

		$query_args = array(
			'Order_ID' 							=> $order->id,
			'Order_Status' 						=> ! empty( $order->status ) ? $order->status : '',
			'Order_Location' 					=> ! empty( $order->location ) ? $order->location : '',
			'Order_Internal_Status' 			=> ! empty( $order->status ) ? $order->status : '',
			'Order_Status_Created' 				=> ! empty( $order->status_updated ) ? $order->status_updated : date( 'Y-m-d H:i:s' ),
		);
		
		$wpdb->insert(
			$this->order_statuses_table_name,
			$query_args
		);
	}

	/**
	 * Removs an existing order status
	 * @since 3.0.1
	 */
	public function delete_order_status( $order_status_id ) {
		global $wpdb;

		$wpdb->delete(
			$this->order_statuses_table_name,
			array( 'Order_Status_ID' => $order_status_id)
		);
	}

	/**
	 * Accepts an order id, deletes the corresponding order
	 * @since 3.0.0
	 */
	public function delete_order( $order_id ) {
		global $wpdb;

		$wpdb->delete(
			$this->orders_table_name,
			array( 'Order_ID' => $order_id )
		);

		$wpdb->delete(
			$this->order_statuses_table_name,
			array( 'Order_ID' => $order_id )
		);

		$wpdb->delete(
			$this->meta_table_name,
			array( 'Order_ID' => $order_id )
		);
	}

	/**
	 * Accepts an order id, sets the payment_received field for the corresponding order to Yes
	 * @since 3.0.0
	 */
	public function set_order_paid( $order_id ) {
		global $wpdb;

		$query_args = array(
			'Order_Payment_Completed' => 'Yes'
		);

		$wpdb->update(
			$this->orders_table_name,
			$query_args,
			array( 'Order_ID' => $order_id )
		);
	}

	/**
	 * Accepts an order id, updates the status of the corresponding order
	 * @since 3.0.0
	 */
	public function set_order_status( $order_id, $status ) {
		global $wpdb;

		$order = new ewdotpOrder();
		$order->load_order_from_id( $order_id );
		
		$order->set_status( $status );
	}

	/**
	 * Increment the views for a given order_id
	 * @since 3.0.4
	 */
	public function increase_order_views( $order_id ) { 
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "UPDATE $this->orders_table_name SET Order_View_Count=Order_View_Count+1 WHERE Order_ID=%d", $order_id ) );
	}

	/**
	 * Accepts an email and order id, and verify that they match the saved data,
	 * @since 3.0.0
	 */
	public function verify_order_email( $email, $order_id ) {
		global $wpdb;

		$order_email = $wpdb->get_var( $wpdb->prepare( "SELECT Order_Email FROM $this->orders_table_name WHERE Order_ID=%d", $order_id ) );

		if ( $order_email == $email ) { 

			return true;
		}

		return false;
	}
}
}