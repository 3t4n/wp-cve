<?php

/**
 * class VendorHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      3.0.3
 *
 */

namespace AppBuilder\Hooks;

defined( 'ABSPATH' ) || exit;

class VendorHook {
	public function __construct() {
		add_filter( 'app_builder_wcfm_search_data', [ $this, 'wcfm_search_data' ], 10, 2 );
		add_filter( 'app_builder_get_stores', [ $this, 'get_stores' ], 10, 2 );
	}

	/**
	 * Filter search data for WCFM get stores
	 *
	 * @param $search_data
	 * @param $request
	 *
	 * @return mixed
	 */
	public function wcfm_search_data( $search_data, $request ) {
		$radius_lat   = $request->get_param( 'radius_lat' );
		$radius_lng   = $request->get_param( 'radius_lng' );
		$radius_range = $request->get_param( 'radius_range' );

		if ( $radius_lat && $radius_lng && $radius_range ) {
			$search_data['wcfmmp_radius_lat']   = $radius_lat;
			$search_data['wcfmmp_radius_lng']   = $radius_lng;
			$search_data['wcfmmp_radius_range'] = $radius_range;
		}

		return $search_data;
	}

	public function get_stores( $stores, $request ) {
		$radius_lat  = $request->get_param( 'radius_lat' );
		$radius_lng  = $request->get_param( 'radius_lng' );
		$units       = $request->get_param( 'units' ) ? sanitize_text_field( $request->get_param( 'units' ) ) : 'metric';
		$gmw_options = get_option( 'gmw_options' );

		if ( ! isset( $gmw_options['api_providers']['google_maps_server_side_api_key'] ) || empty( $radius_lat ) || empty( $radius_lng ) ) {
			return $stores;
		}

		$origins = [];

		foreach ( $stores as $value ) {
			$geolocation = $value['geolocation'];
			$origins[]   = $geolocation['store_lat'] . ',' . $geolocation['store_lng'];
		}

		$origin_string       = implode( '|', $origins );
		$destinations_string = "$radius_lat,$radius_lng";
		$key                 = $gmw_options['api_providers']['google_maps_server_side_api_key'];

		$url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=$units&origins=$origin_string&destinations=$destinations_string&key=$key";

		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return $stores;
		}

		$body = json_decode( $response['body'] );

		if ($body->status == 'REQUEST_DENIED') {
			return $stores;
		}

		$distance_matrix = $body->rows;

		$store_with_distance = [];

		foreach ( $stores as $key => $value ) {
			if ( isset( $distance_matrix[ $key ]->elements[0]->distance ) && isset( $distance_matrix[ $key ]->elements[0]->duration ) ) {
				$store_with_distance[] = array_merge( $value, array(
					'distance' => $distance_matrix[ $key ]->elements[0]->distance,
					'duration' => $distance_matrix[ $key ]->elements[0]->duration,
				) );
			}
		}

		return $store_with_distance;
	}
}
