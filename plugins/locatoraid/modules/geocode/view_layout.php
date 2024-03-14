<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Geocode_View_Layout_LC_HC_MVC extends _HC_MVC
{
	public function header( $model )
	{
		$return = __('Geocode', 'locatoraid');
		return $return;
	}

	public function menubar( $model )
	{
		$return = array();

	// LIST
		$return['list'] = $this->app->make('/html/ahref')
			->to('/locations/' . $model['id'] . '/edit')
			->add( $this->app->make('/html/icon')->icon('arrow-left') )
			->add( __('Edit Location', 'locatoraid') )
			;

		return $menubar;
	}

	public function render( $content, $model )
	{
		$menubar = $this->menubar($model);
		$header = $this->header($model);

		$out = $this->app->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			->set_menubar( $menubar )
			;

		return $out;
	}
}