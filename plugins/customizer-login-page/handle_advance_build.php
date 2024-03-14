<?php
// Include WordPress to use its functions.
require_once '../../../wp-load.php';

if ( isset( $_GET['confirm_advance_build'] ) && $_GET['confirm_advance_build'] == 'true' ) {
	if ( get_option( 'clp_build_package' ) === false ) {
		add_option( 'clp_build_package', 'newlpc' );
		delete_option( 'customizer_login_page_settings' );
	} else {
		update_option( 'clp_build_package', 'newlpc' );
		delete_option( 'customizer_login_page_settings' );
	}

	echo 'Success'; // You can return a response here if needed
	exit;
}

