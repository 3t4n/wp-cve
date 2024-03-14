<?php

namespace Shop_Ready\extension\elewidgets\base;

use Illuminate\Config\Repository as Shop_Ready_Repository;


Class Widgets_Settings{
  
 
    public function register(){
    
        add_filter('shop_ready_system_widgets_config',[$this,'_load_components_widgets'],13); 
        add_filter('shop_ready_widgets_dashboard_options',[$this,'dashboard_options'],13); 
    }

    public function _load_components_widgets($widgets){
        
        $prev_arr = $widgets->all();
        $new_arry = shop_ready_elementor_component_config()->all();
        $merge    = array_merge($prev_arr, $new_arry);
        $_config  = new Shop_Ready_Repository($merge);
        
        return $_config;
    }
    /**
     * Unset mega menu widget
     */
    public function dashboard_options($options){
       
  
        if( isset( $options[ 'navigation_woo_ready_mega_menu' ] ) ){

            if( !shop_ready_sysytem_module_options_is_active( 'mega_menu' ) ){
                unset($options['navigation_woo_ready_mega_menu']);
            }   
          
        }
        
        if( isset( $options[ 'general_currency_swatcher' ]) ){

            if( !shop_ready_sysytem_module_options_is_active( 'currency_swicher' ) ){
                unset($options['general_currency_swatcher']);
            }   
          
        }

        return $options;
    }
    
}