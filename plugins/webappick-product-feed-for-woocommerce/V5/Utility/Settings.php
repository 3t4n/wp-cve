<?php

namespace CTXFeed\V5\Utility;
/**
 * @class      Settings
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Utility
 */
class Settings {
	/**
	 * Get saved settings.
	 *
	 * @param string $key     Option name.
	 *                        All default values will be returned if this set to 'defaults',
	 *                        all settings will be return if set to 'all'.
	 * @param bool   $default value to return if no matching data found for the key (option)
	 *
	 * @return array|bool|string|mixed
	 * @since 3.3.11
	 */
	public static function get( $key = 'all', $default = false ) {
		$defaults = [
			'per_batch'                     => 200,
			'product_query_type'            => 'wc',
			'variation_query_type'          => 'individual',
			'enable_error_debugging'        => 'off',
			'cache_ttl'                     => 6 * HOUR_IN_SECONDS,
			'overridden_structured_data'    => 'off',
			'disable_mpn'                   => 'enable',
			'disable_brand'                 => 'enable',
			'disable_pixel'                 => 'enable',
			'pixel_id'                      => '',
			'disable_remarketing'           => 'disable',
			'remarketing_id'                => '',
			'remarketing_label'             => '',
			'pinterest_tag_id'              => '',
			'pinterest_conversion_tracking' => 'disable',
			'allow_all_shipping'            => 'no',
			'only_free_shipping'            => 'yes',
			'only_local_pickup_shipping'    => 'no',
			'enable_ftp_upload'             => 'no',
			'enable_cdata'                  => 'no',
			'woo_feed_taxonomy'             => array(
				'brand' => 'disable',
			),
			'woo_feed_identifier'           => array(
				'gtin'                      => 'disable',
				'ean'                       => 'disable',
				'mpn'                       => 'disable',
				'isbn'                      => 'disable',
				'age_group'                 => 'disable',
				'material'                  => 'disable',
				'gender'                    => 'disable',
				'cost_of_good_sold'         => 'disable',
				'availability_date'         => 'enable',
				'unit'                      => 'disable',
				'unit_pricing_measure'      => 'disable',
				'unit_pricing_base_measure' => 'disable',
				'custom_field_0'            => 'disable',
				'custom_field_1'            => 'disable',
				'custom_field_2'            => 'disable',
				'custom_field_3'            => 'disable',
				'custom_field_4'            => 'disable',
			),
		];

		/**
		 * Add defaults without chainging the core values.
		 *
		 * @param array $defaults
		 *
		 * @since 3.3.11
		 */
		$defaults = wp_parse_args( apply_filters( 'woo_feed_settings_extra_defaults', [] ), $defaults );

		if ( 'defaults' === $key ) {
			return $defaults;
		}

		$settings = wp_parse_args( get_option( 'woo_feed_settings', [] ), $defaults );

		if ( 'all' === $key ) {
			return $settings;
		}

		if ( array_key_exists( $key, $settings ) ) {
			return $settings[ $key ];
		}

		return $default;
	}

	/**
	 * Update Settings.
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return bool
	 */
	public static function set( $key, $value ) {
		$setting = self::get();

		if ( isset( $setting[ $key ] ) ) {
			$setting[ $key ] = $value;
		}

		return self::save( $setting );
	}

