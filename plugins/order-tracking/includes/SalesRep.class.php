<?php
/**
 * Class to act as a wrapper for a single sales rep
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdotpSalesRep' ) ) {
class ewdotpSalesRep {

	// The database ID of the current sales rep
	public $id = 0;

	// Stores all of the custom field values for an sales rep
	public $custom_fields = array();

	/**
	 * Load a sales rep based on a specific database record
	 * @since 3.0.0
	 */
	public function load_sales_rep( $db_sales_rep ) {
		global $ewd_otp_controller;

		$this->id 						= is_object( $db_sales_rep ) ? $db_sales_rep->Sales_Rep_ID : 0;

		$this->number					= is_object( $db_sales_rep ) ? $db_sales_rep->Sales_Rep_Number : '';

		$this->first_name				= is_object( $db_sales_rep ) ? $db_sales_rep->Sales_Rep_First_Name : '';
		$this->last_name				= is_object( $db_sales_rep ) ? $db_sales_rep->Sales_Rep_Last_Name : '';
		$this->email					= is_object( $db_sales_rep ) ? $db_sales_rep->Sales_Rep_Email : '';
		$this->phone_number				= is_object( $db_sales_rep ) ? $db_sales_rep->Sales_Rep_Phone_Number : '';

		$this->wp_id					= is_object( $db_sales_rep ) ? $db_sales_rep->Sales_Rep_WP_ID : 0;

		$custom_fields = $ewd_otp_controller->settings->get_sales_rep_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$this->custom_fields[ $custom_field->id ] = $ewd_otp_controller->sales_rep_manager->get_field_value( $custom_field->id, $this->id );
		}
	}

	/**
	 * Load a sales rep based on their sales rep ID
	 * @since 3.0.0
	 */
	public function load_sales_rep_from_id( $sales_rep_id ) {
		global $ewd_otp_controller;

		$db_sales_rep = $ewd_otp_controller->sales_rep_manager->get_sales_rep_from_id( $sales_rep_id );

		if( $db_sales_rep ) {

			$this->load_sales_rep( $db_sales_rep );

		}
	}

	/**
	 * Load a sales rep based on their sales rep number
	 * @since 3.0.0
	 */
	public function load_sales_rep_from_number( $sales_rep_number ) {
		global $ewd_otp_controller;

		$sales_rep_id = $ewd_otp_controller->sales_rep_manager->get_sales_rep_id_from_number( $sales_rep_number );

		if ( empty( $sales_rep_id ) ) { return false; }

		$db_sales_rep = $ewd_otp_controller->sales_rep_manager->get_sales_rep_from_id( $sales_rep_id );

		if ( empty( $db_sales_rep ) ) { return false; }

		$this->load_sales_rep( $db_sales_rep );
	}

	/**
	 * Load a sales rep based on their WP ID
	 * @since 3.0.0
	 */
	public function load_sales_rep_from_wp_id( $wp_id ) {
		global $ewd_otp_controller;

		$db_sales_rep = $ewd_otp_controller->sales_rep_manager->get_sales_rep_from_wp_id( $wp_id );

		$this->load_sales_rep( $db_sales_rep );
	}

	/**
	 * Verify that the submitted email matches the one belonging to the sales rep
	 * @since 3.0.0
	 */
	public function verify_sales_rep_email( $email_address ) {

		if ( $email_address == $this->email ) { return true; }

		return false;
	}

	/**
	 * Get orders belonging to this sales rep
	 * @since 3.0.0
	 */
	public function get_sales_rep_orders( $args ) {
		global $ewd_otp_controller;

		$args['sales_rep'] = $this->id;

		return $ewd_otp_controller->order_manager->get_matching_orders( $args );
	}

	/**
	 * Validates a submitted sales rep, and calls sales rep if validated
	 * @since 3.0.0
	 */
	public function process_admin_sales_rep_submission() {
		global $ewd_otp_controller;

		$this->validate_admin_submission();
		if ( $this->is_valid_submission() === false ) {
			return false;
		}
		
		if ( $this->id ) { $this->update_sales_rep(); }
		else { 

			$this->insert_sales_rep(); 

			do_action( 'ewd_otp_admin_insert_sales_rep', $this );
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

		// Sales Rep Data
		$this->number = empty( $_POST['ewd_otp_number'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_number'] );

		$this->first_name = empty( $_POST['ewd_otp_first_name'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_first_name'] );
		$this->last_name = empty( $_POST['ewd_otp_last_name'] ) ? '' : sanitize_text_field( $_POST['ewd_otp_last_name'] );
		$this->email = empty( $_POST['ewd_otp_email'] ) ? '' : sanitize_email( $_POST['ewd_otp_email'] );

		$this->wp_id = empty( $_POST['ewd_otp_wp_id'] ) ? 0 : intval( $_POST['ewd_otp_wp_id'] );
	
		$custom_fields = $ewd_otp_controller->settings->get_sales_rep_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$input_name = 'ewd-otp-custom-field-' . $custom_field->id;

			if ( $custom_field->type == 'checkbox' ) { $this->custom_fields[ $custom_field->id ] = ( empty( $_POST[ $input_name ] ) or ! is_array( $_POST[ $input_name ] ) ) ? array() : sanitize_text_field( implode( ',', $_POST[ $input_name ] ) ); }
			elseif ( $custom_field->type == 'textarea' ) { $this->custom_fields[ $custom_field->id ] = empty( $_POST[ $input_name ] ) ? false : sanitize_textarea_field( $_POST[ $input_name ] ); }
			elseif ( $custom_field->type == 'file' or $custom_field->type == 'image' ) { $this->custom_fields[ $custom_field->id ] = ! empty( $_FILES[ $input_name ]['name'] ) ? $this->handle_file_upload( $input_name ) : ( ! empty( $_POST[ $input_name ] ) ? sanitize_text_field( $_POST[ $input_name ] ) : '' ); }
			else { $this->custom_fields[ $custom_field->id ] = empty( $_POST[ $input_name ] ) ? false : sanitize_text_field( $_POST[ $input_name ] ); }
		}

		do_action( 'ewd_otp_validate_sales_rep_submission', $this );
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
	 * Insert a new sales rep into the database
	 * @since 3.0.0
	 */
	public function insert_sales_rep() {
		global $ewd_otp_controller;

		$id = $ewd_otp_controller->sales_rep_manager->insert_sales_rep( $this );

		$this->id = $id;
	}

	/**
	 * Update an sales rep already in the database
	 * @since 3.0.0
	 */
	public function update_sales_rep() {
		global $ewd_otp_controller;
		
		$ewd_otp_controller->sales_rep_manager->update_sales_rep( $this );
	}
}
}