<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_Wordpress_Index_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$entries = $this->app->make('/users/commands/read')
			->execute()
			;

		$view = $this->app->make('/users.wordpress/index/view')
			->render($entries)
			;
		$view = $this->app->make('/users.wordpress/index/view/layout')
			->render($view)
			;

		$view = $this->app->make('/conf/view/layout')
			->render( $view, 'users' )
			;

		$view = $this->app->make('/layout/view/body')
			->set_content($view)
			;
		return $this->app->make('/http/view/response')
			->set_view($view)
			;
	}
}