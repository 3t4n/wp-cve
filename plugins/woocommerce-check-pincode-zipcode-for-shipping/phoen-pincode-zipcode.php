<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://phoeniixx.com
 * @since             1.0.0
 * @package           Phoen_Pincode_Zipcode
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Pincode / Zipcode Free Version
 * Plugin URI:        https://www.phoeniixx.com/product/woocommerce-check-pincodezipcode-for-shipping-and-cod/
 * Description:       Advance Check Pin Code is a solution that allows users to set delivery dates based on the pin codes.
 * Version:           2.0.4
 * Author:            Phoeniixx
 * Author URI:        https://phoeniixx.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       phoen-pincode-zipcode
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PHOEN_PINCODE_ZIPCODE_VERSION', '2.0.1' );
define( "PHOEN_PINCODE_ZIPCODE_PATH",plugin_dir_path( __FILE__ ));
define( "PHOEN_PINCODE_ZIPCODE_URL",plugin_dir_url( __FILE__ ));
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-phoen-pincode-zipcode-activator.php
 */
function activate_phoen_pincode_zipcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-phoen-pincode-zipcode-activator.php';
	Phoen_Pincode_Zipcode_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-phoen-pincode-zipcode-deactivator.php
 */
function deactivate_phoen_pincode_zipcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-phoen-pincode-zipcode-deactivator.php';
	Phoen_Pincode_Zipcode_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_phoen_pincode_zipcode' );
register_deactivation_hook( __FILE__, 'deactivate_phoen_pincode_zipcode' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-phoen-pincode-zipcode.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function phoeniixx_pincode_zipcode_print_notice() {
    echo '<div id="message" class="error"><p>'.__('sorry ..!! you have to install woocommerce in order to use Pincode Plugin').'</p></div>';
}
function run_phoen_pincode_zipcode() {

	$plugin = new Phoen_Pincode_Zipcode();
	$plugin->run();

}
function phoeniixx_pincode_zipcode_install(){
    if(in_array('woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins')))){
        run_phoen_pincode_zipcode();
    }else{
        add_action( 'admin_notices', 'phoeniixx_pincode_zipcode_print_notice' );
    }
}
add_action( 'plugins_loaded', 'phoeniixx_pincode_zipcode_install', 11 );
