<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://boomdevs.com
 * @since             1.0.0
 * @package           Wp_Bnav
 *
 * @wordpress-plugin
 * Plugin Name:       WP Mobile Bottom Menu
 * Plugin URI:        https://boomdevs.com/products/wordpress-bottom-bar-navigation
 * Description:       Smooth Navigation for Mobile. Create an Eye-Catching Sticky Bottom Menu with Limitless Customization Options.
 * Version:           1.2.1
 * Author:            BOOM DEVS
 * Author URI:        https://boomdevs.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-bnav
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require __DIR__ . '/vendor/autoload.php';



/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_mobile_bottom_menu_for_wp() {

    if ( ! class_exists( 'Appsero\Client' ) ) {
      require_once __DIR__ . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client( '0e0ee063-4565-4af4-bcb4-94da768d98b1', 'WP Mobile Bottom Menu', __FILE__ );

    // Active insights
    $client->insights()->init();

}

appsero_init_tracker_mobile_bottom_menu_for_wp();



/**
 * Plugin global information..
 */
define( 'WP_BNAV_VERSION', '1.2.1' );
define( 'WP_BNAV_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_BNAV_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_BNAV_SLUG', 'wp-bnav' );
define( 'WP_BNAV_SHORT_NAME', 'WP BNav' );
define( 'WP_BNAV_FULL_NAME', 'Bottom Bar Navigation For WordPress' );
define( 'WP_BNAV_BASE_NAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-bnav-activator.php
 */
function activate_wp_bnav() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-bnav-activator.php';
	Wp_Bnav_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-bnav-deactivator.php
 */
function deactivate_wp_bnav() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-bnav-deactivator.php';
	Wp_Bnav_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_bnav' );
register_deactivation_hook( __FILE__, 'deactivate_wp_bnav' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-bnav.php';

do_action( 'wp_bnav/loaded' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_bnav() {
	$plugin = new Wp_Bnav();
	$plugin->run();
}
add_action( 'plugins_loaded', 'run_wp_bnav', 2 );

// Perform wishlist count
function bnav_wishlist_get_items_count() { ob_start(); ?>
<span class="bnav_wishlist_counter">
    <?php echo esc_html( yith_wcwl_count_all_products() ); ?>
</span>
<?php return ob_get_clean();
}