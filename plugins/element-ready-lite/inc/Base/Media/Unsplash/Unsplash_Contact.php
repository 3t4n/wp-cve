<?php

namespace Element_Ready\Base\Media\Unsplash;
use \Element_Ready_Pro\Base\Traits\Helper;

abstract class Unsplash_Contact {

    public $app_id      = "2foxhlRRAxsC-9bSk6tEKK7ZRlQPPwFDTLu97rLGeLQ";
    public $base_domain = "https://api.unsplash.com";
    public $page        = 1;
    public $per_page    = 30;

    abstract function list_photos();

    function get_api_data( $apiUrl ){

      $response     = wp_remote_get(esc_url_raw( $apiUrl ));
      $responseBody = wp_remote_retrieve_body( $response );
      $result       = json_decode( $responseBody );
    
      if ( is_array( $result ) && ! is_wp_error( $result ) ) {
        return $result;
      }
      
      return false;
    }

    function element_ready_get_api_option($key = false){
      static $option;
      
      $option = get_option('element_ready_api_data');
     
      if($option == false){
          return '';
      }
      
      return isset($option[$key]) ? $option[$key] :'';
    }
    
    function get_encode_api_data( $apiUrl ){

      $response     = wp_remote_get(esc_url_raw( $apiUrl ));
      $responseBody = wp_remote_retrieve_body( $response );
      $result       = json_decode( $responseBody );
  
      if(is_array( $result ) && isset( $result['errors'] )){
        return false;
      }
      
      return $result;
    	
  }
  public function element_ready_get_modules_option($key = false){
  
      $option = get_option('element_ready_modules');
      if($option == false){
        return false;
      }
      return isset($option[$key]) && $option[$key] == 'on' ? true : false;
  } 
}