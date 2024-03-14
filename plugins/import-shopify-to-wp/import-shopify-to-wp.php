<?php
/**
 * Plugin Name: Import Shopify To WP
 * Version: 1.0.1
 * Author: WPBeginner
 * Description: Easily transfer your Shopify Store to WooCommerce
 * Plugin URI: https://shopifytowp.com/
 * Requires PHP: 7.1
 * Requires at least: 5.6
 * Text Domain: import-shopify-to-wp
 */

// Constants
// ----------------------------------------------------------------------------
use S2WPImporter\AdminNotice;
use S2WPImporter\AdminPage;
use S2WPImporter\Process\Importer;
use S2WPImporter\Plugins\Installer as PluginsInstaller;

define('S2WP_IMPORTER_VERSION', '1.0.1');
define('S2WP_IMPORTER_DIR', plugin_dir_path(__FILE__));
define('S2WP_IMPORTER_URI', plugin_dir_url(__FILE__));

// Activation hook: Create custom tables
// ----------------------------------------------------------------------------
register_activation_hook(__FILE__, function () {
    (new \S2WPImporter\VariationsLog())->createTable();
});

// Includes
// ----------------------------------------------------------------------------
require_once S2WP_IMPORTER_DIR . 'vendor/autoload.php';

add_action('plugins_loaded', function () {
    (new AdminNotice())->init();
    (new AdminPage())->init();
    (new Importer())->init();
    (new PluginsInstaller())->init();
});