	/**
	 * Save Settings.
	 *
	 * @param array $args Required. option key value paired array to save.
	 *
	 * @return bool
	 * @since 3.3.11
	 */
	public static function save( $args ) {
		$data     = woo_feed_get_options( 'all' );
		$defaults = woo_feed_get_options( 'defaults' );
		$_data    = $data;

		if ( array_key_exists( 'per_batch', $args ) ) {
			$data['per_batch'] = absint( $args['per_batch'] );
			if ( $data['per_batch'] <= 0 ) {
				$data['per_batch'] = $_data['per_batch'] > 0 ? $_data['per_batch'] : $defaults['per_batch'];
			}
			unset( $args['unset'] );
		}
		if ( array_key_exists( 'product_query_type', $args ) ) {
			$data['product_query_type'] = strtolower( $args['product_query_type'] );
			$query_types                = array_keys( woo_feed_get_query_type_options() );
			if ( ! in_array( $data['product_query_type'], $query_types, true ) ) {
				$data['product_query_type'] = in_array( $_data['product_query_type'], $query_types, true ) ? $_data['product_query_type'] : $defaults['product_query_type'];
			}
			unset( $args['product_query_type'] );
		}
		if ( array_key_exists( 'variation_query_type', $args ) ) {
			$data['variation_query_type'] = strtolower( $args['variation_query_type'] );
			$query_types                  = array_keys( woo_feed_get_query_type_options( 'variation' ) );
			if ( ! in_array( $data['variation_query_type'], $query_types, true ) ) {
				$data['variation_query_type'] = in_array( $_data['variation_query_type'], $query_types, true ) ? $_data['variation_query_type'] : $defaults['variation_query_type'];
			}
			unset( $args['variation_query_type'] );
		}
		if ( array_key_exists( 'enable_error_debugging', $args ) ) {
			$data['enable_error_debugging'] = strtolower( $args['enable_error_debugging'] );
			if ( ! in_array( $data['enable_error_debugging'], [ 'on', 'off' ] ) ) {
				$data['enable_error_debugging'] = in_array( $_data['enable_error_debugging'], [
					'on',
					'off'
				] ) ? $_data['enable_error_debugging'] : $defaults['enable_error_debugging'];
			}
			unset( $args['enable_error_debugging'] );
		}
		if ( array_key_exists( 'cache_ttl', $args ) ) {
			$data['cache_ttl'] = absint( $args['cache_ttl'] ); // cache ttl can be zero.
			unset( $args['cache_ttl'] );
		}
		if ( array_key_exists( 'overridden_structured_data', $args ) ) {
			$data['overridden_structured_data'] = strtolower( $args['overridden_structured_data'] );
			if ( ! in_array( $data['overridden_structured_data'], array( 'on', 'off' ) ) ) {
				$data['overridden_structured_data'] = in_array( $_data['overridden_structured_data'], array(
					'on',
					'off'
				) ) ? $_data['overridden_structured_data'] : $defaults['overridden_structured_data'];
			}
			unset( $args['overridden_structured_data'] );
		}

		if ( array_key_exists( 'disable_pixel', $args ) ) {
			$data['disable_pixel'] = strtolower( $args['disable_pixel'] );
			if ( ! in_array( $data['disable_pixel'], array( 'enable', 'disable' ) ) ) {
				$data['disable_pixel'] = in_array( $_data['disable_pixel'], array(
					'enable',
					'disable'
				) ) ? $_data['disable_pixel'] : $defaults['disable_pixel'];
			}
			unset( $args['disable_pixel'] );
		}
		if ( array_key_exists( 'pixel_id', $args ) ) {
			if ( isset( $args['pixel_id'] ) && ! empty( $args['pixel_id'] ) ) {
				$data['pixel_id'] = absint( $args['pixel_id'] );
			} else {
				$data['pixel_id'] = $defaults['pixel_id'];
			}
			unset( $args['pixel_id'] );
		}

		if ( array_key_exists( 'disable_remarketing', $args ) ) {
			$data['disable_remarketing'] = strtolower( $args['disable_remarketing'] );
			if ( ! in_array( $data['disable_remarketing'], array( 'enable', 'disable' ) ) ) {
				$data['disable_remarketing'] = in_array( $_data['disable_remarketing'], array(
					'enable',
					'disable'
				) ) ? $_data['disable_remarketing'] : $defaults['disable_remarketing'];
			}
			unset( $args['disable_remarketing'] );
		}
		if ( array_key_exists( 'remarketing_id', $args ) ) {
			if ( isset( $args['remarketing_id'] ) && ! empty( $args['remarketing_id'] ) ) {
				$data['remarketing_id'] = $args['remarketing_id'];
			} else {
				$data['remarketing_id'] = $defaults['remarketing_id'];
			}
			unset( $args['remarketing_id'] );
		}
		if ( array_key_exists( 'remarketing_label', $args ) ) {
			if ( isset( $args['remarketing_label'] ) && ! empty( $args['remarketing_label'] ) ) {
				$data['remarketing_label'] = $args['remarketing_label'];
			} else {
				$data['remarketing_label'] = $defaults['remarketing_label'];
			}
			unset( $args['remarketing_label'] );
		}

		if ( array_key_exists( 'allow_all_shipping', $args ) ) {
			$data['allow_all_shipping'] = strtolower( $args['allow_all_shipping'] );
			if ( ! in_array( $data['allow_all_shipping'], array( 'yes', 'no' ) ) ) {
				$data['allow_all_shipping'] = in_array( $_data['allow_all_shipping'], array(
					'yes',
					'no'
				) ) ? $_data['allow_all_shipping'] : $defaults['allow_all_shipping'];
			}
			unset( $args['allow_all_shipping'] );
		}

		if ( array_key_exists( 'only_free_shipping', $args ) ) {
			$data['only_free_shipping'] = strtolower( $args['only_free_shipping'] );
			if ( ! in_array( $data['only_free_shipping'], array( 'yes', 'no' ) ) ) {
				$data['only_free_shipping'] = in_array( $_data['only_free_shipping'], array(
					'yes',
					'no'
				) ) ? $_data['only_free_shipping'] : $defaults['only_free_shipping'];
			}
			unset( $args['only_free_shipping'] );
		}

		if ( array_key_exists( 'only_local_pickup_shipping', $args ) ) {
			$data['only_local_pickup_shipping'] = strtolower( $args['only_local_pickup_shipping'] );
			if ( ! in_array( $data['only_local_pickup_shipping'], array( 'yes', 'no' ) ) ) {
				$data['only_local_pickup_shipping'] = in_array( $_data['only_local_pickup_shipping'], array(
					'yes',
					'no'
				) ) ? $_data['only_local_pickup_shipping'] : $defaults['only_local_pickup_shipping'];
			}
			unset( $args['only_local_pickup_shipping'] );
		}


		if ( array_key_exists( 'enable_ftp_upload', $args ) ) {
			$data['enable_ftp_upload'] = strtolower( $args['enable_ftp_upload'] );
			if ( ! in_array( $data['enable_ftp_upload'], array( 'yes', 'no' ) ) ) {
				$data['enable_ftp_upload'] = in_array( $_data['enable_ftp_upload'], array(
					'yes',
					'no'
				) ) ? $_data['enable_ftp_upload'] : $defaults['enable_ftp_upload'];
			}
			unset( $args['enable_ftp_upload'] );
		}

		if ( array_key_exists( 'enable_cdata', $args ) ) {
			$data['enable_cdata'] = strtolower( $args['enable_cdata'] );
			if ( ! in_array( $data['enable_cdata'], array( 'yes', 'no' ) ) ) {
				$data['enable_cdata'] = in_array( $_data['enable_cdata'], array(
					'yes',
					'no'
				) ) ? $_data['enable_cdata'] : $defaults['enable_cdata'];
			}
			unset( $args['enable_cdata'] );
		}

		if ( ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( has_filter( "woo_feed_save_{$key}_option" ) ) {
					$data[ $key ] = apply_filters( "woo_feed_save_{$key}_option", sanitize_text_field( $value ) );
				}
			}
		}

		if ( array_key_exists( 'woo_feed_taxonomy', $args ) ) {
			$data['woo_feed_taxonomy'] = $args['woo_feed_taxonomy'];
		}

		if ( array_key_exists( 'woo_feed_identifier', $args ) ) {
			$data['woo_feed_identifier'] = $args['woo_feed_identifier'];
		}

		return update_option( 'woo_feed_settings', $data, false );
	}
}
