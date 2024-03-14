<?php

namespace MasterAddons\Lib;

// No, Direct access Sir !!!
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Featured')) {

	/**
	 * Featured global class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 */
	class Featured
	{

		/**
		 * Constructor
		 */
		public function __construct()
		{
			if (is_admin()) {
				add_filter('install_plugins_table_api_args_featured', array($this, 'jltma_featured_plugins_tab'));
			}
		}

		/**
		 * Helper function for adding plugins to fav list.
		 *
		 * @param [type] $args .
		 */
		public function jltma_featured_plugins_tab($args)
		{
			add_filter('plugins_api_result', array($this, 'jltma_plugins_api_result'), 10, 3);

			return $args;
		}

		/**
		 * Add our plugins to recommended list.
		 *
		 * @param [type] $res .
		 * @param [type] $action .
		 * @param [type] $args .
		 */
		public function jltma_plugins_api_result($res, $action, $args)
		{
			remove_filter('plugins_api_result', array($this, 'jltma_plugins_api_result'), 10, 1);

			// Plugin list which you want to show as feature in dashboard.
			$res = $this->jltma_add_plugin_favs('image-hover-effects-elementor-addon', $res);
			$res = $this->jltma_add_plugin_favs('adminify', $res);
			$res = $this->jltma_add_plugin_favs('ultimate-blocks-for-gutenberg', $res);
			$res = $this->jltma_add_plugin_favs('darken', $res);
			$res = $this->jltma_add_plugin_favs('copy-to-clipboard', $res);

			return $res;
		}

		/**
		 * Add single plugin to list of favs.
		 *
		 * @param [type] $plugin_slug .
		 * @param [type] $res .
		 */
		public function jltma_add_plugin_favs($plugin_slug, $res)
		{
			if (!empty($res->plugins) && is_array($res->plugins)) {
				foreach ($res->plugins as $plugin) {
					if (is_object($plugin) && !empty($plugin->slug) && $plugin_slug === $plugin->slug) {
						return $res;
					}
				} // foreach
			}

			$plugin_info = new \stdClass();
			if (get_transient('jltma-plugin-info-' . $plugin_slug == $plugin_info)) {
				array_unshift($res->plugins, $plugin_info);
			} else {
				$plugin_info = plugins_api(
					'plugin_information',
					array(
						'slug'   => $plugin_slug,
						'is_ssl' => is_ssl(),
						'fields' => array(
							'banners'           => true,
							'reviews'           => true,
							'downloaded'        => true,
							'active_installs'   => true,
							'icons'             => true,
							'short_description' => true,
						),
					)
				);

				if (!is_wp_error($plugin_info)) {
					$res->plugins[] = $plugin_info;
					set_transient('jltma-plugin-info-' . $plugin_slug, $plugin_info, DAY_IN_SECONDS * 7);
				}
			}

			return $res;
		}
	}

	new Featured();
}
