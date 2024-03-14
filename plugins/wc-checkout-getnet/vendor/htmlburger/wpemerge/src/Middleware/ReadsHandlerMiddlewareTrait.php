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

use CoffeeCode\WPEmerge\Helpers\Handler;

/**
 * Describes how a request is handled.
 */
trait ReadsHandlerMiddlewareTrait {
	/**
	 * Get middleware registered with the given handler.
	 *
	 * @param  Handler  $handler
	 * @return string[]
	 */
	protected function getHandlerMiddleware( Handler $handler ) {
		$instance = $handler->make();

		if ( ! $instance instanceof HasControllerMiddlewareInterface ) {
			return [];
		}

		return $instance->getMiddleware( $handler->get()['method'] );
	}
}
