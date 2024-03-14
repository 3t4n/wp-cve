<?php

namespace Element_Ready\Base\Repository;

class Video_Slider_Modal extends Data_Query{
   
  protected $settings = [];
  public function __construct($settings=[]){

     $this->setSettings($settings);
     $this->settings = $settings;  
  }
  public function get_query_args(){
     return $this->args;
  } 
  protected function setSettings($settings=[]){
      
          if( !is_array( $settings ) ){
            return; 
          }  
 
          $this->args['order']          = $settings['video_post_order'];
          $this->args['posts_per_page'] = 1;
          $this->args['category__in']   = $settings['video_post_cats'];
         

          if( $settings['video_standard_post_format'] =='no' && isset($settings['video_post_formats']) && is_array( $settings['post_formats'] ) ){
              
              if( count( $settings['post_formats'] ) ){

                  $this->args['tax_query'][]=  [
                      'taxonomy' => 'post_format',
                      'field'    => 'slug',
                      'terms'    =>  $settings['post_formats'],
                  ];

              }
          }

    
         
          switch($settings['video_post_sortby']){

              case 'popularposts': 
                  $this->args['meta_key'] = 'element_ready_post_views_count';
                  $this->args['orderby']  = 'meta_value_num';
              break;
              case 'fb_share': 
                  $this->args['meta_key'] = 'element_ready_fb_share_count';
                  $this->args['orderby']  = 'meta_value_num';
              break;
              case 'mostdiscussed': 
                  $this->args['orderby'] = 'comment_count';
              break;
              case 'tranding':
                  $this->args['meta_query'][] = [
                      'key'     => '_element_ready_trending',
                      'value'   => 'yes',
                      'compare' => '=',
                    ];
              default: 
                  $this->args['orderby'] = 'date';
              break;
              
          }
  }
  
}