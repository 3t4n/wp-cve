<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class ModelSearch_Form_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$return = array();

		$return['search'] = $this->app->make('/form/text')
			->set_size(16)
			;

		return $return;
	}
}