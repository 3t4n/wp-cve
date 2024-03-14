<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Layout_WordPress_Path_HC_MVC extends _HC_MVC
{
	public function full_path( $path )
	{
		$return = $path;

		if( HC_Lib2::is_full_url($return) ){
			return $return;
		}

		if( defined('NTS_DEVELOPMENT2') && NTS_DEVELOPMENT2 ){
			$assets_web_dir = $this->app->web_dir . '/';
			$assets_happ_web_dir = plugins_url('locatoraid/happ2') . '/';
		}
		else {
			$assets_web_dir = $this->app->web_dir . '/';
			$assets_happ_web_dir = $assets_web_dir . 'happ2/';
		}

		if( substr($return, 0, strlen('happ2/')) == 'happ2/' ){
			$return = $assets_happ_web_dir . substr($return, strlen('happ2/'));
		}
		else {
			$return = $assets_web_dir . $return;
		}

		return $return;
	}
}