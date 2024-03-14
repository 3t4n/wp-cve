<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_New_Controller_Add_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$post = $this->app->make('/input/lib')->post();

		$inputs = $this->app->make('/locations/form')
			->inputs()
			;
		$helper = $this->app->make('/form/helper');

		list( $values, $errors ) = $helper->grab( $inputs, $post );

		if( $errors ){
			return $this->app->make('/http/view/response')
				->set_redirect('-referrer-') 
				;
		}

		$cm = $this->app->make('/commands/manager');

		$command = $this->app->make('/locations/commands/create');
		$command
			->execute( $values )
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

		$results = $cm->results( $command );

	// OK
		$redirect_to = $this->app->make('/http/uri')
			->url('/locations/' . $results['id'] . '/edit')
			;
		return $this->app->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}