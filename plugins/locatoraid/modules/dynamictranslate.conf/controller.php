<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Dynamictranslate_Conf_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$view = $this->app->make('/dynamictranslate.conf/view')
			->render()
			;
		$view = $this->app->make('/conf/view/layout')
			->render( $view, 'dynamictranslate' )
			;
		$view = $this->app->make('/layout/view/body')
			->set_content($view)
			;
		return $this->app->make('/http/view/response')
			->set_view($view)
			;
	}
}