<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
} 
global $current_user,$wpdb,$wppmfunction;
$project_id  = isset($_POST['project_id']) ? sanitize_text_field($_POST['project_id']) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')))) {exit;}
$wppm_project_visibility = isset($_POST['project_visibility']) ? sanitize_text_field($_POST['project_visibility']):"0";
$id = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}wppm_project_meta WHERE project_id = '$project_id' AND meta_key ='public_project'");
if(empty($id )){
    $wppmfunction->add_project_meta($project_id,'public_project',$wppm_project_visibility);
}elseif(!empty($id)){
    $values = array(
        'meta_value'=>$wppm_project_visibility
    );
    $wpdb->update($wpdb->prefix.'wppm_project_meta', $values,array('id'=>$id));
}