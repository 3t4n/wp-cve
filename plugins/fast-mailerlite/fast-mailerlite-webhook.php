<?php

  require_once("../../../wp-load.php");
  $rawData = file_get_contents("php://input");
  $data = json_decode($rawData);
  error_log(print_r($data, TRUE));
  if($data->events){
    foreach($data->events as $key => $event){
      if($event->type == 'subscriber.add_to_group'){
        $email = $event->data->subscriber->email;
        $group_name = $event->data->group->name;
        $userData = get_user_by('email', $email);
        if($userData){
           $wp_user_tags = wp_get_object_terms($userData->ID, 'fast_tag');
           $term = term_exists( $group_name, 'fast_tag' );
           if($term){
             if(!in_array($group_name, array_column($wp_user_tags, 'name'))){
               wp_set_object_terms( $userData->ID, (int)$term['term_id'], 'fast_tag', true );
               error_log('Fast Mailerlite: '.$group_name.' tag added to user id '.$userData->ID);
             }
           }else{
             $newterm = wp_insert_term($group_name,'fast_tag',array('description' => 'Mailerlite Tag'));
             wp_set_object_terms( $userData->ID, (int)$newterm['term_id'], 'fast_tag', true );
             error_log('Fast Mailerlite: '.$newterm['term_id'].' tag added to user id '.$userData->ID);
           }
         }
      }
      if($event->type == 'subscriber.remove_from_group'){
        $email = $event->data->subscriber->email;
        $group_name = $event->data->group->name;
        $userData = get_user_by('email', $email);
        if($userData){
           $wp_user_tags = wp_get_object_terms($userData->ID, 'fast_tag');
           $term = term_exists( $group_name, 'fast_tag' );
           if($term){
             if(in_array($group_name, array_column($wp_user_tags, 'name'))){
               wp_remove_object_terms( $userData->ID, (int)$term['term_id'], 'fast_tag' );
               error_log('Fast Mailerlite: '.$group_name.' tag removed from user id '.$userData->ID);
             }
           }
         }
      }
    }
  }

  ?>
