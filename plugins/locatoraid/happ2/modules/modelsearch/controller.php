<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class ModelSearch_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$post = $this->app->make('/input/lib')->post();

		$inputs = $this->app->make('/modelsearch/form')
			->inputs()
			;
		$helper = $this->app->make('/form/helper');

		list( $values, $errors ) = $helper->grab( $inputs, $post );

		$redirect_to = $this->app->make('/http/uri')
			->url('-referrer-', array('search' => $values['search'], 'page' => NULL) )
			;
		return $this->app->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}