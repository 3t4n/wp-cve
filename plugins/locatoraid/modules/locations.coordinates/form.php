<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Coordinates_Form_LC_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$return = array(
			'latitude'	=> array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> __('Latitude', 'locatoraid')
				),

			'longitude'	=> array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> __('Longitude', 'locatoraid')
				),
			);
		return $return;
	}
}