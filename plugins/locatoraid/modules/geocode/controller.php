<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Geocode_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute( $id )
	{
	// add javascript
		$this->app->make('/app/enqueuer')
			->register_script('lc-geocode', 'modules/geocode/assets/js/geocode.js')
			->enqueue_script('lc-geocode')
			;

		$location = $this->app->make('/locations/commands/read')
			->execute( $id )
			;

		$view = $this->app->make('/geocode/view')
			->render($location)
			;
		$view = $this->app->make('/geocode/view/layout')
			->render($view, $location)
			;
		$view = $this->app->make('/layout/view/body')
			->set_content($view)
			;
		return $this->app->make('/http/view/response')
			->set_view($view) 
			;
	}
}