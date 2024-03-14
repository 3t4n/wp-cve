<?php
/**
 * Base class for controllers.
 *
 * @package Omnipress\Abstracts
 */

namespace Omnipress\Abstracts;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base class for controllers.
 *
 * @since 1.1.0
 */
class RestControllersBase extends \WP_REST_Controller {

	/**
	 * Class construct.
	 *
	 * @param string $namespace API namespace.
	 * @param string $rest_base API rest or route base.
	 */
	public function __construct( $namespace, $rest_base ) {
		$this->namespace = $namespace;
		$this->rest_base = $rest_base;

		$this->register_routes();
	}

	/**
	 * Register Routes.
	 */
	public function register_rest_route( $args, $base = '' ) {
		$route = $this->rest_base;

		if ( ! empty( $base ) ) {
			$route .= "/{$base}";
		}

		register_rest_route( $this->namespace, $route, $args );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_items_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_item_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function update_item_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete_item_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}
}
