<?php

namespace NativeRent;

use NativeRent\Admin\Self_Checking_Report;

use function defined;
use function delete_option;
use function get_option;
use function implode;
use function is_array;
use function is_string;
use function json_decode;
use function mt_rand;
use function sanitize_text_field;
use function sprintf;
use function strlen;
use function update_option;
use function wp_json_encode;

use const NATIVERENT_PLUGIN_VERSION;

defined( 'ABSPATH' ) || exit;

/**
 * Native Rent options class
 *
 * @package    nativerent
 */
class Options {
	const OPT_GROUP = 'nativerent';
	const OPT_INVALID_TOKEN = 'invalidToken';
	const OPT_ADUNITS_CONFIG = 'adUnitsConfig';
	const OPT_ADV_PATTERNS = 'advPatterns';
	const OPT_SITE_ID = 'siteID';
	const OPT_MONETIZATIONS = 'monetizations';
	const OPT_SITE_MODERATION_STATUS = 'siteModerationStatus';

	/**
	 * Options list.
	 *
	 * @var string[]
	 */
	protected static $opts
		= array(
			self::OPT_INVALID_TOKEN,
			self::OPT_ADUNITS_CONFIG,
			self::OPT_ADV_PATTERNS,
			self::OPT_SITE_ID,
			self::OPT_MONETIZATIONS,
			'token',
		);

	/**
	 * Get full name for option
	 *
	 * @param  string $name  Option name.
	 *
	 * @return string
	 */
	protected static function option( $name ) {
		return sprintf( '%s.%s', self::OPT_GROUP, $name );
	}

	/**
	 * Option setter
	 *
	 * @param  string|null $name   Name of option.
	 * @param  mixed       $value  Option value.
	 *
	 * @return bool
	 */
	public static function set( $name = null, $value = '' ) {
		if ( empty( $name ) ) {
			return false;
		}

		return update_option( self::option( $name ), $value );
	}

	/**
	 * Option getter
	 *
	 * @param  string|null $name  Option name.
	 *
	 * @return mixed
	 */
	public static function get( $name = null ) {
		if ( empty( $name ) ) {
			return false;
		}

		$val = get_option( self::option( $name ), false );
		if ( self::is_json( $val ) ) {
			return json_decode( $val, true );
		}

		return $val;
	}

	/**
	 * Check if variable is JSON.
	 *
	 * @param  string $variable  String data.
	 *
	 * @return bool
	 */
	private static function is_json( $variable ) {
		return (
			is_string( $variable )
			&& ! empty( $variable )
			&& ( '{' == @$variable[0] || '[' == @$variable[0] )
		);
	}


	/**
	 * Delete option
	 *
	 * @param  string|null $name  Option name.
	 *
	 * @return bool
	 */
	public static function delete( $name = null ) {
		if ( empty( $name ) ) {
			return false;
		}

		return delete_option( self::option( $name ) );
	}

	/**
	 * Drop plugin options
	 *
	 * @return void
	 */
	public static function uninstall() {
		foreach ( self::$opts as $opt ) {
			self::delete( $opt );
		}
		delete_option( 'nativerent_version' );
	}

	/**
	 * Check if API is authenticated or not.
	 *
	 * @return bool
	 */
	public static function authenticated() {
		return ( ! empty( self::get( self::OPT_SITE_ID ) ) && ! empty( self::get( 'token' ) ) );
	}

	/**
	 * Check last API token status
	 *
	 * @return bool
	 */
	public static function invalid_token() {
		return self::get( self::OPT_INVALID_TOKEN );
	}

	/**
	 * Set value for `invalid_token` flag
	 *
	 * @param  bool $val  Flag value.
	 *
	 * @return void
	 */
	public static function set_invalid_token_flag( $val = true ) {
		if ( ! $val ) {
			self::delete( self::OPT_INVALID_TOKEN );
		} else {
			self::set( self::OPT_INVALID_TOKEN, true );
		}
	}

	/**
	 * Update ad-units config
	 *
	 * @param  array $data  Configmap.
	 *
	 * @return bool
	 */
	public static function update_adunits_config( $data = array() ) {
		return self::set( self::OPT_ADUNITS_CONFIG, wp_json_encode( $data ) );
	}

	/**
	 * Get actual ad-units configmap
	 *
	 * @return array|array[]
	 */
	public static function get_adunits_config() {
		$data = self::get( self::OPT_ADUNITS_CONFIG );
		if ( is_string( $data ) ) {
			$data = json_decode( $data, true );
		}
		if ( ! is_array( $data ) ) {
			$data = array();
		}

		return self::create_adunits_config_map( is_array( $data ) ? $data : array(), false );
	}

