<?php

namespace StillBE\Plugin\CombineSocialPhotos;


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




final class Graph_API {


	use Instagram_API_Common_Method;


	const PREFIX = SB_CSP_PREFIX;

	const FB_GRAPH_API_EXAMINE_TOKEN_ME_URL = 'https://graph.facebook.com/debug_token';
	const FB_GRAPH_API_PAGE_INFO_URL        = 'https://graph.facebook.com/'. STILLBE_CSP_FB_GRAPH_API_VERSION. '/{page-id}';
	const FB_GRAPH_API_IG_USER_INFO_URL     = 'https://graph.facebook.com/'. STILLBE_CSP_FB_GRAPH_API_VERSION. '/{user-id}';   // Edge '/media' is acquired by including it in 'fields' parameter.
	const FB_GRAPH_API_REFRESH_TOKEN_URL    = null;
	const FB_GRAPH_API_HASHTAG_SEARCH_URL   = 'https://graph.facebook.com/'. STILLBE_CSP_FB_GRAPH_API_VERSION. '/ig_hashtag_search';
	const FB_GRAPH_API_HASHTAG_MEDIA_URL    = 'https://graph.facebook.com/'. STILLBE_CSP_FB_GRAPH_API_VERSION. '/{hashtag-id}';

	const DEFAULT_GET_ACCOUNT_FIELDS        = [ 'id', 'name', 'username', 'profile_picture_url', 'media_count', 'followers_count', 'follows_count' ];
	const DEFAULT_GET_MEDIA_FIELDS          = [ 'caption', 'id', 'media_type', 'media_url', 'permalink', 'thumbnail_url', 'timestamp', 'username', 'comments_count', 'like_count', 'children{media_type,media_url,thumbnail_url}' ];
	const DEFAULT_GET_MEDIA_FIELDS_BD       = [ 'caption', 'id', 'media_type', 'media_url', 'permalink',                  'timestamp', 'username', 'comments_count', 'like_count', 'children{media_type,media_url}' ];
	const DEFAULT_GET_MEDIA_FIELDS_HASHTAG  = [ 'caption', 'id', 'media_type', 'media_url', 'permalink',                  'timestamp',             'comments_count', 'like_count', 'children{media_type,media_url}' ];

	const ACTION_HOOK_GET_IG_MEDIA          = SB_CSP_PREFIX. 'getting-ig-media-graph';
	const ACTION_HOOK_DUMMY_ACCESS_CDN      = SB_CSP_PREFIX. 'dummy-access-to-cdn-not-to-exprire';   // To Be Deleted
	const ACTION_HOOK_GET_HASHTAG_RECENT    = SB_CSP_PREFIX. 'get-new-recent-related-to-hashtag';

	const MEDIA_GET_COUNT_AT_A_TIME         = 100;   // Max = 10k, Default = 25
	const MEDIA_GET_MAX_COUNT_AT_A_TIME     = 1000;
	const MEDIA_GET_INTERVAL_BASE           = 20;

	const API_REQUEST_TIMEOUT               = 30;

	const API_TYPE                          = 'ig_graph';

	/**
	 * Maximum duration (number of days) of background processing
	 * 
	 * During this number of days, background processing continues even if there is no indication on the page,
	 * and the process is deleted after the elapse of the number of days.
	 * 
	 */
	const MAX_DURATION_OF_BACKGROUND_PROCESSING = 28;


	// Account Information from Access Token
	public static function get_me( $access_token, $page_id = null, $fields = [] ) {

		if( empty( $access_token ) ) {
			return null;
		}

		$me_data = array(
			'account_type' => 'PRO',
			'page_id'      => $page_id,
		);

		if( empty( $fields ) ) {
			$fields = self::DEFAULT_GET_ACCOUNT_FIELDS;
		}

		$page_info_query = array(
			'fields'       => 'id,instagram_business_account{'. implode( ',', $fields ). '}',
			'access_token' => $access_token,
		);

		if( empty( $page_id ) ) {
			$page_id = 'me';
		}

		$page_info_uri = add_query_arg(
			$page_info_query,
			str_replace( '{page-id}', $page_id, self::FB_GRAPH_API_PAGE_INFO_URL )
		);

		// Get an Instagram Account Info that Connected to the Page
		$result_page_info = wp_remote_get( $page_info_uri, 'timeout=15' );

		$json_page_info = wp_remote_retrieve_body( $result_page_info );
		if( is_wp_error( $json_page_info ) ) {
			return null;
		}

		$page_info = @json_decode( $json_page_info, true );
		if( empty( $page_info['id'] ) || empty( $page_info['instagram_business_account']['id'] ) || isset( $page_info['error'] ) ) {
			return null;
		}

		$me_data['page_id'] = $page_info['id'];

		$me = (object) array_merge( $me_data, $page_info['instagram_business_account'] );

		return $me;

	}


