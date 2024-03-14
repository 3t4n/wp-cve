<?php
/**
 * This file contains a class with some analytics-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/helpers
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements analytics-related helper functions.
 */
class Nelio_Content_Analytics_Helper {

	// NOTE: remember to update “update_statistics” to actually pull new metrics from each social network.
	// // phpcs:ignore
	private static $engagement_metrics = [ 'total', 'twitter', 'facebook', 'pinterest', 'reddit' ];

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {
		add_action( 'cron_schedules', array( $this, 'add_cron_interval' ) );
		add_action( 'init', array( $this, 'maybe_enable_cron_tasks' ) );
		add_action( 'wp_update_comment_count', array( $this, 'update_comment_count' ), 10, 2 );
	}//end init()

	/**
	 * Enables or disables cron tasks on WordPress’ init based on current settings.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function maybe_enable_cron_tasks() {
		$settings = Nelio_Content_Settings::instance();
		if ( $settings->get( 'use_analytics' ) ) {
			$this->enable_analytics_cron_tasks();
		} else {
			$this->disable_analytics_cron_tasks();
		}//end if
	}//end maybe_enable_cron_tasks()

	/**
	 * Add custom cron intervals.
	 *
	 * @param array $schedules List of schedules.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function add_cron_interval( $schedules ) {

		$schedules['nc_four_hours'] = array(
			'interval' => 4 * HOUR_IN_SECONDS,
			'display'  => esc_html_x( 'Every Four Hours (Nelio Content)', 'text', 'nelio-content' ),
		);
		return $schedules;

	}//end add_cron_interval()

	private function enable_analytics_cron_tasks() {

		$time = time();

		add_action( 'nelio_content_analytics_today_cron_hook', array( $this, 'update_today_posts' ) );
		if ( ! wp_next_scheduled( 'nelio_content_analytics_today_cron_hook' ) ) {
			wp_schedule_event( $time, 'nc_four_hours', 'nelio_content_analytics_today_cron_hook' );
		}//end if

		add_action( 'nelio_content_analytics_month_cron_hook', array( $this, 'update_month_posts' ) );
		if ( ! wp_next_scheduled( 'nelio_content_analytics_month_cron_hook' ) ) {
			wp_schedule_event( $time + 3600, 'nc_four_hours', 'nelio_content_analytics_month_cron_hook' );
		}//end if

		add_action( 'nelio_content_analytics_other_cron_hook', array( $this, 'update_other_posts' ) );
		if ( ! wp_next_scheduled( 'nelio_content_analytics_other_cron_hook' ) ) {
			wp_schedule_event( $time + 7200, 'nc_four_hours', 'nelio_content_analytics_other_cron_hook' );
		}//end if

	}//end enable_analytics_cron_tasks()

	private function disable_analytics_cron_tasks() {

		$timestamp = wp_next_scheduled( 'nelio_content_analytics_today_cron_hook' );
		wp_unschedule_event( $timestamp, 'nelio_content_analytics_today_cron_hook' );

		$timestamp = wp_next_scheduled( 'nelio_content_analytics_month_cron_hook' );
		wp_unschedule_event( $timestamp, 'nelio_content_analytics_month_cron_hook' );

		$timestamp = wp_next_scheduled( 'nelio_content_analytics_other_cron_hook' );
		wp_unschedule_event( $timestamp, 'nelio_content_analytics_other_cron_hook' );

		$timestamp = wp_next_scheduled( 'nelio_content_analytics_top_cron_hook' );
		wp_unschedule_event( $timestamp, 'nelio_content_analytics_top_cron_hook' );

	}//end disable_analytics_cron_tasks()

	/**
	 * Update analytics of all posts published today.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_today_posts() {

		// Let's get the posts published today.
		$today = getdate();
		$args  = array(
			'posts_per_page' => -1,
			'date_query'     => array(
				array(
					'year'  => $today['year'],
					'month' => $today['mon'],
					'day'   => $today['mday'],
				),
			),
		);

		$posts_to_update = $this->get_posts_using_last_update( $args );
		foreach ( $posts_to_update as $post ) {
			$post_id = $post['id'];
			$this->update_statistics( $post_id );
		}//end foreach

	}//end update_today_posts()

	/**
	 * Update analytics of 10 random posts published this month.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_month_posts() {

		// Let's get the posts to update.
		$args = array(
			'posts_per_page' => 10,
			'date_query'     => array(
				array(
					'column' => 'post_date_gmt',
					'after'  => '1 month ago',
				),
			),
		);

		$posts_to_update = $this->get_posts_using_last_update( $args );
		foreach ( $posts_to_update as $post ) {
			$post_id = $post['id'];
			$this->update_statistics( $post_id );
		}//end foreach

	}//end update_month_posts()

	/**
	 * Update analytics of 10 random posts published before this month.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_other_posts() {

		// Let's get 10 posts before this month.
		$args = array(
			'posts_per_page' => 10,
			'date_query'     => array(
				array(
					'column' => 'post_date_gmt',
					'before' => '1 month ago',
				),
			),
		);

		$posts_to_update = $this->get_posts_using_last_update( $args );
		foreach ( $posts_to_update as $post ) {
			$post_id = $post['id'];
			$this->update_statistics( $post_id );
		}//end foreach

	}//end update_other_posts()

	/**
	 * Helper function that, given a certain post ID, updates its analytics.
	 *
	 * @param  integer $post_id the post whose analytics has to be updated.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_statistics( $post_id ) {

		// Safe guards.
		if ( empty( $post_id ) ) {
			return;
		}//end if

		if ( ! $this->needs_to_be_updated( $post_id ) ) {
			return;
		}//end if

		// Compute engagement.
		$social_analytics = $this->get_social_count( $post_id );
		$url              = get_permalink( $post_id );
		$engagement       = array(
			'twitter'   => $social_analytics['twitter'],
			'facebook'  => $social_analytics['facebook'],
			'pinterest' => $this->get_pinterest_count( $url ),
			'reddit'    => $this->get_reddit_count( $url ),
			'comments'  => intval( wp_count_comments( $post_id )->approved ),
		);
		$this->save_engagement( $post_id, $engagement );

		// Compute pageviews.
		$settings = Nelio_Content_Settings::instance();
		$ga4_prop = $settings->get( 'ga4_property_id' );
		if ( ! empty( $ga4_prop ) ) {
			$date      = get_the_date( 'Y-m-d', $post_id );
			$pageviews = $this->get_ga_data( $ga4_prop, $post_id, $url, $date );
			$this->save_pageviews( $ga4_prop, $post_id, $pageviews );
		}//end if

		// Refresh last update.
		update_post_meta( $post_id, '_nc_last_update', time() );

	}//end update_statistics()

	/**
	 * Helper function that, given a certain post ID, retrieves its analytics.
	 *
	 * @param  integer $post_id the post whose analytics has to be recovered.
	 *
	 * @return array the statistics of the given post.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function get_post_stats( $post_id ) {

		// LAST UPDATE.
		$last_update = get_post_meta( $post_id, '_nc_last_update', true );
		if ( empty( $last_update ) ) {
			$last_update = 0;
		}//end if

		// ENGAGEMENT.
		$engagement = array();
		foreach ( self::$engagement_metrics as $metric ) {
			$value                 = get_post_meta( $post_id, '_nc_engagement_' . $metric, true );
			$value                 = '' === $value ? -1 : absint( $value );
			$engagement[ $metric ] = $this->human_number( $value );
		}//end foreach
		$value                  = intval( wp_count_comments( $post_id )->approved );
		$engagement['comments'] = $this->human_number( $value );

		// TODO. Remove old meta “_nc_pageviews_data”?
		// PAGEVIEWS.
		$settings  = Nelio_Content_Settings::instance();
		$ga4_prop  = $settings->get( 'ga4_property_id' );
		$pageviews = get_post_meta( $post_id, "_nc_pageviews_total_{$ga4_prop}", true );
		$pageviews = $this->human_number( absint( $pageviews ) );

		return array(
			'engagement' => $engagement,
			'pageviews'  => $pageviews,
		);

	}//end get_post_stats()

	/**
	 * Get a set of posts from WordPress, embedded in an object for pagination.
	 *
	 * @param array $params Parameters to filter the search.
	 *
	 * @return object an object with two keys: `items` with the list of posts and `pagination` with info for pagination.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function get_paginated_posts( $params ) {

		// Load some settings.
		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types', array() );

		$defaults = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
		);

		$args = wp_parse_args( $params, $defaults );

		if ( isset( $args['meta_key'] ) &&
			'_nc_pageviews_total' === $args['meta_key'] ) {
			$args['meta_key'] = '_nc_pageviews_total_' . $settings->get( 'ga4_property_id' ); // phpcs:ignore
		}//end if

		$query = new WP_Query( $args );
		$posts = array();

		$post_helper = Nelio_Content_Post_Helper::instance();
		while ( $query->have_posts() ) {

			$query->the_post();
			$aux = $post_helper->post_to_json( get_the_ID() );
			if ( ! empty( $aux ) ) {
				array_push( $posts, $aux );
			}//end if
		}//end while

		wp_reset_postdata();

		// Build result object, ready for pagination.
		$page = isset( $params['paged'] ) ? $params['paged'] : 1;
		return array(
			'results'    => $posts,
			'pagination' => array(
				'more'  => $page < $query->max_num_pages,
				'pages' => $query->max_num_pages,
			),
		);

	}//end get_paginated_posts()

	private function get_posts_using_last_update( $params ) {

		$params = wp_parse_args( $params, array( 'posts_per_page' => 10 ) );

		$post_helper = Nelio_Content_Post_Helper::instance();

		// Load some settings.
		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types', array() );

		$defaults = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'meta_query'  => array( // phpcs:ignore
				array(
					'key'     => '_nc_last_update',
					'compare' => 'NOT EXISTS',
				),
			),
		);

		$args = wp_parse_args( $params, $defaults );

		$query = new WP_Query( $args );

		$posts = array();
		while ( $query->have_posts() ) {

			$query->the_post();
			$aux = $post_helper->post_to_json( get_the_ID() );
			if ( ! empty( $aux ) ) {
				array_push( $posts, $aux );
			}//end if
		}//end while

		wp_reset_postdata();

		$posts_to_find = intval( $params['posts_per_page'] );
		if ( count( $posts ) === $posts_to_find ) {
			return $posts;
		}//end if

		if ( -1 !== $posts_to_find ) {
			$params['posts_per_page'] = $posts_to_find - count( $posts );
		}//end if

		$defaults = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'meta_key'    => '_nc_last_update', // phpcs:ignore
			'orderby'     => 'meta_value_num',
			'order'       => 'ASC',
		);

		$args = wp_parse_args( $params, $defaults );

		$query = new WP_Query( $args );
		while ( $query->have_posts() ) {

			$query->the_post();
			$aux = $post_helper->post_to_json( get_the_ID() );
			if ( ! empty( $aux ) ) {
				array_push( $posts, $aux );
			}//end if
		}//end while

		wp_reset_postdata();

		return $posts;
	}//end get_posts_using_last_update()

	/**
	 * Helper function that updates the total engagement value when a new comment
	 * occurs in WordPress or an old comment changes its status.
	 *
	 * @param  integer $post_id the post whose engagement needs to be updated.
	 * @param  integer $new the new comment count.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_comment_count( $post_id, $new ) {

		$total = 0;
		foreach ( self::$engagement_metrics as $metric ) {
			if ( 'total' === $metric ) {
				continue;
			}//end if
			$total += intval( get_post_meta( $post_id, "_nc_engagement_{$metric}", true ) );
		}//end foreach

		update_post_meta( $post_id, '_nc_engagement_total', $total + $new );

	}//end update_comment_count()

	/**
	 * Helper function that obtains the access token and refresh token in Google
	 * Analytics.
	 *
	 * @return string The token to access Google Analytics.
	 */
	public function refresh_access_token() {

		$code = get_option( 'nc_ga_refresh_token', false );
		if ( empty( $code ) ) {
			return false;
		}//end if

		$data = array(
			'method'    => 'POST',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'accept'       => 'application/json',
				'content-type' => 'application/json',
			),
			'body'      => wp_json_encode(
				array(
					'code' => $code,
				)
			),
		);

