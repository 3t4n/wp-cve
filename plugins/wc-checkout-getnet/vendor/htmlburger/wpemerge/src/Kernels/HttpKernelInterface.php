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

use Closure;
use CoffeeCode\Psr\Http\Message\ResponseInterface;
use CoffeeCode\WPEmerge\Helpers\Handler;
use CoffeeCode\WPEmerge\Middleware\HasMiddlewareDefinitionsInterface;
use CoffeeCode\WPEmerge\Requests\RequestInterface;

/**
 * Describes how a request is handled.
 */
interface HttpKernelInterface extends HasMiddlewareDefinitionsInterface {
	/**
	 * Bootstrap the kernel.
	 *
	 * @return void
	 */
	public function bootstrap();

	/**
	 * Run a response pipeline for the given request.
	 *
	 * @param  RequestInterface       $request
	 * @param  string[]               $middleware
	 * @param  string|Closure|Handler $handler
	 * @param  array                  $arguments
	 * @return ResponseInterface
	 */
	public function run( RequestInterface $request, $middleware, $handler, $arguments = [] );

	/**
	 * Return a response for the given request.
	 *
	 * @param  RequestInterface       $request
	 * @param  array                  $arguments
	 * @return ResponseInterface|null
	 */
	public function handle( RequestInterface $request, $arguments = [] );
}
