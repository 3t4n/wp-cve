<?php

function pin_generator_enqueue_pinterest_script() {
  wp_enqueue_script( 'pinterest', '//assets.pinterest.com/js/pinit.js', array(), false, true);
}
add_action( 'wp_enqueue_scripts', 'pin_generator_enqueue_pinterest_script' );

function pin_generator_insert_pin_generator_pin($content)
{
  if (!is_feed() && !is_home() && is_single()) {
    $post_ID = get_the_ID();
    $post_pin_image = get_post_meta($post_ID, "pingen_pin_image_url", true);
    $show_pin = get_post_meta($post_ID, "pingen_show_pin", true);
    $design_options = get_option( 'pin_generator_design_settings' );
    $attribution = $design_options[ 'attribution' ];

    if($show_pin == true && $post_pin_image != ''){
      $content .= "<div class='pinGeneratorPin'>";
      $content .= '<div class="pingen-pin-and-share-button">';

     
      $content .= '<img id="pinImageInPost'. $post_ID . '" class="generated-pin-in-post" src="' . $post_pin_image . '" class="pin-image" />';

      $content .= "<div class='pg-pin-share-button'>";
      $content .= '<a class="pg-pin-share-button" data-pin-do="buttonBookmark" data-pin-tall="true" data-pin-round="true" href="https://www.pinterest.com/pin/create/button/"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_round_red_32.png" /></a>';
      $content .= '</div>';


      $content .= '</div>';
      if($attribution){
        $content .=
        "<p class='pg-made-with-text'>Generated with <a href='https://pingenerator.com' target='_blank' class='pinGeneratorLink' rel='dofollow'>Pin Generator</a></p>";
      }
      
      $content .= "</div>";
    }
  }

    return $content;
  
}
add_filter("the_content", "pin_generator_insert_pin_generator_pin");