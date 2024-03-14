<?php
/*
Class Name: VI_WOO_LUCKY_WHEEL_Admin_Mailchimp
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2018 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_LUCKY_WHEEL_Admin_Mailchimp {
	protected $settings;
	protected $api_key;
	protected $list_id;

	function __construct() {
		$this->settings = VI_WOO_LUCKY_WHEEL_DATA::get_instance();
		$this->api_key  = $this->settings->get_params( 'mailchimp', 'api_key' );
		$this->list_id  = $this->settings->get_params( 'mailchimp', 'lists' );
	}


	function get_lists() {
		if ( $this->api_key ) {
			$dash_position = strpos( $this->api_key, '-' );
			if ( $dash_position !== false ) {
				$api_url = 'https://' . substr( $this->api_key, $dash_position + 1 ) . '.api.mailchimp.com/3.0/lists?fields=lists.name,lists.id&count=1000';
				$auth       = base64_encode( 'user:' . esc_attr( $this->api_key ) );

				try {
					$r = wp_remote_get( $api_url, [
						'headers' => [
							'Authorization' => "Basic $auth",
							'Accept'        => 'application/json',
							'Content-Type'  => 'application/json',
						],
					] );

					$body = wp_remote_retrieve_body( $r );

					return json_decode( $body );

				} catch ( \Exception $e ) {

					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	function add_email( $email, $fname = '', $lname = '', $phone = '', $birthday = '' ) {
		if ( $this->api_key && $this->list_id ) {
			$data = json_encode( array(
				'email_address' => $email,
				'status'        => 'subscribed',
				'merge_fields'  => array(
					'FNAME'    => $fname,
					'LNAME'    => $lname,
					'PHONE'    => $phone,
					'BIRTHDAY' => $birthday,
				),
			) );

			$dataCenter = substr( $this->api_key, strpos( $this->api_key, '-' ) + 1 );
			$url        = 'https://' . esc_attr( $dataCenter ) . '.api.mailchimp.com/3.0/lists/' . esc_attr( $this->list_id ) . '/members/' . md5( strtolower( $email ) );
			$auth       = base64_encode( 'user:' . esc_attr( $this->api_key ) );

			try {
				$r = wp_remote_post( $url, [
					'method'  => 'PUT',
					'headers' => [
						'Authorization' => "Basic $auth",
						'Accept'        => 'application/json',
						'Content-Type'  => 'application/json',
					],
					'body'    => $data,
				] );

				$body = wp_remote_retrieve_body( $r );

				return true;

			} catch ( \Exception $e ) {

				return false;
			}
		} else {
			return false;
		}
	}


}
