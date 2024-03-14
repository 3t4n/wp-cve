<?php
namespace Shop_Ready\base;

Abstract class Ajax_Manager{
    public $config_array = [

    ];
    
    function __construct (){
        
      $this->register_event();
    }

    /**
     * service initializer
     * @return void
     * @since 1.0
     */
    abstract protected function register();

    public function register_event(){
        
        foreach($this->config_array as $key => $method){
           
            if(method_exists(get_called_class(),$method)){
                add_action( 'wp_ajax_'.$key, [get_called_class(),$method] );
            }
            
        }
    }
 }