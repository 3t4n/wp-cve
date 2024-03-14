<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Hidden_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	public function grab( $name, $post )
	{
		$return = $this->app->make('/form/input')
			->grab($name, $post)
			;
		return $return;
	}

	public function render( $name, $value = NULL )
	{
		$name = $this->app->make('/form/input')->name($name);

		$out = $this->app->make('/html/element')->tag('input')
			->add_attr('type', 'hidden' )
			->add_attr('name', $name )
			;

		if( $value !== NULL ){
			$out
				->add_attr('value', $value )
				;
		}

		return $out;
	}
}