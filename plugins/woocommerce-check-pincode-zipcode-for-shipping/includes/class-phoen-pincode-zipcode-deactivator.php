<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://http://phoeniixx.com/
 * @since      1.0.0
 *
 * @package    Phoen_Pincode_Zipcode
 * @subpackage Phoen_Pincode_Zipcode/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Phoen_Pincode_Zipcode
 * @subpackage Phoen_Pincode_Zipcode/includes
 * @author     PHOENIIXX TEAM <raghavendra@phoeniixx.com>
 */
class Phoen_Pincode_Zipcode_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        global $wpdb;
        $table_name_1 = $wpdb->prefix . 'pincode_zipcode_list_free';
        $table_name_2 = $wpdb->prefix . 'pincode_zipcode_setting_free';
        $sql1         = "DROP TABLE IF EXISTS $table_name_1";
        $sql2         = "DROP TABLE IF EXISTS $table_name_2";
        $wpdb->query($sql1);
        $wpdb->query($sql2);
	}

}
