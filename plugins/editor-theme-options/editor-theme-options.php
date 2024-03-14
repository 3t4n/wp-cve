<?php
/**
 * Editor Theme Options
 *
 * Allow editors to access theme options in the Appearance menu.
 *
 * @package   Editor_Theme_Options
 * @author    Cliff Seal <cliff@logoscreative.co>
 * @license   GPL-2.0+
 * @link      http://logoscreative.co
 * @copyright 2014 Logos Creative
 *
 * @wordpress-plugin
 * Plugin Name:       Editor Theme Options
 * Plugin URI:        http://logoscreative.co/editor-theme-options
 * Description:       Allow editors to access theme options in the Appearance menu.
 * Version:           1.0.0
 * Author:            Cliff Seal
 * Author URI:        http://logoscreative.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/logoscreative/editor-theme-options
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-editor-theme-options.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Editor_Theme_Options', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Editor_Theme_Options', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Editor_Theme_Options', 'get_instance' ) );