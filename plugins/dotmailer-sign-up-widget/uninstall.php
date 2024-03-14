<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * Fired when the plugin is uninstalled.
 *
 * @package    Dotdigital_WordPress
 */
// If uninstall not called from WordPress, then exit.
if (!\defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
/**
 * Remove options
 */
function dotdigital_wordpress_uninstall()
{
    delete_option('dm_API_credentials');
    delete_option('dm_API_messages');
    delete_option('dm_API_address_books');
    delete_option('dm_API_data_fields');
    delete_option('dm_redirections');
    delete_option('dm_api_endpoint');
    delete_option('widget_dm_widget');
}
dotdigital_wordpress_uninstall();
