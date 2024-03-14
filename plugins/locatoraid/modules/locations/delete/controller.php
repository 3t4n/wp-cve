<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Delete_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute( $id )
	{
		$command = $this->app->make('/locations/commands/delete');
		$response = $command
			->execute( $id )
			;

		if( isset($response['errors']) ){
			echo $response['errors'];
			exit;
		}

	// OK
		$redirect_to = $this->app->make('/http/uri')
			->url('/locations')
			;
		return $this->app->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}