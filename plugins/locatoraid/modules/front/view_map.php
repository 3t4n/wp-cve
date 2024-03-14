<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_View_Map_LC_HC_MVC extends _HC_MVC
{
	public function render( $params = array() )
	{
		$style = array_key_exists('map-style', $params) ? $params['map-style'] : NULL;
		$map_id = 'hclc_map' . '_' . rand( 100, 999 );
		$map_class = 'hclc_map_class';
		$div = $this->app->make('/html/element')->tag('div')
			->add_attr('id', $map_id)
			->add_attr('class', $map_class)
			->add_attr('class', 'hc-mb3-xs')
			->add_attr('class', 'hc-border')

			->add_attr('style', $style)
			;

		$map_attr = array(
			);
		if( isset($params['map-start-address']) ){
			$map_attr['data-start-address'] = $params['map-start-address'];
		}
		if( isset($params['map-start-zoom']) ){
			$map_attr['data-start-zoom'] = $params['map-start-zoom'];
		}
		if( isset($params['map-max-zoom']) ){
			$map_attr['data-max-zoom'] = $params['map-max-zoom'];
		}
		if( isset($params['map-hide-loc-title']) ){
			$map_attr['data-hide-loc-title'] = $params['map-hide-loc-title'];
		}

		foreach( $map_attr as $k => $v ){
			$div
				->add_attr( $k, $v )
				;
		}

		$app_settings = $this->app->make('/app/settings');
		$template = $app_settings->get('front_map:template');
		$template = htmlspecialchars( $template );

		// $template = str_replace( "\n", "", $template );
		// $template = str_replace( "\r", "", $template );

		// $template = $this->app->make('/html/element')->tag('script')
			// ->add_attr('type', 'text/template')
			// ->add_attr('id', $map_id . '_template')
			// ->add( $template )
			// ;

		$template = $this->app->make('/html/element')->tag('div')
			->add_attr('id', $map_id . '_template')
			->add_attr('style', 'display: none;' )
			->add( $template )
			;

		$out = $this->app->make('/html/element')->tag(NULL)
			->add( $div )
			->add( $template )
			;

		return $out;
	}
}