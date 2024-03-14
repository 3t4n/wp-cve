<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Extensions;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Interface Extension_Interface
 * @package Vimeotheque\Extensions
 * @since 2.1
 * @ignore
 */
interface Extension_Interface {
	/**
	 * Get extension slug. Includes folder name and main plugin file ( ie. my-plugin/main.php )
	 *
	 * @return string
	 */
	public function get_slug();

	/**
	 * Returns plugin file relative path
	 *
	 * @return string
	 */
	public function get_file();

	/**
	 * Is add-on a PRO add-on or free
	 *
	 * @return bool
	 */
	public function is_pro_addon();

	/**
	 * Checks whether the plugin is currently installed or not
	 *
	 * @return bool
	 */
	public function is_installed();

	/**
	 * Checks whether the plugin is currently activated or not
	 *
	 * @return bool
	 */
	public function is_activated();

	/**
	 * Returns the plugin name
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Returns the plugin description
	 *
	 * @return string
	 */
	public function get_description();

	/**
	 * Returns plugin data from main plugin file
	 *
	 * @return mixed
	 */
	public function get_plugin_data();

	/**
	 * Returns WordPress activation URL for the extension
	 *
	 * @return string
	 */
	public function activation_url();

	/**
	 * Returns WordPress deactivation URL for the extension
	 *
	 * @return string
	 */
	public function deactivation_url();

	/**
	 * Returns WordPress installation URL for the extension
	 *
	 * @return string
	 */
	public function install_url();

	/**
	 * Returns WordPress upgrade URL for the extension
	 *
	 * @return string
	 */
	public function upgrade_url();

	/**
	 * Get the file ID on repository
	 *
	 * @return false|int
	 */
	public function get_file_id();
}