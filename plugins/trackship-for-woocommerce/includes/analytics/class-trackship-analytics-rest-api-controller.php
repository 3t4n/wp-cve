<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Automattic\WooCommerce\Admin\API\Reports\TimeInterval;

/**
 * REST API shipment tracking controller.
 *
 * Handles requests to /orders/shipment-tracking endpoint.
 *
 * @since 1.5.0
 */

class WC_Ts_Analytics_REST_API_Controller extends WC_REST_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'ts-analytics';
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		global $wpdb;
		if ( is_multisite() ) {
			if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			}
			if ( is_plugin_active_for_network( 'trackship-for-woocommerce/trackship-for-woocommerce.php' ) ) {
				$main_blog_prefix = $wpdb->get_blog_prefix(BLOG_ID_CURRENT_SITE);
				$this->shipment_table = $main_blog_prefix . 'trackship_shipment';
			} else {
				$this->shipment_table = $wpdb->prefix . 'trackship_shipment';
			}
		} else {
			$this->shipment_table = $wpdb->prefix . 'trackship_shipment';
		}
	}
	
	/**
	 * Set namespace
	 *
	 * @return WC_Advanced_Shipment_Tracking_REST_API_Controller
	 */
	public function set_namespace( $namespace ) {
		$this->namespace = $namespace;
		return $this;
	}
	
	/**
	 * Register the routes for trackings.
	 */
	public function register_routes() {
		
		// get stats data
		register_rest_route( 'wc-analytics/reports/data' , 'trackship/stats', array(
			array(
				'methods'				=> WP_REST_Server::READABLE,
				'callback'				=> array( $this, 'get_trackship_stats' ),
				'permission_callback'	=> array( $this, 'get_items_permissions_check' ),
				'args'					=> $this->get_collection_params(),
			),
		) );

		// Get all provider list
		register_rest_route( 'wc-analytics/data', 'get_shipments_providers', array(
			array(
				'methods'				=> WP_REST_Server::READABLE,
				'callback'				=> array( $this, 'get_shipments_providers' ),
				'permission_callback'	=> array( $this, 'get_items_permissions_check' ),
				'args'					=> $this->get_collection_params(),
			),
		) );
		
		// get all shipments by provider
		register_rest_route( 'wc-analytics/reports/data' , 'shipments_by_provider', array(
			array(
				'methods'				=> WP_REST_Server::READABLE,
				'callback'				=> array( $this, 'get_shipments_by_providers' ),
				'permission_callback'	=> array( $this, 'get_items_permissions_check' ),
				'args'					=> $this->get_collection_params(),
			),
		) );
	}		

	/**
	 * Check whether a given request has permission to read order shipment-trackings.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		if ( ! wc_rest_check_manager_permissions( 'settings', 'read' ) ) {
			return new WP_Error( 'woocommerce_rest_cannot_view', __( 'Sorry, you cannot list resources.', 'woocommerce' ), array( 'status' => rest_authorization_required_code() ) );
		}
		return true;
	}
	
	/**
	 * Maps query arguments from the REST request.
	 *
	 * @param array $request Request array.
	 * @return array
	 */
	protected function prepare_reports_query( $request ) {
		$args						= array();
		$args['before']				= $request['before'];
		$args['after']				= $request['after'];
		$args['page']				= $request['page'];
		$args['per_page']			= $request['per_page'];
		$args['orderby']			= $request['orderby'];
		$args['order']				= $request['order'];
		$args['shipment_status']	= $request['shipment_status'];
		$args['shipment_provider']	= $request['shipment_provider'];
		$args['shipment_type']		= $request['shipment_type'];
		$args['providers']			= $request['providers'];
		$args['interval']			= $request['interval'];


		// hitesh
		$args['page']		= 1;
		$args['orderby']	= 'date';

		return $args;
	}
	
	public function get_trackship_stats ( $request ) {
		global $wpdb;
		$woo_trackship_shipment = $this->shipment_table;

		$query_args = $this->prepare_reports_query( $request );

		$after_date = gmdate( 'Y-m-d', strtotime( $query_args['after'] ) );
		$before_date = gmdate( 'Y-m-d', strtotime( $query_args['before'] ) );
		$providers_id = isset( $query_args['providers'] ) && '' != $query_args['providers'] ? $query_args['providers'] : [];
		$shipment_status = isset( $query_args['shipment_status'] ) && '' != $query_args['shipment_status'] ? $query_args['shipment_status'] : '';
		$interval = isset( $query_args['interval'] ) && '' != $query_args['interval'] ? $query_args['interval'] : '';

		// data by date from TrackShip shipments table
		$intervals = $this->get_data_by_shipments( $after_date, $before_date, $shipment_status, $interval, $providers_id );

		$total_shipments = $this->get_totals_shipment( 'COUNT(*)', $after_date, $before_date, $shipment_status, $providers_id, 'total' ); // totals count
		$active_shipments = $this->get_totals_shipment( 'COUNT(*)', $after_date, $before_date, $shipment_status, $providers_id, 'active' ); // active count
		$delivered_shipments = $this->get_totals_shipment( 'COUNT(*)', $after_date, $before_date, $shipment_status, $providers_id, 'delivered' ); // delivered count
		$avg_shipment_length = $this->get_totals_shipment( 'AVG(ts.shipping_length)', $after_date, $before_date, $shipment_status, $providers_id ); // get AVG data

		// Create totals objects
		$totals = (object) [
			'total_shipments'		=> (int) $total_shipments,
			'active_shipments'		=> (int) $active_shipments,
			'delivered_shipments'	=> (int) $delivered_shipments,
			'avg_shipment_length'	=> (int) $avg_shipment_length,
		];
		
		// Create response object from Totals and Intervals
		$data = (object) array(
			'totals'	=> $totals,
			'intervals'	=> $intervals,
		);
		
		$db_intervals = array_column($intervals, 'time_interval');

		//fill missing interval from Intervals objects
		$data = $this->fill_in_missing_intervals( $db_intervals, new DateTime($query_args['after']), new DateTime($query_args['before']), $query_args['interval'], $data );

		return rest_ensure_response( $data );
	}

	/**
	 * Fill missing interval from data object
	 */
	public function fill_in_missing_intervals( $db_intervals, $start_datetime, $end_datetime, $time_interval, &$data ) {
		// @todo This is ugly and messy.
		$local_tz = new \DateTimeZone( wc_timezone_string() );
		// At this point, we don't know when we can stop iterating, as the ordering can be based on any value.
		$time_ids		= array_flip( wp_list_pluck( $data->intervals, 'time_interval' ) );
		$db_intervals	= array_flip( $db_intervals );
		// Totals object used to get all needed properties.
		$totals_arr = get_object_vars( $data->totals );
		foreach ( $totals_arr as $key => $val ) {
			$totals_arr[ $key ] = 0;
		}

		// @todo Should 'products' be in intervals?
		unset( $totals_arr['products'] );
		while ( $start_datetime <= $end_datetime ) {
			$next_start	= TimeInterval::iterate( $start_datetime, $time_interval );
			$time_id	= TimeInterval::time_interval_id( $time_interval, $start_datetime );
			// Either create fill-zero interval or use data from db.
			if ( $next_start > $end_datetime ) {
				$interval_end = $end_datetime->format( 'Y-m-d H:i:s' );
			} else {
				$prev_end_timestamp	= (int) $next_start->format( 'U' ) - 1;
				$prev_end			= new \DateTime();
				$prev_end->setTimestamp( $prev_end_timestamp );
				$prev_end->setTimezone( $local_tz );
				$interval_end = $prev_end->format( 'Y-m-d H:i:s' );
			}
			if ( array_key_exists( $time_id, $time_ids ) ) {
				// For interval present in the db for this time frame, just fill in dates.
				$record	= &$data->intervals[ $time_ids[ $time_id ] ];
				$shipments_data = array(
					'total_shipments' => (int) $record->total_shipments,
					'active_shipments' => (int) $record->active_shipments,
					'delivered_shipments' => (int) $record->delivered_shipments
				);

				$record->date_start = $start_datetime->format( 'Y-m-d H:i:s' );
				$record->date_end	= $interval_end;
				$record->subtotals	= $shipments_data;


			} elseif ( ! array_key_exists( $time_id, $db_intervals ) ) {
				// For intervals present in the db outside of this time frame, do nothing.
				// For intervals not present in the db, fabricate it.
				$record_arr						= array();
				$record_arr['time_interval']	= $time_id;
				$record_arr['date_start']		= $start_datetime->format( 'Y-m-d H:i:s' );
				$record_arr['date_end']			= $interval_end;
				$record_arr['subtotals']		= $totals_arr;
				$data->intervals[]				= $record_arr;
			}
			$start_datetime = $next_start;
		}

		array_multisort( array_column( $data->intervals, 'time_interval' ), SORT_ASC, $data->intervals);
		
		return $data;
	}

	/**
	 * Get Totals count of Shipments
	 */
	public function get_totals_shipment( $select, $after_date, $before_date, $shipment_status = '', $providers_id = [], $type = '' ) {
		global $wpdb;
		$woo_trackship_shipment = $this->shipment_table;

		$where = [];

		if ( $after_date && $before_date ) {
			$where[] = " ts.shipping_date between '$after_date' and '$before_date' ";
		}

		if ( 'active' == $type ) {
			$where[] = " ts.shipment_status NOT IN ('delivered','return_to_sender') ";
		} elseif ( 'delivered' == $type ) {
			$where[] = " ts.shipment_status IN ('delivered','return_to_sender') ";
		}

		if ( $shipment_status ) {
			$where[] = " ts.shipment_status = '{$shipment_status}'";
		}

		if ( $providers_id ) {
			$providers_id = explode( ',', $providers_id);
			$where[] = ' tp.id IN (' . implode(',', $providers_id) . ')' ;
		}

		$where_condition = '';
		if ( $where ) {
			$where_condition = ' WHERE ' . implode(' AND ', $where);
		}

		$sql = "
			SELECT
				{$select}
			FROM " . $woo_trackship_shipment . ' ts
			LEFT JOIN ' . $wpdb->prefix . "trackship_shipping_provider tp 
				ON ts.shipping_provider = tp.ts_slug
			$where_condition
		";
		// echo $wpdb->last_query;
		return $wpdb->get_var( $sql );
	}

	/**
	 * Get data by Shipments
	 */
	public function get_data_by_shipments( $after_date, $before_date, $shipment_status, $interval, $providers_id = [] ) {
		if ( 'hour' == $interval ) {
			$db_format = '%Y-%m-%d %H';
		} else if ( 'day' == $interval ) {
			$db_format = '%Y-%m-%d';
		} else if ( 'week' == $interval ) {
			$db_format = '%Y-%u';
		} else if ( 'month' == $interval ) {
			$db_format = '%Y-%m';
		} else if ( 'year' == $interval ) {
			$db_format = '%Y';
		} else {
			$db_format = '%Y-%m-%d';
		}

		$where = array();
		if ( $after_date && $before_date ) {
			$where[] = " ts.shipping_date between '$after_date' and '$before_date' ";
		}

		if ( $shipment_status ) {
			$where[] = " ts.shipment_status = '{$shipment_status}'";
		}

		if ( $providers_id ) {
			$where[] = " tp.id IN ({$providers_id}) ";
		}

		$where_condition = '';
		if ( $where ) {
			$where_condition = ' WHERE ' . implode( ' AND ', $where );
		}

		global $wpdb;
		$woo_trackship_shipment = $this->shipment_table;
		$sql = "
			SELECT 
				DATE_FORMAT(shipping_date, '$db_format') as time_interval, count(DATE(shipping_date)) as total_shipments,
				SUM( CASE WHEN ts.shipment_status NOT IN ('delivered','return_to_sender') THEN 1 ELSE 0 END ) as active_shipments,
				SUM( CASE WHEN ts.shipment_status IN ('delivered','return_to_sender') THEN 1 ELSE 0 END ) as delivered_shipments
				
			FROM " . $woo_trackship_shipment . ' ts
			LEFT JOIN ' . $wpdb->prefix . "trackship_shipping_provider tp
				ON ts.shipping_provider = tp.ts_slug
			$where_condition 
			GROUP BY time_interval 
			ORDER BY time_interval ASC 
		";
		$res = $wpdb->get_results( $sql );
		return $res;
	}

	/**
	 * Get shipments count by providers.
	 */
	public function get_shipments_providers( $request ) {

		global $wpdb;
		$woo_trackship_shipment = $this->shipment_table;

		$id = $request->get_param('include');
		$where = $id ? "WHERE id IN ( {$id} )" : '';
		$all_providers = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}trackship_shipping_provider {$where}" );

		return rest_ensure_response( $all_providers );
	}

	/**
	 * Get shipments by providers.
	 */
	public function get_shipments_by_providers( $request ) {
		
		$query_args = $this->prepare_reports_query( $request );
		$data = array();
		$after_date = gmdate( 'Y-m-d', strtotime( $query_args['after'] ) );
		$before_date = gmdate( 'Y-m-d', strtotime( $query_args['before'] ) );
		$shipment_status = ( isset( $query_args['shipment_status'] ) && '' != $query_args['shipment_status'] ? $query_args['shipment_status'] : '' );
		$providers_id = ( isset( $query_args['providers'] ) && '' != $query_args['providers'] ? $query_args['providers'] : null );
		$shipment_provider = ( isset( $query_args['shipment_provider'] ) && '' != $query_args['shipment_provider'] ? $query_args['shipment_provider'] : '' );
		
		global $wpdb;
		$woo_trackship_shipment = $this->shipment_table;
		
		$where = array();
		if ( $after_date && $before_date ) {
			$where[] = " ts.shipping_date between '$after_date' and '$before_date' ";
		}

		if ( $shipment_status ) {
			$where[] = " ts.shipment_status = '{$shipment_status}'";
		}
		
		if ( $providers_id ) {
			$providers_id = explode( ',', $providers_id );
			$where[] = ' tp.id IN (' . implode(',', $providers_id) . ')' ;
		}

		$where_condition = '';
		if ( $where ) {
			$where_condition = ' WHERE ' . implode( ' AND ', $where );
		}
		$left_join = 'LEFT JOIN ' . $wpdb->prefix . 'trackship_shipping_provider tp ON ts.shipping_provider = tp.ts_slug';

		$status_sql = '
			SELECT 
				ts.shipment_status ,
				COUNT(1) AS total ,
				ROUND( COUNT(1) / (SELECT COUNT(1) FROM ' . $woo_trackship_shipment . ' as ts ' . $left_join . $where_condition . ') * 100 ) AS percentage 
			FROM ' . $woo_trackship_shipment . " ts
			$left_join
			$where_condition
			GROUP BY ts.shipment_status	
		";
		$res_by_status = $wpdb->get_results( $status_sql );
		// echo $wpdb->last_query;

		$provider_sql = '
			SELECT 
				ts.shipping_provider ,
				tp.provider_name,
				COUNT(1) AS total ,
				ROUND( COUNT(1) / (SELECT COUNT(1) FROM ' . $woo_trackship_shipment . ' as ts ' . $left_join . $where_condition . ') * 100 ) AS percentage ,
				ROUND( AVG(shipping_length) ) as average
			FROM ' . $woo_trackship_shipment . " ts
			$left_join
			$where_condition
			GROUP BY ts.shipping_provider
		";
		$res_by_provider = $wpdb->get_results( $provider_sql );

		$response['shipment_status'] = $res_by_status;
		$response['shipping_provider'] = $res_by_provider;
		$response['status_url'] = admin_url( 'admin.php?page=trackship-shipments&status=' );
		$response['provider_url'] = admin_url( 'admin.php?page=trackship-shipments&provider=' );
		return rest_ensure_response( $response );
	}

	/**
	 * Get the query params for collections.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		return array(
			'context' => $this->get_context_param( array( 'default' => 'view' ) ),
		);
	}
}
