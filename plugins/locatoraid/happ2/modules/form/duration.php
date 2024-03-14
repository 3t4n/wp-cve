<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Duration_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	public function grab( $name, $post )
	{
		$name1 = $name . '_qty';
		$name2 = $name . '_measure';

		$return1 = $this->app->make('/form/select')
			->grab( $name1, $post )
			;
		$return2 = $this->app->make('/form/select')
			->grab( $name2, $post )
			;

		$return = $return1 . ' ' . $return2;
		return $return;
	}

	public function render( $name, $value = NULL )
	{
		$value = explode( ' ', $value );

		$name1 = $name . '_qty';
		$name2 = $name . '_measure';

		$value1 = array_shift( $value );
		$value2 = array_shift( $value );

		$options1 = array();
		for( $ii = 1; $ii <= 20; $ii++ ){
			$options1[ $ii ] = $ii;
		}
		$input1 = $this->app->make('/form/select')
			->set_options( $options1 )
			->render( $name1, $value1 )
			;

		$options2 = array(
			'hours'	=> __('Hours', 'locatoraid'),
			'days'	=> __('Days', 'locatoraid'),
			'weeks'	=> __('Weeks', 'locatoraid'),
			'months'	=> __('Months', 'locatoraid'),
			);
		$input2 = $this->app->make('/form/select')
			->set_options( $options2 )
			->render( $name2, $value2 )
			;

		$return = $this->app->make('/html/list-inline')
			->set_gutter(2)
			->set_mobile(TRUE)
			->add( $input1 )
			->add( $input2 )
			;

		return $return;
	}
}