<?php
/**
 * Plugin Name:       Clearscope
 * Description:       Optimize your content using Clearscope report recommendations while leveraging the formatting and publishing power of Wordpress.
 * Author:            Clearscope Team
 * Author URI:        https://www.clearscope.io/
 * Version:           2.0.4
 * Requires at least: 5.0
 * Requires PHP:      5.4
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 *                    This program is free software: you can redistribute it and/or modify
 *                    it under the terms of the GNU General Public License as published by
 *                    the Free Software Foundation, either version 3 of the License, or
 *                    (at your option) any later version.
 *
 *                    This program is distributed in the hope that it will be useful,
 *                    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *                    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *                    GNU General Public License for more details.
 *
 *                    You should have received a copy of the GNU General Public License
 *                    along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

$clearscope_plugin_version = '2.0.4';

if ( ! defined( 'CLEARSCOPE_MAIN_FILE' ) ) {
	define( 'CLEARSCOPE_MAIN_FILE', __FILE__ );
}

require_once dirname( CLEARSCOPE_MAIN_FILE ) . '/clearscope-plugin.php';

new Clearscope_Plugin();
