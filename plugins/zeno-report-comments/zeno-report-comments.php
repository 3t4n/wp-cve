<?php
/*
Plugin Name: Zeno Report Comments
Plugin Script: zeno-report-comments.php
Plugin URI: https://wordpress.org/plugins/zeno-report-comments/
Description: This script gives visitors the possibility to flag/report a comment as inapproriate.
After reaching a threshold the comment is moved to moderation. If a comment is approved once by a moderator future reports will be ignored.
Version: 2.1.0
Author: Marcel Pol
Author URI: https://timelord.nl
Text Domain: zeno-report-comments
Domain Path: /lang/
Forked from: https://wordpress.org/plugins/safe-report-comments/


Copyright 2010 - 2016  Thorsten Ott
Copyright 2012 - 2013  Daniel Bachhuber
Copyright 2014 - 2014  Mohammad Jangda
Copyright 2015 - 2015  Ronald Huereca, Jason Lemieux (Postmatic)
Copyright 2016 - 2024  Marcel Pol  (marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/*
 * Todo:
 *
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// Plugin Version
define( 'ZENORC_VER', '2.1.0' );

/*
 * Definitions
 */
define('ZENORC_FOLDER', plugin_basename(dirname(__FILE__)));
define('ZENORC_DIR', WP_PLUGIN_DIR . '/' . ZENORC_FOLDER);
define('ZENORC_URL', plugins_url( '/', __FILE__ ));


require_once ZENORC_DIR . '/general-functions.php';
require_once ZENORC_DIR . '/frontend-hooks.php';

// Functions for the backend.
if ( is_admin() ) {
	require_once ZENORC_DIR . '/admin-hooks.php';
}
