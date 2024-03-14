<?php 

function wpe_get_prayer_comment($id){
	global $wpdb,$table_prefix;
	$result=$wpdb->get_results("select * from ".$table_prefix."prayer_comment where prayer_id=".$id." order by comment_date asc");
	return $result;
}