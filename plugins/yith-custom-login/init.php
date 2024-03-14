<?php
/**
 * Plugin Name: YITH Custom Login
 * Plugin URI: https://yithemes.com/themes/plugins/yith-custom-login/
 * Description: <code><strong>YITH Custom Login</strong></code> allows you to customize the login and register WordPress pages.
 * Version: 1.6.0
 * Author: YITH <plugins@yithemes.com>
 * Author URI: https://yithemes.com/
 * Text Domain: yith-custom-login
 * Domain Path: /languages/
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH Custom Login
 * @version 1.6.0
 */
/*  Copyright 2013-2023  YITH  (email : plugins@yithemes.com)

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
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/* Include common functions */
if( !defined('YITH_FUNCTIONS') ) {
	require_once( 'yit-common/yit-functions.php' );
}

load_plugin_textdomain( 'yith-custom-login', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

define( 'YITH_LOGIN', true );
define( 'YITH_LOGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'YITH_LOGIN_DIR', plugin_dir_path( __FILE__ ) );

// Load required classes and functions
require_once('functions.yith-login.php');
require_once('yith-login-options.php');
require_once('class.yith-login-admin.php');
require_once('class.yith-login-frontend.php');
require_once('class.yith-login.php');

// Let's start the game!
global $yith_login;
$yith_login = new YITH_Login();