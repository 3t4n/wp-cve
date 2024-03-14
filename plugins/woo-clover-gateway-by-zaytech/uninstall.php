<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete the api key.
$settings = get_option( 'woocommerce_woocci_zaytech_settings' );
$settings["secret_key"] = "";

update_option( 'woocommerce_woocci_zaytech_settings', $settings );
delete_option( 'woocci_pakms_key' );
delete_option( 'woocci_jwt_token' );
