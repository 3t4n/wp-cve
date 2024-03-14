<?php

namespace ImageComply;

if (!defined('WPINC')) {
	die;
}

class YoastSEO {

  public function __construct() {
    $this->init();
  }

  public function init(){
    add_filter('imagecomply_image_data', [$this, 'image_data_filter']);
  }

  /**
   * Adds the Yoast SEO keywords to the image data array if they exist
   * @param array $image_data
   * @return array 
   */
  public function image_data_filter($image_data) {
    $yoast_keywords = $this->get_yoast_meta($image_data['id']);

    if($yoast_keywords !== null){
      $combined = $image_data['keywords'] . ', ' . $yoast_keywords;

      $image_data['keywords'] = $combined;
    }
    
    return $image_data;
  }

  /**
   * Get the Yoast SEO keywords for the specified image
   * @param int $attachment_id
   * @return string|null
   */
  public function get_yoast_meta($attachment_id){
    $post = get_post($attachment_id);
        
    if ($post) {
      $post_id = $post->post_parent;

      $focus_keywords = get_post_meta($post_id, '_yoast_wpseo_focuskw', true);

      return isset($focus_keywords) ? $focus_keywords : null;
    } 

    return null;
  }
}

