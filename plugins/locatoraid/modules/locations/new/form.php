<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_New_Form_LC_HC_MVC extends _HC_MVC
{
	public function conf()
	{
		$return = $this->app->make('/locations/form')
			->conf()
			;

		$return = $this->app
			->after( $this, $return )
			;

		return $return;
	}
}