<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewdotpUltimateWPMail' ) ) {
	/**
	 * Class to handle Ultimate WP Mail integration for Order Tracking
	 *
	 * @since 3.0.0
	 */
	class ewdotpUltimateWPMail {

		public function __construct() {

			add_filter( 'uwpm_register_custom_element_section', array( $this, 'add_element_section' ) );
			add_action( 'uwpm_register_custom_element', array( $this, 'add_elements' ) );
		}

		/**
		 * Adds in a section for OTP tags in Ultimate WP Mail
		 * @since 3.0.0
		 */
		public function add_element_section() {

			if ( ! function_exists( 'uwpm_register_custom_element_section' ) ) { return; }

			$args = array(
				'label' => 'Order Tracking Tags'
			);

			uwpm_register_custom_element_section( 'ewd_otp_uwpm_elements', $args );
		}

		/**
		 * Adds in tags for order information and a tracking link
		 * @since 3.0.0
		 */
		public function add_elements() { 
			global $ewd_otp_controller;

			if ( ! function_exists( 'uwpm_register_custom_element' ) ) { return; }
			
			$args = array(
				'label' 			=> 'Order Name',
				'callback_function' => 'ewd_otp_get_order_name',
				'section' 			=> 'ewd_otp_uwpm_elements'
			);

			uwpm_register_custom_element( 'ewd_otp_order_name', $args );

			$args = array(
				'label' 			=> 'Order Number',
				'callback_function' => 'ewd_otp_get_order_number',
				'section' 			=> 'ewd_otp_uwpm_elements'
			);

			uwpm_register_custom_element( 'ewd_otp_order_number', $args );

			$args = array(
				'label' 			=> 'Order Status',
				'callback_function' => 'ewd_otp_get_order_status',
				'section' 			=> 'ewd_otp_uwpm_elements'
			);

			uwpm_register_custom_element( 'ewd_otp_order_status', $args );

			$args = array(
				'label' 			=> 'Order Notes',
				'callback_function' => 'ewd_otp_get_order_notes',
				'section' 			=> 'ewd_otp_uwpm_elements'
			);

			uwpm_register_custom_element( 'ewd_otp_order_notes', $args );

			$args = array(
				'label' 			=> 'Order Customer Notes',
				'callback_function' => 'ewd_otp_get_order_customer_notes',
				'section' 			=> 'ewd_otp_uwpm_elements'
			);

			uwpm_register_custom_element( 'ewd_otp_order_customer_notes', $args );

			$args = array(
				'label' 			=> 'Order Updated Time',
				'callback_function' => 'ewd_otp_get_order_updated_time',
				'section' 			=> 'ewd_otp_uwpm_elements'
			);

			uwpm_register_custom_element( 'ewd_otp_order_updated_time', $args );

			$args = array(
				'label' 			=> 'Tracking Link',
				'callback_function' => 'ewd_otp_get_order_tracking_link',
				'section' 			=> 'ewd_otp_uwpm_elements',
				'attributes' => array(
					array(
						'attribute_name' => 'ewd_otp_tracking_page_url',
						'attribute_label' => 'Tracking Page URL',
						'attribute_type' => 'TextBox'
					),
					array(
						'attribute_name' => 'ewd_otp_tracking_link_text_label',
						'attribute_label' => '"Track Your Order!" label',
						'attribute_type' => 'TextBox'
					)
				)
			);

			uwpm_register_custom_element( 'ewd_otp_order_tracking_link', $args );

			$args = array(
				'label' 			=> 'Customer Name',
				'callback_function' => 'ewd_otp_get_order_customer_name',
				'section' 			=> 'ewd_otp_uwpm_elements'
			);

			uwpm_register_custom_element( 'ewd_otp_order_customer_name', $args );

			$args = array(
				'label' 			=> 'Customer ID',
				'callback_function' => 'ewd_otp_get_order_customer_id',
				'section' 			=> 'ewd_otp_uwpm_elements'
			);

			uwpm_register_custom_element( 'ewd_otp_order_customer_id', $args );

			$args = array(
				'label' 			=> 'Sales Rep Name',
				'callback_function' => 'ewd_otp_get_order_sales_rep_name',
				'section' 			=> 'ewd_otp_uwpm_elements'
			);

			uwpm_register_custom_element( 'ewd_otp_order_sales_rep_name', $args );

			foreach( $ewd_otp_controller->settings->get_order_custom_fields() as $custom_field ) {

				$args = array(
					'label' 			=> $custom_field->name,
					'callback_function' => 'ewd_otp_get_custom_field',
					'section' 			=> 'ewd_otp_uwpm_elements'
				);

				uwpm_register_custom_element( 'ewd_otp_' . $custom_field->slug, $args );
			}
		}
	}
}

/**
 * Returns the name of the specified order
 * @since 3.0.0
 */
function ewd_otp_get_order_name( $params, $user ) {
	global $ewd_otp_controller;

	return ! empty( $params['order_id'] ) ? $ewd_otp_controller->order_manager->get_order_field( 'name', $params['order_id'] ) : '';
}

