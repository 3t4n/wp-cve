<?php
namespace Shop_Ready\system\base\Repository;

class Base_Modal extends Data_Query{

    protected $settings = [];

    public function __construct( $settings = [] ){

       $this->setSettings($settings);
       $this->settings = $settings;  
       
    }

    public function get_query_args(){
       return $this->args;
    }
    
    public function set_post_type( $type ){
        $this->args['post_type'] = $type;
    }
    
    protected function setSettings( $settings = [] ){
        
            if( !is_array( $settings ) ){
              return; 
            } 

            $paged	 = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
          
            $this->args['paged']          = $paged;
            $this->args['order']          = sanitize_text_field($settings['post_order']);
            $this->args['posts_per_page'] = sanitize_text_field($settings['post_count']);
            $this->args['category__in']   = sanitize_text_field($settings['post_cats']);
            $this->args['tag__in']        = sanitize_text_field($settings['post_tags']);

            if( $settings['standard_post_format'] =='no' && isset($settings['post_formats']) && is_array( $settings['post_formats'] ) ){
                
                if( count( $settings['post_formats'] ) ){

                    $this->args['tax_query'][]=  [
                        'taxonomy' => 'post_format',
                        'field'    => 'slug',
                        'terms'    =>  $settings['post_formats'],
                    ];

                }
            }

            if($settings['post__not_in']) {
                $this->args['post__not_in'] = $settings['post__not_in'];
            }
    
            if($settings['offset_enable']=='yes'){
               $this->args['offset'] = $settings['offset_item_num'];
            }
    
            if($settings['post_author']){
                $this->args['author__in'] =  $settings['post_author'];
            } 
            
            if( $settings['sticky_post'] == 'yes' ){
                $this->args['post__in'] = get_option( 'sticky_posts' );
            }
           
            switch($settings['post_sortby']){
 
                case 'mostdiscussed': 
                    $this->args['orderby'] = 'comment_count';
                break;
                default: 
                    $this->args['orderby'] = 'date';
                break;
            }

            $today = getdate();

            if($settings['date_post'] == 'today') {
                $this->args['date_query'][]= array(
                    'year'  => $today['year'],
                    'month' => $today['mon'],
                    'day'   => $today['mday'],
                );
            }
            
            if($settings['date_post'] == 'this_week') {
                $this->args['date_query'][]= array(
                    'year' => date( 'Y' ),
                    'week' => date( 'W' ),
                );
            } 

            if($settings['date_post'] == 'custom_date') {
                
                if($settings['date_after'] != ''){
                    
                    $date_after = strtotime($settings['date_after']);                     

                    $yr  = date("Y", $date_after);
                    $mon = date("m", $date_after);
                    $day = date("d", $date_after);

                    $this->args['date_query'][]['after']= [
                        'year'  =>  $yr,
                        'month' => $mon,
                        'day'   => $day,
                    ]; 

                }

                if($settings['date_before'] != '') {

                    $date_before = strtotime($settings['date_before']);                     

                    $yr   = date("Y", $date_before);
                    $mon  = date("m", $date_before);
                    $day  = date("d", $date_before);

                    $this->args['date_query'][]['before']= [
                        'year'  =>  $yr,
                        'month' => $mon,
                        'day'   => $day,
                    ]; 

                    $this->args['date_query']['inclusive'] = true;

                }

                
            }
      
    }
    
}