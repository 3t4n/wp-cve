<?php
/**
 * @package SSL_Fixer
 * @version 0.3
 */
/*
Plugin Name: SSL Fixer
Plugin URI: https://wordpress.org/extend/plugins/ssl-fixer/
Text Domain: ssl-fixer
License: GPLv2 or later
Description: An extremely simple and lightweight plugin that forces your SSL to work!
Author: Stalwart Fox
Version: 0.3
Author URI: https://stalwartfox.net/
*/
/*  Copyright 2014  Frank Altera Novoa  (email : contact@stalwartfox.net)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
defined('ABSPATH') or die("You do not have access to this page!");
include_once('sf-admin.php');
include_once('sf-utilities.php');

//Fix insecure links within the database.
function fix_insecure_links() {
	global $wpdb;

	$table = $wpdb->prefix . 'options';
	$query = "UPDATE " . $table . " SET option_value = REPLACE(option_value, 'http://', 'https://') WHERE option_name = 'home' OR option_name = 'siteurl'";
	$wpdb->query($query);

	$table = $wpdb->prefix . 'posts';
	$query = "UPDATE " . $table . " SET post_content = REPLACE(post_content, 'http://', 'https://')";
	$wpdb->query($query);

	$table = $wpdb->prefix . 'postmeta';
	$query = "UPDATE " . $table . " SET meta_value = REPLACE(meta_value, 'http://', 'https://')";
	$wpdb->query($query);

	$table = $wpdb->prefix . 'comments';
	$query = "UPDATE " . $table . " SET comment_content = REPLACE(comment_content, 'http://', 'https://')";
	$wpdb->query($query);
}

//Modify any insecure links within the wp-config.php file
function fix_wpconfig() {
	$path = ABSPATH . 'wp-config.php';
	$contents = file_get_contents($path);
	$contents = str_replace('http://', 'https://', $contents);
	file_put_contents($path, $contents);
}

//Execute the changes
function fix_it() {
	fix_wpconfig();
	fix_insecure_links();

	add_option('ssl_fixed', 'true');
}

//Create variables for the plugin
register_activation_hook(__FILE__, 'set_fixer_options');
function set_fixer_options() {
	add_option('ssl_fixed', 'false');
	set_transient('ssl_fixer_activation', true, 5);
}

//Delete variables upon deactivation
register_deactivation_hook(__FILE__, 'unset_fixer_options');
function unset_fixer_options() {
	delete_option('ssl_fixed');
}

//Check activation variable, if not true, display notice for a unique time
add_action('admin_notices', 'activation_notice');

//Activate plugin on click
if (isset($_POST['fixme'])) {
	include_once(ABSPATH . 'wp-includes/pluggable.php');
	$nonce = $_REQUEST['_wpnonce'];
	if (current_user_can('manage_options') && wp_verify_nonce($nonce, 'sslfixer_nonce_action')) {
		fix_it();
		notice_message( __( 'Your SSL is fixed and enforced!', 'ssl-fixer'), 'success');
	} else {
		notice_message( __( 'Something went wrong! SSL not fixed!', 'ssl-fixer' ), 'error');
	}
}
?>