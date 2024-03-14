<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_Fields_Update_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$form = $this->app->make('/front.conf/fields/form');
		return $this->app->make('/conf/update/controller')
			->execute( $form )
			;
	}
}