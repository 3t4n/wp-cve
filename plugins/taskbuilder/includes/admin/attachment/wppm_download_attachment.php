<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb;
$upload_dir   = wp_upload_dir();
$attach_id = intval(sanitize_text_field($attach_id));
$attachment=$wpdb->get_row("select * from {$wpdb->prefix}wppm_attachments where id='".$attach_id."'");
$date_created = $attachment->date_created;
$time  = strtotime($date_created);
$month = date("m",$time);
$year  = date("Y",$time);
$findStr = ".txt";
$filepath = "";
$attachment_name = sanitize_file_name($attachment->name);
$attach_file_name = sanitize_file_name($attachment->file_name);
if (!empty($attachment)) {
  $filepath = $upload_dir['basedir'] . '/wppm'.'/'.$year.'/'.$month.'/'. $attachment_name;
  //Turn off output buffering
  if (ob_get_level()) ob_end_clean();
  if( !file_exists($filepath)) return;
    $mime_type = wp_check_filetype($filepath);
    header('Content-Type: ' . $mime_type['type']);
  // Check whether attachment is of image type
  if($attachment->is_image){
    if(ob_get_length() > 0) {
      ob_clean();
    }
    echo file_get_contents($filepath);
    exit(0);
  }
  header('Content-Description: File Transfer');
  header('Cache-Control: public');
  header("Content-Transfer-Encoding: binary");
  header("Content-Disposition: attachment;filename=".($attach_file_name));
  header('Content-Length: '.filesize($filepath));
  if (ob_get_length()) ob_clean();
  flush();
  readfile($filepath);
  exit(0);   
}
?>
