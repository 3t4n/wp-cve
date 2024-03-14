<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// add razorpay settings menu under contact form 7 menu
function cf7rzp_admin_menu() {
	add_submenu_page('wpcf7',__( 'Razorpay Settings', 'contact-form-7' ),__( 'Razorpay Settings', 'contact-form-7' ),'wpcf7_edit_contact_forms', 'cf7rzp_settings','cf7rzp_settings',3);
}
add_action( 'admin_menu', 'cf7rzp_admin_menu', 20 );