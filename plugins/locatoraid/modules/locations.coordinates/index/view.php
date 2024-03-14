<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Coordinates_Index_View_LC_HC_MVC extends _HC_MVC
{
	public function render( $location, $can_edit = 1 )
	{
		$values = $location;

		$latitude = $values['latitude'];
		$longitude = $values['longitude'];

		$p = $this->app->make('/locations.coordinates/presenter');
		$pl = $this->app->make('/locations/presenter');

		$geocoding_status = $p->geocoding_status( $location );
		if( $geocoding_status > 0 ){
			$this->app->make('/app/enqueuer')
				->register_script( 'lc-locations-coordinates', 'modules/locations.coordinates/assets/js/map.js' )
				->enqueue_script( 'lc-locations-coordinates' )
				;
		}

		$out = $this->app->make('/html/list')
			->set_gutter(2)
			;

		$id = $location['id'];

		$address = $pl->present_address( $location );

	// map
		$map_id = 'hclc_map';
		$map_class = 'hclc_map_class';
		$map = $this->app->make('/html/element')->tag('div')
			->add_attr('id', $map_id)
			->add_attr('class', $map_class)
			->add_attr('class', 'hc-border')
			;

		if( (($latitude == -1) && ($longitude == -1)) ){
			$map
				->add_attr('class', 'hc-p2')
				->add( $p->present_coordinates($location) )
				;
		}
		else {
			$map
				->add_attr('style', 'height: 14rem;')
				->add_attr('data-latitude',  $latitude)
				->add_attr('data-longitude', $longitude)
				->add_attr('data-edit', $can_edit)
				;

			$icon = $pl->present_icon_url( $location );
			if( $icon ){
				$map->add_attr('data-icon', $icon);
			}
		}

	// form
		$form = $this->app->make('/locations.coordinates/form');
		$helper = $this->app->make('/form/helper');

		$inputs_view = $helper->prepare_render( $form->inputs(), $values );
		$out_inputs = $helper->render_inputs( $inputs_view );

		$out_buttons = $this->app->make('/html/list')
			->set_gutter(2)
			;

		$out_buttons->add(
			$this->app->make('/html/element')->tag('input')
				->add_attr('type', 'submit')
				->add_attr('title', __('Save', 'locatoraid') )
				->add_attr('value', __('Save', 'locatoraid') )
				->add_attr('class', 'hc-theme-btn-submit')
				->add_attr('class', 'hc-theme-btn-primary')
				->add_attr('class', 'hc-block')
			);

		if( ! (($latitude == -1) && ($longitude == -1)) ){
			$out_buttons->add(
				$this->app->make('/html/ahref')
					->to('/locations.coordinates/' . $location['id'] . '/reset')
					->add( __('Reset', 'locatoraid') )
					->add_attr('class', 'hc-theme-btn-submit')
					->add_attr('class', 'hc-block')
				);
		}

		$display_form_content = $this->app->make('/html/list')
			->set_gutter(2)
			;

		if( ! (($latitude == -1) && ($longitude == -1)) ){
			$display_form_content
				->add(
					$this->app->make('/html/element')->tag('div')
						->add( __('You can use your mouse to move the location. Or manually enter the coordinates.', 'locatoraid') )
						->add_attr('class', 'hc-italic')
					)
				;
		}

		$display_form_content
			->add(
				$this->app->make('/html/grid')
					->set_gutter(2)
					->add( $out_inputs, 9, 12 )
					->add( $out_buttons, 3, 12 )
				)
			;

		$link = $this->app->make('/http/uri')
			->url('/locations.coordinates/' . $id . '/update')
			;
		$display_form = $helper
			->render( array('action' => $link) )
			->add( $display_form_content )
			;

		if( $can_edit ){
			$out
				->add( $address )
				;
		}

		$out
			->add( $map )
			;

		if( $can_edit ){
			$out
				->add( $display_form )
				;
		}

		$out = $this->app->make('/html/element')->tag('div')
			->add( $out )
			->add_attr('class', 'hcj2-container')
			;

		return $out;
	}
}