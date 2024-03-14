<?php
/**
 * Native Rent API class
 *
 * @package nativerent
 */

namespace NativeRent;

use Exception;
use NativeRent\Admin\Cache_Actions;

use function nativerent_get_plugin_state;
use function nativerent_get_wp_info;

use const NATIVERENT_PLUGIN_VERSION;

defined( 'ABSPATH' ) || exit;

/**
 * Class API
 */
class API extends APICore {
	const ACTIVATED_PLUGIN_STATUS = 'activated';
	const DEACTIVATED_PLUGIN_STATUS = 'deactivated';
	const UNINSTALLED_PLUGIN_STATUS = 'uninstalled';

	/**
	 * Validation `siteID` value.
	 *
	 * @param  array $request  Request payload.
	 *
	 * @return bool
	 */
	private static function is_valid_site_id( $request ) {
		return ( ! empty( $request['siteID'] ) && Options::get_site_id() === $request['siteID'] );
	}

	/**
	 * Authorize integration
	 *
	 * @param  string $login     Login.
	 * @param  string $password  Password.
	 * @param  string $domain    Domain name.
	 *
	 * @return array{success: bool, errors: string[]}|false
	 */
	public static function auth( $login, $password, $domain ) {
		$result     = array(
			'success' => false,
			'errors'  => array(),
		);
		$idn        = new DNAConvert();
		$domain     = $idn->decode( $domain );
		$secret_key = Options::generate_secret_key();
		$response   = self::_request(
			'auth',
			array(
				'domain'    => $domain,
				'email'     => $login,
				'password'  => $password,
				'secretKey' => $secret_key,
			),
			array(
				'specialAPIURL' => '/integration/wp/',
				'disableToken'  => true,
				'timeout'       => 10,
			)
		);

		if ( is_array( $response ) ) {
			try {
				if ( isset( $response['result'] ) && 1 == $response['result'] ) {
					$result['success'] = true;
					Options::set( 'siteID', $response['siteID'] );
					Options::set( 'token', $response['token'] );
					// Setup patterns.
					if ( isset( $response['settings']['patterns'] ) ) {
						Options::update_adv_patterns( $response['settings']['patterns'] );
					}
					// Setup monetization info.
					if ( isset( $response['settings']['monetizations'] ) ) {
						Options::set_monetizations( Monetizations::hydrate( $response['settings']['monetizations'] ) );
					}
					// Setup site moderation status.
					if ( isset( $response['settings']['siteModerationStatus'] ) ) {
						Options::set_site_moderation_status(
							new Site_Moderation_Status( $response['settings']['siteModerationStatus'] )
						);
					}
					Options::set_invalid_token_flag( false );
					self::status( 'activated' );
				} elseif ( isset( $response['errors'] ) ) {
					$result['errors'] = $response['errors'];
				}

				return $result;
			} catch ( Exception $e ) {
				nativerent_report_error( $e, array( 'api_method' => 'auth' ) );

				return false;
			}
		}

		return false;
	}

	/**
	 * Get a status
	 *
	 * @param  int $status  Status.
	 *
	 * @return bool
	 */
	public static function status( $status ) {
		$attempts = 0;
		do {
			$response = self::_request(
				'status',
				array(
					'siteID'  => Options::get( 'siteID' ),
					'version' => NATIVERENT_PLUGIN_VERSION,
					'status'  => $status,
					'cmsInfo' => nativerent_get_wp_info(),
					'state'   => nativerent_get_plugin_state(),
				),
				array( 'timeout' => 5 )
			);
			$attempts ++;
		} while ( 1 != @$response['result'] && $attempts < 2 );

		return ( 1 == @$response['result'] );
	}