	/**
	 * Create and returns ad-units config map from POST data
	 *
	 * @param  array $data          Raw data.
	 * @param  bool  $from_request  If true, then sanitizers are applied.
	 *
	 * @return array
	 */
	public static function create_adunits_config_map( $data = array(), $from_request = true ) {
		$res = array(
			'regular' => array(),
			'ntgb'    => array(),
		);
		foreach ( $res as $type => $config ) {
			switch ( $type ) {
				case 'ntgb':
					$res[ $type ] = self::make_ntgb_units_config(
						isset( $data[ $type ] ) ? $data[ $type ] : array(),
						$from_request
					);
					break;
				default:
					$res[ $type ] = self::make_regular_units_config(
						isset( $data[ $type ] ) ? $data[ $type ] : array(),
						$from_request
					);
					break;
			}
		}

		return $res;
	}

	/**
	 * @param  array $config  NTGB units config.
	 *
	 * @return array
	 */
	public static function get_active_ntgb_units( $config ) {
		if ( ! is_array( $config ) || empty( $config ) ) {
			return array();
		}

		return array_filter(
			$config,
			function ( $unit_conf ) {
				return empty( $unit_conf['settings']['inactive'] );
			}
		);
	}

	/**
	 * Making regular ad-units config map.
	 *
	 * @param  array $data          Raw data.
	 * @param  bool  $from_request  True if data received from request.
	 *
	 * @return array
	 */
	private static function make_regular_units_config( $data, $from_request = true ) {
		$res = array(
			'horizontalTop'    => array(
				'insert'         => 'after',
				'autoSelector'   => 'firstParagraph',
				'customSelector' => '',
				'settings'       => array(),
			),
			'horizontalMiddle' => array(
				'insert'         => 'after',
				'autoSelector'   => 'middleParagraph',
				'customSelector' => '',
				'settings'       => array(),
			),
			'horizontalBottom' => array(
				'insert'         => 'after',
				'autoSelector'   => 'lastParagraph',
				'customSelector' => '',
				'settings'       => array(),
			),
			'popupTeaser'      => array(
				'insert'         => 'after',
				'autoSelector'   => '',
				'customSelector' => '',
				'settings'       => array(
					'mobileTeaser'     => true,
					'mobileFullscreen' => true,
					'desktopTeaser'    => true,
				),
			),
		);

		foreach ( $res as $opt => $val ) {
			$res[ $opt ] = self::make_unit_placement_props(
				isset( $data[ $opt ] ) ? $data[ $opt ] : array(),
				$val,
				$from_request
			);
			if ( 'popupTeaser' === $opt ) {
				$res[ $opt ]['settings'] = array(
					'mobileTeaser'     => isset( $data[ $opt ]['settings']['mobileTeaser'] )
						? ! empty( $data[ $opt ]['settings']['mobileTeaser'] )
						: ! $from_request,
					'mobileFullscreen' => isset( $data[ $opt ]['settings']['mobileFullscreen'] )
						? ! empty( $data[ $opt ]['settings']['mobileFullscreen'] )
						: ! $from_request,
					'desktopTeaser'    => isset( $data[ $opt ]['settings']['desktopTeaser'] )
						? ! empty( $data[ $opt ]['settings']['desktopTeaser'] )
						: ! $from_request,
				);
			}
		}

		return $res;
	}

	/**
	 * Making NTGB ad-units config map.
	 *
	 * @param  array $data          Raw data.
	 * @param  bool  $from_request  True if data received from request.
	 *
	 * @return array
	 */
	private static function make_ntgb_units_config( $data, $from_request = true ) {
		$max_ntgb_count = 3;
		$ntgb_defaults  = array(
			'1' => array(
				'insert'         => 'after',
				'autoSelector'   => 'middleParagraph',
				'customSelector' => '',
				'settings'       => array(
					'inactive'     => false,
					'fallbackCode' => '',
				),
			),
			'2' => array(
				'insert'         => 'after',
				'autoSelector'   => 'lastParagraph',
				'customSelector' => '',
				'settings'       => array(
					'inactive'     => true,
					'fallbackCode' => '',
				),
			),
			'3' => array(
				'insert'         => 'after',
				'autoSelector'   => 'firstParagraph',
				'customSelector' => '',
				'settings'       => array(
					'inactive'     => true,
					'fallbackCode' => '',
				),
			),
		);

		$res       = array();
		$_inserted = 0;
		foreach ( $data as $id => $config ) {
			$unit_key                                     = (string) $id;
			$res[ $unit_key ]                             = self::make_unit_placement_props(
				$config,
				isset( $ntgb_defaults[ $id ] ) ? $ntgb_defaults[ $id ] : $ntgb_defaults['1'],
				$from_request
			);
			$res[ $unit_key ]['settings']['inactive']     = ! empty( $config['settings']['inactive'] );
			$res[ $unit_key ]['settings']['fallbackCode'] = isset( $config['settings']['fallbackCode'] )
				? ( $from_request
					? base64_encode( trim( wp_unslash( $config['settings']['fallbackCode'] ) ) )
					: $config['settings']['fallbackCode']
				) : '';

			$_inserted ++;
			if ( $_inserted >= $max_ntgb_count ) {
				break;
			}
		}

		$res_count = count( $res );
		if ( $res_count < $max_ntgb_count ) {
			for ( $i = $res_count + 1; $i < $max_ntgb_count + 1; $i ++ ) {
				$res[ (string) $i ] = self::make_unit_placement_props(
					array(),
					$ntgb_defaults[ (string) $i ],
					$from_request
				);
			}
		}

		return $res;
	}

