<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_View_Shortcode_LC_HC_MVC extends _HC_MVC
{
	public function render( $shortcode_atts )
	{
		$params = array();

		if( $shortcode_atts && is_array($shortcode_atts) ){
			foreach( $shortcode_atts as $k => $v ){
				$params[$k] = $v;
			}
		}

		$view = $this->app->make('/front/view');
		return $view->render($params);
	}
}