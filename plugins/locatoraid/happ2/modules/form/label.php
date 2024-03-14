<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Label_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	public function grab( $name, $post )
	{
		$return = NULL;
		return $return;
	}

	public function render( $name, $value = NULL )
	{
		$name = $this->app->make('/form/input')->name($name);

		$out = $this->app->make('/html/element')->tag('span')
			// ->add_attr('class', 'hc-field')
			->add_attr('class', 'hc-block')
			// ->add_attr('class', 'hc-muted1')
			->add_attr( 'style', 'margin:0;' )

			// ->add_attr('class', 'hc-bg-lightsilver')
			// ->add_attr('class', 'hc-rounded')
			// ->add_attr('class', 'hc-px2')
			// ->add_attr('class', 'hc-py1')
			;

		if( $value !== NULL ){
			$out
				->add( $value )
				;
		}

		return $out;
	}
}