<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_page_view($post_id){


  $pv_key = '_yahman_addons_pv_';
  $period_key = '_yahman_addons_coverage_period_';

  $period_value = array(
    'all' => 1,
    'yearly' => date('Y'),
    'monthly' => date('Y').date('n'),
    'weekly' => date('Y').date('W'),
    'daily' => date('Y').date('n').date('j'),
  );
  
  $post_meta_value = get_post_meta($post_id, $pv_key.'all', true);



  
  if (empty($post_meta_value) || !isset($post_meta_value)) {

    $page_view = 1;

    foreach ($period_value as $key => $value) {
      add_post_meta($post_id, $pv_key.$key, $page_view);
      add_post_meta($post_id, $period_key.$key, $value);
    }

  } else {

    foreach ($period_value as $key => $value) {
      $period_old_value = get_post_meta($post_id, $period_key.$key, true);
      if($period_old_value != $value){
        
        $page_view = 1;
        update_post_meta($post_id, $period_key.$key, $value);
      }else{
        
        $page_view = get_post_meta($post_id, $pv_key.$key, true) + 1;
      }

      update_post_meta($post_id, $pv_key.$key, $page_view);

    }

  }


  $yahman_addons_count = get_option('yahman_addons_count') ;
  
  if (empty($yahman_addons_count) || !isset($yahman_addons_count)) {

    $page_view = 1;

    foreach ($period_value as $key => $value) {
      $yahman_addons_count['pv'][$key] = $page_view;
      $yahman_addons_count['period'][$key] = $value;
    }
    update_option('yahman_addons_count',$yahman_addons_count);

  } else {

    foreach ($period_value as $key => $value) {
      $period_old_value = $yahman_addons_count['period'][$key];
      if($period_old_value != $value){
        
        $yahman_addons_count['pv'][$key] = 1;
        $yahman_addons_count['period'][$key] = $value;
      }else{
        
        $yahman_addons_count['pv'][$key] = $yahman_addons_count['pv'][$key] + 1;
      }
    }
    update_option('yahman_addons_count',$yahman_addons_count);
  }









}// end of function