	/**
	 * Making map with ad-unit placement props.
	 *
	 * @param  array{insert: string, autoSelector: string, customSelector: string} $config        Unit config data.
	 * @param  array{insert: string, autoSelector: string, customSelector: string} $defaults      Default props values.
	 * @param  bool                                                                $from_request  True if data received from request.
	 *
	 * @return array{insert: string, autoSelector: string, customSelector: string}
	 */
	private static function make_unit_placement_props( $config, $defaults, $from_request = true ) {
		$res = array(
			'insert'         => 'after',
			'autoSelector'   => '',
			'customSelector' => '',
			'settings'       => array(),
		);

		foreach ( array_keys( $res ) as $opt ) {
			if ( isset( $config[ $opt ] ) ) {
				if ( is_string( $config[ $opt ] ) ) {
					$res[ $opt ] = ( $from_request
						? sanitize_text_field( wp_unslash( $config[ $opt ] ) )
						: $config[ $opt ]
					);
				} else {
					$res[ $opt ] = $config[ $opt ];
				}
			} elseif ( isset( $defaults[ $opt ] ) ) {
				$res[ $opt ] = $defaults[ $opt ];
			}
		}

		return $res;
	}

	/**
	 * Update patterns data
	 *
	 * @param  array $data  Patterns data.
	 *
	 * @return bool
	 */
	public static function update_adv_patterns( $data = array() ) {
		$data_in_json = wp_json_encode( $data );

		$result = self::set( self::OPT_ADV_PATTERNS, $data_in_json );
		if ( ! $result ) {
			return $result;
		}

		if ( Cache_Handler::is_compatability_mode() ) {
			Admin\Cache_Actions::need_to_clear_cache( '2' ); // Need to clear cache after settings update.
		}

		return $result;
	}

	/**
	 * Get patterns for adblock
	 *
	 * @return array
	 */
	public static function get_adv_patterns() {
		$data = self::get( self::OPT_ADV_PATTERNS );
		if ( empty( $data ) ) {
			return array();
		}
		if ( is_string( $data ) ) {
			$data = json_decode( $data, true );
		}

		return is_array( $data ) ? $data : array();
	}

	/**
	 * Generate secret key
	 *
	 * @param  int $length  Optional. Determines size of secret key. Default is 32.
	 *
	 * @return string
	 */
	public static function generate_secret_key( $length = 32 ) {
		$alphabet   = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$alpha_len  = strlen( $alphabet ) - 1;
		$secret_key = array();
		for ( $i = 0; $i < $length; $i ++ ) {
			$secret_key[] = $alphabet[ mt_rand( 0, $alpha_len ) ];
		}
		$secret_key = implode( $secret_key );
		self::set( 'secretKey', $secret_key );

		return $secret_key;
	}

	/**
	 * Get current site ID
	 *
	 * @return string|null
	 */
	public static function get_site_id() {
		$id = self::get( self::OPT_SITE_ID );

		return ( ! empty( $id ) ? $id : null );
	}

	/**
	 * Get current plugin version.
	 *
	 * @return string|null
	 */
	public static function get_version() {
		// TODO: нужно заменить опцию `nativerent_version` на `nativerent.version`.
		$version = get_option( 'nativerent_version' );
		if ( ! is_string( $version ) ) {
			return null;
		}

		return $version;
	}

	/**
	 * Updating current version
	 *
	 * @return void
	 */
	public static function setup_version() {
		update_option( 'nativerent_version', NATIVERENT_PLUGIN_VERSION );
	}

	/**
	 * Get monetization info
	 *
	 * @return Monetizations
	 */
	public static function get_monetizations() {
		$raw_data = self::get( self::OPT_MONETIZATIONS );

		return Monetizations::hydrate( is_array( $raw_data ) ? $raw_data : array() );
	}

	/**
	 * Update monetization info
	 *
	 * @param  Monetizations $monetizations  Monetizations instance.
	 *
	 * @return bool
	 */
	public static function set_monetizations( Monetizations $monetizations ) {
		$json = wp_json_encode( $monetizations->convert_to_array() );
		if ( ! empty( $json ) ) {
			return self::set( self::OPT_MONETIZATIONS, $json );
		}
	}

	/**
	 * Get current site moderation status.
	 *
	 * @return Site_Moderation_Status
	 */
	public static function get_site_moderation_status() {
		$raw_value = self::get( self::OPT_SITE_MODERATION_STATUS );

		return new Site_Moderation_Status( is_numeric( $raw_value ) ? $raw_value : null );
	}

	/**
	 * Updating site moderation status value.
	 *
	 * @param  Site_Moderation_Status $status  Site moderation status instance.
	 *
	 * @return bool
	 */
	public static function set_site_moderation_status( Site_Moderation_Status $status ) {
		return self::set( self::OPT_SITE_MODERATION_STATUS, $status->get_value() );
	}
}
