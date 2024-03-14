<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class GeocodeBulk_View_LC_HC_MVC extends _HC_MVC
{
	public function render( $total_count )
	{
		$out = $this->app->make('/html/list')
			->set_gutter(2)
			;

		if( $total_count ){
			$save_url = $this->app->make('/http/uri')
				->mode('api')
				->url('/geocode/_ID_/save/_LATITUDE_/_LONGITUDE_')
				;
			$json_url = $this->app->make('/http/uri')
				->mode('api')
				->url('/geocodebulk/json')
				;

			$map_id = 'hclc_map';
			$map_class = 'hclc_map_class';
			$map = $this->app->make('/html/element')->tag('div')
				->add_attr('id', $map_id)
				->add_attr('class', $map_class)
				->add_attr('class', 'hc-p1')
				->add_attr('class', 'hc-b1')

				->add_attr('data-json-url', $json_url)
				->add_attr('data-save-url', $save_url)
				;

			$out
				->add( $map )
				;
		}

		$out = $this->app->make('/html/element')->tag('div')
			->add( $out )
			->add_attr('class', 'hcj2-container')
			;

		return $out;
	}
}