<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Searchlog_Conf_Update_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$form = $this->app->make('/searchlog.conf/form');
		return $this->app->make('/conf/update/controller')
			->execute( $form )
			;
	}
}