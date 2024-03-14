<?php

add_filter('rsc_settings_store_fields', 'rsc_add_new_fields_for_comments', 10, 1);

function rsc_add_new_fields_for_comments($fields){
  $fields[] = array(
    'field_name' => 'restrict_comments',
    'field_title' => __('Restrict Comments', 'rsc'),
    'field_type' => 'function',
    'function' => 'rsc_yes_no',
    'default_value' => 'no',
    'tooltip' => __('Select whether the comments of the posts with restricted content should be restricted as well.', 'rsc'),
    'section' => 'general_comments'
  );
  return $fields;
}

add_filter('rc_settings_new_sections', 'rsc_add_new_admin_section_for_comments', 10, 1);

function rsc_add_new_admin_section_for_comments($sections){
  $sections['general'][] = array(
    'name' => 'general_comments',
    'title' => __('Comments', 'rsc'),
    'description' => '',
    'subtitle' => '',
    'header' => '',
    'footer' => '',
    'description' => '',
  );
  return $sections;
}

add_filter( 'comments_open', 'rsc_maybe_close_comments', 10, 2 );


function rsc_maybe_close_comments($comments_opened, $post_id){
  $rsc_settings = get_option('rsc_settings');

  $restrict_comments = isset($rsc_settings['restrict_comments']) ? $rsc_settings['restrict_comments'] : 'no';

  if($restrict_comments == 'yes'){
    $value_array = get_post_meta($post_id);
    $value_array['id'] = $post_id;

    $type = isset($value_array['_rsc_content_availability']) ? $value_array['_rsc_content_availability'] : 'everyone';

    $allowed_to_admins_capability = apply_filters('rsc_allowed_to_admins_capability', 'manage_options');

    if (($type !== 'everyone' && !current_user_can($allowed_to_admins_capability)) && !is_admin()) {
      $can_access = Restricted_Content::can_access($value_array);
      if(!$can_access){
        $comments_opened = false;
      }
    }
  }
  return $comments_opened;
}

add_filter('comments_array', 'rsc_maybe_hide_comments', 10, 2);

function rsc_maybe_hide_comments($comments, $post_id){
  $rsc_settings = get_option('rsc_settings');

  $restrict_comments = isset($rsc_settings['restrict_comments']) ? $rsc_settings['restrict_comments'] : 'no';

  if($restrict_comments == 'yes'){
    $value_array = get_post_meta($post_id);
    $value_array['id'] = $post_id;

    $type = isset($value_array['_rsc_content_availability']) ? $value_array['_rsc_content_availability'] : 'everyone';

    $allowed_to_admins_capability = apply_filters('rsc_allowed_to_admins_capability', 'manage_options');

    if (($type !== 'everyone' && !current_user_can($allowed_to_admins_capability)) && !is_admin()) {
      $can_access = Restricted_Content::can_access($value_array);
      if(!$can_access){
        $comments = array();
      }
    }
  }
  return $comments;
}
?>
