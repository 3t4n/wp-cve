<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_Report_Table' ) ) {
	class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Report_Table {
		/**
		 * Create table
		 */
		public static function create_table() {
			global $wpdb;
			$table = $wpdb->prefix . 'vi_wcuf_order_info';
			$query = "CREATE TABLE IF NOT EXISTS {$table} (
                             `id` bigint(20) NOT NULL AUTO_INCREMENT,
                             `order_id` bigint(20),
                             `customer_id` bigint(20),
                             `customer_email` VARCHAR(50) NOT NULL,
                             `us_info` LONGTEXT,
                             `ob_info` LONGTEXT,
                             `create_at` DATETIME,
                             PRIMARY KEY  (`id`)
                             )";
			$wpdb->query( $query );
		}

		/**Insert data to table
		 * @return int|bool
		 */
		public static function insert( $order_id,  $customer_email, $create_at, $customer_id = 0, $us_info = '', $ob_info = '' ) {
			if ( ! $order_id || ! $customer_email || ! $create_at ) {
				return false;
			}
			global $wpdb;
			$table = $wpdb->prefix . 'vi_wcuf_order_info';
			$wpdb->insert( $table,
				array(
					'order_id'       => $order_id,
					'customer_id'    => $customer_id,
					'customer_email' => $customer_email,
					'us_info'        => $us_info,
					'ob_info'        => $ob_info,
					'create_at'      => $create_at
				),
				array(
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
				)
			);
		}
		public static function update_by_order_id( $order_id, $update) {
			if (!$order_id || empty($update)){
				return;
			}
			global $wpdb;
			$table = $wpdb->prefix . 'vi_wcuf_order_info';
			$wpdb->update( $table,
				$update,
				array(
					'order_id' => $order_id,
				)
			);
		}

		/**Get row
		 * @return array|null|object
		 */
		public static function get_row($type='', $customer_id=0,$customer_email = '',$start_date = '', $end_date = '') {
			global $wpdb;
			$table = $wpdb->prefix . 'vi_wcuf_order_info';
			$query = "SELECT * FROM {$table} WHERE 1=1 ";
			$arg=array();
			if ($type){
				$query .= ' AND '.$type.'!= ""';
			}
			if ($customer_id){
				$query .= ' AND customer_id = %d';
				$arg[] = $customer_id;
			}
			if ($customer_email){
				$query .= ' AND customer_email = %s';
				$arg[] = $customer_email;
			}
			if ($start_date){
				$query .= ' AND create_at >= %s';
				$arg[] = $start_date;
			}
			if ($end_date){
				$query .= ' AND create_at < %s';
				$arg[] = $end_date;
			}
			if (count($arg)){
				return $wpdb->get_results( $wpdb->prepare( $query,$arg),ARRAY_A);
			}else{
				return $wpdb->get_results( $query,ARRAY_A);
			}
		}

		/**Get row by order_id
		 *
		 * @param $id
		 *
		 * @return array|null|object
		 */
		public static function get_row_by_order_id( $order_id ) {
			global $wpdb;
			$table = $wpdb->prefix . 'vi_wcuf_order_info';
			$query = "SELECT * FROM {$table} WHERE order_id=%d";
			return $wpdb->get_row( $wpdb->prepare( $query, $order_id ), ARRAY_A );
		}
		/**Delete row
		 * @return false|int
		 */
		public static function delete( $col_name ,$value, $format) {
			global $wpdb;
			$table  = $wpdb->prefix . 'vi_wcuf_order_info';
			$delete = $wpdb->delete( $table,
				array(
					$col_name => $value,
				),
				array(
					$format,
				)
			);

			return $delete;
		}
		/**Delete row by date
		 * @return false|int
		 */
		public static function delete_by_date( $date = 0) {
			global $wpdb;
			$table  = $wpdb->prefix . 'vi_wcuf_order_info';
			$query = "DELETE FROM {$table}";
			if ($date){
				$date = date( 'Y-m-d',strtotime('-'.$date.' days') );
				$query .='where create_at < '.$date;
			}
			$results = $wpdb->get_results( $query );
			return $results;
		}
	}
}