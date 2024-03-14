<?php 
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
require_once(dirname(__FILE__).'/insert-post-ads.php');
 $option_name = 'wporg_option';
 
delete_option($option_name);
 
// for site options in Multisite
delete_site_option($option_name);
// drop a custom database table
global $wpdb;
$prefix = $wpdb->prefix;
//$wpdb->query('UPDATE wp_usermeta SET meta_value = "" WHERE meta_key = "insert_ads_1.6_admin_notice_dismissed"');
$wpdb->query('DELETE FROM '.$prefix.'usermeta WHERE `meta_key` = "insert_ads_'.WP_INSADS_VERSION.'_admin_notice_dismissed"');
?>