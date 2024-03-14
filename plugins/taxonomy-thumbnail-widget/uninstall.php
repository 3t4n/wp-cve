<?php
defined('WP_UNINSTALL_PLUGIN') or die('No script kiddies please!');

if (!current_user_can('activate_plugins')) {
    return;
}
check_admin_referer('bulk-plugins');

if (__FILE__ != WP_UNINSTALL_PLUGIN) {
    return;
}

if (defined('MULTISITE')) {
    delete_site_option('ttw_manager_settings');
} else {
    delete_option('ttw_manager_settings');
}
?>