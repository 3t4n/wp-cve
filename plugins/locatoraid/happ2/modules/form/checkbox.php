<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Checkbox_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	protected $value = NULL;
	protected $label = '';

	public function set_label( $label )
	{
		$this->label = $label;
		return $this;
	}

	public function set_value( $value )
	{
		$this->value = $value;
		return $this;
	}

	public function grab( $name, $post )
	{
		$return = $this->app->make('/form/input')
			->grab($name, $post)
			;
		$return = $return ? 1 : 0;
		return $return;
	}

	public function render( $name, $value = NULL )
	{
		$name = $this->app->make('/form/input')->name($name);

		$out = $this->app->make('/html/element')->tag('input')
			->add_attr('type', 'checkbox' )
			->add_attr('name', $name )
			;

		if( $this->value ){
			$out
				->add_attr('value', $this->value )
				;
		}

		if( $value ){
			$out
				->add_attr('checked', 'checked')
				;
		}

		if( strlen($this->label) ){
			$id = 'hc2_' . hc_random();
			$out
				->add_attr('id', $id )
				;

			$this_label = $this->app->make('/html/element')->tag('label')
				->add_attr('for', $id )
				->add( $this->label )
				; 

			$out = $this->app->make('/html/list-inline')
				->set_gutter(0)
				->set_mobile(TRUE)
				->add( $out )
				->add( $this_label )
				;
		}

		return $out;
	}
}