	/**
	 * Send settings
	 *
	 * @param  array $data  Settings data.
	 *
	 * @return bool
	 */
	public static function settings( $data = array() ) {
		$data['siteID']  = Options::get( 'siteID' );
		$data['version'] = NATIVERENT_PLUGIN_VERSION;
		$data['cmsInfo'] = nativerent_get_wp_info();

		$attempts = 0;
		do {
			$response = self::_request( 'settings', $data, array( 'timeout' => 5 ) );
			$attempts ++;
		} while ( 1 != @$response['status'] && $attempts < 3 );

		return ( 1 == @$response['status'] );
	}

	/**
	 * Send actual plugin state to NR.
	 *
	 * @return bool
	 */
	public static function send_state() {
		$attempts = 0;
		do {
			$response = self::_request(
				'state',
				array(
					'siteID' => Options::get_site_id(),
					'state'  => nativerent_get_plugin_state(),
				),
				array( 'timeout' => 5 )
			);
			$attempts ++;
		} while ( 1 != @$response['result'] && $attempts < 2 );

		return ( 1 == @$response['result'] );
	}

	/**
	 * Get and apply actual monetizations from Native Rent.
	 *
	 * @return bool
	 */
	public static function load_monetizations() {
		$attempts = 0;
		do {
			$response = self::_request(
				'monetizations',
				array(
					'siteID' => Options::get( 'siteID' ),
				),
				array( 'timeout' => 5 )
			);
			$attempts ++;
		} while ( 1 != @$response['result'] && $attempts < 2 );

		if ( ! is_array( $response ) ) {
			return false;
		}

		// Init clear cache flag.
		$clear_cache = false;

		// Updating monetizations.
		$monetizations = @$response['monetizations'];
		if ( ! empty( $monetizations ) ) {
			$clear_cache = Options::set_monetizations( Monetizations::hydrate( $response['monetizations'] ) );
		}

		// Updating site moderation status.
		$site_status = @$response['siteModerationStatus'];
		if ( ! empty( $site_status ) ) {
			$clear_cache |= Options::set_site_moderation_status( new Site_Moderation_Status( $site_status ) );
		}

		// Clear cache.
		if ( $clear_cache ) {
			Cache_Actions::need_to_clear_cache();
		}

		return ( 1 == @$response['result'] );
	}

	/**
	 * Check siteID
	 *
	 * @return void
	 * @api POST check
	 */
	public static function check() {
		$request = self::_get_request();
		if ( ! self::is_valid_site_id( $request ) ) {
			self::_response( array( 'result' => 0 ) );

			return;
		}

		self::_response( array( 'result' => 1 ) );
	}

	/**
	 * @return void
	 * @throws \Exception
	 * @api POST articles
	 */
	public static function articles() {
		$request = self::_get_request();
		if ( ! self::is_valid_site_id( $request ) ) {
			self::_response( array( 'result' => 0 ) );

			return;
		}
		$per_page = isset( $request['perPage'] ) ? $request['perPage'] : 5;
		$page     = isset( $request['page'] ) ? $request['page'] : 1;
		$posts    = nativerent_get_posts( $page, $per_page );

		self::_response(
			array(
				'result'   => 1,
				'articles' => is_array( $posts ) ? nativerent_get_posts_permalinks( $posts ) : array(),
			)
		);
	}

	/**
	 * Update adv patterns
	 *
	 * @return void
	 * @api POST updateAdvPatterns
	 */
	public static function update_adv_patterns() {
		$request = self::_get_request();
		if ( ! self::is_valid_site_id( $request ) ) {
			self::_response( array( 'result' => 0 ) );

			return;
		}

		try {
			Options::update_adv_patterns( $request['patterns'] );
		} catch ( Exception $e ) {
			nativerent_report_error( $e );
			self::_response( array( 'result' => 0 ) );
		}
		self::_response( array( 'result' => 1 ) );
	}

