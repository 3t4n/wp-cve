<?php

namespace Shop_Ready\extension\templates\hooks\shop;

use Shop_Ready\base\Template_Redirect as Mangocube_Template;

/**
* WooCommerece Single Product 
*   
*/
Class Shop extends Mangocube_Template{


    public function register(){
              
        $this->set_name('shop');

        add_filter('body_class', [$this, 'set_body_class'] );
        add_action($this->get_action_hook(), [$this, 'dynamic_template'],10);
      
    }

     /**
     * | is_renderable_template |
     * @param  [string]  $template
     * @param  [string]  $slug
     * @param  [string]  $name
     * @return boolean | int
     */
    public function is_renderable_template( $template, $slug, $name ){
      
        return false;
    }

    public function shop_notification(){
       
    }

    public function _template( $path ){
    
      return $path;
    }

       /**
    * | set_body_class |
    * @author     <quomodosoft.com>
    * @since      File available since Release 1.0
    * @param  [string]  $classes
    * @return array | []
    */
    public function set_body_class($classes){

        if( ( is_shop() || is_product_category() || is_product_tag() ) && $this->preset_tpl('shop') ) {
            
            return array_merge( $classes, array( 'shopready-elementor-addon','woo-ready-'.$this->name ) );
        }

        return  $classes;
    }


}
