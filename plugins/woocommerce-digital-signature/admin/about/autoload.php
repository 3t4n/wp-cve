<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


 // About page directory path
            $pluginName = "Woocommerce" ; 

            if (!defined('ESIGN_'. strtoupper(preg_replace("/\s+/", "", $pluginName)) . '_ABOUT_PATH'))
                    define('ESIGN_'. strtoupper(preg_replace("/\s+/", "", $pluginName)) . '_ABOUT_PATH', dirname(__FILE__));
            
            if (!defined('ESIGN_'. strtoupper(preg_replace("/\s+/", "", $pluginName)) . '_ABOUT_URL'))
                    define('ESIGN_'. strtoupper(preg_replace("/\s+/", "", $pluginName)) . '_ABOUT_URL', plugins_url("/", __FILE__));

require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-activations-states.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-about-load.php' );



$esigAbout = new esig_Addon_About($pluginName);
$esigAbout->hooks();

add_action('admin_notices', array($esigAbout, 'requirement'));
if (strpos(esig_woocommerce_get("page"), "esign-woocommerce-about") === false) {
add_action('esig_admin_notices', array($esigAbout, 'requirement'));
}

function woocommerce_message($esigStatus,$pluginName)
        {
            $screen = get_current_screen();
            $screenName = "admin_page_esign-woocommerce-about";
            $asterisk = "" ; 
            
            if($screen->id == $screenName)
            {
               $asterisk = "*"; 
            }

            $woocommerceID = strpos($screen->id, 'woo');
            if($woocommerceID === false && $screen->id != 'plugins') return;
            
            switch ($esigStatus){

                case 'wpe_inactive':
                  return '<span class="esig-icon-esig-alert"></span><h4> ' . esc_attr($asterisk) . 'WP E-Signature is not activated. Please activate WP E-Signature to finish setting up your integration. <a class="about-button" href="'. esig_plugin_activation_link("e-signature/e-signature.php") .'">Activate WP E-Signature</a></h4>';
                  break;
                case 'wpe_expired':
                  return '<span class="esig-icon-esig-alert"></span><h4>' . esc_attr($asterisk) . 'You willl need to activate your WP E-Signature license to run the WooCommerce digital Signature add-on.  <a class="about-button" href="admin.php?page=esign-licenses-general">Enter your license here</a> </h4>';
                  break;
                case 'wpe_active_basic':
                  return '<span class="esig-icon-esig-alert"></span><h4>' . esc_attr($asterisk) . 'Your WP E-Signature install is missing the Pro Add-Ons. Advanced functionality will not work without these add-ons installed. <a class="about-button" href="'. admin_url("admin.php?page=esign-addons") .'">Install Pro Add-Ons</a></h4>';
                  break;
                case 'wpe_inactive_pro':
                  return '<span class="esig-icon-esig-alert"></span><h4>' . esc_attr($asterisk) . 'Your WP E-Signature Pro Add-Ons are not installed. Advanced functionality will not work without these add-ons installed and enabled. <a class="about-button" href="'. esig_plugin_activation_link("e-signature-business-add-ons/e-signature-business-add-ons.php") .'">Enable Pro Add-Ons</a></h4>';
                  break;
                case 'wpe_active_pro':
                 
                  if(!class_exists('WooCommerce')) {// Notice about add-on dependent 3rd party plugin if not installed
                   return '<span class="esig-icon-esig-alert"></span><h4>Hi there! It looks like the <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce plugin</a> is not active. You need to activate the WooCommerce plugin in order to use the E-signature WooCommerce add-on features.</h4>';
                  }
                  elseif(!class_exists('ESIG_SAD_Admin')){// Notice about stand alone documents if not enabled
                    return '<span class="esig-icon-esig-alert"></span><h4>WP E-Signature <a href="https://www.approveme.com/downloads/stand-alone-documents/?utm_source=wprepo&utm_medium=link&utm_campaign=calderaform" target="_blank">"Stand Alone Documents"</a> Add-on is not active. Please enable WP E-Signature Stand Alone Documents  <a class="about-button" href="'. admin_url("admin.php?page=esign-addons&tab=disable&esig_action=enable&plugin_url=esig-stand-alone-docs%2Fesig-sad.php&plugin_name=WP%20E-Signature%20-%20Stand%20Alone%20Documents") .'">Enable it now </a> </h4>';
                  }

                  break;
                case 'no_wpe':
                    return '<span class="esig-icon-esig-alert"></span> <h4>' . esc_attr($asterisk) . 'WP E-Signature is not installed. It is required to run the WooCommerce digital Signature add-on. &nbsp; <span class="button-container"><a class="about-button" href="https://www.approveme.com/woocommerce-signature-special/?utm_campaign=wprepo">Get your WP E-Signature license</a></span></h4>';
                    break;
                default:
                  return false;
                  break;
              }
        }

  /**
  *  Remove all admin notices from e-signature pages. 
  */
 add_action('in_admin_header', function () {

      $page  = isset($_GET['page']) ? esig_woocommerce_get('page') : false ;
      
      if(empty($page))
      {
        return false ; 
      }
 
      if(!empty($page)  && !preg_match("/esign-/i",$page)){
        return false;
      }
     

      remove_all_actions('admin_notices');
      remove_all_actions('all_admin_notices');

},1000);