	/**
	 * Send vars
	 * Used in Maintenance
	 *
	 * @return void
	 * @api        POST vars
	 * @deprecated use `state` instead this
	 */
	public static function vars() {
		$request = self::_get_request();
		if ( ! self::is_valid_site_id( $request ) ) {
			self::_response( array( 'result' => 0 ) );

			return;
		}

		self::_response(
			array(
				'result'  => 1,
				'options' => array(
					'adUnitsConfig' => Options::get_adunits_config(),
					'lightMode'     => 0,
				),
				'version' => NATIVERENT_PLUGIN_VERSION,
				'cmsInfo' => nativerent_get_wp_info(),
			)
		);
	}

	/**
	 * Get actual plugin state.
	 *
	 * @return void|null
	 * @api POST state
	 */
	public static function state() {
		$request = self::_get_request();
		if ( ! self::is_valid_site_id( $request ) ) {
			self::_response( array( 'result' => 0 ) );

			return;
		}

		self::_response(
			array(
				'result' => 1,
				'state'  => nativerent_get_plugin_state(),
			)
		);
	}

	/**
	 * Updating monetization info
	 *
	 * @return void
	 * @api POST updateMonetizations
	 */
	public static function update_monetizations() {
		$request = self::_get_request();
		if ( ! self::is_valid_site_id( $request ) ) {
			self::_response( array( 'result' => 0 ) );

			return;
		}

		if (
			Options::set_monetizations( Monetizations::hydrate( $request['monetizations'] ) )
			|| Options::set_site_moderation_status( new Site_Moderation_Status( $request['siteModerationStatus'] ) )
		) {
			Cache_Actions::need_to_clear_cache();
		}

		self::_response( array( 'result' => 1 ) );
	}

	/**
	 * Updating some adUnitsConfig props.
	 *
	 * @return void
	 * @api POST updateAdUnitsConfig
	 */
	public static function update_ad_units_config() {
		$request = self::_get_request();
		if ( ! self::is_valid_site_id( $request ) ) {
			self::_response( array( 'result' => 0 ) );

			return;
		}

		$payload = isset( $request['adUnitsConfig'] ) ? $request['adUnitsConfig'] : array();
		if ( empty( $payload ) || ! is_array( $payload ) ) {
			self::_response( array( 'result' => 0 ) );

			return;
		}

		// Safe patching config.
		$patchable_props = array( 'insert', 'autoSelector', 'customSelector' );
		$units_conf      = Options::get_adunits_config();

		// If received old version payload struct.
		if ( ! isset( $payload['regular'] ) || isset( $payload['ntgb']['insert'] ) ) {
			$reformatted = array();
			if ( ! empty( $payload['ntgb'] ) ) {
				$first_ntgb_key                         = array_keys( $units_conf['ntgb'] )[0];
				$reformatted['ntgb'][ $first_ntgb_key ] = $payload['ntgb'];
				unset( $payload['ntgb'] );
			}
			$reformatted['regular'] = $payload;
			$payload                = $reformatted;
		}

		// Config types (regular, ntgb, ...).
		foreach ( $units_conf as $type => $config ) {
			if ( empty( $payload[ $type ] ) ) {
				continue;
			}

			// Unit names (topHorizontal, ...).
			foreach ( $config as $unit => $opts ) {
				if ( empty( $payload[ $type ][ $unit ] ) ) {
					continue;
				}
				if ( 'popupTeaser' === $unit && 'regular' === $type ) {
					continue;
				}

				// Unit props (insert, autoSelector, ...).
				foreach ( $patchable_props as $prop ) {
					if ( ! array_key_exists( $prop, $payload[ $type ][ $unit ] ) ) {
						continue;
					}

					$units_conf[ $type ][ $unit ][ $prop ] = sanitize_text_field(
						wp_unslash( $payload[ $type ][ $unit ][ $prop ] )
					);
				}
			}
		}

		if ( Options::update_adunits_config( $units_conf ) ) {
			Cache_Actions::need_to_clear_cache();
		};

		self::_response( array( 'result' => 1 ) );
	}
}
