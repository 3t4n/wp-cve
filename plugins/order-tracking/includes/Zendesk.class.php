<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdotpZendesk' ) ) {
/**
 * Class to handle interactions with the Zendesk platform
 *
 * @since 3.0.0
 */
class ewdotpZendesk {

	public function __construct() {
		
		if ( ! empty( $_GET['Action'] ) and $_GET['Action'] == 'Zendesk_Order_Created' ) { array( $this, 'create_order' ); }
		if ( ! empty( $_GET['Action'] ) and $_GET['Action'] == 'Zendesk_Order_Updated' ) { array( $this, 'update_order' ); }
	}

	/**
	 * Create an order after receiving a notification form Zendesk
	 * @since 3.0.0
	 */
	public function create_order() {
		global $ewd_otp_controller;

		if ( empty( $ewd_otp_controller->settings->get_setting( 'zendesk-integration' ) ) ) { return; }

		if ( ! empty( $ewd_otp_controller->settings->get_setting( 'zendesk-api-key' ) ) and ( $ewd_otp_controller->settings->get_setting( 'zendesk-api-key' ) != $_GET['API_Key'] ) ) { return; }

		if ( ! empty( $ewd_otp_controller->order_manager->get_order_from_zendesk_id( sanitize_text_field( $_GET['zendeskID'] ) ) ) ) { return; }

		$order = new ewdotpOrder();

		$order->name = sanitize_text_field( $_GET['title'] );
		$order->number = sanitize_text_field( $_GET['zendeskID'] ) . " - " . sanitize_text_field( $_GET['title'] );
		$order->email = sanitize_text_field( $_GET['email'] );
		$order->status = sanitize_text_field( $_GET['status'] );
		$order->external_status = sanitize_text_field( $_GET['status'] );
		$order->status_updated = date( 'Y-m-d H:i:s' );
		$order->notes_public = __( 'Ticket created via Zendesk', 'order-tracking' );
		
		$order->insert_order();
	}

	/**
	 * Update an order after receiving a notification form Zendesk
	 * @since 3.0.0
	 */
	public function update_order() {
		global $ewd_otp_controller;

		if ( empty( $ewd_otp_controller->settings->get_setting( 'zendesk-integration' ) ) ) { return; }

		if ( ! empty( $ewd_otp_controller->settings->get_setting( 'zendesk-api-key' ) ) and ( $ewd_otp_controller->settings->get_setting( 'zendesk-api-key' ) != $_GET['API_Key'] ) ) { return; }

		$zendesk_order = $ewd_otp_controller->order_manager->get_order_from_zendesk_id( sanitize_text_field( $_GET['zendeskID'] ) );

		if ( ! empty( $zendesk_order ) ) { return; }
		
		$order = new ewdotpOrder();

		$order->load_order( $zendesk_order );

		$order->name = sanitize_text_field( $_GET['title'] );
		$order->number = sanitize_text_field( $_GET['zendeskID'] ) . " - " . sanitize_text_field( $_GET['title'] );
		$order->email = sanitize_text_field( $_GET['email'] );
		$order->status = sanitize_text_field( $_GET['status'] );
		$order->external_status = sanitize_text_field( $_GET['status'] );
		$order->status_updated = date( 'Y-m-d H:i:s' );
		$order->notes_public = __( 'Ticket created via Zendesk', 'order-tracking' );
		
		$order->insert_order();
	}
}

}