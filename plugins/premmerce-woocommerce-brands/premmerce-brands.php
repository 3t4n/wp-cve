<?php

use Premmerce\Brands\BrandsPlugin;

/**
 * Premmerce Brands for WooCommerce
 *
 *
 * @wordpress-plugin
 * Plugin Name:       Premmerce Brands for WooCommerce
 * Plugin URI:        https://premmerce.com/premmerce-woocommerce-brands-free-plugin/
 * Description:       This plugin makes it possible to create an unlimited number of brands that can be assigned to the products for better cataloging.
 * Version:           1.2.13
 * Author:            Premmerce
 * Author URI:        https://premmerce.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       premmerce-brands
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 7.3.0
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

    $main = new BrandsPlugin(__FILE__);

    register_activation_hook(__FILE__, [ $main, 'activate' ]);

    register_deactivation_hook(__FILE__, [ $main, 'deactivate' ]);

    register_uninstall_hook(__FILE__, [ BrandsPlugin::class, 'uninstall' ]);

    $main->run();
});
