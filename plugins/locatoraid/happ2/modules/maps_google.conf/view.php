<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Maps_Google_Conf_View_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$form = $this->app->make('/maps-google.conf/form');
		$to = '/maps-google-conf/update';

		return $this->app->make('/conf/view')
			->render( $form, $to )
			;
	}
}