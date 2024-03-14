<?php 

namespace Shop_Ready\base\elementor;

/*
* Register all elementor widge
* @since 1.0 
*/

Abstract Class Widget_Settings {

    public $slug = null; 
    public $configs = []; 

    public function get_configs(){
      
      return $this->configs;  
    }

    public function get_slug(){
      return $this->slug;
    }

    /**
     * service initializer
     * @return void
     * @since 1.0
     */
    abstract protected function register();
    /**
     * Widget configuration from Service 
     */
    abstract protected function set_configs( );
    abstract protected function render( $atts, $content ='' );
    
}