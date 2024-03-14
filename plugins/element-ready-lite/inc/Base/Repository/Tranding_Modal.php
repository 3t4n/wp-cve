<?php

namespace Element_Ready\Base\Repository;

class Tranding_Modal extends Base_Modal{
     
  function __construct($settings){

    parent::__construct($settings);
    $this->settings();

   }

  public function settings(){
      
      if( isset( $this->settings['is_tranding_post'] ) ){

        if( $this->settings['is_tranding_post'] == 'yes' ){

            $this->args['meta_query'][] = [
              'key'     => '_element_ready_trending',
              'value'   => 'yes',
              'compare' => '=',
            ];
  
        }

      }
     
      return $this->args;  
  }
}