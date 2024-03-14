<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Validate_Email_HC_MVC extends _HC_MVC
{
	public function validate( $value )
	{
		$msg = __('Valid email address required', 'locatoraid');
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value)) ? $msg : TRUE;
	}

	public function render( $return )
	{
		$return
			->reset_attr('type')
			->add_attr('type', 'email')
			;
		return $return;
	}
}