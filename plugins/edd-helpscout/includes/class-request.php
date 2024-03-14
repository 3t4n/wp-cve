<?php

namespace EDD\HelpScout;

/**
 *
 */
class Request {

	/**
	 * @var string
	 */
	public $signature = '';

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var string
	 */
	private static $secret_key = HELPSCOUT_SECRET_KEY;

	/**
	 * @param array $data
	 */
	public function __construct( $data ) {
		$this->data = $data;
		$this->signature = $this->create_expected_signature();
	}

	/**
	 * @return string
	 */
	private function create_expected_signature() {
		return base64_encode( hash_hmac( 'sha1', json_encode( $this->data ), self::$secret_key, true ) );
	}

	/**
	 * @param $signature
	 *
	 * @return bool
	 */
	public function signature_equals( $signature ) {

		// use `hash_equals( str1, str2 )` if it exists
		if( function_exists( 'hash_equals' ) ) {
			return hash_equals( $this->signature, $signature );
		}

		return $this->signature === $signature;
	}


	/**
	 * @param string $action
	 *
	 * @return string
	 */
	public function get_signed_url( $action = '' ) {

		$args = $this->data;

		// add signature to url args
		$args['s'] = $this->signature;

		return add_query_arg( urlencode_deep( $args ), home_url( rtrim( EDD_HELPSCOUT_API_PATH, '/' ) . '/' . $action ) );
	}


}