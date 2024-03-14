<?php

namespace Shop_Ready\extension\generalwidgets\base;
use Illuminate\Config\Repository as Shop_Ready_Repository;

 Class Widgets_Settings{

    public function register(){

      add_filter('shop_ready_system_widgets_config',[$this,'_load_components_widgets'],16); 
    }

    public function _load_components_widgets($widgets){
        
        $prev_arr = $widgets->all();
        $new_arry = shop_ready_genwidget_config()->all();
        $merge    = array_merge($prev_arr, $new_arry);
        $_config  = new Shop_Ready_Repository($merge);

        return $_config;
    }
    
}