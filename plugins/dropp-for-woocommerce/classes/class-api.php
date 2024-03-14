<?php
/**
 * API
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp;

use Dropp\Exceptions\Request_Exception;
use Dropp\Exceptions\Response_Exception;
use Exception;
use WC_Log_Levels;
use WC_Logger;
use WC_Shipping;
use Dropp\Models\Model;
use WP_Error;

/**
 * API
 */
class API {
	public bool $require_auth = true;
	public bool $test = false;
	public bool $debug = false;
	public array $errors = [];

	public function __construct() {
		$options     = Options::get_instance();
		$this->test  = $options->test_mode;
		$this->debug = $options->debug_mode;
	}

	/**
	 * No Authentization
	 *
	 * @return API This object
	 */
	public function noauth(): API {
		$this->require_auth = false;

		return $this;
	}

	/**
	 * Get API key
	 *
	 * @return string API key.
	 * @throws Exception When API key is not available.
	 */
	public function get_api_key(): string {
		$options     = Options::get_instance();
		$api_key = $options->test_mode ? $options->api_key_test : $options->api_key;
		if ( $this->require_auth && empty( $api_key ) ) {
			throw new Exception( __( 'No API key could be found.', 'dropp-for-woocommerce' ), 1 );
		}

		return $api_key;
	}

	/**
	 * Get URL
	 *
	 * @param string $endpoint Endpoint.
	 *
	 * @return string URL.
	 */
	public function endpoint_url( string $endpoint ): string {
		$baseurl = 'https://api.dropp.is/dropp/api/v1/';
		if ( $this->test ) {
			$baseurl = 'https://stage.dropp.is/dropp/api/v1/';
		}

		return $baseurl . $endpoint;
	}

	/**
	 * Remote get
	 *
	 * @param string $endpoint  Endpoint.
	 * @param string $data_type (optional) 'json', 'body' or 'raw'.
	 *
	 * @return array|string      Decoded json, string body or raw response object.
	 * @throws Response_Exception
	 * @throws Request_Exception
	 */
	public function get( string $endpoint, string $data_type = 'json' ) {
		$response = $this->remote( 'get', self::endpoint_url( $endpoint ) );

		return $this->process_response( 'get', $response, $data_type );
	}

	/**
	 * Remote post
	 *
	 * @param string $endpoint  Endpoint.
	 * @param Model  $model     Model.
	 * @param string $data_type (optional) 'json', 'body' or 'raw'.
	 *
	 * @return array|string           Decoded json, string body or raw response object.
	 * @throws Request_Exception
	 * @throws Response_Exception
	 */
	public function post( string $endpoint, Model $model, string $data_type = 'json' ) {
		$response = $this->remote( 'post', self::endpoint_url( $endpoint ), $model );

		return $this->process_response( 'post', $response, $data_type );
	}

	/**
	 * Remote patch
	 *
	 * @param string $endpoint Endpoint.
	 * @param Model $model Model.
	 * @param string $data_type (optional) 'json', 'body' or 'raw'.
	 *
	 * @return array|string           Decoded json, string body or raw response object.
	 * @throws Exception
	 */
	public function patch( string $endpoint, Model $model, string $data_type = 'json' ) {
		$response = $this->remote( 'patch', self::endpoint_url( $endpoint ), $model );

		return $this->process_response( 'patch', $response, $data_type );
	}

	/**
	 * Remote delete
	 *
	 * @param string $endpoint Endpoint.
	 * @param Model $model Model.
	 * @param string $data_type (optional) 'json', 'body' or 'raw'.
	 *
	 * @return array|string           Decoded json, string body or raw response object.
	 * @throws Exception
	 */
	public function delete( string $endpoint, Model $model, string $data_type = 'json' ) {
		$response = $this->remote( 'delete', self::endpoint_url( $endpoint ), $model );

		return $this->process_response( 'delete', $response, $data_type );
	}

