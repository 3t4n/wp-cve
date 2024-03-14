<?php

// Check that we should be doing this
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit; // Exit if accessed directly
}

$api_url = 'https://social.appsmav.com/api/v1/';

try
{
    // Delete the stored informations
    $id_shop = get_option('socialboost_shop_id', 0);
    $id_site = get_option('socialboost_appid', 0);
    $payload = get_option('socialboost_payload', 0 );
    delete_option('socialboost_shop_id');
    delete_option('socialboost_appid');
    delete_option('socialboost_payload');
    delete_option('socialboost_admin_email');

    $plugin_type = 'WP';
    if (class_exists('WC_Integration')) {
        $plugin_type = 'WOO';
    }
    $param = array('app' => 'grvlsw', 'plugin_type' => $plugin_type, 'status' => 'delete', 'id_shop' => $id_shop, 'id_site' => $id_site, 'payload'=>$payload);
    $url = $api_url . 'pluginStatus';

    wp_remote_post($url, array('body' => $param, 'timeout' => 10));
}
catch(Exception $e){}



