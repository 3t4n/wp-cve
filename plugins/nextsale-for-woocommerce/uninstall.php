<?php

/**
 * Uninstall file to be triggered
 * @package Nextsale plugin
 */

defined("WP_UNINSTALL_PLUGIN") or die;

// Clear database
delete_option('nextsale_exchange_code');
delete_option('nextsale_access_token');
delete_option('nextsale_script_tags');
delete_option('nextsale_webhooks');
delete_option('nextsale_auth_granted');
