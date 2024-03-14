<?php
/**
 * Counter
 *
 * @package Haruncpi\WpCounter
 * @author Harun<harun.cox@gmail.com>
 * @link https://learn24bd.com
 * @since 1.2
 */

namespace Haruncpi\WpCounter;

/**
 * Counter Class
 *
 * @since 1.2
 */
class Counter {
	/**
	 * Register hooks.
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'do_count' ) );
	}

	/**
	 * Count visitors.
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function do_count() {
		global $wpdb;
		$table_name  = DB::get_table_visitor_table_name();
		$date        = date( 'Y-m-d' );
		$ip          = $_SERVER['REMOTE_ADDR'];
		$totalRecord = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $table_name WHERE ip= %s AND visit_date=%s",
				$ip,
				$date
			)
		);

		if ( 0 === (int) $totalRecord ) {
			$this->insert_data();
		}
	}

	/**
	 * Insert visitor count data to database.
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	private function insert_data() {
		$table_name = DB::get_table_visitor_table_name();

		global $wpdb;
		$wpdb->insert(
			$table_name,
			array(
				'visit_date' => date( 'Y-m-d' ),
				'visit_time' => date( 'H:i:s' ),
				'ip'         => $_SERVER['REMOTE_ADDR'],
				'hits'       => 1,
			)
		);
	}
}
