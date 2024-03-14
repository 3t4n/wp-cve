<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * @link       http://www.apoyl.com/
 * @since      1.0.0
 * @package    Apoyl_Baidupush
 * @subpackage Apoyl_Baidupush/includes
 * @author     凹凸曼 <jar-c@163.com>
 *
 */
class Apoyl_Baidupush_Uninstall {

	
	public static function uninstall() {
	    global $wpdb;
        delete_option('apoyl-baidupush-settings');
        $wpdb->query("DROP TABLE  ".$wpdb->prefix.".apoyl_baidupush; " );
	}

}
