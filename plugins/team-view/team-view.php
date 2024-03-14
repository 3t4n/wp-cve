<?php
/**
 * Plugin Name: Team View
 * Plugin URI: https://wordpress.org/plugins/team-view/
 * Description: Simple and easy, responsive and mobile friendly plugin to display team members profile.
 * Version: 1.1.2
 * Author: WEN Themes
 * Author URI: https://wenthemes.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: team-view
 * Domain Path: /languages
 *
 * @package Team_View
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'TEAM_VIEW_BASENAME', basename( dirname( __FILE__ ) ) );
define( 'TEAM_VIEW_VERSION', '1.1.2' );
define( 'TEAM_VIEW_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'TEAM_VIEW_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'TEAM_VIEW_LIB_DIR', TEAM_VIEW_DIR . '/lib' );

// Load helpers.
require_once TEAM_VIEW_DIR . '/includes/helpers.php';

// Load widgets.
require_once TEAM_VIEW_DIR . '/includes/widgets.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-team-view-activator.php
 */
function activate_team_view() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-team-view-activator.php';
	Team_View_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-team-view-deactivator.php
 */
function deactivate_team_view() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-team-view-deactivator.php';
	Team_View_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_team_view' );
register_deactivation_hook( __FILE__, 'deactivate_team_view' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-team-view.php';

// Load CMB library.
if ( ! class_exists( 'CMB2' ) ) {
	require_once TEAM_VIEW_LIB_DIR . '/cmb2/init.php';
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_team_view() {

	$plugin = new Team_View();
	$plugin->run();

}

run_team_view();
