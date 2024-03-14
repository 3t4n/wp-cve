<?php

namespace Shop_Ready\extension\elewidgets\deps\product;
use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;
/** 
* @since 1.0 
* WooCommerce Product Slider 
* Products Details
* @author quomodosoft.com 
*/

class Slider {
     
    public function register(){
         add_filter('woocommerce_single_product_carousel_options',[$this,'woocommerce_get_script_data'],20);   
    }

    public function woocommerce_get_script_data($params){

        if(isset($params['directionNav'])){
        
          $params['directionNav'] = true;
          $params['animationLoop'] = true;
      
        }

        return $params;
    }

}