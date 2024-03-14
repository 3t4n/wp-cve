<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Edit_Controller_Update_LC_HC_MVC extends _HC_MVC
{
	public function execute( $id )
	{
		$post = $this->app->make('/input/lib')->post();

		$inputs = $this->app->make('/locations/edit/form')
			->inputs()
			;
		$helper = $this->app->make('/form/helper');

		list( $values, $errors ) = $helper->grab( $inputs, $post );

		if( $errors ){
			return $this->app->make('/http/view/response')
				->set_redirect('-referrer-') 
				;
		}

		$values['id'] = $id;

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