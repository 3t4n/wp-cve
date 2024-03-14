<?php

namespace FAL;

/**
 * Plugin Name: Surror Module Notices
 * Plugin URI: https://surror.com/
 * Description: The surror notices module to provide the admin notices page for the surror products. 🚀
 * Version: 1.0.1
 * Author: Surror
 * Author URI: https://surror.com/
 * Text Domain: surror-module-notices
 */
if (!\defined('ABSPATH')) {
    exit;
}
use FAL\Surror\Notices;
require_once plugin_dir_path(__FILE__) . 'includes/class-base.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-notices.php';
new Notices();
