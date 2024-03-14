<?php
/**
 * Database Helper Class
 *
 * @package Haruncpi\WpCounter
 * @author Harun<harun.cox@gmail.com>
 * @link https://learn24bd.com
 * @since 1.2
 */

namespace Haruncpi\WpCounter;

/**
 * DB Class
 *
 * @since 1.2
 */
class DB {
	/**
	 * Visitor table name
	 *
	 * @since 1.2
	 *
	 * @return string
	 */
	public static function get_table_visitor_table_name() {
		global $wpdb;
		return $wpdb->prefix . 'l24bd_wpcounter_visitors';
	}

	/**
	 * Get visitor data
	 *
	 * @since 1.2
	 *
	 * @return array
	 */
	public static function get_visitor_data() {
		global $wpdb;
		$table_name = self::get_table_visitor_table_name();
		$data       = array();

		$sql   = "SELECT SUM(hits) FROM $table_name WHERE visit_date='" . getToday() . "'";
		$today = $wpdb->get_var( $sql );

		$sql       = "SELECT SUM(hits) FROM $table_name WHERE visit_date='" . getYesterday() . "'";
		$yesterday = $wpdb->get_var( $sql );

		$sql      = "SELECT SUM(hits) FROM $table_name WHERE visit_date between '" . getLast( 'week', 'first' ) . "' and '" . getCurrent( 'week', 'last' ) . "'";
		$thisWeek = $wpdb->get_var( $sql );

		$sql       = "SELECT SUM(hits) FROM $table_name WHERE visit_date between '" . getCurrent( 'month', 'first' ) . "' and '" . getCurrent( 'month', 'last' ) . "'";
		$thisMonth = $wpdb->get_var( $sql );

		$sql          = "SELECT SUM(hits) FROM $table_name";
		$totalVisitor = $wpdb->get_var( $sql );

		$data['today']        = $today;
		$data['yesterday']    = $yesterday;
		$data['thisWeek']     = $thisWeek;
		$data['thisMonth']    = $thisMonth;
		$data['totalVisitor'] = $totalVisitor;

		return $data;
	}

	/**
	 * Get visitor graph data
	 *
	 * @since 1.2
	 *
	 * @return mixed
	 */
	public static function get_visitor_graph_data() {
		global $wpdb;
		$table_name = self::get_table_visitor_table_name();
		$sql        = "SELECT visit_date,SUM(hits) AS total FROM $table_name GROUP BY visit_date ORDER BY visit_date DESC LIMIT %d";

		return $wpdb->get_results( $wpdb->prepare( $sql, 7 ) );
	}
}
