<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Searchlog_Conf_View_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$form = $this->app->make('/searchlog.conf/form');
		$to = '/searchlog.conf/update';

		return $this->app->make('/conf/view')
			->render( $form, $to )
			;
	}
}