	// Refresh Token
	public static function refresh_token( $account ) {

		return null;

	}


	// Get User Media Helper
	private static function _get_media_data( $params ) {

		$is_recent_hashtag = ! empty( $params->advanced->hashtag_recent );

		if( ! empty( $params->advanced->business_discovery ) ) {

			$request_endpoint_url = str_replace( '{user-id}', $params->user_id, self::FB_GRAPH_API_IG_USER_INFO_URL );

			$fields = array_merge( self::DEFAULT_GET_ACCOUNT_FIELDS, [
				'media.limit('. $params->media_count. '){'. implode( ',', self::DEFAULT_GET_MEDIA_FIELDS_BD ). '}'
			] );

			$query = array(
				'access_token' => $params->access_token,
				'fields'       => 'business_discovery.username('. $params->advanced->business_discovery. '){'. implode( ',', $fields ). '}',
			);

		} elseif( $is_recent_hashtag ) {

			$saved_hashtags = get_option( SB_CSP_PREFIX. 'ig_hashtag_id_'. md5( $params->advanced->hashtag_recent ), array() );

			if( empty( $saved_hashtags[ $params->advanced->hashtag_recent ] ) ) {
				return array(
					'data'  => [],
					'user'  => null,
					'next'  => null,
					'error' => null,
				);
			}

			$request_endpoint_url = str_replace( '{hashtag-id}', $saved_hashtags[ $params->advanced->hashtag_recent ], self::FB_GRAPH_API_HASHTAG_MEDIA_URL ). '/recent_media';

			$fields = self::DEFAULT_GET_MEDIA_FIELDS_HASHTAG;

			$query = array(
				'access_token' => $params->access_token,
				'user_id'      => $params->user_id,
				'fields'       => implode( ',', $fields ),
				'limit'        => 50,
			);

			// Check to see if there is a Cron for dummy access to the CDN for the media of the hashtag post, and if not, register it
		/*
			self::_set_cron_job_periodically(
				$params,
				self::ACTION_HOOK_DUMMY_ACCESS_CDN,
				'daily',
				19 * 3600
			);
		*/

		} elseif( ! empty( $params->advanced->hashtag_top ) ) {

			$saved_hashtags = get_option( SB_CSP_PREFIX. 'ig_hashtag_id_'. md5( $params->advanced->hashtag_top ), array() );

			if( empty( $saved_hashtags[ $params->advanced->hashtag_top ] ) ) {
				return array(
					'data'  => [],
					'user'  => null,
					'next'  => null,
					'error' => null,
				);
			}

			$request_endpoint_url = str_replace( '{hashtag-id}', $saved_hashtags[ $params->advanced->hashtag_top ], self::FB_GRAPH_API_HASHTAG_MEDIA_URL ). '/top_media';

			$fields = self::DEFAULT_GET_MEDIA_FIELDS_HASHTAG;

			$query = array(
				'access_token' => $params->access_token,
				'user_id'      => $params->user_id,
				'fields'       => implode( ',', $fields ),
				'limit'        => 50,
			);

		} else {

			$request_endpoint_url = str_replace( '{user-id}', $params->user_id, self::FB_GRAPH_API_IG_USER_INFO_URL );

			$fields = array_merge( self::DEFAULT_GET_ACCOUNT_FIELDS, [
				'media.limit('. $params->media_count. '){'. implode( ',', self::DEFAULT_GET_MEDIA_FIELDS ). '}'
			] );

			$query = array(
				'access_token' => $params->access_token,
				'fields'       => implode( ',', $fields ),
			);

		}

		$request_endpoint_uri = add_query_arg(
			$query,
			$request_endpoint_url
		);

		// @since 0.8.0
		$is_exclude_video = ! empty( $params->advanced->exclude_video );

		if( $is_recent_hashtag ) {

			// Convert parameter to string
			$param_serialized = self::_param_serialized( $params );
			$hash_md5 = self::_param_hash_md5( $params );

			// Cache data
			$cached_key   = self::PREFIX. 'cached-ig-media-'. $hash_md5;
			$_cached_data = @json_decode( get_option( $cached_key, '{}' ) );
			$cached_data  = $_cached_data->cache->$param_serialized ?? new \stdClass;

		}

		$data  = [];
		$user  = null;
		$next  = $params->next ?? $request_endpoint_uri;
		$error = null;

		do{

			// Request API
			$response = wp_remote_get( $next, array( 'timeout' => self::API_REQUEST_TIMEOUT ) );
			$json     = wp_remote_retrieve_body( $response );
			$code     = wp_remote_retrieve_response_code( $response );

			$next = null;

			if( is_wp_error( $json ) || 400 <= $code ) {
				$error = $response;
				break;
			}

			$obj = @json_decode( $json );
			if( ! empty( $params->advanced->business_discovery ) ) {
				$obj = $obj->business_discovery ?? null;
			}

			$media = $obj->media->data ?? ( $obj->data ?? null );

			if( ! isset( $media ) || ! is_array( $media ) ) {
				$error = $response;
				break;
			}

			// @since 0.8.0
			foreach( $media as $d ) {
				$media_type = 'CAROUSEL_ALBUM' === $d->media_type ? ( $d->children->data[0]->media_type ?? 'UNKOWN' ) : $d->media_type;
				if( $is_exclude_video && 'VIDEO' === $media_type ) {
					continue;
				}
				if( empty( $d->media_url ) && empty( $d->children->data[0]->media_url ) ) {
					continue;
				}
				if( $is_recent_hashtag && ( $cached_data->data[0]->id ?? 0 ) == $d->id ) {
					unset( $media->paging->next, $obj->paging->next );
					break;
				}
				array_push( $data, $d );
			}

		//	array_push( $data, ...$media );

			$next = $media->paging->next ?? ( $obj->paging->next ?? null );

			if( $params->media_count <= count( $data ) ) {
				break;
			}

		} while( $next );

		$data = array_slice( [ ...$data, ...( $cached_data->data ?? [] ) ], 0, $params->media_count );

		if( ! empty( $obj ) && is_object( $obj ) && empty( $params->advanced->hashtag_recent ) && empty( $params->advanced->hashtag_top ) ) {
			$user = (array) ( clone $obj );
			unset( $user['media'] );
		}

		$return_data = (object) compact( 'data', 'user', 'next', 'error' );

		// Return Data if not Posted by Own User
		if( ! empty( $params->advanced->business_discovery ) || ! empty( $params->advanced->hashtag_recent ) || ! empty( $params->advanced->hashtag_top ) || 
		      empty( $obj->profile_picture_url ) || empty( $obj->id ) ) {
			return $return_data;
		}

		// Thereafter, update user information

		// DB Lock
		$is_db_locked = ! update_option( self::PREFIX. 'db-setting-locked', true, 'no' );

		if( $is_db_locked ) {
			return $return_data;
		}


		// Get Settings
		$_settings = (array) get_option( Setting::SETTING_NAME, array() );

		if( empty( $_settings['accounts'] ) || ! is_array( $_settings['accounts'] ) ) {
			update_option( self::PREFIX. 'db-setting-locked', false, 'no' );
			return $return_data;
		}

		$indexes = [];
		foreach( $_settings['accounts'] as $_index => $_account ) {
			if( isset( $_account->me->id ) && $obj->id == $_account->me->id
			      && isset( $_account->api_type ) && 'Graph API' === $_account->api_type ) {
				$indexes[] = absint( $_index );
			}
		}

		foreach( $indexes as $index ) {
			$current_me = (array) $_settings['accounts'][ $index ]->me;
			$_settings['accounts'][ $index ]->me = (object) array_merge( $current_me, $user );
		}
		update_option( Setting::SETTING_NAME, $_settings );

		// DB Unlock
		update_option( self::PREFIX. 'db-setting-locked', false, 'no' );

		return (object) $return_data;

	}


