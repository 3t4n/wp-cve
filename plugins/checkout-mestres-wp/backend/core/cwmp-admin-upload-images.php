<?php
add_action( 'admin_enqueue_scripts', 'misha_include_js' );
function misha_include_js() {
	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}
 	wp_enqueue_script( 'cwmp_update_image', CWMP_PLUGIN_ADMIN_URL . 'assets/js/upload-image.js?version=0002', array( 'jquery' ) );
}