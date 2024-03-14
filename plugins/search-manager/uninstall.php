<?php
/**
* Plugin uninstall
*/

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( ! current_user_can( 'activate_plugins' ) ) {
	exit;
}

// here we go
delete_option( 'wcstm_lite_settings_wordpress' );
delete_option( 'wcstm_lite_settings_woocommerce' );
delete_option( 'wcstm_lite_terms_recent' );
delete_option( 'wcstm_lite_terms_unsuccessful' );