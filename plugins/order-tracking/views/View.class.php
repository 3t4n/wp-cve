<?php

/**
 * Base class for any view requested on the front end.
 *
 * @since 3.0.0
 */
class ewdotpView extends ewdotpBase {

	/**
	 * Post type to render
	 */
	public $post_type = null;

	/**
	 * Map types of content to the template which will render them
	 */
	public $content_map = array(
		'title'							 => 'content/title',
	);

	/**
	 * Initialize the class
	 * @since 3.0.0
	 */
	public function __construct( $args ) {

		// Parse the values passed
		$this->parse_args( $args );
		
		// Filter the content map so addons can customize what and how content
		// is output. Filters are specific to each view, so for this base view
		// you would use the filter 'us_content_map_ewdotpView'
		$this->content_map = apply_filters( 'ewd_otp_content_map_' . get_class( $this ), $this->content_map );

	}

	/**
	 * Render the view and enqueue required stylesheets
	 *
	 * @note This function should always be overridden by an extending class
	 * @since 3.0.0
	 */
	public function render() {

		$this->set_error(
			array( 
				'type'		=> 'render() called on wrong class'
			)
		);
	}

	/**
	 * Load a template file for views
	 *
	 * First, it looks in the current theme's /ewd-otp-templates/ directory. Then it
	 * will check a parent theme's /ewd-otp-templates/ directory. If nothing is found
	 * there, it will retrieve the template from the plugin directory.

	 * @since 3.0.0
	 * @param string template Type of template to load (eg - reviews, review)
	 */
	function find_template( $template ) {

		$this->template_dirs = array(
			get_stylesheet_directory() . '/' . EWD_OTP_TEMPLATE_DIR . '/',
			get_template_directory() . '/' . EWD_OTP_TEMPLATE_DIR . '/',
			EWD_OTP_PLUGIN_DIR . '/' . EWD_OTP_TEMPLATE_DIR . '/'
		);
		
		$this->template_dirs = apply_filters( 'ewd_otp_template_directories', $this->template_dirs );

		foreach ( $this->template_dirs as $dir ) {
			if ( file_exists( $dir . $template . '.php' ) ) {
				return $dir . $template . '.php';
			}
		}

		return false;
	}

	/**
	 * Enqueue stylesheets
	 */
	public function enqueue_assets() {

		//enqueue assets here
	}

	/**
	 * Print the details of an order, if one is selected
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_order_tracking() {

		if ( empty( $this->order ) ) { return; }

		$template = $this->find_template( 'order-results' );
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Prints an action notification, if any action has happened
	 *
	 * @since 3.0.0
	 */
	public function maybe_print_update_message() {
		
		if ( empty( $this->update_message ) ) { return; }
		
		$template = $this->find_template( 'update-message' );
		
		if ( $template ) {
			include( $template );
		}
	}

