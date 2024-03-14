<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFOB_Contacts_Analytics
 */
if ( ! class_exists( 'WFOB_Contacts_Analytics' ) ) {

	class WFOB_Contacts_Analytics {

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
		 * WFOB_Contacts_Analytics constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->wp_db = $wpdb;
		}

		/**
		 * @return WFOB_Contacts_Analytics|null
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
		public function get_contacts_data( $funnel_id, $cid ) {
			$cid      = is_array( $cid ) ? implode( ',', $cid ) : $cid;
			$query    = "SELECT  bump.cid as cid,  SUM(bump.total) as total_revenue, COUNT(id) as count_orders FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump WHERE bump.fid=$funnel_id AND bump.cid IN (" . $cid . ") AND bump.converted = 1 GROUP BY cid";
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
		public function get_contacts_data_global( $cid ) {
			$cid      = is_array( $cid ) ? implode( ',', $cid ) : $cid;
			$query    = "SELECT  bump.cid as cid,  SUM(bump.total) as total_revenue, COUNT(id) as count_orders FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump WHERE bump.fid != 0 AND bump.cid IN (" . $cid . ") AND bump.converted = 1 GROUP BY cid";
			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		public function get_contact_conversions_by_bump( $bump_id, $cid = '', $is_converted = 0, $limit = '' ) {
			$cid   = is_array( $cid ) ? implode( ',', $cid ) : $cid;
			$query = "SELECT  bump.cid as cid,  SUM(bump.total) as total_revenue, COUNT(id) as count_orders FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump WHERE bump.bid IN (" . $bump_id . ") AND bump.cid IN (" . $cid . ") AND bump.converted = $is_converted GROUP BY cid";
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
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contacts_data( $cid, $bump_id = false ) {
			$cid   = is_array( $cid ) ? implode( ',', $cid ) : $cid;
			$query = "SELECT bump.oid as order_id, bump.cid, DATE_FORMAT(bump.date, '%Y-%m-%dT%TZ') as 'date', bump.total as total_revenue, bump.total as bump_revenue, bump.bid, bump.converted as converted FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump WHERE bump.cid IN (" . $cid . ")";

			if ( true == $bump_id ) {
				$query = "SELECT bump.bid FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump WHERE bump.cid IN (" . $cid . ")";
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
		public function get_all_contacts_records( $funnel_id, $cid ) {
			$item_data = [];
			$query     = "SELECT bump.oid as order_id, bump.bid as 'object_id', bump.iid as 'item_ids', bump.total as 'total_revenue',p.post_title as 'object_name', bump.converted as 'is_converted',DATE_FORMAT(bump.date, '%Y-%m-%dT%TZ') as 'date','bump' as 'type' FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.bid  = p.id  WHERE bump.converted = 1 AND bump.fid=$funnel_id AND bump.cid=$cid order by bump.date asc";

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

			$query = "SELECT bump.fid as fid, bump.oid as order_id, bump.bid as 'object_id',bump.total as 'total_revenue',p.post_title as 'object_name', bump.converted as 'is_converted',DATE_FORMAT(bump.date, '%Y-%m-%d %T') as 'date','bump' as 'type' FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.bid  = p.id  WHERE bump.converted = 1 AND bump.oid IN ( $order_ids ) AND bump.cid=$cid order by bump.date asc";

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		public function get_bumps_by_order_id( $order_id ) {


			$query    = "SELECT bump.bid as 'id', p.post_title as 'bump_name', '' as 'bump_products', if(bump.converted=1, 'Yes', 'No') as 'bump_converted', bump.oid as 'bump_order_id', bump.total as 'bump_total' FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.bid  = p.id WHERE  bump.oid='{$order_id}' order by bump.date asc";
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
		public function get_all_contact_record_by_cid( $cid ) {

			$query = "SELECT stats.oid as order_id, bump.bid as 'object_id',bump.total as 'total_revenue',p.post_title as 'object_name', bump.converted as 'is_converted',DATE_FORMAT(bump.date, '%Y-%m-%dT%TZ') as 'date','bump' as 'type' FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.bid  = p.id LEFT JOIN " . $this->wp_db->prefix . 'wfob_stats' . " as stats on stats.bid = bump.bid WHERE bump.converted = 1 AND bump.cid=$cid order by bump.date asc";

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

			$query    = "SELECT bump.bid as 'id', p.post_title as 'bump_name', '' as 'bump_products', if(bump.converted=1, 'Yes', 'No') as 'bump_converted', bump.oid as 'bump_order_id', bump.total as 'bump_total' FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.bid  = p.id WHERE bump.bid IN ($step_ids) AND bump.fid=$funnel_id AND bump.cid=$cid order by bump.date asc";
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
		public function export_contacts_records_by_contact( $cid, $funnel_id = '' ) {
			$funnel_query = ( '' !== $funnel_id && $funnel_id > 0 ) ? " AND bump.fid = $funnel_id " : '';
			$query        = "SELECT bump.bid as 'id', p.post_title as 'bump_name', '' as 'bump_products', if(bump.converted=1, 'Yes', 'No') as 'bump_converted', bump.oid as 'bump_order_id', bump.total as 'bump_total' FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.bid  = p.id WHERE  bump.cid=$cid " . $funnel_query . " order by bump.date asc";
			$data         = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error     = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return false;
			}

			return $data;
		}

		public function get_records_by_date_range( $funnel_id, $start_date, $end_date ) {

			$query = "SELECT bump.bid as 'object_id',COUNT(CASE WHEN converted = 1 THEN 1 END) AS `converted`, p.post_title as 'object_name',SUM(bump.total) as 'total_revenue',COUNT(bump.ID) as viewed, 'bump' as 'type' FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.bid  = p.id WHERE bump.fid =$funnel_id AND date BETWEEN '" . $start_date . "' AND '" . $end_date . "' GROUP by bump.bid ASC";

			return $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

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

			$query = "SELECT  SUM(total) as sum_bump " . $interval_query . "  FROM `" . $this->wp_db->prefix . "wfob_stats` WHERE 1=1 AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' AND fid = " . $funnel_id . " " . $group_by . " ORDER BY id ASC";

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

			$query    = "SELECT  SUM(total) as sum_bump " . $interval_query . " FROM `" . $this->wp_db->prefix . "wfob_stats` WHERE 1=1 " . $date . $funnel_id . $group_by . " ORDER BY id DESC";
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
		public function get_top_bumps( $start_date, $end_date, $limit = '' ) {

			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			$query = "SELECT bid, sum(total) as revenue,COUNT(bmp.id) as conversion,p.post_title as title FROM `" . $this->wp_db->prefix . "wfob_stats` as bmp LEFT JOIN " . $this->wp_db->prefix . "posts as p ON p.id = bmp.bid WHERE date >= '" . $start_date . "' AND date < '" . $end_date . "' AND bmp.converted = 1 GROUP BY bid ORDER BY sum(total) DESC" . $limit;

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		/**
		 * @param $bump_id
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_bump_data_by_id( $bump_id, $start_date, $end_date ) {
			$range = '';

			if ( $start_date !== '' && $end_date !== '' ) {
				$range = " date >= '" . $start_date . "' AND date < '" . $end_date . "' AND ";
			}

			$query = "SELECT ";
			$query .= "COUNT(bid) as viewed, ";
			$query .= "SUM(total) as total_revenue, ";
			$query .= "SUM(IF(converted=1, 1, 0)) as converted, ";
			$query .= "CONCAT(CAST( ROUND(SUM(total) / COUNT(bid), 2) as decimal(5,2)), '%') as avg_revenue, ";
			$query .= "CONCAT(CAST( ROUND(SUM(IF(converted=1, 1, 0)) * 100.0 / COUNT(bid), 2) as decimal(5,2)), '%') as conversion ";

			$query .= "FROM " . $this->wp_db->prefix . "wfob_stats WHERE " . $range . " bid = " . $bump_id . "";

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

			return "SELECT stats.bid as id, stats.fid as 'fid', stats.cid as 'cid', oid as 'order_id', CONVERT( stats.total USING utf8) as 'total_revenue', 'bump' as 'type', posts.post_title as 'post_title', stats.date as date FROM " . $this->wp_db->prefix . "wfob_stats AS stats LEFT JOIN " . $this->wp_db->prefix . "posts AS posts ON stats.bid=posts.ID WHERE stats.converted = 1 ORDER BY " . $order_by . " " . $order . " " . $limit;

		}

		/**
		 * @param $limit
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_top_funnels( $limit = '', $date_query = '' ) {
			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';
			$date_query = str_replace( '{{COLUMN}}', 'wfob_stats.date', $date_query );
			$query = "SELECT funnel.id as fid, funnel.title as title, stats.total as total FROM " . $this->wp_db->prefix . "bwf_funnels AS funnel 
    				JOIN ( SELECT fid, SUM( total ) as total FROM " . $this->wp_db->prefix . "wfob_stats as wfob_stats 
    				WHERE fid != 0 AND converted = 1 AND ".$date_query."  GROUP BY fid ) as stats ON funnel.id = stats.fid WHERE 1 = 1 GROUP BY funnel.id ORDER BY total DESC " . $limit;

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

			$query    = "SELECT stats.fid as fid, '' as title, SUM(stats.total) as total FROM  " . $this->wp_db->prefix . "wfob_stats AS stats 
                WHERE 1=1 AND stats.fid != 0 " . $sales_ids . " AND stats.converted = 1 " . $range . " GROUP BY stats.fid ORDER BY stats.fid ASC ";
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

			$funnel_query = ( absint( $funnel_id ) > 0 ) ? " AND fid = " . $funnel_id . " " : '';
			$query        = "DELETE FROM " . $this->wp_db->prefix . "wfob_stats WHERE cid IN (" . $placeholdersForFavFruits . ") " . $funnel_query;

			$this->wp_db->query( $this->wp_db->prepare( $query, $cids ) ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
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

			$query = "SELECT p.post_title as title, bump.fid as fid, bump.bid, bump.converted as converted FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.fid  = p.id WHERE bump.cid=$contact_id AND bump.fid IN (" . $fid . ")";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contacts_records_crm( $cid ) {

			$query = "SELECT p.post_title as 'name', bump.fid as 'funnel', bump.total as 'amount', bump.oid as 'order', DATE_FORMAT(bump.date, '%Y-%m-%dT%TZ') as 'date' FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.bid = p.id WHERE bump.cid=$cid order by bump.fid asc";

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

			$query = "DELETE FROM " . $this->wp_db->prefix . "wfob_stats WHERE cid IN (" . $placeholdersForFavFruits . ")";

			return $this->wp_db->query( $this->wp_db->prepare( $query, $cids ) );
		}

		/**
		 * @param $funnel_id
		 */
		public function reset_analytics( $funnel_id ) {
			$query = "DELETE FROM " . $this->wp_db->prefix . "wfob_stats WHERE fid=" . $funnel_id;
			$this->wp_db->query( $query );
		}
	}
}