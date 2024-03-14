<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Radio_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
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

		$out = $this->app->make('/html/list-inline')
			->set_gutter(2)
			// ->set_mobile(TRUE)
			;

		$id = 'hc2_' . hc_random();
		$options = $this->options();

		if( $value === NULL ){
			$options_keys = array_keys($options);
			$value = array_shift( $options_keys );
		}

		foreach( $options as $k => $v ){
			$this_id = $id . '_' . $k;

			$this_input = $this->app->make('/html/element')->tag('input')
				->add_attr('type', 'radio' )
				->add_attr('name', $name )
				->add_attr('value', $k )
				->add_attr('id', $this_id )
				;

			if( ($value !== NULL) && ($value == $k) ){
				$this_input
					->add_attr('checked', 'checked')
					;
			}

			$this_label = $this->app->make('/html/element')->tag('label')
				->add_attr('for', $this_id )
				->add( $v )
				; 

			$this_out = $this->app->make('/html/list-inline')
				->set_gutter(0)
				->set_mobile(TRUE)
				;

			$this_out
				->add( $this_input )
				->add( $this_label )
				;

			$out
				->add( $this_out )
				;
		}

		return $out;
	}
}