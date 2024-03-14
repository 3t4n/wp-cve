<?php
/*
Text Doamin: bp-group-chatroom
*/
if ( !defined( 'ABSPATH' ) ) exit;
global $wpdb;

$sql = "DROP TABLE IF EXISTS {$wpdb->base_prefix}bp_group_chat";
$wpdb->query( $sql );
$sql = "DROP TABLE IF EXISTS {$wpdb->base_prefix}bp_group_chat_online";
$wpdb->query( $sql );
$sql = "DROP TABLE IF EXISTS {$wpdb->base_prefix}bp_group_chat_updates";
$wpdb->query( $sql );
$sql = "DROP TABLE IF EXISTS {$wpdb->base_prefix}bp_group_chat_threads";
$wpdb->query( $sql );

$all_groups = groups_get_groups( array( 'per_page' => 2000 ) );
$all_groups = $all_groups['groups'];
foreach( $all_groups as $group ) {
	groups_delete_groupmeta( $group->id, 'bp_group_chat_enabled' ); 
}

?>