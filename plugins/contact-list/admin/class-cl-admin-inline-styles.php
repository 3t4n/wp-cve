<?php

class ContactListAdminInlineStyles {

  public static function generateInlineStyles() {
    
    $s = get_option('contact_list_settings');
    
    $css = '';
    
    $css .= '#menu-posts-contact > ul > li:nth-of-type(5):before { ';
    $css .= 'display: block;';
    $css .= "content: 'Tools';";
    $css .= 'color: #fff;';
    $css .= 'padding: 7px 12px 3px 12px;';
    $css .= 'border-bottom: 1px solid rgb(90, 90, 90);';
    $css .= '}';
    $css .= '@media (max-width: 782px) {';
    $css .= '#menu-posts-contact > ul > li:nth-of-type(5):before {';
    $css .= 'padding-left: 20px;';
    $css .= 'padding-bottom: 7px;';
    $css .= 'font-size: 15px;';
    $css .= '}';
    $css .= '}';
    
    if (isset($s['disable_mail_log'])) {
      $num_1 = 8;
      $num_2 = 9;
    } else {
      $num_1 = 9;
      $num_2 = 10;
    }
    
    $css .= '#menu-posts-contact > ul > li:nth-of-type(' . intval( $num_1 ) . ') {';
    $css .= 'border-bottom: 1px solid rgb(110, 110, 110);';      
    $css .= '}';
    $css .= '#menu-posts-contact > ul > li:nth-of-type(' . intval( $num_2 ) . ') {';
    $css .= 'padding-top: 7px;';
    $css .= '}';

    if (ContactListHelpers::isPremium() == 0) {
      $css .= '.wp-list-table tr[data-slug="contact-list"] .upgrade a { color: #3db634; }';
    }

    return $css;
    
  }

  public static function printableListStyles() {
  
    $s = get_option('contact_list_settings');
  
    $css = '';        
    
    $css .= '.contact-list-container #contact-list-search ul li { margin-bottom: 5px; } ';        
    $css .= '.contact-list-contact-container { background: #fff; } ';
  
    if (isset($s['card_border']) && $s['card_border']) {
  
      if ($s['card_border'] == 'black') {
        $css .= '.contact-list-contact-container { border: 1px solid #333; border-radius: 10px; padding: 10px; } ';
      } elseif ($s['card_border'] == 'gray') {
        $css .= '.contact-list-contact-container { border: 1px solid #bbb; border-radius: 10px; padding: 10px; } ';
      }
    }
  
    if (isset($_GET['card_height'])) {
  
      $css .= '.contact-list-2-cards-on-the-same-row #all-contacts li .contact-list-contact-container { height: ' . intval( $_GET['card_height'] ) . 'px !important; } ';  
      $css .= ' @media (max-width: 820px) { .contact-list-2-cards-on-the-same-row #all-contacts li .contact-list-contact-container { height: auto; } } ';  
  
    } else {
  
      $css .= '.contact-list-2-cards-on-the-same-row #all-contacts li .contact-list-contact-container { height: 340px !important; }';
  
    }
    
    return $css;
    
  }

  public static function contactEditStyles() {
  
    $s = get_option('contact_list_settings');
  
    $css = '';        

    if (isset($s['af_hide_groups'])) {
      
      $css .= '
            #contact-groupdiv {
              display: none;
            }';

    }
    
    return $css;
    
  }

}
