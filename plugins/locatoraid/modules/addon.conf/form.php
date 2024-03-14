<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Addon_Conf_Form_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$ret = array();

		$on = true;
		$ret['addon:zipcode:nl'] = array(
			'input'	=> $this->app->make('/form/checkbox'),
			'label'	=> __('Zip Code Database Netherlands', 'locatoraid'),
			);

		return $ret;
	}
}