<?php

namespace ImageComply;

if (!defined('WPINC')) {
	die;
}

class Woocommerce {

  public function __construct() {
    $this->init();
  }

  public function init(){
    add_filter('imagecomply_image_data', [$this, 'image_data_filter']);
  }

  /**
   * Adds the Woocommerce product name to the image data array if it exists
   * @param array $image_data
   * @return array 
   */
  public function image_data_filter($image_data) {
    $ecommerce_meta = $this->get_woocommerce_meta($image_data['id']);

    if($ecommerce_meta !== null){
      $image_data['ecommerce'] = $ecommerce_meta;
    }
    
    return $image_data;
  }


  /**
   * Get the Woocommerce product name for the specified image
   * @param int $attachment_id
   * @return string|null
   */
  public function get_woocommerce_meta($attachment_id){
    $post_parent = get_post_parent($attachment_id);

    if($post_parent->post_type === 'product'){
      return array(
        'title' => $post_parent->post_title,
      );
    }
    else {
      return null;
    }
  }
}

