<?php

namespace Shop_Ready\extension\templates\hooks\order;

use Shop_Ready\base\Template_Redirect as Mangocube_Template;
/*
* WooCommerece Thank you page
* @since 1.0  
*/

Class Order extends Mangocube_Template{


    public function register(){
       
        $this->set_name('order_received');

        add_filter( 'body_class' , [ $this , 'set_body_class' ] );
        add_action( $this->get_action_hook() , [ $this, 'dynamic_template' ], 100 );
    }
   

    /**
     * not-used
     * | is_renderable_template |
     * @param  [string]  $template
     * @param  [string]  $slug
     * @param  [string]  $name
     * @return boolean | int
    */
    public function is_renderable_template( $template, $slug, $name ){
        
       
        return true;
    }
    /**
     * not-used
     *  action hook
     * | _cart_notification |
     * 
     * @param  [string]  $template
     * @return boolean | int
    */
    public function _order_notification($page){
      
    }


    /**
    * | set_body_class |
    * @author     <quomodosoft.com>
    * @since      File available since Release 1.0
    * @param  [string]  $classes
    * @return array | []
    */
    public function set_body_class($classes){
      
        //is_wc_endpoint_url
        if( is_checkout() && is_wc_endpoint_url('order-received') ) {
           
            return array_merge( $classes, array( 'shopready-elementor-addon','woo-ready-'.str_replace(['_'],['-'], $this->name ) ) );
        }
        return  $classes;
    }


}
