<?php
/**
 * Data Sync Request
 *
 * Abstract Request
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Request {

	/**
	 * @var WP_REST_Request
	 */

	private $request;

	/**
	 * Set Request
	 *
	 * @param WP_REST_Request $request
	 */

	public function set_request( WP_REST_Request $request ) {
		$this->request = $request;
	}

	/**
	 * Allow access to sync data.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */

	public function access( WP_REST_Request $request ) {

		$this->set_request( $request );

		Log::write( 'request', $this->request->get_method(), 'Resquest Method' );

		if ( $this->allowed() && $this->referer() && $this->content_length() && $this->user_agent() ) {
			return $this->private_key();
		}

		return false;

	}

	/**
	 * Is access allowed.
	 *
	 * @return bool
	 */

	public function allowed() {
		return Settings::is_checked( $this->permissions_key );
	}

	/**
	 * Verify the access_token.
	 *
	 * @return bool|string
	 */

	public function access_token( $param ) {

		$access_token = sanitize_key( $param );

		if ( empty( $access_token ) ) {
			return false;
		}

		if ( ! $local_token = get_option( $this->access_token_key ) ) {
			return false;
		}

		Log::write( 'request', "Access Token Provided" );

		if ( $access_token === $local_token ) {

			Log::write( 'request', "Access token Approved" );

			return true;

		}

		return false;

	}

	/**
	 * Verify private key.
	 *
	 * @return bool
	 */

	public function private_key() {

		$private_key = sanitize_key( $this->request->get_header( 'authentication' ) );

		if ( empty( $private_key ) ) {
			return false;
		}

		if ( ! $local_token = get_option( $this->private_token_key ) ) {
			return false;
		}

		Log::write( 'request', "Private Token Provided" );

		if ( $private_key === $local_token ) {

			Log::write( 'request', "Private Token Approved" );

			return true;

		}

		return false;

	}

	/**
	 * Get the HTTP referer header.
	 *
	 * @return bool
	 */

	public function referer() {

		$referer = sanitize_text_field( $this->request->get_header( 'referer' ) );

		if ( empty( $referer )  ) {
			return false;
		}

		Log::write( 'request', "Referer: $referer" );

		return true;

	}

	/**
	 * Content length.
	 *
	 * @return bool
	 */

	public function content_length() {

		global $wpds_response;

		if ( 'GET' === $this->request->get_method() ) {
			return true;
		}

		$json           = $this->request->get_body();
		$content_length = $this->request->get_header( 'content-length' );

		if ( empty( $content_length ) ) {

			$wpds_response['content-length'] = 'Content length not provided.';

			return false;

		}

		$match = strlen( $json ) === intval( $content_length );

		if ( ! $match ) {
			$wpds_response['content-length'] = 'Content length does not match.';
		}

		Log::write( 'request', $content_length, 'Content Length Match' );

		return $match;

	}

	/**
	 * User Agent
	 *
	 * @return bool
	 */

	public function user_agent() {

		$user_agent = $this->request->get_header( 'user-agent' );

		Log::write( 'request', $user_agent, 'User Agent' );

		return 'WP Data Sync API' === $user_agent;

	}

	/**
	 * Request data.
	 *
	 * @return mixed|void
	 */

	public function request_data() {

		$json     = $this->request->get_body();

		Log::write( $this->log_key, 'Sync Request JSON' );
		Log::write( $this->log_key, $json );

		$raw_data = json_decode( $json, true );

		Log::write( $this->log_key, 'Sync Request Raw Data' );
		Log::write( $this->log_key, $raw_data );

		$data     = $this->sanitize_request( $raw_data );

		Log::write( $this->log_key, 'Sync Request Sanitized Data' );
		Log::write( $this->log_key, $data );

		return apply_filters( 'wp_data_sync_data', $data );

	}

	/**
	 * Sanitize request.
	 *
	 * @param $raw_data
	 *
	 * @return array|bool
	 */

	public function sanitize_request( $raw_data ) {

		$data = [];

		if ( ! is_array( $raw_data ) ) {
			die( __( 'A valid array is required!!', 'wp-data-sync' ) );
		}

		foreach ( $raw_data as $key => $value ) {

			$key = $this->sanitize_key( $key );

			if ( is_array( $value ) ) {

				$data[ $key ] = $this->sanitize_request( $value );

			} else {

				$sanitize_callback = $this->sanitize_callback( $key );

				$data[ $key ] = $this->sanitize_data( $sanitize_callback, $value );

			}

		}

		unset( $data['access_token'] );

		return $data;

	}

	/**
	 * Sanitize key.
	 *
	 * @param $key
	 *
	 * @return bool|float|int|string
	 */

	public function sanitize_key( $key ) {

		if ( is_string( $key ) ) {
			return $this->sanitize_data( 'string', $key );
		}

		if ( is_int( $key ) ) {
			return intval( $key );
		}

		die( __( 'A valid array is required!!', 'wp-data-sync' ) );

	}

	/**
	 * Sanitize callback.
	 *
	 * @param $key
	 *
	 * @return mixed|void
	 */

	public function sanitize_callback( $key ) {

		switch ( $key ) {

			case 'post_content' :
			case 'post_excerpt'	:
				$sanitize_callback = 'html';
				break;

			case 'image_url' :
				$sanitize_callback = 'url';
				break;

			default :
				$sanitize_callback = 'string';

		}

		Log::write( 'sanitize-callback', "$key - $sanitize_callback" );

		return apply_filters( 'wp_data_sync_sanitize_callback', $sanitize_callback, $key );

	}

	/**
	 * Sanitize data.
	 *
	 * @param $sanitize_callback
	 * @param $value
	 *
	 * @return bool|float|int|string
	 */

	public function sanitize_data( $sanitize_callback, $value ) {

		$value = trim( $value );

		if ( empty( $value ) ) {
			return '';
		}

		switch ( $sanitize_callback ) {

			case 'bool':
				$clean_value = boolval( $value );
				break;

			case 'float':
				$clean_value = floatval( $value );
				break;

			case 'int':
				$clean_value = intval( $value );
				break;

			case 'numeric':
				$clean_value = sanitize_text_field( $value );
				break;

			case 'email':
				$clean_value = sanitize_email( $value );
				break;

			case 'key':
				$clean_value = sanitize_key( $value );
				break;

			case 'html':
				// If we have some html from an editor, let's use allowed post html.
				// All scripts, videos, etc... will be removed.
				$clean_value = wp_kses_post( $value );
				break;

			case 'url':
				$clean_value = sanitize_url( $value );
				break;

			case 'title':
				$clean_value = sanitize_title( $value );
				break;

			case 'filename':
				$clean_value = sanitize_file_name( $value );
				break;

			default:
				$clean_value = sanitize_text_field( $value );

		}

		$encoding = mb_detect_encoding( $clean_value, 'auto' );

		if ( 'ASCII' !== $encoding ) {

			Log::write( 'encoding', [
				'encoding'    => $encoding,
				'clean_value' => $clean_value
			] );

		}

		return apply_filters( 'wp_data_sync_clean_value', $clean_value, $sanitize_callback );

	}

}