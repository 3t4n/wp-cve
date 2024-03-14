<?php
/**
 *
 * @since             1.0.0
 * @package           WC_Swiss_Qr_Bill
 *
 * Plugin Name:       Swiss QR Bill
 * Description:       Swiss QR Bill
 * Version:           1.2.4
 * Author:            swissplugins
 * Author URI:        https://hostbliss.ch/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       swiss-qr-bill
 * Domain Path:       /languages
 * WC requires at least: 2.6
 * WC tested up to: 5.7.1
 */

// If this file is called directly, abort.
if ( !defined('WPINC') ) {
    die;
}

/**
 * Currently plugin version.
 */
define('WC_SWISS_QR_BILL_VER', '1.2.1');
/**
 * Root level plugin file
 */
if ( !defined('WC_SWISS_QR_BILL_FILE') ) {
    define('WC_SWISS_QR_BILL_FILE', __FILE__);
}
/**
 * Define plugin upload directory
 */
if ( !defined('WC_SWISS_QR_BILL_UPLOAD_DIR') ) {
    $upload_dir = wp_upload_dir();
    define('WC_SWISS_QR_BILL_UPLOAD_DIR', $upload_dir['basedir'] . '/wc-swiss-qr-bill/');
}

/**
 * The code that runs during plugin activation.
 */
function activate_wc_swiss_qr_bill() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-wc-swiss-qr-bill-activator.php';
    WC_Swiss_Qr_Bill_Activator::activate();

}

register_activation_hook(__FILE__, 'activate_wc_swiss_qr_bill');

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wc_swiss_qr_bill() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-wc-swiss-qr-bill-deactivator.php';
    WC_Swiss_Qr_Bill_Deactivator::deactivate();
}

register_deactivation_hook(__FILE__, 'deactivate_wc_swiss_qr_bill');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wc-swiss-qr-bill.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_wc_swiss_qr_bill() {

    $plugin = new WC_Swiss_Qr_Bill();
    $plugin->run();

}

add_action('plugins_loaded', 'run_wc_swiss_qr_bill');
