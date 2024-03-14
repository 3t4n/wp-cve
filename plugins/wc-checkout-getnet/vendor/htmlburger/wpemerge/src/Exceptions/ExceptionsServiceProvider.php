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

namespace CoffeeCode\WPEmerge\Exceptions;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use CoffeeCode\WPEmerge\Exceptions\Whoops\DebugDataProvider;
use CoffeeCode\WPEmerge\ServiceProviders\ExtendsConfigTrait;
use CoffeeCode\WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Provide exceptions dependencies.
 *
 * @codeCoverageIgnore
 */
class ExceptionsServiceProvider implements ServiceProviderInterface {
	use ExtendsConfigTrait;

	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		$this->extendConfig( $container, 'debug', [
			'enable' => true,
			'pretty_errors' => true,
		] );

		$container[ DebugDataProvider::class ] = function ( $container ) {
			return new DebugDataProvider( $container );
		};

		$container[ PrettyPageHandler::class ] = function ( $container ) {
			$handler = new PrettyPageHandler();
			$handler->addResourcePath( implode( DIRECTORY_SEPARATOR, [WPEMERGE_DIR, 'src', 'Exceptions', 'Whoops'] ) );

			$handler->addDataTableCallback( 'WP Emerge: Route', function ( $inspector ) use ( $container ) {
				return $container[ DebugDataProvider::class ]->route( $inspector );
			} );

			return $handler;
		};

		$container[ Run::class ] = function ( $container ) {
			if ( ! class_exists( Run::class ) ) {
				return null;
			}

			$run = new Run();
			$run->allowQuit( false );

			$handler = $container[ PrettyPageHandler::class ];

			if ( $handler ) {
				$run->pushHandler( $handler );
			}

			return $run;
		};

		$container[ WPEMERGE_EXCEPTIONS_ERROR_HANDLER_KEY ] = function ( $container ) {
			$debug = $container[ WPEMERGE_CONFIG_KEY ]['debug'];
			$whoops = $debug['pretty_errors'] ? $container[ Run::class ] : null;
			return new ErrorHandler( $container[ WPEMERGE_RESPONSE_SERVICE_KEY ], $whoops, $debug['enable'] );
		};

		$container[ WPEMERGE_EXCEPTIONS_CONFIGURATION_ERROR_HANDLER_KEY ] = function ( $container ) {
			$debug = $container[ WPEMERGE_CONFIG_KEY ]['debug'];
			$whoops = $debug['pretty_errors'] ? $container[ Run::class ] : null;
			return new ErrorHandler( $container[ WPEMERGE_RESPONSE_SERVICE_KEY ], $whoops, $debug['enable'] );
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		// Nothing to bootstrap.
	}
}
