<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * Notification emails.
 */
class Nelio_Content_Cloud_Notification_Emails_Setting extends Nelio_Content_Abstract_React_Setting {

	public function __construct() {
		parent::__construct( 'cloud_notification_emails', 'CloudNotificationEmailsSetting' );
	}//end __construct()

	// @Implements
	// phpcs:ignore
	public function sanitize( $input ) {

		$value = trim( sanitize_text_field( $input[ $this->name ] ) );
		$value = explode( ',', $value );
		$value = array_map( 'trim', $value );

		$body = array(
			'notificationEmails' => $value,
		);

		$data = array(
			'method'    => 'PUT',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body'      => wp_json_encode( $body ),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id(), 'wp' );
		$response = wp_remote_request( $url, $data );

		unset( $input[ $this->name ] );
		return $input;

	}//end sanitize()

}//end class
