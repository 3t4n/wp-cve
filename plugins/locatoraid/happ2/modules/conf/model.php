<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Conf_Model_HC_MVC extends _HC_MVC
{
	public function save()
	{
		$return = $this;
		
		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}
}