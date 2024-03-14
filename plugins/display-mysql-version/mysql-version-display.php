<?php
/*
Plugin Name: MySQL Version Display Footer
Plugin URI: http://apasionados.es/#utm_source=wpadmin&utm_medium=plugin&utm_campaign=mysql-version-display-plugin
Description: Shows the MySQL version in the admin footer. This plugin can be used alone or together with the <a href="https://wordpress.org/plugins/server-ip-memory-usage/" target="_blank">Server IP & Memory Usage Display</a> plugin.
Version: 1.2.0
Author: Apasionados, Apasionados del Marketing
Author URI: http://apasionados.es
Text Domain: display-mysql-version

# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( is_admin() ) {	
	class mysql_version_display {
		public function __construct() {
			add_filter( 'admin_footer_text', array (&$this, 'mysql_version_display_add_footer') );
		}
		function mysql_version_display_add_footer($content) {
            $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			//$content .= ' | MySQL: ' . mysqli_get_server_info($connection) . ' | WP: ' . get_bloginfo( 'version' );
			$content .= ' | MySQL: ' . mysqli_get_server_info($connection);
			return $content;
		}
	}
	add_action( 'plugins_loaded', function() {
		$mysqlvd = new mysql_version_display();
	}, 100 );
}



/**
 * Do some check on plugin activation
 * @return void
 */
function mysql_version_display_activation() {
	if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		load_plugin_textdomain( 'display-mysql-version', false,  dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		$plugin_data = get_plugin_data( __FILE__ );
		$plugin_version = $plugin_data['Version'];
		$plugin_name = $plugin_data['Name'];
		wp_die( '<h1>' . __('Could not activate plugin: PHP version error', 'display-mysql-version' ) . '</h1><h2>PLUGIN: <i>' . $plugin_name . ' ' . $plugin_version . '</i></h2><p><strong>' . __('You are using PHP version', 'display-mysql-version' ) . ' ' . PHP_VERSION . '</strong>. ' . __( 'This plugin has been tested with PHP versions 5.3 and greater.', 'display-mysql-version' ) . '</p><p>' . __('WordPress itself <a href="https://wordpress.org/about/requirements/" target="_blank">recommends using PHP version 7 or greater</a>. Please upgrade your PHP version or contact your Server administrator.', 'display-mysql-version' ) . '</p>', __('Could not activate plugin: PHP version error', 'display-mysql-version' ), array( 'back_link' => true ) );

	}
}
register_activation_hook( __FILE__, 'mysql_version_display_activation' );

function apa_display_mysql_version_f_init() {
	load_plugin_textdomain( 'display-mysql-version', false,  dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'apa_display_mysql_version_f_init');