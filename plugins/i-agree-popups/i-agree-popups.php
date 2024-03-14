<?php

/**
 * Plugin Name: I Agree! Popups
 * Description: Creates T&C and disclaimer popups for use across your whole WordPress site or individual posts and pages.
 * Version:     1.0
 * Author:      Jake The Bear
 * Text Domain: i-agree-popups
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
**/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Includes
require plugin_dir_path( __FILE__ ) . 'includes/i-agree.php';
require plugin_dir_path( __FILE__ ) . 'includes/i-agree-registration.php';
require plugin_dir_path( __FILE__ ) . 'includes/i-agree-popup-metaboxes.php';
require plugin_dir_path( __FILE__ ) . 'includes/i-agree-post-metaboxes.php';
require plugin_dir_path( __FILE__ ) . 'includes/i-agree-wp-footer.php';
require plugin_dir_path( __FILE__ ) . 'includes/i-agree-enqueue.php';

// Define and Initialise
$popup_registration = new I_Agree_Registration;
$popup = new I_Agree_Popups( $popup_registration );
register_activation_hook( __FILE__, array( $popup, 'activate' ) );
$popup_registration->init();
$popup_metaboxes = new I_Agree_Popup_Metaboxes;
$popup_metaboxes->init();
$post_metaboxes = new I_Agree_Post_Metaboxes;
$post_metaboxes->init();
$popup_footer_functions = new I_Agree_WP_Footer;
$popup_footer_functions->init();
$popup_enqueue = new I_Agree_Enqueue;
$popup_enqueue->init();

// Add Popups count and shortcut to Dashboard
if ( is_admin() ) {
    if ( ! class_exists( 'Gamajo_Dashboard_Glancer' ) ) {
        require plugin_dir_path( __FILE__ ) . 'includes/i-agree-glancer.php';  // WP 3.8
    }
    require plugin_dir_path( __FILE__ ) . 'includes/i-agree-admin.php';
    $popup_admin = new I_Agree_Admin($popup_registration);
    $popup_admin->init();
}

?>