<?php
/**
 * Class to act as a wrapper for a single order
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdotpOrder' ) ) {
class ewdotpOrder {

	// The database ID of the current order
	public $id = 0;

	// Stores all of the custom field values for an order
	public $custom_fields = array();

	// Stores all of the previous statuses for an order
	public $status_history = array();

	/**
	 * Load an order based on a specific database record
	 * @since 3.0.0
	 */
	public function load_order( $db_order ) {
		global $ewd_otp_controller;

		$this->id 						= is_object( $db_order ) ? $db_order->Order_ID : 0;

		$this->name						= is_object( $db_order ) ? $db_order->Order_Name : '';
		$this->number					= is_object( $db_order ) ? $db_order->Order_Number : '';
		$this->email					= is_object( $db_order ) ? $db_order->Order_Email : '';
		$this->phone_number				= is_object( $db_order ) ? $db_order->Order_Phone_Number : '';

		$this->status 					= is_object( $db_order ) ? $db_order->Order_Status : '';
		$this->external_status			= is_object( $db_order ) ? $db_order->Order_External_Status : '';
		$this->location					= is_object( $db_order ) ? $db_order->Order_Location : '';

		$this->notes_public				= is_object( $db_order ) ? $db_order->Order_Notes_Public : '';
		$this->notes_private			= is_object( $db_order ) ? $db_order->Order_Notes_Private : '';
		$this->customer_notes			= is_object( $db_order ) ? $db_order->Order_Customer_Notes : '';

		$this->customer					= is_object( $db_order ) ? $db_order->Customer_ID : 0;
		$this->sales_rep				= is_object( $db_order ) ? $db_order->Sales_Rep_ID : 0;
		$this->woocommerce_id			= is_object( $db_order ) ? $db_order->WooCommerce_ID : 0;
		$this->zendesk_id				= is_object( $db_order ) ? $db_order->Zendesk_ID : 0;

		$this->status_updated			= is_object( $db_order ) ? $db_order->Order_Status_Updated : '';
		$this->status_updated_fmtd 		= $this->date_formatted( $this->status_updated );

		$this->display					= is_object( $db_order ) ? ( $db_order->Order_Display == 'Yes' ? true : false ) : false;

		$this->payment_price			= is_object( $db_order ) ? $db_order->Order_Payment_Price : 0;
		$this->payment_completed		= is_object( $db_order ) ? ( $db_order->Order_Payment_Completed == 'Yes' ? true : false ) : false;
		$this->paypal_receipt_number	= is_object( $db_order ) ? $db_order->Order_PayPal_Receipt_Number : '';

		$this->views					= is_object( $db_order ) ? $db_order->Order_View_Count : 0;

		$this->tracking_link_clicked	= is_object( $db_order ) ? ( $db_order->Order_Tracking_Link_Clicked == 'Yes' ? true : false ) : false;
		$this->tracking_link_code		= is_object( $db_order ) ? $db_order->Order_Tracking_Link_Code : '';

		$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$this->custom_fields[ $custom_field->id ] = $ewd_otp_controller->order_manager->get_field_value( $custom_field->id, $this->id );
		}

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

		foreach ( $statuses as $status ) {

			if ( $this->external_status == $status->status ) { $this->current_status = $status; }
		}

		$locations = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'locations' ) );

		foreach ( $locations as $location ) {

			if ( $this->location == $location->name ) { $this->current_location = $location; }
		}
	}

	/**
	 * Loads an order based on its ID
	 * @since 3.0.0
	 */
	public function load_order_from_id( $order_id ) {
		global $ewd_otp_controller;

		$db_order = $ewd_otp_controller->order_manager->get_order_from_id( $order_id );

		$this->load_order( $db_order );
	}

	/**
	 * Loads an order based on its tracking number
	 * @since 3.0.0
	 */
	public function load_order_from_tracking_number( $order_number ) {
		global $ewd_otp_controller;

		$db_order = $ewd_otp_controller->order_manager->get_order_from_tracking_number( $order_number );

		$this->load_order( $db_order );
	}

	/**
	 * Returns the WordPress user ID for this order's sales rep
	 * @since 3.0.0
	 */
	public function get_sales_rep_wp_id() {

		if ( empty( $this->sales_rep ) ) { return 0; }

		$sales_rep = new ewdotpSalesRep();

		$sales_rep->load_sales_rep_from_id( $this->sales_rep );

		return ! empty( $sales_rep->wp_id ) ? $sales_rep->wp_id : 0;
	}

	/**
	 * Loads an order's status history
	 * @since 3.0.0
	 */
	public function load_order_status_history() {
		global $ewd_otp_controller;

		$this->status_history = array();
		
		$db_status_history = $ewd_otp_controller->order_manager->get_order_status_history( $this->id );

		foreach ( $db_status_history as $db_status ) {

			$status_history_object = new stdClass();

			$status_history_object->id 				= $db_status->Order_Status_ID;
			$status_history_object->status 			= $db_status->Order_Status;
			$status_history_object->location 		= $db_status->Order_Location;
			$status_history_object->internal_status = $db_status->Order_Internal_Status;
			$status_history_object->updated 		= $db_status->Order_Status_Created;
			$status_history_object->updated_fmtd 	= $this->date_formatted( $status_history_object->updated );

			$this->status_history[] = $status_history_object;
		}
	}

	/**
	 * Verify that the submitted email matches the one belonging to the order
	 * @since 3.0.0
	 */
	public function verify_order_email( $email_address ) {

		if ( $email_address == $this->email ) { return true; }

		return false;
	}

	/**
	 * Verify that the submitted user ID matches either the customer or sales rep for this order
	 * @since 3.0.15
	 */
	public function verify_order_user( $user_id ) {
		global $ewd_otp_controller;

		if ( empty( $user_id ) ) { return false; }

		if ( $this->customer == $ewd_otp_controller->customer_manager->get_customer_id_from_wp_id( $user_id ) ) { return true; }

		if ( $this->sales_rep == $ewd_otp_controller->sales_rep_manager->get_sales_rep_id_from_wp_id( $user_id ) ) { return true; }

		return false;
	}

	/**
	 * Validates a submitted order, and calls insert_order if validated
	 * @since 3.0.0
	 */
	public function process_client_order_submission() {
		global $ewd_otp_controller;

		$this->validate_submission();
		if ( $this->is_valid_submission() === false ) {
			return false;
		}
		
		$this->insert_order();

		if ( ! empty( $this->status ) ) { $this->insert_order_status(); }

		do_action( 'ewd_otp_insert_customer_order', $this );

		return true;
	}

	/**
	 * Validate submission data. Expects to find data in $_POST.
	 * @since 3.0.0
	 */
	public function validate_submission() {
		global $ewd_otp_controller;

		$this->validation_errors = array();

		// reCAPTCHA
		if ( $ewd_otp_controller->settings->get_setting( 'use-captcha' ) == 'recaptcha' ) {

			if ( ! isset( $_POST['g-recaptcha-response'] ) ) {

				$this->validation_errors[] = array(
					'field'		=> 'recaptcha',
					'error_msg'	=> 'No reCAPTCHA code',
					'message'	=> __( 'Please fill out the reCAPTCHA box  before submitting.', 'order-tracking' ),
				);

			}
			else {

				$secret_key = $ewd_otp_controller->settings->get_setting( 'captcha-secret-key' );
				$captcha = sanitize_text_field( $_POST['g-recaptcha-response'] );

				$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode( $secret_key ) .  '&response=' . urlencode( $captcha );
				$json_response = file_get_contents( $url );
				$response = json_decode( $json_response );

				$reCaptcha_error = false;
				if ( json_last_error() != JSON_ERROR_NONE ) {

					$response = new stdClass();
					$response->success = false;
					$reCaptcha_error = true;

					if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

						error_log( 'ORP reCAPTCHA error. Raw respose: ' . print_r( array( $json_response ), true ) );
					}
				}

				if ( ! $response->success ) {

					$message = __( 'Please fill out the reCAPTCHA box again and re-submit.', 'ultimate-reviews' );
						
					if ( $reCaptcha_error ) {

						$message .= __( 'If you encounter reCAPTCHA error multiple times, please contact us.', 'ultimate-reviews' );
					}

					$this->validation_errors[] = array(
						'field'		=> 'recaptcha',
						'error_msg'	=> 'Invalid reCAPTCHA code',
						'message'	=> $message,
					);
				}
			}
		}

		// Order Basics
		$this->number = $ewd_otp_controller->settings->get_setting( 'customer-order-number-prefix' ) . ewd_random_string( 5 ) . $ewd_otp_controller->settings->get_setting( 'customer-order-number-suffix' );

		$this->name = empty( $_POST['ewd_otp_order_name'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_order_name'] );
		$this->email = empty( $_POST['ewd_otp_order_email'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_order_email'] );
		$this->location = empty( $_POST['ewd_otp_location'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_location'] );
		$this->customer_notes = empty( $_POST['ewd_otp_customer_notes'] ) ? '' : sanitize_textarea_field( $_POST['ewd_otp_customer_notes'] );

		$this->external_status = $this->status = $ewd_otp_controller->settings->get_setting( 'default-customer-order-form-status' );
		$this->display = true;

		if ( empty( $this->status ) ) {

			$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

			$status = reset( $statuses );

			$this->external_status = $this->status = $status->status;
		}

		// Customer
		$user = wp_get_current_user(); 

		if ( $user ) {

			$this->customer = $ewd_otp_controller->customer_manager->get_customer_id_from_wp_id( $user->ID );
		}
		elseif ( function_exists( 'FEUP_User' ) ) {

			$feup_user = new FEUP_User();

			if ( $feup_user->Is_Logged_In() ) {

				$this->customer = $ewd_otp_controller->customer_manager->get_customer_id_from_feup_id( $feup_user->Get_User_ID() );
			}
		}

		if ( empty( $this->customer ) and ! empty( $this->email ) and $ewd_otp_controller->settings->get_setting( 'allow-assign-orders-to-customers' ) ) {

			$this->customer = $ewd_otp_controller->customer_manager->get_customer_id_from_email( $this->email );
		}

		// Sales Rep
		if ( ! empty( $this->customer ) ) {

			$this->sales_rep = $ewd_otp_controller->customer_manager->get_customer_field( 'Sales_Rep_ID', $this->customer );
		}

		if ( empty( $this->sales_rep ) ){
		
			$this->sales_rep = empty( $_POST['ewd_otp_sales_rep'] ) ? $ewd_otp_controller->settings->get_setting( 'default-sales-rep' ) : intval( $_POST['ewd_otp_sales_rep'] );
		}
		
		$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$input_name = 'ewd_otp_custom_field_' . $custom_field->id;

			if ( $custom_field->type == 'checkbox' ) { $this->custom_fields[ $custom_field->id ] = ( empty( $_POST[ $input_name ] ) or ! is_array( $_POST[ $input_name ] ) ) ? array() : sanitize_text_field( implode( ',', $_POST[ $input_name ] ) ); }
			elseif ( $custom_field->type == 'textarea' ) { $this->custom_fields[ $custom_field->id ] = empty( $_POST[ $input_name ] ) ? false : sanitize_textarea_field( $_POST[ $input_name ] ); }
			else { $this->custom_fields[ $custom_field->id ] = empty( $_POST[ $input_name ] ) ? false : sanitize_text_field( $_POST[ $input_name ] ); }
		}
		
		do_action( 'ewd_otp_validate_order_submission', $this );
	}

	/**
	 * Validates a submitted order, and calls insert_order if validated
	 * @since 3.0.0
	 */
	public function process_admin_order_submission() {
		global $ewd_otp_controller;

		$this->validate_admin_submission();

		if ( $this->is_valid_submission() === false ) {
			return false;
		}
		
		if ( $this->id ) { 

			$old_status = $ewd_otp_controller->order_manager->get_order_field( 'status', $this->id );

			$this->update_order();

			if ( $this->status != $old_status ) {

				$this->insert_order_status();
			}

			do_action( 'ewd_otp_admin_order_updated', $this, $old_status );
		}
		else { 

			$this->insert_order(); 

			$this->insert_order_status();

			do_action( 'ewd_otp_admin_order_inserted', $this );
		}

		return true;
	}

	/**
	 * Validate submission data entered via the admin page
	 * @since 3.0.0
	 */
	public function validate_admin_submission() {
		global $ewd_otp_controller;

		$this->validation_errors = array();

		if ( ! isset( $_POST['ewd-otp-admin-nonce'] ) 
		    or ! wp_verify_nonce( $_POST['ewd-otp-admin-nonce'], 'ewd-otp-admin-nonce' ) 
		) {
			$this->validation_errors[] = __( 'The request has been rejected because it does not appear to have come from this site.', 'order-tracking' );
		}

		// Order Data
		$this->number = empty( $_POST['ewd_otp_number'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_number'] );
		$this->name = empty( $_POST['ewd_otp_name'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_name'] );
		$this->email = empty( $_POST['ewd_otp_email'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_email'] );

		$this->status = $this->external_status = empty( $_POST['ewd_otp_status'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_status'] );
		$this->location = empty( $_POST['ewd_otp_location'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_location'] );
		$this->display = ( empty( $_POST['ewd_otp_display'] ) or $_POST['ewd_otp_display'] == 'no' ) ? false : true;

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

		// Overwrite the external status of the order if the new status is an internal one
		foreach ( $statuses as $status ) {

			if ( $this->status == $status->status and $status->internal == 'yes' ) {

				$this->external_status = $ewd_otp_controller->order_manager->get_order_field( 'Order_External_Status', $this->id );
			}
		}

		$this->notes_public = empty( $_POST['ewd_otp_public_notes'] ) ? '' : sanitize_textarea_field( $_POST['ewd_otp_public_notes'] );
		$this->notes_private = empty( $_POST['ewd_otp_private_notes'] ) ? '' : sanitize_textarea_field( $_POST['ewd_otp_private_notes'] );
		$this->customer_notes = empty( $_POST['ewd_otp_customer_notes'] ) ? '' : sanitize_textarea_field( $_POST['ewd_otp_customer_notes'] );

		$this->customer = empty( $_POST['ewd_otp_customer'] ) ? 0 : sanitize_text_field( $_POST['ewd_otp_customer'] );
		$this->sales_rep = empty( $_POST['ewd_otp_sales_rep'] ) ? 0 : sanitize_text_field( $_POST['ewd_otp_sales_rep'] );

		$this->payment_price			= empty( $_POST['ewd_otp_payment_price'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_payment_price'] );
		$this->payment_completed		= ( ! empty( $_POST['ewd_otp_payment_completed'] ) and $_POST['ewd_otp_payment_completed'] == 'yes' ) ? true : false;
		$this->paypal_receipt_number	= empty( $_POST['ewd_otp_paypal_receipt_number'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_paypal_receipt_number'] );
		
		$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$input_name = 'ewd-otp-custom-field-' . $custom_field->id;

			if ( $custom_field->type == 'checkbox' ) { $this->custom_fields[ $custom_field->id ] = ( empty( $_POST[ $input_name ] ) or ! is_array( $_POST[ $input_name ] ) ) ? '' : sanitize_text_field( implode( ',', $_POST[ $input_name ] ) ); }
			elseif ( $custom_field->type == 'textarea' ) { $this->custom_fields[ $custom_field->id ] = empty( $_POST[ $input_name ] ) ? false : sanitize_textarea_field( $_POST[ $input_name ] ); }
			elseif ( $custom_field->type == 'file' or $custom_field->type == 'image' ) { $this->custom_fields[ $custom_field->id ] = ! empty( $_FILES[ $input_name ]['name'] ) ? $this->handle_file_upload( $input_name ) : ( ! empty( $_POST[ $input_name ] ) ? sanitize_text_field( $_POST[ $input_name ] ) : '' ); }
			else { $this->custom_fields[ $custom_field->id ] = empty( $_POST[ $input_name ] ) ? false : sanitize_text_field( $_POST[ $input_name ] ); }
		}

		do_action( 'ewd_otp_validate_order_submission', $this );
	}

	/**
	 * Takes an input name, uploads the file from that input if it exists, returns the file URL
	 * @since 3.0.0
	 */
	public function handle_file_upload( $input_name ) {

		if ( ! function_exists( 'wp_handle_upload' ) ) {
		   
		    require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$args = array(
			'test_form' => false
		);

		$uploaded_file = wp_handle_upload( $_FILES[ $input_name ], $args );

		if ( $uploaded_file && empty( $uploaded_file['error'] ) ) {
			
			return $uploaded_file['url'];
		}
		else {

			return false;
		}
	}

	/**
	 * Check if submission is valid
	 * @since 3.0.0
	 */
	public function is_valid_submission() {

		if ( !count( $this->validation_errors ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Takes a status, correctly updates the orders status based on whether its an internal or external status
	 * @since 3.0.0
	 */
	public function set_status( $new_status ) {
		global $ewd_otp_controller;

		$old_status = $ewd_otp_controller->order_manager->get_order_field( 'status', $this->id );

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

		$internal_status = false;

		foreach ( $statuses as $status ) {

			if ( $status->status == $new_status and $status->internal == 'yes' ) { $internal_status = true; }
		}

		if ( ! $internal_status ) {

			$this->external_status = $new_status;
		}

		$this->status = $new_status;
		
		$this->update_order();

		$this->insert_order_status();
		
		if ( ! $internal_status ) {

			do_action( 'ewd_otp_status_updated', $this, $old_status );
		}
	}

	/**
	 * Takes a tracking link code and updates the tracking code value in the database for this order
	 * @since 3.0.0
	 */
	public function set_tracking_link_code( $tracking_code ) {

		$this->tracking_link_code = $tracking_code;

		$this->update_order();
	}

	/**
	 * Takes a tracking link code and verifies that it matches the one currently saved for the order
	 * @since 3.0.0
	 */
	public function verify_tracking_link( $tracking_code ) {

		return $this->tracking_link_code == $tracking_code ? true : false;
	}

	/**
	 * Update the tracking link clicked flag for this order
	 * @since 3.0.0
	 */
	public function set_tracking_link_clicked() {

		$this->tracking_link_clicked = true;

		$this->update_order();
	}

	/**
	 * Increase the view count for this order
	 * @since 3.0.0
	 */
	public function increase_view_count() {
		global $ewd_otp_controller;
		
		$ewd_otp_controller->order_manager->increase_order_views( $this->id );
	}

	/**
	 * Insert a new order into the database
	 * @since 3.0.0
	 */
	public function insert_order() {
		global $ewd_otp_controller;

		$id = $ewd_otp_controller->order_manager->insert_order( $this );

		$this->id = $id;
	}

	public function insert_order_status() {
		global $ewd_otp_controller;

		if ( ! $this->id ) { return; }

		$this->status_updated = date( 'Y-m-d H:i:s' );
		
		$ewd_otp_controller->order_manager->update_order_status( $this );
	}

	/**
	 * Update an order already in the database
	 * @since 3.0.0
	 */
	public function update_order() {
		global $ewd_otp_controller;

		$ewd_otp_controller->order_manager->update_order( $this );

	}

	/**
	 * Returns the date/time formatted based on the use WP timezone settings and formats
	 * @since 3.0.0
	 */
	public function date_formatted( $input ) {
		global $ewd_otp_controller;

		$output = $input;

		if ( $ewd_otp_controller->settings->get_setting( 'use-wp-timezone' ) ) {
			
			$wp_tz = wp_timezone();
			$current_tz = new DateTime( $input, new DateTimeZone( date_default_timezone_get() ) );
			$offset = $wp_tz->getOffset( $current_tz );

			$output = date(
				get_option( 'date_format' ).' '.get_option( 'time_format' ),
				( $current_tz->format( 'U' ) + $offset )
			);
		}

		return $output;
	}
}
}