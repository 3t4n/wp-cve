<?php

namespace FAL;

/**
 * Plugin Name: Surror Module Dashboard
 * Plugin URI: https://surror.com/
 * Description: The surror dashboard module to provide the admin dashboard page for the surror products. 🚀
 * Version: 1.0.4
 * Author: Surror
 * Author URI: https://surror.com/
 * Text Domain: surror-module-dashboard
 */
if (!\defined('ABSPATH')) {
    exit;
}
use FAL\Surror\Dashboard;
require_once plugin_dir_path(__FILE__) . 'includes/class-base.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-dashboard.php';
new Dashboard();
