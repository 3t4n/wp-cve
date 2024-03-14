<?php

namespace Shop_Ready\extension\templates\hooks\shop;

use Shop_Ready\base\Page_Layout as Page_Layout;
use Shop_Ready\base\View;

/*
* WooCommerece Shop Archiive
*   
*/

Class Archive_Layout extends Page_Layout{

    public $name = null;
     // Override Template layout.php
    public function register(){
     
      add_filter('template_include', [$this, 'archive_render'],185 );
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
    
    public function archive_render($template){
       
        if( isset($_GET['elementor-preview']) ){
            
            return $template;
        }

        $view = shop_ready_app_config()[ 'views' ];
         
        if( $this->is_woo_shop() ){

            $template = $view['shop_archive_scene'].'/full-width.php';
           
            if( ( $this->template == 'elementor_theme' || $this->template == 'elementor_header_footer' ) && file_exists($view['shop_scene'].'/full-width.php') ){
                return $view['shop_archive_scene'].'/full-width.php';
            }

            if( $this->template == 'elementor_canvas' && file_exists($view['shop_archive_scene'].'/full-width.php') ){
                return $view['shop_archive_scene'].'/offcanvas.php';
            }
            
        }
       
        return $template;
    }

    public function is_woo_shop(){
        
        $this->template = View::get_page_template_slug();

        if( is_product_category() || is_product_tag() ){

           if( $this->template && $this->preset_tpl('shop_archive') ) {
                return true;
           }
        }
        return false;
    }


}
