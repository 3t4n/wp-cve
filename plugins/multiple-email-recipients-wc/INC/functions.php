<?php

/* Plugin functions for controlling the mail sending*/

// Check if WooCommerce is active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {


	// Hook me in!
	add_filter( 'woocommerce_email_headers', 'sunarcwoome_multiple_recipients', 10, 2);

	function sunarcwoome_multiple_recipients( $headers = '', $id = '') {
	// Get options
	$sunarcwoome_options = get_option('woome_settings_sunarc');
	// Replace the emails below to your desire email
	$emails = array( $sunarcwoome_options['email_1'], $sunarcwoome_options['email_2'] );


	// WooCommerce core
    if ($id == 'new_order'  && $sunarcwoome_options['enable_new']) {
        $headers .= 'Bcc: ' . implode(',', $emails) . "\r\n";
			//break;

	}

	if ($id == 'cancelled_order'  && $sunarcwoome_options['enable_cancelled']) {
        $headers .= 'Bcc: ' . implode(',', $emails) . "\r\n";
			//break;

	}

	
	return $headers;
}

}

else {return 'WooCommerce is not active, please install and activate it first';}