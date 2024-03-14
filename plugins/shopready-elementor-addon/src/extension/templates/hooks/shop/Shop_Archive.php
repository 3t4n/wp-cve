<?php

namespace Shop_Ready\extension\templates\hooks\shop;

use Shop_Ready\base\Template_Redirect as Shop_Ready_Template;
/*
* WooCommerece Single Product 
*   
*/
Class Shop_Archive extends Shop_Ready_Template{


    public function register(){
              
        $this->set_name('shop_archive');
       
        add_filter('body_class', [ $this, 'set_body_class' ] );
        add_action( $this->get_action_hook() , [ $this, 'dynamic_template' ],19);
      
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
        
        if( ( is_product_category() || is_product_tag() ) && $this->preset_tpl('shop_archive') ) {
           
            return array_merge( $classes, array( 'shopready-elementor-addon','shop-ready-'.$this->name ) );
        }

        return  $classes;
    }


}
