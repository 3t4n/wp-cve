<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//WooCommerce Bookings Compatibility
class WC_Szamlazz_Bookings_Compatibility {

	public static function init() {

		//Create settings
		add_filter( 'wc_szamlazz_settings_fields', array( __CLASS__, 'add_settings') );

		//Custom note and automation conditions
		add_filter( 'wc_szamlazz_notes_conditions', array( __CLASS__, 'conditions'));
		add_filter( 'wc_szamlazz_automations_conditions', array( __CLASS__, 'conditions'));
		add_filter( 'wc_szamlazz_notes_conditions_values', array( __CLASS__, 'conditions_values'), 10, 2);
		add_filter( 'wc_szamlazz_automations_conditions_values', array( __CLASS__, 'conditions_values'), 10, 2);

		//Modify invoice
		add_filter('wc_szamlazz_invoice_line_item', array( __CLASS__, 'add_line_item_comment'), 10, 3);

	}

	public static function add_settings($settings) {
		$settings_custom = array(
			'section_compat_subscriptions' => array(
				'title' => __( 'WooCommerce Bookings', 'wc-szamlazz' ),
				'type' => 'wc_szamlazz_settings_title',
				'description' => __( 'Settings related to WooCommerce Bookings.', 'wc-szamlazz' ),
			),
			'compat_bookings_comment' => array(
				'title'    => __( 'Line item comment', 'wc-szamlazz' ),
				'type'     => 'textarea',
				'default' => '',
				'description' => __( 'This description will appear below the line item, if it was a booking. You can display the booking details and more with the following shortcodes: {booking_start_date}, {booking_end_date}, {booking_id}, {booking_person_count}, {booking_person_counts}.', 'wc-szamlazz' ),
			)
		);

		return array_merge($settings, $settings_custom);
	}

	public static function conditions($conditions) {
		$conditions['bookings'] = array(
			'label' => __('WooCommerce Bookings', 'wc-szamlazz'),
			'options' => array(
				'has_wc_bookings_item' => 'Order has a booking'
			)
		);

		return $conditions;
	}

	public static function conditions_values($order_details, $order) {

		//Check if there is at least one booking in the order
		$has_booking = false;
		foreach ( $order->get_items() as $item ) {
			$item_id = $item->get_id();
			$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
			if ( $booking_ids ) {
				$has_booking = true;
			}
		}

		//Set bookings parameter
		$order_details['bookings'] = 'no';
		if($has_booking) $order_details['bookings'] = 'has_wc_bookings_item';

		return $order_details;
	}

	public static function add_line_item_comment($tetel, $order_item, $order) {
		$item_type = $order_item->get_type();
		$item_id 	= $order_item->get_id();

		//Check if its a line item(not shipping for example)
		if($item_type != 'line_item') return $tetel;

		//Check if line item is a booking
		$product = $order_item->get_product();
		if ( !$product->is_type('booking') ) return $tetel;

		//Get booking info
		$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
		if ( $booking_ids ) {
			foreach ( $booking_ids as $booking_id ) {

				//Get single booking details
				$booking = new WC_Booking( $booking_id );
				$booking_product  = $booking->get_product();

				//Setup placeholders
				$comment_template = WC_Szamlazz()->get_option('compat_bookings_comment');
				$placeholders = [
					'{booking_start_date}' => $booking->get_start_date(),
					'{booking_end_date}' => $booking->get_end_date(),
					'{booking_id}' => $booking_id,
					'{booking_person_count}' => array_sum( $booking->get_person_counts() ),
					'{booking_person_counts}' => ''
				];

				//Check if we have different person types
				$person_counts = [];
				if ( $booking_product->has_person_types() ) {
					$person_types  = $booking_product->get_person_types();
					$person_counts = $booking->get_person_counts();
					if ( ! empty( $person_types ) && is_array( $person_types ) ) {
						foreach ( $person_types as $person_type ) {
							if ( empty( $person_counts[ $person_type->get_id() ] ) ) {
								continue;
							}
							$person_counts[] = esc_html( sprintf( '%s: %d', $person_type->get_name(), $person_counts[ $person_type->get_id() ] ) );
						}
					}
				}

				if(!empty($person_counts)) {
					$placeholders['{booking_person_counts}'] = implode(', ', $person_counts);
				}

				//Set comment
				$tetel->megjegyzes .= str_replace( array_keys( $placeholders ), array_values( $placeholders ), $comment_template);
			}
		}

		return $tetel;
	}

}

WC_Szamlazz_Bookings_Compatibility::init();
