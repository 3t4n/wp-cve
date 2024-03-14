<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$proj_id  = isset($_POST['proj_id']) ? sanitize_text_field($_POST['proj_id']) : '' ;
if (!(($current_user->ID && $current_user->has_cap('manage_options')) || $wppmfunction->has_project_permission('assign_project_users',$proj_id))) {exit;}
$wppm_users_role = get_option('wppm_user_role');
$project_data = $wppmfunction->get_project($proj_id);
$prev_assign_users = $project_data['users'];
if(!empty($prev_assign_users )){
  $prev_assign_users = explode(",",$prev_assign_users);
}
$users = (!empty($_POST['user_names']))? $wppmfunction->sanitize_array($_POST['user_names']):"";
$prev_assgn_user_meta = $wppmfunction->get_project_meta($proj_id,'prev_assigned_users');
$wppmfunction->delete_project_meta($proj_id,'prev_assigned_users');
$task_users = $wpdb->get_results("SELECT id,users FROM {$wpdb->prefix}wppm_task WHERE project = $proj_id");
if(!empty($prev_assign_users)) {
  foreach($prev_assign_users as $puser){
    $wppmfunction->add_project_meta($proj_id,'prev_assigned_users',$puser);
    if(!empty($users) && !in_array($puser,$users)){
      if(!empty($task_users)){
        foreach($task_users as $tuser){
          if(!empty($tuser)){
            $wppm_tuser = explode(',',$tuser->users);
            if (($key = array_search($puser, $wppm_tuser)) !== false) {
                unset($wppm_tuser[$key]);
                if(!empty($wppm_tuser)){
                  $wppmtuser = implode(',',$wppm_tuser);
                  $value=array(
                    'users'=>  $wppmtuser
                  );
                } elseif(empty($wppm_tuser)){
                  $value=array(
                    'users'=> ''
                  );
                }
                $wpdb->update($wpdb->prefix.'wppm_task', $value, array('id'=>$tuser->id));
            }
          }
        }
      }
    }
    elseif(empty($users) && !empty($task_users)){
      foreach($task_users as $tuser){
        if(!empty($tuser)){
          $wppm_tuser = explode(',',$tuser->users);
          if (($key = array_search($puser, $wppm_tuser)) !== false) {
              unset($wppm_tuser[$key]);
              if(!empty($wppm_tuser)){
                $wppmtuser = implode(',',$wppm_tuser);
                $value=array(
                  'users'=>  $wppmtuser
                );
              } elseif(empty($wppm_tuser)){
                $value=array(
                  'users'=> ''
                );
              }
              $wpdb->update($wpdb->prefix.'wppm_task', $value, array('id'=>$tuser->id));
          }
        }
      }
    }
  }
}
if(!empty($users)){
  $users = array_unique($users);
  foreach($users as $user){
    $user_role = sanitize_text_field($_POST['wppm_select_user_role_'.$user]);
    $project_user = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = '$proj_id' AND user_id = '$user'");
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
          'proj_id' => $proj_id,
          'user_id' => $user,
          'role_id' => $user_role,
          'assigned_by' => $current_user->ID
      ) );
    }
  }
  $pusers = implode(",",$users);
  if($project_data['users'] != $pusers){
    $pvalues=array(
      'users'=> $pusers
    );
    $wpdb->update($wpdb->prefix.'wppm_project', $pvalues, array('id'=>$proj_id));
  }
} else{
    $pvalues = array(
      'users'=>''
    );
    $wpdb->update($wpdb->prefix.'wppm_project', $pvalues, array('id'=>$proj_id));
}
do_action('wppm_set_project_users', $proj_id);