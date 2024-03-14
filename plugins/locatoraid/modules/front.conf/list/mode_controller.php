<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_List_Mode_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute( $to )
	{
		$app_settings = $this->app->make('/app/settings');

		if( $to == 'reset' ){
			$this_field_pname = 'front_list:template';
			$new_value = '';
		}
		else {
			$this_field_pname = 'front_list:advanced';
			$new_value = ($to == 'advanced') ? 1 : 0;
		}

		$this_field_conf = $app_settings->set( $this_field_pname, $new_value );

		$redirect_to = $this->app->make('/http/uri')
			->url('/front.conf/list')
			;
		return $this->app->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}