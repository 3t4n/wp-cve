<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function cf7rzp_admin_enqueue($hook){
    wp_enqueue_script('cf7rzp-sweetalert2', CF7RZP_DIR_URL.'assets/js/lib/sweetalert2.js');
    wp_enqueue_script('cf7rzp-admin', CF7RZP_DIR_URL.'assets/js/admin.js',array('jquery'),CF7RZP_VERSION_NUM,true);
    wp_localize_script('cf7rzp-admin', 'ajax_object_cf7rzp',
		array (
			'ajax_url' 			=> admin_url('admin-ajax.php')
		)
	);

    wp_enqueue_style( 'cf7rzp-styles', CF7RZP_DIR_URL.'assets/css/styles.css','',CF7RZP_VERSION_NUM);
}
add_action('admin_enqueue_scripts','cf7rzp_admin_enqueue',10);