/**
 * Returns the number of the specified order
 * @since 3.0.0
 */
function ewd_otp_get_order_number( $params, $user ) {
	global $ewd_otp_controller;

	return ! empty( $params['order_id'] ) ? $ewd_otp_controller->order_manager->get_order_field( 'number', $params['order_id'] ) : '';
}

/**
 * Returns the external status of the specified order
 * @since 3.0.0
 */
function ewd_otp_get_order_status( $params, $user ) {
	global $ewd_otp_controller;

	return ! empty( $params['order_id'] ) ? $ewd_otp_controller->order_manager->get_order_field( 'external_status', $params['order_id'] ) : '';
}

/**
 * Returns the public notes for the specified order
 * @since 3.0.0
 */
function ewd_otp_get_order_notes( $params, $user ) {
	global $ewd_otp_controller;

	return ! empty( $params['order_id'] ) ? $ewd_otp_controller->order_manager->get_order_field( 'notes_public', $params['order_id'] ) : '';
}

/**
 * Returns the customer notes for the specified order
 * @since 3.0.0
 */
function ewd_otp_get_order_customer_notes( $params, $user ) {
	global $ewd_otp_controller;

	return ! empty( $params['order_id'] ) ? $ewd_otp_controller->order_manager->get_order_field( 'customer_notes', $params['order_id'] ) : '';
}

/**
 * Returns the time the specified order was last updated
 * @since 3.0.0
 */
function ewd_otp_get_order_updated_time( $params, $user ) {
	global $ewd_otp_controller;

	return ! empty( $params['order_id'] ) ? $ewd_otp_controller->order_manager->get_order_field( 'status_updated', $params['order_id'] ) : '';
}

/**
 * Returns the customer name for the specified order
 * @since 3.0.0
 */
function ewd_otp_get_order_customer_name( $params, $user ) {
	global $ewd_otp_controller;

	$customer_id = ! empty( $params['order_id'] ) ? $ewd_otp_controller->order_manager->get_order_field( 'customer', $params['order_id'] ) : '';

	return ! empty( $customer_id ) ? $ewd_otp_controller->customer_manager->get_customer_field( 'name', $customer_id ) : '';
}

/**
 * Returns the customer ID for the specified order
 * @since 3.0.0
 */
function ewd_otp_get_order_customer_id( $params, $user ) {
	global $ewd_otp_controller;

	return ! empty( $params['order_id'] ) ? $ewd_otp_controller->order_manager->get_order_field( 'customer', $params['order_id'] ) : '';
}

/**
 * Returns the sales rep ID for the specified order
 * @since 3.0.0
 */
function ewd_otp_get_order_sales_rep_name( $params, $user ) {
	global $ewd_otp_controller;

	$sales_rep_id = ! empty( $params['order_id'] ) ? $ewd_otp_controller->order_manager->get_order_field( 'sales_rep', $params['order_id'] ) : '';

	return ! empty( $sales_rep_id ) ? ( $ewd_otp_controller->sales_rep_manager->get_sales_rep_field( 'first_name', $sales_rep_id ) . ' ' . $ewd_otp_controller->sales_rep_manager->get_sales_rep_field( 'last_name', $sales_rep_id ) ) : '';
}

/**
 * Returns a tracking link for the specified order
 * @since 3.0.0
 */
function ewd_otp_get_order_tracking_link( $params, $user ) {
	global $ewd_otp_controller;

	if ( empty( $params['order_id'] ) ) { return ''; }

	$attributes = (array) $params['attributes'];

	foreach ( $attributes as $attribute_name => $attribute_value ) {

		if ( $attribute_name == 'ewd_otp_tracking_link_text_label' ) { $link_text = $attribute_value; }

		if ( $attribute_name == 'ewd_otp_tracking_page_url' ) {

			$order = new ewdotpOrder();
			$order->load_order_from_id( $params['order_id'] ); 

			$args = array(
				'tracking_number'	=> $order->number,
				'order_email'		=> $order->email,
				'tl_code'			=> ewd_random_string()
			);

			$tracking_url = add_query_arg( $args, $attribute_value );

			$order->tracking_link_code = $args['tl_code'];

			$order->update_order();
		}
	}

	$link_text = ! empty( $link_text ) ? $link_text : __( 'Track your order!', 'order-tracking' );
	$tracking_url = ! empty( $tracking_url ) ? $tracking_url : $ewd_otp_controller->settings->get_setting( 'tracking-page-url' );

	return '<a href="' . $tracking_url . '">' . $link_text . '</a>';
}

/**
 * Returns the value for the specified custom field for the specified order
 * @since 3.0.0
 */
function ewd_otp_get_custom_field( $params, $user ) {
	global $ewd_otp_controller;
	
	if ( empty( $params['order_id'] ) or empty( $params['slug'] ) ) { return; }

	$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();

	$field_id = 0;

	foreach ( $custom_fields as $custom_field ) { 

		if ( 'ewd_otp_' . $custom_field->slug == $params['slug'] ) { 

			return $ewd_otp_controller->order_manager->get_field_value( $custom_field->id, $params['order_id'] );
		}
	}

	return false;
}