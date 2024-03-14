<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.wpgoaltracker.com/
 * @since             1.0.1
 * @package           Wp_Goal_Tracker_Ga
 *
 * @wordpress-plugin
 * Plugin Name:       Goal Tracker
 * Plugin URI:        https://www.wpgoaltracker.com/goal-tracker-ga
 * Description:       Custom Event Tracking for Google Analytics GA4
 * Version:           1.1.3
 * Author:            pinewise
 * Author URI:        https://www.wpgoaltracker.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-goal-tracker-ga
 * Domain Path:       /languages
 *
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( !function_exists( 'gtg_fs' ) ) {
    // Create a helper function for easy SDK access.
    function gtg_fs()
    {
        global  $gtg_fs ;
        
        if ( !isset( $gtg_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $gtg_fs = fs_dynamic_init( array(
                'id'               => '11325',
                'slug'             => 'goal-tracker-ga',
                'type'             => 'plugin',
                'public_key'       => 'pk_3af5dde41d9e715ced0557669259d',
                'is_premium'       => false,
                'premium_suffix'   => 'Personal',
                'has_addons'       => false,
                'has_paid_plans'   => true,
                'trial'            => array(
                'days'               => 7,
                'is_require_payment' => false,
            ),
                'is_org_compliant' => true,
                'menu'             => array(
                'slug' => 'wp-goal-tracker-ga',
            ),
                'is_live'          => true,
            ) );
        }
        
        return $gtg_fs;
    }
    
    // Init Freemius.
    gtg_fs();
    // Signal that SDK was initiated.
    do_action( 'gtg_fs_loaded' );
}

function gtg_fs_custom_icon()
{
    return dirname( __FILE__ ) . '/public/images/icon-256x256.png';
}

gtg_fs()->add_filter( 'plugin_icon', 'gtg_fs_custom_icon' );
// gtg_fs()->add_action('after_uninstall', 'gtg_fs_uninstall_cleanup');
/**
 * Current plugin path.
 * Current plugin url.
 * Current plugin version.
 *
 * Rename these constants for your plugin
 * Update version as you release new versions.
 */
define( 'WP_CUSTOM_EVENTS_TRACKER_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_CUSTOM_EVENTS_TRACKER_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_CUSTOM_EVENTS_TRACKER_VERSION', '1.1.3' );
define( 'WP_CUSTOM_EVENTS_TRACKER_DB_VERSION', '1.1.3' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-goal-tracker-ga-activator.php
 */
function activate_wp_goal_tracker_ga()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-goal-tracker-ga-activator.php';
    Wp_Goal_Tracker_Ga_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-goal-tracker-ga-deactivator.php
 */
function deactivate_wp_goal_tracker_ga()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-goal-tracker-ga-deactivator.php';
    Wp_Goal_Tracker_Ga_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_goal_tracker_ga' );
register_deactivation_hook( __FILE__, 'deactivate_wp_goal_tracker_ga' );
// Display settings link next to plugin listing on plugins page
add_filter(
    "plugin_action_links_" . plugin_basename( __FILE__ ),
    'gtga_plugin_add_settings_link',
    10,
    1
);
// Callback for adding settings link next to listing on plugins page
function gtga_plugin_add_settings_link( $links )
{
    $url = admin_url( 'admin.php' ) . '?page=wp-goal-tracker-ga#/settings';
    $settings_link = '<a href="' . $url . '">Settings</a>';
    array_unshift( $links, $settings_link );
    return $links;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-goal-tracker-ga.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_goal_tracker_ga()
{
    $plugin = new Wp_Goal_Tracker_Ga();
    $plugin->run();
}

run_wp_goal_tracker_ga();