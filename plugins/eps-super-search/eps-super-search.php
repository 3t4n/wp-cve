<?php
/**
 * 
 * EPS 301 WEBCAM
 * 
 * 
 * 
 * This plugin queries and caches the 5 most recent webcam uploads for Flyingmax.com.
 * 
 * 
 * PHP version 5
 *
 *
 * @package    EPS Packages
 * @author     Shawn Wernig ( shawn@eggplantstudios.ca )
 * @version    1.0.0
 */

 
/*
Plugin Name: Super Search - Custom Post Types
Plugin URI: http://www.eggplantstudios.ca
Description: This plugin allows you to create search widgets for custom post types, and redirect to custom search templates.
Version: 1.0.0
Author: Shawn Wernig http://www.eggplantstudios.ca
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

define ( 'EPS_SS_PATH', plugin_dir_path(__FILE__) );
define ( 'EPS_SS_URL', plugin_dir_url( __FILE__ ) );
define ( 'EPS_SS_VERSION', '1.0.0');

register_activation_hook(__FILE__, array('EPS_Super_Search_Plugin', '__activation'));
register_deactivation_hook(__FILE__, array('EPS_Super_Search_Plugin', '__deactivation'));

include_once( EPS_SS_PATH . 'widget.super_search.php' );
        

class EPS_Super_Search_Plugin {

    public function __construct(){
        if(is_admin()){
            if ( !self::is_current_version() )  self::update_self();
        }
    }
    
    /**
     * 
     * 
     * Activation and Deactivation Handlers.
     * 
     * @return nothing
     * @author epstudios
     */
    public static function __activation() {
            self::update_self();
    }
    public static function __deactivation() {

    }
    
    function is_current_version(){
        $version = get_option( 'eps_ss_version' );
        return version_compare($version, EPS_SS_VERSION, '=') ? true : false;
    }

     /**
     * 
     * CHECK VERSION
     * 
     * This function will check the current version and do any fixes required
     * 
     * @return string - version number.
     * @author epstudios
     *      
     */
    public function update_self() {
        $version = get_option( 'eps_ss_version' );

        if( version_compare($version, '1.0.0', '<')) {} 
        
        update_option( 'eps_ss_version', EPS_SS_VERSION );
        return EPS_SS_VERSION;
    }


 
    
}


// Run the plugin.
$EPS_Super_Search_Plugin = new EPS_Super_Search_Plugin();

?>