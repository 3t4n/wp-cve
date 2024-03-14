<?php

namespace CTXFeed\V5\Common;

use CTXFeed\V5\Product\AttributeValueByType;
use CTXFeed\V5\Utility\Logs;
use CTXFeed\V5\Utility\Settings;

/**
 * Class Helper
 *
 * @package    CTXFeed\V5\Common
 * @subpackage CTXFeed\V5\Common
 */
class Helper {
	/**
	 * Object to array.
	 *
	 * @param object|array $obj
	 *
	 * @return array|object
	 */
	public static function object_to_array( $obj ) {
		//only process if it's an object or array being passed to the function
		if ( is_object( $obj ) || is_array( $obj ) ) {
			$arr = (array) $obj;
			foreach ( $arr as &$item ) {
				//recursively process EACH element regardless of type
				$item = self::object_to_array( $item );
			}

			return $arr;
		} //otherwise (i.e. for scalar values) return without modification

		return $obj;
	}

	/**
	 * Remove pro templates form merchant array.
	 *
	 * @param array $merchants
	 *
	 * @return array
	 */
	public static function filter_merchant( $merchants ) {

		if ( WOO_FEED_PLUGIN_FILE === 'woo-feed.php' ) {
			$removeTemplates = array( 'custom2' );
			foreach ( $merchants as $index => $group ) {
				foreach ( $group['options'] as $option_name => $option_value ) {
					if ( in_array( $option_name, $removeTemplates ) ) {
						unset( $merchants[ $index ]['options'][ $option_name ] );
					}
				}
			}
		}

		return $merchants;
	}

	/**
	 * Get plugin file i.e woo-feed.php or webappick-product-feed-for-woocommerce-pro.php
	 * @return false|mixed|string
	 */
	public static function get_plugin_file() {
		return WOO_FEED_PLUGIN_FILE;
	}

	/**
	 * Is the plugin is pro
	 * @return bool
	 */
	public static function is_pro() {
		if ( 'woo-feed.php' === WOO_FEED_PLUGIN_FILE ) {
			return false;
		}

		//TODO CHECK IF LICENSE IS ACTIVE FOR MORE TRANSPARENCY.
		if ( 'webappick-product-feed-for-woocommerce-pro.php' === WOO_FEED_PLUGIN_FILE ) {
			return true;
		}

		return false;

	}

	/**
	 * Get Feed Directory
	 *
	 * @param string $provider
	 * @param string $feedType
	 *
	 * @return string
	 */
	public static function get_file_dir( $provider, $feedType ) {
		$upload_dir = wp_get_upload_dir();

		return sprintf( '%s/woo-feed/%s/%s', $upload_dir['basedir'], $provider, $feedType );
	}

	/**
	 * str_replace() wrapper with trim()
	 *
	 * @param mixed $search The value being searched for, otherwise known as the needle.
	 *                          An array may be used to designate multiple needles.
	 * @param mixed $replace The replacement value that replaces found search values.
	 *                          An array may be used to designate multiple replacements.
	 * @param mixed $subject The string or array being searched and replaced on,
	 *                          otherwise known as the haystack.
	 * @param string $charlist [optional]
	 *                          Optionally, the stripped characters can also be specified using the charlist parameter.
	 *                          Simply list all characters that you want to be stripped.
	 *                          With this you can specify a range of characters.
	 *
	 * @return array|string
	 */
	public static function str_replace_trim( $search, $replace, $subject, $charlist = " \t\n\r\0\x0B" ) {
		$replaced = str_replace( $search, $replace, $subject );
		if ( is_array( $replaced ) ) {
			return array_map(
				function ( $item ) use ( $charlist ) {
					return trim( $item, $charlist );
				},
				$replaced
			);
		} else {
			return trim( $replaced, $charlist );
		}
	}

	/**
	 * Remove Feed Option Name Prefix and return the slug
	 *
	 * @param string $feed_option_name
	 *
	 * @return string
	 */
	public static function extract_feed_option_name( $feed_option_name ) {
		return str_replace( array( 'wf_feed_', 'wf_config' ), '', $feed_option_name );
	}


