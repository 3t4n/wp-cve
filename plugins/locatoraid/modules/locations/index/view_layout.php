<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Index_View_Layout_LC_HC_MVC extends _HC_MVC
{
	public function header()
	{
		$return = __('Locations', 'locatoraid');
		return $return;
	}

	public function menubar()
	{
		$return = array();

		$return['new'] = $this->app->make('/html/ahref')
			->to('/locations/new')
			->add( __('Add New', 'locatoraid') )
			;

		return $return;
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