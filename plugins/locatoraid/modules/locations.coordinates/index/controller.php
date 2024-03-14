<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Coordinates_Index_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute( $id )
	{
	// add javascript
		$this->app->make('/app/enqueuer')
			->register_script( 'lc-locations-coordinates', 'modules/locations.coordinates/assets/js/map.js' )
			->enqueue_script( 'lc-locations-coordinates' )
			;

		$location = $this->app->make('/locations/commands/read')
			->execute( $id )
			;

		$view = $this->app->make('/locations.coordinates/index/view')
			->render($location)
			;
		$view = $this->app->make('/locations.coordinates/index/view/layout')
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