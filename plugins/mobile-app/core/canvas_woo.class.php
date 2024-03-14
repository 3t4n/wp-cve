<?php

class CanvasWoo {
	public static function init() {
		add_action( 'woocommerce_email_sent', array( __CLASS__, 'trigger_push_notification' ), 10, 3 );
	}

	/**
	 * @param \WC_Email $instance
	 */
	public static function trigger_push_notification( $is_sent, $email_id, $instance ) {
		if ( ! $is_sent ) {
			return;
		}

		$woo_email_options = Canvas::get_option( 'push_woo_email_type', array() );

		if ( ! in_array( $email_id, $woo_email_options ) ) {
			return;
		}

		$recipient = $instance->get_recipient();
		$customer  = get_user_by( 'email', $recipient );

		if ( ! $customer ) {
			return;
		}

		$push_api  = self::get_push_api();

		$heading  = empty( $instance->get_heading() ) ? __( 'Email received' ) : $instance->get_heading();
		$subject  = empty( $instance->get_subject() ) ? __( 'Email received' ) : $instance->get_subject();

		$push_api->send_to_users( $heading, $subject, array( $customer->ID ) );
	}

	/**
	 * Return CanvasNotifications instance
	 *
	 * @return CanvasNotifications
	 */
	public static function get_push_api() {
		if ( ! class_exists( 'CanvasNotifications' ) ) {
			require_once dirname( __FILE__ ) . '/push/canvas-notifications.class.php';
		}

		return CanvasNotifications::get();
	}
}

add_action( 'woocommerce_loaded', array( 'CanvasWoo', 'init' ) );
