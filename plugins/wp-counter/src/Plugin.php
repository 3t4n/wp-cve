<?php
/**
 * Plugin Initiator
 *
 * @package Haruncpi\WpCounter
 * @author Harun<harun.cox@gmail.com>
 * @link https://learn24bd.com
 * @since 1.2
 */

namespace Haruncpi\WpCounter;

/**
 * Plugin Class
 *
 * @since 1.2
 */
class Plugin {
	/**
	 * Constructor
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function __construct() {

	}

	/**
	 * Init function.
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function init() {
		new Assets();
		new DashboardWidget();
		new Shortcode();
		new Counter();
	}

	/**
	 * Invoke when plugin activated.
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function on_plugin_active() {
		$this->create_tables();
	}

	/**
	 * Create db tables
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	private function create_tables() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table_name = DB::get_table_visitor_table_name();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name(
			id int(10) unsigned NOT NULL AUTO_INCREMENT, 
			visit_date date NOT NULL, 
			visit_time time NOT NULL, 
			ip varchar(255) COLLATE utf8_unicode_ci NOT NULL, 
			hits int(11) NOT NULL, 
			PRIMARY KEY (id)
		  )";
		dbDelta( $sql );
	}
}