		$url      = nc_get_api_url( '/connect/ga/refresh', 'wp' );
		$response = wp_remote_request( $url, $data );

		if ( is_wp_error( $response ) ) {
			update_option( 'nc_ga_token_error', true );
			return false;
		}//end if

		$json = json_decode( $response['body'] );
		if ( ! isset( $json->token ) ||
			! isset( $json->expiration ) ) {
			update_option( 'nc_ga_token_error', true );
			return false;
		}//end if

		$expiration = ( $json->expiration / MINUTE_IN_SECONDS ) - 10;
		$expiration = $expiration < 10 ? 10 : $expiration;
		set_transient( 'nc_ga_token', $json->token, $expiration * MINUTE_IN_SECONDS );
		delete_option( 'nc_ga_token_error' );

		return $json->token;

	}//end refresh_access_token()

	private function needs_to_be_updated( $post_id ) {

		$last_update = get_post_meta( $post_id, '_nc_last_update', true );
		if ( empty( $last_update ) ) {
			return true;
		}//end if

		$updated_last_hour = time() - $last_update < HOUR_IN_SECONDS;
		if ( $updated_last_hour ) {
			return false;
		}//end if

		$publication_date    = get_post_time( 'U', true, $post_id );
		$published_last_week = time() - $publication_date < WEEK_IN_SECONDS;
		if ( $published_last_week ) {
			return true;
		}//end if

		$updated_today = time() - $last_update <= DAY_IN_SECONDS;
		return ! $updated_today;

	}//end needs_to_be_updated()

	private function save_engagement( $post_id, $engagement ) {

		// Properly compute total value.
		$aux = 0;
		foreach ( $engagement as $key => $value ) {
			if ( -1 !== $value ) {
				$aux += $value;
			} else {
				$aux += intval( get_post_meta( $post_id, '_nc_engagement_' . $key, true ) );
			}//end if
		}//end foreach

		$engagement['total'] = $aux;

		// Save.
		foreach ( $engagement as $key => $value ) {
			if ( 'comments' !== $key && -1 !== $value ) {
				update_post_meta( $post_id, '_nc_engagement_' . $key, $value );
			}//end if
		}//end foreach
	}//end save_engagement()

	private function save_pageviews( $ga4_prop, $post_id, $pageviews ) {
		if ( empty( $ga4_prop ) ) {
			return;
		}//end if

		if ( -1 === $pageviews ) {
			return;
		}//end if

		update_post_meta( $post_id, "_nc_pageviews_total_{$ga4_prop}", absint( $pageviews ) );
	}//end save_pageviews()

	private function human_number( $number, $precision = 1 ) {

		if ( -1 === $number ) {
			return '–';
		}//end if

		$units = array( '', 'k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y' );
		$step  = 1000;
		$i     = 0;
		while ( ( $number / $step ) > 0.9 ) {
			$number = $number / $step;
			$i++;
		}//end while

		if ( floor( $number ) >= 100 ) {
			$number = number_format_i18n( $number, 0 );
		} elseif ( floor( $number ) * 10 !== floor( $number * 10 ) ) {
			$number = number_format_i18n( $number, $precision );
		} else {
			$number = number_format_i18n( $number, 0 );
		}//end if

		return $number . $units[ $i ];
	}//end human_number()

	private function get_social_count( $post_id ) {

		$previous_twitter_count  = absint( get_post_meta( $post_id, '_nc_engagement_twitter', true ) );
		$previous_facebook_count = absint( get_post_meta( $post_id, '_nc_engagement_facebook', true ) );
		$count                   = array(
			'twitter'  => $previous_twitter_count,
			'facebook' => $previous_facebook_count,
		);
		if ( ! nc_is_subscribed() ) {
			return $count;
		}//end if

		$data = array(
			'method'    => 'GET',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$path = sprintf(
			'/site/%s/post/%d/analytics',
			nc_get_site_id(),
			$post_id
		);

		$response = wp_remote_request( nc_get_api_url( $path, 'wp' ), $data );
		if ( is_wp_error( $response ) ) {
			return $count;
		}//end if

		$body = json_decode( $response['body'], true );
		if ( isset( $body['twitter'] ) ) {
			$count['twitter'] = absint( $body['twitter'] );
		}//end if

		if ( isset( $body['facebook'] ) ) {
			$count['facebook'] = absint( $body['facebook'] );
		}//end if

		return $count;

	}//end get_social_count()

	private function get_pinterest_count( $url ) {

		// Retrieves data with HTTP GET method for current URL.
		$json_string = wp_remote_request(
			'https://api.pinterest.com/v1/urls/count.json?url=' . rawurlencode( $url ),
			array(
				'method'    => 'GET',
				'sslverify' => false, // Disable checking SSL certificates.
			)
		);

		if ( is_wp_error( $json_string ) ) {
			return -1;
		}//end if

		// Retrives only body from previous HTTP GET request.
		$json_string = wp_remote_retrieve_body( $json_string );
		$json_string = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $json_string );

		// Convert body data to JSON format.
		$json = json_decode( $json_string, true );

		// Get count of Pinterest Shares for requested URL.
		if ( ! isset( $json['count'] ) ) {
			return -1;
		}//end if

		$value = intval( $json['count'] );
		return $value;

	}//end get_pinterest_count()

	private function get_reddit_count( $url ) {

		// Retrieves data with HTTP GET method for current URL.
		$json_string = wp_remote_request(
			'https://www.reddit.com/api/info.json?url=' . rawurlencode( $url ),
			array(
				'method'    => 'GET',
				'sslverify' => false, // Disable checking SSL certificates.
			)
		);

		if ( is_wp_error( $json_string ) ) {
			return -1;
		}//end if

		// Retrives only body from previous HTTP GET request.
		$json_string = wp_remote_retrieve_body( $json_string );

		// Convert body data to JSON format.
		$json = json_decode( $json_string, true );

		// Get count of Reddit Shares for requested URL.
		if ( ! isset( $json['data']['children'] ) ) {
			return -1;
		}//end if

		$value = 0;
		$items = $json['data']['children'];
		foreach ( $items as $item ) {

			if ( ! isset( $item['data']['score'] ) ) {
				continue;
			}//end if

			$value += $item['data']['score'];

		}//end foreach

		return $value;

	}//end get_reddit_count()

	private function get_ga_data( $ga4_prop, $post_id, $url, $start_date ) {

		$result = absint( get_post_meta( $post_id, "_nc_pageviews_total_{$ga4_prop}", true ) );

		$path = preg_replace( '/^https?:\/\/[^\/]+/', '', $url );
		if ( ! $path ) {
			$path = '/';
		}//end if

		/**
		 * Modifies the list of paths in which we can find a given post.
		 *
		 * @param array $paths   an array with one or more paths in which the post can be found.
		 * @param int   $post_id the ID of the post.
		 *
		 * @since 1.3.0
		 */
		$paths = apply_filters( 'nelio_content_analytics_post_paths', array( $path ), $post_id );

		$ga_token = get_transient( 'nc_ga_token' );
		if ( false === $ga_token ) {
			$ga_token = $this->refresh_access_token();
			if ( false === $ga_token ) {
				return $result;
			}//end if
		}//end if

		$args = array(
			'method'    => 'POST',
			'sslverify' => false,
			'headers'   => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $ga_token,
			),
			'body'      => sprintf(
				preg_replace(
					'/\s/',
					'',
					<<<JSON
					{
						"dateRanges": [ {
							"startDate": %1\$s,
							"endDate": "today"
						} ],
						"dimensions": [ {
							"name": "pagePathPlusQueryString"
						} ],
						"metrics": [ {
							"name": "screenPageViews"
						} ],
						"dimensionFilter": {
							"filter": {
								"fieldName": "pagePathPlusQueryString",
								"inListFilter": {
									"values": %2\$s,
									"caseSensitive": false
								}
							}
						}
					}
JSON
				),
				wp_json_encode( $start_date ),
				wp_json_encode( $paths )
			),
		);

		$json_string = wp_remote_post( "https://analyticsdata.googleapis.com/v1beta/properties/{$ga4_prop}:runReport", $args );
		if ( is_wp_error( $json_string ) ) {
			return $result;
		}//end if

		$json = json_decode( $json_string['body'], true );
		if ( isset( $json['error'] ) ) {
			return -1;
		}//end if

		$rows   = isset( $json['rows'] ) ? $json['rows'] : array();
		$result = 0;
		foreach ( $rows as $row ) {
			$values = empty( $row['metricValues'] ) ? array() : $row['metricValues'];
			foreach ( $values as $value ) {
				$result += isset( $value['value'] ) ? absint( $value['value'] ) : 0;
			}//end foreach
		}//end foreach

		return $result;

	}//end get_ga_data()

}//end class
