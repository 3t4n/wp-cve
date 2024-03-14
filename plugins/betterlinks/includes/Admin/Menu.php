<?php

namespace BetterLinks\Admin;

use BetterLinks\Helper;

class Menu
{
	/**
	 * add plugin menu page and submenu pages
	 */
	public function __construct()
	{
		add_action('admin_menu', [$this, 'admin_menu']);
	}

	/**
	 * add admin menu page
	 * @return hooks
	 */
	public function admin_menu()
	{
		add_menu_page(
			'BetterLinks',
			sprintf('BetterLinks %1$s', Helper::btl_menu_notice() ? '<span class="btl-menu-notice">1</span>' : ''),
			apply_filters('betterlinks/admin/menu_capability', 'manage_options'),
			BETTERLINKS_PLUGIN_SLUG,
			[$this, 'load_main_template'],
			BETTERLINKS_ASSETS_URI . 'images/logo.svg',
			30
		);
		foreach (Helper::get_menu_items() as $key => $item) {
			add_submenu_page(BETTERLINKS_PLUGIN_SLUG, $item['title'], $item['title'],  apply_filters("betterlinks/admin/".$key."_menu_capability", $item['capability']), $key, [$this, 'load_main_template']);
		}
	}
	public function load_main_template()
	{
		echo '<div id="betterlinksbody" class="betterlinks-body"></div>';
	}
}
