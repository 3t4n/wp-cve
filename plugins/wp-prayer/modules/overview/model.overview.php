<?php
/**
 * Class: WPE_Model_Overview
 * @author Flipper Code <hello@flippercode.com>
 * @version 3.0.0
 * @package Maps
 */
if ( ! class_exists( 'WPE_Model_Overview' ) ) {
	/**
	 * Overview model for Plugin Overview.
	 * @package Maps
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class WPE_Model_Overview extends FlipperCode_WPE_Model_Base {
		/**
		 * Intialize Backup object.
		 */
		function __construct() {
		}
		/**
		 * Admin menu for Settings Operation
		 */
		function navigation() {
		}
		/**
		 * Install table associated with Prayer entity.
		 * @return string SQL query to install map_prayers table.
		 */
		public function install() {
			global $wpdb;
			$map_prayer = 'CREATE TABLE '.$wpdb->prefix.'prayer_engine (
				prayer_id int(11) NOT NULL AUTO_INCREMENT,
				prayer_title varchar(255) DEFAULT NULL,
				prayer_author int(11) DEFAULT NULL,
				prayer_messages text DEFAULT NULL,
				prayer_author_email varchar(255) DEFAULT NULL,
				prayer_author_name varchar(255) DEFAULT NULL,
				request_type ENUM("prayer_request","praise_report") NOT NULL DEFAULT "prayer_request",
				prayer_status varchar(255) NOT NULL DEFAULT "pending",
				prayer_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				prayer_lastname varchar(255) DEFAULT NULL,
				prayer_country varchar(255) DEFAULT NULL,
				prayer_category varchar(255) DEFAULT NULL,
				PRIMARY KEY (prayer_id)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;';
			return $map_prayer;
		}
	}
}
