<?php
namespace Shop_Ready\system\base\Repository;

class Product_Modal extends Data_Query{

    protected $settings = [];
    public $total_post_found = 0;
    public $max_num_pages = 1;
    public function __construct( $settings = [] ){

       $this->setSettings($settings);
       $this->settings = $settings;  
       
    }

    public function get_posts( $single= false ){

        $this->args['numberposts'] = $this->settings['post_count'];
        
        $query = wc_get_products( $this->args );
        
        if($single == true){
            return isset($query[0])?$query[0]:false;
        }

        return $query;

    }

    public function get_query_args(){
       return $this->args;
    }
    
    public function set_post_type( $type = 'product' ){
        $this->args['post_type'] = $type;
    }
    
    protected function setSettings( $settings = [] ){
        
            if( !is_array( $settings ) ){
              return; 
            } 

            $paged	 = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
          
            $this->args['paged']          = $paged;
            $this->args['order']          = $settings['post_order'];
            $this->args['posts_per_page'] = $settings['post_count'];

            if( isset( $settings[ 'enable_pagination' ] ) ){

                if($settings[ 'enable_pagination' ] == 'yes'){
                    $this->args['posts_per_page'] = isset($settings['per_page_product']) && $settings['per_page_product'] > 0 ? $settings['per_page_product'] : $settings['post_count'];
                } 

                $the_query              = new \WP_Query( $this->args );
                $this->total_post_found = $the_query->found_posts;
                $this->max_num_pages    = $the_query->max_num_pages;

            }
        
            if( isset($settings['post_tags']) && is_array( $settings['post_tags'] ) ){
                
                if( count( $settings['post_tags'] ) ){

                    $this->args['tax_query'][]=  [
                        'taxonomy' => 'product_tag',
                        'field'    => 'term_id',
                        'terms'    =>  $settings['post_tags'],
                    ];

                }
            }
            
            
            if( isset($settings['post_cats']) && is_array( $settings['post_cats'] ) ){
                
                if( count( $settings['post_cats'] ) ){

                    $this->args['tax_query'][]=  [
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    =>  $settings['post_cats'],
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