<?php

namespace Faire\Wc\Api\Client;

use Faire\Wc\Api\Interfaces\Api_Auth_Interface;
use Faire\Wc\Api\Request;

/**
 * The authorization controller for Faire.
 *
 * To authenticate calls to the API, pass your Faire API access token
 * as an HTTP request header named "X-FAIRE-ACCESS-TOKEN".
 */
class Auth implements Api_Auth_Interface {

  /**
	 * The header token
	 *
	 * @var String
	 */
	protected $token;

	/**
	 * The Faire authorization header where the access token is included.
	 *
	 * @since [*next-version*]
	 */
	const H_ACCESS_TOKEN = 'X-FAIRE-ACCESS-TOKEN';

	/**
	 * Constructor.
	 *
	 * @since [*next-version*]
	 * @param string $token    The access token.
	 */
	public function __construct( $token ) {
		$this->token = $token;
	}

	/**
	 * Sets the Faire header token for a request.
	 *
	 * @since [*next-version*]
	 */
	public function authorize( Request $request ) {
		$request->headers[ static::H_ACCESS_TOKEN ] = $this->token;
		return $request;
	}

}
