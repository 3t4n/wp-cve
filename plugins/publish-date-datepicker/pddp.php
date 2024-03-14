<?php
/*
Plugin Name:       Publish Date DatePicker
Plugin URI:        https://wordpress.org/plugins/publish-date-datepicker/
Description:       Publish Date DatePicker adds interactive calendar in publish section of post, page & custom post. It makes adding and changing date easier by selecting it from interactive calendar using mouse.
Version:           3.0
Author:            Vinod Dalvi
Author URI:        https://profiles.wordpress.org/vinod-dalvi/
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Domain Path:       /languages
Text Domain:       pddp

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * If this file is called directly, then abort execution.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define( 'PDDP_VERSION', '3.0' );
define( 'PDDP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

function load_pddp_admin_style_script( $hook ) {

	if ( 'post.php' != $hook && 'post-new.php' != $hook ) {
		return;
	}

	if ( apply_filters( 'add_pddp_timepicker_js', true ) ) {
		wp_enqueue_script( 'timepicker-js', PDDP_PLUGIN_URL . 'js/jquery-ui-timepicker-addon.js', array( 'jquery-ui-datepicker' ) );
	}

	if ( apply_filters( 'add_pddp_js', true ) ) {
		wp_enqueue_script( 'pddp-js', PDDP_PLUGIN_URL . 'js/pddp.js', array( 'timepicker-js' ) );
	}

	if ( apply_filters( 'add_pddp_css', true ) ) {
		wp_enqueue_style( 'pddp-css', PDDP_PLUGIN_URL . 'css/pddp.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'load_pddp_admin_style_script' );

/**
 * Add a link to the settings page to the plugins list
 *
 * @param array  $links array of links for the plugins, adapted when the current plugin is found.
 * @param string $file  the filename for the current plugin, which the filter loops through.
 *
 * @return array $links
 */
function pddp_settings_link( $links, $file ) {

	if ( false !== strpos( $file, 'pddp' ) ) {
		$mylinks = array(
			'<a href="https://wordpress.org/support/plugin/publish-date-datepicker/>' . esc_html__( 'Get Support', 'pddp' ) . '</a>'
		);

		$links = array_merge( $mylinks, $links );
	}
	return $links;
}
add_action( 'plugin_action_links', 'pddp_settings_link', 10, 2 );

/**
 * Load the plugin text domain for translation.
 *
 */
function pddp_load_plugin_textdomain() {

	load_plugin_textdomain(
		'pddp',
		false,
		dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
	);

}
add_action( 'plugins_loaded', 'pddp_load_plugin_textdomain' );