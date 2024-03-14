<?php
/**
 * Class to handle Drop Monitor related things.
 *
 * @package SurferSEO
 */

namespace SurferSEO\Surfer\GSC;

use stdClass;

/**
 * Class to handle Drop Monitor related things.
 */
class Surfer_GSC_Drop_Monitor {

	use Surfer_GSC_Common;

	/**
	 * Number of rows to get from GSC.
	 * 1000 - for old Surfer endpoint.
	 * 25 000 - limit from GSC.
	 *
	 * @var int
	 */
	private $max_gsc_rows = 1000;

	/**
	 * Object construct.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init function.
	 */
	public function init() {

		add_action( 'surfer_gather_drop_monitor_data', array( $this, 'gather_position_monitor_data' ) );
		add_action( 'surfer_gather_position_monitor_data_bunch', array( $this, 'parse_data_bunch_for_postion_monitor' ) );

		if ( ! wp_next_scheduled( 'surfer_gather_drop_monitor_data' ) ) {
			wp_schedule_single_event( strtotime( 'next monday 08:00:00' ), 'surfer_gather_drop_monitor_data' );
		}

		add_action( 'wp_ajax_surfer_test_gsc_traffic_gatherer', array( $this, 'test_gather_position_monitor_data' ) );

		add_action( 'wp_ajax_surfer_get_posts_for_performance_report', array( $this, 'get_posts_for_performance_report' ) );
		add_action( 'wp_ajax_surfer_get_domain_performance_report', array( $this, 'get_domain_performance_report' ) );

		add_action( 'wp_ajax_surfer_test_email_performance_report', array( $this, 'test_performance_email_template' ) );
	}


	/**
	 * Gather single chunk of data from GSC.
	 */
	public function parse_data_bunch_for_postion_monitor() {

		// Do not run if not connected to GSC.
		if ( ! $this->check_if_gsc_connected( true ) ) {
			return 'GSC not connected';
		}

		$posts_max  = get_transient( 'surfer_gsc_data_collection_posts_max' );
		$posts_done = get_transient( 'surfer_gsc_data_collection_posts_done' );
		if ( false === $posts_done ) {
			$posts_done = 0;
		}

		if ( ! is_numeric( $posts_max ) || ! $posts_max > 0 ) {
			return 'No records for this domain in last week in GSC.';
		}

		$debug_log = array(
			'execution_time'            => gmdate( 'Y-m-d H:i:s' ),
			'posts_max'                 => $posts_max,
			'posts_done_before'         => $posts_done,
			'last_gathering_date'       => get_option( 'surfer_last_gsc_data_update', strtotime( 'this week monday' ) ),
			'query_last_gathering_date' => gmdate( 'Y-m-d 00:00:00', strtotime( get_option( 'surfer_last_gsc_data_update', strtotime( 'this week monday' ) ) ) ),
			'posts'                     => array(),
		);

		// Process is done.
		if ( $posts_done >= $posts_max ) {
			delete_transient( 'surfer_gsc_data_collection_posts_done' );
			delete_transient( 'surfer_gsc_data_collection_posts_max' );

			$this->send_performace_report_email();

			set_transient( 'surfer_gsc_weekly_report_ready', true, DAY_IN_SECONDS );
			update_option( 'surfer_last_gsc_data_update', gmdate( 'Y-m-d H:i:s', strtotime( 'this week monday ' . gmdate( 'H:i:s' ) ) ) );

			return 'All posts done for this week.';
		} else {
			// We may need another run.
			wp_schedule_single_event( strtotime( '+5 minute' ), 'surfer_gather_position_monitor_data_bunch' );
		}

		$return = $this->get_posts_from_gsc( $posts_max, $posts_done );

		if ( isset( $return['code'] ) && 200 === $return['code'] ) {
			foreach ( $return['response']['traffic_data'] as $i => $page ) {

				++$posts_done;
				set_transient( 'surfer_gsc_data_collection_posts_done', $posts_done, HOUR_IN_SECONDS * 1 );

				$can_parse = $this->can_parse_data( $page );

				if ( true !== $can_parse ) {
					$debug_log['posts'][ $i ] = $can_parse;
					continue;
				}

				$db_insert                = $this->parse_single_page_data( $page );
				$debug_log['posts'][ $i ] = $db_insert;
			}
		}

		$debug_log['posts_done_after_cycle'] = $posts_done;
		$debug_log['return']                 = $return;

		return print_r( $debug_log, true );
	}

