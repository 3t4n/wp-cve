<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_Optin_Contacts_Analytics
 */
if ( ! class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {

	class WFFN_Optin_Contacts_Analytics {

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
		 * WFFN_Optin_Contacts_Analytics constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->wp_db = $wpdb;
		}

		/**
		 * @return WFFN_Optin_Contacts_Analytics|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		/**
		 * @param $funnel_id
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return mixed
		 */
		public function get_all_optin_records( $funnel_id, $start_date, $end_date ) {
			$range = '';
			if ( $start_date !== '' && $end_date !== '' ) {
				$range = " AND date BETWEEN '" . $start_date . "' AND '" . $end_date . "' ";
			}

			$query = "SELECT optin.step_id as 'object_id', p.post_title as 'object_name','0' as 'total_revenue',COUNT(optin.id) as cn FROM " . $this->wp_db->prefix . 'bwf_optin_entries' . " AS optin LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON optin.step_id  = p.id WHERE optin.funnel_id=" . $funnel_id . " " . $range . " GROUP by optin.step_id ASC";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $funnel_id
		 * @param string $search
		 *
		 * @return array|object|null
		 */
		public function get_contacts( $funnel_id, $search = '' ) {

			if ( ! empty( $search ) ) {
				$query = "SELECT contact.id as cid, contact.f_name, contact.l_name, contact.email, optin.date, optin.opid FROM " . $this->wp_db->prefix . 'bwf_contact' . " AS contact JOIN " . $this->wp_db->prefix . 'bwf_optin_entries' . " AS optin ON contact.id=optin.cid WHERE optin.funnel_id=$funnel_id";

				global $wpdb;
				$query .= $wpdb->prepare( " AND (contact.f_name LIKE %s OR contact.email LIKE %s) group by contact.id", "%" . $search . "%", "%" . $search . "%" );

			} else {
				$query = "SELECT optin.cid FROM " . $this->wp_db->prefix . 'bwf_optin_entries' . " AS optin WHERE optin.funnel_id=$funnel_id";
			}

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $funnel_id
		 * @param $filtered_ids
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_contacts_data( $funnel_id, $filtered_ids ) {
			$get_total_possible_contacts_str = implode( ',', $filtered_ids );
			$query                           = "SELECT optin.cid as cid,  DATE_FORMAT(optin.date, '%Y-%m-%dT%TZ') as 'date', optin.opid FROM " . $this->wp_db->prefix . 'bwf_optin_entries' . " AS optin WHERE optin.funnel_id=$funnel_id AND `cid` IN (" . $get_total_possible_contacts_str . ")";

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $filtered_ids
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_all_contacts_data( $filtered_ids ) {
			$get_total_possible_contacts_str = implode( ',', $filtered_ids );
			$query                           = "SELECT optin.cid as cid,  DATE_FORMAT(optin.date, '%Y-%m-%dT%TZ') as 'date', optin.opid FROM " . $this->wp_db->prefix . 'bwf_optin_entries' . " AS optin WHERE `cid` IN (" . $get_total_possible_contacts_str . ")";

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

			$query = "SELECT optin.step_id as 'object_id',optin.data as 'data',DATE_FORMAT(optin.date, '%Y-%m-%dT%TZ') as 'date',p.post_title as 'object_name', 'optin' as 'type' FROM " . $this->wp_db->prefix . 'bwf_optin_entries' . " as optin LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON optin.step_id  = p.id WHERE optin.funnel_id=$funnel_id AND optin.cid= $cid  order by optin.date asc";

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		public function get_contacts_optin_records( $cid, $entry_ids ) {
			$query = "SELECT optin.id, optin.funnel_id as fid, optin.email as email, optin.step_id as 'object_id', optin.data as 'data',DATE_FORMAT(optin.date, '%Y-%m-%d %T') as 'date', COALESCE( p.post_title, '' ) as 'object_name', 'optin' as 'type' FROM " . $this->wp_db->prefix . 'bwf_optin_entries' . " as optin LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON optin.step_id  = p.id WHERE optin.id IN ( $entry_ids ) AND optin.cid= $cid  order by optin.date asc";

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

			$query = "SELECT optin.step_id as 'object_id',optin.data as 'data',DATE_FORMAT(optin.date, '%Y-%m-%dT%TZ') as 'date',p.post_title as 'object_name', 'optin' as 'type' FROM " . $this->wp_db->prefix . 'bwf_optin_entries' . " as optin LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON optin.step_id  = p.id WHERE optin.cid= $cid  order by optin.date asc";

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		/**
		 * @param $funnel_id
		 * @param $cid
		 * @param $step_ids
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function export_contacts_records( $funnel_id, $cid, $step_ids ) {
			$step_ids = is_array( $step_ids ) ? implode( ',', $step_ids ) : $step_ids;
			$query    = "SELECT optin.step_id as 'id',optin.data as 'data',DATE_FORMAT(optin.date, '%Y-%m-%dT%TZ') as 'date',p.post_title as 'object_name', 'optin' as 'type' FROM " . $this->wp_db->prefix . 'bwf_optin_entries' . " as optin LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON optin.step_id  = p.id WHERE optin.step_id IN ($step_ids) AND optin.funnel_id=$funnel_id AND optin.cid= $cid  order by optin.date asc";

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
			$funnel_query = ( '' !== $funnel_id && $funnel_id > 0 ) ? " AND optin.funnel_id = $funnel_id " : '';
			$query        = "SELECT optin.step_id as 'id',optin.data as 'data',DATE_FORMAT(optin.date, '%Y-%m-%dT%TZ') as 'date',p.post_title as 'object_name', 'optin' as 'type' FROM " . $this->wp_db->prefix . 'bwf_optin_entries' . " as optin LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON optin.step_id  = p.id WHERE  optin.cid= $cid " . $funnel_query . " order by optin.date asc";

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return false;
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
		public function get_top_optins( $start_date, $end_date, $limit = '' ) {

			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			$query = "SELECT step_id, count(op.id) as conversion,p.post_title as title from  " . $this->wp_db->prefix . "bwf_optin_entries as op LEFT JOIN " . $this->wp_db->prefix . "posts as p ON p.id = op.step_id WHERE  1=1 AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' GROUP BY step_id ORDER BY conversion DESC " . $limit;

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $limit
		 * @param string $order
		 * @param string $order_by
		 *
		 * @return string
		 */
		public function get_timeline_data_query( $limit, $order = 'DESC', $order_by = 'date', $can_union = true ) {

			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			if ( $can_union ) {
				return "SELECT stats.step_id as id, stats.funnel_id as 'fid', stats.cid as 'cid', '0' as 'order_id', '0' as 'total_revenue', 'optin' as 'type', posts.post_title as 'post_title', stats.date as date FROM " . $this->wp_db->prefix . "bwf_optin_entries AS stats LEFT JOIN " . $this->wp_db->prefix . "posts AS posts ON stats.step_id=posts.ID ORDER BY " . $order_by . " " . $order . " " . $limit;

			} else {
				return "SELECT stats.step_id as id, stats.funnel_id as 'fid', stats.cid as 'cid', '0' as 'order_id', '0' as 'total_revenue', 'optin' as 'type', posts.post_title as 'post_title',contact.f_name as f_name, contact.l_name as l_name, stats.date as date FROM " . $this->wp_db->prefix . "bwf_optin_entries AS stats LEFT JOIN " . $this->wp_db->prefix . "posts AS posts ON stats.step_id=posts.ID LEFT JOIN " . $this->wp_db->prefix . "bwf_contact AS contact ON contact.id=cid WHERE contact.id != '' ORDER BY " . $order_by . " " . $order . " " . $limit;

			}

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

			$funnel_query = ( absint( $funnel_id ) > 0 ) ? " AND fid = " . $funnel_id . " " : '';

			$e_query = "DELETE FROM " . $this->wp_db->prefix . "bwf_optin_entries WHERE cid IN (" . $placeholdersForFavFruits . ") " . $funnel_query;
			$this->wp_db->query( $this->wp_db->prepare( $e_query, $cids ) ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

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
				$query = "SELECT p.post_title as title, optin.funnel_id as fid, optin.opid FROM " . $this->wp_db->prefix . 'bwf_contact' . " AS contact JOIN " . $this->wp_db->prefix . 'bwf_optin_entries' . " AS optin ON contact.id=optin.cid LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON optin.funnel_id  = p.id WHERE optin.cid=$contact_id";

				global $wpdb;
				$query .= $wpdb->prepare( " AND (contact.f_name LIKE %s OR contact.email LIKE %s) group by contact.id", "%" . $search . "%", "%" . $search . "%" );

			} else {
				$query = "SELECT p.post_title as title, optin.funnel_id as fid FROM " . $this->wp_db->prefix . 'bwf_optin_entries' . " AS optin LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON optin.funnel_id  = p.id WHERE optin.cid=$contact_id";
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

			$query = "SELECT p.post_title as title, optin.funnel_id as fid, optin.opid FROM " . $this->wp_db->prefix . 'bwf_contact' . " AS contact JOIN " . $this->wp_db->prefix . 'bwf_optin_entries' . " AS optin ON contact.id=optin.cid LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON optin.funnel_id  = p.id WHERE optin.cid=$contact_id";

			if ( ! empty( $search ) ) {
				global $wpdb;
				$query .= $wpdb->prepare( " AND (contact.f_name LIKE %s OR contact.email LIKE %s)", "%" . $search . "%", "%" . $search . "%" );
			}
			if ( ! empty( $orderby ) ) {
				$query .= " ORDER BY $orderby $order";
			}
			if ( ! empty( $limit ) ) {
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

			$query = "SELECT p.post_title as 'name', optin.funnel_id as 'funnel', optin.data as 'entry', DATE_FORMAT(optin.date, '%Y-%m-%dT%TZ') as 'date' FROM " . $this->wp_db->prefix . 'bwf_optin_entries' . " as optin LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON optin.step_id  = p.id WHERE optin.cid= $cid  order by optin.funnel_id asc";

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

			$query = "DELETE FROM " . $this->wp_db->prefix . "bwf_optin_entries WHERE cid IN (" . $placeholdersForFavFruits . ")";

			return $this->wp_db->query( $this->wp_db->prepare( $query, $cids ) );
		}

		public function get_contacts_by_funnel( $funnel_id ) {
			return "SELECT DISTINCT cid as id FROM " . $this->wp_db->prefix . "bwf_optin_entries WHERE funnel_id = " . $funnel_id . "";

		}

		public function get_contacts_all() {
			return "SELECT DISTINCT cid as id FROM " . $this->wp_db->prefix . "bwf_optin_entries";

		}

		public function get_contacts_by_ids( $ids, $start_date = '' ) {
			$get_total_possible_contacts_str = implode( ',', $ids );
			$start_date                      = $start_date !== '' ? " AND date >= '" . $start_date . "' " : '';

			$query = "SELECT DISTINCT cid FROM " . $this->wp_db->prefix . "bwf_optin_entries WHERE step_id IN (" . $get_total_possible_contacts_str . ")" . $start_date;

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		public function get_cid_by_page_for_multiple_id( $ids, $offset, $limit ) {
			$get_total_possible_contacts_str = implode( ',', $ids );

			$query = "SELECT DISTINCT cid from `" . $this->wp_db->prefix . "bwf_optin_entries` WHERE step_id IN (" . $get_total_possible_contacts_str . ") GROUP BY cid  ORDER BY cid  ASC LIMIT " . $offset . "," . $limit . " ";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

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

			$query    = "SELECT DISTINCT cid " . $interval_query . " FROM `" . $this->wp_db->prefix . "bwf_optin_entries` WHERE 1=1 " . $date . $group_by . " ORDER BY cid ASC";
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
		 *
		 * @return string
		 */
		public function get_contacts_by_funnel_id( $funnel_id, $start_date, $end_date, $is_interval = '' ) {
			$date           = ( '' !== $start_date && '' !== $end_date ) ? " AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' " : '';
			$funnel_query   = ( 0 === intval( $funnel_id ) ) ? " AND funnel_id != " . $funnel_id . " " : " AND funnel_id = " . $funnel_id . " ";
			$interval_param = ! empty( $is_interval ) ? ', date as p_date ' : '';

			return "SELECT DISTINCT cid as contacts " . $interval_param . " FROM `" . $this->wp_db->prefix . "bwf_optin_entries` WHERE 1=1 " . $date . " " . $funnel_query;
		}

		/**
		 * @param $funnel_id
		 */
		public function reset_analytics( $funnel_id ) {
			$query = "DELETE FROM " . $this->wp_db->prefix . "bwf_optin_entries WHERE funnel_id=" . $funnel_id;
			$this->wp_db->query( $query );
		}

		/**
		 * @param $entry_ids
		 *
		 * @return array|false[]|void
		 */
		public function delete_optin_entries( $entry_ids ) {
			$entry_ids = is_array( $entry_ids ) ? implode( ',', $entry_ids ) : $entry_ids;
			if ( empty( $entry_ids ) ) {
				return;
			}
			$e_query = "DELETE FROM " . $this->wp_db->prefix . "bwf_optin_entries WHERE id IN (" . $entry_ids . ")";
			$this->wp_db->query( $e_query );

			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			do_action( 'wffn_delete_optin_entries', $entry_ids );

		}

	}
}