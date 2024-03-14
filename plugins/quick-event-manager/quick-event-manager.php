<?php

/**
 * @copyright (c) 2020.
 * @author            Alan Fuller (support@fullworksplugins.com)
 * @licence           GPL V3 https://www.gnu.org/licenses/gpl-3.0.en.html
 * @link                  https://fullworksplugins.com
 *
 * This file is part of  a Fullworks plugin.
 *
 *   This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with  this plugin.  https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 *     Plugin Name: Quick Event Manager
 *
 *     Plugin URI: https://fullworksplugins.com/products/quick-event-manager/
 *     Description: A quick and easy to use Event Manager
 *     Version: 9.8.9
 *     Requires at least: 4.6
 *     Requires PHP: 5.6
 *     Author: Fullworks
 *     Author URI: https://fullworksplugins.com/
 *     Text Domain: quick-event-manager
 *     Domain Path: /languages
 *
 *     Original Author: Aerin
 *
 */
namespace Quick_Event_Manager\Plugin;

use  Quick_Event_Manager\Plugin\Control\Plugin ;
use  Quick_Event_Manager\Plugin\Control\Freemius_Config ;
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( !function_exists( 'Quick_Event_Manager\\Plugin\\run_quick_event_manager' ) ) {
    define( 'QUICK_EVENT_MANAGER_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    define( 'QUICK_EVENT_MANAGER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    define( 'QUICK_EVENT_MANAGER_PLUGIN_FILE', plugin_basename( __FILE__ ) );
    define( 'QUICK_EVENT_MANAGER_PLUGIN_NAME', 'quick-event-manager' );
    define( 'QUICK_EVENT_MANAGER_PLUGIN_VERSION', '9.8.9' );
    // Include the autoloaders so we can dynamically include the classes.
    require_once QUICK_EVENT_MANAGER_PLUGIN_DIR . 'control/autoloader.php';
    require_once QUICK_EVENT_MANAGER_PLUGIN_DIR . 'vendor/autoload.php';
    function run_quick_event_manager()
    {
        $freemius = new Freemius_Config();
        $freemius = $freemius->init();
        // Signal that SDK was initiated.
        do_action( 'quick_event_manager_fs_loaded' );
        register_activation_hook( __FILE__, array( '\\Quick_Event_Manager\\Plugin\\Control\\Activator', 'activate' ) );
        register_deactivation_hook( __FILE__, array( '\\Quick_Event_Manager\\Plugin\\Control\\Deactivator', 'deactivate' ) );
        /**
         * @var \Freemius $freemius freemius SDK.
         */
        $freemius->add_action( 'after_uninstall', array( '\\Quick_Event_Manager\\Plugin\\Control\\Uninstall', 'uninstall' ) );
        $plugin = new Plugin( 'quick-event-manager', QUICK_EVENT_MANAGER_PLUGIN_VERSION, $freemius );
        $plugin->run();
    }
    
    run_quick_event_manager();
} else {
    global  $wfea_fs ;
    
    if ( null !== $wfea_fs && !$wfea_fs->is_premium() ) {
        $wfea_fs->set_basename( true, __FILE__ );
    } else {
        die( esc_html__( 'You already have a pro version of Quick Event Manager (Premium) installed, please check versions and delete one of them. The correct one should be in the folder wp-content/quick-event-manager-premium - this one you are trying is in folder wp-content/plugins/', 'display-eventbrite-events' ) . esc_html( basename( plugin_dir_path( __FILE__ ) ) ) );
    }
    
    return;
}
