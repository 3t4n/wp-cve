<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\API;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use WP_REST_Server;

/**
 * This class should be extended from the actual Endpoint class.
 * The Endpoint class should have the following methods defined:
 * - get_name()
 * - get_namespace()
 */
abstract class Endpoint
{
	/**
	 * Available Methods
	 * 
	 * @var  array
	 */
	const AVAILABLE_METHODS = [
		WP_REST_Server::READABLE,
		WP_REST_Server::CREATABLE,
		WP_REST_Server::EDITABLE,
		WP_REST_Server::DELETABLE,
		WP_REST_Server::ALLMETHODS,
	];

	/**
	 * Register route.
	 *
	 * @param   string  $route
	 * @param   string  $methods
	 * @param   null    $callback
	 * @param   array   $args
	 *
	 * @return  bool
	 */
	public function register_route($route = '', $methods = WP_REST_Server::READABLE, $callback = null, $args = [], $permission_callback = null)
	{
		if ( ! in_array( $methods, self::AVAILABLE_METHODS, true ) ) {
			throw new \Exception( 'Invalid method.' );
		}

		$route = $this->get_base_route() . $route;

		return register_rest_route( $this->get_namespace(), $route, [
			[
				'args' => $args,
				'methods' => $methods,
				'callback' => $callback,
				'permission_callback' => $permission_callback == null ? [$this, 'check_permission_callback'] : $permission_callback
			],
		] );
	}

	/**
	 * Get permission callback.
	 *
	 * By default get permission callback from the controller.
	 *
	 * @param   \WP_REST_Request  $request Full data about the request.
	 *
	 * @return  boolean
	 */
	public function check_permission_callback($request)
	{
		if (!method_exists($this, 'get_permission_callback'))
		{
			return true;
		}
		
		return $this->get_permission_callback($request);
	}

	/**
	 * Get base route.
	 *
	 * @return  string
	 */
	public function get_base_route()
	{
		$endpoint_name = $this->get_name();

		$route = '';
		
		if (method_exists($this, 'get_rest_base') && $rest_base = $this->get_rest_base())
		{
			$route .= $rest_base;
		}
		
		return $route . '/' . $endpoint_name;
	}

	/**
	 * Get endpoint name.
	 *
	 * @return  string
	 */
	abstract public function get_name();
}