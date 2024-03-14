<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Priority_Presenter_LC_HC_MVC extends _HC_MVC
{
	public function present_options()
	{
		$return = array(
			0	=> __('Normal', 'locatoraid'),
			1	=> __('Featured', 'locatoraid'),
			-1	=> __('Draft', 'locatoraid'),
			// 2	=> __('Always Show', 'locatoraid'),
			);
		return $return;
	}
}