	/**
	 * Remote args
	 *
	 * @param string $method Remote method, either 'get' or 'post'.
	 * @param string $url Url.
	 * @param ?Model $model Model.
	 *
	 * @return array|WP_Error               Remote arguments.
	 * @throws Request_Exception Unknown method.
	 */
	public function remote( string $method, string $url, ?Model $model = null ) {
		$log  = new WC_Logger();
		$args = [
			'headers' => [
				'Content-Type' => 'application/json;charset=UTF-8',
			],
		];
		if ( $this->require_auth ) {
			$args['headers']['Authorization'] = 'Basic ' . $this->get_api_key();
		}

		$allowed_methods = [ 'get', 'post', 'delete', 'patch' ];
		if ( ! in_array( $method, $allowed_methods, true ) ) {
			throw new Request_Exception( "Unknown method, \"$method\"" );
		}
		$args['method'] = strtoupper( $method );
		if ( 'delete' === $method ) {
			$args['method'] = 'DELETE';
		}
		if ( 'patch' === $method || 'post' === $method ) {
			$args['body'] = wp_json_encode( $model->to_array() ?? '' );
		}
		if ( $this->debug ) {
			$log->add(
				'dropp-for-woocommerce',
				'[DEBUG] Remote ' . strtoupper( $method ) . ' request:' . PHP_EOL . $url . PHP_EOL . wp_json_encode( $args, JSON_PRETTY_PRINT ),
				WC_Log_Levels::DEBUG
			);
		}

		return wp_remote_request( $url, $args );
	}

	/**
	 * Process response
	 *
	 * @param string $method Remote method, either 'get' or 'post'.
	 * @param WP_Error|array $response Array with response data on success.
	 * @param string $data_type (optional) 'json', 'body' or 'raw'
	 *
	 * @return array|string              Decoded json, string body or raw response object.
	 * @throws Response_Exception $e      Response exception.
	 */
	protected function process_response( string $method, WP_Error|array $response, string $data_type = 'json' ): array|string {
		$log = new WC_Logger();
		if ( is_wp_error( $response ) ) {
			$log->add(
				'dropp-for-woocommerce',
				'[ERROR] Remote ' . strtoupper( $method ) . ' response:' . PHP_EOL . wp_json_encode( $response->errors, JSON_PRETTY_PRINT ),
				WC_Log_Levels::ERROR
			);
		} elseif ( $this->debug ) {
			$data = json_decode( $response['body'] );
			if ( ! empty( $data ) ) {
				$body = wp_json_encode( $data, JSON_PRETTY_PRINT );
			} else {
				$body = $response['body'];
			}
			if ( 'raw' === $data_type ) {
				if ( 100 < strlen( $body ) ) {
					$body = substr( $body, 0, 100 ) . '...';
				}
				$body = htmlspecialchars( $body );
			}
			$log->add(
				'dropp-for-woocommerce',
				'[DEBUG] Remote ' . strtoupper( $method ) . ' response:' . PHP_EOL . $body,
				WC_Log_Levels::DEBUG
			);
		}

		// Validate response.
		if ( is_wp_error( $response ) ) {
			$this->errors = $response->get_error_messages();
			throw new Response_Exception( __( 'Response error', 'dropp-for-woocommerce' ) );
		}
		if (! str_starts_with($response['response']['code'], '2')){
			throw new Response_Exception( __( 'Response error with code ' .$response['response']['code'], 'dropp-for-woocommerce' ) );
		}

		if ( 'raw' === $data_type ) {
			return $response;
		}

		if ( 'body' === $data_type ) {
			return $response['body'];
		}

		$data = json_decode( $response['body'], true );
		if ( ! is_array( $data ) ) {
			$this->errors['invalid_json'] = $response['body'];
			throw new Response_Exception( __( 'Invalid json', 'dropp-for-woocommerce' ) );
		}

		return $data;
	}
}
