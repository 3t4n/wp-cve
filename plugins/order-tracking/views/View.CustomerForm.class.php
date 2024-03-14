<?php

/**
 * Class to display the customer form on the front end.
 *
 * @since 3.0.0
 */
class ewdotpViewCustomerForm extends ewdotpView {

	// Holds any matching orders based on submitted Customer ID and (optionally) email
	public $customer_orders = array();

	/**
	 * Render the view and enqueue required stylesheets
	 * @since 3.0.0
	 */
	public function render() {
		global $ewd_otp_controller;

		$this->set_customer_orders();

		$this->set_customer_options();

		// Add any dependent stylesheets or javascript
		$this->enqueue_assets();

		// Add css classes to the slider
		$this->classes = $this->get_classes();

		ob_start();

		$this->add_custom_styling();

		$template = $this->find_template( 'customer-form' );
		
		if ( $template ) {
			include( $template );
		}

		$output = ob_get_clean();

		return apply_filters( 'ewd_otp_customer_form_output', $output, $this );
	}

	/**
	 * Print the customer's orders, if any
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_customer_results() {

		$form_submitted = isset($_POST['ewd_otp_form_type']) && 'customer_form' == $_POST['ewd_otp_form_type'];

		if ( $form_submitted && empty( $this->customer_orders ) ) {

			$this->error_message = __( 'No orders were found associated with the submitted customer number', 'order-tracking' );

			$this->print_error_message();

			return;
		}

		if( empty( $this->customer_orders ) ) { return; }

		$template = $this->find_template( 'customer-results' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the customer ID input
	 *
	 * @since 3.0.0
	 */
	public function print_customer_id_input() {
		
		$template = $this->find_template( 'form-identifier-number' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the customer email input, if enabled
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_customer_email_input() {
		global $ewd_otp_controller;

		if ( ! $ewd_otp_controller->settings->get_setting( 'email-verification' ) ) { return; }
		
		$template = $this->find_template( 'form-email' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the customer's name, if selected
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_customer_name() {
		global $ewd_otp_controller;

		if ( ! in_array( 'customer_name', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->customer ) or empty( $this->customer->name ) ) ) { return; }
		
		$template = $this->find_template( 'customer-name' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the customer's email, if selected
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_customer_email() {
		global $ewd_otp_controller;

		if ( ! in_array( 'customer_email', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->customer ) or empty( $this->customer->email ) ) ) { return; }
		
		$template = $this->find_template( 'customer-email' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print any custom fields that should be displayed
	 *
	 * @since 3.0.0
	 */
	public function print_customer_custom_fields() {
		global $ewd_otp_controller;

		$custom_fields = $ewd_otp_controller->settings->get_customer_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			if ( ! $custom_field->front_end_display ) { continue; }

			if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->customer ) or empty( $this->customer->custom_fields[ $custom_field->id ] ) ) ) { continue; }

			$custom_field->value = $this->customer->custom_fields[ $custom_field->id ];

			$this->custom_field = $custom_field;

			$this->print_custom_field();
		}
	}

	/**
	 * Print the header for the customer orders table
	 *
	 * @since 3.0.0
	 */
	public function print_customer_orders_header() {
		
		$template = $this->find_template( 'matching-order-header' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the orders for the customer orders table
	 *
	 * @since 3.0.0
	 */
	public function print_customer_orders() {
		
		$template = $this->find_template( 'matching-order' );

		foreach ( $this->customer_orders as $order ) {

			$this->current_order = $order;

			if ( $template ) {
				include( $template );
			}
		}
	}

	/**
	 * Print the download button for customer orders, if enabled
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_customer_download_button() {
		global $ewd_otp_controller;

		if ( empty( $ewd_otp_controller->settings->get_setting( 'allow-customer-downloads' ) ) ) { return; }
		
		$template = $this->find_template( 'customer-download-button' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the customer form submit button
	 *
	 * @since 3.0.0
	 */
	public function print_customer_form_submit() {
		
		$template = $this->find_template( 'form-submit' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Returns the placeholder for the customer number field
	 *
	 * @since 3.0.0
	 */
	public function get_identifier_placeholder_label() {
		
		return $this->get_label( 'label-customer-form-number-placeholder' );
	}

	/**
	 * Returns the placeholder for the customer email field
	 *
	 * @since 3.0.0
	 */
	public function get_email_placeholder_label() {
		
		return $this->get_label( 'label-customer-form-email-placeholder' );
	}

	/**
	 * Returns true if a link to order tracking results should be included, false otherwise
	 *
	 * @since 3.0.0
	 */
	public function include_separate_tracking_link() {
		
		if ( empty( $this->get_option( 'disable-ajax-loading' ) ) ) { return false; }

		if ( empty( $this->get_option( 'tracking-page-url' ) ) ) { return false; }

		return true;
	}

	/**
	 * Get the initial customer form css classes
	 * @since 3.0.0
	 */
	public function get_classes( $classes = array() ) {
		global $ewd_otp_controller;

		$classes = array_merge(
			$classes,
			array(
				'ewd-otp-customer-form-div',
				'ewd-otp-form'
			)
		);

		if ( ! empty( $ewd_otp_controller->settings->get_setting( 'disable-ajax-loading' ) ) ) {

			$classes[] = 'ewd-otp-disable-ajax';
		}

		return apply_filters( 'ewd_otp_customer_form_classes', $classes, $this );
	}

	/**
	 * Allow some parameters to be overwritten with $_REQUEST parameters
	 * @since 3.0.0
	 */
	public function set_request_parameters() {
		global $ewd_otp_controller;

		if ( empty( $_POST['ewd_otp_form_type'] ) or $_POST['ewd_otp_form_type'] != 'customer_form' ) { return; } 

		if ( empty( $_POST['ewd_otp_identifier_number'] ) ) { return; }

		$customer = new ewdotpCustomer();

		$customer->load_customer_from_number( sanitize_text_field( trim( $_POST['ewd_otp_identifier_number'] ) ) );

		if ( $ewd_otp_controller->settings->get_setting( 'email-verification' ) and ! $customer->verify_customer_email( sanitize_email( $_POST['ewd_otp_form_email'] ) ) ) { return; }

		$this->customer = $customer;
	}

	/**
	 * If a customer is set, fetch their matching orders
	 * @since 3.0.0
	 */
	public function set_customer_orders() {

		if ( empty( $this->customer->id ) ) { return; }

		$args = array(
			'after'				=> date( 'Y-m-d H:i:s', strtotime( '-365 days' ) ),
			'orders_per_page' 	=> -1
		);

		$this->customer_orders = $this->customer->get_customer_orders( $args );
	}

	/**
	 * Add in default options when displaying in the admin appointments area
	 *
	 * @since 3.0.0
	 */
	public function set_customer_options() {
		global $ewd_otp_controller;

		$this->nonce = wp_create_nonce( basename( __FILE__ ) );
		$this->customer_form_title = ! empty( $this->order_form_title ) ? $this->order_form_title : $this->get_label( 'label-customer-form-title' );
		$this->customer_form_instructions = ! empty( $this->order_instructions ) ? $this->order_instructions : $this->get_label( 'label-customer-form-instructions' );
		$this->order_field_text = ! empty( $this->order_field_text ) ? $this->order_field_text : $this->get_label( 'label-customer-form-number' );
		$this->email_field_text = ! empty( $this->email_field_text ) ? $this->email_field_text : $this->get_label( 'label-customer-form-email' );
		$this->submit_text = ! empty( $this->submit_text ) ? $this->submit_text : $this->get_label( 'label-customer-form-button' );
	}

	/**
	 * Enqueue the necessary CSS and JS files
	 * @since 3.0.0
	 */
	public function enqueue_assets() {
		global $ewd_otp_controller;

		wp_enqueue_style( 'ewd-otp-css' );
		
		$args = array(
			'nonce' 					=> wp_create_nonce( 'ewd-otp-js' ),
			'retrieving_results' 		=> $ewd_otp_controller->settings->get_setting( 'label-retrieving-results' ),
			'customer_notes_submit'		=> $this->get_label( 'label-order-add-note-button' )
		);

		$ewd_otp_controller->add_front_end_php_data( 'ewd-otp-js', 'ewd_otp_php_data', $args );

		wp_enqueue_script( 'ewd-otp-js' );
	}
}
