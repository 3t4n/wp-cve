<?php 

namespace Shop_Ready\base\elementor;

/*
* Register all elementor document settings
* @since 1.0 
*/
Abstract Class Document_Settings {

    public $configs = []; 

    public function get_configs(){
      
      return $this->configs;  
    }
  
    abstract protected function register();
    
}