<?php

namespace Shop_Ready\extension\templates\hooks\order;

use Shop_Ready\base\Page_Layout as Page_Layout;
use Shop_Ready\base\View;
/** 
* Thank you
* WooCommerece Order Template
* woocommerce->settings->advanced->page setup  
*/

Class Layout extends Page_Layout{

    public $name = 'order-received';
    public function register(){
     
      add_filter('template_include', [$this, 'render'], 1000 );
  
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

        if(!is_wc_endpoint_url('order-received')){
            return $template;
        }
       
        $view = shop_ready_app_config()['views'];
       
        
        if( $this->is_order_received_after_checkout() ) {
           
            if( ( $this->template == 'elementor_theme' || $this->template == 'elementor_header_footer') && file_exists($view['order_scene'].'/received/full-width.php') ){
                return $view['order_scene'].'/received/full-width.php';
            }

            if( $this->template == 'elementor_canvas' && file_exists($view['order'].'/full-width.php') ){
                return $view['order_scene'].'/received/offcanvas.php';
            }

        }
        
        return $template;
    }

    public function is_order_received_after_checkout(){
       
        $this->template = View::get_page_template_slug();

        if( 
            is_checkout() &&
            is_wc_endpoint_url('order-received') &&
            $this->template &&
            $this->is_tpl_active('order_received') &&
            $this->preset_tpl('order_received')
            ){

          return true;
        }

        return false;
    }


}
