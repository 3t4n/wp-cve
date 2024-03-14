<?php

class MailsterMailchimpAPI {





	private $domain  = 'api.mailchimp.com';
	private $version = '3.0';

	public function __construct( $apikey = null ) {

		if ( ! is_null( $apikey ) ) {
			$this->set_apikey( $apikey );
		}

	}

	public function set_apikey( $apikey ) {
		$this->apikey = $apikey;
		$this->dc     = preg_replace( '/^([a-f0-9]+)-([a-z0-9]+)$/', '$2', $apikey );
		$this->url    = trailingslashit( 'https://' . $this->dc . '.' . $this->domain . '/' . $this->version );
	}

	public function ping() {
		return $this->get( 'ping' );
	}

	public function lists( $args = array() ) {
		$result = $this->get( 'lists', $args );
		return isset( $result->lists ) ? $result->lists : array();
	}

	public function list( $list_id, $args = array() ) {
		return $this->get( 'lists/' . $list_id, $args );
	}

	public function members( $list_id, $args = array() ) {
		return $this->get( 'lists/' . $list_id . '/members', $args );
	}

	private function get( $action, $args = array(), $timeout = 15 ) {
		return $this->do_call( 'GET', $action, $args, $timeout );
	}
	private function post( $action, $args = array(), $timeout = 15 ) {
		return $this->do_call( 'POST', $action, $args, $timeout );
	}


	private function do_call( $method, $action, $args = array(), $timeout = 15 ) {

		$url = $this->url . $action;

		$headers = array(
			'Authorization' => 'apikey ' . $this->apikey,
		);

		$body = null;

		if ( 'GET' == $method ) {
			$url = add_query_arg( $args, $url );
		} elseif ( 'POST' == $method ) {
			$body = $args;
		} else {
			return new WP_Error( 'method_not_allowed', 'This method is not allowed' );
		}

		$key = md5(
			serialize(
				array(
					$url,
					$body,
					$method,
				)
			)
		);

		if ( false !== ( $body = get_transient( 'mailster_mailchimp_api_request_' . $key ) ) ) {
			return $body;
		}

		$response = wp_remote_request(
			$url,
			array(
				'method'  => $method,
				'headers' => $headers,
				'timeout' => $timeout,
				'body'    => $body,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( 200 != $code ) {

			$return = new WP_Error( $body->status, $body->title . ': ' . $body->detail, $body );

			return $return;

		}

		set_transient( 'mailster_mailchimp_api_request_' . $key, $body, 120 );
		return $body;

	}

}
