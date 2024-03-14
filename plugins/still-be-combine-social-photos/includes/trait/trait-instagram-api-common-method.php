<?php

/**
 * Common methods for get the Instagram API data and updating the cache data.
 * 
 * Since the API endpoints accessed by the Instagram Graph API and the Instagram Basic Display API are different,
 * the formatting of the request data to the API and the normalization of the acquired data are done in each class.
 * 
 */



namespace StillBE\Plugin\CombineSocialPhotos;


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




trait Instagram_API_Common_Method {


	/**
	 * Get data about Instagram posts from the API with specified conditions
	 * 
	 * If cache data exists, priority is given to the cache.
	 * When an old cache is detected, WP-Cron is set to update the cache in the background.
	 * 
	 * If cache data does not exist or the forced update flag is set, data is acquired in real time.
	 * 
	 *
	 * @since 0.7.0   Change $business_discovery to $advanced to also get posts related to the hashtag.
	 * 
	 * @since 0.3.0
	 * 
	 * 
	 * @param string        $user_id         Instagram User ID (not Username)
	 * @param string        $access_token    Access Token for API
	 * 
	 * @param int           $media_count     Number of Posts to Save in Cache
	 * @param string[]      $fields          Array of Names of Data Dields to be Getfrom the API
	 * 
	 * @param object|null   $advanced        Object keys; 'business_discovery' -- Username (not User ID) to Get from Another Account
	 *                                                    'hashtag_recent'     -- Hashtag (not ID) to Get Recent Posted
	 *                                                    'hashtag_top'        -- Hashtag (not ID) to Get Popular Posts
	 * 
	 * @param bool          $force_refresh   Get Data from API and Force Cache Refresh
	 * 
	 * 
	 * Return NULL if getting data fails
	 * 
	 * @return null | object {
	 * 
	 *   @type object[]      $data   Data array of Instagram posts
	 *   @type object|null   $user   Instagram posting user information
	 * 
	 * }
	 * 
	 */
	public static function get_media_data( $user_id, $access_token, $media_count = 100, $fields = [], $advanced = null, $force_refresh = false ) {

		if( empty( $user_id ) || empty( $access_token ) ) {
			return null;
		}

		$media_count = absint( $media_count );
		if( 1 > $media_count ) {
			return [];
		}

		if( empty( $fields ) || ! is_array( $fields ) ) {
			$fields = self::DEFAULT_GET_MEDIA_FIELDS;
		}

		// Set API type so that cache data is not covered
		$api_type = self::API_TYPE;

		// Calculate the number of posts to be get
		// Cache data rounded up to a multiple of the maximum number of posts that can be get at one time
		$_return_media_count = $media_count;
		$media_count = ceil( $media_count / self::MEDIA_GET_COUNT_AT_A_TIME ) * self::MEDIA_GET_COUNT_AT_A_TIME;

		// When getting the latest posts related to a hashtag, do not get more than the number of posts required for display
		if( ! empty( $advanced->hashtag_recent ) && $_return_media_count < self::MEDIA_GET_COUNT_AT_A_TIME ) {
			$media_count = $_return_media_count;
		}

		// Set parameters to characterize the data to be cached
		$params = (object) compact( 'user_id', 'access_token', 'media_count', 'fields', 'advanced', 'api_type' );

		// Convert parameter to string
		$param_serialized = self::_param_serialized( $params );
		$hash_md5 = self::_param_hash_md5( $params );

		// Cache data
		$cached_key   = self::PREFIX. 'cached-ig-media-'. $hash_md5;
		$_cached_data = @json_decode( get_option( $cached_key, '{}' ) );
		$cached_data  = $_cached_data->cache->$param_serialized ?? new \stdClass;

		$params->cached_key = $cached_key;

		$now      = time();
		$_setting = get_option( Setting::SETTING_NAME, array() );

		// Get cache-related configuration values
		$setting  = $_setting['cache'] ?? array();
		$lifetime = absint( $setting['data-lifetime'] ?? Setting::DEFAULT_CACHE_LIFETIME );

		// Return Object
		$return_obj = (object) array(
			'user' => $cached_data->user ?? null,
		);

		// When cache data is valid
		if( ! $force_refresh && isset( $cached_data->data ) && is_array( $cached_data->data ) &&
		      isset( $cached_data->created ) && $now < $cached_data->created + $lifetime ) {
			$return_obj->data = array_slice( $cached_data->data, 0 , $_return_media_count );
			return $return_obj;
		}

		// Force refresh or cache is empty
		if( $force_refresh || ! isset( $cached_data->data ) ) {

			$result  = self::_get_media_data( $params );
			$data    = $result->data ?? null;
			$user    = $result->user ?? null;
			$next    = $result->next ?? null;
			$created = $now;

			if( ! isset( $data ) && isset( $cached_data->data ) ) {
				$return_obj->data = array_slice( $cached_data->data, 0 , $_return_media_count );
				return $return_obj;
			}

			// To prevent data loss in the event of a collision between hash strings of parameters,
			// cache data is stored as an associative array keyed by serialized strings of parameters that characterize itself.
			$save_data = array(
				'cache' => array_merge(
					(array) ( $_cached_data->cache ?? array() ),
					array(
						$param_serialized => compact( 'data', 'user', 'next', 'created' )
					)
				)
			);

			// Update cache data
			update_option( $cached_key, json_encode( $save_data ), 'no' );

			// Cut out and return only the required number of posts
			$return_obj->data = array_slice( $data ?? [], 0 , $_return_media_count );
			$return_obj->user = $user;
			return $return_obj;

		}

		// Option name for storing temporary data for cache background updates
		$gettings_key         = self::PREFIX. 'getting-ig-media-'. $hash_md5;
		$params->gettings_key = $gettings_key;

		// Set a Single WP-Cron
		$get_scheduled_cron = wp_next_scheduled( self::ACTION_HOOK_GET_IG_MEDIA, [ $params ] );
		if( empty( $get_scheduled_cron ) ) {
			wp_schedule_single_event(
				$now + self::MEDIA_GET_INTERVAL_BASE,
				self::ACTION_HOOK_GET_IG_MEDIA,
				[ $params ]
			);
		}

		// Return Cache Data
		$return_obj->data = array_slice( $cached_data->data, 0 , $_return_media_count );
		return $return_obj;

	}


