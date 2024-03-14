<?php
/**
 * Plugin Name: No Gutenberg - Disable Blocks Editor and Global Styles - Back to Classic Editor
 * Plugin URI: https://servicios.ayudawp.com/
 * Description: Don't you want the new Gutenberg Block editor bundled with WordPress 5.x for compatibility reasons and FSE Global Styles for optimization? Simply get rid of them! Install this plugin prior update to WordPress 5.x, activate and … That's all!
 * Version: 1.0.7
 * Author: Fernando Tellado
 * Author URI: https://tellado.es/
 *
 * @package No Gutenberg
 * License: GPL2+
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: no-gutenberg
 *
 * No Gutenberg plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * No Gutenberg plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with No Gutenberg. If not, see https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/* INIT FOR TRANSLATION READY */
function no_gutenberg_init() {
	load_plugin_textdomain( 'no-gutenberg', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'no_gutenberg_init' );
/* THE NO GUTEMBERG KILLER FILTER
* The plugin checks if gutenberg editor is activated, what version is active
* and if it's true then deactivate with return_false
*/
if (version_compare($GLOBALS['wp_version'], '5.0-beta', '>')) {

	// WP > 5 beta
	add_filter('use_block_editor_for_post_type', '__return_false', 100);

} else {

	// WP < 5 beta
	add_filter('gutenberg_can_edit_post_type', '__return_false');

}
/* THE NO GUTENBERG CALLOUT FILTER
* The filter disables the callout to try Gutenberg Dashboard widget
*/
remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );

/* THE NO GUTENBERG GLOBAL STYLES KILLER */
add_action( 'wp_enqueue_scripts', 'no_gutenberg_remove_global_styles' );
function no_gutenberg_remove_global_styles(){
wp_dequeue_style( 'global-styles' );
}