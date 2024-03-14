<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_blog_public($option) {

  if( is_front_page() ) return;

  $robot_list = array( '404' , 'search' , 'year' , 'month' , 'day' , 'tag' , 'category' , 'attachment' );
  foreach ($robot_list as $key) {
    $robot[$key] = isset($option[$key]) ? true: false;

    if($robot[$key]){
      $robot_page = 'is_'.$key;
      if( $robot_page() ){
        echo '<meta name="robots" content="noindex,follow" />'."\n";
        return;
      }
    }
  }
  if ( function_exists( 'get_privacy_policy_url' ) ){
    if( get_privacy_policy_url() === get_the_permalink() ){
      echo '<meta name="robots" content="noindex,follow" />'."\n";
      return;
    }
  }



  $post_not_in = explode(',', $option['post_not_in']);
  if( in_array ( get_the_ID(), $post_not_in  ) ) {
    echo '<meta name="robots" content="noindex,follow" />'."\n";
    return;
  }

  if( isset($option['parent_not_in'])){

    $parent_num = get_the_ID();

    $parents_id = array_reverse ( get_post_ancestors($parent_num) );

    if( !empty($parents_id) ){
      
      $parent_num = $parents_id[0];
    }

    $parent_not_in = explode(',', $option['parent_not_in']);

    if( in_array ( $parent_num , $parent_not_in  ) ) {
      echo '<meta name="robots" content="noindex,follow" />'."\n";
      return;
    }

  }





}
