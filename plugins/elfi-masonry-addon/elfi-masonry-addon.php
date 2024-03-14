<?php
/**
 * Plugin Name: Elfi Masonry Addon
 * Description: Creating showcases for portfolios, posts and products with Elementor
 * Plugin URI:  https://wordpress.org/plugins/elfi-masonry-addon/
 * Version:     1.4.0
 * Author:      Sharabindu
 * Author URI:  http://sharabindu.com/plugins/elfi
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       elfi-masonry-addon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC'))
{
    die;
}

/**
 *Include plugin.php
 *Check Qr composer Pro Version is enable.
 * Then Deactive Pro version and activate Lite version
 */

include_once(ABSPATH.'wp-admin/includes/plugin.php');
if( is_plugin_active('elfi-masonry-addon-pro/elfi-masonry-addon-pro.php') ){
     add_action('update_option_active_plugins', 'elf_deactivate_version');
}
function elf_deactivate_version(){
   deactivate_plugins('elfi-masonry-addon-pro/elfi-masonry-addon-pro.php');
}


/**
 * Currently plugin version.
 * Start at version 1.4.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('ELFI_VERSION_LIGHT', '1.4.0');

/**
 * plugin define directory.
 *
 */

define('ELFI_DIR_LIGHT', plugin_dir_url(__DIR__));

/**
 * define plugin path.
 *
 */
define('ELFI_PATH_LIGHT', plugin_dir_path(__FILE__));

/**
 * define plugin url.
 *
 */
define('ELFI_URL_LIGHT', plugin_dir_url(__FILE__));
/**
 *  define plugin basename.
 *
 */
define('ELFI_BASENAME_LIGHT', plugin_basename(__FILE__));

if (!defined('ELFI_PLUGIN_ID'))
{
    define('ELFI_PLUGIN_ID', 'elfi_settings'); // unique prefix (same plugin ID name for 'lite' and 'pro')
    
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-elfi-activator.php
 */
function elfi_light_activator()
{
    require_once ELFI_PATH_LIGHT . 'includes/Class/class-elfi-activator.php';
    elfi_light_activator::elfi_light_activate();

    add_option('elfi_plugin_do_activation_redirect', true);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-elfi-deactivator.php
 */
function elfi_light_deativator()
{
    require_once ELFI_PATH_LIGHT . 'includes/Class/class-elfi-deactivator.php';
    Elfi_Light_Deactivator::elfi_light_deactivate();
}

register_activation_hook(__FILE__, 'elfi_light_activator');
register_deactivation_hook(__FILE__, 'elfi_light_deativator');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/Class/class-elfi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.4.0
 */
function elfi_light_run()
{

    $plugin = new Elfi_Light();
    $plugin->elfi_light_get_run();

}
elfi_light_run();
