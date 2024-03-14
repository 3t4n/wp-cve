<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Maps_Google_Conf_Input_Map_Style_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	protected $input = NULL;

	public function render( $name, $value = NULL )
	{
	// add javascript
		$this->app->make('/app/enqueuer')
			->register_script( 'hc-maps-google-style-preview', 'happ2/modules/maps_google.conf/assets/js/preview.js' )
			->enqueue_script( 'hc-maps-google-style-preview' )
			;

		$input = $this->app->make('/form/textarea')
			->set_rows( 12 )
			->render( $name, $value )
			;

		$input = $this->app->make('/html/element')->tag('div')
			->add( $input )
			->add_attr('class', 'hcj2-map-style')
			;

		// $input = $this->input->render();

		$preview_button = $this->app->make('/html/element')->tag('button')
			->add( __('Map Style Preview', 'locatoraid') )
			->add_attr('class', 'hc-theme-btn-submit')
			->add_attr('class', 'hc-theme-btn-secondary')
			->add_attr('class', 'hc-block')
			->add_attr('class', 'hcj2-map-preview')
			;

	// map preview
		$map_id = 'hclc_map';
		$map = $this->app->make('/html/element')->tag('div')
			->add_attr('id', $map_id)
			->add_attr('class', 'hc-border')
			->add_attr('style', 'height: 14rem;')
			;

		$out = $this->app->make('/html/grid')
			->set_gutter(2)
			;

		$out
			->add( $input, 5 )
			->add( $preview_button, 2 )
			->add( $map, 5 )
			;

		return $out;
	}

	public function grab( $name, $post )
	{
		$return = $this->app->make('/form/textarea')
			->grab( $name, $post )
			;
		return $return;
	}
}