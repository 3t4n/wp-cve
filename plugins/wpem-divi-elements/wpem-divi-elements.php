<?php
/*
Plugin Name: WP Event Manager - Divi Elements
Plugin URI:  www.wp-eventmanager.com
Description: Divi element provides an easy interface that smoothly combines with the Divi theme builder to offer you a toolset to add various elements without shortcodes.
Version:     1.0.1
Author:      WPEM Team
Author URI:  www.wp-eventmanager.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wpem-divi-elements
Domain Path: /languages

WP Event Manager Divi Elements is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WP Event Manager Divi Elements is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WP Event Manager Divi Elements. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

include_once(ABSPATH.'wp-admin/includes/plugin.php');
function pre_check_before_installing_divi_elements() 
{
/*
* Check weather WP Event Manager is installed or not. If WP Event Manger is not installed or active then it will give notification to admin panel
*/
if (! in_array( 'wp-event-manager/wp-event-manager.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{
        global $pagenow;
        if( $pagenow == 'plugins.php' )
        {
           echo '<div id="error" class="error notice is-dismissible"><p>';
           echo __( 'WP Event Manager is require to use WP Event Manager - Divi Elements' , 'wpem-divi-elements');
           echo '</p></div>';   
        }
        //return true;
}

}
add_action( 'admin_notices', 'pre_check_before_installing_divi_elements' );  


/**
 * WP_Event_Manager_Divi_Elements class.
 */
class WPEM_Divi_Elements {

    /**
     * The single instance of the class.
     *
     * @var self
     * @since  1.0.0
     */
    private static $_instance = null;

    /**
     * Main WP Event Manager Instance.
     *
     * Ensures only one instance of WP Event Manager is loaded or can be loaded.
     *
     * @since  1.0.0
     * @static
     * @see WP_Event_Manager()
     * @return self Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Constructor
     */
    public function __construct() 
    {
        // Define constants
        define( 'WPEM_DIVI_ELEMENTS_VERSION', '1.0.1' );
        define( 'WPEM_DIVI_ELEMENTS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
        define( 'WPEM_DIVI_ELEMENTS_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

        add_action( 'divi_extensions_init', array($this,'wpem_initialize_extension') );

    }

    /**
     * Creates the extension's main class instance.
     *
     * @since 1.0.0
     */
    public function wpem_initialize_extension() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/WpEventManagerDiviElements.php';
    }

    /**
     * Localisation
     */
    public function load_plugin_textdomain() {
        $domain = 'wpem-divi-elements';       
        $locale = apply_filters('plugin_locale', get_locale(), $domain);
        load_textdomain( $domain, WP_LANG_DIR . "/wpem-divi-elements/".$domain."-" .$locale. ".mo" );
        load_plugin_textdomain($domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }
}

/**
 * Main instance of WP Event Manager Speaker & Schedule.
 *
 * Returns the main instance of WP Event Manager to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return WPEM_Divi_Elements
 */
function wpem_divi_elements() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
    return WPEM_Divi_Elements::instance();
}
$GLOBALS['wpem_divi_elements'] =  wpem_divi_elements();
