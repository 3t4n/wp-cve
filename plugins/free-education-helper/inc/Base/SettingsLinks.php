<?php
/**
 * @package  Free Education Helper
 */
namespace Inc\Base;

use Inc\Base\BaseController;

class SettingsLinks extends BaseController
{
	public function register() 
	{
		add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
	}

	public function settings_link( $links ) 
	{
		$settings_link = '<a href="admin.php?page=free_education_helper">Settings</a>';
		array_push( $links, $settings_link );
		return $links;
	}
}