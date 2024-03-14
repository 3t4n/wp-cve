<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Dynamictranslate_Conf_View_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$form = $this->app->make('/dynamictranslate.conf/form');
		$to = '/dynamictranslate.conf/update';

		return $this->app->make('/conf/view')
			->render( $form, $to )
			;
	}
}