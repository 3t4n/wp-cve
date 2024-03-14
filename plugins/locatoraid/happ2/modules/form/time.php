<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Time_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	protected static $time_options = array();

	public function grab( $name, $post )
	{
		$return = $this->app->make('/form/select')
			->grab($name, $post)
			;
		return $return;
	}

	public function options()
	{
		if( ! self::$time_options ){
			self::$time_options = $this->_init_time_options();
		}
		return self::$time_options;
	}

	public function render( $name, $value = NULL )
	{
		$return = $this->app->make('/form/select')
			->set_options( $this->options() )
			->render( $name, $value )
			;

		return $return;
	}

	protected function _init_time_options()
	{
		$t = $this->app->make('/app/lib')->time();

		$start_with = 0;
		$end_with = 24 * 60 * 60;

		if( $end_with < $start_with ){
			$end_with = $start_with;
		}

		$step = 5 * 60;
		$options = array();

		$t->setDateDb( 20130118 );

		if( $start_with ){
			$t->modify( '+' . $start_with . ' seconds' );
		}

		$no_of_steps = ( $end_with - $start_with) / $step;
		for( $ii = 0; $ii <= $no_of_steps; $ii++ ){
			$sec = $start_with + $ii * $step;
			$options[ $sec ] = $t->formatTime();
			$t->modify( '+' . $step . ' seconds' );
		}

		return $options;
	}
}