<?php

namespace StillBE\Plugin\CombineSocialPhotos;


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




final class Cron {


	const PREFIX = SB_CSP_PREFIX;

	const SCHEDULE_RECURRENCE_NAME            = SB_CSP_PREFIX. 'twice-a-day';
	const CRON_HOOK_NAME_CHECK_EXPIRED_TOKENS = SB_CSP_PREFIX. 'check-expired-tokens';
	const CRON_HOOK_NAME_BASIC_DISPLAY_API_ME = SB_CSP_PREFIX. 'basic-display-api-me';

	private static $instance = null;


	public static function init() {

		if( empty( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;

	}


	// Constructer
	private function __construct() {

		// Set WP-Cron Action for Updating IG Cache Data
		add_action( Basic_Display_API::ACTION_HOOK_GET_IG_MEDIA, [ Basic_Display_API::class, 'update_ig_media_cache' ] );
		add_action( Graph_API::ACTION_HOOK_GET_IG_MEDIA,         [ Graph_API::class,         'update_ig_media_cache' ] );

		// Set WP-Cron Action for Accessing to Ig CDN not to Eexpire
	//	add_action( Graph_API::ACTION_HOOK_DUMMY_ACCESS_CDN,     [ Graph_API::class, 'dummy_access_to_cdn_not_to_exprire' ] );
		wp_unschedule_hook( Graph_API::ACTION_HOOK_DUMMY_ACCESS_CDN   );

		// Set WP-Cron Action for Updating in the Background
		add_action( Graph_API::ACTION_HOOK_GET_HASHTAG_RECENT,   [ Graph_API::class, 'update_cache_in_the_background' ] );

		// Add a Custom Type to Periodic Execution Type of WP-Cron
		add_filter( 'cron_schedules', [ $this, 'add_custom_cron_schedule_interval' ] );

		// Check and Refresh Access Tokens that are Close to Expiration
		add_action( self::CRON_HOOK_NAME_CHECK_EXPIRED_TOKENS, [ $this, 'check_expired_tokens' ] );
		if( ! wp_next_scheduled( self::CRON_HOOK_NAME_CHECK_EXPIRED_TOKENS ) ) {
			wp_schedule_event( time() + 12 * 3600, self::SCHEDULE_RECURRENCE_NAME, self::CRON_HOOK_NAME_CHECK_EXPIRED_TOKENS );
		}

		// Update User Information in the Background when Using Basic Display API
		add_action( self::CRON_HOOK_NAME_BASIC_DISPLAY_API_ME, [ $this, 'basic_display_api_me' ] );
		if( ! wp_next_scheduled( self::CRON_HOOK_NAME_BASIC_DISPLAY_API_ME ) ) {
			wp_schedule_event( time() + 17 * 3600, 'daily', self::CRON_HOOK_NAME_BASIC_DISPLAY_API_ME );
		}

	}


	// Deactivate Cron Actions
	public static function deactivate_actions() {

		// wp_clear_scheduled_hook  ->  Unschedules all events attached to the hook with the specified arguments.

		// wp_unschedule_hook       ->  Unschedules all events attached to the hook.

		wp_unschedule_hook( self::CRON_HOOK_NAME_CHECK_EXPIRED_TOKENS );

		wp_unschedule_hook( self::CRON_HOOK_NAME_BASIC_DISPLAY_API_ME );

		wp_unschedule_hook( Graph_API::ACTION_HOOK_DUMMY_ACCESS_CDN   );

	}


	// 
	public function add_custom_cron_schedule_interval( $schedules ) {

		$schedules[ self::SCHEDULE_RECURRENCE_NAME ] = array(
			'interval' => 12 * 3600,
			'display'  => esc_html__( 'Twice a Day', 'still-be-combine-social-photos' ),
		);

		return $schedules;

	}


	// 
	public function check_expired_tokens( $id = -1, $retry_count = 0 ) {

		// Settings
		$setting  = get_option( Setting::SETTING_NAME, array() );
		$cache    = $setting['cache'] ?? array( 'refresh-token' => Setting::DEFAULT_REFRESH_TOKEN_DAYS );

		if( empty( $setting['accounts'] ) ) {
			return;
		}

		// Threashold Timestamp to Refresh
		$threashold_timestamp = time() + absint( $cache['refresh-token'] ?? Setting::DEFAULT_REFRESH_TOKEN_DAYS ) * 24 * 3600;

		// Sort Accounts Array; ASC
		usort( $setting['accounts'], function( $a, $b ) {
			if( ! isset( $a->id ) || ! isset( $b->id ) ) {
				return 1;
			}
			return intval( $a->id ) - intval( $b->id );
		} );

		// Select the Account that Needs to be Refreshed
		$account = null;
		$account_index = null;
		foreach( $setting['accounts'] as $_index => $_account ) {
			if( empty( $_account->id ) || (int) $_account->id <= $id ) {
				continue;
			}
			if( ! empty( $_account->token->token ) && isset( $_account->token->expire ) && (int) $_account->token->expire < $threashold_timestamp ) {
				$account = $_account;
				$account_index = absint( $_index );
				break;
			}
		}

		// Finished!!
		if( empty( $account ) ) {
			return;
		}

		if( 'Graph API' === ( $account->api_type ?? '___' ) ) {
			// for Graph API
			// Check for ENABLE access token
			$graph_api_token_test_result = Graph_API::get_me( $account->token->token );
			// DB Lock
			$is_db_locked = ! update_option( Setting::PREFIX. 'db-setting-locked', true, 'no' );
			// When the access token is disabled
			if( empty( $graph_api_token_test_result->id ) ) {
				if( $is_db_locked ) {
					wp_schedule_single_event(
						time() + 3600,
						self::CRON_HOOK_NAME_CHECK_EXPIRED_TOKENS,
						[ $account->id ]
					);
				} else {
					$setting['accounts'][ $account_index ]->disabled_access_token = true;
					error_log(
						sprintf(
							__( 'Disabled access token for ID = %d. Result of disabled flag set is "true".', 'still-be-combine-social-photos' ),
							$account->id
						)
					);
				}
			} else {
				unset( $setting['accounts'][ $account_index ]->disabled_access_token );
			}
			// Save
			if( ! $is_db_locked ) {
				$setting['accounts'] = array_reverse( $setting['accounts'] );
				$flag_disabled_access_token = update_option( Setting::SETTING_NAME, $setting );
				update_option( self::PREFIX. 'db-setting-locked', false, 'no' );
			}
			// No retry for check only
			$result = (object) array( 'ok' => true );
		} else {
			// Refresh Token for Basic Display API
			$result = Setting::refresh_token( $account->id, true );
		}

		// Next Param
		$result_ok   = !! ( $result->ok ?? false );
		$result_code = absint( $result->code ?? 0 );
		$current_id  = true  === $result_ok || 99 >   $result_code ? $account->id : $id;
		$retry_count = false === $result_ok && 99 === $result_code ? $retry_count + 1 : 0;
		$param = [ $current_id, $retry_count ];

		// Next Schedule
		wp_schedule_single_event(
			time() + 10,
			self::CRON_HOOK_NAME_CHECK_EXPIRED_TOKENS,
			$param
		);

	}


	// 
	public function basic_display_api_me( $id = -1 ) {

		// Settings
		$setting  = get_option( Setting::SETTING_NAME, array() );
		$accounts = $setting['accounts'] ?? [];

		// Sort Accounts Array; ASC
		usort( $accounts, function( $a, $b ) {
			if( ! isset( $a->id ) || ! isset( $b->id ) ) {
				return 1;
			}
			return intval( $a->id ) - intval( $b->id );
		} );

		// Select the Account using "Basic Display API"
		$account = null;
		$index   = null;
		foreach( $accounts as $_index => $_account ) {
			if( empty( $_account->id ) || (int) $_account->id <= $id ) {
				continue;
			}
			if( isset( $_account->token->token ) &&  isset( $_account->token->api ) && 'ig_basic_display' === $_account->token->api ) {
				$account = $_account;
				$index   = $_index;
				break;
			}
		}

		// Finished!!
		if( empty( $account ) ) {
			return;
		}

		// DB Lock
		$is_db_locked = ! update_option( self::PREFIX. 'db-setting-locked', true, 'no' );

		// Skip when the DB is Locked
		if( $is_db_locked ) {
			// Next Schedule
			return wp_schedule_single_event(
				time() + 300,
				self::CRON_HOOK_NAME_BASIC_DISPLAY_API_ME,
				[ $id ]
			);
		}

		// Get Account Informations
		$me = Basic_Display_API::get_me( $account->token->token );
		if( empty( $me ) || ! ( isset( $me->id ) || isset( $me->username ) || isset( $me->media_count ) || isset( $me->account_type ) ) ) {
			update_option( self::PREFIX. 'db-setting-locked', false, 'no' );
			// Next Schedule
			return wp_schedule_single_event(
				time() + 10,
				self::CRON_HOOK_NAME_BASIC_DISPLAY_API_ME,
				[ $account->id ]
			);
		}

		// Save Accounts Data
		$accounts[ $index ]->me = $me;
		$setting['accounts']    = array_reverse( $accounts );

		// Update Settings
		update_option( Setting::SETTING_NAME, $setting );

		// DB Unlock
		update_option( self::PREFIX. 'db-setting-locked', false, 'no' );

		// Next Schedule
		wp_schedule_single_event(
			time() + 10,
			self::CRON_HOOK_NAME_BASIC_DISPLAY_API_ME,
			[ $account->id ]
		);

	}


}



