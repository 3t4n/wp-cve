<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Checkbox_Set_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	protected $options = array();
	protected $readonly_options = array();
	protected $default = array();

	public function set_default( array $v )
	{
		$this->default = $v;
		return $this;
	}

	public function set_options( $options )
	{
		$this->options = $options;
		return $this;
	}

	public function set_readonly_options( $readonly_options )
	{
		$this->readonly_options = $readonly_options;
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

		if( ! is_array($return) ){
			if( strlen($return) ){
				$return = array( $return );
			}
			else {
				$return = array();
			}
		}

		return $return;
	}

	public function render( $name, $value = null )
	{
		$inputName = $name;
		if( 'hc-' == substr($inputName, 0, strlen('hc-')) ){
			$inputName = substr($inputName, strlen('hc-'));
		}

		$name = $this->app->make('/form/input')->name($name);

		if( null === $value ){
			$value = $this->default;
		}

		if( $value && (! is_array($value)) ){
			$value = array( $value );
		}

		$out = $this->app->make('/html/list-inline')
			// ->set_gutter(2)
			->set_gutter(0)
			;

		$id = 'hc2r_' . hc_random();
		$options = $this->options();

		foreach( $options as $k => $v ){
			$this_id = $id . '_' . $k;

			if( $this->readonly_options && in_array($k, $this->readonly_options) ){
				if( ($value !== NULL) && in_array($k, $value) ){
					$this_input = $this->app->make('/html/element')->tag('span')
						->add( $this->app->make('/html/icon')->icon('check') )
						->add_attr('class', 'hc-olive')
						;
				}
				else {
					$this_input = $this->app->make('/html/element')->tag('span')
						->add( $this->app->make('/html/icon')->icon('times') )
						->add_attr('class', 'hc-maroon')
						;
				}
			}
			else {
				$this_input = $this->app->make('/html/element')->tag('input')
					->add_attr('type', 'checkbox' )
					->add_attr('name', $name . '[]' )
					->add_attr('value', $k )
					->add_attr('id', $this_id )
					;

				if( ($value !== NULL) && in_array($k, $value) ){
					$this_input
						->add_attr('checked', 'checked')
						;
				}
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

			$this_out = $this->app->make('/html/element')->tag('div')
				->add( $this_out )
				->add_attr('id', 'hc-checkbox-' . $inputName . '-' . $k )
				->add_attr('class', 'hc-mr2')
				;

			$out
				->add( $this_out )
				;
		}

		return $out;
	}
}