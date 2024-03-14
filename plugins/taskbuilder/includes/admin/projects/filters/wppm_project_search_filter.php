<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;

if (!($current_user->ID && $current_user->has_cap('manage_options'))) {exit;}

$old_search_term = get_user_meta($current_user->ID, 'wppm_current_filter_result');
$search_tag = isset($_POST) && isset($_POST['project_search']) ? sanitize_text_field($_POST['project_search']) : '';
$search_tag = '%'.$search_tag.'%';
$search_tag = array('search_tag'=>$search_tag);
if (!empty($search_tag)) {exit;}
foreach( $search_tag as $key=>$val){
    if($key == 'search_tag'){
      $query = "SELECT * FROM {$wpdb->prefix}wppm_project where project_name LIKE '$val'";
    }
}
$new_search_term = array('search_tag'=>$query);
update_user_meta($current_user->ID, 'wppm_current_filter_result', $new_search_term);
?>