<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class App_Conf_Form_LC_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$return = array();
		$return = $this->app
			->after( $this, $return )
			;
		return $return;
	}
}