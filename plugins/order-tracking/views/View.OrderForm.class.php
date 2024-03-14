<?php

/**
 * Class to display the order tracking form on the front end.
 *
 * @since 3.0.0
 */
class ewdotpViewOrderForm extends ewdotpView {

	/**
	 * Render the view and enqueue required stylesheets
	 * @since 3.0.0
	 */
	public function render() {
		global $ewd_otp_controller;

		$this->set_order_form_options();

		$this->get_order_results();

		// Add any dependent stylesheets or javascript
		$this->enqueue_assets();

		// Add css classes to the slider
		$this->classes = $this->get_classes();

		ob_start();

		$this->add_custom_styling();

		$template = $this->find_template( 'order-form' );
		
		if ( $template ) {
			include( $template );
		}

		$output = ob_get_clean();

		return apply_filters( 'ewd_otp_order_form_output', $output, $this );
	}

	/**
	 * Print the matching order, if any
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_order_results() {

		if ( empty( $this->order ) ) { return; }

		$this->order->increase_view_count();
		
		$template = $this->find_template( 'order-results' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the order number input
	 *
	 * @since 3.0.0
	 */
	public function print_order_number_input() {
		
		$template = $this->find_template( 'form-identifier-number' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the order email input, if enabled
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_order_email_input() {
		global $ewd_otp_controller;

		if ( ! $ewd_otp_controller->settings->get_setting( 'email-verification' ) ) { return; }
		
		$template = $this->find_template( 'form-email' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print any custom fields that should be displayed
	 *
	 * @since 3.0.0
	 */
	public function print_order_custom_fields() {
		global $ewd_otp_controller;

		$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			if ( ! $custom_field->front_end_display ) { continue; }
			
			if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->order ) or empty( $this->order->custom_fields[ $custom_field->id ] ) ) ) { continue; }

			$custom_field->value = $this->order->custom_fields[ $custom_field->id ];

			$this->custom_field = $custom_field;

			$this->print_custom_field();
		}
	}

