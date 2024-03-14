<?php
// removeIf(pro)

/**
 * The plugin bootstrap file
 *
 * @link              http://hurrytimer.com
 * @since             1.0.0
 * @package           Hurrytimer
 *
 * @wordpress-plugin
 * Plugin Name:       HurryTimer
 * Plugin URI:        https://hurrytimer.com
 * Description:       A Scarcity and Urgency Countdown Timer for WordPress & WooCommerce with recurring and evergreen mode.
 * Version:           2.9.2
 * Author:            Nabil Lemsieh
 * Author URI:        https://hurrytimer.com
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl.html
 * Text Domain:       hurrytimer
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 8.5
 */


// endRemoveIf(pro)

// If this file is called directly, abort.

if (!defined('WPINC')) {
    die;
}

if(!(defined( 'WP_CLI' ) && WP_CLI) && function_exists('\is_plugin_active') && function_exists('\deactivate_plugins')):

// removeIf(pro)
if ( defined( 'HURRYT_IS_PRO' ) ) {
	if ( is_plugin_active( 'hurrytimer-pro/hurrytimer.php' ) ) {
		deactivate_plugins( 'hurrytimer-pro/hurrytimer.php' );
	}

	wp_redirect( $_SERVER['REQUEST_URI'] );
	exit;
}
// endRemoveIf(pro)

endif;

define('HURRYT_VERSION', ' 2.9.2');

define('HURRYT_DIR', plugin_dir_path(__FILE__));
define('HURRYT_URL', plugin_dir_url(__FILE__));
define('HURRYT_BASENAME', plugin_basename(__FILE__));
define('HURRYT_POST_TYPE', 'hurrytimer_countdown');

// Declare compatibility with custom order tables for WooCommerce.
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

require_once __DIR__ . '/vendor/autoload.php';

register_activation_hook(__FILE__, [Hurrytimer\Installer::get_instance(), 'activate']);

add_action('plugins_loaded', function () {
    (new \Hurrytimer\Bootstrap())->run();
    do_action('hurrytimer_init');
});
