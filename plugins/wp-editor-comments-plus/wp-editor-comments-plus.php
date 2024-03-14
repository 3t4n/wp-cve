<?php
/**
 * WP Editor Comments Plus
 *
 * Enhance WordPress comments with the TinyMCE Editor, Inline Comment Editing and Asynchronous Posting.
 *
 * @package   wp-editor-comments-plus
 * @author    Neo Snc <neosnc1@gmail.com>
 * @license   GPL-2.0+
 * @link      https://wordpress.org/plugins/wp-editor-comments-plus/
 * @copyright 4-29-2016 Neo Snc
 *
 * @wordpress-plugin
 * Plugin Name: WP Editor Comments Plus
 * Plugin URI:  https://wordpress.org/plugins/wp-editor-comments-plus/
 * Description: Enhance WordPress comments with the TinyMCE Editor, Inline Comment Editing and Asynchronous Posting.
 * Version:     1.1.4
 * Author:      Neo Snc
 * Author URI:  https://wordpress.org/plugins/wp-editor-comments-plus/
 * Text Domain: wp-editor-comments-plus-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( "WPINC" ) ) {
	die;
}

// if ( ! class_exists( 'FirePHP' ) ) { require( 'fb.php' ); }

define( 'wpecp_plugin_dir', plugin_dir_path( __FILE__ ) );
define( 'wpecp_plugin_file', plugin_basename( __FILE__ ) );

require_once( wpecp_plugin_dir . "WPEditorCommentsPlus.php");

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook(__FILE__, array("WPEditorCommentsPlus", "activate"));
register_deactivation_hook(__FILE__, array("WPEditorCommentsPlus", "deactivate"));


WPEditorCommentsPlus::get_instance();
