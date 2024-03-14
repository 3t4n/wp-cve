<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Coordinates_View_LC_HC_MVC extends _HC_MVC
{
	public function render( $address )
	{
		$out = $this->app->make('/html/list')
			->set_gutter(2)
			;

		$out
			->add( $address )
			;

		return $out;
	}
}