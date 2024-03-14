<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_meta_g_verification($option){

  
  if( isset($option['enable']) && $option['id'] != ''){
    echo '<meta name="google-site-verification" content="'.esc_attr($option['verification']).'"/>'."\n";
  }


}

