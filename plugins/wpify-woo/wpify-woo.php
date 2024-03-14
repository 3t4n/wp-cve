<?php // phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

/*
 * Plugin Name:          WPify Woo
 * Description:          Custom functionality for WooCommerce
 * Version:              4.0.9
 * Requires PHP:         8.0.0
 * Requires at least:    6.2
 * Author:               WPify s.r.o.
 * Author URI:           https://www.wpify.io/
 * License:              GPLv2 or later
 * License URI:          https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:          wpify-woo
 * Domain Path:          /languages
 * WC requires at least: 7.0
 * WC tested up to:      8.2
*/

use Automattic\WooCommerce\Utilities\FeaturesUtil;
use WpifyWoo\Plugin;
use WpifyWooDeps\DI;
use WpifyWooDeps\DI\Definition\Helper\AutowireDefinitionHelper;
use WpifyWooDeps\Wpify\Core\Container;
use WpifyWooDeps\Wpify\CustomFields\CustomFields;

if ( ! defined( 'WPIFY_WOO_MIN_PHP_VERSION' ) ) {
	define( 'WPIFY_WOO_MIN_PHP_VERSION', '8.0.0' );
}

// Compatibility with plugins using woo-core
add_filter( 'wpify_woo_core_settings_initialized', '__return_true' );

/**
 * Singleton instance function. We will not use a global at all as that defeats the purpose of a singleton
 * and is a bad design overall
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @return WpifyWoo\Plugin
 * @throws Exception
 */
function wpify_woo(): Plugin {
	return wpify_woo_container()->get( Plugin::class );
}

/**
 * This container singleton enables you to setup unit testing by passing an environment file to map classes in Dice
 *
 * @param string $env
 *
 * @return DI\Container
 * @throws Exception
 */
function wpify_woo_container(): DI\Container {
	static $container;

	if ( empty( $container ) ) {
		$wpify_container = Container::getInstance();
		$container       = $wpify_container->get_container( 'wpify_woo' );

		if ( ! $container ) {
			$container = $wpify_container->add_container(
				'wpify_woo',
				array(
					Plugin::class          => new AutowireDefinitionHelper( Plugin::class ),
					CustomFields::class    => ( new AutowireDefinitionHelper() )
						->constructor( plugins_url( dirname( plugin_basename( __FILE__ ) ) . '/deps/wpify/custom-fields' ) ),
				)
			);
		}
	}

	return $container;
}

/**
 * Init function shortcut
 */
function wpify_woo_init() {
	wpify_woo()->init();
}

/**
 * Activate function shortcut
 */
function wpify_woo_activate( $network_wide ) {
	register_uninstall_hook( __FILE__, 'wpify_woo_uninstall' );
	wpify_woo()->init();
	wpify_woo()->activate( $network_wide );
}

/**
 * Deactivate function shortcut
 */
function wpify_woo_deactivate( $network_wide ) {
	wpify_woo()->deactivate( $network_wide );
}

/**
 * Uninstall function shortcut
 */
function wpify_woo_uninstall() {
	wpify_woo()->uninstall();
}

/**
 * Error for older php
 */
function wpify_woo_php_upgrade_notice() {
	$info = get_plugin_data( __FILE__ ); ?>
	<div class="error notice">
		<p>
			<?php printf( _e( 'Opps! %1$s requires a minimum PHP version of %2$s. Your current version is: %3$s. Please contact your host to upgrade.', 'dogsie' ), $info['Name'], WPIFY_WOO_MIN_PHP_VERSION, PHP_VERSION ); ?>
		</p>
	</div>
	<?php
}

/**
 * Error if vendors autoload is missing
 */
function wpify_woo_php_vendor_missing() {
	$info = get_plugin_data( __FILE__ );
	?>
	<div class="error notice">
		<p><?php printf( __( 'Opps! %s is corrupted it seems, please re-install the plugin.', 'wpify-woo' ), $info['Name'] ); ?></p>
	</div>
	<?php
}

/**
 * WooCommerce not active notice
 */
function wpify_woo_woocommerce_not_active() {
	$info = get_plugin_data( __FILE__ );
	?>
	<div class="error notice">
		<p><?php printf( __( 'Plugin %s requires WooCommerce. Please install and activate it first.', 'wpify-woo' ), $info['Name'] ); ?></p>
	</div>
	<?php
}

/**
 * Load plugin textdomain.
 */
add_action( 'init', 'wpify_woo_load_textdomain' );
function wpify_woo_load_textdomain() {
	load_plugin_textdomain( 'wpify-woo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Check if required plugin is active
 */
function wpify_woo_plugin_is_active( $plugin ) {
	if ( is_multisite() ) {
		$plugins = get_site_option( 'active_sitewide_plugins' );
		if ( isset( $plugins[ $plugin ] ) ) {
			return true;
		}
	}

	if ( in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ) {
		return true;
	}

	return false;
}

/**
 * We want to use a fairly modern php version, feel free to increase the minimum requirement
 */
if ( version_compare( PHP_VERSION, WPIFY_WOO_MIN_PHP_VERSION ) < 0 ) {
	add_action( 'admin_notices', 'wpify_woo_php_upgrade_notice' );
} elseif ( ! wpify_woo_plugin_is_active( 'woocommerce/woocommerce.php' ) ) {
	add_action( 'admin_notices', 'wpify_woo_woocommerce_not_active' );
} else {
	if ( file_exists( __DIR__ . '/deps/scoper-autoload.php' ) ) {
		include_once __DIR__ . '/deps/scoper-autoload.php';
		include_once __DIR__ . '/lib/PacketeraSDK/vendor/autoload.php';
		include_once __DIR__ . '/vendor/autoload.php';

		add_action( 'plugins_loaded', 'wpify_woo_init', 11 );
		register_activation_hook( __FILE__, 'wpify_woo_activate' );
		register_deactivation_hook( __FILE__, 'wpify_woo_deactivate' );
	} else {
		add_action( 'admin_notices', 'wpify_woo_php_vendor_missing' );
	}
}

add_action( 'before_woocommerce_init', function () {
	if ( class_exists( FeaturesUtil::class ) ) {
		FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__ );
	}
} );
