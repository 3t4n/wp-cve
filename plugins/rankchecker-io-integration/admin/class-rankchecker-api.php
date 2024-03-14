<?php

/**
 * Class Rankchecker_Api
 */
class Rankchecker_Api {

	private static $instances = [];

	private $api_url = 'https://rankchecker.io/api/v1';

	private $default_req_args = [
		'headers' => [
			'Accept' => 'application/json',
		],
	];

	protected function __construct() {

		if ( $token = get_option( 'rc_api_key' ) ) {
			$this->set_token( $token );
		}

	}

	protected function __clone() {
	}

	/**
	 * @throws Exception
	 */
	public function __wakeup() {
		throw new \Exception( "Cannot unserialize a singleton." );
	}

	public static function get_instance(): Rankchecker_Api {
		$cls = static::class;
		if ( ! isset( self::$instances[ $cls ] ) ) {
			self::$instances[ $cls ] = new static();
		}

		return self::$instances[ $cls ];
	}

	private function set_token( string $token ) {

		$req_args                                 = $this->default_req_args;
		$req_args[ 'headers' ][ 'Authorization' ] = 'Bearer ' . $token;

		$this->default_req_args = $req_args;

	}

	public function is_valid_api_token( string $token ) {

		$response = wp_remote_get( $this->api_url . '/domains', [
			'headers' => [
				'Accept'        => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			],
		] );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		if ( $response[ 'response' ][ 'code' ] !== 200 ) {
			return false;
		}

		return true;

	}

	public function get_domains() {

		$response = wp_remote_get( $this->api_url . '/domains', $this->default_req_args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( $response[ 'response' ][ 'code' ] !== 200 ) {
			return new WP_Error( $response[ 'response' ][ 'code' ], 'API Error' );
		}

		return json_decode( $response[ 'body' ], true )[ 'data' ];

	}

	public function get_domain( int $id ) {

		$response = wp_remote_get( $this->api_url . '/domains/' . $id, $this->default_req_args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( $response[ 'response' ][ 'code' ] !== 200 ) {
			return new WP_Error( $response[ 'response' ][ 'code' ], 'API Error' );
		}

		return json_decode( $response[ 'body' ], true )[ 'data' ];

	}

	public function get_domain_keywords( int $id, $order_by = 'rank', $limit = 10 ) {

		$response = wp_remote_get( $this->api_url . '/domains/' . $id . '/keywords?' . build_query( [ 'order_by' => $order_by, 'limit' => $limit ] ), $this->default_req_args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( $response[ 'response' ][ 'code' ] !== 200 ) {
			return new WP_Error( $response[ 'response' ][ 'code' ], 'API Error' );
		}

		return json_decode( $response[ 'body' ], true )[ 'data' ];

	}

	public function get_badge_images() {

		$response = wp_remote_get( $this->api_url . '/badge-images', $this->default_req_args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( $response[ 'response' ][ 'code' ] !== 200 ) {
			return new WP_Error( $response[ 'response' ][ 'code' ], 'API Error' );
		}

		return json_decode( $response[ 'body' ], true )[ 'data' ];

	}

	public function update_badge_image( $badge_id, $image_id ) {

		$req_args                                = $this->default_req_args;
		$req_args[ 'method' ]                    = 'PUT';
		$req_args[ 'body' ]                      = json_encode( [ 'badge_storage_id' => $image_id ] );
		$req_args[ 'data_format' ]               = 'body';
		$req_args[ 'headers' ][ 'Content-Type' ] = 'application/json';

		$response = wp_remote_request( $this->api_url . '/badges/' . $badge_id, $req_args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( $response[ 'response' ][ 'code' ] !== 200 ) {
			return new WP_Error( $response[ 'response' ][ 'code' ], 'API Error' );
		}

		return json_decode( $response[ 'body' ], true )[ 'success' ];

	}

}
