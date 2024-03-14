<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Addon_Conf_Update_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$form = $this->app->make('/addon.conf/form');
		return $this->app->make('/conf/update/controller')
			->execute( $form )
			;
	}
}