<?php

if (!defined('WP_UNINSTALL_PLUGIN'))
    exit();

delete_option('spr_settings');
delete_option('spr_version');
delete_option('widget_spr_top_widget');
global $wpdb;
$query="DROP TABLE IF EXISTS  `".$wpdb->prefix."spr_votes` , `".$wpdb->prefix."spr_rating`;";
$wpdb->query($query);
unlink(__FILE__);
@rmdir(__DIR__);
?>