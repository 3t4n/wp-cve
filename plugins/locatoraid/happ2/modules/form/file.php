<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_File_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	public function grab( $name, $post )
	{
		$name = $this->app->make('/form/input')->name($name);

		$return = NULL;
		if( isset($_FILES[$name]) && is_uploaded_file($_FILES[$name]['tmp_name']) ){
			$return = $_FILES[$name];
		}

		return $return;
	}

	public function render( $name, $value = NULL )
	{
		$name = $this->app->make('/form/input')->name($name);

		$out = $this->app->make('/html/element')->tag('input')
			->add_attr( 'type', 'file' )
			->add_attr('name', $name )
			// ->add_attr('id', $this->id() )
			->add_attr('class', 'hc-field')
			->add_attr( 'style', 'margin:0;' )
			;

		return $out;
	}
}
