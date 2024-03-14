<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_Text_Form_LC_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$return = array(
			'front_text:submit_button'	=> array(
				'input'		=> $this->app->make('/form/text'),
				'label'		=> __('Submit Button', 'locatoraid'),
				'validators' => array(
					$this->app->make('/validate/required'),
					),
				'help'		=> __('Search', 'locatoraid'),
				),

			'front_text:search_field'	=> array(
				'input'		=> $this->app->make('/form/text'),
				'label'		=> __('Search Field', 'locatoraid'),
				'validators' => array(
					$this->app->make('/validate/required'),
					),
				'help'		=> __('Address or Zip Code', 'locatoraid'),
				),

			'front_text:more_results'	=> array(
				'input'		=> $this->app->make('/form/text'),
				'label'		=> __('More Results Link', 'locatoraid'),
				'validators' => array(
					$this->app->make('/validate/required'),
					),
				'help'		=> __('More Results', 'locatoraid'),
				),

			'front_text:no_results'	=> array(
				'input'		=> $this->app->make('/form/textarea'),
				'label'		=> __('No Results', 'locatoraid'),
				'validators' => array(
					$this->app->make('/validate/required'),
					),
				'help'		=> __('No Results', 'locatoraid'),
				),

			'front_text:locate_me'	=> array(
				'input'		=> $this->app->make('/form/text'),
				'label'		=> __('Locate Me', 'locatoraid'),
				'validators' => array(
					$this->app->make('/validate/required'),
					),
				'help'		=> __('Locate Me', 'locatoraid'),
				),

			'front_text:my_location'	=> array(
				'input'		=> $this->app->make('/form/text'),
				'label'		=> __('My Location', 'locatoraid'),
				'validators' => array(
					$this->app->make('/validate/required'),
					),
				'help'		=> __('My Location', 'locatoraid'),
				),

			'front_text:reset_my_location'	=> array(
				'input'		=> $this->app->make('/form/text'),
				'label'		=> __('Reset My Location', 'locatoraid'),
				'validators' => array(
					$this->app->make('/validate/required'),
					),
				'help'		=> __('Reset', 'locatoraid'),
				),
		);

		return $return;
	}
}