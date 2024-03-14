<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Searchlog_Conf_Form_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$return = array();

		$options = array(
			7*24*60*60	=> __('1 Week', 'locatoraid'),
			2*7*24*60*60	=> __('2 Weeks', 'locatoraid'),
			4*7*24*60*60	=> __('4 Weeks', 'locatoraid'),
			8*7*24*60*60	=> __('8 Weeks', 'locatoraid'),
			);

		$return['searchlog:period'] = array(
			'input'	=> $this->app->make('/form/select')
				->set_options( $options )
				,
			'label'	=> __('Log Searches', 'locatoraid'),
			);

		return $return;
	}
}