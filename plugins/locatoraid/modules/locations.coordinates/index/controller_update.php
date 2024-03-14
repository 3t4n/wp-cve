<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Coordinates_Index_Controller_Update_LC_HC_MVC extends _HC_MVC
{
	public function execute( $id )
	{
		$post = $this->app->make('/input/lib')->post();

		$inputs = $this->app->make('/locations.coordinates/form')
			->inputs()
			;
		$helper = $this->app->make('/form/helper');

		list( $values, $errors ) = $helper->grab( $inputs, $post );

		if( $errors ){
			return $this->app->make('/http/view/response')
				->set_redirect('-referrer-') 
				;
		}

	/* API */
		$response = $this->app->make('/locations/commands/update')
			->execute( $id, $values )
			;

		if( isset($response['errors']) ){
			$session = $this->app->make('/session/lib');
			$session
				->set_flashdata('error', $response['errors'])
				;
			return $this->app->make('/http/view/response')
				->set_redirect('-referrer-') 
				;
		}

	// OK
		$redirect_to = $this->app->make('/http/uri')
			->url('/locations/' . $id . '/edit')
			;
		return $this->app->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}