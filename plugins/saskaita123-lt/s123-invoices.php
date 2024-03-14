<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 *
 * @wordpress-plugin
 * Plugin Name: Invoice123
 * Plugin URI: https://www.invoice123.com
 * Description: Integration with Invoice123.com Application
 * Version: 1.4.3
 * Author: Invoice123.com
 * Author URI: https://www.invoice123.com
 * License: GPLv2 or later
 * Text Domain: s123-invoices
 * Domain Path: /languages
 *
 */

use S123\Includes\Base\S123_Activate;
use S123\Includes\Base\S123_Deactivate;

if (!defined('ABSPATH')) exit;

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

function activate_s123_plugin() {
    S123_Activate::s123_activate();
}
register_activation_hook(__FILE__, 'activate_s123_plugin');

function deactivate_s123_plugin() {
    S123_Deactivate::s123_deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_s123_plugin');

if (class_exists('S123\\Includes\\S123_Init')) {
    S123\Includes\S123_Init::s123_register_services();
}
