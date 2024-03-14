<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Coordinates_Index_Controller_Reset_LC_HC_MVC extends _HC_MVC
{
	public function execute( $id )
	{
	/* API */
		$values = array(
			'latitude'	=> NULL, 
			'longitude'	=> NULL,
			);

		$return = $this->app->make('/locations/commands/update')
			->execute( $id, $values )
			;

		if( isset($return['errors']) ){
			$errors = $return['errors'];

			$session = $this->app->make('/session/lib');
			$session
				->set_flashdata('errors', $errors)
				;

			return $this->app->make('/http/view/response')
				->set_redirect('-referrer-') 
				;
		}

		$redirect_to = $this->app->make('/http/uri')
			->url('/locations/' . $id . '/edit')
			;
		return $this->app->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}