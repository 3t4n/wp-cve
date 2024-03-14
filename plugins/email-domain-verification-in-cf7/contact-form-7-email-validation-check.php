<?php
/*
Plugin Name: Contact Form 7 Email Validation
Plugin URI: https://wordpress.org/plugins/email-domain-verification-in-cf7/
Description: Add a customized functionality to add a email field validation check point to the popular Contact Form 7 plugin. A DNS verification validation has been integrated to verify email address from a valid domain.
Author: clarionwpdeveloper
Author URI: https://www.clariontech.com/
Version: 3.5.2
Text Domain: contact-form-7-email-validation
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Clarion Technologies; either version 2
of the License, or (at your option) any later version.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Clarion Technologies.

Copyright 2005-2019  Clarion Technologies.
*/

defined('ABSPATH') or die('Direct Access Restricted!'); // Check point to restrict direct access to the file

add_action('admin_init', 'wpcf7_email_validation_has_parent_plugin');

    function wpcf7_email_validation_has_parent_plugin() {
        
        if (is_admin() && current_user_can("activate_plugins") && !is_plugin_active("contact-form-7/wp-contact-form-7.php")) {
            
            add_action('admin_notices', 'wpcf7_email_validation_nocf7_notice');
            
            deactivate_plugins(plugin_basename(__FILE__));
            
            $flag = (int) $_GET['activate'];
            
            if (isset($flag)) {
            
                unset($_GET['activate']);
            }
        }
    }
    
    function wpcf7_email_validation_nocf7_notice() {
    
        $plugin_URL =  esc_url( admin_url('plugin-install.php?tab=search&s=contact+form+7') );
    
        ?>
        <div class="error">
            <p>
                <?php
                printf( __('%s must be installed and activated for the CF7 Email Validation Check plugin to work', 'contact-form-7-email-validation'), '<a href="' . $plugin_URL . '">Contact Form 7</a>'
                );
                ?>
            </p>
        </div>
        <?php
    }

    function wpcf7_validate_email_check($emailAddress) {
       // Check the formatting is correct
        if(filter_var($emailAddress, FILTER_VALIDATE_EMAIL) === false){
          return false;
        }
        $domain = explode("@", $emailAddress, 2);
        return $response = (checkdnsrr($domain[1]) ? true : false);
      
    }

    function wpcf7_custom_email_validation_filter($result, $tags) {
                
        $tags = new WPCF7_FormTag( $tags );

        $type = $tags->type;
        $name = $tags->name;

        if ('email' == $type || 'email*' == $type) { // Only apply to fields with the form field name of "company-email"
          
          $email_value = sanitize_email($_POST[$name]);
          
          if(!wpcf7_validate_email_check($email_value)){
              //code commented to enable translations

            $result->invalidate( $tags, __( 'Email address entered is not valid, DNS resolution failed.', 'contact-form-7-email-validation' ));
          }
        }
        return $result;
    }

    add_filter('wpcf7_validate_email', 'wpcf7_custom_email_validation_filter', 20, 2); // Email field
    add_filter('wpcf7_validate_email*', 'wpcf7_custom_email_validation_filter', 20, 2); // Req. Email field

    function contact_form_7_email_valid_load_plugin_textdomain() {
        load_plugin_textdomain( 'contact-form-7-email-validation', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }
    add_action( 'plugins_loaded', 'contact_form_7_email_valid_load_plugin_textdomain' );