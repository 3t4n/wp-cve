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
use CoffeeCode\WPEmerge\Controllers\ControllersServiceProvider;
use CoffeeCode\WPEmerge\Csrf\CsrfServiceProvider;
use CoffeeCode\WPEmerge\Exceptions\ConfigurationException;
use CoffeeCode\WPEmerge\Exceptions\ExceptionsServiceProvider;
use CoffeeCode\WPEmerge\Flash\FlashServiceProvider;
use CoffeeCode\WPEmerge\Input\OldInputServiceProvider;
use CoffeeCode\WPEmerge\Kernels\KernelsServiceProvider;
use CoffeeCode\WPEmerge\Middleware\MiddlewareServiceProvider;
use CoffeeCode\WPEmerge\Requests\RequestsServiceProvider;
use CoffeeCode\WPEmerge\Responses\ResponsesServiceProvider;
use CoffeeCode\WPEmerge\Routing\RoutingServiceProvider;
use CoffeeCode\WPEmerge\ServiceProviders\ServiceProviderInterface;
use CoffeeCode\WPEmerge\Support\Arr;
use CoffeeCode\WPEmerge\View\ViewServiceProvider;

/**
 * Load service providers.
 */
trait LoadsServiceProvidersTrait {
	/**
	 * Array of default service providers.
	 *
	 * @var string[]
	 */
	protected $service_providers = [
		ApplicationServiceProvider::class,
		KernelsServiceProvider::class,
		ExceptionsServiceProvider::class,
		RequestsServiceProvider::class,
		ResponsesServiceProvider::class,
		RoutingServiceProvider::class,
		ViewServiceProvider::class,
		ControllersServiceProvider::class,
		MiddlewareServiceProvider::class,
		CsrfServiceProvider::class,
		FlashServiceProvider::class,
		OldInputServiceProvider::class,
	];

	/**
	 * Register and bootstrap all service providers.
	 *
	 * @codeCoverageIgnore
	 * @param  Container $container
	 * @return void
	 */
	protected function loadServiceProviders( Container $container ) {
		$container[ WPEMERGE_SERVICE_PROVIDERS_KEY ] = array_merge(
			$this->service_providers,
			Arr::get( $container[ WPEMERGE_CONFIG_KEY ], 'providers', [] )
		);

		$service_providers = array_map( function ( $service_provider ) use ( $container ) {
			if ( ! is_subclass_of( $service_provider, ServiceProviderInterface::class ) ) {
				throw new ConfigurationException(
					'The following class does not implement ' .
					ServiceProviderInterface::class . ': ' . $service_provider
				);
			}

			// Provide container access to the service provider instance
			// so bootstrap hooks can be unhooked e.g.:
			// remove_action( 'some_action', [\App::resolve( SomeServiceProvider::class ), 'methodAddedToAction'] );
			$container[ $service_provider ] = new $service_provider();

			return $container[ $service_provider ];
		}, $container[ WPEMERGE_SERVICE_PROVIDERS_KEY ] );

		$this->registerServiceProviders( $service_providers, $container );
		$this->bootstrapServiceProviders( $service_providers, $container );
	}

	/**
	 * Register all service providers.
	 *
	 * @param  ServiceProviderInterface[] $service_providers
	 * @param  Container                  $container
	 * @return void
	 */
	protected function registerServiceProviders( $service_providers, Container $container ) {
		foreach ( $service_providers as $provider ) {
			$provider->register( $container );
		}
	}

	/**
	 * Bootstrap all service providers.
	 *
	 * @param  ServiceProviderInterface[] $service_providers
	 * @param  Container                  $container
	 * @return void
	 */
	protected function bootstrapServiceProviders( $service_providers, Container $container ) {
		foreach ( $service_providers as $provider ) {
			$provider->bootstrap( $container );
		}
	}
}
