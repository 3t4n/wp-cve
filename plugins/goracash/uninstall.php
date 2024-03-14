<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

delete_option('goracash_idw');
delete_option('goracash_settings');
delete_option('goracash_client_id');
delete_option('goracash_client_secret');
delete_option('goracash_ads_thematic');
delete_option('goracash_ads_advertiser');
delete_option('goracash_ads_default_lang');
delete_option('goracash_ads_default_market');
delete_option('goracash_ads_popexit');
delete_option('goracash_ads_top_bar');
delete_option('goracash_ads_force_ssl');