	// Search Hashtag ID from Hashtag String
	public static function hashtag_search( $user_id, $access_token, $hashtag ) {

		if( empty( $user_id ) || empty( $access_token ) || empty( $hashtag ) ) {
			return null;
		}

	//	$hashtag = strtolower( trim( $hashtag, " #\n\r\t\v\x00" ) );

		$saved_hashtags = get_option( SB_CSP_PREFIX. 'ig_hashtag_id_'. md5( $hashtag ), array() );

		if( isset( $saved_hashtags[ $hashtag ] ) ) {
			return $saved_hashtags[ $hashtag ];
		}

		// Request Param
		$params = array(
			'user_id'      => $user_id,
			'q'            => $hashtag,
			'access_token' => $access_token,
		);

		// Request URI of Instagram Hashtag Search
		$hashtag_search_uri = self::FB_GRAPH_API_HASHTAG_SEARCH_URL. '?'. http_build_query( $params );

		// Get an Instagram Hashtag ID
		$response = wp_remote_get( $hashtag_search_uri );

		$body = wp_remote_retrieve_body( $response );
		if( is_wp_error( $body ) ) {
			return null;
		}

		$json = @json_decode( $body );
		if( empty( $json->data[0]->id ) || isset( $json->error ) ) {
			return null;
		}

		// Save to DB
		$saved_hashtags[ $hashtag ] = $json->data[0]->id;
		update_option( SB_CSP_PREFIX. 'ig_hashtag_id_'. md5( $hashtag ), $saved_hashtags );

		return $json->data[0]->id;

	}