	/**
	 * Get Feed File URL
	 *
	 * @param string $fileName
	 * @param string $provider
	 * @param string $type
	 *
	 * @return string
	 */
	public static function get_file_url( $fileName, $provider, $type ) {
		$fileName   = self::extract_feed_option_name( $fileName );
		$upload_dir = wp_get_upload_dir();

		return esc_url(
			sprintf(
				'%s/woo-feed/%s/%s/%s.%s',
				$upload_dir['baseurl'],
				$provider,
				$type,
				$fileName,
				$type
			)
		);
	}


	/**
	 * Get Feed File URL
	 *
	 * @param string $fileName
	 * @param string $provider
	 * @param string $type
	 *
	 * @return string
	 */
	public static function get_file( $fileName, $provider, $type ) {
		$fileName = self::extract_feed_option_name( $fileName );
		$path     = self::get_file_path( $provider, $type );

		return sprintf( '%s/%s.%s', untrailingslashit( $path ), $fileName, $type );
	}

	/**
	 * Get File Path for feed or the file upload path for the plugin to use.
	 *
	 * @param string $provider provider name.
	 * @param string $type feed file type.
	 *
	 * @return string
	 */
	public static function get_file_path( $provider = '', $type = '' ) {
		$upload_dir = wp_get_upload_dir();

		return sprintf( '%s/woo-feed/%s/%s/', $upload_dir['basedir'], $provider, $type );
	}

	/**
	 * Remove temporary feed files
	 *
	 * @param array $config Feed config
	 * @param string $fileName feed file name.
	 *
	 * @return void
	 */
	public static function unlink_tempFiles( $config, $fileName ) {
		$type = $config['feedType'];
		$ext  = $type;
		$path = self::get_file_dir( $config['provider'], $type );

		if ( 'csv' === $type || 'tsv' === $type || 'xls' === $type || 'xlsx' === $type ) {
			$ext = 'json';
		}
		$files = array(
			'headerFile' => $path . '/' . AttributeValueByType::FEED_TEMP_HEADER_PREFIX . $fileName . '.' . $ext,
			'bodyFile'   => $path . '/' . AttributeValueByType::FEED_TEMP_BODY_PREFIX . $fileName . '.' . $ext,
			'footerFile' => $path . '/' . AttributeValueByType::FEED_TEMP_FOOTER_PREFIX  . $fileName . '.' . $ext,
		);

		Logs::write_log( $config['filename'], sprintf( 'Deleting Temporary Files (%s).', implode( ', ', array_values( $files ) ) ) );
		foreach ( $files as $key => $file ) {
			if ( file_exists( $file ) ) {
				unlink( $file ); // phpcs:ignore
			}
		}
	}


	/**
	 * Clear cache data.
	 *
	 * @param int _ajax_clean_nonce nonce number.
	 *
	 * @since 4.1.2
	 */
	public static function clear_cache_data( $cache_types = [] ) {

		if ( empty( $cache_options ) ) {
			$cache_types = [
				"woo_feed_attributes",
				"woo_feed_category_mapping",
				"woo_feed_dynamic_attributes",
				"woo_feed_attribute_mapping",
				"woo_feed_wp_options"
			];
		}

		global $wpdb;
		//TODO add wpdb prepare statement
		$result = $wpdb->query( "DELETE FROM $wpdb->options WHERE ({$wpdb->options}.option_name LIKE '_transient_timeout___woo_feed_cache_%') OR ({$wpdb->options}.option_name LIKE '_transient___woo_feed_cache_%')" ); // phpcs:ignore

		if( count( $cache_types )> 0) {
			$prefix = 'wf_dismissed';
			foreach ( $cache_types as $value ){
				$id     = $value;
				update_option( "{$prefix}_{$id}", true, false );
			}

		}

		return true;
	}

