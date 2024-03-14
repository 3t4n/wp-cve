<?php

use Premmerce\ExtendedUsers\ExtendedUsersPlugin;


/**
 * WooCommerce Customers Manager
 *
 * @package           Premmerce\ExtendedUsers
 *
 * @wordpress-plugin
 * Plugin Name:       Premmerce WooCommerce Customers Manager
 * Plugin URI:        https://premmerce.com/woocommerce-customers-manager/
 * Description:       This plugin extends the standard user list and the edit user page in WordPress and adds the customer data from WooCommerce.
 * Version:           1.1.14
 * Author:            Premmerce
 * Author URI:        https://premmerce.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-customers-manager
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 7.3.0
 */

// If this file is called directly, abort.
if(!defined('WPINC')){
	die;
}

call_user_func(function(){

	require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

	if(!get_option('premmerce_version')){
		require_once plugin_dir_path(__FILE__) . '/freemius.php';
	}

	$main = new ExtendedUsersPlugin(__FILE__);

	register_activation_hook(__FILE__, [$main, 'activate']);

	$main->run();
});
