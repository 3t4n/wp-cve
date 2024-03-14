<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Addon_Conf_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$view = $this->app->make('/addon.conf/view')
			->render()
			;
		$view = $this->app->make('/conf/view/layout')
			->render( $view, 'addon' )
			;
		$view = $this->app->make('/layout/view/body')
			->set_content($view)
			;
		return $this->app->make('/http/view/response')
			->set_view($view)
			;
	}
}