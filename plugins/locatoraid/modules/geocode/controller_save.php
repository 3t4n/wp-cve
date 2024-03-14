<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Geocode_Controller_Save_LC_HC_MVC extends _HC_MVC
{
	public function execute( $id, $latitude, $longitude )
	{
		if( ! ($id && $latitude && $longitude) ){
			echo "id, latitude, longitude required";
			echo $this->app->make('/http/view/response')
				->set_status_code(500) 
				;
			exit;
		}

		$values = array(
			'latitude'	=> $latitude,
			'longitude'	=> $longitude,
			);

		$cm = $this->app->make('/commands/manager');

		$command = $this->app->make('/locations/commands/update');
		$command
			->execute( $id, $values )
			;

		$errors = $cm->errors( $command );

		if( $errors ){
			$session = $this->app->make('/session/lib');
			$session
				->set_flashdata('error', $errors)
				;
			return $this->app->make('/http/view/response')
				->set_redirect('-referrer-') 
				;
		}

	// OK
		return $this->app->make('/http/view/response')
			->set_redirect('-referrer-') 
			;
	}
}