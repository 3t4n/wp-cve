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

namespace CoffeeCode\WPEmerge\Kernels;

use CoffeeCode\WPEmerge\ServiceProviders\ExtendsConfigTrait;
use CoffeeCode\WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Provide old input dependencies.
 *
 * @codeCoverageIgnore
 */
class KernelsServiceProvider implements ServiceProviderInterface {
	use ExtendsConfigTrait;

	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		$this->extendConfig( $container, 'middleware', [
			'flash' => \CoffeeCode\WPEmerge\Flash\FlashMiddleware::class,
			'old_input' => \CoffeeCode\WPEmerge\Input\OldInputMiddleware::class,
			'csrf' => \CoffeeCode\WPEmerge\Csrf\CsrfMiddleware::class,
			'user.logged_in' => \CoffeeCode\WPEmerge\Middleware\UserLoggedInMiddleware::class,
			'user.logged_out' => \CoffeeCode\WPEmerge\Middleware\UserLoggedOutMiddleware::class,
			'user.can' => \CoffeeCode\WPEmerge\Middleware\UserCanMiddleware::class,
		] );

		$this->extendConfig( $container, 'middleware_groups', [
			'wpemerge' => [
				'flash',
				'old_input',
			],
			'global' => [],
			'web' => [],
			'ajax' => [],
			'admin' => [],
		] );

		$this->extendConfig( $container, 'middleware_priority', [] );

		$container[ WPEMERGE_WORDPRESS_HTTP_KERNEL_KEY ] = function ( $c ) {
			$kernel = new HttpKernel(
				$c,
				$c[ WPEMERGE_APPLICATION_GENERIC_FACTORY_KEY ],
				$c[ WPEMERGE_HELPERS_HANDLER_FACTORY_KEY ],
				$c[ WPEMERGE_RESPONSE_SERVICE_KEY ],
				$c[ WPEMERGE_REQUEST_KEY ],
				$c[ WPEMERGE_ROUTING_ROUTER_KEY ],
				$c[ WPEMERGE_VIEW_SERVICE_KEY ],
				$c[ WPEMERGE_EXCEPTIONS_ERROR_HANDLER_KEY ]
			);

			$kernel->setMiddleware( $c[ WPEMERGE_CONFIG_KEY ]['middleware'] );
			$kernel->setMiddlewareGroups( $c[ WPEMERGE_CONFIG_KEY ]['middleware_groups'] );
			$kernel->setMiddlewarePriority( $c[ WPEMERGE_CONFIG_KEY ]['middleware_priority'] );

			return $kernel;
		};

		$app = $container[ WPEMERGE_APPLICATION_KEY ];

		$app->alias( 'run', function () use ( $app ) {
			$kernel = $app->resolve( WPEMERGE_WORDPRESS_HTTP_KERNEL_KEY );
			return call_user_func_array( [$kernel, 'run'], func_get_args() );
		} );
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		// Nothing to bootstrap.
	}
}
