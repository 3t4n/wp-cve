<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Conf_Commands_Update_HC_MVC extends _HC_MVC
{
	public function execute( $values = array() )
	{
		$app_settings = $this->app->make('/app/settings');
		reset( $values );
		foreach( $values as $k => $v ){
			$app_settings
				->set( $k, $v )
				;
		}

		$model = $this->app->make('/conf/model');
		$model->save();
		$app_settings->reload();

		$return = $values;

		$return = $this->app
			->after( $this, $return )
			;

		return $return;
	}
}