<?php 

namespace Shop_Ready\extension\generalwidgets\assets;
use Shop_Ready\system\base\assets\Assets as Woo_Ready_Resource;

/*
* Register all widgets related js and css
* @since 1.0 
* $pagenow (string) used in wp-admin See also get_current_screen() for the WordPress Admin Screen API
* $post_type (string) used in wp-admin
* $allowedposttags (array)
* $allowedtags (array)
* $menu (array)
*/

Class Assets extends Woo_Ready_Resource{
   
   public function register(){}
 
  /*
   * enqueue css
   */ 
  public function enqueue_public_css($hook){
 
        
    $public_css = [
        'woo-ready-extra-widgets-base'
    ];

    foreach($public_css as $handle){
        wp_enqueue_style( str_replace(['_'],['-'],$handle ) );
    }

    unset($public_css);
 } 
 

   public function enqueue_public_js($hook){

    $public_js = [
        'woo-ready-extra-widgets',
    ];

    foreach($public_js as $handle) {
 
        wp_enqueue_script( str_replace(['_'],['-'],$handle ) );
    }

    unset($public_js);
   }


}