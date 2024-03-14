<?php
/**
 * Main file for initializing our plugins API.
 *
 * @package Omnipress\RestApi
 */

namespace Omnipress\RestApi;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RestApi {

	/**
	 * This class instance.
	 *
	 * @var RestApi
	 */
	private static $instance;

	/**
	 * Returns instance of this class.
	 *
	 * @return RestApi
	 */
	public static function init() {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Registered controllers.
	 *
	 * @var array
	 */
	protected $controllers = array();

	/**
	 * Class construct.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'api_init' ) );
	}

	/**
	 * Register our API controllers and namespaces.
	 *
	 * @return void
	 */
	public function api_init() {
		$namespaces = apply_filters( 'omnipress_rest_api_namespaces', $this->get_namespaces() );

		if ( is_array( $namespaces ) && ! empty( $namespaces ) ) {
			foreach ( $namespaces as $namespace => $controllers ) {
				if ( is_array( $controllers ) && ! empty( $controllers ) ) {
					foreach ( $controllers as $rest_base => $controller ) {
						$this->controllers[ $namespace ][ $rest_base ] = new $controller( $namespace, $rest_base );
						$this->controllers[ $namespace ][ $rest_base ]->register_routes();
					}
				}
			}
		}
	}

	/**
	 * Returns version 1 api controllers.
	 *
	 * @return array
	 */
	public function get_v1_controllers() {
		return array(
			'fonts'    => __NAMESPACE__ . '\\Controllers\\V1\\FontsController',
			'demos'    => __NAMESPACE__ . '\\Controllers\\V1\\DemosController',
			'patterns' => __NAMESPACE__ . '\\Controllers\\V1\\PatternsController',
		);
	}

	/**
	 * Returns our namespaces.
	 *
	 * @return array
	 */
	public function get_namespaces() {
		return array(
			'omnipress/v1' => $this->get_v1_controllers(),
		);
	}
}