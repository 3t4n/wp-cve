<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Conf_Controller_HC_MVC extends _HC_MVC
{
	function execute()
	{
		$tabs = $this->app->make('/conf/view/layout')
			->tabs()
			;

		if( ! $tabs ){
			return;
		}

		$tab_keys = array_keys( $tabs );
		$tab = array_shift( $tab_keys );

		if( is_array($tabs[$tab]) ){
			$slug = array_shift( $tabs[$tab] );
			if( substr($slug, 0, 1) != '/' ){
				$slug = '/' . $slug;
			}
		}
		else {
			$slug = '/conf/' . $tab;
		}

		list( $callable, $args ) = $this->app->route( $slug );
		return call_user_func_array( $callable, $args );
	}
}