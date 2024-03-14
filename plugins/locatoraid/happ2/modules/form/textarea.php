<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Textarea_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	protected $rows = NULL;
	protected $cols = NULL;

	public function set_rows( $rows )
	{
		$this->rows = $rows;
		return $this;
	}

	public function set_cols( $cols )
	{
		$this->cols = $cols;
		return $this;
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

		$out = $this->app->make('/html/element')->tag('textarea')
			->add_attr('name', $name )
			// ->add_attr('id', $this->id() )
			->add_attr('class', 'hc-field')
			->add_attr('class', 'hc-block')
			->add_attr( 'style', 'margin:0;' )
			;

		if( $this->cols !== NULL ){
			$out
				->add_attr('cols', $this->cols)
				;
		}

		if( $this->rows !== NULL ){
			$out
				->add_attr('rows', $this->rows)
				;
		}

		if( $value !== NULL ){
			$out
				->add( $value )
				;
		}

		return $out;
	}
}