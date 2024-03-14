<?php

/**
 * Class to display the sales rep form on the front end.
 *
 * @since 3.0.0
 */
class ewdotpViewSalesRepForm extends ewdotpView {

	// Holds any matching orders based on submitted Sales Rep ID and (optionally) email
	public $sales_rep_orders = array();

	/**
	 * Render the view and enqueue required stylesheets
	 * @since 3.0.0
	 */
	public function render() {
		global $ewd_otp_controller;

		$this->set_sales_rep_orders();

		$this->set_sales_rep_options();

		// Add any dependent stylesheets or javascript
		$this->enqueue_assets();

		// Add css classes to the slider
		$this->classes = $this->get_classes();

		ob_start();

		$this->add_custom_styling();

		$template = $this->find_template( 'sales-rep-form' );
		
		if ( $template ) {
			include( $template );
		}

		$output = ob_get_clean();

		return apply_filters( 'ewd_otp_sales_rep_form_output', $output, $this );
	}

	/**
	 * Print the sales rep's orders, if any
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_sales_rep_results() {

		$form_submitted = isset($_POST['ewd_otp_form_type']) && 'sales_rep_form' == $_POST['ewd_otp_form_type'];

		if ( $form_submitted && empty( $this->sales_rep_orders ) ) {

			$this->error_message = __( 'No orders were found associated with the submitted sales rep number', 'order-tracking' );

			$this->print_error_message();

			return;
		}

		if ( empty( $this->sales_rep_orders ) ) { return; }
		
		$template = $this->find_template( 'sales-rep-results' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the sales rep ID input
	 *
	 * @since 3.0.0
	 */
	public function print_sales_rep_identifier_input() {
		
		$template = $this->find_template( 'form-identifier-number' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the sales rep email input, if enabled
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_sales_rep_email_input() {
		global $ewd_otp_controller;

		if ( ! $ewd_otp_controller->settings->get_setting( 'email-verification' ) ) { return; }
		
		$template = $this->find_template( 'form-email' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the sales rep's first name, if selected
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_sales_rep_first_name() {
		global $ewd_otp_controller;

		if ( ! in_array( 'sales_rep_first_name', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->sales_rep ) or empty( $this->sales_rep->first_name ) ) ) { return; }
		
		$template = $this->find_template( 'sales-rep-first-name' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the sales rep's last name, if selected
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_sales_rep_last_name() {
		global $ewd_otp_controller;

		if ( ! in_array( 'sales_rep_last_name', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->sales_rep ) or empty( $this->sales_rep->last_name ) ) ) { return; }
		
		$template = $this->find_template( 'sales-rep-last-name' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the sales rep's email, if selected
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_sales_rep_email() {
		global $ewd_otp_controller;

		if ( ! in_array( 'sales_rep_email', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->sales_rep ) or empty( $this->sales_rep->email ) ) ) { return; }
		
		$template = $this->find_template( 'sales-rep-email' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print any custom fields that should be displayed
	 *
	 * @since 3.0.0
	 */
	public function print_sales_rep_custom_fields() {
		global $ewd_otp_controller;

		$custom_fields = $ewd_otp_controller->settings->get_sales_rep_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			if ( ! $custom_field->front_end_display ) { continue; }

			if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->sales_rep ) or empty( $this->sales_rep->custom_fields[ $custom_field->id ] ) ) ) { continue; }

			$custom_field->value = $this->sales_rep->custom_fields[ $custom_field->id ];

			$this->custom_field = $custom_field;

			$this->print_custom_field();
		}
	}

	/**
	 * Print the header for the sales rep orders table
	 *
	 * @since 3.0.0
	 */
	public function print_sales_rep_orders_header() {
		
		$template = $this->find_template( 'matching-order-header' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the orders for the sales rep orders table
	 *
	 * @since 3.0.0
	 */
	public function print_sales_rep_orders() {
		
		$template = $this->find_template( 'matching-order' );

		foreach ( $this->sales_rep_orders as $order ) {

			$this->current_order = $order;

			if ( $template ) {
				include( $template );
			}
		}
	}

	/**
	 * Print the download button for sales rep orders, if enabled
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_sales_rep_download_button() {
		global $ewd_otp_controller;

		if ( empty( $ewd_otp_controller->settings->get_setting( 'allow-sales-rep-downloads' ) ) ) { return; }
		
		$template = $this->find_template( 'sales-rep-download-button' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the sales rep form submit button
	 *
	 * @since 3.0.0
	 */
	public function print_sales_rep_form_submit() {
		
		$template = $this->find_template( 'form-submit' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Returns the placeholder for the sales rep number field
	 *
	 * @since 3.0.0
	 */
	public function get_identifier_placeholder_label() {
		
		return $this->get_label( 'label-sales-rep-form-number-placeholder' );
	}

	/**
	 * Returns the placeholder for the sales rep email field
	 *
	 * @since 3.0.0
	 */
	public function get_email_placeholder_label() {
		
		return $this->get_label( 'label-sales-rep-form-email-placeholder' );
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
	 * Get the initial sales rep css classes
	 * @since 3.0.0
	 */
	public function get_classes( $classes = array() ) {
		global $ewd_otp_controller;

		$classes = array_merge(
			$classes,
			array(
				'ewd-otp-sales-rep-form-div',
				'ewd-otp-form'
			)
		);

		if ( ! empty( $ewd_otp_controller->settings->get_setting( 'disable-ajax-loading' ) ) ) {

			$classes[] = 'ewd-otp-disable-ajax';
		}

		return apply_filters( 'ewd_otp_sales_rep_form_classes', $classes, $this );
	}

	/**
	 * Allow some parameters to be overwritten with $_REQUEST parameters
	 * @since 3.0.0
	 */
	public function set_request_parameters() {
		global $ewd_otp_controller;

		if ( empty( $_POST['ewd_otp_form_type'] ) or $_POST['ewd_otp_form_type'] != 'sales_rep_form' ) { return; } 

		if ( empty( $_POST['ewd_otp_identifier_number'] ) ) { return; }

		$sales_rep = new ewdotpSalesRep();

		$sales_rep->load_sales_rep_from_number( sanitize_text_field( trim( $_POST['ewd_otp_identifier_number'] ) ) );

		if ( $ewd_otp_controller->settings->get_setting( 'email-verification' ) and ! $sales_rep->verify_sales_rep_email( sanitize_email( $_POST['ewd_otp_form_email'] ) ) ) { return; }

		$this->sales_rep = $sales_rep;
	}

	/**
	 * If a sales rep is set, fetch their matching orders
	 * @since 3.0.0
	 */
	public function set_sales_rep_orders() {

		if ( empty( $this->sales_rep->id ) ) { return; }

		$args = array(
			'after'				=> date( 'Y-m-d H:i:s', strtotime( '-365 days' ) ),
			'orders_per_page' 	=> -1
		);

		$this->sales_rep_orders = $this->sales_rep->get_sales_rep_orders( $args );
	}

	/**
	 * Add in default options when displaying in the admin appointments area
	 *
	 * @since 3.0.0
	 */
	public function set_sales_rep_options() {
		global $ewd_otp_controller;

		$this->nonce = wp_create_nonce( basename( __FILE__ ) );
		$this->sales_rep_form_title = ! empty( $this->order_form_title ) ? $this->order_form_title : $this->get_label( 'label-sales-rep-form-title' );
		$this->sales_rep_form_instructions = ! empty( $this->order_instructions ) ? $this->order_instructions : $this->get_label( 'label-sales-rep-form-instructions' );
		$this->order_field_text = ! empty( $this->order_field_text ) ? $this->order_field_text : $this->get_label( 'label-sales-rep-form-number' );
		$this->email_field_text = ! empty( $this->email_field_text ) ? $this->email_field_text : $this->get_label( 'label-sales-rep-form-email' );
		$this->submit_text = ! empty( $this->submit_text ) ? $this->submit_text : $this->get_label( 'label-sales-rep-form-button' );
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
