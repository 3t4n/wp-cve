<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
// Is a Natural number (0,1,2,3, etc.)
class Validate_Natural_HC_MVC extends _HC_MVC
{
	protected $msg;
	protected $min = 1;
	protected $max = 100;

	public function _init()
	{
		$this->msg = __('This field must contain only positive numbers.', 'locatoraid');
		return $this;
	}

	public function set_min( $min )
	{
		$this->min = $min;
		return $this;
	}
	public function set_max( $max )
	{
		$this->max = $max;
		return $this;
	}

	public function validate( $value )
	{
		$return = (bool) preg_match( '/^[0-9]+$/', $value);
		if( ! $return ){
			$return = $this->msg;
		}
		return $return;
	}

	public function render( $return )
	{
		$return
			->reset_attr('type')
			;

		$return
			->add_attr('type', 'number')
			->add_attr('min', $this->min)
			->add_attr('step', 1)
			->add_attr('pattern', '\d+')
			->add_attr('oninvalid', "this.setCustomValidity('" . addslashes($this->msg) . "')")
			;
		
		if( $this->max ){
			$return
				->add_attr('max', $this->max)
				;
		}
		
		return $return;
	}
}