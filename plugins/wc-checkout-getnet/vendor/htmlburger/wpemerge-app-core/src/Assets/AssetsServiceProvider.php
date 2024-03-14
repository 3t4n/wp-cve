<?php
/**
 * @package   WPEmergeAppCore
 * @author    Atanas Angelov <hi@atanas.dev>
 * @copyright 2017-2020 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\WPEmergeAppCore\Assets;

use CoffeeCode\WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Provide assets dependencies.
 *
 * @codeCoverageIgnore
 */
class AssetsServiceProvider implements ServiceProviderInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		$container['wpemerge_app_core.assets.manifest'] = function( $c ) {
			return new Manifest( $c[ WPEMERGE_CONFIG_KEY ]['app_core']['path'] );
		};

		$container['wpemerge_app_core.assets.assets'] = function( $container ) {
			return new Assets(
				$container[ WPEMERGE_CONFIG_KEY ]['app_core']['path'],
				$container[ WPEMERGE_CONFIG_KEY ]['app_core']['url'],
				$container['wpemerge_app_core.config.config'],
				$container['wpemerge_app_core.assets.manifest']
			);
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		// Nothing to bootstrap.
	}
}
