<?php

/**
 * Plugin Name: Pixobe Coloring Book
 * Description: Add coloring book application to your wordpress easily.
 * Plugin URI:        https://colorgizer.com
 * Version:           1.0.5
 * Author:            Pixobe
 * Author URI:        https://pixobe.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

/*
 Pixobe Coloring Book is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Pixobe Coloring Book is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Informative Maps. If not, see {URI to Plugin License}.
*/



if (!class_exists('PixobeColoringBook')) :

	class PixobeColoringBook
	{
		/** @var string The plugin version number. */
		var $version = '1.0.0';

		/** @var array The plugin settings array. */
		var $settings = array();

		/** @var array The plugin data array. */
		var $data = array();

		/** @var array Storage for class instances. */
		var $instances = array();

		/** @var array Prefix for variables and handles. */
		var $prefix = 'PCB';

		/**
		 * __construct
		 *
		 * A dummy constructor to ensure PixobeCartography is only setup once.
		 *
		 * @date	11/07/2020
		 * @since	1.0.0
		 *
		 * @param	void
		 * @return	void
		 */
		function __construct()
		{
			// Do nothing.
		}

		/**
		 * initialize
		 *
		 * Sets up the Pixobe Jockey plugin.
		 *
		 * @date	11/07/2020
		 * @since	1.0.0
		 *
		 * @param	void
		 * @return	void
		 */
		function initialize()
		{
			define('PCB', true);
			define('PCB_BASENAME', plugin_basename(__FILE__));
			define('PCB_PATH', plugin_dir_path(__FILE__));
			define('PCB_VERSION', $this->version);
			define('PCB_MAJOR_VERSION', 1);

			$this->settings = array(
				'name'						=> __('Coloring Book', 'PCB_plugin_name'),
				'page_title'				=> __('Coloring Book', 'PCB_page_tile'),
				'shortname' 				=> $this->prefix,
				'slug'						=> dirname(PCB_BASENAME),
				'version'					=> PCB_VERSION,
				'basename'					=> PCB_BASENAME,
				'path'						=> PCB_PATH,
				'file'						=> __FILE__,
				'url'						=> plugin_dir_url(__FILE__),
				'show_admin'				=> true,
				'show_updates'				=> true,
				'scripts_path'				=> 'public/colorgizer',
				'css_handle'				=> $this->prefix . "_admin_styles",
				'top_level_menu'			=> 'toplevel_page_pixobe-coloring-book',
			);

			//add option version
			add_option($this->get_setting("slug"), $this->get_setting("version"));
			
			// add application scripts
			add_action('wp_enqueue_scripts', array($this, 'add_application_scripts'), 5);

		}
		
		/**
		 * register application scripts scripts
		 *
		 * @date	11/08/2020
		 * @since	1.0.0
		 *
		 * @param	void
		 * @return	void
		 */
		function add_application_scripts()
		{
			$script_path = $this->get_setting('url') . $this->get_setting('scripts_path');
			$scripts = [
				[
					'key' => "$this->get_setting('basename')",
					'script' => 'bundle.js'
				]
			];

			foreach ($scripts as $key => $value) {
				if ($key == 0) {
					$previous_key = "jquery";
				} else {
					$previous_key = $scripts[$key - 1]['key'];
				}
				wp_enqueue_script($value['key'], $this->get_resource_path($script_path, $value['script']), array($previous_key), $this->version, true);
			}
			// register styles
		}
 

		/**
		 * get_setting
		 *
		 * Returns a setting or null if doesn't exist.
		 *
		 * @date	11/08/2020
		 * @since	1.0.0
		 *
		 * @param	string $name The setting name.
		 * @return	mixed
		 */
		function get_setting($name)
		{
			return isset($this->settings[$name]) ? $this->settings[$name] : null;
		}

		/**
		 * Joins the path
		 * 
		 * @param base_path
		 * @param name
		 */
		function get_resource_path($base_path, $name)
		{
			return join(DIRECTORY_SEPARATOR, array($base_path, $name));
		}
	}

	function pixobe_coloring_book()
	{
		global $PCB;
		// Instantiate only once.
		if (!isset($PCB)) {
			$PCB = new PixobeColoringBook();
			$PCB->initialize();
		}
		return $PCB;
	}

	pixobe_coloring_book();

endif;

add_shortcode( 'pixobecoloringbook', 'pixobe_coloringbook_shortcut_fn' );
function pixobe_coloringbook_shortcut_fn( $atts, $content = "" ) {
	$src = $atts['src'];
    return "<pixobe-coloring-book src=$src></pixobe-coloring-book>";
}