<?php

/**
 * Class to display a customer order form on the front end.
 *
 * @since 3.0.0
 */
class ewdotpViewCustomerOrderForm extends ewdotpView {

	/**
	 * Render the view and enqueue required stylesheets
	 * @since 3.0.0
	 */
	public function render() {
		global $ewd_otp_controller;

		$this->set_customer_order_options();

		// Add any dependent stylesheets or javascript
		$this->enqueue_assets();

		// Add css classes to the slider
		$this->classes = $this->get_classes();

		ob_start();

		$this->add_custom_styling();

		$template = $this->find_template( 'customer-order-form' );
		
		if ( $template ) {
			include( $template );
		}

		$output = ob_get_clean();

		return apply_filters( 'ewd_otp_customer_order_form_output', $output, $this );
	}

	/**
	 * Prints a custom field, using the correct template based on it's type
	 *
	 * @since 3.0.0
	 */
	public function print_customer_order_field( $custom_field ) {
		global $ewd_otp_controller;
		
		$this->custom_field = $custom_field;

		$this->custom_field->field_value = ! empty( $this->order ) ? $ewd_otp_controller->order_manager->get_field_value( $custom_field->id, $this->order->id ) : '';
		$this->custom_field->field_value = $custom_field->type == 'checkbox' ? explode( ',', $this->custom_field->field_value ) : $this->custom_field->field_value;

		if ( $custom_field->type == 'text' ) { $template = $this->find_template( 'customer-order-field-text' ); }
		elseif ( $custom_field->type == 'number' ) { $template = $this->find_template( 'customer-order-field-number' ); }
		elseif ( $custom_field->type == 'textarea' ) { $template = $this->find_template( 'customer-order-field-textarea' ); }
		elseif ( $custom_field->type == 'select' ) { $template = $this->find_template( 'customer-order-field-select' ); }
		elseif ( $custom_field->type == 'radio' ) { $template = $this->find_template( 'customer-order-field-radio' ); }
		elseif ( $custom_field->type == 'checkbox' ) { $template = $this->find_template( 'customer-order-field-checkbox' ); }
		elseif ( $custom_field->type == 'link' ) { $template = $this->find_template( 'customer-order-field-link' ); }
		elseif ( $custom_field->type == 'date' ) { $template = $this->find_template( 'customer-order-field-date' ); }
		elseif ( $custom_field->type == 'datetime' ) { $template = $this->find_template( 'customer-order-field-datetime' ); }
		else { $template = null; }

		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Display the captcha field, if selected via settings
	 *
	 * @since 3.1.1
	 */
	public function maybe_display_captcha_field() {
		global $ewd_otp_controller;
		
		if ( ! $ewd_otp_controller->settings->get_setting( 'use-captcha' ) ) { return; }
		
		$template = $this->find_template( 'customer-order-recaptcha' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the edit appointment area, if enabled
	 *
	 * @since 3.0.0
	 */
	public function print_customer_order_form_submit() {
		
		$template = $this->find_template( 'form-submit' );
		
		if ( $template ) {
			include( $template );
		}
	}

	public function get_sales_reps() {
		global $ewd_otp_controller;

		$args = array(
			'sales_reps_per_page' => 1000
		);

		return $ewd_otp_controller->sales_rep_manager->get_matching_sales_reps( $args );
	}

	/**
	 * Get the initial submit faq css classes
	 * @since 3.0.0
	 */
	public function get_classes( $classes = array() ) {
		global $ewd_otp_controller;

		$classes = array_merge(
			$classes,
			array(
				'ewd-otp-customer-order-form',
				'ewd-otp-form'
			)
		);

		return apply_filters( 'ewd_otp_customer_order_form_classes', $classes, $this );
	}

	/**
	 * Allow some parameters to be overwritten with URL parameters, to pay for/cancel/update a specific booking
	 * @since 3.0.0
	 */
	public function set_request_parameters() {
		global $ewd_otp_controller;

		
	}

	/**
	 * Add in default options when displaying in the admin appointments area
	 *
	 * @since 3.0.0
	 */
	public function set_customer_order_options() {
		global $ewd_otp_controller;

		$this->nonce = wp_create_nonce( basename( __FILE__ ) );
		$this->location = ! empty( $this->location ) ? $this->location : '';
		$this->customer_order_form_title = ! empty( $this->customer_order_form_title ) ? $this->customer_order_form_title : __( 'Customer Order Form', 'order-tracking' );
		$this->customer_order_form_instructions = ! empty( $this->customer_order_form_instructions ) ? $this->customer_order_form_instructions : __( 'Enter information about your order using the form below.', 'order-tracking' );
		$this->customer_name_field_text = ! empty( $this->customer_name_field_text ) ? $this->customer_name_field_text : $this->get_label( 'label-customer-order-name' );
		$this->customer_email_field_text = ! empty( $this->customer_email_field_text ) ? $this->customer_email_field_text : $this->get_label( 'label-customer-order-email' );
		$this->customer_notes_field_text = ! empty( $this->customer_notes_field_text ) ? $this->customer_notes_field_text : $this->get_label( 'label-customer-order-notes' );
		$this->submit_text = ! empty( $this->submit_text ) ? $this->submit_text : $this->get_label( 'label-customer-order-button' );
	}

	/**
	 * Enqueue the necessary CSS and JS files
	 * @since 3.0.0
	 */
	public function enqueue_assets() {
		global $ewd_otp_controller;

		wp_enqueue_style( 'ewd-otp-css' );
		
		$args = array(
			'nonce' 			=> wp_create_nonce( 'ewd-otp-js' ),
			'default_date' 		=> $ewd_otp_controller->settings->get_setting( 'calendar-offset' ),
		);

		$ewd_otp_controller->add_front_end_php_data( 'ewd-otp-js', 'ewd_otp_php_data', $args );

		wp_enqueue_script( 'ewd-otp-js' );

		if ( empty( $ewd_otp_controller->settings->get_setting( 'use-captcha' ) ) )  { return; }

		wp_enqueue_script( 'ewd-otp-google-recaptcha', 'https://www.google.com/recaptcha/api.js?hl=' . get_locale() . '&render=explicit&onload=ewdotpLoadRecaptcha' );
		wp_enqueue_script( 'ewd-otp-process-recaptcha', EWD_OTP_PLUGIN_URL . '/assets/js/ewd-otp-recaptcha.js', array( 'ewd-otp-google-recaptcha' ) );

		$args = array(
			'site_key'	=> $ewd_otp_controller->settings->get_setting( 'captcha-site-key' ),
		);

		$ewd_otp_controller->add_front_end_php_data( 'ewd-otp-process-recaptcha', 'ewd_otp_recaptcha', $args );
	}
}
