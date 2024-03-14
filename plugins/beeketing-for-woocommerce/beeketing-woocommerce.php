<?php
/**
 * Plugin Name: Beeketing For WooCommerce
 * Plugin URI: https://beeketing.com
 * Description: Built to seamlessly integrate with WooCommerce, Beeketing is a platform of marketing & sales apps to grow sales for e-Commerce stores. Features include popup builders, up-sell & cross-sell offers, social lead generation, email automation service, product recommendation, and more. All to help you <strong>sell automatically and boost revenue to the max</strong>.
 * Version: 3.8.16
 * Author: Beeketing
 * Author URI: https://beeketing.com
 * WC tested up to: 3.7.0
 * Requires at least: 4.4
 * Tested up to: 5.3
 */

use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\PluginConfig;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Plugin\Loader;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Define plugin constants
define('BEEKETINGWOOCOMMERCE_PLUGIN_DIR', __DIR__ . '/');
define('BEEKETINGWOOCOMMERCE_PLUGIN_DIRNAME', __FILE__);
define('BEEKETINGWOOCOMMERCE_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Require plugin autoload
require_once BEEKETINGWOOCOMMERCE_PLUGIN_DIR . 'vendor/autoload.php';

$config = new PluginConfig(
    'app.js',
    'Beeketing',
    'beeketing_menu',
    BEEKETINGWOOCOMMERCE_PLUGIN_DIRNAME,
    BEEKETINGWOOCOMMERCE_PLUGIN_BASENAME
);

Loader::makeInstance($config);

require_once __DIR__ . '/sentry.php';

register_activation_hook(__FILE__, array(Loader::class, 'activateHook'));
register_deactivation_hook(__FILE__, array(Loader::class, 'deactivateHook'));

$pluginLoader = Loader::instance();
$pluginLoader->init();
