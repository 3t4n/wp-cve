<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die( '-1' );
}

if ( ! is_multisite() ) {
	delete_user_meta( get_current_user_id(), 'qlwapp-user-rating' );
	delete_option( 'qlwapp' );
	delete_option( 'qlwapp_box' );
	delete_option( 'qlwapp_button' );
	delete_option( 'qlwapp_contacts' );
	delete_option( 'qlwapp_display' );
	delete_option( 'qlwapp_scheme' );
	delete_option( 'qlwapp_settings' );
	delete_option( 'qlwapp_woocommerce' );
}
