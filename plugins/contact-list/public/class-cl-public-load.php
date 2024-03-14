<?php

class ContactListPublicLoad {

  public function public_inline_scripts() {
  
    $inline_js = $this->get_inline_scripts();
    wp_add_inline_script( 'contact-list', $inline_js );
    
  }

  public function get_inline_scripts() {

    $js = '';
  
    $js .= "jQuery(document).ready(function($) {

      if (typeof ajaxurl === 'undefined') {
        ajaxurl = '" . esc_url_raw( admin_url('admin-ajax.php') ) . "'; // get ajaxurl
      }

    });";
    
    return $js;
  
  }
  
  public function public_inline_styles() {
  
    $styles = $this->get_inline_styles();
    wp_add_inline_style( 'contact-list', $styles );
    
  }
  
  public function get_inline_styles() {
  
    $s = get_option('contact_list_settings');

    $css = '';
    
    if ( isset($s['contact_card_left_column_width']) && $s['contact_card_left_column_width'] ) {
      
      $left_width = intval( $s['contact_card_left_column_width'] );
      $right_width = 100 - $left_width;
      
      $css .= '.contact-list-main-left { width: ' . $left_width . '%; }';
      $css .= '.contact-list-main-right { width: ' . $right_width . '%; }';

    } elseif ( isset($s['contact_image_size']) && $s['contact_image_size'] == 'small' ) {

      $css .= '.contact-list-main-left { width: 86%; }';
      $css .= '.contact-list-main-right { width: 14%; }';

    } elseif ( isset($s['contact_image_size']) && $s['contact_image_size'] == 'large' ) {

      $css .= '.contact-list-main-left { width: 66%; }';
      $css .= '.contact-list-main-right { width: 34%; }';

    }
    
    if (isset($s['show_contact_images_always'])) {
    
      $css .= 'body .contact-list-container.contact-list-3-cards-on-the-same-row .contact-list-image img { display: inline-block; }';
      $css .= 'body .contact-list-container.contact-list-4-cards-on-the-same-row .contact-list-image img { display: inline-block; }';
      
    }

    if (isset($s['card_background']) && $s['card_background']) {
    
//      $css .= '.contact-list-container #contact-list-search ul li { margin-bottom: 5px; }';
    
      if ($s['card_background'] == 'white') {
        $css .= '.contact-list-contact-container { background: #fff; }';
      } elseif ($s['card_background'] == 'light_gray') {
        $css .= '.contact-list-contact-container { background: #f7f7f7; }';
      }
    }
    
    if (isset($s['card_border']) && $s['card_border']) {
    
      if ($s['card_border'] == 'black') {
        $css .= '.contact-list-contact-container { border: 1px solid #333; border-radius: 10px; padding: 10px; }';
      } elseif ($s['card_border'] == 'gray') {
        $css .= '.contact-list-contact-container { border: 1px solid #bbb; border-radius: 10px; padding: 10px; }';
      }
    }
    
    $card_height = 0;

    if (isset($atts['card_height'])) {
      $card_height = 380;
      $card_height = intval( $atts['card_height'] );
    } elseif (isset($s['card_height']) && $s['card_height']) {
      $card_height = 380;
      $card_height = intval( $s['card_height'] );
    }
    
    if ($card_height) {
      $css .= '#all-contacts li { min-height: ' . intval( $card_height ) . 'px; }';  
      $css .= '.contact-list-2-cards-on-the-same-row #all-contacts li { min-height: ' . intval( $card_height ) . 'px; }';  
      $css .= '.contact-list-3-cards-on-the-same-row #all-contacts li { min-height: ' . intval( $card_height ) . 'px; }';  
      $css .= '.contact-list-4-cards-on-the-same-row #all-contacts li { min-height: ' . intval( $card_height ) . 'px; }';  
    }

    $css .= ' @media (max-width: 820px) { #all-contacts li { min-height: 0; } }';  
    $css .= ' @media (max-width: 820px) { .contact-list-2-cards-on-the-same-row #all-contacts li { min-height: 0; } }';  
    $css .= ' @media (max-width: 820px) { .contact-list-3-cards-on-the-same-row #all-contacts li { min-height: 0; } }';  
    $css .= ' @media (max-width: 820px) { .contact-list-4-cards-on-the-same-row #all-contacts li { min-height: 0; } }';  
  
    return $css;
  
  }

}
