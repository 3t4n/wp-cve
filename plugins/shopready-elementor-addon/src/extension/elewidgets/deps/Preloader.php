<?php 

namespace Shop_Ready\extension\elewidgets\deps;
use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;
/**
 * Prealoder
 * @since 2.0
 */
Class Preloader {
 
    public function register(){
        add_filter( 'body_class', [ $this,'body_class' ] );
        add_action( 'wp_body_open',[$this,'push_html'], 10);
        add_action( 'wp_enqueue_scripts', [ $this , '_enqueue_inline' ],106 );
    }
    public function body_class($classes){

        if(!$this->should_proceed()){
            return $classes;
        }

        return array_merge( $classes, array( 'shop-ready-preloader-active' ) );
    }

    public function push_html(){

       if(!$this->should_proceed()){
        return;
       }
       $color =  WReady_Helper::get_global_setting('shop_ready_preloader_bg_color');
       echo wp_kses_post(sprintf('<div style="%s" id="shop-ready-preloader-wrapper" class="shop-ready-preloader-wrapper"> </div>',
       $color != '' ? 'background:'.esc_attr($color) : ''
       ));
    }
    public function _enqueue_inline(){
        
        wp_add_inline_script( 'shop-ready-elementor-base', '

            document.addEventListener("DOMContentLoaded", function(event) {
            var element = document.getElementById("shop-ready-preloader-wrapper");
            if (typeof(element) != "undefined" && element != null)
            {
               document.getElementById("shop-ready-preloader-wrapper").remove();
               document.body.classList.remove("shop-ready-preloader-active");
            }
           
        });

        ' );   
    }
    
    public function should_proceed(){

        $active =  WReady_Helper::get_global_setting('shop_ready_preloader_active');
        if($active == 'yes'){
            return true;
        }
        return false;
    }
 
}