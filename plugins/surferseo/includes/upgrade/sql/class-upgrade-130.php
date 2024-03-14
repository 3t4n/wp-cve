<?php
/**
 * Abstract class to define SQL upgrade methods
 *
 * @package SurferSEO
 */

namespace SurferSEO\Upgrade\SQL;

use SurferSEO\Upgrade\Database_Upgrade;

/**
 * Update database to version 130
 */
class Upgrade_130 extends Database_Upgrade {


	/**
	 * Construct to prepare SQLs
	 */
	public function __construct() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'surfer_gsc_traffic';

		$surfer_gsc_traffic = '
     CREATE TABLE IF NOT EXISTS ' . $table_name . ' (
     id bigint(20) NOT NULL AUTO_INCREMENT,
     post_id bigint(20) NOT NULL,
     position int(11),
     position_change int(11),
     clicks int(11),
     clicks_change int(11),
     impressions int(11),
     impressions_change int(11),
     data_gathering_date datetime NOT NULL,
     period_start_date date NOT NULL,
     period_end_date date NOT NULL,
     PRIMARY KEY  (id)
     ) ' . $charset_collate . ';';

		$this->sql[ $table_name ] = $surfer_gsc_traffic;
	}
}
