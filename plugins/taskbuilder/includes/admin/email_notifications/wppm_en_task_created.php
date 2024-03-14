<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $wppmfunction, $current_user,$wpdb;
$from_name     = get_option('wppm_en_from_name');
$from_email    = get_option('wppm_en_from_email');
$ignore_emails = get_option('wppm_en_ignore_emails');
$task_id = intval(sanitize_text_field($task_id));
$task_data = $wppmfunction->get_task($task_id);
$project_id = $task_data['project'];
$project_users = $wpdb->get_results("SELECT user_id FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = $project_id AND role_id = 1");
$task_users = $task_data['users'];
$task_users = explode(',',$task_users);
$flag= false;
if ( !$from_name || !$from_email ) {
  return;
}
$wppm_email_notificatins = get_option('wppm_email_notification');
foreach ($wppm_email_notificatins as $key=>$val) :
  if($val['type']=='new_task'){
    $subject  = $wppmfunction->replace_task_macro(stripslashes($val['subject']), $task_id);
    $body  = $wppmfunction->replace_task_macro(stripslashes($val['body']),$task_id);
    $recipients = $val['recipients'];
    $email_addresses = array();
    if(!empty($recipients)){
      foreach ($recipients as $recipient) {
          if(is_numeric($recipient)){
            if($recipient == 1 && !empty($project_users)){
              foreach($project_users as $proj_user){
                $proj_user = (array) $proj_user;
                $proj_userdata = get_userdata($proj_user['user_id']);
                $email_addresses[] = $proj_userdata->user_email;
              }
            }
            if($recipient == 1 && !empty($task_users)) {
                foreach($task_users as $user){
                  if(!empty($user)){
                    $project_user_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = $project_id  AND user_id = $user");
                    $userdata = get_userdata($user);
                    if((!empty($project_user_data)) && $project_user_data->role_id == 1){
                      $email_addresses[] = $userdata->user_email;
                    }
                  }
                }
            }
            elseif($recipient == 2 && !empty($task_users)){
                foreach($task_users as $user){
                  if(!empty($user)){
                    $project_user_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = $project_id  AND user_id = $user");
                    $userdata = get_userdata($user);
                    if((!empty($project_user_data)) && $project_user_data->role_id == 2){
                      $email_addresses[] = $userdata->user_email;
                    }
                  }
                } 
            }
          }
      }
    }   
    $email_addresses = array_unique($email_addresses);
    $email_addresses = array_diff($email_addresses,$ignore_emails);
    $email_addresses = array_diff($email_addresses,array($current_user->user_email));
    $email_addresses = apply_filters('wppm_en_task_users_email_addresses',$email_addresses,$val,$task_id);
    $email_addresses = array_values($email_addresses);
    $to =  isset($email_addresses[0])? $email_addresses[0] : '';
    if($to){
      unset($email_addresses[0]);
    } else {
      continue; // no email address found to send. So go to next foreach iteration.
    }
    $bcc = implode(',',$email_addresses);
    $headers  = "From: {$from_name} <{$from_email}>\r\n";
    $email_addresses = explode(',',$bcc);
    foreach ($email_addresses as $email_address) {
      $headers .= "BCC: {$email_address}\r\n";
    }
    $headers .= "Content-Type: text/html; charset=UTF-8 \r\n";
    wp_mail($to, $subject, $body, $headers);
    do_action('wppm_after_submit_task_created_mail',$task_id,$val);
  }
endforeach;