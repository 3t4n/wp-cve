<?php
/**
 * Plugin Name: Simple Revisions Delete by bweb
 * Plugin URI: http://b-website.com/
 * Description: Let you delete your posts revisions individually or all at once (purge or bulk action). Compatible with Gutenberg (experimental) and classic editor.
 * Author: Brice CAPOBIANCO
 * Author URI: http://b-website.com/
 * Version: 1.5.4
 * Domain Path: /langs
 * Text Domain: simple-revisions-delete
 */

/*  Copyright 2015  Brice CAPOBIANCO  (contact : http://b-website.com/contact)

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

/**
 * SECURITY : Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed!' );
}

/**
 * Load plugin textdomain
 */
add_action( 'init', 'wpsrd_load_textdomain' );
function wpsrd_load_textdomain() {
	$path = dirname( plugin_basename( __FILE__ ) ) . '/langs/';
	load_plugin_textdomain( 'simple-revisions-delete', false, $path );
}

/**
 * Load plugin files
 */
$wppic_files = array( 'functions', 'bulk', 'single', 'once', 'gutenberg' );
foreach ( $wppic_files as $wppic_file ) {
	require_once( plugin_dir_path( __FILE__ ) . 'wp-simple-revisions-delete-' . $wppic_file . '.php' );
}

/**
 * Add custom meta link on plugin list page
 */
add_filter( 'plugin_row_meta', 'wpsrd_meta_links', 10, 2 );
function wpsrd_meta_links( $links, $file ) {
	if ( $file === 'simple-revisions-delete/wp-simple-revisions-delete.php' ) {
		$links[] = '<a href="http://b-website.com/category/plugins" target="_blank" title="' . __( 'More b*web Plugins', 'simple-revisions-delete' ) . '">' . __( 'More b*web Plugins', 'simple-revisions-delete' ) . '</a>';
		$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7Z6YVM63739Y8" target="_blank" title="' . __( 'Donate to this plugin &#187;' ) . '"><strong>' . __( 'Donate to this plugin &#187;' ) . '</strong></a>';
	}
	return $links;
}
