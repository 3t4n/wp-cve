<?php

/**
 * 
 * @package WP E-Signature - Gravity Form
 * @author  Stephen Gravitt <stpehen.gravitt@approveme.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Provide context for the various installation and activation states
 * 
 * @return {string} | One of many strings to represent activation state:
 *    'no_wpe', // wp e-signature not installed
 *    'wpe_inactive', // wp e-signature installed but not active
 *    'wpe_expired', // wp e-signature installed and active but license is expired
 *    'wpe_active_basic', // wp e-signature is installed, active, does not have pro addons
 *    'wpe_active_pro', // wp e-signature is installed, active, AND has pro addons
 */

if(!function_exists('esig_get_activation_state')) {
    
    function esig_get_activation_state(){


        if (!file_exists(WP_PLUGIN_DIR . '/e-signature/e-signature.php') && !function_exists("WP_E_Sig")) {

            return 'no_wpe'; // wp e-signature not installed

        }

        if( ! function_exists("WP_E_Sig") ){

            return 'wpe_inactive'; // wp e-signature installed but not active

        }else{

            if( ! Esign_licenses::is_license_valid() ){

                return 'wpe_expired'; // wp e-signature installed and active but license is expired

            }

            if (file_exists(WP_PLUGIN_DIR . '/wpesignature-add-ons/wpesignature-add-ons.php') && is_plugin_active("wpesignature-add-ons/wpesignature-add-ons.php")) {

                return 'wpe_active_pro';  // wp e-signature is installed, active, AND has pro addons

            } else if (!function_exists("esig_business_pack_activate")) {

                return "wpe_inactive_pro"; // wp e-signature is installed , pro installed but not active but user has active license    

            } else if (file_exists(WP_PLUGIN_DIR . '/e-signature-business-add-ons/e-signature-business-add-ons.php') && is_plugin_active("e-signature-business-add-ons/e-signature-business-add-ons.php")) {

                return 'wpe_active_pro';  // wp e-signature is installed, active, AND has pro addons

            } 
            else{

                return 'wpe_active_basic'; // wp e-signature is installed, active, does not have pro addons

            }
        }
    }

}

/**
 *  Provides partial template for given names. 
 */
if(!function_exists("esig_load_template")) {
    
  function esig_load_template($path, $arg=array() ){
            $file =  $path . ".php" ; 
            ob_start();
            if(file_exists($file)){
                require_once($file);
                $contents = ob_get_clean();
                return $contents; 
            }

            return "File not found" ; 
    }  
}

/**
 *  return a plugin activation link 
 */
if(!function_exists("esig_plugin_activation_link"))
{
    function esig_plugin_activation_link($plugin_file){
        $activate_url = add_query_arg(
            array(
                '_wpnonce' => wp_create_nonce( 'activate-plugin_' . $plugin_file ),
                'action'   => 'activate',
                'plugin'   => $plugin_file,
            ),
            network_admin_url( 'plugins.php' )
        );

        if ( is_network_admin() ) {
            $activate_url = add_query_arg( array( 'networkwide' => 1 ), $activate_url );
        }
        return $activate_url;
    }
}
