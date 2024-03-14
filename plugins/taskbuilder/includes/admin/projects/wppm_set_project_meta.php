<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$project_id = intval(sanitize_text_field($project_id));
$project_data = $wppmfunction->get_project($project_id);
$users = explode(',',$project_data['users']);
$users = array_unique($users);
if(!empty($users)){
  foreach($users as $user){
    if(!empty($user)){
      $user_role = sanitize_text_field($_POST['wppm_select_user_role_'.$user]);
      $project_user = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = '$project_id' AND user_id = '$user'");
      if(!empty($project_user)){
        $values=array(
          'role_id'=> $user_role,
          'assigned_by'=> $current_user->ID
        );
        $wpdb->update($wpdb->prefix.'wppm_project_users', $values, array('id'=>$project_user->id));
      }else{
        $wpdb->insert( 
          $wpdb->prefix . 'wppm_project_users', 
          array(
            'proj_id' => $project_id,
            'user_id' => $user,
            'role_id' => $user_role,
            'assigned_by' => $current_user->ID
        ) );
      }
    }
  }
}