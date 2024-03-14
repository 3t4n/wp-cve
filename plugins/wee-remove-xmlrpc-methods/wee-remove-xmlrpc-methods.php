<?php
/**
 * Plugin Name: Remove XML-RPC Methods
 * Plugin URI: https://gitlab.com/walterebert/wee-remove-xmlrpc-methods
 * Description: Remove all methods from the WordPress XML-RPC API.
 * Version: 1.4.0
 * Author: Walter Ebert
 * Author URI: https://walterebert.com
 * Text Domain: wee-remove-xmlrpc-methods
 * License: GPL-2.0-or-later
 * License URI: https://spdx.org/licenses/GPL-2.0-or-later.html
 *
 * @package WordPress
 * @subpackage Remove_XMLRPC_Methods
 */

// Deny direct access.
if ( ! defined( 'ABSPATH' ) ) {
	http_response_code( 403 );
	die( 'Access denied' );
}

/**
 * Run on plugin activation
 */
function wee_remove_xmlprc_methods_activate() {
	add_option( 'wee_remove_xmlrpc_methods_default_ping_status_original', get_option( 'default_ping_status' ) );
	update_option( 'default_ping_status', 'closed' );
}

/**
 * Admin footer HTML
 */
function wee_remove_xmlrpc_methods_admin_footer() {
	if ( 'options-discussion' === get_current_screen()->id ) :
		?>
		<script>
		(function () {
			var default_ping_status = document.getElementById( 'default_ping_status' );
			default_ping_status.disabled = true;
		})();
		</script>
		<?php
	endif;
}

/**
 * Run on plugin deactivation
 */
function wee_remove_xmlprc_methods_deactivate() {
	$original = get_option( 'wee_remove_xmlrpc_methods_default_ping_status_original' );
	if ( 'closed' !== $original ) {
		$original = 'open';
	}
	update_option( 'default_ping_status', $original );
	delete_option( 'wee_remove_xmlrpc_methods_default_ping_status_original' );
}

/**
 * Remove HTTP headers
 */
function wee_remove_xmlprc_methods_header_remove() {
	header_remove( 'X-Pingback' );
}

/* Plugin settings */
register_activation_hook( __FILE__, 'wee_remove_xmlprc_methods_activate' );
register_deactivation_hook( __FILE__, 'wee_remove_xmlprc_methods_deactivate' );

/* Actions */
add_action( 'admin_footer', 'wee_remove_xmlrpc_methods_admin_footer', 10, 0 );
remove_action( 'wp_head', 'rsd_link' );

/* Filters */
add_filter( 'xmlrpc_methods', '__return_empty_array', PHP_INT_MAX );
add_filter( 'wp', 'wee_remove_xmlprc_methods_header_remove', PHP_INT_MAX, 0 );
add_filter( 'pings_open', '__return_false', PHP_INT_MAX, 2 );
