<?php

/**
 *
 * @wordpress-plugin
 * Plugin Name:       Display Eventbrite Events
 * Plugin URI:        https://fullworksplugins.com/products/widget-for-eventbrite/
 * Description:       Easily display Eventbrite events on your WordPress site
 * Version:           5.5.7
 * Requires at least: 4.9
 * Requires PHP:      5.6
 * Author:            Fullworks
 * Author URI:        https://fullworksplugins.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       widget-for-eventbrite-api
 * Domain Path:       /languages
 *
 *
 *
 * Acknowledgements:
 * Lots of code and coding ideas for the original widget have been from the GPL licenced Recent Posts Widget Extended by Satrya https://www.theme-junkie.com/
 *
 * This plugin used to depend on  https://wordpress.org/plugins/eventbrite-api/ by Automattic
 * However Automattic stopped supporting and maintaining it in July 2018, so I have taken onboard many GPL licenced classes and functions
 * directly within this code line, whilst many changes have been made some code originates from Automattic
 *
 */
namespace WidgetForEventbriteAPI;

// If this file is called directly, abort.
use  Freemius ;
use  WidgetForEventbriteAPI\Admin\Admin ;
use  WidgetForEventbriteAPI\Includes\Core ;
use  WidgetForEventbriteAPI\Includes\Freemius_Config ;
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( !function_exists( 'WidgetForEventbriteAPI\\run_wfea' ) ) {
    // define some useful constants
    define( 'WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    define( 'WIDGET_FOR_EVENTBRITE_API_PLUGIN_NAME', basename( WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR ) );
    define( 'WIDGET_FOR_EVENTBRITE_API_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    define( 'WIDGET_FOR_EVENTBRITE_API_PLUGINS_TOP_DIR', plugin_dir_path( __DIR__ ) );
    define( 'WIDGET_FOR_EVENTBRITE_PLUGIN_VERSION', '5.5.7' );
    // Include the plugin autoloader, so we can dynamically include the classes.
    require_once WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . 'includes/autoloader.php';
    // include legacy functions for backwards compatability
    require_once WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . 'includes/legacy-functions.php';
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-activator.php
     */
    register_activation_hook( __FILE__, array( '\\WidgetForEventbriteAPI\\Includes\\Activator', 'activate' ) );
    register_deactivation_hook( __FILE__, array( '\\WidgetForEventbriteAPI\\Includes\\Deactivator', 'deactivate' ) );
    add_action( 'setup_theme', 'WidgetForEventbriteAPI\\run_wfea' );
    function run_wfea()
    {
        global  $wfea_fs ;
        // run the plugin now
        $plugin = new Core( $wfea_fs );
        $plugin->run();
    }
    
    function run_freemius()
    {
        /**
         * The core plugin class that is used to define internationalization,
         * admin-specific hooks, and public-facing site hooks.
         */
        /**
         *  Load freemius SDK
         */
        $freemius = new Freemius_Config();
        $freemius = $freemius->init();
        // Signal that SDK was initiated.
        do_action( 'wfea_fs_loaded' );
        /**
         * The code that runs during plugin uninstall.
         * This action is documented in includes/class-uninstall.php
         * * use freemius hook
         *
         * @var Freemius $freemius freemius SDK.
         */
        $freemius->add_action( 'after_uninstall', array( '\\WidgetForEventbriteAPI\\Includes\\Uninstall', 'uninstall' ) );
    }
    
    run_freemius();
} else {
    global  $wfea_fs ;
    
    if ( $wfea_fs && !$wfea_fs->is_premium() ) {
        $wfea_fs->set_basename( true, __FILE__ );
    } else {
        add_action( 'current_screen', function () {
            if ( Admin::can_display_admin_notice() ) {
                add_action( 'admin_notices', function () {
                    ?>
                    <div class="notice notice-error">
                        <p><?php 
                    echo  esc_html( 'You already have a pro version of Display Eventbrite Events (Premium) installed, please check versions and deactivate and delete one of them. The correct one should be in the folder wp-content/freemius-premium - this one you are trying is in folder wp-content/plugins/', 'display-eventbrite-events' ) . esc_html( basename( plugin_dir_path( __FILE__ ) ) ) ;
                    ?>&nbsp;&nbsp;<a href="<?php 
                    esc_url( admin_url( 'wp-admin/plugins.php' ) );
                    ?>"><?php 
                    esc_html_e( 'Manage Plugins Here', 'widget-for-eventbrite-api' );
                    ?></a></p>
                    </div>
					<?php 
                } );
            }
        } );
    }
    
    return;
}
