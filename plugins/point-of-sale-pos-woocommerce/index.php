<?php
/*
 * Plugin Name: Jovvie Point of Sale POS for WooCommerce
 * Plugin URI: https://www.jovvie.com
 * Description: A Point of Sale (POS) API plugin for the WooCommerce e-commerce toolkit. Sell anywhere.
 * Version: 4.1.9
 * Text Domain: zpos-wp-api
 * Domain Path: /lang
 * WC requires at least: 2.4.0
 * WC tested up to: 8.6.1
 * Author: BizSwoop a CPF Concepts, LLC Brand
 * Author URI: http://www.bizswoop.com
 */

namespace ZPOS;

const ACTIVE = true;
const PLUGIN_NAME = 'pos';
const TEXTDOMAIN = 'zpos-wp-api';
const CLOUD_APP_NAME = 'Jovvie';
const PLUGIN_ROOT = __DIR__;
const PLUGIN_ROOT_FILE = __FILE__;
const PLUGIN_VERSION = '4.1.9';
const REST_NAMESPACE = 'wc-pos';

remove_filter('template_redirect', 'redirect_canonical');

$autoload = __DIR__ . '/vendor/autoload.php';

if (!file_exists($autoload)) {
	throw new \Exception('Autoloader not exists');
}

require_once $autoload;

if (!function_exists('get_plugins')) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

new Setup();