	public static function dummy_access_to_cdn_not_to_exprire( $params ) {

		// Convert parameter to string
		$param_serialized = self::_param_serialized( $params );
		$hash_md5 = self::_param_hash_md5( $params );

		// Cache data
		$cached_key   = self::PREFIX. 'cached-ig-media-'. $hash_md5;
		$_cached_data = @json_decode( get_option( $cached_key, '{}' ) );
		$cached_data  = $_cached_data->cache->$param_serialized ?? new \stdClass;

		// Start Index
		$index = $params->start_index ?? 0;
		if( ( $cached_data->created ?? 0 ) != ( $params->cached_time ?? -1 ) ) {
			$index = 0;
		}

		// 
		for( $i = $index, $x = 0, $n = count( $cached_data->data ?? [] ); $i < $n && $x < 10; ++$i, ++$x ) {

			$post = $cached_data->data[ $i ];

			$media = [ $post->thumbnail_url ?? null, $post->media_url ?? null ];

			$media_urls = array_merge( $media, ...array_map( function( $d ) {
				return [ $d->thumbnail_url ?? null, $d->media_url ?? null ];
			}, $post->children->data ?? [] ) );

			$media_urls = array_filter( $media_urls );

			foreach( $media_urls as $url ) {
				if( filter_var( $url, FILTER_VALIDATE_URL ) ) {
					$test["{$i}-{$url}"]=wp_remote_get(
						$url,
						array(
							'timeout'  => 0.05,
							'blocking' => false,
						)
					);
				}
			}

		}

		// Set a WP-Cron Job for Updating Recent Hashtag Posts
		if( ! empty( $params->advanced->hashtag_recent ) ) {
			$test=self::_set_cron_job_periodically(
				$params,
				self::ACTION_HOOK_GET_HASHTAG_RECENT,
				'twicedaily',
				2.5 * 3600
			);
		}


		if( isset( $cached_data->data[ $i ] ) && isset( $cached_data->created ) ) {

			$params->start_index = $i;
			$params->cached_time = $cached_data->created;

			return;

		/*

			$set_cron_job = wp_get_scheduled_event( self::ACTION_HOOK_DUMMY_ACCESS_CDN, [ $params ] );

			if( isset( $set_cron_job->schedule ) && false === $set_cron_job->schedule ) {
				// Completed because the cron job is already set up
				return;
			}

			wp_schedule_single_event(
				time() + self::MEDIA_GET_INTERVAL_BASE,
				self::ACTION_HOOK_DUMMY_ACCESS_CDN,
				[ $params ]
			);

		*/

		} elseif( isset( $cached_data->created ) ) {

			// For Debug
		//	error_log( 'Finish!!' );

		}

	}


	public static function update_cache_in_the_background( $params ) {

		// Settings
		$_setting = get_option( Setting::SETTING_NAME, array() );
		$accounts = $_setting['accounts'] ?? [];

		// Select an Account using an Authorized ID
		$account = null;
		foreach( (array) $accounts as $_account ) {
			if( isset( $_account->me->id ) && $params->user_id == $_account->me->id ) {
				$account = $_account;
				break;
			}
		}

		// ID is not Found...
		if( empty( $account->token->token ) || empty( $account->api_type ) || empty( $account->me->id ) || empty( $account->me->username ) ) {
			return false;
		}

		$data = self::get_media_data( $params->user_id, $account->token->token, $params->media_count, $params->fields, $params->advanced, true );

		return $data;

	}


	private static function _set_cron_job_periodically( $_params, $hook_name, $interval = 'daily', $delay = 600 ) {

		$params = clone $_params;
		unset( $params->access_token, $params->cached_key, $params->gettings_key );

		$schedule_names = wp_get_schedules();
		if( empty( $schedule_names[ $interval ] ) ) {
			return false;
		}

		$set_cron_job = wp_get_scheduled_event( $hook_name, [ $params ] );

		if( $set_cron_job && $interval === $set_cron_job->schedule ) {
			// Completed because the cron job is already set up
			return true;
		}

		return wp_schedule_event(
			time() + $delay,
			$interval,
			$hook_name,
			[ $params ]
		);

	}


}



