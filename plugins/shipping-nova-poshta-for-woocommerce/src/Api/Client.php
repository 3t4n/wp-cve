<?php
/**
 * Client for Nova Poshta API.
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Api;

/**
 * Class Client is the main class that communicates with
 * the Sendinblue API.
 *
 * @since 1.0.0
 */
class Client {

	/**
	 * Authentication HTTP header name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const HEADER_AUTH_KEY = 'api-key';

	/**
	 * API URL for tracking events.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const API_URL = 'https://api.novaposhta.ua/%s/json/';

	/**
	 * URL prefix.
	 *
	 * @since 1.0.0
	 */
	const API_VERSION_URL = 'v2.0';

	/**
	 * Validator for fields.
	 *
	 * @var \NovaPoshta\Api\ValidateField
	 */
	private $validator;

	/**
	 * Client constructor.
	 *
	 * @param \NovaPoshta\Api\ValidateField $validator Validate fields.
	 */
	public function __construct( ValidateField $validator ) {

		$this->validator = $validator;
	}

	/**
	 * Create a new connection.
	 *
	 * @since {VERSION}
	 *
	 * @param string $api_key API key.
	 *
	 * @return Connection
	 */
	public function new_connection( string $api_key ): Connection {

		return new Connection(
			new Http\Request(
				$this->get_url(),
				[
					'body' => [
						'apiKey' => $api_key,
					],
				]
			),
			$this->validator
		);
	}

	/**
	 * Retrieve an API URL with include a API version
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_url() {

		return sprintf( self::API_URL, self::API_VERSION_URL );
	}
}
