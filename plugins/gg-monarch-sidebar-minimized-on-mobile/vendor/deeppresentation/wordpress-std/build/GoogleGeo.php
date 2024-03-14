<?php namespace MSMoMDP\Wp;

use MSMoMDP\Std\Core\Arr;
use MSMoMDP\Std\Core;

class GoogleGeo extends Core\GoogleGeo {

	public function __construct( $apiKey ) {
		parent::__construct( $apiKey );
	}

	public static function get_data_from_wp_post( ?int $postId = null ) {
		if ( ! $postId ) {
			$postId = get_the_ID();
		}
		$data = self::get_initialized_data();
		if ( $postId ) {
			foreach ( $data as $key => $val ) {
				$data[ $key ] = get_field( $key, $postId );
			}
		}
		return $data;
	}

	public static function clear_data_in_wp_post( ?int $postId = null ) {
		if ( ! $postId ) {
			$postId = get_the_ID();
		}
		if ( $postId ) {
			$data = self::get_initialized_data();
			foreach ( $data as $key => $val ) {
				WpStd::update_post_meta(
					$postId,
					$key,
					''
				);
			}
		}
	}

	public static function set_data_to_wp_post( array $geoData, ?int $postId = null ) {
		if ( ! $postId ) {
			$postId = get_the_ID();
		}
		if ( $postId ) {
			foreach ( $geoData as $key => $val ) {
				WpStd::update_post_meta(
					$postId,
					$key,
					$val
				);
			}
		}
	}
}