	/**
	 * Execute query to get data from GSC via Surfer.
	 *
	 * @param int $row_limit - number of rows to get.
	 * @param int $start_row - row to start from.
	 * @return array
	 */
	private function get_posts_from_gsc( $row_limit, $start_row ) {

		$time_base = strtotime( 'this week monday' );

		$params = array(
			'urls'          => array( home_url() ),
			'row_limit'     => $row_limit,
			'start_row'     => $start_row,
			'days_interval' => 7,
			'start_date'    => gmdate( 'Y-m-d', strtotime( 'previous monday', $time_base ) ),
			'end_date'      => gmdate( 'Y-m-d', strtotime( 'previous sunday', $time_base ) ),
		);

		return Surfer()->get_surfer()->make_surfer_request( '/get_traffic_from_gsc', $params );
	}

	/**
	 * Check if data for certain post can be parsed. Return true if yes, string with reason if not.
	 *
	 * @param array $page - page data.
	 * @return bool | string
	 */
	private function can_parse_data( $page ) {

		if ( ! isset( $page['site'] ) ) {
			return 'Response for page does not contain site key.';
		}

		$post_id = url_to_postid( $page['site'] );

		// If URL was not transformend to post ID, skip.
		if ( 0 === intval( $post_id ) ) {
			return 'Post URL ' . $page['site'] . ' was not transformed to post ID.';
		}

		// Skip if post is not published.
		if ( 'publish' !== get_post_status( $post_id ) ) {
			return 'Post with ID: ' . $post_id . ' is not published.';
		}

		// Skip if post is not post or page.
		if ( ! in_array( get_post_type( $post_id ), surfer_return_supported_post_types(), true ) ) {
			return 'Post with ID: ' . $post_id . ' has wrong post type: ' . get_post_type( $post_id );
		}

		$last_post_update    = $this->get_last_period_date( $post_id );
		$last_data_gathering = get_option( 'surfer_last_gsc_data_update', strtotime( 'this week monday' ) );
		$query_last_date     = gmdate( 'Y-m-d 00:00:00', strtotime( $last_data_gathering ) );

		// Skip upddate if last update was this week.
		if ( false !== $last_post_update && strtotime( $last_post_update ) > strtotime( $query_last_date ) ) {
			return 'Post with ID: ' . $post_id . ' was already updated in this cycle. (' . $last_post_update . ')';
		}

		return true;
	}

	/**
	 * Gets data of a single post for performance report.
	 *
	 * @param int $post_id - ID of the post.
	 * @return string | bool
	 */
	private function get_last_period_date( $post_id ) {
		global $wpdb;
		$records = $wpdb->get_row( $wpdb->prepare( 'SELECT p.data_gathering_date FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS p WHERE p.post_id = %d ORDER BY p.data_gathering_date DESC LIMIT 1', $post_id ), ARRAY_A );

		if ( isset( $records ) && $records ) {
			return $records['data_gathering_date'];
		}

		return false;
	}

