<?php

use AjaxPagination\AjaxPaginationPlugin;

/**
 *
 * Plugin Name:       WP Ajax Load More Pagination
 * Plugin URI:        https://processby.com/ajax-pagination-plugin-wordpress/
 * Description:       Loading paged content with Ajax.
 * Version:           1.1.5
 * Author:            Processby
 * Author URI:        https://processby.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-ajax-pagination
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

call_user_func( function () {

	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

	$main = new AjaxPaginationPlugin( __FILE__ );

	register_activation_hook( __FILE__, [ $main, 'activate' ] );

	register_deactivation_hook( __FILE__, [ $main, 'deactivate' ] );

	register_uninstall_hook( __FILE__, [ AjaxPaginationPlugin::class, 'uninstall' ] );

	$main->run();
} );