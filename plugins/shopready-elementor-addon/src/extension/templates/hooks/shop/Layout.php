<?php

namespace Shop_Ready\extension\templates\hooks\shop;

use Shop_Ready\base\Page_Layout as Page_Layout;
use Shop_Ready\base\View;
use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;
/*
* WooCommerece Shop Archiive
*   
*/
Class Layout extends Page_Layout{

    public $name = null;
    public function register(){
     
      add_filter( 'template_include' , [$this, 'render'],180 );
      add_action( 'init', [$this,'shop_ready_shop_compatible'], 1 );
      add_action( 'wp_enqueue_scripts', [$this,'remove_default_stylesheet'], 200 );
    }

    public function remove_default_stylesheet(){
   
        if( !is_shop()){
            return;
        }
    
        if( !shop_ready_template_is_active_gl( 'shop' ) ){
            
            // wp_dequeue_style( 'shop-ready-public-base' );
            // wp_deregister_style( 'shop-ready-public-base' );
        
        }
      
      
    
    }

     /**
     * [ is_renderable_template ]
     * @param  [string]  $template
     * @return string
     */
    public function render($template){
       
        if( isset($_GET['elementor-preview']) ){
            
            return $template;
        }

        $view = shop_ready_app_config()[ 'views' ];
         
        if( $this->is_woo_shop() ){

            $template = $view['shop_scene'].'/full-width.php';
           
            if( ( $this->template == 'elementor_theme' || $this->template == 'elementor_header_footer' ) && file_exists($view['shop_scene'].'/full-width.php') ){
                return $view['shop_scene'].'/full-width.php';
            }

            if( $this->template == 'elementor_canvas' && file_exists($view['shop_scene'].'/full-width.php') ){
                return $view['shop_scene'].'/offcanvas.php';
            }
            
        }
       
        return $template;
    }
    
   

    public function is_woo_shop(){
        
        $this->template = View::get_page_template_slug();

        if(is_shop() || is_product_category() || is_product_tag()){

           if( $this->template && $this->preset_tpl('shop') ) {
                
                return true;
           }
        }
        return false;
    }

    public function shop_ready_shop_compatible(){

        $catalog     = WReady_Helper::get_global_setting('shop_ready_pro_archive_settings_catalog_remove_default','yes');
        $shop_result = WReady_Helper::get_global_setting('shop_ready_pro_archive_settings_shop_result_remove_default','yes');
      
       // if($catalog === 'yes'){
            remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
       // }
        //if($shop_result === 'yes'){
            remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
       // }
        
   }


}
