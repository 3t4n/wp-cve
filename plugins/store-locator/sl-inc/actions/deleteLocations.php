<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//3/20/13 5:32:26p - last saved

if ($_POST){ 
	#https://developer.wordpress.org/reference/functions/sanitize_text_field/#comment-5504 -- v3.98.5 5/13/22 (can also use map_deep())
	$sl_id = (is_array($_POST['sl_id']))? array_map("sanitize_text_field", $_POST['sl_id']) : sanitize_text_field($_POST['sl_id']);
}
if (is_array($sl_id)==1) {
	$rplc_arr=array_fill(0, count($sl_id), "%d");
	$id_string=implode(",", array_map(array($wpdb, "prepare"), $rplc_arr, $sl_id)); 
} else { 
	$id_string=$wpdb->prepare("%d", $sl_id); 
}
$wpdb->query("DELETE FROM ".SL_TABLE." WHERE sl_id IN ($id_string)");
sl_process_tags("", "delete", $id_string);
?>