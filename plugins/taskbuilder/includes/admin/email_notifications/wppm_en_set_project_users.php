<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wppmfunction, $current_user,$wpdb;
$from_name     = get_option('wppm_en_from_name');
$from_email    = get_option('wppm_en_from_email');
$ignore_emails = get_option('wppm_en_ignore_emails');
$project_id = intval(sanitize_text_field($project_id));
$project_data = $wppmfunction->get_project($project_id);
$proj_users = $project_data['users'];
if(!empty($proj_users)){
  $proj_users = explode(',',$proj_users);
}
if ( !$from_name || !$from_email ) {
  return;
}
$wppm_email_notificatins = get_option('wppm_email_notification');
foreach ($wppm_email_notificatins as $key=>$val) :
  if($val['type']=='change_project_assign_users'){
    $subject  = $wppmfunction->replace_macro(stripslashes($val['subject']), $project_id);
    $body  = $wppmfunction->replace_macro(stripslashes($val['body']),$project_id);
    $recipients = $val['recipients'];
    $email_addresses = array();
    if(!empty($recipients)){
      foreach ($recipients as $recipient) {
          if(is_numeric($recipient)){
            if($recipient == 1 && !empty($proj_users)) {
                foreach($proj_users as $user){
                  if(!empty($user)){
                    $project_user_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = $project_id  AND user_id = $user");
                    $userdata = get_userdata($user);
                    if((!empty($project_user_data)) && $project_user_data->role_id == 1){
                      $email_addresses[] = $userdata->user_email;
                    }
                  }
                }
            }
            elseif($recipient == 2 && !empty($proj_users)){
                foreach($proj_users as $user){
                  if(!empty($user)){
                    $project_user_data = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wppm_project_users WHERE proj_id = $project_id  AND user_id = $user");
                    $userdata = get_userdata($user);
                    if((!empty($project_user_data)) && $project_user_data->role_id == 2){
                      $email_addresses[] = $userdata->user_email;
                    }
                  }
                }
            } 
          } else {
            switch ($recipient) {
                case 'previously_assigned_user':
                $email_addresses = array_merge($email_addresses,$wppmfunction->get_previously_assigned_users($project_id));
                break;
            }
          }
      }
    }   
    $email_addresses = array_unique($email_addresses);
    $email_addresses = array_diff($email_addresses,$ignore_emails);
    $email_addresses = array_diff($email_addresses,array($current_user->user_email));
    $email_addresses = apply_filters('wppm_en_project_assign_users_email_addresses',$email_addresses,$val,$project_id);
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
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    wp_mail($to, $subject, $body, $headers);
    do_action('wppm_after_project_change_users_mail',$project_id,$val);
  }
endforeach;

