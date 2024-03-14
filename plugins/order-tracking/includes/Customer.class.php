<?php
/**
 * Class to act as a wrapper for a single customer
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdotpCustomer' ) ) {
class ewdotpCustomer {

	// The database ID of the current customer
	public $id = 0;

	// Stores all of the custom field values for an customer
	public $custom_fields = array();

	/**
	 * Load an customer based on a specific database record
	 * @since 3.0.0
	 */
	public function load_customer( $db_customer ) {
		global $ewd_otp_controller;

		$this->id 						= is_object( $db_customer ) ? $db_customer->Customer_ID : 0;

		$this->number					= is_object( $db_customer ) ? $db_customer->Customer_Number : '';

		$this->name						= is_object( $db_customer ) ? $db_customer->Customer_Name : '';
		$this->email					= is_object( $db_customer ) ? $db_customer->Customer_Email : '';

		$this->sales_rep				= is_object( $db_customer ) ? $db_customer->Sales_Rep_ID : 0;
		$this->wp_id					= is_object( $db_customer ) ? $db_customer->Customer_WP_ID : 0;
		$this->feup_id					= is_object( $db_customer ) ? $db_customer->Customer_FEUP_ID : 0;

		$custom_fields = $ewd_otp_controller->settings->get_customer_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$this->custom_fields[ $custom_field->id ] = $ewd_otp_controller->customer_manager->get_field_value( $custom_field->id, $this->id );
		}
	}

	public function load_customer_from_id( $customer_id ) {
		global $ewd_otp_controller;

		$db_customer = $ewd_otp_controller->customer_manager->get_customer_from_id( $customer_id );

		if( $db_customer ) {

			$this->load_customer( $db_customer );

		}
	}

	public function load_customer_from_number( $customer_number ) {
		global $ewd_otp_controller;

		$customer_id = $ewd_otp_controller->customer_manager->get_customer_id_from_number( $customer_number );

		if ( empty( $customer_id ) ) { return false; }

		$db_customer = $ewd_otp_controller->customer_manager->get_customer_from_id( $customer_id );
		
		if( $db_customer ) {

			$this->load_customer( $db_customer );

		}
	}

	/**
	 * Verify that the submitted email matches the one belonging to the customer
	 * @since 3.0.0
	 */
	public function verify_customer_email( $email_address ) {

		if ( $email_address == $this->email ) { return true; }

		return false;
	}

	/**
	 * Get orders belonging to this customer
	 * @since 3.0.0
	 */
	public function get_customer_orders( $args ) {
		global $ewd_otp_controller;

		$args['customer'] = $this->id;

		return $ewd_otp_controller->order_manager->get_matching_orders( $args );
	}

	/**
	 * Validates a submitted customer, and calls insert_customer if validated
	 * @since 3.0.0
	 */
	public function process_admin_customer_submission() {
		global $ewd_otp_controller;

		$this->validate_admin_submission();
		if ( $this->is_valid_submission() === false ) {
			return false;
		}
		
		if ( $this->id ) { $this->update_customer(); }
		else { 

			$this->insert_customer(); 

			do_action( 'ewd_otp_admin_insert_customer', $this );
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

		// Customer Data
		$this->number = empty( $_POST['ewd_otp_number'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_number'] );

		$this->name = empty( $_POST['ewd_otp_name'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_name'] );
		$this->email = empty( $_POST['ewd_otp_email'] ) ? '' : sanitize_email( $_POST['ewd_otp_email'] );
		
		$this->sales_rep = empty( $_POST['ewd_otp_sales_rep'] ) ? 0 : intval( $_POST['ewd_otp_sales_rep'] );

		$this->wp_id = empty( $_POST['ewd_otp_wp_id'] ) ? 0 : intval( $_POST['ewd_otp_wp_id'] );
		$this->feup_id = empty( $_POST['ewd_otp_feup_id'] ) ? 0 : intval( $_POST['ewd_otp_feup_id'] );
	
		$custom_fields = $ewd_otp_controller->settings->get_customer_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$input_name = 'ewd-otp-custom-field-' . $custom_field->id;

			if ( $custom_field->type == 'checkbox' ) { $this->custom_fields[ $custom_field->id ] = ( empty( $_POST[ $input_name ] ) or ! is_array( $_POST[ $input_name ] ) ) ? array() : sanitize_text_field( implode( ',', $_POST[ $input_name ] ) ); }
			elseif ( $custom_field->type == 'textarea' ) { $this->custom_fields[ $custom_field->id ] = empty( $_POST[ $input_name ] ) ? false : sanitize_textarea_field( $_POST[ $input_name ] ); }
			elseif ( $custom_field->type == 'file' or $custom_field->type == 'image' ) { $this->custom_fields[ $custom_field->id ] = ! empty( $_FILES[ $input_name ]['name'] ) ? $this->handle_file_upload( $input_name ) : ( ! empty( $_POST[ $input_name ] ) ? sanitize_text_field( $_POST[ $input_name ] ) : '' ); }
			else { $this->custom_fields[ $custom_field->id ] = empty( $_POST[ $input_name ] ) ? false : sanitize_text_field( $_POST[ $input_name ] ); }
		}

		do_action( 'ewd_otp_validate_customer_submission', $this );
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
	 * Insert a new customer into the database
	 * @since 3.0.0
	 */
	public function insert_customer() {
		global $ewd_otp_controller;

		$id = $ewd_otp_controller->customer_manager->insert_customer( $this );

		$this->id = $id;
	}

	/**
	 * Update an customer already in the database
	 * @since 3.0.0
	 */
	public function update_customer() {
		global $ewd_otp_controller;

		$ewd_otp_controller->customer_manager->update_customer( $this );

	}
}
}