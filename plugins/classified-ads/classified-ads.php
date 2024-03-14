<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpdirectorykit.com
 * @since             1.0.1
 * @package           Classified_Ads
 *
 * @wordpress-plugin
 * Plugin Name:       Classified Ads
 * Plugin URI:        https://wordpress.org/plugins/classified-ads/
 * Description:       Build your Classified Ads Directory Portal based on Wp Directory Kit Plugin
 * Version:           1.0.2
 * Author:            wpdirectorykit.com
 * Author URI:        https://wpdirectorykit.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       classified-ads
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
define( 'CLASSIFIED_ADS_VERSION', '1.0.0' );
define( 'CLASSIFIED_ADS_NAME', 'classified-ads' );
define( 'CLASSIFIED_ADS_PATH', plugin_dir_path( __FILE__ ) );
define( 'CLASSIFIED_ADS_URL', plugin_dir_url( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-classified-ads-activator.php
 */
function activate_classified_ads() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-classified-ads-activator.php';
	Classified_Ads_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-classified-ads-deactivator.php
 */
function deactivate_classified_ads() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-classified-ads-deactivator.php';
	Classified_Ads_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_classified_ads' );
register_deactivation_hook( __FILE__, 'deactivate_classified_ads' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-classified-ads.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_classified_ads() {

	$plugin = new Classified_Ads();
	$plugin->run();

}
run_classified_ads();


if ( ! function_exists('classified_ads_get_tgmpa_link'))
{
    function classified_ads_get_tgmpa_link()
    {
        if(file_exists(get_template_directory().'/includes/tgm_pa/class-tgm-plugin-activation.php') || file_exists(get_template_directory().'/tgm_pa/class-tgm-plugin-activation.php')) {
            return get_admin_url() . "themes.php?page=tgmpa-install-plugins";
        } else {
            return get_admin_url() . "plugins.php?page=tgmpa-install-plugins";
        }

        return false;
    }
}
