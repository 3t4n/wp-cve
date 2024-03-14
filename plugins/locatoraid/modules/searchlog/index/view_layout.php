<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Searchlog_Index_View_Layout_LC_HC_MVC extends _HC_MVC
{
	public function header()
	{
		$return = __('Search Log', 'locatoraid');
		return $return;
	}

	public function menubar()
	{
		$ret = array();

		$ret['export'] = $this->app->make('/html/ahref')
			->to('/searchlog/export')
			->add( __('Export', 'locatoraid') )
			;

		return $ret;
	}

	public function render( $content )
	{
		$header = $this->header();
		$menubar = $this->menubar();

		$out = $this->app->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			->set_menubar( $menubar )
			;

		return $out;
	}
}