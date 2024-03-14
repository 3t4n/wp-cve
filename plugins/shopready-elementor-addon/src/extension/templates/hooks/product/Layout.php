<?php

namespace Shop_Ready\extension\templates\hooks\product;

use Shop_Ready\base\Page_Layout as Page_Layout;
use Shop_Ready\base\View;

/*
* WooCommerece Single Product 
*   
*/
Class Layout extends Page_Layout{

    public $name = null;
    public function register(){
        
        add_filter( 'template_include' , [ $this , 'render' ] , 100 );
        add_filter( 'template_include' , [ $this , 'non_woo_theme_compatibilty' ], 99 ); 
        add_action( 'wp_enqueue_scripts' , [ $this ,'remove_default_stylesheet' ], 200 );

    }

    public function remove_default_stylesheet(){

        if( !is_singular('product') ){
            return;
        }

        if( !is_product() ){
            return;
        }

        if( !$this->preset_tpl('single') ){

            // wp_dequeue_style( 'shop-ready-public-base' );
            // wp_deregister_style( 'shop-ready-public-base' );

        }
     
    }

    public function non_woo_theme_compatibilty($template){
      
        if( !current_theme_supports('woocommerce') ){
          
          wp_enqueue_script( 'wc-single-product' );
    
          $template = shop_ready_app_config()->get('views')['non_woo_single'].'/compatible.php'; 
        }
       
        return $template;
       
      }

     /**
     * [ is_renderable_template ]
     * @param  [string]  $template
     * @return string
     */
    public function render($template){
                  
        if( shop_ready_is_elementor_mode() ){
       
            return $template;
        } 
        
        if( !is_product() ){
          return $template;
        }
 
        $view = shop_ready_app_config()['views'];
    
        if( $this->is_single_porduct() ){

         
            if( ($this->template == 'elementor_theme' || $this->template == 'elementor_header_footer') && file_exists($view['single_scene'].'/full-width.php') ){
                return $view['single_scene'].'/full-width.php';
            }

            if( $this->template == 'elementor_canvas' && file_exists($view['single_scene'].'/full-width.php') ){
                return $view['single_scene'].'/offcanvas.php';
            }

        }
   
        return $template;
    }

    public function is_single_porduct(){
        
        $this->template = View::get_page_template_slug();
      
        if( is_product() && $this->template && $this->preset_tpl('single') ){
           
          return true;
        }
       
        return false;
    }


}
