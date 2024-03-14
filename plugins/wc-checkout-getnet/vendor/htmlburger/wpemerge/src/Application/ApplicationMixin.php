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

namespace CoffeeCode\WPEmerge\Application;

use CoffeeCode\Pimple\Container;
use CoffeeCode\Psr\Http\Message\ResponseInterface;
use CoffeeCode\WPEmerge\Requests\RequestInterface;
use CoffeeCode\WPEmerge\Responses\RedirectResponse;
use CoffeeCode\WPEmerge\Routing\RouteBlueprint;
use CoffeeCode\WPEmerge\View\ViewInterface;

/**
 * Can be applied to your App class via a "@mixin" annotation for better IDE support.
 * This class is not meant to be used in any other capacity.
 *
 * @codeCoverageIgnore
 */
final class ApplicationMixin {
	/**
	 * Prevent class instantiation.
	 */
	private function __construct() {}

	// --- Methods --------------------------------------- //

	/**
	 * Get whether the application has been bootstrapped.
	 *
	 * @return boolean
	 */
	public static function isBootstrapped() {}

	/**
	 * Bootstrap the application.
	 *
	 * @param  array   $config
	 * @param  boolean $run
	 * @return void
	 */
	public static function bootstrap( $config = [], $run = true ) {}

	/**
	 * Get the IoC container instance.
	 *
	 * @codeCoverageIgnore
	 * @return Container
	 */
	public static function container() {}

	/**
	 * Set the IoC container instance.
	 *
	 * @codeCoverageIgnore
	 * @param  Container $container
	 * @return void
	 */
	public static function setContainer( $container ) {}

	/**
	 * Resolve a dependency from the IoC container.
	 *
	 * @param  string     $key
	 * @return mixed|null
	 */
	public static function resolve( $key ) {}

	// --- Aliases --------------------------------------- //

	/**
	 * Get the Application instance.
	 *
	 * @codeCoverageIgnore
	 * @return \CoffeeCode\WPEmerge\Application\Application
	 */
	public static function app() {}

	/**
	 * Get the ClosureFactory instance.
	 *
	 * @codeCoverageIgnore
	 * @return ClosureFactory
	 */
	public static function closure() {}

	/**
	 * Get the CSRF service instance.
	 *
	 * @codeCoverageIgnore
	 * @return \CoffeeCode\WPEmerge\Csrf\Csrf
	 */
	public static function csrf() {}

	/**
	 * Get the Flash service instance.
	 *
	 * @codeCoverageIgnore
	 * @return \CoffeeCode\WPEmerge\Flash\Flash
	 */
	public static function flash() {}

	/**
	 * Get the OldInput service instance.
	 *
	 * @codeCoverageIgnore
	 * @return \CoffeeCode\WPEmerge\Input\OldInput
	 */
	public static function oldInput() {}

	/**
	 * Run a full middleware + handler pipeline independently of routes.
	 *
	 * @codeCoverageIgnore
	 * @see    \CoffeeCode\WPEmerge\Kernels\HttpKernel::run()
	 * @param  RequestInterface  $request
	 * @param  string[]          $middleware
	 * @param  string|\Closure   $handler
	 * @param  array             $arguments
	 * @return ResponseInterface
	 */
	public static function run( RequestInterface $request, $middleware, $handler, $arguments = [] ) {}

	/**
	 * Get the ResponseService instance.
	 *
	 * @codeCoverageIgnore
	 * @return \CoffeeCode\WPEmerge\Responses\ResponseService
	 */
	public static function responses() {}

	/**
	 * Create a "blank" response.
	 *
	 * @codeCoverageIgnore
	 * @see    \CoffeeCode\WPEmerge\Responses\ResponseService::response()
	 * @return ResponseInterface
	 */
	public static function response() {}

	/**
	 * Create a response with the specified string as its body.
	 *
	 * @codeCoverageIgnore
	 * @see    \CoffeeCode\WPEmerge\Responses\ResponseService::output()
	 * @param  string            $output
	 * @return ResponseInterface
	 */
	public static function output( $output ) {}

	/**
	 * Create a response with the specified data encoded as JSON as its body.
	 *
	 * @codeCoverageIgnore
	 * @see    \CoffeeCode\WPEmerge\Responses\ResponseService::json()
	 * @param  mixed             $data
	 * @return ResponseInterface
	 */
	public static function json( $data ) {}

	/**
	 * Create a redirect response.
	 *
	 * @codeCoverageIgnore
	 * @see    \CoffeeCode\WPEmerge\Responses\ResponseService::redirect()
	 * @return RedirectResponse
	 */
	public static function redirect() {}

	/**
	 * Create a response with the specified error status code.
	 *
	 * @codeCoverageIgnore
	 * @see    \CoffeeCode\WPEmerge\Responses\ResponseService::error()
	 * @param  integer           $status
	 * @return ResponseInterface
	 */
	public static function error( $status ) {}

	/**
	 * Get the ViewService instance.
	 *
	 * @codeCoverageIgnore
	 * @return \CoffeeCode\WPEmerge\View\ViewService
	 */
	public static function views() {}

	/**
	 * Create a view.
	 *
	 * @codeCoverageIgnore
	 * @see    \CoffeeCode\WPEmerge\View\ViewService::make()
	 * @param  string|string[] $views
	 * @return ViewInterface
	 */
	public static function view( $views ) {}

	/**
	 * Output child layout content.
	 *
	 * @codeCoverageIgnore
	 * @see    \CoffeeCode\WPEmerge\View\PhpViewEngine::getLayoutContent()
	 * @return void
	 */
	public static function layoutContent() {}

	/**
	 * Create a new route.
	 *
	 * @codeCoverageIgnore
	 * @return RouteBlueprint
	 */
	public static function route() {}

	/**
	 * Output the specified view.
	 *
	 * @codeCoverageIgnore
	 * @see    \CoffeeCode\WPEmerge\View\ViewService::make()
	 * @see    \CoffeeCode\WPEmerge\View\ViewInterface::toString()
	 * @param  string|string[]      $views
	 * @param  array<string, mixed> $context
	 * @return void
	 */
	public static function render( $views, $context = [] ) {}
}
