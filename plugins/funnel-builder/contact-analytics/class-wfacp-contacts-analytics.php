<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFACP_Contacts_Analytics
 */
if ( ! class_exists( 'WFACP_Contacts_Analytics' ) ) {
	class WFACP_Contacts_Analytics {

		/**
		 * instance of class
		 * @var null
		 */
		private static $ins = null;
		/**
		 * WPDB instance
		 *
		 * @since 2.0
		 *
		 * @var $wp_db
		 */
		protected $wp_db;

		/**
		 * WFACP_Contacts_Analytics constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->wp_db = $wpdb;
		}

		/**
		 * @return WFACP_Contacts_Analytics|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		/**
		 * @param $funnel_id
		 * @param string $search
		 *
		 * @return array|object|null
		 */
		public function get_contacts( $funnel_id, $search = '' ) {

			if ( ! empty( $search ) ) {
				$query = "SELECT contact.id as cid, contact.f_name, contact.l_name, contact.email, aero.date, aero.total_revenue, aero.wfacp_id FROM " . $this->wp_db->prefix . 'bwf_contact' . " AS contact JOIN " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero ON contact.id=aero.cid WHERE aero.fid=$funnel_id";
				global $wpdb;
				$query .= $wpdb->prepare( " AND (contact.f_name LIKE %s OR contact.email LIKE %s) group by contact.id", "%" . $search . "%", "%" . $search . "%" );


			} else {
				$query = "SELECT aero.cid FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero WHERE aero.fid=$funnel_id";
			}

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		public function get_contacts_by_funnel( $funnel_id ) {
			return "SELECT DISTINCT cid as id FROM " . $this->wp_db->prefix . "wfacp_stats WHERE fid = " . $funnel_id . "";

		}

		public function get_contacts_all() {
			return "SELECT DISTINCT cid as id FROM " . $this->wp_db->prefix . "wfacp_stats where cid != 0";

		}

		/**
		 * Get Checkout Page Ids from Contacts IDS
		 *
		 * @param $cids
		 *
		 * @return string
		 */
		public function get_checkout_ids_by_customers( $cids ) {
			$cids = implode( ',', $cids );

			return "SELECT DISTINCT wfacp_id as id FROM " . $this->wp_db->prefix . "wfacp_stats where cid in ($cids) and wfacp_id!=0";

		}

		public function get_contacts_by_funnel_and_checkout_id( $funnel_id, $checkout_ids ) {
			return "SELECT cid,wfacp_id FROM " . $this->wp_db->prefix . "wfacp_stats WHERE fid = " . $funnel_id . " AND wfacp_id IN " . $funnel_id . "";

		}

		/**
		 * @param $funnel_id
		 * @param $filtered_ids
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_contacts_data( $funnel_id, $filtered_ids = [] ) {
			$get_total_possible_contacts_str = implode( ',', $filtered_ids );
			$query                           = "SELECT aero.cid as cid,SUM(aero.total_revenue) as total_revenue,COUNT(ID) as count_orders  FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero WHERE aero.fid=$funnel_id AND `cid` IN (" . $get_total_possible_contacts_str . ") GROUP BY cid";

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}


		/**
		 * @param $funnel_id
		 * @param $filtered_ids
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_contacts_global( $filtered_ids = [] ) {
			$get_total_possible_contacts_str = implode( ',', $filtered_ids );
			$query                           = "SELECT aero.cid as cid,SUM(aero.total_revenue) as total_revenue,COUNT(ID) as count_orders  FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero WHERE aero.fid != 0 AND `cid` IN (" . $get_total_possible_contacts_str . ") GROUP BY cid";

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		/**
		 * @param $cids
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_all_contacts_data( $filtered_ids = [] ) {
			$get_total_possible_contacts_str = implode( ',', $filtered_ids );

			$query    = "SELECT aero.order_id, aero.cid as cid, DATE_FORMAT(aero.date, '%Y-%m-%dT%TZ') as 'date', aero.total_revenue, aero.total_revenue AS aero_revenue, aero.wfacp_id FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero WHERE `cid` IN (" . $get_total_possible_contacts_str . ")";
			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		/**
		 * @param $funnel_id
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contacts_records( $funnel_id, $cid ) {
			$item_data = [];

			$query = "SELECT aero.order_id as 'order_id', aero.wfacp_id as 'object_id', p.post_title as 'object_name',aero.total_revenue as 'total_revenue',DATE_FORMAT(aero.date, '%Y-%m-%dT%TZ') as 'date', 'checkout' as 'type' FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero.wfacp_id  = p.id WHERE aero.fid=$funnel_id AND aero.cid=$cid order by aero.date asc";

			$order_data = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error   = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			if ( ! is_array( $order_data ) || count( $order_data ) === 0 ) {
				return array();
			}

			$get_order_ids = wp_list_pluck( $order_data, 'order_id' );

			if ( is_array( $get_order_ids ) && count( $get_order_ids ) > 0 ) {

				/**
				 * get order all items meta by order id for showing name and quantity
				 */
				$item_query = "SELECT oi.order_id as 'order_id', oi.order_item_name as 'product_name', oi.order_item_id as 'item_id', oim2.meta_key as 'item_meta', oim2.meta_value as 'meta_value' FROM " . $this->wp_db->prefix . "woocommerce_order_items as oi
                        LEFT JOIN " . $this->wp_db->prefix . "woocommerce_order_itemmeta as oim ON oi.order_item_id = oim.order_item_id
                        LEFT JOIN " . $this->wp_db->prefix . "woocommerce_order_itemmeta as oim2 ON oi.order_item_id = oim2.order_item_id
                        WHERE  oi.order_id IN (" . implode( ',', $get_order_ids ) . ") AND oi.order_item_type='line_item' AND oim.meta_key='_line_total' ORDER BY oi.order_id ASC";

				$item_data = $this->wp_db->get_results( $item_query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$db_error  = WFFN_Common::maybe_wpdb_error( $this->wp_db );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}

			}

			/**
			 * Exclude which items purchased by bump and upsell
			 */
			$exclude_items     = [];
			$upstroke_purchase = array_keys( wp_list_pluck( $item_data, 'item_meta' ), '_upstroke_purchase', true );
			$bump_purchase     = array_keys( wp_list_pluck( $item_data, 'item_meta' ), '_bump_purchase', true );
			if ( is_array( $upstroke_purchase ) && count( $upstroke_purchase ) > 0 ) {
				$exclude_items = array_merge( $exclude_items, $upstroke_purchase );
			}
			if ( is_array( $bump_purchase ) && count( $bump_purchase ) > 0 ) {
				$exclude_items = array_merge( $exclude_items, $bump_purchase );
			}

			if ( is_array( $exclude_items ) && count( $exclude_items ) > 0 ) {
				foreach ( $exclude_items as $key_id ) {
					if ( isset( $item_data[ $key_id ] ) && isset( $item_data[ $key_id ]['item_id'] ) ) {
						$item_id = $item_data[ $key_id ]['item_id'];
						foreach ( $item_data as $key => $item ) {
							if ( absint( $item['item_id'] ) === absint( $item_id ) ) {
								unset( $item_data[ $key ] );
							}
						}
					}

				}
			}

			foreach ( $order_data as &$order ) {
				$get_names = [];
				if ( is_array( $item_data ) && count( $item_data ) > 0 ) {
					foreach ( $item_data as $item_i ) {
						if ( isset( $item_i['order_id'] ) && ( $order->order_id === $item_i['order_id'] ) && '_qty' === $item_i['item_meta'] ) {
							$get_names[ $order->order_id ]['product_name'][] = $item_i['product_name'];
							if ( isset( $get_names[ $order->order_id ]['qty'] ) ) {
								$get_names[ $order->order_id ]['qty'] += absint( $item_i['meta_value'] );
							} else {
								$get_names[ $order->order_id ]['qty'] = absint( $item_i['meta_value'] );
							}
						}
					}
					if ( isset( $get_names[ $order->order_id ] ) && isset( $get_names[ $order->order_id ]['product_name'] ) ) {
						$order->product_name = implode( ', ', array_unique( $get_names[ $order->order_id ]['product_name'] ) );
						$order->product_qty  = $get_names[ $order->order_id ]['qty'];
					} else {
						$order->product_name = '';
						$order->product_qty  = 0;
					}
				} else {
					$order->product_name = '';
					$order->product_qty  = 0;
				}
			}

			return $order_data;
		}

		public function get_contacts_revenue_records( $cid, $order_ids ) {
			$query = "SELECT aero.fid as fid, aero.order_id as 'order_id', aero.wfacp_id as 'object_id', p.post_title as 'object_name',aero.total_revenue as 'total_revenue',DATE_FORMAT(aero.date, '%Y-%m-%d %T') as 'date', 'checkout' as 'type' FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero.wfacp_id  = p.id WHERE aero.order_id IN ( $order_ids ) AND aero.cid=$cid order by aero.date asc";

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contact_record_by_cid( $cid ) {
			$query = "SELECT aero.order_id as 'order_id', aero.wfacp_id as 'object_id', p.post_title as 'object_name',aero.total_revenue as 'total_revenue',DATE_FORMAT(aero.date, '%Y-%m-%dT%TZ') as 'date', 'checkout' as 'type' FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero.wfacp_id  = p.id WHERE aero.cid=$cid order by aero.date asc";

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			if ( ! empty( $data[0]->order_id ) ) {
				$order_products = ! empty( wc_get_order( $data[0]->order_id ) ) ? wffn_rest_funnel_modules()->get_first_item( $data[0]->order_id ) : [];
				if ( ! empty( $order_products ) ) {
					$data[0]->product_name = $order_products['title'];
					$data[0]->product_qty  = $order_products['more'];
				}
			} else if ( ! empty( $data[0] ) ) {
				$data[0]->product_name = '';
				$data[0]->product_qty  = '';
			}

			return $data;
		}

		/**
		 * @param $cid
		 * @param $step_ids
		 *
		 * @return array|object|null
		 */
		public function export_contacts_records( $funnel_id, $cid, $step_ids ) {
			$step_ids = is_array( $step_ids ) ? implode( ',', $step_ids ) : $step_ids;
			$filter   = "aero.wfacp_id as 'id', p.post_title as 'checkout_name', '' as 'checkout_products', '' as 'checkout_coupon', aero.order_id as 'checkout_order_id', aero.total_revenue as 'checkout_total'";
			$query    = "SELECT " . $filter . " FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero.wfacp_id  = p.id WHERE aero.wfacp_id IN ($step_ids) AND aero.fid=$funnel_id AND aero.cid=$cid order by aero.id asc";

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}


		/**
		 * @param $cid
		 * @param $funnel_id
		 *
		 * @return array|false|object|stdClass[]|null
		 */
		public function export_aero_data_order_id( $order_id ) {
			$filter   = "aero.wfacp_id as 'id', p.post_title as 'checkout_name', '' as 'checkout_products', '' as 'checkout_coupon', aero.order_id as 'checkout_order_id', aero.total_revenue as 'checkout_total'";
			$query    = "SELECT " . $filter . " FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero.wfacp_id  = p.id WHERE  aero.order_id={$order_id} order by aero.id asc";
			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return false;
			}

			return $data;
		}

		public function get_checkout_name( $wfacp_id ) {
			$data     = $this->wp_db->get_results( $this->wp_db->prepare( "SELECT p.post_title as 'checkout_name', '' as 'checkout_products', '' as 'checkout_coupon' FROM " . $this->wp_db->prefix . 'posts' . " as p.ID= %s ", $wfacp_id ), ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return false;
			}

			return $data;
		}

		/**
		 * @param $cid
		 * @param $funnel_id
		 *
		 * @return array|false|object|stdClass[]|null
		 */
		public function export_contacts_records_by_contact( $cid, $funnel_id = '' ) {
			$funnel_query = ( '' !== $funnel_id && $funnel_id > 0 ) ? " AND aero.fid = $funnel_id " : '';
			$filter       = "aero.wfacp_id as 'id', p.post_title as 'checkout_name', '' as 'checkout_products', '' as 'checkout_coupon', aero.order_id as 'checkout_order_id', aero.total_revenue as 'checkout_total'";
			$query        = "SELECT " . $filter . " FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero.wfacp_id  = p.id WHERE  aero.cid=$cid " . $funnel_query . " order by aero.id asc";
			$data         = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error     = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return false;
			}

			return $data;
		}

		/**
		 * @param $funnel_id
		 * @param $start_date
		 * @param $end_date
		 * @param $is_interval
		 *
		 * @return string
		 */
		public function get_contacts_by_funnel_id( $funnel_id, $start_date, $end_date, $is_interval = '' ) {
			$date           = ( '' !== $start_date && '' !== $end_date ) ? " AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' " : '';
			$funnel_query   = ( 0 === intval( $funnel_id ) ) ? " AND fid != " . $funnel_id . " " : " AND fid = " . $funnel_id . " ";
			$interval_param = ! empty( $is_interval ) ? ', date as p_date ' : '';


			return "SELECT DISTINCT cid as contacts " . $interval_param . " FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE 1=1 " . $date . " " . $funnel_query;
		}

		/**
		 * @param $funnel_id
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_records_by_date_range( $funnel_id, $start_date, $end_date ) {

			$query = "SELECT aero.wfacp_id as 'object_id', p.post_title as 'object_name',SUM(aero.total_revenue) as 'total_revenue',COUNT(aero.ID) as cn,aero.date as 'date', 'checkout' as 'type' FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero.wfacp_id  = p.id WHERE aero.fid=$funnel_id AND date BETWEEN '" . $start_date . "' AND '" . $end_date . "' GROUP by aero.wfacp_id ASC";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $funnel_id
		 * @param $interval_query
		 * @param $start_date
		 * @param $end_date
		 * @param $group_by
		 * @param $limit
		 *
		 * @return array|object|null
		 */
		public function get_total_orders_by_interval( $funnel_id, $interval_query, $start_date, $end_date, $group_by, $limit ) {
			$funnel_param = ( $funnel_id !== '' && $funnel_id > 0 ) ? " AND fid = " . $funnel_id . " " : " AND fid != 0 ";

			$query = "SELECT  COUNT(ID) as total_orders " . $interval_query . "  FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE 1=1 AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' " . $funnel_param . $group_by . " ORDER BY id ASC " . $limit . "";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $funnel_id
		 * @param $interval_query
		 * @param $start_date
		 * @param $end_date
		 * @param $group_by
		 *
		 * @return array|object|null
		 */
		public function get_total_revenue_by_interval( $funnel_id, $interval_query, $start_date, $end_date, $group_by ) {

			$query = "SELECT  SUM(total_revenue) as sum_aero " . $interval_query . "  FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE 1=1 AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' AND fid = " . $funnel_id . " " . $group_by . " ORDER BY id ASC";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $funnel_id
		 * @param $start_date
		 * @param $end_date
		 * @param $is_interval
		 * @param $int_request
		 *
		 * @return array|false[]|object|stdClass|null
		 */
		public function get_total_orders( $funnel_id, $start_date, $end_date, $is_interval = '', $int_request = '' ) {
			$funnel_id = ( $funnel_id !== '' ) ? " AND fid = " . $funnel_id . " " : " AND fid != 0 ";
			$date      = ( '' !== $start_date && '' !== $end_date ) ? " AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' " : '';

			$interval_query = '';
			$group_by       = '';
			if ( class_exists( 'WFFN_REST_Controller' ) ) {
				$rest_con = new WFFN_REST_Controller();

				if ( 'interval' === $is_interval ) {
					$get_interval   = $rest_con->get_interval_format_query( $int_request, 'date' );
					$interval_query = $get_interval['interval_query'];
					$interval_group = $get_interval['interval_group'];
					$group_by       = " GROUP BY " . $interval_group;

				}
			}

			$query    = "SELECT  COUNT(ID) as total_orders " . $interval_query . " FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE 1=1 " . $date . $funnel_id . $group_by . " ORDER BY id ASC";
			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $start_date
		 * @param $end_date
		 * @param $is_interval
		 * @param $int_request
		 *
		 * @return array|false[]
		 */
		public function get_total_cids( $start_date, $end_date, $is_interval = '', $int_request = '' ) {
			$date = ( '' !== $start_date && '' !== $end_date ) ? " AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' " : '';

			$interval_query = '';
			$group_by       = '';
			if ( ! class_exists( 'WFFN_REST_Controller' ) ) {
				$rest_con = new WFFN_REST_Controller();

				if ( 'interval' === $is_interval ) {
					$get_interval   = $rest_con->get_interval_format_query( $int_request, 'date' );
					$interval_query = $get_interval['interval_query'];
					$interval_group = $get_interval['interval_group'];
					$group_by       = " GROUP BY " . $interval_group;

				}
			}

			$query    = "SELECT DISTINCT cid " . $interval_query . " FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE 1=1 " . $date . $group_by . " ORDER BY cid ASC";
			$data     = $this->wp_db->get_col( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $funnel_id
		 * @param $start_date
		 * @param $end_date
		 * @param $is_interval
		 * @param $int_request
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_total_revenue( $funnel_id, $start_date, $end_date, $is_interval = '', $int_request = '' ) {
			$funnel_id = ( $funnel_id !== '' ) ? " AND fid = " . $funnel_id . " " : " AND fid != 0 ";
			$date      = ( '' !== $start_date && '' !== $end_date ) ? " AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' " : '';

			$interval_query = '';
			$group_by       = '';
			if ( class_exists( 'WFFN_REST_Controller' ) ) {
				$rest_con = new WFFN_REST_Controller();

				if ( 'interval' === $is_interval ) {
					$get_interval   = $rest_con->get_interval_format_query( $int_request, 'date' );
					$interval_query = $get_interval['interval_query'];
					$interval_group = $get_interval['interval_group'];
					$group_by       = " GROUP BY " . $interval_group;

				}
			}

			$query    = "SELECT SUM(total_revenue) as sum_aero " . $interval_query . " FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE 1=1 " . $date . $funnel_id . $group_by . " ORDER BY id DESC";
			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		/**
		 * @param $start_date
		 * @param $end_date
		 * @param string $limit
		 *
		 * @return array
		 */
		public function get_top_checkouts( $start_date, $end_date, $limit = '' ) {
			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';
			$query = "SELECT wfacp_id,sum(total_revenue) as revenue,COUNT(aero.id) as conversion,p.post_title as title FROM `" . $this->wp_db->prefix . "wfacp_stats` as aero LEFT JOIN " . $this->wp_db->prefix . "posts as p ON p.id = aero.wfacp_id WHERE date >= '" . $start_date . "' AND date < '" . $end_date . "' GROUP BY wfacp_id ORDER BY sum(total_revenue) DESC" . $limit;

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $aero_id
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_aero_data_by_id( $aero_id, $start_date = '', $end_date = '' ) {
			$range = '';
			if ( $start_date !== '' && $end_date !== '' ) {
				$range = "WHERE date >= '" . $start_date . "' AND date < '" . $end_date . "' AND ";
			}
			$query = "SELECT * from `" . $this->wp_db->prefix . "wfacp_stats` WHERE " . $range . " wfacp_id = " . $aero_id;

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		public function get_contacts_by_ids( $ids, $fid, $start_date = '' ) {
			$get_total_possible_contacts_str = implode( ',', $ids );
			$start_date                      = $start_date !== '' ? " AND date >= '" . $start_date . "' " : '';

			$query = "SELECT DISTINCT cid FROM " . $this->wp_db->prefix . "wfacp_stats WHERE wfacp_id IN (" . $get_total_possible_contacts_str . ") AND fid = $fid " . $start_date;

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $limit
		 * @param string $order
		 * @param string $order_by
		 *
		 * @return string
		 */
		public function get_timeline_data_query( $limit, $order = "DESC", $order_by = 'date' ) {
			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			return "SELECT stats.wfacp_id as id, stats.fid as 'fid', stats.cid as 'cid', stats.order_id as 'order_id', CONVERT( stats.total_revenue USING utf8) as 'total_revenue', 'aero' as 'type', posts.post_title as 'post_title', stats.date as date FROM " . $this->wp_db->prefix . "wfacp_stats as stats LEFT JOIN " . $this->wp_db->prefix . "posts as posts ON stats.wfacp_id = posts.ID ORDER BY " . $order_by . " " . $order . " " . $limit;
		}

		/**
		 * @param $limit
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_top_funnels( $limit = '', $date_query = '' ) {
			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';
			$date_query = str_replace('{{COLUMN}}', 'stats.date', $date_query );
			$query = "SELECT funnel.id as fid, funnel.title as title, stats.total as total FROM " . $this->wp_db->prefix . "bwf_funnels AS funnel 
			JOIN ( SELECT fid, SUM( total_revenue ) as total FROM " . $this->wp_db->prefix . "wfacp_stats as stats 
			WHERE fid != 0 AND ".$date_query." group by fid ) as stats ON funnel.id = stats.fid WHERE 1 = 1 GROUP BY funnel.id ORDER BY total DESC " . $limit;


			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $sales_ids
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_funnels_analytics( $sales_ids, $start_date = '', $end_date = '' ) {
			$sales_ids = ( is_array( $sales_ids ) && count( $sales_ids ) > 0 ) ? " AND stats.fid IN (" . implode( ',', $sales_ids ) . ") " : '';
			$range     = ( '' !== $start_date && '' !== $end_date ) ? " AND stats.date >= '" . $start_date . "' AND stats.date < '" . $end_date . "' " : '';

			$query     = "SELECT stats.fid as fid, '' as title, SUM(stats.total_revenue) as total FROM " . $this->wp_db->prefix . "wfacp_stats AS stats 
			WHERE 1=1 AND stats.fid != 0 " . $sales_ids . $range . " GROUP BY stats.fid ORDER BY stats.fid ASC ";

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

        /**
         * @param $cids
         * @param $funnel_id
         * @return array|false[]|true
         */
		public function delete_contact( $cids, $funnel_id = 0 ) {
			$cid_count                = count( $cids );
			$stringPlaceholders       = array_fill( 0, $cid_count, '%s' );
			$placeholdersForFavFruits = implode( ',', $stringPlaceholders );
			$funnel_query             = ( absint( $funnel_id ) > 0 ) ? " AND fid = " . $funnel_id . " " : '';

			$query = "DELETE FROM " . $this->wp_db->prefix . "wfacp_stats WHERE cid IN( " . $placeholdersForFavFruits . " ) " . $funnel_query;

			$this->wp_db->query( $this->wp_db->prepare( $query, $cids ) ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return true;
		}


		/**
		 * @param $contact_id
		 * @param string $search
		 *
		 * @return array|object|null
		 */
		public function get_contacts_crm( $contact_id, $search = '' ) {

			if ( ! empty( $search ) ) {
				$query = "SELECT p . post_title as title, aero . fid as fid, aero . wfacp_id FROM " . $this->wp_db->prefix . 'bwf_contact' . " as contact JOIN " . $this->wp_db->prefix . 'wfacp_stats' . " as aero ON contact . id = aero . cid LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero . fid = p . id WHERE aero . cid = $contact_id";

				$query .= " and ( contact . f_name LIKE '%$search%' or contact . email LIKE '%$search%') group by contact . id";

			} else {
				$query = "SELECT p . post_title as title, aero . fid FROM " . $this->wp_db->prefix . 'wfacp_stats' . " as aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero . fid = p . id WHERE aero . cid = $contact_id";
			}

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}


		/**
		 * @param $contact_id
		 * @param string $search
		 * @param string $offset
		 * @param string $limit
		 * @param string $orderby
		 * @param string $order
		 *
		 * @return array|object|null
		 */
		public function get_contacts_data_crm( $contact_id, $search = '', $offset = '', $limit = '', $orderby = '', $order = '' ) {
			$query = "SELECT p . post_title as title, aero . fid as fid, aero . wfacp_id FROM " . $this->wp_db->prefix . 'bwf_contact' . " as contact JOIN " . $this->wp_db->prefix . 'wfacp_stats' . " as aero ON contact . id = aero . cid LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero . fid = p . id WHERE aero . cid = $contact_id";

			if ( ! empty( $search ) ) {
				$query .= " and ( contact . f_name LIKE '%$search%' or contact . email LIKE '%$search%')";
			}
			if ( ! empty( $orderby ) ) {
				$query .= " ORDER BY $orderby $order";
			}
			if ( ! empty( $orderby ) ) {
				$query .= " LIMIT $offset, $limit";
			}

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contacts_records_crm( $cid ) {
			$query = "SELECT p . post_title as 'name', aero . fid as 'funnel', aero . total_revenue as 'amount', aero . order_id as 'order', DATE_FORMAT( aero . date, '%Y-%m-%dT%TZ' ) as 'date' FROM " . $this->wp_db->prefix . 'wfacp_stats' . " as aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero . wfacp_id = p . id WHERE aero . cid = $cid order by aero . fid asc";

			return $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}


		/**
		 * @param array $cids
		 *
		 * @return bool|false|int
		 */
		public function delete_contact_crm( $cids = array() ) {

			$cid_count                = count( $cids );
			$stringPlaceholders       = array_fill( 0, $cid_count, '%s' );
			$placeholdersForFavFruits = implode( ',', $stringPlaceholders );

			$query = "DELETE FROM " . $this->wp_db->prefix . "wfacp_stats WHERE cid IN( " . $placeholdersForFavFruits . " )";

			return $this->wp_db->query( $this->wp_db->prepare( $query, $cids ) );

		}

		/**
		 * @param $funnel_id
		 */
		public function reset_analytics( $funnel_id ) {
			$query = "DELETE FROM " . $this->wp_db->prefix . "wfacp_stats WHERE fid = " . $funnel_id;
			$this->wp_db->query( $query );
		}
	}
}