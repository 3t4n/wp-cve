<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              test.com
 * @since             1.0.0
 * @package           Blossom_Recipe_Maker
 *
 * @wordpress-plugin
 * Plugin Name:       Blossom Recipe Maker
 * Plugin URI:        test.com
 * Description:       Blossom Recipe Maker is a free Recipe Plugin to create recipes for any food blog.
 * Version:           1.0.10
 * Author:            blossomthemes
 * Author URI:        https://blossomthemes.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       blossom-recipe-maker
 * Domain Path:       /languages
 * Tested up to: 6.2
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
define( 'BLOSSOM_RECIPE_MAKER_BASE_PATH', dirname( __FILE__ ) );
define( 'BLOSSOM_RECIPE_MAKER_VERSION', '1.0.10' );
define( 'BLOSSOM_RECIPE_MAKER_URL', plugins_url( '', __FILE__ ) );
define( 'BLOSSOM_RECIPE_MAKER_TEMPLATE_PATH', BLOSSOM_RECIPE_MAKER_BASE_PATH . '/includes/templates' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-blossom-recipe-activator.php
 */
function activate_blossom_recipe_maker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blossom-recipe-activator.php';
	Blossom_Recipe_Maker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-blossom-recipe-deactivator.php
 */
function deactivate_blossom_recipe_maker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blossom-recipe-deactivator.php';
	Blossom_Recipe_Maker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_blossom_recipe_maker' );
register_deactivation_hook( __FILE__, 'deactivate_blossom_recipe_maker' );

/**
 * The class that represents the meta box that will dispaly the navigation tabs and each of the
 * fields for the meta box.
 */
require_once plugin_dir_path( __FILE__ ) . 'admin/class-blossom-recipe-meta-box.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-blossom-recipe.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_blossom_recipe_maker() {

	$plugin = new Blossom_Recipe_Maker();
	$plugin->run();

}
run_blossom_recipe_maker();
