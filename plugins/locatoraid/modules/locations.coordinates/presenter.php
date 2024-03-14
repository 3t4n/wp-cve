<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Coordinates_Presenter_LC_HC_MVC extends _HC_MVC
{
	public function geocoding_status( $data )
	{
		$lat = isset($data['latitude']) ? $data['latitude'] : NULL;
		$lng = isset($data['longitude']) ? $data['longitude'] : NULL;

		if( ! ($lat && $lng) ){
			$return = 0;
		}
		elseif( ($lat == -1) && ($lng == -1) ){
			$return = -1;
		}
		else {
			$return = 1;
		}

		return $return;
	}

	public function present_coordinates( $data )
	{
		$lat = isset($data['latitude']) ? $data['latitude'] : NULL;
		$lng = isset($data['longitude']) ? $data['longitude'] : NULL;

		$geocoded = TRUE;
		if( ((! $lat) OR ($lat == -1)) && ((! $lng) OR ($lng == -1)) ){
			$geocoded = FALSE;
		}

		$wrap = $this->app->make('/html/element')->tag('span')
			->add_attr('class', 'hc-inline-block')
			->add_attr('class', 'hc-p1')
			->add_attr('class', 'hc-rounded')
			;

		if( $geocoded ){
			$return = $lat . ', ' . $lng;
			$wrap
				->add_attr('class', 'hc-bg-olive')
				->add_attr('class', 'hc-white')
				;
		}
		elseif( ($lat == -1) && ($lng == -1) ){
			$return = __('Address Not Found', 'locatoraid');
			$wrap
				->add_attr('class', 'hc-bg-red')
				->add_attr('class', 'hc-white')
				;
		}
		else {
			$return = __('Not Geocoded', 'locatoraid');
			$wrap
				->add_attr('class', 'hc-bg-orange')
				->add_attr('class', 'hc-white')
				;
		}

		$wrap
			->add( $return )
			;

		return $wrap;
	}
}