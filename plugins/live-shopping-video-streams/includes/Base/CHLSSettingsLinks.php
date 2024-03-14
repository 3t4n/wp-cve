<?php

/**
 * @package  channelize Shopping
 */

namespace Includes\Base;

defined('ABSPATH') || exit;

class CHLSSettingsLinks
{
	protected $plugin;

	public function __construct()
	{
		$this->plugin = CHLS_PLUGIN;
	}

	public function register()
	{
		add_filter("plugin_action_links_$this->plugin", array($this, 'settings_link'));
	}

	public function settings_link($links)
	{
		$settings_link = '<a href="admin.php?page=channelize_live_shopping">Configuration</a>';
		array_push($links, $settings_link);
		return $links;
	}
}
