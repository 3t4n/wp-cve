<?php

use Premmerce\UsersRoles\UsersRolesPlugin;

/**
 * Premmerce User Roles
 *
 *
 * @package           Premmerce\UsersRoles
 *
 * @wordpress-plugin
 * Plugin Name:       Premmerce User Roles
 * Plugin URI:        https://premmerce.com/wordpress-custom-user-roles/
 * Description:       This plugin has been  developed for creating user roles from the WordPress admin area and assigning the arbitrary access rights to them.
 * Version:           1.0.13
 * Author:            Premmerce
 * Author URI:        http://premmerce.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       premmerce-users-roles
 * Domain Path:       /languages
 *
 * WC tested up to: 7.3.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

call_user_func( function () {

	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

	if(!get_option('premmerce_version')){
		require_once plugin_dir_path(__FILE__) . '/freemius.php';
	}

	$main = new UsersRolesPlugin( __FILE__);

	$main->run();
} );
