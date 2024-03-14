<?php

/**
 * Plugin Name:       Integrate Razorpay for Contact Form 7
 * Description:       This plugin seamlessly integrates Razorpay with Contact Form 7.
 * Version:           1.0.9
 * Requires at least: 5.6
 * Requires PHP:      7.2
 * Author:            Codolin Technologies
 * Author URI:        https://www.codolin.com?utm_source=wordpress&utm_medium=plugin&utm_campaign=integrate-razorpay-cf7&utm_id=integrate-razorpay-cf7
 */


define('CF7RZP_VERSION_NUM', 	'1.0.9');
define('CF7RZP_DIR_NAME', 	'integrate-razorpay-contact-form-7');
define('CF7RZP_DIR_URL', 	plugin_dir_url( __FILE__ ));
//define('CF7RZP_DEFAULT_CMP_NAME', 	'Acme Corp');
//define('CF7RZP_DEFAULT_CMP_LOGO', 	'https://cdn.razorpay.com/logos/FFATTsJeURNMxx_medium.png');


//  plugin functions
register_activation_hook( 	__FILE__, "cf7rzp_activate" );
register_deactivation_hook( __FILE__, "cf7rzp_deactivate" );
register_uninstall_hook( 	__FILE__, "cf7rzp_uninstall" );

function cf7rzp_activate() {
    
    // default options
    $cf7rzp_options = array(
        'mode' => '1',
        'rzp_key_id' => '',
        'rzp_key_secret' => '',
        'rzp_cmp_name' => '',
        /*'rzp_cmp_logo' => '',*/
        'return_url' => '',
        'cancel_url' => ''
    );
    
    add_option("cf7rzp_options", $cf7rzp_options);
    
}

function cf7rzp_deactivate() {
}

function cf7rzp_uninstall() {
}

// check to make sure contact form 7 is installed and active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {		
    
    // public includes
    include_once('includes/enqueue.php');
    
    // admin includes
    if (is_admin()) {
        include_once('includes/admin/rzp_tab.php');
        include_once('includes/admin/rzp_menu_links.php');
        include_once('includes/admin/rzp_settings.php');
        include_once('includes/admin/enqueue.php');
        include_once('includes/admin/functions.php');
    }

    // include payments functionality
    include_once('includes/payments/main.inc.php');
    
} else {
    
    // give warning if contact form 7 is not active
    function cf7rzp_my_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( '<b>Integrate Razorpay for Contact Form 7:</b> Contact Form 7 is not installed and / or active! Please install <a target="_blank" href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a>.', 'cf7rzp' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'cf7rzp_my_admin_notice' );
    
}

?>
