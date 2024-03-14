<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class GeocodeBulk_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$total_count = $this->app->make('/locations/commands/read')
			->execute(
				array(
					'count',
					array( 'latitude', '=', NULL ),
					array( 'longitude', '=', NULL )
					)
				);
		$total_count2 = $this->app->make('/locations/commands/read')
			->execute(
				array(
					'count',
					array( 'latitude', '=', '0' ),
					array( 'longitude', '=', '0' )
					)
				);
		$total_count += $total_count2;

		if( $total_count ){
		// add javascript
			$this->app->make('/app/enqueuer')
				->register_script('lc-geocodebulk', 'modules/geocodebulk/assets/js/geocode.js')
				->enqueue_script('lc-geocodebulk')
				;
		}

		$view = $this->app->make('/geocodebulk/view')
			->render($total_count)
			;
		$view = $this->app->make('/geocodebulk/view/layout')
			->render($view, $total_count)
			;
		$view = $this->app->make('/layout/view/body')
			->set_content($view)
			;
		return $this->app->make('/http/view/response')
			->set_view($view) 
			;
	}
}