	/**
	 * Get data sequentially from the Instagram API and update the cache when data retrieval is complete.
	 * 
	 * When updating a cache that exceeds the number of data that can be retrieved from the API at one time,
	 * attempt to retrieve the data multiple times.
	 * WP-Cron must not be disabled for multiple splits.
	 * 
	 * If an access error to the API occurs five or more times in a row, the acquisition is aborted.
	 * 
	 * 
	 * @since 0.3.0
	 * 
	 * 
	 * @param object $params {
	 * 
	 *   @type string     $user_id               Instagram User ID (not Username)
	 *   @type string     $access_token          Access Token for API
	 * 
	 *   @type int        $media_count           Number of Posts to Save in Cache
	 *   @type string[]   $fields                Array of Names of Data Dields to be Getfrom the API
	 * 
	 *   @type string     $business_discovery    Username (not User ID) to Get from Another Account
	 * 
	 *   @type string     $api_type              API Type (Graph API: "ig_graph", Basic Display API: "ig_basic_display")
	 * 
	 *   @type string     $cached_key            Name of the Option to Save in {db_prefix}_options Table
	 *   @type string     $gettings_key          Name of the Option to Temporarily Store Updated Data in {db_prefix}_options
	 * 
	 * }
	 * 
	 */
	public static function update_ig_media_cache( $params ) {

		// Number of times the API is accessed in a single WP-Cron run
		$_setting        = get_option( Setting::SETTING_NAME, array() );
		$count_at_a_time = absint( $_setting['others']['cron-get-media-requests'] ?? 1 );
		if( 1 > $count_at_a_time ) {
			$count_at_a_time = 1;
		}

		// Data being acquired
		$_gettings = @json_decode( get_option( $params->gettings_key ) );
		if( ! $_gettings instanceof \stdClass ) {
			$_gettings = new \stdClass;
		}
		$data = $_gettings->data ?? new \stdClass;

		// Get data for itself from the data being got
		$param_serialized = self::_param_serialized( $params );
		$gettings = $data->{ $param_serialized } ?? new \stdClass;

		$get_params = clone $params;

		// Get the continuation of the data, if the URL is set to get the continuation of the data.
		if( isset( $gettings->next ) ) {
			$get_params->next = $gettings->next;
		}

		// Calculate the number of posts to be get
		// Cache data rounded up to a multiple of the maximum number of posts that can be get at one time
		$_return_media_count = $params->media_count;
		$_max_media_count    = ceil( $params->media_count / self::MEDIA_GET_COUNT_AT_A_TIME ) * self::MEDIA_GET_COUNT_AT_A_TIME;
		$_media_count        = min( $_max_media_count, self::MEDIA_GET_MAX_COUNT_AT_A_TIME * $count_at_a_time );

		// When getting the latest posts related to a hashtag, do not get more than the number of posts required for display
		if( ! empty( $params->advanced->hashtag_recent ) && $_return_media_count < self::MEDIA_GET_COUNT_AT_A_TIME ) {
			$_media_count = $_return_media_count;
		}

		// Get data from API
		$get_params->media_count = $_media_count;
		$result = self::_get_media_data( $get_params );

		if( null === $result->error && ! empty( $result->data ) ) {

			// Result
			$gettings->results   = $gettings->results ?? [];
			$gettings->results[] = $result;

			// Data
			$gettings->data = $gettings->data ?? [];
			array_push( $gettings->data, ...$result->data );

			// User
			$gettings->user = $result->user ?? ( $gettings->user ?? null );

			// Next
			$gettings->next = $result->next;

		} else {

			// Error
			$gettings->errors   = $gettings->errors ?? [];
			$gettings->errors[] = $result->error;

		}

		$data->$param_serialized = $gettings;
		update_option( $params->gettings_key, json_encode( compact( 'data' ) ), 'no' );

		$now = time();

		// Finish!!
		if( $params->media_count <= count( $gettings->data ?? [] ) ||
		      ( empty( $result->next ) && null === $result->error ) ) {

			$gettings->next    = $gettings->next ?? null;
			$gettings->created = $now;

			unset( $gettings->errors );
			unset( $gettings->results );

			$_cache = @json_decode( get_option( $params->cache_key ) );
			if( ! $_cache instanceof \stdClass ) {
				$_cache = new \stdClass;
			}

			$cache = $_cache->cache ?? new \stdClass;
			$cache->$param_serialized = $gettings;

			$save_data = (object) compact( 'cache' );
			update_option( $params->cached_key, json_encode( $save_data ), 'no' );

			unset( $data->$param_serialized );
			update_option( $params->gettings_key, json_encode( compact( 'data' ) ), 'no' );

			return;

		}

		// Set a Single WP-Cron
		$get_scheduled_cron = wp_next_scheduled( self::ACTION_HOOK_GET_IG_MEDIA, [ $params ] );

		// Set a Next Schedule
		if( empty( $get_scheduled_cron ) && ( empty( $gettings->errors ) || 5 > count( $gettings->errors ) ) ) {

			wp_schedule_single_event(
				$now + self::MEDIA_GET_INTERVAL_BASE * $count_at_a_time,
				self::ACTION_HOOK_GET_IG_MEDIA,
				[ $params ]
			);

		}

	}


