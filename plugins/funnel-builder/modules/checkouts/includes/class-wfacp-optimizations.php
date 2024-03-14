<?php

#[AllowDynamicProperties]

 abstract class WFACP_Optimizations {
	private static $preferred_countries = [];

	public static function init() {
		add_action( 'wfacp_after_checkout_page_found', [ __CLASS__, 'optimizations' ] );
	}

	public static function optimizations() {

		$settings = WFACP_Common::get_page_settings( WFACP_Common::get_id() );
		if ( ! is_array( $settings ) ) {
			return;
		}

		if ( true !== wc_string_to_bool( $settings['preferred_countries_enable'] ) || '' == $settings['preferred_countries'] ) {
			return;
		}

		$countries_str = $settings['preferred_countries'];
		if ( empty( $countries_str ) ) {
			return;
		}
		self::$preferred_countries = $countries_str;
		add_filter( 'option_woocommerce_ship_to_countries', [ __CLASS__, 'option_woocommerce_ship_to_countries' ] );
		add_filter( 'woocommerce_countries_shipping_countries', [ __CLASS__, 'preferred_country' ] );
		add_filter( 'woocommerce_countries_allowed_countries', [ __CLASS__, 'preferred_country' ] );
	}

	public static function option_woocommerce_ship_to_countries( $status ) {
		if ( 'all' == $status ) {
			return '';
		}

		return $status;

	}

	public static function preferred_country( $countries ) {

		if ( empty( self::$preferred_countries ) ) {
			return $countries;
		}

		$top_countries = [];
		foreach ( self::$preferred_countries as $val ) {
			$code = trim( $val['id'] );
			if ( isset( $countries[ $code ] ) ) {
				$top_countries[ $code ] = $countries[ $code ];
			}
		}

		if ( empty( $top_countries ) ) {
			return $countries;
		}

		return array_merge( $top_countries, $countries );
	}

}

WFACP_Optimizations::init();
