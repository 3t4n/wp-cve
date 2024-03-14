<?php
/**
 * Controller for managing WP files.
 *
 * @version   2016-12-20 11:41 UTC+01
 * @package   Watchful WP Client
 * @author    Watchful
 * @authorUrl https://watchful.net
 * @copyright Copyright (c) 2020 watchful.net
 * @license   GNU/GPL
 */

namespace Watchful\Controller;

use Watchful\Audit\Files\Tools;
use Watchful\Exception;
use Watchful\Helpers\Authentification;
use \WP_REST_Request;
use \WP_REST_Server;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Watchful files class.
 */
class Files implements BaseControllerInterface  {

	/**
	 * Register watchful routes for WP API v2.
	 *
	 * @since  1.2.0
	 */
	public function register_routes() {
		register_rest_route(
			'watchful/v1',
			'/files/chmod',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'chmod' ),
					'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
					'args'                => array_merge(
						Authentification::get_arguments(),
						array(
							'path'        => array(
								'default' => null,
							),
							'permissions' => array(
								'default'           => null,
								'sanitize_callback' => 'esc_attr',
								'validate_callback' => function( $param ) {
									return is_numeric( $param );
								},
							),
						)
					),
				),
			)
		);

		register_rest_route(
			'watchful/v1',
			'/files/',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'read' ),
					'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
					'args'                => array_merge(
						Authentification::get_arguments(),
						array(
							'path' => array(
								'default' => null,
							),
						)
					),
				),
			)
		);

		register_rest_route(
			'watchful/v1',
			'/files/',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'write' ),
					'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
					'args'                => array_merge(
						Authentification::get_arguments(),
						array(
							'path'      => array(
								'default' => null,
							),
							'fileperms' => array(
								'default'           => '0644',
								'sanitize_callback' => 'esc_attr',
								'validate_callback' => function( $param, $request, $key ) {
									return is_numeric( $param );
								},
							),
							'dirperms'  => array(
								'default'           => '0775',
								'sanitize_callback' => 'esc_attr',
								'validate_callback' => function( $param, $request, $key ) {
									return is_numeric( $param );
								},
							),
						)
					),
				),
			)
		);

		register_rest_route(
			'watchful/v1',
			'/files/',
			array(
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete' ),
					'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
					'args'                => array_merge(
						Authentification::get_arguments(),
						array(
							'path' => array(
								'default' => null,
							),
						)
					),
				),
			)
		);
	}


	/**
	 * Set permission on a file or a directory.
	 *
	 * @param WP_REST_Request $request The request containing the chmod info.
	 *
	 * @return string
	 * @throws Exception If one or more required parameters are missing from request.
	 */
	public function chmod( WP_REST_Request $request ) {
		if ( ! $request->get_param( 'path' ) ) {
			throw new Exception( 'parameter is missing. path required', 400 );
		}

		if ( ! $request->get_param( 'permissions' ) ) {
			throw new Exception( 'parameter is missing. permissions required', 400 );
		}

		$tools = new Tools( $request->get_param( 'path' ) );
		return $tools->chmod( $request->get_param( 'permissions' ) );
	}

	/**
	 * Read contents of a file.
	 *
	 * @param WP_REST_Request $request The request containing the file info.
	 *
	 * @return string
	 * @throws Exception If file info is not available in request.
	 */
	public function read( WP_REST_Request $request ) {
		if ( ! $request->get_param( 'path' ) ) {
			throw new Exception( 'parameter is missing. path required', 400 );
		}

		$tools = new Tools( $request->get_param( 'path' ) );
		return $tools->read();
	}

	/**
	 * Write content to a file or create a file.
	 *
	 * @param WP_REST_Request $request The request with the file info.
	 *
	 * @return string
	 * @throws Exception If file info is not available in request.
	 */
	public function write( WP_REST_Request $request ) {
		if ( ! $request->get_param( 'path' ) ) {
			throw new Exception( 'parameter is missing. path required', 400 );
		}

		if ( ! $request->get_body() ) {
			throw new Exception( 'body is empty', 400 );
		}

		$tools = new Tools( $request->get_param( 'path' ), true );
		return $tools->write( $request->get_body(), $request->get_param( 'fileperms' ), $request->get_param( 'dirperms' ) );
	}

	/**
	 * Delete a file or a directory.
	 *
	 * @param WP_REST_Request $request The request with the file info.
	 *
	 * @return string
	 * @throws Exception If file info is not available in request.
	 */
	public function delete( WP_REST_Request $request ) {
		if ( ! $request->get_param( 'path' ) ) {
			throw new Exception( 'parameter is missing. path required', 400 );
		}

		$tools = new Tools( $request->get_param( 'path' ) );
		return $tools->delete();
	}
}