	/**
	 * Parse data for single page and saves it in database.
	 *
	 * @param array $page - page data.
	 * @return string
	 */
	private function parse_single_page_data( $page ) {

		$clicks      = $page['clicks'];
		$impressions = $page['impressions'];
		$position    = $page['position'];

		$post_id         = url_to_postid( $page['site'] );
		$previous_result = surfer_get_last_post_traffic_by_id( $post_id );

		$gathering_date = gmdate( 'Y-m-d H:i:s', strtotime( 'this week monday ' . gmdate( 'H:i:s' ) ) );

		$data_to_insert = array(
			'post_id'             => $post_id,
			'clicks'              => $clicks,
			'clicks_change'       => ( is_null( $previous_result ) ) ? null : $clicks - $previous_result['clicks'],
			'impressions'         => $impressions,
			'impressions_change'  => ( is_null( $previous_result ) ) ? null : $impressions - $previous_result['impressions'],
			'position'            => $position,
			'position_change'     => ( is_null( $previous_result ) ) ? null : $position - $previous_result['position'],
			'data_gathering_date' => $gathering_date,
			'period_start_date'   => gmdate( 'Y-m-d', strtotime( 'previous monday', strtotime( $gathering_date ) ) ),
			'period_end_date'     => gmdate( 'Y-m-d', strtotime( 'previous sunday', strtotime( $gathering_date ) ) ),
		);

		global $wpdb;
		$inset_result = $wpdb->insert( $wpdb->prefix . 'surfer_gsc_traffic', $data_to_insert );

		return 'Post ID: ' . $post_id . ' insert into database resulted with: ' . $inset_result . ' last_gathering_date: ' . $gathering_date;
	}

	/**
	 * Ajax function to test or force GSC data gathering.
	 */
	public function test_gather_position_monitor_data() {

		if ( ! surfer_validate_ajax_request() ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$this->gather_position_monitor_data();
	}

	/**
	 * Endpoint to gather data about posts traffic.
	 */
	public function gather_position_monitor_data() {

		if ( ! Surfer()->get_surfer()->get_gsc()->check_if_gsc_connected( true ) ) {
			echo wp_json_encode( 'GSC not connected' );
			wp_die();
		}

		$return = $this->get_posts_from_gsc( $this->max_gsc_rows, 0 );

		if ( isset( $return['code'] ) && 200 === $return['code'] ) {
			$numer_of_posts = count( $return['response']['traffic_data'] );
			set_transient( 'surfer_gsc_data_collection_posts_max', $numer_of_posts, HOUR_IN_SECONDS * 1 );

			$results = $this->parse_data_bunch_for_postion_monitor();

			echo wp_json_encode( $results );
			wp_die();
		}

		// We have this print for debug.
		echo wp_json_encode( print_r( $return, true ) );
		wp_die();
	}

	/**
	 * Sends email with performance report.
	 */
	private function send_performace_report_email() {

		if ( ! $this->performance_report_email_notification_endabled() ) {
			return;
		}

		$last_email_sent = get_transient( 'surfer_gsc_weekly_report_email_sent' );
		if ( strtotime( $last_email_sent ) > strtotime( '-7 days' ) ) {
			return;
		}

		$message = $this->prepare_weekly_report_email_message();
		/* translators: %s: domain */
		$title   = sprintf( __( 'Surfer Performance Report for domain: %s', 'surferseo' ), preg_replace( '/^https?:\/\//', '', home_url() ) );
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
		);

		$email_sent = wp_mail( get_admin_url(), $title, $message, $headers );
		if ( $email_sent ) {
			Surfer()->get_surfer_tracking()->track_wp_event( 'report_email_sent', home_url() );
			set_transient( 'surfer_gsc_weekly_report_email_sent', gmdate( 'm-d-Y H:i:s' ), 7 * DAY_IN_SECONDS );
		}
	}

	/**
	 * Function to check if email template looks like it should
	 */
	public function test_performance_email_template() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$message = $this->prepare_weekly_report_email_message();