	public function print_error_message()
	{
		if( empty( $this->error_message ) ) { return; }

		$template = $this->find_template( 'error-message' );

		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Print the details of an order, if one is selected
	 *
	 * @since 3.0.0
	 */
	public function print_custom_field() {

		if ( empty( $this->custom_field ) ) { return; }

		if ( $this->custom_field->type == 'file' ) { $template = $this->find_template( 'custom-field-file' ); }
		elseif ( $this->custom_field->type == 'image' ) { $template = $this->find_template( 'custom-field-image' ); }
		elseif ( $this->custom_field->type == 'link' ) { $template = $this->find_template( 'custom-field-link' ); }
		else { $template = $this->find_template( 'custom-field-default' ); }
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Returns the custom fields designated for orders
	 *
	 * @since 3.0.0
	 */
	public function get_order_fields() {
		global $ewd_otp_controller;

		return $ewd_otp_controller->settings->get_order_custom_fields();
	}

	/**
	 * Returns whether the order number field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_number_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'order_number', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->order ) or empty( $this->order->number ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether the order name field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_name_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'order_name', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->order ) or empty( $this->order->name ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether the public order notes field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_notes_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'order_notes', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->order ) or empty( $this->order->notes_public ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether the customer notes field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_customer_notes_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'customer_notes', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->order ) or empty( $this->order->customer_notes ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether the customer name field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_customer_name_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'customer_name', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->customer ) or empty( $this->customer->name ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether the customer email field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_customer_email_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'customer_email', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->customer ) or empty( $this->customer->email ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether the sales rep first name field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_sales_rep_first_name_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'sales_rep_first_name', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->sales_rep ) or empty( $this->sales_rep->first_name ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether the sales rep last name field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_sales_rep_last_name_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'sales_rep_last_name', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->sales_rep ) or empty( $this->sales_rep->last_name ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether the sales rep email field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_sales_rep_email_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'sales_rep_email', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->sales_rep ) or empty( $this->sales_rep->email ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether a custom field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_custom_field_display( $custom_field ) {
		global $ewd_otp_controller;

		if ( ! $custom_field->display ) { return false; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->order ) or empty( $this->order->custom_fields[ $custom_field->id ] ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether the order status field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_status_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'order_status', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->order ) or empty( $this->order->external_status ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether the order location field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_location_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'order_location', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->order ) or empty( $this->order->location ) ) ) { return false; }

		return true;
	}

	/**
	 * Returns whether the order updated datetime field should be displayed or not
	 *
	 * @since 3.0.0
	 */
	public function get_order_updated_display() {
		global $ewd_otp_controller;

		if ( ! in_array( 'order_updated', $ewd_otp_controller->settings->get_setting( 'order-information' ) ) ) { return false ; }

		if ( $ewd_otp_controller->settings->get_setting( 'hide-blank-fields' ) and ( empty( $this->order ) or empty( $this->order->status_updated ) ) ) { return false; }

		return true;
	}

	/**
	 * Return the name of the customer for this order, if any
	 *
	 * @since 3.0.13
	 */
	public function get_customer_name() {

		return empty( $this->customer->name ) ? '' : $this->customer->name;
	}

	/**
	 * Return the email of the customer for this order, if any
	 *
	 * @since 3.0.13
	 */
	public function get_customer_email() {

		return empty( $this->customer->email ) ? '' : $this->customer->email;
	}

	/**
	 * Return the first name of the sales rep for this order, if any
	 *
	 * @since 3.0.13
	 */
	public function get_sales_rep_first_name() {

		return empty( $this->sales_rep->first_name ) ? '' : $this->sales_rep->first_name;
	}

	/**
	 * Return the last name of the sales rep for this order, if any
	 *
	 * @since 3.0.13
	 */
	public function get_sales_rep_last_name() {

		return empty( $this->sales_rep->last_name ) ? '' : $this->sales_rep->last_name;
	}

	/**
	 * Return the email of the sales rep for this order, if any
	 *
	 * @since 3.0.13
	 */
	public function get_sales_rep_email() {

		return empty( $this->sales_rep->email ) ? '' : $this->sales_rep->email;
	}

	/**
	 * Returns the target attribute for a shortcode form
	 *
	 * @since 3.0.0
	 */
	public function get_form_target() {
		global $ewd_otp_controller;

		return $ewd_otp_controller->settings->get_setting( 'new-window' ) ? 'target="_blank"' : '';
	}

	public function get_option( $option_name ) {
		global $ewd_otp_controller;

		return ! empty( $this->$option_name ) ? $this->$option_name : $ewd_otp_controller->settings->get_setting( $option_name );
	}

	public function get_label( $label_name ) {
		global $ewd_otp_controller;

		if ( empty( $this->label_defaults ) ) { $this->set_label_defaults(); }

		return ! empty( $ewd_otp_controller->settings->get_setting( $label_name ) ) ? $ewd_otp_controller->settings->get_setting( $label_name ) : $this->label_defaults[ $label_name ];
	}

	public function set_label_defaults() {

		$this->label_defaults = array(
			'label-order-form-title'					=> __( 'Track an Order', 'order-tracking' ),
			'label-order-form-number'					=> __( 'Order Number', 'order-tracking' ),
			'label-order-form-number-placeholder'		=> __( '', 'order-tracking' ),
			'label-order-form-email'					=> __( 'Order E-mail Address', 'order-tracking' ),
			'label-order-form-email-placeholder'		=> __( '', 'order-tracking' ),
			'label-order-form-button'					=> __( 'Track', 'order-tracking' ),
			'label-retrieving-results'					=> __( 'Retrieving results...', 'order-tracking' ),
			'label-customer-form-title'					=> __( 'Track Your Orders', 'order-tracking' ),
			'label-customer-form-instructions'			=> __( 'Enter your customer number in the form below to track your orders.', 'order-tracking' ),
			'label-customer-form-number'				=> __( 'Customer Number', 'order-tracking' ),
			'label-customer-form-number-placeholder'	=> __( '', 'order-tracking' ),
			'label-customer-form-email'					=> __( 'Customer Email', 'order-tracking' ),
			'label-customer-form-email-placeholder'		=> __( '', 'order-tracking' ),
			'label-customer-form-button'				=> __( 'Find Customer', 'order-tracking' ),
			'label-sales-rep-form-title'				=> __( 'Track Your Orders', 'order-tracking' ),
			'label-sales-rep-form-instructions'			=> __( 'Enter your sales rep number in the form below to track your orders.', 'order-tracking' ),
			'label-sales-rep-form-number'				=> __( 'Sales Rep Number', 'order-tracking' ),
			'label-sales-rep-form-number-placeholder'	=> __( 'Sales Rep Number', 'order-tracking' ),
			'label-sales-rep-form-email'				=> __( 'Sales Rep Email', 'order-tracking' ),
			'label-sales-rep-form-email-placeholder'	=> __( 'Sales Rep Email', 'order-tracking' ),
			'label-sales-rep-form-button'				=> __( 'Find Sales Rep', 'order-tracking' ),
			'label-order-information'					=> __( 'Order Information', 'order-tracking' ),
			'label-order-number'						=> __( 'Order Number', 'order-tracking' ),
			'label-order-name'							=> __( 'Order Name', 'order-tracking' ),
			'label-order-notes'							=> __( 'Order Notes', 'order-tracking' ),
			'label-customer-notes'						=> __( 'Customer Notes', 'order-tracking' ),
			'label-order-status'						=> __( 'Order Status', 'order-tracking' ),
			'label-order-location'						=> __( 'Order Location', 'order-tracking' ),
			'label-order-updated'						=> __( 'Order Updated', 'order-tracking' ),
			'label-order-current-location'				=> __( 'Current Location', 'order-tracking' ),
			'label-order-print-button'					=> __( 'Print', 'order-tracking' ),
			'label-order-add-note-button'				=> __( 'Add Note', 'order-tracking' ),
			'label-order-update-status'					=> __( 'Update Status', 'order-tracking' ),
			'label-customer-display-number'				=> __( 'Customer Number', 'order-tracking' ),
			'label-customer-display-name'				=> __( 'Customer Name', 'order-tracking' ),
			'label-customer-display-email'				=> __( 'Customer Email', 'order-tracking' ),
			'label-customer-display-download'			=> __( 'Download All Orders', 'order-tracking' ),
			'label-sales-rep-display-number'			=> __( 'Sales Rep Number', 'order-tracking' ),
			'label-sales-rep-display-first-name'		=> __( 'Sales Rep First Name', 'order-tracking' ),
			'label-sales-rep-display-last-name'			=> __( 'Sales Rep Last Name', 'order-tracking' ),
			'label-sales-rep-display-email'				=> __( 'Sales Rep Email', 'order-tracking' ),
			'label-sales-rep-display-download'			=> __( 'Download All Orders', 'order-tracking' ),
			'label-customer-order-name'					=> __( 'Order Name', 'order-tracking' ),
			'label-customer-order-email'				=> __( 'Order Email Address', 'order-tracking' ),
			'label-customer-order-phone'				=> __( 'Order Phone Number', 'order-tracking' ),
			'label-customer-order-notes'				=> __( 'Customer Notes', 'order-tracking' ),
			'label-customer-order-button'				=> __( 'Send Order', 'order-tracking' ),
			'label-customer-order-thank-you'			=> __( 'Thank you. Your order number is:', 'order-tracking' ),
			'label-customer-order-email-instructions'	=> __( 'The email address to send order updates to, if the site administrator has selected that option.', 'order-tracking' ),
			'label-customer-order-phone-instructions'	=> __( 'The phone number to send order updates to, if the site administrator has selected that option.', 'order-tracking' ),
		);
	}

	public function add_custom_styling() {
		global $ewd_otp_controller;

		$css = '';

		if ( $ewd_otp_controller->settings->get_setting( 'styling-title-font' ) != '' ) { $css .= '.ewd-otp-order-tracking-form-div h3 { font-family: ' . $ewd_otp_controller->settings->get_setting( 'styling-title-font' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-title-font-size' ) != '' ) { $css .= '.ewd-otp-order-tracking-form-div h3 { font-size: ' . $ewd_otp_controller->settings->get_setting( 'styling-title-font-size' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-title-font-color' ) != '' ) { $css .= '.ewd-otp-order-tracking-form-div h3 { color: ' . $ewd_otp_controller->settings->get_setting( 'styling-title-font-color' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-title-margin' ) != '' ) { $css .= '.ewd-otp-order-tracking-form-div h3 { margin: ' . $ewd_otp_controller->settings->get_setting( 'styling-title-margin' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-title-padding' ) != '' ) { $css .= '.ewd-otp-order-tracking-form-div h3 { padding: ' . $ewd_otp_controller->settings->get_setting( 'styling-title-padding' ) . ' !important; }'; }

		if ( $ewd_otp_controller->settings->get_setting( 'styling-label-font' ) != '' ) { $css .= '.ewd-otp-tracking-results-label { font-family: ' . $ewd_otp_controller->settings->get_setting( 'styling-label-font' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-label-font-size' ) != '' ) { $css .= '.ewd-otp-tracking-results-label { font-size: ' . $ewd_otp_controller->settings->get_setting( 'styling-label-font-size' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-label-font-color' ) != '' ) { $css .= '.ewd-otp-tracking-results-label { color: ' . $ewd_otp_controller->settings->get_setting( 'styling-label-font-color' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-label-margin' ) != '' ) { $css .= '.ewd-otp-tracking-results-label { margin: ' . $ewd_otp_controller->settings->get_setting( 'styling-label-margin' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-label-padding' ) != '' ) { $css .= '.ewd-otp-tracking-results-label { padding: ' . $ewd_otp_controller->settings->get_setting( 'styling-label-padding' ) . ' !important; }'; }

		if ( $ewd_otp_controller->settings->get_setting( 'styling-content-font' ) != '' ) { $css .= '.ewd-otp-tracking-results-value { font-family: ' . $ewd_otp_controller->settings->get_setting( 'styling-content-font' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-content-font-size' ) != '' ) { $css .= '.ewd-otp-tracking-results-value { font-size: ' . $ewd_otp_controller->settings->get_setting( 'styling-content-font-size' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-content-font-color' ) != '' ) { $css .= '.ewd-otp-tracking-results-value { color: ' . $ewd_otp_controller->settings->get_setting( 'styling-content-font-color' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-content-margin' ) != '' ) { $css .= '.ewd-otp-tracking-results-value { margin: ' . $ewd_otp_controller->settings->get_setting( 'styling-content-margin' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-content-padding' ) != '' ) { $css .= '.ewd-otp-tracking-results-value { padding: ' . $ewd_otp_controller->settings->get_setting( 'styling-content-padding' ) . ' !important; }'; }

		if ( $ewd_otp_controller->settings->get_setting( 'styling-button-font-color' ) != '' ) { $css .= '.ewd-otp-submit { color: ' . $ewd_otp_controller->settings->get_setting( 'styling-button-font-color' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-button-background-color' ) != '' ) { $css .= '.ewd-otp-submit { background-color: ' . $ewd_otp_controller->settings->get_setting( 'styling-button-background-color' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-button-border' ) != '' ) { $css .= '.ewd-otp-submit { border: ' . $ewd_otp_controller->settings->get_setting( 'styling-button-border' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-button-margin' ) != '' ) { $css .= '.ewd-otp-submit { margin: ' . $ewd_otp_controller->settings->get_setting( 'styling-button-margin' ) . ' !important; }'; }
		if ( $ewd_otp_controller->settings->get_setting( 'styling-button-padding' ) != '' ) { $css .= '.ewd-otp-submit { padding: ' . $ewd_otp_controller->settings->get_setting( 'styling-button-padding' ) . ' !important; }'; }

		$css .= $ewd_otp_controller->settings->get_setting( 'custom-css' );

		if( ! empty( $css ) ) {
			echo '<style>';
				echo $css;
			echo '</style>';
		}
	}

}