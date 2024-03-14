<?php
/*
Class Name: VI_WOO_COUPON_BOX_Admin_Mailchimp
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_COUPON_BOX_Admin_Mailchimp {
	protected $settings;
	protected $api_key;
	protected $list_id;

	function __construct() {
		$this->settings = new VI_WOO_COUPON_BOX_DATA();
		$this->api_key  = $this->settings->get_params( 'wcb_api' );
		$this->list_id  = $this->settings->get_params( 'wcb_mlists' );
	}

	function get_lists() {
		if ( $this->api_key ) {
			$dash_position = strpos( $this->api_key, '-' );
			if ( $dash_position !== false ) {
				$url        = 'https://' . substr( $this->api_key, $dash_position + 1 ) . '.api.mailchimp.com/3.0/lists?fields=lists.name,lists.id&count=1000';
				$auth       = base64_encode( 'user:' . esc_attr( $this->api_key ) );

				try {
					$r = wp_remote_get( $url, [
						'headers' => [
							'Authorization' => "Basic $auth",
							'Accept'        => 'application/json',
							'Content-Type'  => 'application/json',
						],
					] );

					$body = wp_remote_retrieve_body( $r );
					$lists_data = json_decode( $body );

					if ( property_exists( $lists_data, 'lists' ) ) {
						$data  = $lists_data->lists;
						$lists = array();
						if ( count( $data ) ) {

							foreach ( $data as $list ) {
								$lists[ $list->id ] = $list->name;
							}

						} else {
							return false;
						}

						return $lists;
					} else {
						return false;
					}

				} catch ( \Exception $e ) {

					return false;
				}

//				$api_url = 'https://' . substr( $this->api_key, $dash_position + 1 ) . '.api.mailchimp.com/3.0/lists?fields=lists.name,lists.id&count=1000';
//				$url     = esc_attr( $api_url ) . '&apikey=' . esc_attr( $this->api_key ); //set the url

//				try {
//					$r    = wp_remote_get( $url );
//					$body = wp_remote_retrieve_body( $r );
//					$body = json_decode( $body );
//
//					return $body->lists;
//
//				} catch ( \Exception $e ) {
//					return false;
//				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	function add_email( $email, $fname = '', $lname = '', $phone = '', $birthday = '' ) {
		if ( $this->api_key && $this->list_id ) {

			try {
				$data = array(
					'email_address' => $email,
					'status'        => $this->settings->get_params( 'wcb_mailchimp_double_optin' ) ? 'pending' : 'subscribed',
					'merge_fields'  => array(
						'FNAME'    => $fname,
						'LNAME'    => $lname,
						'PHONE'    => $phone,
						'BIRTHDAY' => $birthday,
					),
				);

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
						'body'    => json_encode( $data ),
					] );

					$body = wp_remote_retrieve_body( $r );
					$data = json_decode( $body );
					switch ( $data->status ) {
						case 'subscribed':
							return true;
							break;
						default:
							return false;
					}

				} catch ( \Exception $e ) {

					return false;
				}

//				$data_center = substr( $this->api_key, strpos( $this->api_key, '-' ) + 1 );
//				$url         = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $this->list_id . '/members/';
//
//
//				$response = wp_remote_post( $url, [
//					'headers' => [
//						'Authorization' => 'Basic ' . base64_encode( 'user:' . $this->api_key ) // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
//					],
//					'body'    => wp_json_encode( $data )
//				] );
//
//				$body = wp_remote_retrieve_body( $response );
//
//				$data = json_decode( $body );
//
//				switch ( $data->status ) {
//					case 'subscribed':
//						return true;
//						break;
//					default:
//						return false;
//				}
			} catch ( Exception $e ) {
				return $e->getMessage();
			}

		} else {
			return false;
		}
	}


}