		/* translators: %s: domain */
		$title   = sprintf( __( 'Surfer Performance Report for domain: %s', 'surferseo' ), preg_replace( '/^https?:\/\//', '', home_url() ) );
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
		);

		echo $message; //@PHPCS:ignore:WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( isset( $_GET['send_email'] ) && is_email( $_GET['send_email'] ) ) {
			wp_mail( $_GET['send_email'], $title, $message, $headers );
		}

		wp_die();
	}

	/**
	 * Function that prepares body for the weekly report email.
	 *
	 * @return string
	 */
	private function prepare_weekly_report_email_message() {

		$posts_drops_in_top_10              = $this->get_posts_drops_in_top_10();
		$posts_drops_that_droped_to_next_10 = $this->get_posts_drops_to_next_10();
		$posts_out_of_index                 = $this->get_posts_out_of_index();
		$posts_indexed                      = $this->get_indexed_posts();
		$posts_growth                       = $this->get_posts_that_grew();

		$list_of_posts = array_merge(
			$posts_drops_in_top_10,
			$posts_drops_that_droped_to_next_10,
			$posts_out_of_index,
			$posts_indexed,
			$posts_growth
		);

		$summary_impressions      = $this->count_domain_performance_report_for_single_stat( 'impressions' );
		$summary_impressions_prev = $this->count_domain_performance_report_for_single_stat( 'impressions', 'prev' );
		$summary_clicks           = $this->count_domain_performance_report_for_single_stat( 'clicks' );
		$summary_clicks_prev      = $this->count_domain_performance_report_for_single_stat( 'clicks', 'prev' );

		$posts_down = $this->count_domain_performance_report_position_change( 'fall' );
		$posts_up   = $this->count_domain_performance_report_position_change( 'growth' );

		$last_update = get_option( 'surfer_last_gsc_data_update', false );
		$update_date = $this->return_period_based_on_gathering_date( $last_update );

		$tracking_enabled = Surfer()->get_surfer_tracking()->is_tracking_allowed();

		ob_start();
			require_once Surfer()->get_basedir() . '/templates/emails/performance-report.php';
		$message = ob_get_clean();

		return $message;
	}

	/**
	 * Gets posts that are in top 10 and made a drop.
	 *
	 * @return array
	 */
	private function get_posts_drops_in_top_10() {

		$last_update_date = get_option( 'surfer_last_gsc_data_update', false );

		if ( false === $last_update_date ) {
			return array();
		}

		$query_last_date = gmdate( 'Y-m-d 0:00:00', strtotime( $last_update_date ) );

		global $wpdb;

		// position_change DESC beacust we want biggest fall on top, and fall is represented by positive numbers.
		$posts = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t
				LEFT JOIN ' . $wpdb->prefix . 'posts AS p ON t.post_id = p.ID
				WHERE ( t.position - t.position_change ) <= 10 
				AND ( t.position - t.position_change ) > 0 
				AND t.position_change > 0 
				AND t.data_gathering_date = ( SELECT MAX(data_gathering_date) FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t2 WHERE t.post_id = t2.post_id )
				AND t.data_gathering_date >= %s
				ORDER BY t.position_change DESC',
				$query_last_date
			)
		);

		return $posts;
	}

	/**
	 * Gets posts that droped to next 10.
	 * We count only top 50.
	 *
	 * @return array
	 */
	private function get_posts_drops_to_next_10() {

		$last_update_date = get_option( 'surfer_last_gsc_data_update', false );

		if ( false === $last_update_date ) {
			return array();
		}

		$query_last_date = gmdate( 'Y-m-d 0:00:00', strtotime( $last_update_date ) );

		global $wpdb;

		// position_change DESC beacust we want biggest fall on top, and fall is represented by positive numbers.
		$posts = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t
				LEFT JOIN ' . $wpdb->prefix . 'posts AS p ON t.post_id = p.ID
				WHERE 
				  (t.position - t.position_change) <= 50 
				  AND (t.position - t.position_change) > 10
				  AND t.position_change > 0
				  AND t.position / 10 <> (t.position - t.position_change) / 10
				  AND t.data_gathering_date = ( SELECT MAX(data_gathering_date) FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t2 WHERE t.post_id = t2.post_id )
				  AND t.data_gathering_date >= %s
				ORDER BY t.position_change DESC',
				$query_last_date
			)
		);

		return $posts;
	}

	/**
	 * Gets posts that are out of index.
	 * Moves from any position to 0.
	 *
	 * @return array
	 */
	private function get_posts_out_of_index() {

		$last_update_date = get_option( 'surfer_last_gsc_data_update', false );

		if ( false === $last_update_date ) {
			return array();
		}

		$query_last_date = gmdate( 'Y-m-d 0:00:00', strtotime( $last_update_date ) );

		global $wpdb;

		// Order by position_change ASC - smaller change, means that post was on higher position and they are more important for us. Changes are positive numbers. (POSTION - CHANGE) = 0.
		$posts = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t
				LEFT JOIN ' . $wpdb->prefix . 'posts AS p ON t.post_id = p.ID
				WHERE 
				  t.position = 0
				  AND t.position_change IS NOT NULL
				  AND t.position_change <> 0
				  AND t.data_gathering_date = ( SELECT MAX(data_gathering_date) FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t2 WHERE t.post_id = t2.post_id )
				  AND t.data_gathering_date >= %s
				ORDER BY t.position_change ASC',
				$query_last_date
			)
		);

		return $posts;
	}

	/**
	 * Gets posts that are indexed.
	 * Moves from 0 to any position.
	 *
	 * @return array
	 */
	private function get_indexed_posts() {

		$last_update_date = get_option( 'surfer_last_gsc_data_update', false );

		if ( false === $last_update_date ) {
			return array();
		}

		$query_last_date = gmdate( 'Y-m-d 0:00:00', strtotime( $last_update_date ) );

		global $wpdb;

		// Order by position_change DESC - smaller change, means that post was indexed on higher position. Changes will be negative numbers POSITION = (0 - CHANGE).
		$posts = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t
				LEFT JOIN ' . $wpdb->prefix . 'posts AS p ON t.post_id = p.ID
				WHERE 
				  t.position = ABS(t.position_change)
				  AND t.position_change IS NOT NULL
				  AND t.position_change <> 0
				  AND t.data_gathering_date = ( SELECT MAX(data_gathering_date) FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t2 WHERE t.post_id = t2.post_id )
				  AND t.data_gathering_date >= %s
				ORDER BY t.position_change DESC',
				$query_last_date
			)
		);

		return $posts;
	}

	/**
	 * Gets posts with position change at least +2 in top 50, if they were optimized in Surfer.
	 *
	 * @return array
	 */
	private function get_posts_that_grew() {

		$last_update_date = get_option( 'surfer_last_gsc_data_update', false );

		if ( false === $last_update_date ) {
			return array();
		}

		$query_last_date = gmdate( 'Y-m-d 0:00:00', strtotime( $last_update_date ) );

		global $wpdb;

		// position_change ASC beacust we want biggest growth on top, and growth is represented by negative numbers.
		$posts = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t
				LEFT JOIN ' . $wpdb->prefix . 'posts AS p ON t.post_id = p.ID
				LEFT JOIN ' . $wpdb->prefix . 'postmeta AS meta ON p.ID = meta.post_id AND meta.meta_key = "surfer_draft_id"
				WHERE 
				  ( t.position + t.position_change ) < 50
				  AND t.position > 0
				  AND t.position_change IS NOT NULL
				  AND t.position_change < -1
				  AND meta.meta_value IS NOT NULL
				  AND t.data_gathering_date = ( SELECT MAX(data_gathering_date) FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t2 WHERE t.post_id = t2.post_id )
				  AND t.data_gathering_date >= %s
				ORDER BY t.position_change ASC',
				$query_last_date
			)
		);

		return $posts;
	}


	/**
	 * Gets data for performance report page.
	 */
	public function get_posts_for_performance_report() {

		$json = file_get_contents( 'php://input' );
		$data = json_decode( $json, true );

		if ( ! surfer_validate_custom_request( $data['_surfer_nonce'] ) ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$query        = $data['query'];
		$current_page = $data['page'];
		$per_page     = $data['perPage'];
		$filter       = $data['filter'];
		$sorting      = $data['sorting'];

		$return = new stdClass();

		$return->posts = $this->count_posts_for_performance_report( $query, $current_page, $per_page, $filter, $sorting );

		$last_update_date = get_option( 'surfer_last_gsc_data_update', 'this week monday' );
		$query_last_date  = gmdate( 'Y-m-d 00:00:00', strtotime( $last_update_date ) );

		$return->current_period = $this->return_period_based_on_gathering_date( $query_last_date );

		$return->counts = array(
			'all'    => $this->count_posts_for_performance_report( $query, $current_page, $per_page, 'all', $sorting, true ),
			'fall'   => $this->count_posts_for_performance_report( $query, $current_page, $per_page, 'fall', $sorting, true ),
			'growth' => $this->count_posts_for_performance_report( $query, $current_page, $per_page, 'growth', $sorting, true ),
		);

		echo wp_json_encode( $return );
		wp_die();
	}

	/**
	 * Gather posts for performance report.
	 *
	 * @param string $query - search query.
	 * @param int    $current_page - current page.
	 * @param int    $per_page - posts per page.
	 * @param string $filter - all | growth | fall - filter by traffic change.
	 * @param string $sorting - sorting field and direction.
	 * @param bool   $count_only - if true, will return only count.
	 */
	private function count_posts_for_performance_report( $query, $current_page, $per_page, $filter, $sorting, $count_only = false ) {

		$last_update_date = get_option( 'surfer_last_gsc_data_update', 'this week monday' );
		$query_last_date  = gmdate( 'Y-m-d 00:00:00', strtotime( $last_update_date ) );

		global $wpdb;

		$cache_key = 'surfer_' . $current_page . '_' . $per_page . '_' . $filter . '_' . $sorting . '_' . $query . '_' . $count_only;
		$posts     = wp_cache_get( $cache_key, 'surfer' );

		if ( false === $posts ) {

			if ( $count_only ) {
				$sql = 'SELECT COUNT(*) AS cnt';
			} else {
				$sql = 'SELECT p.ID, p.post_title, p.post_content, surfer_gsc.*, meta.meta_value AS draft_id';
			}

			$sql .= ' FROM ' . $wpdb->prefix . 'posts AS p';
			$sql .= ' JOIN ' . $wpdb->prefix . 'surfer_gsc_traffic AS surfer_gsc ON p.ID = surfer_gsc.post_id';

			if ( ! $count_only ) {
				$sql .= ' LEFT JOIN ' . $wpdb->prefix . 'postmeta AS meta ON meta.post_id = p.ID AND meta.meta_key = "surfer_draft_id"';
			}

			$sql .= ' WHERE post_type IN ("' . implode( '", "', surfer_return_supported_post_types() ) . '") AND post_status IN ("publish")';
			$sql .= ' AND data_gathering_date = ( SELECT MAX(data_gathering_date) FROM ' . $wpdb->prefix . 'surfer_gsc_traffic t2 WHERE surfer_gsc.post_id = t2.post_id )';
			$sql .= $wpdb->prepare( ' AND data_gathering_date >= %s', $query_last_date );

			if ( strlen( $query ) > 0 ) {
				$sql .= $wpdb->prepare( ' AND post_title LIKE %s', '%' . $query . '%' );
			}

			if ( 'all' !== $filter ) {
				if ( 'fall' === $filter ) {
					$sql .= ' AND position_change > 0';
				} else {
					$sql .= ' AND position_change < 0';
				}
			}

			if ( strpos( $sorting, 'ID' ) !== false ) {
				$sorting = str_replace( 'ID', 'p.ID', $sorting );
			}

			if ( ! $count_only ) {
				$sql .= ' ORDER BY ' . $sorting;
				$sql .= $wpdb->prepare( ' LIMIT %d OFFSET %d', $per_page, ( $current_page - 1 ) * $per_page );
			}

			$results = $wpdb->get_results( $sql ); // @codingStandardsIgnoreLine

			if ( $wpdb->last_error ) {
				return $wpdb->last_error;
			}

			if ( $count_only ) {
				return isset( $results[0] ) ? $results[0]->cnt : 0;
			}

			$posts = array();

			foreach ( $results  as $result ) {

				$show_previous_period = $this->check_if_should_show_previous_date( $result->ID );

				$posts[] = array(
					'id'                  => $result->ID,
					'postURL'             => get_the_permalink( $result->ID ),
					'postEditURL'         => get_edit_post_link( $result->ID, 'notdisplay' ),
					'draftId'             => $result->draft_id,
					'scrapeStatus'        => get_post_meta( $result->ID, 'surfer_scrape_ready', true ),
					'postTitle'           => $result->post_title,
					'position'            => $result->position,
					'postContent'         => $result->post_content,
					'previousPosition'    => ( null === $result->position_change ) ? false : $result->position - $result->position_change,
					'clicks'              => $result->clicks,
					'previousClicks'      => ( null === $result->clicks_change ) ? false : $result->clicks - $result->clicks_change,
					'impressions'         => $result->impressions,
					'previousImpressions' => ( null === $result->impressions_change ) ? false : $result->impressions - $result->impressions_change,
					'contentScore'        => false,
					'currentPeriod'       => $this->return_period_based_on_gathering_date( $query_last_date ),
					'previousPeriod'      => $show_previous_period ? $this->return_period_based_on_gathering_date( gmdate( 'Y-m-d', strtotime( 'previous monday', strtotime( $query_last_date ) ) ) ) : false,
				);
			}

			wp_cache_set( $cache_key, $posts, 'surfer', 60 * 5 );
		}

		return $posts;
	}

	/**
	 * Check if we should show previous period date for post.
	 *
	 * @param int $post_id - post id.
	 * @return bool
	 */
	private function check_if_should_show_previous_date( $post_id ) {

		$previous_post_update_date = $this->get_previous_period_date( $post_id );
		if ( false === $previous_post_update_date ) {
			return false;
		}

		$last_update_date     = get_option( 'surfer_last_gsc_data_update', 'this week monday' );
		$previous_update_date = strtotime( 'previous monday 00:00:00', strtotime( $last_update_date ) );

		if ( $previous_post_update_date < $previous_update_date ) {
			return true;
		}

		return false;
	}

	/**
	 * Get domain performance report.
	 */
	public function get_domain_performance_report() {

		$json = file_get_contents( 'php://input' );
		$data = json_decode( $json );

		if ( ! surfer_validate_custom_request( $data->_surfer_nonce ) ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$return = new stdClass();

		// @codingStandardsIgnoreStart
		$return->totalImporessions     = (int)$this->count_domain_performance_report_for_single_stat( 'impressions' );
		$return->prevTotalImporessions = (int)$this->count_domain_performance_report_for_single_stat( 'impressions', 'prev' );

		$return->totalClicks     = (int)$this->count_domain_performance_report_for_single_stat( 'clicks' );
		$return->prevTotalClicks = (int)$this->count_domain_performance_report_for_single_stat( 'clicks', 'prev' );

		$return->fallInPosition     = (int)$this->count_domain_performance_report_position_change( 'fall' );
		$return->prevFallInPosition = (int)$this->count_domain_performance_report_position_change( 'fall', 'prev' );

		$return->growthInPosition     = (int)$this->count_domain_performance_report_position_change( 'growth' );
		$return->prevGrowthInPosition = (int)$this->count_domain_performance_report_position_change( 'growth', 'prev' );
		// @codingStandardsIgnoreEnd

		echo wp_json_encode( $return );
		wp_die();
	}

	/**
	 * Count clicks and impressions for the whole domain in last period.
	 *
	 * @param string        $stat - clicks | impressions.
	 * @param string | bool $period - prev | false.
	 */
	private function count_domain_performance_report_for_single_stat( $stat, $period = false ) {

		$last_update_date = get_option( 'surfer_last_gsc_data_update', 'this week monday' );

		if ( false === $last_update_date ) {
			return 0;
		}

		$query_last_date = gmdate( 'Y-m-d 0:00:00', strtotime( $last_update_date ) );

		global $wpdb;

		$cache_key = 'surfer_domain_report_' . $stat . '_' . $period;
		$result    = wp_cache_get( $cache_key, 'surfer' );

		if ( false === $result ) {
			if ( 'prev' === $period ) {
				$sql = 'SELECT SUM(' . $stat . ' - ' . $stat . '_change) AS sum FROM ' . $wpdb->prefix . 'surfer_gsc_traffic t1';
			} else {
				$sql = 'SELECT SUM(' . $stat . ') AS sum FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t1';
			}

			$sql .= ' WHERE data_gathering_date = ( SELECT MAX(data_gathering_date) FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t2 WHERE t1.post_id = t2.post_id )';
			$sql .= $wpdb->prepare( ' AND data_gathering_date >= %s', $query_last_date );

			if ( 'prev' === $period ) {
				$sql .= ' AND ' . $stat . '_change IS NOT NULL';
			}

			$results = $wpdb->get_results( $sql ); // @codingStandardsIgnoreLine

			if ( $wpdb->last_error ) {
				return $wpdb->last_error;
			}

			$result = isset( $results[0] ) ? $results[0]->sum : 0;

			wp_cache_set( $cache_key, $result, 'surfer', 60 * 5 );
		}

		return $result;
	}

	/**
	 * Gather information about posts with position fall/growth in recent period.
	 *
	 * @param string        $direction - fall | growth.
	 * @param string | bool $period - prev | false.
	 */
	private function count_domain_performance_report_position_change( $direction, $period = false ) {

		$last_update_date = get_option( 'surfer_last_gsc_data_update', 'this week monday' );

		if ( false === $last_update_date ) {
			return 0;
		}

		$query_last_date     = gmdate( 'Y-m-d 00:00:00', strtotime( $last_update_date ) );
		$query_previous_date = gmdate( 'Y-m-d 00:00:00', strtotime( 'previous monday', strtotime( $query_last_date ) ) );

		global $wpdb;

		$cache_key = 'surfer_domain_report_' . $direction . '_' . $period;
		$result    = wp_cache_get( $cache_key, 'surfer' );

		if ( false === $result ) {

			$sql = 'SELECT COUNT(*) AS cnt FROM ' . $wpdb->prefix . 'surfer_gsc_traffic t1';
			// $sql .= ' WHERE data_gathering_date = ( SELECT MAX(data_gathering_date) FROM ' . $wpdb->prefix . 'surfer_gsc_traffic AS t2 WHERE t1.post_id = t2.post_id )';

			if ( 'prev' === $period ) {
				$sql .= $wpdb->prepare( ' WHERE data_gathering_date >= %s', $query_previous_date );
				$sql .= $wpdb->prepare( ' AND data_gathering_date < %s', $query_last_date );
			} else {
				$sql .= $wpdb->prepare( ' WHERE data_gathering_date >= %s', $query_last_date );
			}

			$sql .= ' AND position_change ' . ( 'fall' === $direction ? '>' : '<' ) . ' 0';

			$results = $wpdb->get_results( $sql ); // @codingStandardsIgnoreLine

			if ( $wpdb->last_error ) {
				return $wpdb->last_error;
			}

			$result = isset( $results[0] ) ? $results[0]->cnt : 0;
			wp_cache_set( $cache_key, $result, 'surfer', 60 * 5 );

		}

		return $result;
	}
}