	/**
	 * Convert parameters to serialized string
	 * 
	 * 
	 * @since 0.7.0   Change $params->business_discovery to $params->advanced to also get posts related to the hashtag.
	 * 
	 * @since 0.3.0
	 * 
	 * 
	 * @param object $params {
	 * 
	 *   @type string        $user_id         Instagram User ID (not Username)
	 * 
	 *   @type int           $media_count     Number of Posts to Save in Cache
	 *   @type string[]      $fields          Array of Names of Data Dields to be Getfrom the API
	 * 
	 *   @type object|null   $advanced        Object keys; 'business_discovery' -- Username (not User ID) to Get from Another Account
	 *                                                     'hashtag_recent'     -- Hashtag (not ID) to Get Recent Posted
	 *                                                     'hashtag_top'        -- Hashtag (not ID) to Get Popular Posts
	 *                                                     'exclude_video'      -- Not Including Video
	 * 
	 *   @type string        $api_type         API Type (Graph API: "ig_graph", Basic Display API: "ig_basic_display")
	 * 
	 * }
	 * 
	 */
	public static function _param_serialized( $params ) {

		if( ! defined( 'self::API_TYPE' ) ) {
			throw new ErrorException( 'self::API_TYPE is not defined....' );
		}

		$param_serialized = serialize( array(
			'user_id'     => $params->user_id,
			'media_count' => $params->media_count,
			'fields'      => $params->fields,
			'advanced'    => (object) array(
				'business_discovery' => empty( $params->advanced->business_discovery ) ? null : $params->advanced->business_discovery,
				'hashtag_recent'     => empty( $params->advanced->hashtag_recent     ) ? null : $params->advanced->hashtag_recent,
				'hashtag_top'        => empty( $params->advanced->hashtag_top        ) ? null : $params->advanced->hashtag_top,
				'exclude_video'      => empty( $params->advanced->exclude_video      ) ? null : $params->advanced->exclude_video,
			),
			'api_type'    => self::API_TYPE,
		) );

		return $param_serialized;

	}


	/**
	 * Convert parameters to hash string using md5
	 * 
	 * 
	 * @since 0.7.0   Change $params->business_discovery to $params->advanced to also get posts related to the hashtag.
	 * 
	 * @since 0.3.0
	 * 
	 * 
	 * @param object $params {
	 * 
	 *   @type string        $user_id         Instagram User ID (not Username)
	 * 
	 *   @type int           $media_count     Number of Posts to Save in Cache
	 *   @type string[]      $fields          Array of Names of Data Dields to be Getfrom the API
	 * 
	 *   @type object|null   $advanced        Object keys; 'business_discovery' -- Username (not User ID) to Get from Another Account
	 *                                                     'hashtag_recent'     -- Hashtag (not ID) to Get Recent Posted
	 *                                                     'hashtag_top'        -- Hashtag (not ID) to Get Popular Posts
	 * 
	 *   @type string        $api_type         API Type (Graph API: "ig_graph", Basic Display API: "ig_basic_display")
	 * 
	 * }
	 * 
	 */
	public static function _param_hash_md5( $params ) {

		$param_serialized = self::_param_serialized( $params );

		return md5( $param_serialized );

	}


	/**
	 * 
	 *
	 *
	 *
	 */
	private static function _set_hashtag_recent_crolling(  ) {

		// 
		// Set a Single WP-Cron
		$get_scheduled_cron = wp_next_scheduled( self::ACTION_HOOK_GET_IG_MEDIA, [ $params ] );
		if( empty( $get_scheduled_cron ) ) {
			wp_schedule_single_event(
				$now + self::MEDIA_GET_INTERVAL_BASE,
				self::ACTION_HOOK_GET_IG_MEDIA,
				[ $params ]
			);
		}

	}


}



