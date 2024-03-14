<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function cf7rzp_public_enqueue(){
	wp_enqueue_script('cf7rzp-sweetalert2', CF7RZP_DIR_URL.'assets/js/lib/sweetalert2.js');
    wp_enqueue_script('cf7rzp-rzp-checkout','https://checkout.razorpay.com/v1/checkout.js');
    wp_enqueue_script('cf7rzp-main',plugins_url('/assets/js/main.js',__DIR__),array('jquery'),CF7RZP_VERSION_NUM);
    wp_localize_script('cf7rzp-main', 'ajax_object_cf7rzp',
		array (
			'ajax_url' 			=> admin_url('admin-ajax.php')
		)
	);
	
	wp_enqueue_style( 'cf7rzp-styles', CF7RZP_DIR_URL.'assets/css/styles.css','',CF7RZP_VERSION_NUM);
}
add_action('wp_enqueue_scripts','cf7rzp_public_enqueue',10);