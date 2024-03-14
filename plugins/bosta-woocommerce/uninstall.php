<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// if uninstall not called from WordPress exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options.
delete_option('woocommerce_bosta_settings');
