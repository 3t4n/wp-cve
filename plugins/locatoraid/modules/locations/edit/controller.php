<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Edit_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute( $id )
	{
		$args = array();
		$args[] = $id;
		$args[] = array('with', '-all-', 'flat');

		$model = $this->app->make('/locations/commands/read')
			->execute( $args )
			;

		$view = $this->app->make('/locations/edit/view')
			->render($model)
			;
		$view = $this->app->make('/locations/edit/view/layout')
			->render($view, $model)
			;
		$view = $this->app->make('/layout/view/body')
			->set_content($view)
			;
		return $this->app->make('/http/view/response')
			->set_view($view) 
			;
	}
}