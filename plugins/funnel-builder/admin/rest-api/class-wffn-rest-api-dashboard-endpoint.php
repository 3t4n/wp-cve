<?php

if ( ! class_exists( 'WFFN_REST_API_Dashboard_EndPoint' ) ) {
	class WFFN_REST_API_Dashboard_EndPoint extends WFFN_REST_Controller {

		private static $ins = null;
		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'funnel-analytics';

		/**
		 * WFFN_REST_API_Dashboard_EndPoint constructor.
		 */
		public function __construct() {

			add_action( 'rest_api_init', [ $this, 'register_endpoint' ], 12 );
		}

		/**
		 * @return WFFN_REST_API_Dashboard_EndPoint|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function register_endpoint() {
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/dashboard/stats/', array(
				array(
					'args'                => $this->get_stats_collection(),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_graph_data' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/dashboard/overview/', array(
				array(
					'args'                => $this->get_stats_collection(),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_overview_data' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/stream/timeline/', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_timeline_funnels' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/dashboard/', array(
				array(
					'args'                => $this->get_stats_collection(),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_all_stats_data' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/dashboard/sources', array(
				array(
					'args'                => $this->get_stats_collection(),
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_all_source_data' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
				),
			) );
		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'analytics', 'read' );
		}

		public function get_overview_data( $request ) {
			if ( isset( $request['overall'] ) ) {
				$start_date = '';
				$end_date   = '';
			} else {
				$start_date = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : self::default_date( WEEK_IN_SECONDS )->format( self::$sql_datetime_format );
				$end_date   = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : self::default_date()->format( self::$sql_datetime_format );

			}

			$funnel_id      = '';
			$total_revenue  = null;
			$aero_revenue   = 0;
			$upsell_revenue = 0;
			$bump_revenue   = 0;

			$get_total_revenue = $this->get_total_revenue( $funnel_id, $start_date, $end_date );
			$get_total_orders  = $this->get_total_orders( $funnel_id, $start_date, $end_date );

			if ( ! isset( $get_total_revenue['db_error'] ) ) {
				if ( count( $get_total_revenue['aero'] ) > 0 ) {
					$aero_revenue  = $get_total_revenue['aero'][0]['sum_aero'];
					$total_revenue += $aero_revenue;
				}
				if ( count( $get_total_revenue['bump'] ) > 0 ) {
					$bump_revenue  = $get_total_revenue['bump'][0]['sum_bump'];
					$total_revenue += $bump_revenue;
				}
				if ( count( $get_total_revenue['upsell'] ) > 0 ) {
					$upsell_revenue = $get_total_revenue['upsell'][0]['sum_upsells'];
					$total_revenue  += $upsell_revenue;
				}
			}

			$result = [
				'revenue'          => is_null( $total_revenue ) ? 0 : $total_revenue,
				'total_orders'     => intval( $get_total_orders ),
				'checkout_revenue' => floatval( $aero_revenue ),
				'upsell_revenue'   => floatval( $upsell_revenue ),
				'bump_revenue'     => floatval( $bump_revenue ),
			];

			$resp = array(
				'status' => true,
				'msg'    => __( 'success', 'funnel-builder' ),
				'data'   => $result
			);

			return rest_ensure_response( $resp );

		}

		public function get_graph_data( $request ) {
			$resp = array(
				'status' => false,
				'data'   => []
			);

			$interval_type = '';

			if ( isset( $request['overall'] ) ) {
				global $wpdb;
				$request['after']    = $wpdb->get_var( $wpdb->prepare( "SELECT date FROM {$wpdb->prefix}wfacp_stats WHERE fid != '' ORDER BY ID ASC LIMIT %d", 1 ) );
				$start_date          = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : self::default_date( WEEK_IN_SECONDS )->format( self::$sql_datetime_format );
				$end_date            = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : self::default_date()->format( self::$sql_datetime_format );
				$request['interval'] = $this->get_two_date_interval( $start_date, $end_date );
				$interval_type       = $request['interval'];
			}

			$totals    = $this->prepare_graph_for_response( $request );
			$intervals = $this->prepare_graph_for_response( $request, 'interval' );

			if ( ! is_array( $totals ) || ! is_array( $intervals ) ) {
				return rest_ensure_response( $resp );
			}

			$resp = array(
				'status' => true,
				'data'   => array(
					'totals'    => $totals,
					'intervals' => $intervals
				)
			);

			if ( isset( $request['overall'] ) ) {
				$resp['data']['interval_type'] = $interval_type;
			}

			return rest_ensure_response( $resp );
		}

		public function prepare_graph_for_response( $request, $is_interval = '' ) {
			$start_date  = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : self::default_date( WEEK_IN_SECONDS )->format( self::$sql_datetime_format );
			$end_date    = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : self::default_date()->format( self::$sql_datetime_format );
			$int_request = ( isset( $request['interval'] ) && '' !== $request['interval'] ) ? $request['interval'] : 'week';


			$funnel_id      = '';
			$total_revenue  = null;
			$aero_revenue   = 0;
			$upsell_revenue = 0;
			$bump_revenue   = 0;

			$get_total_orders = $this->get_total_orders( $funnel_id, $start_date, $end_date, $is_interval, $int_request );
			if ( isset( $get_total_orders['db_error'] ) ) {
				$get_total_orders = 0;
			}
			$get_total_revenue = $this->get_total_revenue( $funnel_id, $start_date, $end_date, $is_interval, $int_request );

			$result    = [];
			$intervals = array();
			if ( ! empty( $is_interval ) ) {
				$overall = isset( $request['overall'] ) ? true : false ;
				$intervals_all = $this->intervals_between( $start_date, $end_date, $int_request, $overall );
				foreach ( $intervals_all as $all_interval ) {
					$interval   = $all_interval['time_interval'];
					$start_date = $all_interval['start_date'];
					$end_date   = $all_interval['end_date'];

					$get_total_order = is_array( $get_total_orders ) ? $this->maybe_interval_exists( $get_total_orders, 'time_interval', $interval ) : [];

					if ( ! isset( $get_total_revenue['db_error'] ) ) {
						$total_revenue_aero = $this->maybe_interval_exists( $get_total_revenue['aero'], 'time_interval', $interval );

						$total_revenue_aero = is_array( $total_revenue_aero ) ? $total_revenue_aero[0]['sum_aero'] : 0;

						$total_revenue_bump = $this->maybe_interval_exists( $get_total_revenue['bump'], 'time_interval', $interval );
						$total_revenue_bump = is_array( $total_revenue_bump ) ? $total_revenue_bump[0]['sum_bump'] : 0;

						$total_revenue_upsells = $this->maybe_interval_exists( $get_total_revenue['upsell'], 'time_interval', $interval );
						$total_revenue_upsells = is_array( $total_revenue_upsells ) ? $total_revenue_upsells[0]['sum_upsells'] : 0;
					} else {
						$total_revenue_aero    = 0;
						$total_revenue_bump    = 0;
						$total_revenue_upsells = 0;
					}

					$get_total_order             = is_array( $get_total_order ) ? $get_total_order[0]['total_orders'] : 0;
					$intervals['interval']       = $interval;
					$intervals['start_date']     = $start_date;
					$intervals['date_start_gmt'] = $this->convert_local_datetime_to_gmt( $start_date )->format( self::$sql_datetime_format );
					$intervals['end_date']       = $end_date;
					$intervals['date_end_gmt']   = $this->convert_local_datetime_to_gmt( $end_date )->format( self::$sql_datetime_format );
					$total_revenue               = $total_revenue_aero + $total_revenue_bump + $total_revenue_upsells;
					$intervals['subtotals']      = array(
						'orders'           => $get_total_order,
						'revenue'          => $total_revenue,
						'checkout_revenue' => floatval( $total_revenue_aero ),
						'upsell_revenue'   => floatval( $total_revenue_upsells ),
						'bump_revenue'     => floatval( $total_revenue_bump ),
					);

					$result[] = $intervals;

				}

			} else {
				if ( ! isset( $get_total_revenue['db_error'] ) ) {
					if ( count( $get_total_revenue['aero'] ) > 0 ) {
						$aero_revenue  = $get_total_revenue['aero'][0]['sum_aero'];
						$total_revenue += $aero_revenue;
					}
					if ( count( $get_total_revenue['bump'] ) > 0 ) {
						$bump_revenue  = $get_total_revenue['bump'][0]['sum_bump'];
						$total_revenue += $bump_revenue;
					}
					if ( count( $get_total_revenue['upsell'] ) > 0 ) {
						$upsell_revenue = $get_total_revenue['upsell'][0]['sum_upsells'];
						$total_revenue  += $upsell_revenue;
					}
				}

				$result = [
					'orders'           => $get_total_orders,
					'revenue'          => is_null( $total_revenue ) ? 0 : $total_revenue,
					'checkout_revenue' => floatval( $aero_revenue ),
					'upsell_revenue'   => floatval( $upsell_revenue ),
					'bump_revenue'     => floatval( $bump_revenue ),
				];
			}

			return $result;

		}

		public function get_all_stats_data( $request ) {
			$response                = array();
			$response['top_funnels'] = $this->get_top_funnels( $request );

			$top_campaigns = array(
				'sales' => array(),
				'lead'  => array()
			);

			$top_campaigns = apply_filters( 'wffn_dashboard_top_campaigns',$top_campaigns, $request );

			$response['top_campaigns'] = $top_campaigns;

			return rest_ensure_response( $response );
		}

		public function get_top_funnels( $request ) {
			$limit = isset( $request['top_funnels_limit'] ) ? $request['top_funnels_limit'] : ( isset( $request['limit'] ) ? $request['limit'] : 5 );
			global $wpdb;
			$fid           = 0;
			$sales_funnels = [];
			$sales_ids     = [];
			$top_funnels   = array(
				'sales' => array(),
				'lead'  => array()
			);

			if ( isset( $request['overall'] ) ) {
				$date_query = ' 1=1 ';
			} else {
				$start_date = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : WFFN_REST_API_Dashboard_EndPoint::get_instance()->default_date( WEEK_IN_SECONDS )->format( WFFN_REST_API_Dashboard_EndPoint::get_instance()::$sql_datetime_format ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				$end_date   = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : WFFN_REST_API_Dashboard_EndPoint::get_instance()->default_date()->format( WFFN_REST_API_Dashboard_EndPoint::get_instance()::$sql_datetime_format ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				$date_query = " {{COLUMN}} >= '" . $start_date . "' AND {{COLUMN}} < '" . $end_date . "'";

			}
			/**
			 * get aero funnels
			 */
			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj     = WFACP_Contacts_Analytics::get_instance();
				$aero_funnels = $aero_obj->get_top_funnels( $limit, $date_query );
				if ( ! isset( $aero_funnels['db_error'] ) ) {
					$sales_funnels = array_merge( $sales_funnels, $aero_funnels );
				}
			}

			/**
			 * get bump funnels
			 */
			if ( defined( 'WFFN_PRO_VERSION' ) && class_exists( 'WFOB_Contacts_Analytics' ) ) {
				$bump_obj     = WFOB_Contacts_Analytics::get_instance();
				$bump_funnels = $bump_obj->get_top_funnels( $limit, $date_query );
				if ( ! isset( $bump_funnels['db_error'] ) ) {
					$sales_funnels = array_merge( $sales_funnels, $bump_funnels );
				}
			}

			/**
			 * get upsells funnels
			 */
			if ( defined( 'WFFN_PRO_VERSION' ) && class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$upsell_obj     = WFOCU_Contacts_Analytics::get_instance();
				$upsell_funnels = $upsell_obj->get_top_funnels( $limit, $date_query );
				if ( ! isset( $upsell_funnels['db_error'] ) ) {
					$sales_funnels = array_merge( $sales_funnels, $upsell_funnels );
				}
			}

			if ( is_array( $sales_funnels ) && count( $sales_funnels ) > 0 ) {
				$total_sale = [];
				$funnel_ids = [];
				$i          = 0;

				foreach ( $sales_funnels as $item ) {
					$fid   = $item['fid'];
					$total = isset( $item['total'] ) ? $item['total'] : 0;
					$title = ( isset( $item['title'] ) && ! empty( $item['title'] ) ) ? $item['title'] : '#' . $fid;
					if ( array_key_exists( $fid, $funnel_ids ) ) {
						$total_sale[ $funnel_ids[ $fid ] ]['total'] = ( $total_sale[ $funnel_ids[ $fid ] ]['total'] + $total );
					} else {
						$total_sale[ $i ]   = array(
							'conversion'      => 0,
							'conversion_rate' => 0,
							'link'            => WFFN_Common::get_funnel_edit_link( $fid ),
							'views'           => 0,
							'fid'             => absint( $fid ),
							'title'           => $title,
							'total'           => $total
						);
						$funnel_ids[ $fid ] = $i;
						$i ++;
					}
				}

				/**
				 * sort Funnels based on revenues
				 */
				if ( ! empty( $total_sale ) ) {
					usort( $total_sale, function ( $a, $b ) {
						if ( $a['total'] < $b['total'] ) {
							return 1;
						}
						if ( $a['total'] > $b['total'] ) {
							return - 1;
						}
						if ( $a['total'] === $b['total'] ) {
							return - 1;
						}
					} );
				}
				$total_sale = array_slice( $total_sale, 0, $limit );

				$sales_ids            = array_unique( wp_list_pluck( $total_sale, 'fid' ) );
				$top_funnels['sales'] = apply_filters( 'wffn_top_sales_funnels', $total_sale, $sales_ids );

			}

			/**
			 * excludes all sales funnel from optin
			 */
			$exclude_sale_funnel = ( is_array( $sales_ids ) && count( $sales_ids ) > 0 ) ? " AND entry.funnel_id NOT IN (" . implode( ',', $sales_ids ) . ") " : '';
			$op_query = "SELECT funnel.id as fid, funnel.title AS title, report.views AS views, COUNT( DISTINCT entry.id) as conversion, 0 as total, (CASE WHEN report.views != 0 THEN ROUND(COUNT( DISTINCT entry.id) * 100/report.views, 2 ) ELSE 0 END) as conversion_rate FROM " . $wpdb->prefix . "bwf_funnels AS funnel 
    			LEFT JOIN ( SELECT object_id, SUM( no_of_sessions ) AS views, type FROM " . $wpdb->prefix . "wfco_report_views as vw WHERE type = 7 AND ".str_replace( '{{COLUMN}}', 'vw.date', $date_query )." GROUP by object_id ORDER BY object_id ) as report ON funnel.id = report.object_id 
    			LEFT JOIN " . $wpdb->prefix . "bwf_optin_entries as entry ON funnel.id = entry.funnel_id 
                WHERE funnel.steps NOT LIKE '%wc_checkout%' AND report.type = 7 " . $exclude_sale_funnel . " AND ".str_replace( '{{COLUMN}}', 'entry.date', $date_query )." GROUP BY funnel.id ORDER BY conversion DESC LIMIT " . $limit;

			$optin_funnels = $wpdb->get_results( $op_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			if ( is_array( $optin_funnels ) && count( $optin_funnels ) > 0 ) {
				foreach ( $optin_funnels as &$item ) {
					if ( ! defined( 'WFFN_PRO_VERSION' ) ) {
						$item['views']           = 0;
						$item['conversion_rate'] = 0;
					}
					$item['title'] = ( isset( $item['title'] ) && ! empty( $item['title'] ) ) ? $item['title'] : '#' . $fid;
					$item['link']  = WFFN_Common::get_funnel_edit_link( $item['fid'] );
				}
				$top_funnels['lead'] = $optin_funnels;
			}

			return $top_funnels;

		}

		public function get_timeline_funnels() {
			global $wpdb;
			$aero_timeline   = '';
			$bump_timeline   = '';
			$upsell_timeline = '';
			$optin_timeline  = '';
			$limit           = 20;
			$can_union       = false;
			/**
			 * get aero timeline
			 */
			if ( class_exists( 'WFACP_Contacts_Analytics' ) && defined( 'WFACP_VERSION' ) && ( version_compare( WFACP_VERSION, '2.0.7', '>=' ) ) ) {
				$aero_obj      = WFACP_Contacts_Analytics::get_instance();
				$aero_timeline = $aero_obj->get_timeline_data_query( $limit );
				$can_union     = true;
			}

			/**
			 * get bump timeline
			 */
			if ( class_exists( 'WFOB_Contacts_Analytics' ) && defined( 'WFOB_VERSION' ) && ( version_compare( WFOB_VERSION, '1.8.1', '>=' ) ) ) {
				$bump_obj      = WFOB_Contacts_Analytics::get_instance();
				$bump_timeline = $bump_obj->get_timeline_data_query( $limit );
				$can_union     = true;
			}

			/**
			 * get upsells timeline
			 */
			if ( class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$upsell_obj      = WFOCU_Contacts_Analytics::get_instance();
				$upsell_timeline = $upsell_obj->get_timeline_data_query( $limit );
				$can_union       = true;
			}

			/**
			 * get optin timeline
			 */
			if ( class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
				$optin_obj      = WFFN_Optin_Contacts_Analytics::get_instance();
				$optin_timeline = $optin_obj->get_timeline_data_query( $limit, 'DESC', 'date', $can_union );
			}
			if ( $can_union === true ) {
				$final_q = "SELECT u.id as id, u.fid as fid, u.cid as cid, u.order_id as order_id, u.total_revenue as tot, coalesce(u.post_title, '') as post_title, coalesce(contact.f_name, '') as f_name, coalesce(contact.l_name, '') as l_name, u.type as type, u.date as date FROM (";
				if ( ! empty( $aero_timeline ) ) {
					$final_q .= '(';
					$final_q .= $aero_timeline;
					$final_q .= ') ';
				}
				if ( ! empty( $bump_timeline ) ) {
					$final_q .= 'UNION ALL (';
					$final_q .= $bump_timeline;
					$final_q .= ') ';
				}
				if ( ! empty( $optin_timeline ) ) {
					$final_q .= 'UNION ALL (';
					$final_q .= $optin_timeline;
					$final_q .= ') ';
				}
				if ( ! empty( $upsell_timeline ) ) {
					$final_q .= 'UNION ALL (';
					$final_q .= $upsell_timeline;
					$final_q .= ') ';
				}

				$final_q .= ')u LEFT JOIN ' . $wpdb->prefix . 'bwf_contact AS contact ON contact.id=cid WHERE contact.id != "" ORDER BY date DESC LIMIT ' . $limit;
			} else {
				$final_q = $optin_timeline;
			}

			$steps    = $wpdb->get_results( $final_q, ARRAY_A ); //phpcs:ignore
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return rest_ensure_response( $db_error );
			}

			if ( ! is_array( $steps ) || count( $steps ) === 0 ) {
				return rest_ensure_response( [] );
			}

			foreach ( $steps as &$step ) {
				if ( isset( $step['id'] ) && isset( $step['type'] ) ) {
					$step['edit_link'] = WFFN_Common::get_step_edit_link( $step['id'], $step['type'], $step['fid'], true );
				}
				if ( isset( $step['order_id'] ) ) {
					if ( wffn_is_wc_active() ) {
						$order = wc_get_order( $step['order_id'] );
						if ( $order instanceof WC_Order ) {
							if ( absint( $step['fid'] ) === WFFN_Common::get_store_checkout_id() ) {
								$step['order_edit_link'] = WFFN_Common::get_store_checkout_edit_link( '/orders' );
							} else {
								$step['order_edit_link'] = WFFN_Common::get_funnel_edit_link( $step['fid'], '/orders' );
							}
						} else {
							$step['order_edit_link'] = '';
						}
					} else {
						$step['order_edit_link'] = '';
					}

				}
			}

			return rest_ensure_response( $steps );

		}

		public function get_total_orders( $funnel_id, $start_date, $end_date, $is_interval = '', $int_request = '' ) {

			$total_orders = 0;
			$intervals    = [];

			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj    = WFACP_Contacts_Analytics::get_instance();
				$aero_orders = $aero_obj->get_total_orders( $funnel_id, $start_date, $end_date, $is_interval, $int_request );
				if ( isset( $aero_orders['db_error'] ) ) {

					return $total_orders;
				}

				if ( is_array( $aero_orders ) && count( $aero_orders ) > 0 ) {
					if ( 'interval' === $is_interval ) {
						$intervals = ( is_array( $aero_orders ) && count( $aero_orders ) > 0 ) ? $aero_orders : [];
					} else {
						$total_orders = isset( $aero_orders[0]['total_orders'] ) ? absint( $aero_orders[0]['total_orders'] ) : 0;
					}
				}
			}

			return ( 'interval' === $is_interval ) ? $intervals : $total_orders;
		}

		public function get_total_revenue( $funnel_id, $start_date, $end_date, $is_interval = '', $int_request = '' ) {

			$total_revenue_aero    = [];
			$total_revenue_bump    = [];
			$total_revenue_upsells = [];

			/**
			 * get aero revenue
			 */
			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj           = WFACP_Contacts_Analytics::get_instance();
				$total_revenue_aero = $aero_obj->get_total_revenue( $funnel_id, $start_date, $end_date, $is_interval, $int_request );
				if ( isset( $total_revenue_aero['db_error'] ) ) {
					return $total_revenue_aero;
				}
			}

			/**
			 * get bump revenue
			 */
			if ( defined( 'WFFN_PRO_VERSION' ) && class_exists( 'WFOB_Contacts_Analytics' ) ) {
				$bump_obj           = WFOB_Contacts_Analytics::get_instance();
				$total_revenue_bump = $bump_obj->get_total_revenue( $funnel_id, $start_date, $end_date, $is_interval, $int_request );
				if ( isset( $total_revenue_bump['db_error'] ) ) {
					return $total_revenue_bump;
				}
			}

			/**
			 * get upsells revenue
			 */
			if ( defined( 'WFFN_PRO_VERSION' ) && class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$upsell_obj            = WFOCU_Contacts_Analytics::get_instance();
				$total_revenue_upsells = $upsell_obj->get_total_revenue( $funnel_id, $start_date, $end_date, $is_interval, $int_request );
				if ( isset( $total_revenue_upsells['db_error'] ) ) {
					return $total_revenue_upsells;
				}
			}

			return array( 'aero' => $total_revenue_aero, 'bump' => $total_revenue_bump, 'upsell' => $total_revenue_upsells );
		}

		public function get_all_source_data( $request ) {
			$resp = array(
				'status' => false,
				'msg'    => __( 'failed', 'funnel-builder' ),
				'data'   => [
					'sales' => [],
					'lead'  => []
				]
			);

			if ( isset( $request['overall'] ) ) {
				$start_date = '';
				$end_date   = '';
			} else {
				$start_date = ( isset( $request['after'] ) && '' !== $request['after'] ) ? $request['after'] : self::default_date( WEEK_IN_SECONDS )->format( self::$sql_datetime_format );
				$end_date   = ( isset( $request['before'] ) && '' !== $request['before'] ) ? $request['before'] : self::default_date()->format( self::$sql_datetime_format );

			}

			$args = [
				'start_date' => $start_date,
				'end_date'   => $end_date,
			];

			$conv_data = apply_filters( 'wffn_source_data_by_conversion_query', [], $args );

			if ( is_array( $conv_data ) && count( $conv_data ) > 0 ) {
				$resp['data']['sales'] = $conv_data['sales'];
				$resp['data']['lead']  = $conv_data['lead'];
			}

			$resp['status'] = true;
			$resp['msg']    = __( 'success', 'funnel-builder' );

			return rest_ensure_response( $resp );
		}

		public function get_stats_collection() {
			$params = array();

			$params['after']  = array(
				'type'              => 'string',
				'format'            => 'date-time',
				'validate_callback' => 'rest_validate_request_arg',
				'description'       => __( 'Limit response to resources published after a given ISO8601 compliant date.', 'funnel-builder' ),
			);
			$params['before'] = array(
				'type'              => 'string',
				'format'            => 'date-time',
				'validate_callback' => 'rest_validate_request_arg',
				'description'       => __( 'Limit response to resources published before a given ISO8601 compliant date.', 'funnel-builder' ),
			);
			$params['limit']  = array(
				'type'              => 'integer',
				'default'           => 5,
				'validate_callback' => 'rest_validate_request_arg',
				'description'       => __( 'Limit response to resources published before a given ISO8601 compliant date.', 'funnel-builder' ),
			);

			return apply_filters( 'wfocu_rest_funnels_dashboard_stats_collection', $params );
		}

	}

	WFFN_REST_API_Dashboard_EndPoint::get_instance();
}
