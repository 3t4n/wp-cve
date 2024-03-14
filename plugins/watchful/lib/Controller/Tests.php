<?php
/**
 * Controller for Watchful tests.
 *
 * @version   2016-12-20 11:41 UTC+01
 * @package   Watchful WP Client
 * @author    Watchful
 * @authorUrl https://watchful.net
 * @copyright Copyright (c) 2020 watchful.net
 * @license   GNU/GPL
 */

namespace Watchful\Controller;

/**
 * WP REST API Menu routes
 */
use WP_REST_Response;
use Watchful\Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Tests
 * Use this class to do some tests.
 *
 * @package Watchful
 */
class Tests implements BaseControllerInterface {

	/**
	 * Register WP REST API routes.
	 */
	public function register_routes() {
		register_rest_route(
			'watchful/v1',
			'/test',
			array(
				array(
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => array( $this, 'test' ),
                    'permission_callback' => '__return_true',
				),
			)
		);

		register_rest_route(
			'watchful/v1',
			'/test/error',
			array(
				array(
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => array( $this, 'error' ),
                    'permission_callback' => '__return_true',
				),
			)
		);

	}

	/**
	 * Connection test.
	 *
	 * @return \WP_REST_Response
	 */
	public function test() {
		return new WP_REST_Response( 'ok' );
	}

	/**
	 * Error test
	 *
	 * @throws Exception To test error messages.
	 */
	public function error() {
		throw new Exception( 'Error message', 403 );
	}

}
