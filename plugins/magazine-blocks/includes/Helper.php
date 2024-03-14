<?php

namespace MagazineBlocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Helper {

	private static $instance;

	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get Post Types.
	 *
	 * @since 1.0.9
	 */
	public static function get_post_types() {
		$post_types = get_post_types(
			array(
				'public'       => true,
				'show_in_rest' => true,
			),
			'objects'
		);

		$options = array();

		foreach ( $post_types as $post_type ) {
			if ( 'product' === $post_type->name ) {
				continue;
			}

			if ( 'attachment' === $post_type->name ) {
				continue;
			}

			if ( 'page' === $post_type->name ) {
				continue;
			}

			$options[] = array(
				'value' => $post_type->name,
				'label' => $post_type->label,
			);
		}

		return $options;
	}

	public static function show_temp() {
		$instance = self::getInstance();

		$api       = magazine_blocks_get_setting( 'integrations.dateWeatherApiKey' );
		$postal    = magazine_blocks_get_setting( 'integrations.dateWeatherZipCode' );
		$transient = get_transient( 'show_temp' );

		if ( ! empty( $transient ) ) {
			return $transient;
		}

		if ( empty( $api ) && empty( $postal ) ) {
			return;
		}
		$url = 'http://api.openweathermap.org/data/2.5/weather?zip=' . $postal . ',us&units=imperial&APPID=' . $api . '';

		$response = wp_remote_get( $url );

		if ( is_array( $response ) ) {
			$body = $response['body'];
			$resp = json_decode( $body );
			$temp = $resp->main->temp;
		}

		set_transient( 'temp', $temp, MINUTE_IN_SECONDS );

		return round( $temp );
	}

	public static function show_weather() {
		$instance = self::getInstance();

		$api       = magazine_blocks_get_setting( 'integrations.dateWeatherApiKey' );
		$postal    = magazine_blocks_get_setting( 'integrations.dateWeatherZipCode' );
		$transient = get_transient( 'show_weather' );

		if ( ! empty( $transient ) ) {
			return $transient;
		}
		if ( empty( $api ) && empty( $postal ) ) {
			return;
		}

			$url = 'http://api.openweathermap.org/data/2.5/weather?zip=' . $postal . ',us&units=imperial&APPID=' . $api . '';

			$response = wp_remote_get( $url );

		if ( is_array( $response ) ) {
			$body    = $response['body'];
			$resp    = json_decode( $body );
			$weather = $resp->weather[0]->main;
		}

			set_transient( 'weather', $weather, MINUTE_IN_SECONDS );

		return $weather;
	}

	public static function show_location() {
		$instance = self::getInstance();

		$api       = magazine_blocks_get_setting( 'integrations.dateWeatherApiKey' );
		$postal    = magazine_blocks_get_setting( 'integrations.dateWeatherZipCode' );
		$transient = get_transient( 'show_location' );

		if ( ! empty( $transient ) ) {
			return $transient;
		}
		if ( empty( $api ) && empty( $postal ) ) {
			return;
		}
			$url = 'http://api.openweathermap.org/data/2.5/weather?zip=' . $postal . ',us&units=imperial&APPID=' . $api . '';

			$response = wp_remote_get( $url );

		if ( is_array( $response ) ) {
			$body     = $response['body'];
			$resp     = json_decode( $body );
			$location = $resp->name;
		}

			set_transient( 'location', $location, MINUTE_IN_SECONDS );

		return $location;
	}
}
