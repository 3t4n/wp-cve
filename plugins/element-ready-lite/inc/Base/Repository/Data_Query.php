<?php

namespace Element_Ready\Base\Repository;

abstract class Data_Query {

    public $args = [
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'suppress_filters' => false,
    ];

    abstract protected function setSettings($settings=[]); 

    public function get(){
      
        $query = new \WP_Query( $this->args );

        if( $query->have_posts() ) {
            return $query;
        }
        
        return false;
    }
    
    public function get_posts($single=false){

        $this->args['numberposts'] = $this->settings['post_count'];
       
        $query = get_posts( $this->args );
        
        if($single==true){
            return isset($query[0])?$query[0]:false;
        }

        return $query;

    }
   
}