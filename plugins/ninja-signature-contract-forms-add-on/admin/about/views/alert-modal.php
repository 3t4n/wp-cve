<?php

/**
 * ESIG WP ADMIN  ALERTS
 * @package   WP E-Signature - utm_campaign=ninja-forms Form
 */

require_once( dirname(__DIR__,2) . '/admin/about/includes/esig-activations-states.php' );
$esigStatus = esig_get_activation_state();


switch ($esigStatus){
  
  case 'wpe_inactive':
  case 'wpe_expired':
    echo '<div class="bangBar error"> <h4>'. __('*You willl need to activate your WP E-Signature license to run the Ninja Forms Signature add-on.','esign').' <a href="admin.php?page=esign-licenses-general">'. __('Enter your license here','esign').'</a></h4></div>';
    break;
  case 'wpe_active_basic':
    echo '<div class="bangBar error"> <h4>'. __('*Your WP E-Signature install is missing the Pro Add-Ons. Advanced functionality will not work without these add-ons installed.','esign').' <a href="https://www.approveme.com/profile/">'. __('Install Pro Add-Ons','esign').'</a></h4></div>';
  case 'wpe_active_pro':
    
    if(!class_exists('Ninja_Forms')) {// Notice about add-on dependent 3rd party plugin if not installed
      echo '<div class="error ' . $esigStatus . '"><span class="esig-icon-esig-alert"></span><h4>'. __('Ninja Forms plugin is not installed. Please install Ninja Forms version 2.0 or greater -','esign').' <a href="https://wordpress.org/plugins/ninja-forms/">'. __('Get it here now','esign').'</a></h4></div>';
    }elseif(!class_exists('ESIG_SAD_Admin')){// Notice about stand alone documents if not enabled
      echo '<div class="error ' . $esigStatus . '"><span class="esig-icon-esig-alert"></span><h4>'. __('WP E-Signature','esign').' <a href="https://www.approveme.com/wpesign-features/stand-alone-documents/?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms" target="_blank">"'. __('Stand Alone Documents','esign').'"</a> '. __('Add-on is not installed. Please install WP E-Signature Stand Alone Documents - version 1.2.5 or greater.','esign').'  </h4></div>';
    }
    break;
  case 'no_wpe':
  default:
    echo '<div class="bangBar error"> <h4>'. __('*WP E-Signature is not active. &nbsp; It is required to run the Ninja forms Signature add-on.','esign').' &nbsp;<a href="https://www.approveme.com/ninja-forms-signature-special/?utm_source=wprepo&utm_medium=link&utm_campaign=ninja-forms">'. __('Get your business license now','esign').'</a></h4></div>';
    break;
}

