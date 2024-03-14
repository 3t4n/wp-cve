<?php

/**
 * @link              
 * @since             1.0.0
 * @package           Wpgsi
 *
 * @wordpress-plugin
 * Plugin Name:       Spreadsheet Integration – Google sheet Integration, Sync & Display.
 * Plugin URI:        https://wordpress.org/plugins/wpgsi
 * Description:       Spreadsheet Integration, Connects WordPress events and most popular plugin with  Google Sheets via API. 
 * Version:           3.7.9
 * Author:            javmah
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpgsi
 * Domain Path:       /languages
 */
# If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
# freemius Starts

if ( function_exists( 'wpgsi_fs' ) ) {
    wpgsi_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'wpgsi_fs' ) ) {
        // ... Freemius integration snippet ...
        // Create a helper function for easy SDK access.
        function wpgsi_fs()
        {
            global  $wpgsi_fs ;
            
            if ( !isset( $wpgsi_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/includes/freemius/start.php';
                $wpgsi_fs = fs_dynamic_init( array(
                    'id'             => '5870',
                    'slug'           => 'wpgsi',
                    'premium_slug'   => 'wpgsi-professional',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_e966b3152512a4564903a23c4be4f',
                    'is_premium'     => false,
                    'premium_suffix' => 'professional',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'       => 'wpgsi',
                    'first-path' => 'admin.php?page=wpgsi-settings',
                    'support'    => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $wpgsi_fs;
        }
        
        // Init Freemius.
        wpgsi_fs();
        // Signal that SDK was initiated.
        do_action( 'wpgsi_fs_loaded' );
    }
    
    /**
     * test purpose s
     */
    add_action( 'activated_plugin', 'save_error_wpgsi' );
    function save_error_wpgsi()
    {
        file_put_contents( dirname( __FILE__ ) . '/error_activation.txt', ob_get_contents() );
    }
    
    /**
     * Currently plugin version.
     * Start at version 1.0.0 and use SemVer - https://semver.org
     * Rename this for your plugin and update it as you release new versions.
     */
    define( 'WPGSI_VERSION', '3.7.9' );
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-wpgsi-activator.php
     */
    function activate_wpgsi()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpgsi-activator.php';
        Wpgsi_Activator::activate();
    }
    
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-wpgsi-deactivator.php
     */
    function deactivate_wpgsi()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpgsi-deactivator.php';
        Wpgsi_Deactivator::deactivate();
    }
    
    # Activation & Deactivation Hooks init
    register_activation_hook( __FILE__, 'activate_wpgsi' );
    register_deactivation_hook( __FILE__, 'deactivate_wpgsi' );
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-wpgsi.php';
    /**
     * Begins execution of the plugin. Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does not affect the page life cycle.
     * @since    1.0.0
     */
    function run_wpgsi()
    {
        $plugin = new Wpgsi();
        $plugin->run();
    }
    
    # 786
    run_wpgsi();
}

# 29 Apr 2023
# ------------------------------------------------------------.
# Hello, Friend How are you doing? i am doing fine.
# I know  Golang, Python, PHP, Javascript, HTML & CSS.
# I am from Dhaka, Bangladesh.
# You can contact me with this email : jaedmah@gmail.com
# Thank you & Kindest regards -jav