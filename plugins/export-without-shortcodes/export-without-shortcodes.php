<?php
/*
Plugin Name: Export Without Shortcodes
Description: During the exporting process it converts the shortcodes to HTML.
Author: Jose Mortellaro
Author URI: https://josemortellaro.com
Domain Path: /languages/
Version: 0.0.3
*/
/*  This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

//Definitions
define( 'EOS_EWS_PLUGIN_DIR',untrailingslashit( dirname( __FILE__ ) ) );
define( 'EOS_EWS_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

is_admin() && require_once EOS_EWS_PLUGIN_DIR.'/inc/ews-admin.php';
