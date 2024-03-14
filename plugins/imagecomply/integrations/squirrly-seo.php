<?php

namespace ImageComply;

if (!defined('WPINC')) {
	die;
}

class SquirrlySEO {
  public function __construct() {
    $this->init();
  }

  public function init(){
    add_filter('imagecomply_image_data', [$this, 'image_data_filter']);
  }

  /**
   * Adds the Squirrly SEO keywords to the image data array if they exist
   * @param array $image_data
   * @return array 
   */
  public function image_data_filter($image_data) {
    $squirrly_keywords = $this->get_squirrly_meta($image_data['id']);

    if($squirrly_keywords !== null){
      $combined = $image_data['keywords'] . ', ' . $squirrly_keywords;

      $image_data['keywords'] = $combined;
    }
    
    return $image_data;
  }


  /**
   * Get the Squirrly SEO keywords for the specified image
   * @param int $attachment_id
   * @return string|null
   */
  public function get_squirrly_meta($attachment_id){
    $post = get_post($attachment_id); 
        
    if ($post) {
      $post_id = $post->post_parent;

      $focus_keywords = get_post_meta($post_id, '_sq_keywords', true);

      // error_log("Squirrly SEO Focus Keywords: ");
      // error_log($focus_keywords);

      return isset($focus_keywords) ? $focus_keywords : null;
    } 

    return null;
  }
}

