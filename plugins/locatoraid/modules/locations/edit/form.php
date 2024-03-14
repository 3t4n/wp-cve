<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Edit_Form_LC_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$return = $this->app->make('/locations/form')
			->inputs()
			;

		$return = $this->app
			->after( $this, $return )
			;

		return $return;
	}
}