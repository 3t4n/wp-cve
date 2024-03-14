<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Stock_Sync_Api_Check {
	protected $errors = [];
	public $url = null;
	public $key = null;
	public $secret = null;

	/**
	 * Constructor
	 */
	public function __construct($url, $key, $secret) {
		$this->url = $url;
		$this->key = $key;
		$this->secret = $secret;
	}

	/**
	 * Check
	 */
	public function check($type) {
		$this->errors = [];

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			$this->errors[] = __( 'You do not have access to this function', 'woo-stock-sync' );
		} else {
			call_user_func( [ $this, "check_{$type}" ] );
		}

		wp_send_json( [
			'ok' => empty( $this->errors ),
			'errors' => $this->errors,
		] );
		die;
	}

	/**
	 * Check format
	 */
	public function check_format() {
		// Check that API key starts with ck_
		if ( strpos( $this->key, 'ck_' ) !== 0 ) {
			$this->errors[] = __( 'API Key should start with "ck_"', 'woo-stock-sync' );
		}

		// Check that API secret starts with cs_
		if ( strpos( $this->secret, 'cs_' ) !== 0 ) {
			$this->errors[] = __( 'API Secret should start with "cs_"', 'woo-stock-sync' );
		}
	}

	/**
	 * Check that the URL is accessible
	 */
	public function check_url() {
		$response = wp_remote_get( $this->url, [
			'timeout' => 30,
			'redirection' => 0,
		] );

		if ( ! is_wp_error( $response ) ) {
			$code = wp_remote_retrieve_response_code( $response );

			if ( $code !== 200 ) {
				if ( $code === 301 || $code === 302 ) {
					$location = wp_remote_retrieve_header( $response, 'location' );

					// Do not report trailing slash as error
					if ( rtrim( $location, '/' ) === rtrim( $this->url, '/' ) ) {
						return;
					}

					$this->errors[] = sprintf( __( '<strong>%s</strong> is redirecting to <strong>%s</strong>. Please use <strong>%s</strong> as the URL.', 'woo-stock-sync' ), $this->url, $location, $location );
				} else {
					$headers = wp_remote_retrieve_headers( $response );
					if ( is_callable( [ $headers, 'getAll'] ) ) {
						$headers = $headers->getAll();
					} else {
						$headers = [];
					}

					update_option( 'wss_last_response', [
						'code' => $code,
						'body' => wp_remote_retrieve_body( $response ),
						'headers' => $headers,
					] );

					$url = admin_url( 'admin-ajax.php?action=wss_view_last_response' );
					$this->errors[] = sprintf( __( 'Invalid response code %s. <a href="%s" target="_blank">View response for debugging &raquo;</a>', 'woo-stock-sync' ), $code, $url );
				}
			}
		} else {
			$this->errors[] = sprintf( __( 'Connection error: %s', 'woo-stock-sync' ), $response->get_error_message() );
		}
	}

	/**
	 * Check that REST API is accessible
	 */
	public function check_rest_api() {
		// We will submit invalid credentials by purpose 
		// If API responds that credentials are faulty, we are good as REST API is located
		// in the correct URL
		$client = Woo_Stock_Sync_Api_Client::create( $this->url, 'invalid', 'invalid' );

		try {
			$response = $client->get('');
		} catch (\Automattic\WooCommerce\HttpClient\HttpClientException $e ) {
			$response = $e->getResponse();
			$request = $e->getRequest();

			if ( $response->getCode() === 401 ) {
				// All good, we will check credentials in the next step
				return;
			} else if ( $response->getCode() === 404 ) {
				$this->errors[] = sprintf( __( 'WooCommerce REST API is not accessible at <pre>%s</pre>In most cases this means that permalink structure is set to Plain or REST API is disabled or hidden by some WP security plugin such as Defender', 'woo-stock-sync' ), $request->getUrl() );
			} else {
				update_option( 'wss_last_response', [
					'code' => $response->getCode(),
					'body' => $response->getBody(),
					'headers' => $response->getHeaders(),
				] );

				$url = admin_url( 'admin-ajax.php?action=wss_view_last_response' );
				$this->errors[] = sprintf( __( 'Website responsed in a way that could not be understood. <a href="%s" target="_blank">View response for debugging &raquo;</a>', 'woo-stock-sync' ), $url );
			}
		} catch ( \Exception $e ) {
			$this->errors[] = $e->getMessage();
		}
	}

	/**
	 * Check that API credentials are correct
	 */
	public function check_credentials() {
		try {
			$response = $this->get_client()->get( 'products' );
		} catch (\Automattic\WooCommerce\HttpClient\HttpClientException $e ) {
			$response = $e->getResponse();
			$request = $e->getRequest();

			$url = admin_url( 'admin-ajax.php?action=wss_view_last_response' );
			update_option( 'wss_last_response', [
				'code' => $response->getCode(),
				'body' => $response->getBody(),
				'headers' => $response->getHeaders(),
			] );

			if ( $response->getCode() === 401 ) {
				$this->errors[] = sprintf( __( 'API Key or Secret is invalid or API user has only write permissions. <a href="%s" target="_blank">View response for debugging &raquo;</a>', 'woo-stock-sync' ), $url );
			} else {
				$this->errors[] = sprintf( __( 'Website responsed in a way that could not be understood. <a href="%s" target="_blank">View response for debugging &raquo;</a>', 'woo-stock-sync' ), $url );
			}
		} catch ( \Exception $e ) {
			$this->errors[] = $e->getMessage();
		}
	}

	/**
	 * Check that Stock Sync is installed
	 */
	public function check_stock_sync() {
		try {
			$response = $this->get_client()->get('stock-sync-exists');
		} catch (\Automattic\WooCommerce\HttpClient\HttpClientException $e ) {
			$response = $e->getResponse();
			$request = $e->getRequest();

			if ( $response->getCode() === 404 ) {
				$this->errors[] = sprintf( __( 'Stock Sync is not installed on %s', 'woo-stock-sync' ), $this->url );
			} else if ( $response->getCode() === 403 ) {
				// This is good, we will check for privileges in the next step
				return;
			} else {
				update_option( 'wss_last_response', [
					'code' => $response->getCode(),
					'body' => $response->getBody(),
					'headers' => $response->getHeaders(),
				] );

				$url = admin_url( 'admin-ajax.php?action=wss_view_last_response' );
				$this->errors[] = sprintf( __( 'Website responsed in a way that could not be understood. <a href="%s" target="_blank">View response for debugging &raquo;</a>', 'woo-stock-sync' ), $url );
			}
		} catch ( \Exception $e ) {
			$this->errors[] = $e->getMessage();
		}
	}

	/**
	 * Get client
	 */
	protected function get_client() {
		return Woo_Stock_Sync_Api_Client::create( $this->url, $this->key, $this->secret );
	}

	/**
	 * Check that API user has read / write access
	 */
	public function check_privileges() {
		try {
			$response = $this->get_client()->post( 'stock-sync-batch', [
				'update' => [],
			] );
		} catch (\Automattic\WooCommerce\HttpClient\HttpClientException $e ) {
			$response = $e->getResponse();
			$request = $e->getRequest();

			if ( in_array( $response->getCode(), [ 401, 403 ], true ) ) {
				$body = json_decode( $response->getBody() );

				if ( is_object( $body ) && isset( $body->message ) ) {
					$this->errors[] = $body->message;
				} else {
					$this->errors[] = __( 'API user has invalid permissions', 'woo-stock-sync' );
				}

				update_option( 'wss_last_response', [
					'code' => $response->getCode(),
					'body' => $response->getBody(),
					'headers' => $response->getHeaders(),
				] );

				$url = admin_url( 'admin-ajax.php?action=wss_view_last_response' );
				$this->errors[] = sprintf( __( 'Usually this means that the API user does not have role "Administrator". <a href="%s" target="_blank">View response for debugging &raquo;</a>', 'woo-stock-sync' ), $url );
			} else if ( $response->getCode() === 404 ) {
				return;
			} else {
				update_option( 'wss_last_response', [
					'code' => $response->getCode(),
					'body' => $response->getBody(),
					'headers' => $response->getHeaders(),
				] );

				$url = admin_url( 'admin-ajax.php?action=wss_view_last_response' );
				$this->errors[] = sprintf( __( 'Website responsed in a way that could not be understood. <a href="%s" target="_blank">View response for debugging &raquo;</a>', 'woo-stock-sync' ), $url );
			}
		} catch ( \Exception $e ) {
			$this->errors[] = $e->getMessage();
		}
	}
}
