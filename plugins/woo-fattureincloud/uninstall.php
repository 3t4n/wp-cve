<?php

/* If uninstall not called from WordPress exit */

if (!defined('WP_UNINSTALL_PLUGIN')) {

    exit;

}

// Delete option from options table
delete_option('wfic_api_key_fattureincloud');

delete_option('api_key_fattureincloud');

delete_option('api_uid_fattureincloud');

delete_option('woo_fattureincloud_order_id');

delete_option('fattureincloud_auto_save');

delete_option('woo-fattureincloud-anno-fatture');

delete_option('fattureincloud_partiva_codfisc');

delete_option('fattureincloud_paid');

delete_option('fattureincloud_send_choice');

delete_option('update_customer_registry');

delete_option('show_short_descr');

delete_option('delete_autosave_fattureincloud');

delete_option('wfic_device_code');

delete_option('wfic_api_key_fattureincloud');

delete_option('wfic_refresh_token');

delete_option('wfic_id_azienda');

delete_option('count_load_time_woo_fattureincloud');

$user_id = get_current_user_id();

delete_user_meta( $user_id, 'woo_fattureincloud_notice_maybe_delay');

delete_user_meta( $user_id, 'woo_fattureincloud_notice_dismissed') ;


