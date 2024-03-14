<?php 
// no direct access!
defined('ABSPATH') or die("No direct access");

delete_option('wpgs_settings');
delete_option('wpgs_db_processed');

global $wpdb;

require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

$sql = "DROP TABLE IF EXISTS `".$wpdb->prefix."gspeech_data`";
$wpdb->query($sql);

?>