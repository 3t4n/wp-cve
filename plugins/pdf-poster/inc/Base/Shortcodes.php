<?php

namespace PDFPro\Base;

use PDFPro\Model\AdvanceSystem;
use PDFPro\Model\AnalogSystem;

class Shortcodes{

  public function register(){
    add_shortcode('pdf', [$this, 'pdf'], 10, 2);
    add_shortcode('raw_pdf', [$this, 'raw_pdf']);
  }

  public function pdf($atts, $content){
    extract(shortcode_atts(array(
      'id' => null,
    ), $atts));
    $post_type = get_post_type($id);
    $pluginUpdated = 1630223686;
    $publishDate = get_the_date('U', $id);
    $isGutenberg = get_post_meta($id, 'isGutenberg', true);
    $post = get_post($id);
    
    ob_start();
    
    if($post_type !== 'pdfposter'){
      return false;
    }
    
    if($pluginUpdated < $publishDate && $post->post_content != '' || $isGutenberg){
      echo( AdvanceSystem::html($id));
    }else {
      echo Analogsystem::html($id);
    }
    return ob_get_clean(); 
  }

  // Raw PDF ShortCode
  public function raw_pdf($atts){
    extract(shortcode_atts(array(
      'id' => null,
    ), $atts));

    $post_type = get_post_type($id);
    ob_start(); 
    if($post_type !== 'pdfposter'){
      return false;
    }

    $isGutenberg = get_post_meta($id, 'isGutenberg', true);

    if($isGutenberg){
      echo( AdvanceSystem::html($id, true));
    }else {
      echo AnalogSystem::html($id, true);
    }
    return ob_get_clean(); 
  }

}
