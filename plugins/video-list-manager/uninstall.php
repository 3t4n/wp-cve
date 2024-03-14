<?php

if(!defined('WP_UNINSTALL_PLUGIN') )
{
    exit();
}

delete_option("tntVideoManageOptions");
delete_option("tnt_video_list_manager_db_version");

//delete any options, tables, etc the plugin created
global $wpdb;
$tableName1 = $wpdb->prefix."tnt_videos";
$tableName2 = $wpdb->prefix."tnt_videos_cat";
$tableName3 = $wpdb->prefix."tnt_videos_type";

$sql = "DELETE FROM wp_tnt_videos WHERE video_id=1";

$wpdb->query("DROP TABLE IF EXISTS $tableName1");

$wpdb->query("DROP TABLE IF EXISTS $tableName2");

$wpdb->query("DROP TABLE IF EXISTS $tableName3");
?>