<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Setup_Wordpress_Index_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$view = $this->app->make('/setup.wordpress/index/view')
			->render()
			;

		$view = $this->app->make('/layout/view/body')
			->set_content($view)
			;
		return $this->app->make('/http/view/response')
			->set_view($view)
			;
	}
}