<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Woocommerce
 * @subpackage Profilegrid_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Profilegrid_Woocommerce
 * @subpackage Profilegrid_Woocommerce/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0e
	 */
         public function activate()
         {
             
            if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) 
            {
              //echo plugin_basename( 'profilegrid-woocommerce/profilegrid-woocommerce.php',__FILE__ );
               
               deactivate_plugins('profilegrid-woocommerce/profilegrid-woocommerce.php'); 
               $error_message = sprintf(__('This plugin requires <a href="%s">WooCommerce</a> plugin to be active!', 'woocommerce'),'http://wordpress.org/extend/plugins/woocommerce/');
               wp_die($error_message);
            }
            elseif(class_exists('Profile_Magic'))
            {
                 update_option('pg_woo_activation_redirect',"1");
            }
            else
            {
                update_option('pm_show_woocommerce_check_core_plugin_popup',"1");
            }

         }
         
         
         
	
}