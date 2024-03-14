<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Select_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	protected $options = array();

	public function set_options( $options )
	{
		$this->options = $options;
		return $this;
	}

	public function options()
	{
		return $this->options;
	}

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

		$out = $this->app->make('/html/element')->tag('select')
			->add_attr('name', $name )
			->add_attr('class', 'hc-field')
			;

		$options = $this->options();
		foreach( $options as $k => $v ){
			if( ! strlen($k) ){
				$k = ' ';
			}

			$this_input = $this->app->make('/html/element')->tag('option')
				->add_attr('value', $k )
				;

			if( ($value !== NULL) && ($value == $k) ){
				$this_input
					->add_attr('selected', 'selected')
					;
			}

			$this_input
				->add( $v )
				;

			$out
				->add( $this_input )
				;
		}

		return $out;
	}
}