<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <hi@atanas.dev>
 * @copyright 2017-2019 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\WPEmerge\Middleware;

/**
 * Interface for HasControllerMiddlewareTrait.
 */
interface HasControllerMiddlewareInterface {
	/**
	 * Get middleware.
	 *
	 * @param  string   $method
	 * @return string[]
	 */
	public function getMiddleware( $method );

	/**
	 * Add middleware.
	 *
	 * @param  string|string[]      $middleware
	 * @return ControllerMiddleware
	 */
	public function addMiddleware( $middleware );

	/**
	 * Fluent alias for addMiddleware().
	 *
	 * @param  string|string[]      $middleware
	 * @return ControllerMiddleware
	 */
	public function middleware( $middleware );
}
