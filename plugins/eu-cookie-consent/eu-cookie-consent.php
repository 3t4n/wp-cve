<?php
/*
Plugin Name: EU Cookie Consent
Description: Allows you to meet the minimum compliance requirements for the <a href="http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm#section_2" onclick="window.open( this ); return false;">EU cookie legislation</a> introduced in 2011 without sacrificing functionality.
Version: 0.1.4
Author: RS
Author URI: https://rs.scot
Author Email: wordpress.plugins@rs.scot
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

	Copyright 2015-2022 RS (wordpress.plugins@rs.scot)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 3, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

*/

if( !defined( 'ABSPATH' ) ) { exit; }

define( 'RS_EUCC__ADMIN_FUNC', 'rs_eucc_admin_init' );
define( 'RS_EUCC__ADMIN_PAGE', 'rs-eucc' );
define( 'RS_EUCC__ADMIN_REQCAP', 'add_users' );
define( 'RS_EUCC__BASE', 'eucc/' );
define( 'RS_EUCC__CC_VERSION', '1.0.9' );
define( 'RS_EUCC__OPTION', 'rs_eucc_settings' );
define( 'RS_EUCC__PLUGIN_ADMIN_URL', admin_url( 'admin.php?page='.RS_EUCC__ADMIN_PAGE ) );
define( 'RS_EUCC__PLUGIN_DIR', plugin_dir_path( __FILE__ ).RS_EUCC__BASE );
define( 'RS_EUCC__PLUGIN_DIR_NAME', end( explode( '/', dirname( __FILE__ ) ) ) );
define( 'RS_EUCC__PLUGIN_FILE', __FILE__ );
define( 'RS_EUCC__PLUGIN_ICON', 'dashicons-thumbs-up' );
define( 'RS_EUCC__PLUGIN_MENU_POS', '80.00000000000003' );
define( 'RS_EUCC__PLUGIN_NAME', 'EU Cookie Consent' );
define( 'RS_EUCC__PLUGIN_SHORT_NAME', 'Cookie Consent' );
define( 'RS_EUCC__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'RS_EUCC__PLUGIN_VERSION', '0.1.4' );

foreach( glob( RS_EUCC__PLUGIN_DIR.'*.php' ) as $file ) { require_once( $file ); }

add_action( 'admin_enqueue_scripts', 'rs_eucc_css' );
add_action( 'admin_menu', 'rs_eucc_setup_menu' );
add_action( 'init', 'rs_eucc_activate' );
add_action( 'wp_enqueue_scripts', 'rs_eucc_js_load' );
add_action( 'wp_head', 'rs_eucc_js_output' );

add_filter( 'plugin_action_links', 'rs_eucc_plugin_links', 10, 2 );

register_activation_hook( RS_EUCC__PLUGIN_FILE, 'rs_eucc_on_activation' );
register_deactivation_hook( RS_EUCC__PLUGIN_FILE, 'rs_eucc_on_deactivation' );

function rs_eucc_on_activation() { rs_eucc_activate(); }
function rs_eucc_on_deactivation() { rs_eucc_deactivate(); }