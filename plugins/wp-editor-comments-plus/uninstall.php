<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   tinymce-comments-plus
 * @author    Neo Snc <neosnc1@gmail.com>
 * @license   GPL-2.0+
 * @link      https://wordpress.org/plugins/wp-editor-comments-plus/
 * @copyright 3-22-2015 Neo Snc
 */

// If uninstall, not called from WordPress, then exit
if (!defined("WP_UNINSTALL_PLUGIN")) {
	exit;
}
