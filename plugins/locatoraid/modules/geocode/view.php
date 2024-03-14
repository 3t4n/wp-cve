<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Geocode_View_LC_HC_MVC extends _HC_MVC
{
	public function render( $location )
	{
	// add javascript
		$this->app->make('/app/enqueuer')
		->register_script( 'lc-geocode', 'modules/geocode/assets/js/geocode.js' )
		->enqueue_script( 'lc-geocode' )
		;

		$out = $this->app->make('/html/list')
			->set_gutter(2)
			;

		$id = $location['id'];

		$p = $this->app->make('/locations/presenter');
		$address = $p->present_address( $location );

		$geocoder = $this->app->make('/geocode/lib');
		$escape_address = $geocoder->prepare_address($address);
		$escape_address = addslashes( $escape_address );

	// map
		$save_url = $this->app->make('/http/uri')
			->mode('api')
			->url('/geocode/' . $location['id'] . '/save/_LATITUDE_/_LONGITUDE_')
			;

		$map_id = 'hclc_map';
		$map_class = 'hclc_map_class';
		$map = $this->app->make('/html/element')->tag('div')
			->add_attr('id', $map_id)
			->add_attr('class', $map_class)
			->add_attr('style', 'height: 15em;')

			->add_attr('data-address', $escape_address)
			->add_attr('data-save-url', $save_url)
			;

		$out
			->add( $map )
			;

		$out = $this->app->make('/html/element')->tag('div')
			->add( $out )
			->add_attr('class', 'hcj2-container')
			;

		return $out;
	}
}