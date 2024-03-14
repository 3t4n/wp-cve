<?php

namespace ShopWP;

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

\ShopWP\Factories\Config_Factory::build()->init();
$Processing_Database = \ShopWP\Factories\Processing\Database_Factory::build();

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

if (!current_user_can('activate_plugins')) {
    exit();
}

if (is_multisite()) {
    $Processing_Database->uninstall_plugin_multisite();
} else {
    $Processing_Database->uninstall_plugin();
}
