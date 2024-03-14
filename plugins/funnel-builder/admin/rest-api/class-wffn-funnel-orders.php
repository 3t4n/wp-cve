<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Funnel contact class
 * Class WFFN_Funnel_Contacts
 */
if ( ! class_exists( 'WFFN_Funnel_Orders' ) ) {
	class WFFN_Funnel_Orders {
		private static $ins = null;
		protected $namespace = 'funnelkit-app';
		protected $advanced_filters = false;
		private $search_filters = [];
		private $args = [];

		/**
		 * WFFN_Funnel_Contacts constructor.
		 */
		public function __construct() {
			add_action( 'rest_api_init', [ $this, 'register_contact_data_endpoint' ], 11 );
		}

		/**
		 * @return WFFN_Funnel_Orders
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function register_contact_data_endpoint() {
			$this->order_end_points();
			$this->leads_end_points();
		}

		private function order_end_points() {


			register_rest_route( $this->namespace, '/funnel-orders/', array(
				'args'                => [],
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_orders' ),
				'permission_callback' => array( $this, 'get_read_api_permission_check' ),
			) );
			register_rest_route( $this->namespace, '/funnel-orders/(?P<funnel_id>[\d]+)', array(
				'args'                => [],
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_single_funnel_order' ),
				'permission_callback' => array( $this, 'get_read_api_permission_check' ),
			) );
			register_rest_route( $this->namespace, '/funnel-order-info/(?P<id>[\d]+)/', array(
				'args'                => [
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					)
				],
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_single_order_info' ),
				'permission_callback' => array( $this, 'get_read_api_permission_check' ),
			) );
		}

		private function leads_end_points() {
			register_rest_route( $this->namespace, '/funnel-leads/', array(
				'args'                => [],
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_leads' ),
				'permission_callback' => array( $this, 'get_read_api_permission_check' ),
			) );
			register_rest_route( $this->namespace, '/funnel-leads/(?P<funnel_id>[\d]+)', array(
				'args'                => [],
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_single_funnel_leads' ),
				'permission_callback' => array( $this, 'get_read_api_permission_check' ),
			) );
			register_rest_route( $this->namespace, '/funnel-leads/delete', array(
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'delete_lead_entry' ),
				'permission_callback' => array( $this, 'get_write_api_permission_check' ),
				'args'                => array(
					'source_id' => array(
						'description'       => __( 'Delete funnels', 'funnel-builder' ),
						'type'              => 'string',
						'validate_callback' => 'rest_validate_request_arg',
					),
				),
			) );
		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		public function prepare_filters( $filters ) {
			if ( ! is_array( $filters ) ) {
				$filters = json_decode( $filters, true );
			}

			$single_data = [];

			if ( ! is_array( $filters ) || count( $filters ) === 0 ) {
				return $single_data;
			}

			foreach ( $filters as $filter ) {
				if ( in_array( $filter['filter'], [ 'wc_order_bump', 'offer' ], true ) ) {
					$this->advanced_filters = true;
				}
				$single_data[ $filter['filter'] ] = $filter;
				if ( is_array( $filter['data'] ) ) {
					$ids = array_column( $filter['data'], 'id' );
					if ( ! empty( $ids ) ) {
						$single_data[ $filter['filter'] ]['data'] = implode( ',', $ids );
					}
				}
			}
			$this->search_filters = $single_data;

			return $single_data;
		}

		public function filters_list( $args, $optin = false ) {
			$filters = array(
				array(
					'type'  => 'sticky',
					'rules' => array(
						array(
							'slug'          => 'period',
							'title'         => __( 'Date Created', 'funnel-builder' ),
							'type'          => 'date-range',
							'op_label'      => __( 'Time Period', 'funnel-builder' ),
							'required'      => array( 'rule', 'data' ),
							'readable_text' => '{{value /}}',
						),
					),
				),
				array(
					'type'  => 'list',
					'rules' => array(

						array(
							'slug'            => 'utm_source',
							'title'           => __( 'UTM Source', 'funnel-builder' ),
							'type'            => 'search',
							'op_label'        => __( 'UTM Source', 'funnel-builder' ),
							'required'        => array( 'data' ),
							'api'             => '/funnel-utms/?utm_key=utm_source&s={{search}}',
							'readable_text'   => '{{rule /}} - {{value /}}',
							'default_options' => ( class_exists( 'WFFN_Conversion_Data' ) ) ? WFFN_Conversion_Data::get_instance()->get_utm_data( [
								'utm_key'   => 'utm_source',
								'funnel_id' => $args['funnel_id'] ?? 0,
								'limit'     => 5
							], true ) : '',
							'is_pro'          => true,
						),
						array(
							'slug'            => 'utm_medium',
							'title'           => __( 'UTM Medium', 'funnel-builder' ),
							'type'            => 'search',
							'op_label'        => __( 'UTM Medium', 'funnel-builder' ),
							'required'        => array( 'data' ),
							'api'             => '/funnel-utms/?utm_key=utm_medium&s={{search}}',
							'readable_text'   => '{{rule /}} - {{value /}}',
							'default_options' => ( class_exists( 'WFFN_Conversion_Data' ) ) ? WFFN_Conversion_Data::get_instance()->get_utm_data( [
								'utm_key'   => 'utm_medium',
								'funnel_id' => $args['funnel_id'] ?? 0,
								'limit'     => 5
							], true ) : '',
							'is_pro'          => true,
						),


					),
				),
			);

			if ( false === $optin ) {
				$revenue_filter      = array(
					array(
						'slug'          => 'wc_order_bump',
						'title'         => __( 'Order Bump', 'funnel-builder' ),
						'type'          => 'search',
						'operators'     => array(
							'bump_accepted' => __( 'Is Accepted', 'funnel-builder' ),
							'bump_rejected' => __( 'Is Rejected', 'funnel-builder' ),
						),
						'api'           => '/funnels/step/search/?s={{search}}&type=wc_order_bump&is_substep=true',
						'op_label'      => 'Status',
						'required'      => array( 'rule', 'data' ),
						'readable_text' => '{{rule /}} - {{value /}}',
						'multiple'      => true,
						'is_pro'        => true,
					),
					array(
						'slug'          => 'offer',
						'title'         => __( 'Upsell Offer', 'funnel-builder' ),
						'type'          => 'search',
						'operators'     => array(
							'offer_accepted' => __( 'Is Accepted', 'funnel-builder' ),
							'offer_rejected' => __( 'Is Rejected', 'funnel-builder' ),
						),
						'api'           => '/funnels/step/search/?s={{search}}&type=offer&is_substep=true',
						'op_label'      => __( 'Status', 'funnel-builder' ),
						'required'      => array( 'rule', 'data' ),
						'readable_text' => '{{rule /}} - {{value /}}',
						'multiple'      => true,
						'is_pro'        => true,
					),
					array(
						'slug'          => 'total_order_value',
						'title'         => __( 'Total Spend', 'funnel-builder' ),
						'type'          => 'number',
						'operators'     => array(
							'eq' => __( 'is equal to', 'funnel-builder' ),
							'gt' => __( 'is greater than', 'funnel-builder' ),
							'lt' => __( 'is less than', 'funnel-builder' ),
							'ge' => __( 'is greater or equal to', 'funnel-builder' ),
							'le' => __( 'is less or equal to', 'funnel-builder' ),
						),
						'op_label'      => __( 'Order Value', 'funnel-builder' ),
						'required'      => array( 'rule', 'data' ),
						'readable_text' => '{{rule /}} - {{value /}}',
					),
				);
				$filters[0]['rules'] = array_merge( $filters[0]['rules'], $revenue_filter );
			}

			if ( ! isset( $args['funnel_id'] ) || intval( $args['funnel_id'] ) === 0 ) {
				$filters[0]['rules'][] = array(
					'slug'          => 'funnels',
					'title'         => __( 'Funnel', 'funnel-builder' ),
					'type'          => 'search',
					'api'           => '/funnels/?s={{search}}&search_filter',
					'op_label'      => __( 'Funnel', 'funnel-builder' ),
					'required'      => array( 'data' ),
					'readable_text' => '{{rule /}} - {{value /}}',
				);
			}

			$filters[0]['rules'][] = array(
				'slug'            => 'utm_campaign',
				'title'           => __( 'UTM Campaign', 'funnel-builder' ),
				'type'            => 'search',
				'op_label'        => __( 'UTM Campaign', 'funnel-builder' ),
				'required'        => array( 'data' ),
				'api'             => '/funnel-utms/?utm_key=utm_campaign&s={{search}}',
				'readable_text'   => '{{rule /}} - {{value /}}',
				'default_options' => ( class_exists( 'WFFN_Conversion_Data' ) ) ? WFFN_Conversion_Data::get_instance()->get_utm_data( [
					'utm_key'   => 'utm_campaign',
					'funnel_id' => $args['funnel_id'] ?? 0,
					'limit'     => 5
				], true ) : '',
				'is_pro'          => true,
			);


			$filters[0]['rules'][] = array(
				'slug'          => 'utm_referrer',
				'title'         => __( 'Referrer', 'funnel-builder' ),
				"type"          => "checklist",
				'multiple'      => true,
				'options'       => WFFN_Common::get_refs( true, true ),
				'op_label'      => __( 'Referrer', 'funnel-builder' ),
				'required'      => array( 'data' ),
				'readable_text' => '{{rule /}} - {{value /}}',
				'is_pro'        => true,
			);


			return $filters;
		}

		public function get_single_funnel_order( $request ) {
			return $this->get_orders( $request );
		}


		public function get_single_order_info( $request ) {
			$response = [ 'status' => false, 'message' => __( 'Invalid order' ), 'records' => 0 ];
			$order_id = $request['id'] ?? 0;
			if ( 0 === $order_id ) {
				return rest_ensure_response( $response );
			}
			$order = wc_get_order( $order_id );
			if ( ! $order instanceof WC_Order ) {
				return rest_ensure_response( $response );
			}


			$items  = $order->get_items();
			$output = [
				'checkout' => [],
				'bump'     => [],
				'upsell'   => [],
			];

			$subtotal = 0;
			$currency = get_woocommerce_currency();
			foreach ( $items as $item ) {

				$key = 'checkout';
				if ( 'yes' === $item->get_meta( '_upstroke_purchase' ) ) {
					$key = 'upsell';
				} elseif ( 'yes' === $item->get_meta( '_bump_purchase' ) ) {
					$key = 'bump';
				}
				$sub_total        = $item->get_subtotal();
				$output[ $key ][] = [
					'title'          => $item->get_name(),
					'subtotal_html'  => wc_price( $sub_total, [ 'currency' => $currency ] ),
					'subtotal_total' => $sub_total,
				];

				$subtotal += $sub_total;
			}

			if ( ! empty( $output ) ) {

				$order_total        = $order->get_total();
				$total_discount     = $order->get_total_discount();
				$response['status'] = true;
				$response['items']  = $output;
				$remaining_amount   = $order_total - ( $subtotal - $total_discount );
				if ( $remaining_amount > 0 ) {
					$response['other_cost_html'] = sprintf( __( 'including shipping and taxes ,other costs: %s', 'funnel-builder' ), wc_price( $remaining_amount, [ 'currency' => $currency ] ) );
					$response['other_cost']      = $remaining_amount;
				}
				if ( $order->get_total_discount() > 0 ) {
					$response['order_discount']      = $order->get_total_discount();
					$response['order_discount_html'] = sprintf( __( "Discount : <span>-</sapn> %s", 'funnel-builder' ), wc_price( $order->get_total_discount(), [ 'currency' => $currency ] ) );
				}
				$response['order_total']      = $order_total;
				$response['order_total_html'] = wc_price( $order_total, [ 'currency' => $currency ] );
			}

			return rest_ensure_response( $response );
		}

		public function get_conversion_orders_from_aero( $args = [], $limit = '' ) {
			global $wpdb;
			$filters     = $this->search_filters;
			$where_query = [ '1=1' ];

			if ( isset( $filters['funnels'] ) && isset( $filters['funnels']['data'] ) && ! empty( $filters['funnels']['data'] ) ) {
				$where_query[] = "(aero_stats.fid IN ({$filters['funnels']['data']}))";

			} elseif ( $args['funnel_id'] > 0 ) {
				$where_query[] = "(aero_stats.fid= '{$args['funnel_id']}')";
			} else {
				$where_query[] = "(aero_stats.fid!= '0')";
			}

			if ( isset( $filters['period'] ) ) {
				$where_query[] = "(aero_stats.date BETWEEN '" . $filters['period']['data']['after'] . "' AND '" . $filters['period']['data']['before'] . "')";
			}

			if ( isset( $this->search_filters['total_order_value'] ) ) {
				$amount        = floatval( $this->search_filters['total_order_value']['data'] );
				$operator      = WFFN_Common::get_compare_operator_amount( $this->search_filters['total_order_value']['rule'] );
				$where_query[] = "(orders.total_sales {$operator} '{$amount}')";

			}

			$search_filter = ! empty( $this->args['s'] ) ? $this->args['s'] : '';

			if ( isset( $this->search_filters['s'] ) && isset( $this->search_filters['s']['data'] ) && ! empty( $this->search_filters['s']['data'] ) ) {
				$search_filter = $this->search_filters['s']['data'];

			}

			if ( ! empty( $search_filter ) ) {
				$where_query[] = "(CONCAT(cust.f_name,' ',cust.l_name) like '%$search_filter%' OR cust.email like '%$search_filter%' OR aero_stats.order_id like '%$search_filter%')";
			}

			$conv_filter = apply_filters( 'wffn_filter_data_conversion_query', [], 'order', $where_query, $filters );

			$conv_join   = '';
			$case_string = '';

			if ( is_array( $conv_filter ) && count( $conv_filter ) > 0 ) {
				$conv_join   = isset( $conv_filter['join'] ) ? $conv_filter['join'] : '';
				$case_string = isset( $conv_filter['case_string'] ) ? $conv_filter['case_string'] : '';
				$where_query = isset( $conv_filter['where_query'] ) ? $conv_filter['where_query'] : $where_query;
			}

			$where_query = implode( ' AND ', $where_query );
			$output_data = [];


			if ( $args['total_count'] ) {
				$count_query                = "SELECT count(aero_stats.ID) as total_count FROM  `{$wpdb->prefix}wfacp_stats` as aero_stats JOIN `{$wpdb->prefix}wc_order_stats` as orders ON aero_stats.order_id = orders.order_id JOIN {$wpdb->prefix}bwf_contact as cust ON cust.id = aero_stats.cid " . $conv_join . " WHERE {$where_query}";
				$count_results              = $wpdb->get_results( $count_query, ARRAY_A );
				$output_data['total_count'] = $count_results[0]['total_count'] ?? 0;
			}


			$order_stats_query = "SELECT aero_stats.order_id,aero_stats.wfacp_id,aero_stats.fid,aero_stats.cid,cust.f_name,cust.l_name, cust.email, cust.contact_no as phone,orders.total_sales, aero_stats.date " . $case_string . " FROM  `{$wpdb->prefix}wfacp_stats` as aero_stats JOIN `{$wpdb->prefix}wc_order_stats` as orders ON aero_stats.order_id = orders.order_id JOIN {$wpdb->prefix}bwf_contact as cust ON cust.id = aero_stats.cid " . $conv_join . " WHERE {$where_query} ORDER BY aero_stats.date DESC {$limit}";
			$results           = $wpdb->get_results( $order_stats_query, ARRAY_A );

			$output_data['data'] = $this->add_funnel_title( $results );

			return $output_data;

		}

		public function get_conversion_orders( $args = [], $limit = '', $return_data = false ) {
			global $wpdb;
			$filters     = $this->search_filters;
			$where_query = [ 'tracking.type=2' ];

			if ( isset( $filters['funnels'] ) && isset( $filters['funnels']['data'] ) && ! empty( $filters['funnels']['data'] ) ) {
				$where_query[] = "(tracking.funnel_id IN ({$filters['funnels']['data']}))";

			} elseif ( $args['funnel_id'] > 0 ) {
				$where_query[] = "(tracking.funnel_id= '{$args['funnel_id']}')";
			} else {
				$where_query[] = "(tracking.funnel_id!= 0)";
			}

			if ( isset( $filters['period'] ) ) {
				$where_query[] = "(tracking.timestamp BETWEEN '" . $filters['period']['data']['after'] . "' AND '" . $filters['period']['data']['before'] . "')";
			}

			/***
			 * return bump and upsell for funnel filter
			 */
			$case_string = '';
			if ( true === $return_data ) {
				/**
				 * add bump and upsell rows only for export process
				 */
				$case_string = $case_string . ", tracking.step_id, tracking.checkout_total, tracking.bump_total, tracking.bump_accepted, tracking.bump_rejected, tracking.offer_total, tracking.offer_accepted, tracking.offer_rejected ";
			}


			$where_query = $this->maybe_bump_filter( $where_query );
			$where_query = $this->maybe_upsell_filter( $where_query );

			if ( isset( $this->search_filters['total_order_value'] ) ) {
				$amount        = floatval( $this->search_filters['total_order_value']['data'] );
				$operator      = WFFN_Common::get_compare_operator_amount( $this->search_filters['total_order_value']['rule'] );
				$where_query[] = "(tracking.value {$operator} {$amount})";

			}

			$search_filter = ! empty( $this->args['s'] ) ? $this->args['s'] : '';

			if ( isset( $this->search_filters['s'] ) && isset( $this->search_filters['s']['data'] ) && ! empty( $this->search_filters['s']['data'] ) ) {
				$search_filter = $this->search_filters['s']['data'];

			}

			if ( ! empty( $search_filter ) ) {
				$where_query[] = $wpdb->prepare( " (CONCAT(cust.f_name,' ',cust.l_name) like %s OR cust.email like %s OR tracking.source like %s)", "%" . $search_filter . "%", "%" . $search_filter . "%", "%" . $search_filter . "%" );
			}

			$conv_filter = apply_filters( 'wffn_filter_data_conversion_query', [], 'order', $where_query, $filters );

			$conv_join = '';

			if ( is_array( $conv_filter ) && count( $conv_filter ) > 0 ) {
				$case_string .= isset( $conv_filter['case_string'] ) ? $conv_filter['case_string'] : '';
				$where_query = isset( $conv_filter['where_query'] ) ? $conv_filter['where_query'] : $where_query;
			}

			$where_query = implode( ' AND ', $where_query );
			$output_data = [];


			if ( $args['total_count'] ) {
				$count_query = "SELECT count(tracking.id) as total_count FROM `{$wpdb->prefix}bwf_conversion_tracking` as tracking JOIN {$wpdb->prefix}bwf_contact as cust ON cust.id = tracking.contact_id " . $conv_join . " WHERE {$where_query}";

				$count_results              = $wpdb->get_results( $count_query, ARRAY_A );
				$output_data['total_count'] = $count_results[0]['total_count'] ?? 0;
			}


			$order_stats_query = "SELECT (CASE WHEN TIMESTAMPDIFF( SECOND, tracking.first_click, tracking.timestamp ) != 0 THEN TIMESTAMPDIFF( SECOND, tracking.first_click, tracking.timestamp ) ELSE 0 END ) as 'convert_time', tracking.source as 'order_id', tracking.step_id as 'wfacp_id', tracking.funnel_id as 'fid', tracking.contact_id as 'cid', cust.f_name as 'f_name', cust.l_name as 'l_name', cust.email as 'email', cust.contact_no as 'phone', tracking.value as 'total_sales', tracking.timestamp as 'date' " . $case_string . " FROM  `{$wpdb->prefix}bwf_conversion_tracking` as tracking JOIN {$wpdb->prefix}bwf_contact as cust ON cust.id = tracking.contact_id " . $conv_join . " WHERE {$where_query} ORDER BY tracking.timestamp DESC {$limit}";
			$results           = $wpdb->get_results( $order_stats_query, ARRAY_A );

			$output_data['data'] = $this->add_funnel_title( $results );

			return $output_data;

		}

		public function maybe_upsell_filter( $where_query ) {
			$filters = $this->search_filters;
			if ( ! isset( $filters['offer'] ) && ! isset( $filters['funnels'] ) ) {
				return $where_query;
			}
			if ( isset( $filters['offer'] ) && isset( $filters['offer']['rule'] ) && isset( $filters['offer']['data'] ) && ! empty( $filters['offer']['data'] ) ) {
				$offer_data = '"' . $filters['offer']['data'] . '"';
				if ( 'offer_accepted' === $filters['offer']['rule'] ) {
					$where_query[] = "( tracking.offer_accepted LIKE '%{$offer_data}%' )";
				} else if ( 'offer_rejected' === $filters['offer']['rule'] ) {
					$where_query[] = "( tracking.offer_rejected LIKE '%{$offer_data}%' )";
				}
			}

			return $where_query;
		}

		public function maybe_bump_filter( $where_query ) {
			$filters = $this->search_filters;

			if ( ! isset( $filters['wc_order_bump'] ) && ! isset( $filters['funnels'] ) ) {
				return $where_query;
			}

			if ( empty( $filters['wc_order_bump']['data'] ) && empty( $filters['funnels']['data'] ) ) {
				return $where_query;
			}

			if ( isset( $filters['wc_order_bump'] ) && isset( $filters['wc_order_bump']['rule'] ) && isset( $filters['wc_order_bump']['data'] ) && ! empty( $filters['wc_order_bump']['data'] ) ) {
				$bump_data = '"' . $filters['wc_order_bump']['data'] . '"';
				if ( 'bump_accepted' === $filters['wc_order_bump']['rule'] ) {
					$where_query[] = "( tracking.bump_accepted LIKE '%{$bump_data}%' )";
				} else if ( 'bump_rejected' === $filters['wc_order_bump']['rule'] ) {
					$where_query[] = "( tracking.bump_rejected LIKE '%{$bump_data}%' )";
				}
			}

			return $where_query;
		}

		public function add_funnel_title( $args ) {
			global $wpdb;
			$sql         = "select id as 'fid', title from {$wpdb->prefix}bwf_funnels WHERE 1 = 1";
			$results     = $wpdb->get_results( $sql, ARRAY_A );
			$funnel_data = [];
			if ( is_array( $results ) && count( $results ) > 0 ) {
				foreach ( $results as $item ) {
					$funnel_data[ $item['fid'] ] = $item['title'];
				}
			}
			$args = array_map( function ( $item ) use ( $funnel_data ) {
				$fid = absint( $item['fid'] );
				if ( $fid > 0 && isset( $funnel_data[ $fid ] ) ) {
					$item['funnel_title'] = $funnel_data[ $fid ];
				} else {
					$item['funnel_title'] = '-';
				}

				return $item;
			}, $args );

			return $args;
		}

		public function get_orders( $request, $return_data = false ) {
			$response = [];

			$args = array(
				'funnel_id'   => $request['funnel_id'] ?? 0,
				's'           => $request['s'] ?? '',
				'limit'       => $request['limit'] ?? get_option( 'posts_per_page' ),
				'page_no'     => $request['page_no'] ?? 1,
				'total_count' => $request['total_count'] ?? false,
				'only_count'  => $request['only_count'] ?? false,
				'offset'      => isset( $request['offset'] ) ? $request['offset'] : '',
			);

			$this->args = $args;
			$limit      = $args['limit'];
			$page_no    = $args['page_no'];
			$offset     = ! empty( $args['offset'] ) ? $args['offset'] : ( intval( $limit ) * intval( $page_no - 1 ) );

			$response['filters_list'] = $this->filters_list( $args );
			$limit_str                = " LIMIT $offset, $limit";
			if ( isset( $request['filters'] ) ) {

				$this->prepare_filters( $request['filters'] );
			}

			if ( ! empty( $args['s'] ) ) {
				$limit_str = '';
			}

			if ( ! in_array( absint( wffn_conversion_tracking_migrator()->get_upgrade_state() ), [ 3, 4 ], true ) ) {
				$conversions_orders = $this->get_conversion_orders_from_aero( $args, $limit_str );
			}else {
				$conversions_orders = $this->get_conversion_orders( $args, $limit_str, $return_data );
			}

			$final_orders       = $conversions_orders['data'];
			if ( isset( $conversions_orders['total_count'] ) ) {
				$response['total_count'] = $conversions_orders['total_count'];
			}

			if ( isset( $request['only_count'] ) && false !== $args['only_count'] ) {
				return rest_ensure_response( $response );
			}

			$prepared_data = $this->prepare_order_data( $final_orders, $return_data );

			$response['status']                      = true;
			$response['records']                     = $prepared_data;
			$response['conversion_migration_status'] = WFFN_Core()->admin_notifications->is_conversion_migration_required();

			if ( isset( $response['total_count'] ) ) {
				$response['total_count'] = absint( $response['total_count'] );
			}
			if ( ! empty( $args['funnel_id'] ) ) {
				$funnel_data             = WFFN_REST_Funnels::get_instance()->get_funnel_data( $args['funnel_id'] );
				$response['funnel_data'] = is_array( $funnel_data ) ? $funnel_data : [];
			}


			return $return_data ? $response : rest_ensure_response( $response );

		}

		public function prepare_order_data( $final_orders, $return_data = false ) {
			return array_map( function ( $result ) use ( $return_data ) {
				$data = [
					'order_id'     => $result['order_id'],
					'email'        => $result['email'] ?? '',
					'name'         => $result['f_name'] ?? '' . ' ' . $result['l_name'] ?? '',
					'phone'        => $result['phone'] ?? '',
					'date'         => $result['date'] ?? '',
					'referrers'    => $result['referrers'] ?? '',
					'utm_campaign' => $result['utm_campaign'] ?? '',
					'utm_source'   => $result['utm_source'] ?? '',
					'utm_medium'   => $result['utm_medium'] ?? '',
					'total_spent'  => $result['total_sales'] ?? '',
					'customer_id'  => $result['cid'],
					'funnel_title' => $result['funnel_title'] ?? '',
					'fid'          => $result['fid'] ?? '',
					'convert_time' => (isset($result['convert_time'])) ? human_time_diff( current_time( 'timestamp' ), current_time( 'timestamp' ) + absint( $result['convert_time'] ) ) ?? '':''
				];

				if ( true === $return_data ) {
					$data['step_id']        = ! empty( $result['step_id'] ) ? $result['step_id'] : 0;
					$data['checkout_total'] = ! empty( $result['checkout_total'] ) ? $result['checkout_total'] : 0;
					$data['bump_total']     = ! empty( $result['bump_total'] ) ? $result['bump_total'] : 0;
					$data['bump_accepted']  = ! empty( $result['bump_accepted'] ) ? $result['bump_accepted'] : '';
					$data['bump_rejected']  = ! empty( $result['bump_rejected'] ) ? $result['bump_rejected'] : '';
					$data['offer_total']    = ! empty( $result['offer_total'] ) ? $result['offer_total'] : 0;
					$data['offer_accepted'] = ! empty( $result['offer_accepted'] ) ? $result['offer_accepted'] : '';
					$data['offer_rejected'] = ! empty( $result['offer_rejected'] ) ? $result['offer_rejected'] : '';
				}

				return $data;

			}, $final_orders );
		}

		/**
		 * Get Single Funnel Optin Data (map with funnelkit contact)
		 *
		 * @param $request
		 *
		 * @return array|WP_Error|WP_HTTP_Response|WP_REST_Response
		 */
		public function get_single_funnel_leads( $request ) {
			return $this->get_leads( $request );
		}

		/**
		 * get optin data with funnelkit contact data.
		 *
		 * @param $request
		 * @param $return_data
		 *
		 * @return array|WP_Error|WP_HTTP_Response|WP_REST_Response
		 */
		public function get_leads( $request, $return_data = false ) {
			$response                = [ 'status' => false, 'message' => __( 'No Optins Found', 'funnel-builder' ), 'records' => [] ];
			$args                    = array(
				'funnel_id'   => $request['funnel_id'] ?? 0,
				's'           => $request['s'] ?? '',
				'limit'       => $request['limit'] ?? get_option( 'posts_per_page' ),
				'page_no'     => $request['page_no'] ?? 1,
				'total_count' => $request['total_count'] ?? false,
				'only_count'  => $request['only_count'] ?? false,
				'delete_ids'  => isset( $request['delete_ids'] ) ? $request['delete_ids'] : false,
				'offset'      => isset( $request['offset'] ) ? $request['offset'] : '',

			);
			$this->args              = $args;
			$limit                   = $args['limit'];
			$page_no                 = $args['page_no'];
			$offset                  = ! empty( $args['offset'] ) ? $args['offset'] : ( intval( $limit ) * intval( $page_no - 1 ) );
			$funnel_data             = WFFN_REST_Funnels::get_instance()->get_funnel_data( $args['funnel_id'] );
			$response['funnel_data'] = is_array( $funnel_data ) ? $funnel_data : [];

			global $wpdb;
			$filters = [];

			if ( isset( $request['filters'] ) ) {
				$filters = $this->prepare_filters( $request['filters'] );
			}

			$delete_ids = $args['delete_ids'];

			if ( ! empty( $delete_ids ) ) {
				$op_obj = WFFN_Optin_Contacts_Analytics::get_instance();
				$delete = $op_obj->delete_optin_entries( $delete_ids );
				if ( is_array( $delete ) && true === $delete['db_error'] ) {
					return rest_ensure_response( $delete );
				}

				$args['total_count'] = 'yes';
			}

			$response['filters_list'] = $this->filters_list( $args, true );
			$where_query              = [ '(1=1)' ];

			$search_filter = ! empty( $args['s'] ) ? $args['s'] : '';

			if ( isset( $filters['s'] ) && isset( $filters['s']['data'] ) && ! empty( $filters['s']['data'] ) ) {
				$search_filter = $filters['s']['data'];

			}

			if ( ! empty( $search_filter ) ) {
				$search_text   = trim( $search_filter );
				$where_query[] = $wpdb->prepare( "(optin.email like %s OR optin.data like %s)", "%" . $search_text . "%", "%" . $search_text . "%" );
			}

			if ( isset( $filters['funnels'] ) && isset( $filters['funnels']['data'] ) && ! empty( $filters['funnels']['data'] ) ) {
				$where_query[] = "(optin.funnel_id IN ({$filters['funnels']['data']}))";
			} elseif ( $args['funnel_id'] > 0 ) {
				$where_query[] = "(optin.funnel_id = '{$args['funnel_id']}')";
			}

			if ( isset( $filters['period'] ) ) {
				$where_query[] = "(optin.date BETWEEN '" . $filters['period']['data']['after'] . "' AND '" . $filters['period']['data']['before'] . "')";
			}

			$conv_filter = apply_filters( 'wffn_filter_data_conversion_query', [], 'optin', $where_query, $filters );

			$conv_join   = '';
			$case_string = '';

			if ( is_array( $conv_filter ) && count( $conv_filter ) > 0 ) {
				$conv_join   = isset( $conv_filter['join'] ) ? $conv_filter['join'] : '';
				$case_string = isset( $conv_filter['case_string'] ) ? $conv_filter['case_string'] : '';
				$where_query = isset( $conv_filter['where_query'] ) ? $conv_filter['where_query'] : $where_query;
			}

			$where_query = implode( ' AND ', $where_query );
			$limit_str   = " LIMIT $offset, $limit";
			$select_type = "select optin.*, optin.funnel_id as fid {$case_string} ";
			if ( 'yes' === $args['total_count'] ) {
				$total_count_query  = "select count(optin.id) as total_count  from {$wpdb->prefix}bwf_optin_entries as optin {$conv_join} where {$where_query}";
				$total_count_result = $wpdb->get_results( $total_count_query, ARRAY_A );
				if ( ! empty( $total_count_result ) ) {
					$response['total_count'] = absint( $total_count_result[0]['total_count'] );
				}
			}

			$sql_query = "{$select_type}  from {$wpdb->prefix}bwf_optin_entries as optin {$conv_join} where {$where_query} ORDER BY optin.date DESC {$limit_str}";
			$results   = $wpdb->get_results( $sql_query, ARRAY_A );
			$db_error  = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( is_array( $db_error ) && isset( $db_error['db_error'] ) && true === $db_error['db_error'] ) {
				return rest_ensure_response( $response );
			}

			if ( ! is_array( $results ) || count( $results ) === 0 ) {
				$response['status'] = true;

				return rest_ensure_response( $response );
			}

			$response['status']                      = true;
			$contact                                 = WFFN_Funnel_Contacts::get_instance();
			$response['conversion_migration_status'] = WFFN_Core()->admin_notifications->is_conversion_migration_required();

			$final_result = [];

			$results = $this->add_funnel_title( $results );

			foreach ( $results as $result ) {
				$entry_id       = $result['id'];
				$optin_data     = json_decode( $result['data'], true );
				$full_name      = ( $optin_data['optin_first_name'] ?? '' ) . ' ' . ( $optin_data['optin_last_name'] ?? '' );
				$phone          = ! empty( $result['phone'] ) ? $result['phone'] : ( ! empty( $optin_data['optin_phone'] ) ? $optin_data['optin_phone'] : '' );
				$final_result[] = [
					'entry_id'     => $entry_id,
					'email'        => $result['email'] ?? ''    ,
					'name'         => $full_name,
					'phone'        => $phone,
					'others'       => $contact->prepare_optin_data( $result['data'] ),
					'date'         => $result['date'] ?? '',
					'referrers'    => $result['referrers'] ?? '',
					'utm_campaign' => $result['utm_campaign'] ?? '',
					'utm_source'   => $result['utm_source'] ?? '',
					'utm_medium'   => $result['utm_medium'] ?? '',
					'customer_id'  => $result['cid'],
					'funnel_title' => $result['funnel_title'] ?? '',
					'fid'          => $result['fid'] ?? '',
					'convert_time' => ( isset( $result['convert_time'] ) ) ? human_time_diff( current_time( 'timestamp' ), current_time( 'timestamp' ) + absint( $result['convert_time'] ) ) ?? '' : ''

				];
			}

			$funnel_data             = ( isset( $args['funnel_id'] ) && ! empty( $args['funnel_id'] ) ) ? WFFN_REST_Funnels::get_instance()->get_funnel_data( $args['funnel_id'] ) : [];
			$response['funnel_data'] = is_array( $funnel_data ) ? $funnel_data : [];
			$response['records']     = $final_result;
			$response['message']     = __( 'Success', 'funnel-builder' );

			return $return_data ? $response : rest_ensure_response( $response );

		}

		public function delete_lead_entry( $request ) {
			$resp = array(
				'msg'     => __( 'Failed', 'funnel-builder' ),
				'success' => false,
			);

			$entry_ids = isset( $request['source_id'] ) ? $request['source_id'] : 0;

			$op_obj = WFFN_Optin_Contacts_Analytics::get_instance();
			$result = $op_obj->delete_optin_entries( $entry_ids );
			if ( is_array( $result ) && isset( $result['db_error'] ) ) {
				return rest_ensure_response( $resp );
			}
			$resp = array(
				'success' => true,
				'msg'     => __( 'Success', 'funnel-builder' )
			);

			return rest_ensure_response( $resp );

		}

	}


	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'wffn_orders', 'WFFN_Funnel_Orders' );
	}
}
