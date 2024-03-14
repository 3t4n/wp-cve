<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpdb;
$garbage_collection_time = get_option('wppm_garbage_collection_time' ,'');
$check_flag = false;
$now = time();
$ago = strtotime($garbage_collection_time);
$diff = $now - $ago;
if($diff >= 86400){
  $check_flag = true;
}
if(!$check_flag){
  return;
}
$attachments=$wpdb->get_results("select * from {$wpdb->prefix}wppm_attachments where is_active=0");
if($attachments){
  foreach($attachments as $attachment){
    $created_time   = $attachment->date_created;
    $upload_dir  = wp_upload_dir();
    $time  = strtotime($created_time);
    $month = date("m",$time);
    $year  = date("Y",$time);
    $filepath = $upload_dir['basedir'] . '/wppm/'.'/'.$year.'/'.$month.'/'. $attachment->name;
    $attachment_updated_time = strtotime($created_time);
    $now  = time();
    $diff = $now - $attachment_updated_time;
    if($diff > 86400){
        $wpdb->delete($wpdb->prefix.'wppm_attachments', array( 'id' => $attachment->id));
        if(file_exists($filepath)){
            unlink($filepath);
        }
    }
  }
}
update_option('wppm_garbage_collection_time', date("Y-m-d H:i:s"));
    
