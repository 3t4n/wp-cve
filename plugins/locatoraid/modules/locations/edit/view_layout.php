<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Edit_View_Layout_LC_HC_MVC extends _HC_MVC
{
	public function header( $model )
	{
		$return = __('Edit Location', 'locatoraid');
		return $return;
	}

	public function menubar( $model )
	{
		$return = array();

	// LIST
		$return['list'] = 
			$this->app->make('/html/ahref')
				->to('/locations')
				->add( $this->app->make('/html/icon')->icon('arrow-left') )
				->add( __('Locations', 'locatoraid') )
			;

		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $model )
			;

		return $return;
	}

	public function render( $content, $model )
	{
		$this->app->make('/layout/top-menu')
			->set_current( 'locations' )
			;

		$menubar = $this->menubar( $model);
		$header = $this->header( $model );

		$out = $this->app->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			->set_menubar( $menubar )
			;

		return $out;
	}
}