<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_New_View_Layout_LC_HC_MVC extends _HC_MVC
{
	public function header()
	{
		$return = __('Add New Location', 'locatoraid');
		return $return;
	}

	public function menubar()
	{
		$return = array();

	// LIST
		$return['list'] = $this->app->make('/html/ahref')
			->to('/locations')
			->add( $this->app->make('/html/icon')->icon('arrow-left') )
			->add( __('Locations', 'locatoraid') )
			;

		return $return;
	}

	public function render( $content )
	{
		$this->app->make('/layout/top-menu')
			->set_current( 'locations' )
			;

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