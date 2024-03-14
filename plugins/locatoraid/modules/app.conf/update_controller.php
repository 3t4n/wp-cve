<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class App_Conf_Update_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$form = $this->app->make('/app.conf/form');
		return $this->app->make('/conf/update/controller')
			->execute( $form )
			;
	}
}