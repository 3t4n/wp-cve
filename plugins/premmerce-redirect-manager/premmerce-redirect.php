<?php

use Premmerce\Redirect\RedirectPlugin;

/**
 * Premmerce plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Premmerce Redirect Manager
 * Plugin URI:        https://premmerce.com/woocommerce-redirect-manager/
 * Description:       The Premmerce Redirect Manager enables you to create 301 and 302 redirects and to set up the automatic redirects for the deleted products in the WooCommerce store.
 * Version:           1.0.12
 * Author:            Premmerce
 * Author URI:        https://premmerce.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       premmerce-redirect
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}


call_user_func(function () {
    require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

    if(!get_option('premmerce_version')){
        require_once plugin_dir_path(__FILE__) . '/freemius.php';
    }

    $main = new RedirectPlugin(__FILE__);

    register_activation_hook(__FILE__, [$main, 'activate']);

    register_uninstall_hook(__FILE__, [\Premmerce\Redirect\RedirectPlugin::class, 'uninstall']);

    $main->run();
});