	/**
	 * Get Sellers User Role Based On Multi Vendor Plugin
	 *
	 * @return string
	 */
	public static function get_multi_vendor_user_role() {
		$map         = array(
			'WeDevs_Dokan' => 'seller',
			'WC_Vendors'   => 'vendor',
			'YITH_Vendor'  => 'yith_vendor',
			'MVX'          => 'dc_vendor',
			'WCFMmp'       => 'wcfm_vendor',
		);
		$vendor_role = '';
		foreach ( $map as $class => $role ) {
			if ( class_exists( $class, false ) ) {
				$vendor_role = $role;
				break;
			}
		}

		/**
		 * Filter Vendor User Role
		 *
		 * @param string $vendor_role
		 *
		 * @since 3.4.0
		 */
		return apply_filters( 'woo_feed_multi_vendor_user_role', $vendor_role );
	}

	public  static  function is_debugging_enabled() {
		return self::get_options( 'enable_error_debugging', false ) === 'on';
	}

	/**
	 * Get saved settings.
	 *
	 * @param string $key Option name.
	 *                        All default values will be returned if this set to 'defaults',
	 *                        all settings will be return if set to 'all'.
	 * @param bool $default value to return if no matching data found for the key (option)
	 *
	 * @return array|bool|string|mixed
	 * @since 3.3.11
	 */
	public static function get_options( $key, $default = false ) {
		$defaults = array(
			'per_batch'                  => 200,
			'product_query_type'         => 'wc',
			'variation_query_type'       => 'individual',
			'enable_error_debugging'     => 'off',
			'cache_ttl'                  => 6 * HOUR_IN_SECONDS,
			'overridden_structured_data' => 'off',
			'disable_mpn'                => 'enable',
			'disable_brand'              => 'enable',
			'disable_pixel'              => 'enable',
			'pixel_id'                   => '',
			'disable_remarketing'        => 'disable',
			'remarketing_id'             => '',
			'remarketing_label'          => '',
			'allow_all_shipping'         => 'no',
			'only_free_shipping'         => 'yes',
			'only_local_pickup_shipping' => 'no',
			'enable_ftp_upload'          => 'no',
			'enable_cdata'               => 'no',
			'woo_feed_taxonomy'          => array(
				'brand' => 'disable',
			),
			'woo_feed_identifier'        => array(
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
		);

		/**
		 * Add defaults without chainging the core values.
		 *
		 * @param array $defaults
		 *
		 * @since 3.3.11
		 */
		$defaults = wp_parse_args( apply_filters( 'woo_feed_settings_extra_defaults', array() ), $defaults );

		if ( 'defaults' === $key ) {
			return $defaults;
		}

		$settings = wp_parse_args( get_option( 'woo_feed_settings', array() ), $defaults );

		if ( 'all' === $key ) {
			return $settings;
		}

		if ( array_key_exists( $key, $settings ) ) {
			return $settings[ $key ];
		}

		return $default;
	}

	/**
	 * Remove Option Name Prefix and return the slug
	 *
	 * @param string $feed_option_name
	 *
	 * @return string
	 */
	public static function extract_option_name( $feed_option_name, $prefix ) {
		return str_replace( array( $prefix ), '', $feed_option_name );
	}

	public static  function access_protected_props_and_methods($obj, $prop) {
		$reflection = new \ReflectionClass($obj);
		$property = $reflection->getProperty($prop);
		$property->setAccessible(true);
		return $property->getValue($obj);
	}

	/**
	 * Get Formatted URL
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	public static function woo_feed_get_formatted_url( $url = '' ) {
		if ( ! empty( $url ) ) {
			if ( substr( trim( $url ), 0, 4 ) === 'http' || substr(
					trim( $url ),
					0,
					3
				) === 'ftp' || substr( trim( $url ), 0, 4 ) === 'sftp' ) {
				$url = str_replace( ' ' , '%20', $url );
				return rtrim( $url, '/' );
			} else {
				$base = get_site_url();
				$url = str_replace( ' ' , '%20', $url );
				$url  = $base . $url;

				return rtrim( $url, '/' );
			}
		}

		return '';
	}

}
