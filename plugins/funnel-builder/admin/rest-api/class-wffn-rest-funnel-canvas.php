<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Funnel_Canvas
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Funnel_Canvas' ) ) {
	#[AllowDynamicProperties]
	class WFFN_REST_Funnel_Canvas extends WFFN_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'canvas/(?P<funnel_id>[\d]+)/nodes';

		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/**
		 * Register the routes for taxes.
		 */
		public function register_routes() {
			register_rest_route( $this->namespace, '/' . $this->rest_base, array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_nodes' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/analytics', array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'get_analytics' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => [],
				),
			) );


		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		/**
		 * @param WP_REST_Request $request
		 *
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_nodes( $request ) {
			$funnel_id = $request->get_param( 'funnel_id' );

			$funnel = new WFFN_Funnel( $funnel_id );

			if ( ! $funnel instanceof WFFN_Funnel ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}
			$nodes_data  = $funnel->get_group_steps();
			$funnel_data = WFFN_REST_Funnels::get_instance()->get_funnel_data( $funnel_id );

			$nodes_data['steps_list'] = wffn_rest_api_helpers()->add_step_edit_details( $nodes_data['steps_list'] );
			$nodes_data['steps_list'] = apply_filters( 'wffn_rest_get_funnel_steps', $nodes_data['steps_list'], $funnel );

			return rest_ensure_response( array( 'status' => true, 'data' => $nodes_data, 'funnel_data' => $funnel_data ) );
		}


		/**
		 * Callback for analytics endpoint
		 *
		 * @param WP_REST_Request $request
		 *
		 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
		 */
		public function get_analytics( $request ) {
			$funnel_id = $request->get_param( 'funnel_id' );

			$funnel = new WFFN_Funnel( $funnel_id );

			if ( $funnel->get_id() === 0 ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}
			$data                     = $this->sanitize_custom( $request->get_body() );
			$nodes_data               = [];
			$nodes_data['steps_list'] = $this->prepare_analytics( $data['steps_list'] );

			return rest_ensure_response( array( 'status' => true, 'data' => $nodes_data ) );
		}

		/**
		 * @param $type
		 * @param $step
		 *
		 * @return array|false|false[]
		 */
		public function get_node_analytics_data( $type, $step ) {
			if ( $type === 'optin' ) {
				return $this->get_optin_stats( $step );
			}
			if ( $type === 'optin_ty' ) {
				return $this->get_optin_ty_stats( $step );
			}
			if ( $type === 'landing' ) {
				return $this->get_landing_stats( $step );
			}
			if ( $type === 'wc_checkout' ) {
				return $this->get_checkout_stats( $step );
			}
			if ( $type === 'offer' ) {
				return $this->get_offer_stats( $step );
			}
			if ( $type === 'wc_thankyou' ) {
				return $this->get_ty_stats( $step );
			}
			if ( $type === 'bump' || 'wc_order_bump' === $type ) {
				return $this->get_bump_stats( $step );
			}

			return [];

		}

		/**
		 * @param $step_id
		 *
		 * @return int[]|void
		 */
		public function prepare_analytics( $steps ) {

			if ( ! is_array( $steps ) || 0 === count( $steps ) ) {
				return $steps;
			}

			global $wpdb;
			$data = [];

			$defult_args = array(
				'views'           => 0,
				'conversions'     => 0,
				'conversion_rate' => 0,
				'revenue'         => 0
			);

			$get_all_data = $this->maybe_get_variants_ids( $steps );

			$steps = $get_all_data['steps'];

			foreach ( $steps as $step_id => $step ) {
				$data[ $step_id ] = $defult_args;
			}

			$ids      = array_keys( $steps );
			$step_ids = implode( ',', $ids );

			/*
			 * Get view and conversion data based on type
			 *
			 * 2 - Landing visited
			 * 3 - Landing converted
			 * 4 - Aero visited
			 * 5- Thank you visited
			 * 7 - Funnel session
			 * 8 - Optin visited
			 * 10 - Optin thank you visited
			 * 11 - Optin thank you converted
			 */
			$view_type    = "type = 2 OR type = 4 OR type = 5 OR type = 8 OR type = 10";
			$convert_type = "type = 3 OR type = 11";
			$view_query   = "SELECT object_id, SUM(CASE WHEN " . $view_type . " THEN `no_of_sessions` END) AS `views` ,SUM(CASE WHEN " . $convert_type . " THEN `no_of_sessions` END) AS `converted` FROM  " . $wpdb->prefix . 'wfco_report_views' . "  WHERE object_id IN(" . $step_ids . ") GROUP BY object_id ORDER BY object_id ASC";
			$get_views    = $wpdb->get_results( $view_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					WFFN_Core()->logger->log( "canvas views analytics error : " . print_r( $wpdb->last_error, true ) . "  query " . print_r( $wpdb->last_query, true ), 'wffn-failed-query', true );

					return $data;
				}
			}

			if ( is_array( $get_views ) && count( $get_views ) > 0 ) {
				foreach ( $get_views as $view_data ) {
					if ( isset( $data[ $view_data['object_id'] ] ) ) {
						$data[ $view_data['object_id'] ]['views']           = is_null( $view_data['views'] ) ? 0 : intval( $view_data['views'] );
						$data[ $view_data['object_id'] ]['conversions']     = is_null( $view_data['converted'] ) ? 0 : intval( $view_data['converted'] );
						$data[ $view_data['object_id'] ]['conversion_rate'] = $this->get_percentage( $data[ $view_data['object_id'] ]['views'], $data[ $view_data['object_id'] ]['conversions'] );
					}
				}
			}

			$data = $this->get_analytics_conversion_data( $data, $steps );


			/**
			 * merge variants analytics data in control and remove variants from step list
			 */
			if ( is_array( $get_all_data['variants'] ) && count( $get_all_data['variants'] ) > 0 ) {
				foreach ( $get_all_data['variants'] as $control_id => $item ) {
					if ( isset( $data[ $control_id ] ) ) {
						foreach ( $get_all_data['variants'][ $control_id ] as $v ) {
							if ( isset( $data[ $v ] ) ) {
								$data[ $control_id ]['views']           = $data[ $control_id ]['views'] + $data[ $v ]['views'];
								$data[ $control_id ]['conversions']     = $data[ $control_id ]['conversions'] + $data[ $v ]['conversions'];
								$data[ $control_id ]['revenue']         = floatval( number_format( $data[ $control_id ]['revenue'] + $data[ $v ]['revenue'], 2, '.', '' ) );
								$data[ $control_id ]['conversion_rate'] = $this->get_percentage( $data[ $control_id ]['views'], $data[ $control_id ]['conversions'] );
								unset( $data[ $v ] );
							}
						}
					}
				}
			}

			return $data;

		}

		/**
		 * get all variants ids for prepare analytics data
		 *
		 * @param $steps
		 *
		 * @return array
		 */
		public function maybe_get_variants_ids( $steps ) {
			$data = [
				'steps'    => $steps,
				'variants' => []
			];
			if ( class_exists( 'WFFN_Pro_Core' ) ) {
				$op_ids = array_keys( $data['steps'], 'optin' );
				foreach ( $op_ids as $op_id ) {
					$op_step = WFFN_Pro_Core()->steps->get_integration_object( 'optin' );
					if ( $op_step instanceof WFFN_Pro_Step ) {
						$op_variants = $op_step->maybe_get_ab_variants( $op_id );
						if ( is_array( $op_variants ) && count( $op_variants ) > 0 ) {
							$data['variants'][ $op_id ] = $op_variants;
							foreach ( $op_variants as $op_variant ) {
								$data['steps'][ $op_variant ] = 'optin';
							}
						}
					}
				}

				$opt_ids = array_keys( $data['steps'], 'optin_ty' );
				foreach ( $opt_ids as $opt_id ) {
					$opt_step = WFFN_Pro_Core()->steps->get_integration_object( 'optin_ty' );
					if ( $opt_step instanceof WFFN_Pro_Step ) {
						$opt_variants = $opt_step->maybe_get_ab_variants( $opt_id );
						if ( is_array( $opt_variants ) && count( $opt_variants ) > 0 ) {
							$data['variants'][ $opt_id ] = $opt_variants;
							foreach ( $opt_variants as $opt_variant ) {
								$data['steps'][ $opt_variant ] = 'optin_ty';
							}
						}
					}
				}

				$lp_ids = array_keys( $data['steps'], 'landing' );
				foreach ( $lp_ids as $lp_id ) {
					$lp_step = WFFN_Pro_Core()->steps->get_integration_object( 'landing' );
					if ( $lp_step instanceof WFFN_Pro_Step ) {
						$lp_variants = $lp_step->maybe_get_ab_variants( $lp_id );
						if ( is_array( $lp_variants ) && count( $lp_variants ) > 0 ) {
							$data['variants'][ $lp_id ] = $lp_variants;
							foreach ( $lp_variants as $lp_variant ) {
								$data['steps'][ $lp_variant ] = 'landing';
							}
						}
					}
				}

				$ch_ids = array_keys( $data['steps'], 'wc_checkout' );
				foreach ( $ch_ids as $ch_id ) {
					$ch_step = WFFN_Pro_Core()->steps->get_integration_object( 'wc_checkout' );
					if ( $ch_step instanceof WFFN_Pro_Step ) {
						$ch_variants = $ch_step->maybe_get_ab_variants( $ch_id );
						if ( is_array( $ch_variants ) && count( $ch_variants ) > 0 ) {
							$data['variants'][ $ch_id ] = $ch_variants;
							foreach ( $ch_variants as $ch_variant ) {
								$data['steps'][ $ch_variant ] = 'wc_checkout';
							}
						}
					}
				}

				$ty_ids = array_keys( $data['steps'], 'wc_thankyou' );
				foreach ( $ty_ids as $ty_id ) {
					$ty_step = WFFN_Pro_Core()->steps->get_integration_object( 'wc_thankyou' );
					if ( $ty_step instanceof WFFN_Pro_Step ) {
						$ty_variants = $ty_step->maybe_get_ab_variants( $ty_id );
						if ( is_array( $ty_variants ) && count( $ty_variants ) > 0 ) {
							$data['variants'][ $ty_id ] = $ty_variants;
							foreach ( $ty_variants as $ty_variant ) {
								$data['steps'][ $ty_variant ] = 'wc_thankyou';
							}
						}
					}
				}

				$ob_ids = array_keys( $data['steps'], 'wc_order_bump' );
				foreach ( $ob_ids as $ob_id ) {
					$ob_step = WFFN_Pro_Core()->substeps->get_integration_object( 'wc_order_bump' );
					if ( $ob_step instanceof WFFN_Pro_Substep ) {
						$ob_variants = $ob_step->maybe_get_ab_variants( $ob_id );
						if ( is_array( $ob_variants ) && count( $ob_variants ) > 0 ) {
							$data['variants'][ $ob_id ] = $ob_variants;
							foreach ( $ob_variants as $ob_variant ) {
								$data['steps'][ $ob_variant ] = 'wc_order_bump';
							}
						}
					}
				}

				if ( class_exists( 'WFOCU_Common' ) ) {
					$of_ids = array_keys( $data['steps'], 'offer' );
					foreach ( $of_ids as $of_id ) {
						$of_variants = $this->maybe_get_offer_ab_variants( $of_id );
						if ( is_array( $of_variants ) && count( $of_variants ) > 0 ) {
							$data['variants'][ $of_id ] = $of_variants;
							foreach ( $of_variants as $of_variant ) {
								$data['steps'][ $of_variant ] = 'offer';
							}
						}

					}
				}
			}

			return $data;

		}

		/**
		 * prepare analytics conversion data which steps data not store in report table
		 *
		 * @param $data
		 * @param $steps
		 *
		 * @return array
		 */
		public function get_analytics_conversion_data( $data, $steps ) {
			global $wpdb;
			$converted_data = [];
			/**
			 * get optin converted data
			 */
			$optin_ids = array_keys( $steps, 'optin' );
			if ( count( $optin_ids ) > 0 ) {
				$optin_ids = implode( ',', $optin_ids );

				$optin_sql        = "SELECT step_id as 'object_id', COUNT(id) as 'converted', 0 as 'revenue' FROM " . $wpdb->prefix . 'bwf_optin_entries' . "  WHERE step_id IN(" . $optin_ids . ") GROUP BY step_id";
				$get_optin_record = $wpdb->get_results( $optin_sql, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					WFFN_Core()->logger->log( "canvas optin analytics error : " . print_r( $wpdb->last_error, true ) . "  query " . print_r( $wpdb->last_query, true ), 'wffn-failed-query', true );
				} else {
					$converted_data = array_merge( $converted_data, $get_optin_record );
				}
			}

			/**
			 * get checkout converted and revenue data
			 */
			$checkout_ids = array_keys( $steps, 'wc_checkout' );
			if ( count( $checkout_ids ) > 0 ) {
				$checkout_ids = implode( ',', $checkout_ids );
				$aero_sql     = "SELECT wfacp_id as 'object_id', COUNT(ID) as 'converted', SUM(total_revenue) as 'revenue' FROM " . $wpdb->prefix . 'wfacp_stats' . " WHERE wfacp_id IN(" . $checkout_ids . ")  GROUP BY wfacp_id";

				$get_checkout_record = $wpdb->get_results( $aero_sql, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					WFFN_Core()->logger->log( "canvas checkout analytics error : " . print_r( $wpdb->last_error, true ) . "  query " . print_r( $wpdb->last_query, true ), 'wffn-failed-query', true );
				} else {
					$converted_data = array_merge( $converted_data, $get_checkout_record );
				}

			}

			/**
			 * get offer view, converted and revenue data
			 */
			$offer_ids = array_keys( $steps, 'offer' );
			if ( count( $offer_ids ) > 0 ) {
				$offer_ids = implode( ',', $offer_ids );

				$offer_sql        = "SELECT object_id, COUNT(CASE WHEN action_type_id = 4 THEN 1 END) AS `converted`, COUNT(CASE WHEN action_type_id = 2 THEN 1 END) AS `views`, SUM(value) as revenue FROM " . $wpdb->prefix . 'wfocu_event' . " WHERE object_id IN ( " . $offer_ids . " ) AND (action_type_id = '2' OR action_type_id = '4' ) GROUP BY object_id";
				$get_offer_record = $wpdb->get_results( $offer_sql, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					WFFN_Core()->logger->log( "canvas offer analytics error : " . print_r( $wpdb->last_error, true ) . "  query " . print_r( $wpdb->last_query, true ), 'wffn-failed-query', true );
				} else {
					$converted_data = array_merge( $converted_data, $get_offer_record );
				}

			}


			/**
			 * get bump view, converted and revenue data
			 */
			$bump_ids = array_keys( $steps, 'wc_order_bump' );
			if ( count( $bump_ids ) > 0 ) {
				$bump_ids = implode( ',', $bump_ids );

				$bump_sql = "SELECT bump.bid as 'object_id', COUNT(CASE WHEN converted = 1 THEN 1 END) AS `converted`, COUNT(bump.ID) as views, SUM(bump.total) as 'revenue' FROM " . $wpdb->prefix . 'wfob_stats' . " AS bump WHERE bump.bid IN ( " . $bump_ids . " ) GROUP BY bump.bid";

				$get_bump_record = $wpdb->get_results( $bump_sql, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					WFFN_Core()->logger->log( "canvas bump analytics error : " . print_r( $wpdb->last_error, true ) . "  query " . print_r( $wpdb->last_query, true ), 'wffn-failed-query', true );
				} else {
					$converted_data = array_merge( $converted_data, $get_bump_record );
				}

			}


			if ( count( $converted_data ) > 0 ) {
				foreach ( $converted_data as $item ) {
					if ( isset( $data[ $item['object_id'] ] ) ) {
						$data[ $item['object_id'] ]['views']           = empty( $item['views'] ) ? $data[ $item['object_id'] ]['views'] : intval( $item['views'] );
						$data[ $item['object_id'] ]['conversions']     = empty( $item['converted'] ) ? $data[ $item['object_id'] ]['conversions'] : intval( $item['converted'] );
						$data[ $item['object_id'] ]['revenue']         = empty( $item['revenue'] ) ? $data[ $item['object_id'] ]['revenue'] : floatval( number_format( $item['revenue'], 2, '.', '' ) );
						$data[ $item['object_id'] ]['conversion_rate'] = $this->get_percentage( $data[ $item['object_id'] ]['views'], $data[ $item['object_id'] ]['conversions'] );
					}
				}
			}

			return $data;
		}


		/**
		 * @param $control_id
		 *
		 * @return array
		 */
		public function maybe_get_offer_ab_variants( $control_id ) {
			$variants = [];
			$args     = array(
				'post_type'      => WFOCU_Common::get_offer_post_type_slug(),
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => '-1',
				'meta_query'     => array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => '_bwf_ab_variation_of',
						'compare' => '=',
						'value'   => $control_id
					)
				)
			);
			$q        = new WP_Query( $args );
			if ( $q->found_posts > 0 ) {
				foreach ( $q->posts as $variant ) {
					$variants[] = $variant->ID;
				}
			}

			return $variants;
		}

		/**
		 * @param $step_id
		 *
		 * @return array|false[]
		 */
		public function get_optin_stats( $step_id ) {
			global $wpdb;
			$ids            = [];
			$date_query     = '';
			$con_date_query = '';
			$view_type      = 8;

			$data = array(
				'views'           => 0,
				'conversions'     => 0,
				'conversion_rate' => 0,
				'revenue'         => 0
			);

			if ( 0 === intval( $step_id ) ) {
				return $data;
			}

			if ( ! class_exists( 'WFOPP_Core' ) || ! class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
				return $data;
			}

			if ( class_exists( 'WFFN_Pro_Core' ) ) {
				$get_step = WFFN_Pro_Core()->steps->get_integration_object( 'optin' );
				if ( $get_step instanceof WFFN_Pro_Step ) {
					$ids = $get_step->maybe_get_ab_variants( $step_id );
				}
			}

			$ids[] = $step_id;


			$step_ids = implode( ',', $ids );

			$optin_sql        = "SELECT COUNT(id) as cn FROM " . $wpdb->prefix . 'bwf_optin_entries' . " WHERE step_id IN(" . $step_ids . ") " . $con_date_query . " ORDER BY step_id ASC";
			$get_optin_record = $wpdb->get_row( $optin_sql, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}

			if ( is_array( $get_optin_record ) && count( $get_optin_record ) > 0 ) {
				$data['conversions'] = intval( $get_optin_record['cn'] );

			}

			$get_query = "SELECT SUM( CASE WHEN type = " . $view_type . " THEN `no_of_sessions` END ) AS viewed FROM " . $wpdb->prefix . 'wfco_report_views' . "  WHERE object_id IN(" . $step_ids . ") " . $date_query . " ORDER BY object_id ASC";
			$get_data  = $wpdb->get_row( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}

			if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
				$data['views']           = is_null( $get_data['viewed'] ) ? 0 : intval( $get_data['viewed'] );
				$data['conversion_rate'] = $this->get_percentage( $get_data['viewed'], $data['conversions'] );
			}

			return $data;
		}

		/**
		 * @param $step_id
		 *
		 * @return array|false[]
		 */
		public function get_optin_ty_stats( $step_id ) {
			global $wpdb;
			$ids          = [];
			$date_query   = '';
			$view_type    = 10;
			$convert_type = 11;

			$data = array(
				'views'           => 0,
				'conversions'     => 0,
				'conversion_rate' => 0,
				'revenue'         => 0
			);

			if ( ! class_exists( 'WFOPP_Core' ) || ! class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
				return $data;
			}

			if ( 0 === intval( $step_id ) ) {
				return $data;
			}

			if ( class_exists( 'WFFN_Pro_Core' ) ) {
				$get_step = WFFN_Pro_Core()->steps->get_integration_object( 'optin_ty' );
				if ( $get_step instanceof WFFN_Pro_Step ) {
					$ids = $get_step->maybe_get_ab_variants( $step_id );
				}
			}
			$ids[]    = $step_id;
			$step_ids = implode( ',', $ids );

			$get_query = "SELECT SUM(CASE WHEN type = " . $view_type . " THEN `no_of_sessions` END) AS `viewed` ,SUM(CASE WHEN type = " . $convert_type . " THEN `no_of_sessions` END) AS `converted` FROM  " . $wpdb->prefix . 'wfco_report_views' . "  WHERE object_id IN(" . $step_ids . ") " . $date_query . " ORDER BY object_id ASC";
			$get_data  = $wpdb->get_row( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}

			if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
				$data['views']           = is_null( $get_data['viewed'] ) ? 0 : intval( $get_data['viewed'] );
				$data['conversions']     = is_null( $get_data['converted'] ) ? 0 : intval( $get_data['converted'] );
				$data['conversion_rate'] = $this->get_percentage( $get_data['viewed'], $get_data['converted'] );
			}

			return $data;
		}

		/**
		 * @param $step_id
		 *
		 * @return array|false[]
		 */
		public function get_landing_stats( $step_id ) {

			global $wpdb;
			$ids          = [];
			$date_query   = '';
			$view_type    = 2;
			$convert_type = 3;

			$data = array(
				'views'           => 0,
				'conversions'     => 0,
				'conversion_rate' => 0,
				'revenue'         => 0,
			);

			if ( 0 === intval( $step_id ) ) {
				return $data;
			}

			if ( class_exists( 'WFFN_Pro_Core' ) ) {
				$get_step = WFFN_Pro_Core()->steps->get_integration_object( 'landing' );
				if ( $get_step instanceof WFFN_Pro_Step ) {
					$ids = $get_step->maybe_get_ab_variants( $step_id );
				}
			}

			$ids[] = $step_id;


			$step_ids = implode( ',', $ids );

			$get_query = "SELECT SUM(CASE WHEN type = " . $view_type . " THEN `no_of_sessions` END) AS `viewed` ,SUM(CASE WHEN type = " . $convert_type . " THEN `no_of_sessions` END) AS `converted` FROM  " . $wpdb->prefix . 'wfco_report_views' . "  WHERE object_id IN(" . $step_ids . ") " . $date_query . " ORDER BY object_id ASC";

			$get_data = $wpdb->get_row( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}

			if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
				$data['views']           = is_null( $get_data['viewed'] ) ? 0 : intval( $get_data['viewed'] );
				$data['conversions']     = is_null( $get_data['converted'] ) ? 0 : intval( $get_data['converted'] );
				$data['conversion_rate'] = $this->get_percentage( $get_data['viewed'], $get_data['converted'] );
			}

			return $data;
		}

		/**
		 * @param $step_id
		 *
		 * @return array|false[]
		 */
		public function get_checkout_stats( $step_id ) {
			global $wpdb;

			$data = array(
				'views'           => 0,
				'conversions'     => 0,
				'conversion_rate' => 0,
				'revenue'         => 0,
			);

			$date_query     = '';
			$con_date_query = '';
			$view_type      = 4;

			if ( 0 === intval( $step_id ) ) {
				return $data;
			}

			if ( ! class_exists( 'WFACP_Contacts_Analytics' ) || version_compare( WFACP_VERSION, '2.0.7', '<' ) || ( class_exists( 'WFOB_Core' ) && version_compare( WFOB_VERSION, '1.8,1', '<=' ) ) ) {
				return $data;
			}

			if ( class_exists( 'WFFN_Pro_Core' ) ) {
				$get_step = WFFN_Pro_Core()->steps->get_integration_object( 'wc_checkout' );
				if ( $get_step instanceof WFFN_Pro_Step ) {
					$ids = $get_step->maybe_get_ab_variants( $step_id );
				}
			}
			$ids[] = $step_id;


			$step_ids = implode( ',', $ids );


			$aero_sql = "SELECT SUM(total_revenue) as 'total_revenue',COUNT(ID) as cn FROM " . $wpdb->prefix . 'wfacp_stats' . " WHERE wfacp_id IN(" . $step_ids . ") " . $con_date_query . "  ORDER BY wfacp_id ASC";

			$get_all_checkout_records = $wpdb->get_row( $aero_sql, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}

			if ( is_array( $get_all_checkout_records ) && count( $get_all_checkout_records ) > 0 ) {

				$data['revenue']     = is_null( $get_all_checkout_records['total_revenue'] ) ? 0 : floatval( number_format( $get_all_checkout_records['total_revenue'], 2, '.', '' ) );
				$data['conversions'] = intval( $get_all_checkout_records['cn'] );

			}

			$get_query = "SELECT object_id, SUM( CASE WHEN type = " . $view_type . " THEN `no_of_sessions` END ) AS viewed FROM " . $wpdb->prefix . 'wfco_report_views' . "  WHERE object_id IN(" . $step_ids . ") " . $date_query . " ORDER BY object_id ASC";
			$get_data  = $wpdb->get_row( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}

			if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
				$data['views']           = is_null( $get_data['viewed'] ) ? 0 : intval( $get_data['viewed'] );
				$data['conversion_rate'] = $this->get_percentage( $get_data['viewed'], $data['conversions'] );
			}

			return $data;
		}

		/**
		 * @param $step_id
		 *
		 * @return array|false[]
		 */
		public function get_offer_stats( $step_id ) {
			global $wpdb;

			$data = array(
				'views'           => 0,
				'conversions'     => 0,
				'conversion_rate' => 0,
				'revenue'         => 0,
			);

			if ( 0 === intval( $step_id ) ) {
				return $data;
			}

			if ( ! class_exists( 'WFOCU_Core' ) || ! class_exists( 'WFOCU_Contacts_Analytics' ) || ! version_compare( WFOCU_VERSION, '2.2.0', '>=' ) ) {
				return $data;
			}

			$get_query = "SELECT COUNT(CASE WHEN action_type_id = 4 THEN 1 END) AS `converted`, COUNT(CASE WHEN action_type_id = 2 THEN 1 END) AS `viewed`, object_id  as 'offer', action_type_id, SUM(value) as revenue FROM " . $wpdb->prefix . 'wfocu_event' . " WHERE object_id = " . $step_id . " AND (action_type_id = '2' OR action_type_id = '4' ) GROUP BY object_id";
			$get_data  = $wpdb->get_row( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}

			if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
				$data['views']           = is_null( $get_data['viewed'] ) ? 0 : intval( $get_data['viewed'] );
				$data['conversions']     = is_null( $get_data['converted'] ) ? 0 : intval( $get_data['converted'] );
				$data['revenue']         = isset( $get_data['revenue'] ) && ! is_null( $get_data['revenue'] ) ? floatval( number_format( $get_data['revenue'], 2, '.', '' ) ) : 0;
				$data['conversion_rate'] = $this->get_percentage( $get_data['viewed'], $get_data['converted'] );

			}

			return $data;
		}

		/**
		 * @param $step_id
		 *
		 * @return array|false[]
		 */
		public function get_ty_stats( $step_id ) {
			global $wpdb;

			$data = array(
				'views'       => 0,
				'revenue'     => 0,
				'conversions' => 0
			);

			$date_query = '';
			$view_type  = 5;

			if ( 0 === intval( $step_id ) ) {
				return $data;
			}

			if ( class_exists( 'WFFN_Pro_Core' ) ) {
				$get_step = WFFN_Pro_Core()->steps->get_integration_object( 'wc_thankyou' );
				if ( $get_step instanceof WFFN_Pro_Step ) {
					$ids = $get_step->maybe_get_ab_variants( $step_id );
				}
			}
			$ids[] = $step_id;


			$step_ids = implode( ',', $ids );

			$get_query = "SELECT SUM( CASE WHEN type = " . $view_type . " THEN `no_of_sessions` END ) AS viewed FROM " . $wpdb->prefix . 'wfco_report_views' . "  WHERE object_id IN(" . $step_ids . ") " . $date_query . "  ORDER BY object_id ASC";
			$get_data  = $wpdb->get_row( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}

			if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
				$data['views'] = is_null( $get_data['viewed'] ) ? 0 : intval( $get_data['viewed'] );
			}

			return $data;
		}

		/**
		 * @param $step_id
		 *
		 * @return array|false[]|int[]
		 */
		public function get_bump_stats( $step_id ) {

			global $wpdb;

			$data = array(
				'views'           => 0,
				'conversions'     => 0,
				'conversion_rate' => 0,
				'revenue'         => 0,
			);

			if ( 0 === intval( $step_id ) ) {
				return $data;
			}

			if ( ! class_exists( 'WFOB_Core' ) || version_compare( WFOB_VERSION, '1.8,1', '<=' ) ) {
				return $data;
			}
			$get_query = "SELECT COUNT(CASE WHEN converted = 1 THEN 1 END) AS `converted`, COUNT(bump.ID) as viewed, SUM(bump.total) as 'revenue' FROM " . $wpdb->prefix . 'wfob_stats' . " AS bump WHERE bump.bid = " . $step_id . " ORDER BY bump.bid ASC";

			$get_data = $wpdb->get_row( $get_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			if ( method_exists( 'WFFN_Common', 'maybe_wpdb_error' ) ) {
				$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}

			if ( is_array( $get_data ) && count( $get_data ) > 0 ) {
				$data['views']           = is_null( $get_data['viewed'] ) ? 0 : intval( $get_data['viewed'] );
				$data['conversions']     = is_null( $get_data['converted'] ) ? 0 : intval( $get_data['converted'] );
				$data['revenue']         = isset( $get_data['revenue'] ) && ! is_null( $get_data['revenue'] ) ? floatval( number_format( $get_data['revenue'], 2, '.', '' ) ) : 0;
				$data['conversion_rate'] = $this->get_percentage( $get_data['viewed'], $get_data['converted'] );

			}

			return $data;
		}

		/**
		 * Prepare canvas data from list step
		 *
		 * @param $step
		 *
		 * @return array
		 */
		public function map_list_step( $step ) {


			if ( is_object( $step ) ) {
				$step = ( array ) $step;
			}

			if ( ! empty( $step['_data'] ) && is_object( $step['_data'] ) ) {
				$step['_data'] = ( array ) $step['_data'];
			}

			$view_link = ! empty( $step['_data']['view'] ) ? $step['_data']['view'] : '';

			if ( 'wc_order_bump' === $step['type'] && isset( $step['checkout_id'] ) ) {
				if ( 0 === absint( $step['checkout_id'] ) && function_exists( 'wc_get_checkout_url' ) ) {
					$view_link = wc_get_checkout_url();
				} else {
					$ch_post = get_post( $step['checkout_id'] );
					if ( $ch_post instanceof WP_Post ) {
						$view_link = $this->get_base_url( $ch_post );;
					}
				}
			}

			return array(
				'type'  => ! empty( $step['type'] ) ? $step['type'] : '',
				'id'    => ! empty( $step['id'] ) ? $step['id'] : 0,
				'title' => ! empty( $step['_data']['title'] ) ? $step['_data']['title'] : '',
				'tags'  => ! empty( $step['tags'] ) ? $step['tags'] : array(),
				'links' => array(
					[
						'title' => __( 'Edit', 'funnel-builder' ),
						'type'  => 'edit_link',
						'link'  => ! empty( $step['_data']['edit'] ) ? $step['_data']['edit'] : ''
					],
					[
						'title'  => __( 'Preview', 'funnel-builder' ),
						'type'   => 'preview',
						'target' => '_blank',
						'link'   => $view_link
					],
				),
				'stats' => array(
					'views'           => 0,
					'conversions'     => 0,
					'conversion_rate' => 0,
					'revenue'         => 0
				),
				'_data' => ! empty( $step['_data'] ) ? $step['_data'] : array(),
			);
		}

		public function maybe_canvas_substeps_mode( $canvas_data, $new_step, $funnel ) {
			$step_data = [];
			if ( ! empty( $canvas_data ) ) {
				$funnel->maybe_update_canvas( $new_step['id'], $canvas_data );
				$prepare_data                                  = [];
				$prepare_data['steps_list']                    = [];
				$prepare_data['groups']                        = [];
				$prepare_data['steps_list'][ $new_step['id'] ] = WFFN_REST_Funnel_Canvas::get_instance()->map_list_step( $new_step );
				$prepare_data['groups'][0]                     = [ 'type' => $new_step['type'], 'id' => $new_step['id'], 'substeps' => [] ];
				$prepare_data['steps_list']                    = wffn_rest_api_helpers()->add_step_edit_details( $prepare_data['steps_list'] );
				$prepare_data['steps_list']                    = apply_filters( 'wffn_rest_get_funnel_steps', $prepare_data['steps_list'], false );

				return $prepare_data;
			}

			return $step_data;
		}

	}


	return WFFN_REST_Funnel_Canvas::get_instance();


}
