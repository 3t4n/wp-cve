<?php
/**
 * Class: WPE_Model_Prayers_Performed
 * @author Flipper Code <hello@flippercode.com>
 * @package Maps
 * @version 3.0.0
 */
if ( ! class_exists( 'WPE_Model_Prayers_Performed' ) ) {
	/**
	 * Prayer model for CRUD operation.
	 * @package Maps
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class WPE_Model_Prayers_Performed extends FlipperCode_WPE_Model_Base
	{

		/**
		 * Intialize location object.
		 */
		public function __construct() {
			$this->table = WPE_TBL_PRAYER_USERS;
			$this->unique = 'pu_id';
		}
		/**
		 * Admin menu for CRUD Operation
		 * @return array Admin meny navigation(s).
		 */
		public function navigation() {
			return array(
			'wpe_manage_prayers_performed' => 'Manage Prayers Performed',
			);
		}

		/**
		 * Get Prayer(s) Performed
		 * @param  array $where  Conditional statement.
		 * @return array Array of Prayer object(s).
		 */
		public function fetch($where = array()) {
			$objects = $this->get( $this->table, $where );
			$result=array();
			if ( isset( $objects ) ) {
				foreach ( $objects  as $object ) {
					$result[] = $object->prayer_id;
				}
				return $result;
			}
		}

		/**
		 * Get Prayer(s) Performed Daily
		 * @return array Array of result object(s).
		 */
		public function fetch_prayers_recieved_perday(){
			global $wpdb;
			echo $query = "SELECT pe.prayer_messages, pe.prayer_author_name, pe.prayer_lastname, pe.prayer_author_email, usr.user_email, COUNT(pu.$this->unique) as prayers_recieved FROM {$wpdb->prefix}prayer_engine pe INNER JOIN $this->table pu ON pe.prayer_id=pu.prayer_id LEFT JOIN {$wpdb->prefix}users usr ON pe.prayer_author=usr.ID WHERE date(pu.prayer_time) = '".date('Y-m-d')."' GROUP BY pe.prayer_id, usr.user_email";
			$results = $this->query($query);
			return $results;
		}

		/**
		 * Get Prayer(s) Performed For Each Prayer Request
		 * @return array Array of result object(s).
		 */
		public function count_prayers_for_each_request(){
			$query = "SELECT COUNT($this->unique) as prayers_recieved, prayer_id FROM $this->table GROUP BY prayer_id";
			$objects = $this->query($query);
			$result=array();
			if ( isset( $objects ) ) {
				foreach ( $objects  as $object ) {
					$result[$object->prayer_id] = $object->prayers_recieved;
				}
				return $result;
			}
		}
	}
}
