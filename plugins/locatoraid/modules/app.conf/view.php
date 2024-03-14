<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class App_Conf_View_LC_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$form = $this->app->make('/app.conf/form');
		$to = '/app.conf/update';

		return $this->app->make('/conf/view')
			->render( $form, $to )
			;
	}
}