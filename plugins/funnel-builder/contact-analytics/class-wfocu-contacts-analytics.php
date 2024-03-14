<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFOCU_Contacts_Analytics
 */
if ( ! class_exists( 'WFOCU_Contacts_Analytics' ) ) {

	class WFOCU_Contacts_Analytics {

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
		 * WFOCU_Contacts_Analytics constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->wp_db = $wpdb;
		}

		/**
		 * @return WFOCU_Contacts_Analytics|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self();
			}

			return self::$ins;
		}


		/**
		 * @param $funnel_id
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_contact_conversions_by_offer( $ids, $cid = '', $is_accepted = 4, $limit = '' ) {
			$cid   = is_array( $cid ) ? implode( ',', $cid ) : $cid;
			$query = "SELECT COUNT(session.order_id) as count_orders, session.cid as cid, SUM(`value`) AS `total_revenue` FROM " . $this->wp_db->prefix . 'wfocu_session' . " AS session LEFT JOIN " . $this->wp_db->prefix . 'wfocu_event' . " AS event ON session.id=event.sess_id WHERE session.cid IN  (" . $cid . ") AND event.action_type_id = $is_accepted AND event.object_id IN (" . $ids . ") group by cid";
			if ( ! empty( $limit ) ) {
				$query .= $limit;
			}
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
		public function get_contacts_data( $funnel_id, $cid ) {
			$cid   = is_array( $cid ) ? implode( ',', $cid ) : $cid;
			$query = "SELECT COUNT(session.order_id) as count_orders, session.cid as cid, SUM(`value`) AS `total_revenue` FROM " . $this->wp_db->prefix . 'wfocu_session' . " AS session LEFT JOIN " . $this->wp_db->prefix . 'wfocu_event' . " AS event ON session.id=event.sess_id WHERE session.fid=$funnel_id AND session.cid IN  (" . $cid . ") AND event.action_type_id = 4 group by cid";

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
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
		public function get_contacts_data_global( $cid ) {
			$cid   = is_array( $cid ) ? implode( ',', $cid ) : $cid;
			$query = "SELECT COUNT(session.order_id) as count_orders, session.cid as cid, SUM(`value`) AS `total_revenue` FROM " . $this->wp_db->prefix . 'wfocu_session' . " AS session LEFT JOIN " . $this->wp_db->prefix . 'wfocu_event' . " AS event ON session.id=event.sess_id WHERE session.fid != 0 AND session.cid IN  (" . $cid . ") AND event.action_type_id = 4 group by cid";

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
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
		public function get_all_contacts_data( $cid ) {
			$cid   = is_array( $cid ) ? implode( ',', $cid ) : $cid;
			$query = "SELECT session.order_id, session.cid, DATE_FORMAT(session.timestamp, '%Y-%m-%dT%TZ') as 'date', (CASE WHEN action_type_id = 4 THEN `value`  else '' END) AS `total_revenue`, session.id as session_id, event.action_type_id FROM " . $this->wp_db->prefix . 'wfocu_session' . " AS session LEFT JOIN " . $this->wp_db->prefix . 'wfocu_event' . " AS event ON session.id=event.sess_id WHERE session.cid IN (" . $cid . ")";

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

			$query = "SELECT session.order_id as order_id, event.object_id, event.action_type_id, event.value as 'total_revenue', event_meta.meta_value as 'item_ids', DATE_FORMAT(event.timestamp, '%Y-%m-%dT%TZ') as 'date', p.post_title as 'object_name', 'upsell'as 'type' FROM " . $this->wp_db->prefix . "wfocu_event as event 
			LEFT JOIN " . $this->wp_db->prefix . "wfocu_session as session ON event.sess_id=session.id 
			LEFT JOIN " . $this->wp_db->prefix . "posts as p ON event.object_id=p.id 
			LEFT JOIN " . $this->wp_db->prefix . "wfocu_event_meta as event_meta ON event.id=event_meta.event_id 
			WHERE(event.action_type_id=4 OR event.action_type_id=6 OR event.action_type_id=7 OR event.action_type_id=9) AND session.fid=" . $funnel_id . " AND session.cid=" . $cid . " AND event_meta.meta_key = '_items_added' order by session.timestamp asc";

			$order_data = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error   = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			if ( ! is_array( $order_data ) || count( $order_data ) === 0 ) {
				return $item_data;
			}

			$all_item_ids = [];

			/** merge all items ids in one array */
			if ( is_array( $order_data ) && count( $order_data ) > 0 ) {
				foreach ( $order_data as &$i_array ) {
					$i_array->item_ids = ( isset( $i_array->item_ids ) && '' != $i_array->item_ids ) ? json_decode( $i_array->item_ids ) : [];
					if ( is_array( $i_array->item_ids ) && count( $i_array->item_ids ) > 0 ) {
						$all_item_ids = array_merge( $all_item_ids, $i_array->item_ids );
					}
				}
			}

			if ( is_array( $all_item_ids ) && count( $all_item_ids ) > 0 ) {
				/**
				 * get order item product name and quantity by items ids
				 */
				$item_query = "SELECT oi.order_item_id as 'item_id', oi.order_item_name as 'product_name', oim.meta_value as 'qty' FROM " . $this->wp_db->prefix . "woocommerce_order_items as oi LEFT JOIN " . $this->wp_db->prefix . "woocommerce_order_itemmeta as oim ON oi.order_item_id = oim.order_item_id WHERE oi.order_item_id IN (" . implode( ',', $all_item_ids ) . ") AND oi.order_item_type = 'line_item' AND oim.meta_key = '_qty' GROUP BY oi.order_item_id";
				$item_data  = $this->wp_db->get_results( $item_query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$db_error   = WFFN_Common::maybe_wpdb_error( $this->wp_db );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}

			foreach ( $order_data as &$order ) {
				$product_titles = [];
				$qty            = 0;
				if ( is_array( $order->item_ids ) && count( $order->item_ids ) > 0 && is_array( $item_data ) && count( $item_data ) > 0 ) {
					foreach ( $order->item_ids as $item_id ) {
						$search = array_search( intval( $item_id ), array_map( 'intval', wp_list_pluck( $item_data, 'item_id' ) ), true );
						if ( false !== $search && isset( $item_data[ $search ] ) ) {
							$product_titles[] = $item_data[ $search ]->product_name;
							$qty              += absint( $item_data[ $search ]->qty );
						}
					}

				}
				unset( $order->item_ids );
				$order->product_name = implode( ', ', $product_titles );
				$order->product_qty  = $qty;
			}

			return $order_data;

		}

		public function get_contacts_revenue_records( $cid, $order_ids ) {

			$query = "SELECT session.fid as fid, session.order_id as order_id, event.object_id,event.action_type_id,event.value,DATE_FORMAT(event.timestamp, '%Y-%m-%d %T') as 'date',p.post_title as 'object_name','upsell' as 'type' FROM " . $this->wp_db->prefix . 'wfocu_event' . " as event LEFT JOIN " . $this->wp_db->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 4 OR event.action_type_id = 6 OR event.action_type_id = 7 OR event.action_type_id = 9) AND session.order_id IN ( $order_ids ) AND session.cid=$cid order by session.timestamp asc";

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

			$query = "SELECT session.order_id as order_id, event.object_id,event.action_type_id,event.value,DATE_FORMAT(event.timestamp, '%Y-%m-%dT%TZ') as 'date',p.post_title as 'object_name','upsell' as 'type' FROM " . $this->wp_db->prefix . 'wfocu_event' . " as event LEFT JOIN " . $this->wp_db->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 4 OR event.action_type_id = 6 OR event.action_type_id = 7 OR event.action_type_id = 9) AND session.cid=$cid order by session.timestamp asc";

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
		 * @param $funnel_id
		 * @param $cid
		 * @param $step_ids
		 *
		 * @return array|object|null
		 */
		public function export_contacts_records( $funnel_id, $cid, $step_ids ) {
			$step_ids = is_array( $step_ids ) ? implode( ',', $step_ids ) : $step_ids;

			$query = "SELECT event.object_id as 'id', p.post_title as 'offer_name', ( CASE WHEN event.action_type_id = 4 THEN 'Yes' ELSE 'No' END ) as 'offer_converted', ( CASE WHEN event.value = '' THEN 0 ELSE event.value END ) as 'offer_total' FROM " . $this->wp_db->prefix . 'wfocu_event' . " as event LEFT JOIN " . $this->wp_db->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE event.object_id IN ($step_ids) AND (event.action_type_id = 4 OR event.action_type_id = 6 OR event.action_type_id = 7 OR event.action_type_id = 9) AND session.fid=$funnel_id AND session.cid=$cid order by session.timestamp asc";

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
		public function export_contacts_records_by_contact_id( $cid, $funnel_id = '' ) {
			$funnel_query = ( '' !== $funnel_id && $funnel_id > 0 ) ? " AND session.fid = $funnel_id " : '';
			$query        = "SELECT event.object_id as 'id', p.post_title as 'offer_name', if(event.action_type_id=4, 'Yes', 'No') as 'offer_converted', event.value as 'offer_total' FROM " . $this->wp_db->prefix . 'wfocu_event' . " as event LEFT JOIN " . $this->wp_db->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 4 OR event.action_type_id = 6 OR event.action_type_id = 7 OR event.action_type_id = 9) AND session.cid=$cid " . $funnel_query . " order by session.timestamp asc";
			$data         = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error     = WFFN_Common::maybe_wpdb_error( $this->wp_db );
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
		public function export_upsell_offer_by_order_id( $order_id ) {
			$query    = "SELECT event.object_id as 'id', session.order_id as 'order_id', p.post_title as 'offer_name', (CASE WHEN action_type_id = 4 THEN 'Yes' WHEN action_type_id = 6 THEN 'No' ELSE '' END) AS `offer_converted`, event.value as 'offer_total' FROM " . $this->wp_db->prefix . 'wfocu_event' . " as event LEFT JOIN " . $this->wp_db->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 4 OR event.action_type_id = 6 OR event.action_type_id = 7 OR event.action_type_id = 9) AND session.order_id='{$order_id}'  order by session.timestamp asc";

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return false;
			}

			return $data;
		}

		public function get_records_by_date_range( $funnel_id, $start_date, $end_date ) {

			$query = "SELECT DISTINCT(event.object_id),p.post_title as 'name' FROM " . $this->wp_db->prefix . 'wfocu_event' . " as event LEFT JOIN " . $this->wp_db->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 1) AND session.fid=$funnel_id AND event.timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "' order by event.object_id asc";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $funnel_id
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_records_data( $funnel_id, $start_date, $end_date ) {

			$query = "SELECT COUNT(CASE WHEN action_type_id = 4 THEN 1 END) AS `converted`, COUNT(CASE WHEN action_type_id = 2 THEN 1 END) AS `viewed`, object_id  as 'offer', action_type_id,SUM(value) as revenue FROM " . $this->wp_db->prefix . 'wfocu_event' . "  as events INNER JOIN " . $this->wp_db->prefix . 'wfocu_event_meta' . " AS events_meta__funnel_id ON ( events.ID = events_meta__funnel_id.event_id ) AND ( ( events_meta__funnel_id.meta_key   = '_funnel_id' AND events_meta__funnel_id.meta_value = $funnel_id )) AND (events.action_type_id = '2' OR events.action_type_id = '4' ) AND events.timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "'  GROUP BY events.object_id";


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

			$query = "SELECT SUM(total) as sum_upsells" . $interval_query . "  FROM `" . $this->wp_db->prefix . "wfocu_session` WHERE 1=1 AND `timestamp` >= '" . $start_date . "' AND `timestamp` < '" . $end_date . "' AND fid = " . $funnel_id . " " . $group_by . " ORDER BY id ASC";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

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
			$funnel_id = ( $funnel_id !== '' ) ? " AND fid = " . $funnel_id . " " : ' AND sess.fid != 0 ';
			$date      = ( '' !== $start_date && '' !== $end_date ) ? " AND ev.timestamp >= '" . $start_date . "' AND ev.timestamp < '" . $end_date . "' " : '';

			$interval_query = '';
			$group_by       = '';
			if ( class_exists( 'WFFN_REST_Controller' ) ) {
				$rest_con = new WFFN_REST_Controller();

				if ( 'interval' === $is_interval ) {
					$get_interval   = $rest_con->get_interval_format_query( $int_request, 'ev.timestamp' );
					$interval_query = $get_interval['interval_query'];
					$interval_group = $get_interval['interval_group'];
					$group_by       = " GROUP BY " . $interval_group;

				}
			}

			$query    = "SELECT SUM(ev.value) as sum_upsells " . $interval_query . " FROM `" . $this->wp_db->prefix . "wfocu_event` as ev LEFT JOIN `" . $this->wp_db->prefix . "wfocu_session` as sess on sess.id = ev.sess_id WHERE ev.action_type_id = 4 " . $funnel_id . " AND sess.total > 0  " . $date . $group_by . " ORDER BY sess.id DESC";
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
		public function get_top_upsells( $start_date, $end_date, $limit = '' ) {

			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			$query = "SELECT object_id,sum(value) as revenue,COUNT(ev.id) as conversion,p.post_title as title FROM `" . $this->wp_db->prefix . "wfocu_event` as ev LEFT JOIN " . $this->wp_db->prefix . "posts as p ON p.id = ev.object_id WHERE timestamp >= '" . $start_date . "' AND timestamp < '" . $end_date . "' AND action_type_id=4 GROUP BY object_id ORDER BY sum(value) DESC " . $limit;

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $upsell_id
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_upsell_data_by_id( $upsell_id, $start_date, $end_date ) {
			$range = '';

			if ( $start_date !== '' && $end_date !== '' ) {
				$range = " timestamp >= '" . $start_date . "' AND timestamp < '" . $end_date . "' AND ";
			}

			$query = "SELECT ";
			$query .= "SUM(IF(action_type_id=2, 1, 0)) as offers_viewed, ";
			$query .= "SUM(IF(action_type_id=4, 1, 0)) as offers_accepted, ";
			$query .= "SUM(IF(action_type_id=6, 1, 0)) as offers_rejected, ";
			$query .= "SUM(IF(action_type_id=9, 1, 0)) as offers_failed, ";
			$query .= "SUM(IF(action_type_id=7, 1, 0)) as offers_expired, ";
			$query .= "SUM(IF(action_type_id=4, value, 0)) as upsells, ";
			$query .= "SUM(IF(action_type_id=7, 1, 0)) + SUM(IF(action_type_id=9, 1, 0)) as offers_pending, ";
			$query .= "CONCAT(CAST( ROUND(SUM(IF(action_type_id=4, 1, 0)) * 100.0/ SUM(IF(action_type_id=2, 1, 0)) + SUM(IF(action_type_id=7, 1, 0)) + SUM(IF(action_type_id=9, 1, 0)), 2) as decimal(5,2)), '%') as conversations ";

			$query .= "FROM " . $this->wp_db->prefix . "wfocu_event WHERE " . $range . " object_id = " . $upsell_id . "";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $limit
		 * @param string $order
		 * @param string $order_by
		 *
		 * @return string
		 */
		public function get_timeline_data_query( $limit, $order = 'DESC', $order_by = 'date' ) {

			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			return "SELECT stats.object_id as id, sess.fid as 'fid', sess.cid as 'cid', sess.order_id as 'order_id', CONVERT( stats.value USING utf8) as 'total_revenue', 'upsell' as 'type', posts.post_title as 'post_title', stats.timestamp as date FROM " . $this->wp_db->prefix . "wfocu_event AS stats LEFT JOIN " . $this->wp_db->prefix . "wfocu_session AS sess ON stats.sess_id=sess.id LEFT JOIN " . $this->wp_db->prefix . "posts AS posts ON stats.object_id=posts.ID where ( stats.action_type_id = 4) AND sess.cid IS NOT NULL ORDER BY " . $order_by . " " . $order . " " . $limit;

		}

		/**
		 * @param $offer_id
		 *
		 * Get offers contact ids
		 *
		 * @return array|false[]|object|null
		 */
		public function get_cid_by_offer_id( $offer_id, $start_date = '' ) {
			$offer_id   = is_array( $offer_id ) ? implode( ',', $offer_id ) : $offer_id;
			$start_date = $start_date !== '' ? " AND session.timestamp >= '" . $start_date . "' " : '';

			$query    = "SELECT session.cid as 'cid' FROM " . $this->wp_db->prefix . "wfocu_session AS session INNER JOIN " . $this->wp_db->prefix . "wfocu_event AS event ON session.id=event.sess_id WHERE  event.action_type_id=4 AND event.object_id IN ( " . $offer_id . ") " . $start_date . " GROUP BY session.cid ORDER BY session.cid ASC";
			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $upsell_ids
		 *
		 * Get all accepted offer ids
		 *
		 * @return array|false[]|object|null
		 */
		public function get_accepted_offer_ids_by_upsell( $upsell_ids, $start_date = '' ) {
			$upsell_ids = is_array( $upsell_ids ) ? implode( ',', $upsell_ids ) : $upsell_ids;
			$start_date = $start_date !== '' ? " AND events.timestamp >= '" . $start_date . "' " : '';

			$query    = "SELECT events.object_id as 'offer' FROM " . $this->wp_db->prefix . "wfocu_event as events INNER JOIN " . $this->wp_db->prefix . "wfocu_event_meta AS event_meta ON(events.ID=event_meta.event_id) AND ((event_meta.meta_key='_funnel_id' AND event_meta.meta_value IN(" . $upsell_ids . "))) AND ( events.action_type_id='4' ) " . $start_date . " GROUP BY events.object_id ORDER BY events.object_id";
			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $limit
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_top_funnels( $limit = '', $date_query = '' ) {
			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';
			$date_query = str_replace( '{{COLUMN}}', 'sess.timestamp', $date_query );
			$query = "SELECT funnel.id as fid, funnel.title as title, stats.total as total FROM " . $this->wp_db->prefix . "bwf_funnels AS funnel 
			JOIN ( SELECT sess.fid as fid, SUM(ev.value) as total FROM " . $this->wp_db->prefix . "wfocu_event as ev 
			LEFT JOIN " . $this->wp_db->prefix . "wfocu_session as sess on sess.id = ev.sess_id 
			WHERE ev.action_type_id = 4 AND sess.fid != 0 AND sess.total > 0 AND ".$date_query." GROUP BY fid ) as stats ON funnel.id = stats.fid WHERE 1=1  GROUP BY funnel.id ORDER BY total DESC  " . $limit;

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
			$sales_ids = ( is_array( $sales_ids ) && count( $sales_ids ) > 0 ) ? " AND sess.fid IN (" . implode( ',', $sales_ids ) . ") " : '';
			$range     = ( '' !== $start_date && '' !== $end_date ) ? " AND ev.timestamp >= '" . $start_date . "' AND ev.timestamp < '" . $end_date . "' " : '';

			$query = "SELECT sess.fid as fid, '' as title, SUM(ev.value) as total FROM " . $this->wp_db->prefix . "wfocu_event as ev 
    			LEFT JOIN " . $this->wp_db->prefix . "wfocu_session as sess on sess.id = ev.sess_id 
            	WHERE ev.action_type_id = 4 AND sess.fid != 0 " . $sales_ids . " AND sess.total > 0 " . $range . " GROUP BY sess.fid ORDER BY sess.fid ASC ";

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
		 *
		 * @return array|false[]|true
		 */
		public function delete_contact( $cids, $funnel_id = 0 ) {

			$cid_count                = count( $cids );
			$stringPlaceholders       = array_fill( 0, $cid_count, '%s' );
			$placeholdersForFavFruits = implode( ',', $stringPlaceholders );
			$funnel_query             = ( absint( $funnel_id ) > 0 ) ? " AND fid = " . $funnel_id . " " : '';

			if ( ! class_exists( 'WFOCU_Core' ) ) {
				return true;
			}

			$query    = "SELECT id  FROM " . $this->wp_db->prefix . "wfocu_session WHERE cid IN (" . $placeholdersForFavFruits . ") " . $funnel_query;
			$sess_ids = $this->wp_db->get_results( $this->wp_db->prepare( $query, $cids ), ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			if ( is_array( $sess_ids ) && count( $sess_ids ) > 0 ) {
				foreach ( $sess_ids as $sess_id ) {
					WFOCU_Core()->session_db->delete( $sess_id['id'] );
				}
			}

			return true;
		}


		/**
		 * @param $contact_id
		 * @param $fid
		 *
		 * @return array|object|null
		 */
		public function get_contacts_data_crm( $contact_id, $fid ) {

			$fid = is_array( $fid ) ? implode( ',', $fid ) : $fid;

			$query = "SELECT p.post_title as title, session.fid as fid, (CASE WHEN action_type_id = 4 THEN `value` END) AS `total_revenue`, session.id as session_id, event.action_type_id FROM " . $this->wp_db->prefix . 'wfocu_session' . " AS session LEFT JOIN " . $this->wp_db->prefix . 'wfocu_event' . " AS event ON session.id=event.sess_id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON session.fid = p.id WHERE session.cid=$contact_id AND session.fid IN (" . $fid . ")";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contacts_records_crm( $cid ) {

			$query = "SELECT event.object_id,event.action_type_id,event.value,DATE_FORMAT(event.timestamp, '%Y-%m-%dT%TZ') as 'date',p.post_title as 'object_name','upsell' as 'type' FROM " . $this->wp_db->prefix . 'wfocu_event' . " as event LEFT JOIN " . $this->wp_db->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 4 OR event.action_type_id = 6 OR event.action_type_id = 7 OR event.action_type_id = 9 OR event.action_type_id = 10) AND session.cid=$cid order by session.timestamp asc";

			return $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $cids
		 *
		 * @return bool|false|int
		 */
		public function delete_contact_crm( $cids ) {

			$cid_count                = count( $cids );
			$stringPlaceholders       = array_fill( 0, $cid_count, '%s' );
			$placeholdersForFavFruits = implode( ',', $stringPlaceholders );

			$query = "DELETE FROM " . $this->wp_db->prefix . "wfocu_session WHERE cid IN (" . $placeholdersForFavFruits . ") AND";

			return $this->wp_db->query( $this->wp_db->prepare( $query, $cids ) );
		}

		/**
		 * @param $funnel_id
		 */
		public function reset_analytics( $funnel_id ) {

			if ( ! class_exists( 'WFOCU_Core' ) ) {
				return;
			}
			$query    = "SELECT id  FROM " . $this->wp_db->prefix . "wfocu_session WHERE fid =" . $funnel_id;
			$sess_ids = $this->wp_db->get_results( $query, ARRAY_A );
			if ( is_array( $sess_ids ) && count( $sess_ids ) > 0 ) {
				foreach ( $sess_ids as $sess_id ) {
					WFOCU_Core()->session_db->delete( $sess_id['id'] );
				}
			}
			$query = "DELETE FROM " . $this->wp_db->prefix . "wfocu_session WHERE fid=" . $funnel_id;
			$this->wp_db->query( $query );

			$all_upsell_funnel_ids = [];
			$funnel                = new WFFN_Funnel( $funnel_id );
			$rest_API              = WFFN_REST_API_EndPoint::get_instance();
			if ( $funnel instanceof WFFN_Funnel && 0 < $funnel->get_id() ) {
				$get_steps = $funnel->get_steps();
				$get_steps = $rest_API::get_instance()->maybe_add_ab_variants( $get_steps );
				foreach ( $get_steps as $step ) {
					if ( $step['type'] === 'wc_upsells' ) {
						array_push( $all_upsell_funnel_ids, $step['id'] );
					}
				}
			}


			if ( count( $all_upsell_funnel_ids ) > 0 ) {
				$get_all_upsell_events_left = $this->wp_db->get_results( "DELETE eventss FROM " . $this->wp_db->prefix . "wfocu_event as eventss
INNER JOIN " . $this->wp_db->prefix . "wfocu_event_meta AS events_meta__funnel_id ON ( eventss.ID = events_meta__funnel_id.event_id ) 
 

			                        AND
( ( events_meta__funnel_id.meta_key   = '_funnel_id' AND events_meta__funnel_id.meta_value IN (" . implode( ',', $all_upsell_funnel_ids ) . ") ))" );

			}


		}
	}
}