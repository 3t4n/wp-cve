<?php

namespace WpifyWoo\Modules\AsyncEmails;

use WP_Error;
use WpifyWoo\Abstracts\AbstractModule;

class AsyncEmailsModule extends AbstractModule {

	/**
	 * @return void
	 */
	public function setup() {
		add_filter( 'woocommerce_mail_callback', array( $this, 'change_mail_callback' ) );
		add_action( 'wpify_send_email', array( $this, 'send_email' ) );
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
	}

	function id() {
		return 'async_emails';
	}

	/**
	 * @return array
	 */
	public function change_mail_callback(): array {
		return array( $this, 'add_job_to_queue' );
	}

	/**
	 * @param $to
	 * @param $subject
	 * @param $message
	 * @param $headers
	 * @param $attachments
	 */
	public function add_job_to_queue( $to, $subject, $message, $headers, $attachments ) {
		$args = array( $to, $subject, $message, $headers, $attachments );
		$hash = md5( json_encode( $args ) );
		update_option( $hash, $args );
		as_schedule_single_action( time(), 'wpify_send_email', array( $hash ) );
	}

	/**
	 * @param $hash
	 *
	 * @return WP_Error
	 */
	public function send_email( $hash ) {
		$args = get_option( $hash );
		if ( ! $args ) {
			return new WP_Error( 'email-args-not-found', __( 'Email args not found', 'wpify-woo' ) );
		}
		delete_option( $hash );
		if ( ! class_exists( '\WC_Email', false ) ) {
			include_once WC_ABSPATH . 'includes/emails/class-wc-email.php';
		}

		$emails = new \WC_Email();

		add_filter( 'wp_mail_from', array( $emails, 'get_from_address' ) );
		add_filter( 'wp_mail_from_name', array( $emails, 'get_from_name' ) );

		$result = call_user_func_array( 'wp_mail', $args );

		remove_filter( 'wp_mail_from', array( $emails, 'get_from_address' ) );
		remove_filter( 'wp_mail_from_name', array( $emails, 'get_from_name' ) );

		return $result;
	}

	/**
	 * @return array[]
	 */
	public function settings(): array {
		$settings = array(
			array(
				'id'    => 'async_emails_test',
				'type'  => 'title',
				'label' => __( 'Asynchronous sending of WooCommerce emails', 'wpify-woo' ),
				'desc'  => __( 'When an order is placed, emails are sent immediately, slowing down processing of the checkout. With the "Async emails" module enabled, the emails are added to a queue and sent asynchronously using Action Scheduler. This can speed up the checkout by a few seconds.',
					'wpify-woo' ),
			),
		);

		if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
			$settings[] = array(
				'id'    => 'async_emails_cron_notification',
				'type'  => 'title',
				'label' => __( 'WP Cron is disabled in wp-config!', 'wpify-woo' ),
				'desc'  => __( 'WP Cron is disabled in wp-config.php. Please make sure that you have the cron job setup externally.', 'wpify-woo' ),
			);
		}

		return $settings;
	}

	public function name() {
		return __( 'Async emails', 'wpify-woo' );
	}
}
