<?php 

namespace Shop_Ready\extension\shopajax\Assets;
use Shop_Ready\system\base\assets\Assets as Shop_Ready_Resource;
use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

/*
* Register all widgets related js and css
* @since 1.0 
* $pagenow (string) used in wp-admin See also get_current_screen() for the WordPress Admin Screen API
* $post_type (string) used in wp-admin
* $allowedposttags (array)
* $allowedtags (array)
* $menu (array)
*/

Class Enqueue extends Shop_Ready_Resource{
   
   public function register(){
    add_action( 'wp_enqueue_scripts' , [ $this,'enqueue_public_js' ], 105 );
   }

  /*
   * enqueue js
   */ 
  public function enqueue_public_js($hook){
 
    wp_localize_script( 'shop-ready-public-base', 'shop_ready_shop_filter_obj',
        array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'active'   => true
        )
    );
    
    wp_localize_script( 'shop-ready-elementor-base', 'shop_ready_shop_filter_obj',
        array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'active'   => true
        )
    );
    
  }

  


}