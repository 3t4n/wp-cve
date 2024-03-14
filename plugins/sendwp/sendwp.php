<?php

/**
 * Plugin Name: SendWP
 * Description: The easy solution to transactional email in WordPress.
 * Version: 1.4.6
 * Requires PHP: 5.6
 */

if (version_compare(PHP_VERSION, '5.6', '<')) {
    require_once plugin_dir_path(__FILE__) . 'includes/compatibility.php';
    add_action('admin_notices', 'sendwp_below_php_version_notice');
    return;
}

require_once plugin_dir_path(__FILE__) . 'assets/load.php';
require_once plugin_dir_path(__FILE__) . 'includes/api/load.php';

require_once plugin_dir_path(__FILE__) . 'includes/interface.mailer.php';
require_once plugin_dir_path(__FILE__) . 'includes/class.mailer.php';
require_once plugin_dir_path(__FILE__) . 'includes/functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/hooks.php';

if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'includes/admin/load.php';
}

require_once plugin_dir_path(__FILE__) . 'includes/activation.php';
require_once plugin_dir_path(__FILE__) . 'includes/deactivation.php';
require_once plugin_dir_path(__FILE__) . 'includes/trustedlogin.php';
