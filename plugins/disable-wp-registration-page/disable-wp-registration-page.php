<?php
/**
 * Plugin Name: Disable WP Registration Page
 * Plugin URI: https://wordpress.org/plugins/disable-wp-registration-page/
 * Description: Disable default WP registration page. If somehow you still want to accept user registration but disable default registration page to prevent bot registration or something like that, this plugin is for you.
 * Version: 1.0.2
 * Author: Yudhistira Mauris
 * Author URI: http://www.yudhistiramauris.com/
 * Text Domain: dwprp
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: languages
 */

/**
 * 	Copyright © 2015 Yudhistira Mauris (email: mauris@yudhistiramauris.com)
 *
 *	This program is free software; you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License, version 2, as 
 *	published by the Free Software Foundation.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/****************** Setup Globals and Constants ************************/

if ( ! defined( 'DWPRP_VERSION' ) ) {
	define( 'DWPRP_VERSION', '1.0.2' );
}

if ( ! defined( 'DWPRP_DIR' ) ) {
	define( 'DWPRP_DIR', plugin_dir_path( __FILE__ ) );
}

/************************ Includes *************************************/

require_once DWPRP_DIR . 'includes/disable-registration-page.php';