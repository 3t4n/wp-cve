<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Conf_Update_Controller_HC_MVC extends _HC_MVC
{
	function execute( $form )
	{
		$post = $this->app->make('/input/lib')->post();

		$inputs = $form->inputs();
		$helper = $this->app->make('/form/helper');
		list( $values, $errors ) = $helper->grab( $inputs, $post );

		if( $errors ){
			$redirect_to = $this->app->make('/http/uri')
				->url('-referrer-')
				;
			return $this->app->make('/http/view/response')
				->set_redirect($redirect_to) 
				;
		}

	/* run */
		$response = $this->app->make('/conf/commands/update')
			->execute( $values )
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
		$this->app->make('/session/lib')
			->set_flashdata('form_errors', array())
			->set_flashdata('form_values', array())
			;
		return $this->app->make('/http/view/response')
			->set_redirect('-referrer-') 
			;
	}
}