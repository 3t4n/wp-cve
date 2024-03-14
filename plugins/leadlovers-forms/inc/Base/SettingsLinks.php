<?php
/**
 * @package  LeadloversPlugin
 */
namespace LeadloversInc\Base;

use LeadloversInc\Base\BaseController;

class SettingsLinks extends BaseController
{
	public function register() 
	{
		add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
	}

	public function settings_link( $links ) 
	{
		$settings_link = '<a href="admin.php?page=leadlovers_plugin">Configurações</a>';
		array_push( $links, $settings_link );
		return $links;
	}
}