	/**
	 * Print links to all orders that exist
	 *
	 * @since 3.0.0
	 */
	public function print_all_available_order_links() {
		global $ewd_otp_controller;
		
		$template = $this->find_template( 'order-form-all-available-order-links' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the order tracking graphic, if enabled
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_order_graphic() {
		global $ewd_otp_controller;

		if ( ! in_array( 'order_graphic', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return; }
		
		if ( in_array( $ewd_otp_controller->settings->get_setting( 'tracking-graphic' ), array( 'default', 'streamlined', 'sleek' ) ) ) {
		
			$template = $this->find_template( 'order-graphic-image' );
		}
		else { $template = $this->find_template( 'order-graphic-progress' ); }
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the order payment link, if enabled
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_order_payment() {
		global $ewd_otp_controller;

		if ( empty( $ewd_otp_controller->settings->get_setting( 'allow-order-payments' ) ) ) { return; }

		if ( empty( $this->order ) or empty( $this->order->payment_price ) ) { return; }
		
		$template = $this->find_template( 'order-payment' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the order map, if enabled
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_order_map() {
		global $ewd_otp_controller;
		
		if ( ! in_array( 'order_map', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return; }

		if ( empty( $this->order ) or empty( $this->order->current_location->latitude ) or empty( $this->order->current_location->longitude ) ) { return; }
		
		$template = $this->find_template( 'order-map' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the order statuses header
	 *
	 * @since 3.0.0
	 */
	public function print_order_statuses_header() {
		global $ewd_otp_controller;
		
		$template = $this->find_template( 'order-statuses-header' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the order statuses
	 *
	 * @since 3.0.0
	 */
	public function print_order_statuses() {
		global $ewd_otp_controller;
		
		$template = $this->find_template( 'order-statuses' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the order statuses
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_order_update_location_and_status() {
		global $ewd_otp_controller;

		$current_user_cannot = !current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) );
		$order_has_no_sales_rep = 1 > intval( $this->order->sales_rep );
		$different_sales_rep = get_current_user_id() !== intval( $this->order->get_sales_rep_wp_id() );

		if ( $current_user_cannot and ( $order_has_no_sales_rep or $different_sales_rep) ) {
			return;
		}
		
		$template = $this->find_template( 'order-update-status-and-location' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the order form submit button
	 *
	 * @since 3.0.0
	 */
	public function print_order_form_submit() {
		
		$template = $this->find_template( 'form-submit' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Return all available statuses
	 *
	 * @since 3.0.0
	 */
	public function get_possible_statuses() {
		global $ewd_otp_controller;

		return ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );
	}

	/**
	 * Return all available locations
	 *
	 * @since 3.0.0
	 */
	public function get_possible_locations() {
		global $ewd_otp_controller;

		return ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'locations' ) );
	}

	/**
	 * Return the lowest percentage status
	 *
	 * @since 3.0.0
	 */
	public function get_starting_status() {
		global $ewd_otp_controller;

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

		$starting_status = reset( $statuses );

		return $starting_status->status;
	}

	/**
	 * Return the highest percentage status
	 *
	 * @since 3.0.0
	 */
	public function get_ending_status() {
		global $ewd_otp_controller;

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

		$highest_status = reset( $statuses );

		foreach ( $statuses as $status ) {

			if ( $status->percentage > $highest_status->percentage ) { $highest_status = $status; }
		}

		return $highest_status->status;
	}

	/**
	 * Returns all of the orders that currently exist
	 *
	 * @since 3.0.0
	 */
	public function get_all_orders() {
		global $ewd_otp_controller;

		$args = array(
			'orders_per_page'	=> -1
		);

		return $ewd_otp_controller->order_manager->get_matching_orders( $args );
	}

	/**
	 * Returns the placeholder for the order number field
	 *
	 * @since 3.0.0
	 */
	public function get_identifier_placeholder_label() {
		
		return $this->get_label( 'label-order-form-number-placeholder' );
	}

	/**
	 * Returns the placeholder for the order email field
	 *
	 * @since 3.0.0
	 */
	public function get_email_placeholder_label() {
		
		return $this->get_label( 'label-order-form-email-placeholder' );
	}

	/**
	 * Get the initial order form css classes
	 * @since 3.0.0
	 */
	public function get_classes( $classes = array() ) {
		global $ewd_otp_controller;

		$classes = array_merge(
			$classes,
			array(
				'ewd-otp-form',
				'ewd-otp-tracking-form-div'
			)
		);

		if ( ! empty( $ewd_otp_controller->settings->get_setting( 'disable-ajax-loading' ) ) ) {

			$classes[] = 'ewd-otp-disable-ajax';
		}

		return apply_filters( 'ewd_otp_order_form_classes', $classes, $this );
	}

	/**
	 * Allow some parameters to be overwritten with URL parameters, find a specific order
	 * @since 3.0.0
	 */
	public function get_order_results() {
		global $ewd_otp_controller;

		if ( empty( $_POST['ewd_otp_form_type'] ) or $_POST['ewd_otp_form_type'] != 'order_form' ) { return; } 

		if ( empty( $_POST['ewd_otp_identifier_number'] ) ) { return; }

		$order = new ewdotpOrder();

		$order->load_order_from_tracking_number( sanitize_text_field( $_POST['ewd_otp_identifier_number'] ) );

		if ( empty( $order->id ) ) {

			$this->update_message = __( 'There are no order statuses for tracking number: ', 'order-tracking' ) . sanitize_text_field( $_POST['ewd_otp_identifier_number'] );

			return;
		}

		if ( $ewd_otp_controller->settings->get_setting( 'email-verification' ) and 
			 ! $order->verify_order_email( sanitize_email( $_POST['ewd_otp_form_email'] ) ) and 
			 ! $order->verify_order_user( get_current_user_id() ) and 
			 ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) ) ) { 

			$this->update_message = __( 'The email submitted does not match the email associated with this order.', 'order-tracking' );

			return; 
		}

		$this->order = $order;

		$this->order->load_order_status_history();

		$this->customer = new ewdotpCustomer();

		$this->customer->load_customer_from_id( $this->order->customer );

		$this->sales_rep = new ewdotpSalesRep();

		$this->sales_rep->load_sales_rep_from_id( $this->order->sales_rep );
	}

	/**
	 * Allow some parameters to be overwritten with URL parameters, find a specific order
	 * @since 3.0.0
	 */
	public function set_request_parameters() {
		global $ewd_otp_controller;

		if ( empty( $_REQUEST['tracking_number'] ) ) { return; } 

		$order = new ewdotpOrder();

		$order->load_order_from_tracking_number( sanitize_text_field( $_REQUEST['tracking_number'] ) );

		if ( $ewd_otp_controller->settings->get_setting( 'email-verification' ) and ! $order->verify_order_email( ( ! empty( $_REQUEST['order_email'] ) ? sanitize_email( $_REQUEST['order_email'] ) : '' ) ) ) { return; }

		if ( ! empty( $_REQUEST['tracking_link_code'] ) ) {

			$order->set_tracking_link_clicked();
		}

		$this->order = $order;

		$this->order->load_order_status_history();

		$this->customer = new ewdotpCustomer();

		$this->customer->load_customer_from_id( $this->order->customer );

		$this->sales_rep = new ewdotpSalesRep();

		$this->sales_rep->load_sales_rep_from_id( $this->order->sales_rep );
	}

	/**
	 * Add in default options when displaying in the admin appointments area
	 *
	 * @since 3.0.0
	 */
	public function set_order_form_options() {
		global $ewd_otp_controller;

		$this->nonce = wp_create_nonce( basename( __FILE__ ) );
		$this->show_orders = strtolower( $this->show_orders ) == 'yes' ? true : false;
		$this->order_form_title = ! empty( $this->order_form_title ) ? $this->order_form_title : $this->get_label( 'label-order-form-title' );
		$this->order_form_instructions = ! empty( $this->order_instructions ) ? $this->order_instructions : $this->get_option( 'form-instructions' );
		$this->order_field_text = ! empty( $this->order_field_text ) ? $this->order_field_text : $this->get_label( 'label-order-form-number' );
		$this->email_field_text = ! empty( $this->email_field_text ) ? $this->email_field_text : $this->get_label( 'label-order-form-email' );
		$this->customer_notes_submit = ! empty( $this->notes_submit ) ? $this->notes_submit : $this->get_label( 'label-order-add-note-button' );
		$this->submit_text = ! empty( $this->submit_text ) ? $this->submit_text : $this->get_label( 'label-order-form-button' );
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
			'customer_notes_submit'		=> $this->customer_notes_submit
		);

		$ewd_otp_controller->add_front_end_php_data( 'ewd-otp-js', 'ewd_otp_php_data', $args );

		wp_enqueue_script( 'ewd-otp-js' );
	}
}
