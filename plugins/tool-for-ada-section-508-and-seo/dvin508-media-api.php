<?php
class Dvin508_Media_api{

    public $page_number;
    public $media_type;
    public $media_per_page = 25;
    public $media_list = array();
    public $media_size = array(80,80);

    public function __construct(){
        add_action( 'rest_api_init', array($this,'media_route') );
        add_action( 'rest_api_init', array($this,'update_media_route') );
    }

    /* 
        Getting different types of media 
        there are 5 types of media query
        1)  all - show all the mdia
        2)  caption - show media missing caption
        3)  alt - show media missing alt
        4)  content - show meida missing alt

        Other variable are 
        1)  page_number
        2)  per_page

        Register API root
    */
    public function media_route(){
        register_rest_route( 
            'dvin508-seo/v1',
            '/media/missing/(?P<media_type>[a-z]+)/(?P<page_number>\d+)',
            array(
                'methods' => 'GET',
                'callback' =>  array($this,'get_media_type'),
                'permission_callback' => array($this, 'check_permission')
            )
        );
    }

    function check_permission($request_data){
        //return true;
        //print_r(wp_get_current_user());
        if ( is_super_admin() ) {
            return true;
        }else{
            return false;
        }

    }

    public function get_media_type( $request_data ){
        /* getting the varibles from the url */
            $present_page_array = $request_data->get_url_params();

            $this->page_number = $present_page_array['page_number'];
            $this->media_type = $present_page_array['media_type'];
           
        /* end */

        /* 
            Calling appropresate media selection based on the type
            of media requested
        */

        switch($this->media_type){
            case 'all':
                $this->get_all_media();
            break;

            case 'caption':
                $this->get_caption_or_content_missing_media('excerpt');
            break;

            case 'alt':
                $this->get_alt_missing_media();
            break;

            case 'content':
                $this->get_caption_or_content_missing_media('content');
            break;

            default:
                $this->get_all_media();
            break;
        }
        
        return $this->media_list;
    }

    /*
        Gell all the media in the site 
    */
    function get_all_media(){
        $query = new WP_Query(array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => 'any',
            'posts_per_page' => $this->media_per_page,
            'paged' => $this->page_number
        ));

        $this->media_list['present_page'] = (int)$this->page_number;

        foreach($query->get_posts() as $media){
            $this->media_list['data'][]= array('id'=> $media->ID,
                            'caption' => $media->post_excerpt,
                            'description' => $media->post_content,
                            'image' => wp_get_attachment_image($media->ID, $this->media_size ), 
                            'alt' =>  get_post_meta( $media->ID, '_wp_attachment_image_alt', true),
                    );
        }

        $this->media_list['max_pages'] = $query->max_num_pages;

    }

    /*
        This is used for getting missing caption and content both    
        that is controlled my input $missing -> 'excerpt' or 'content'
    */
    public function get_caption_or_content_missing_media($missing){

        global $wpdb;

        $query1 = $wpdb->get_row('Select count(*) as count from '.$wpdb->prefix.'posts where post_type = "attachment" AND post_mime_type LIKE "image/%" AND post_'.$missing.'= ""');
        $total_results = ($query1->count);

        $max_pages = ceil($total_results / $this->media_per_page);

        $offset = ($this->page_number - 1) * $this->media_per_page;
        //print_r($max_pages);

        $query =  $wpdb->get_results('Select * from '.$wpdb->prefix.'posts where post_type = "attachment" AND post_mime_type LIKE "image/%" AND post_'.$missing.'= "" Limit '.$this->media_per_page.' Offset '.$offset);

        //print_r($query);
        
        $this->media_list['present_page'] = (int)$this->page_number;

        foreach($query as $media){
            $this->media_list['data'][]= array('id'=> $media->ID,
                            'caption' => $media->post_excerpt,
                            'description' => $media->post_content,
                            'image' => wp_get_attachment_image($media->ID, $this->media_size), 
                            'alt' =>  get_post_meta( $media->ID, '_wp_attachment_image_alt', true),
                    );
        }

        $this->media_list['max_pages'] = $max_pages;
    }

    public function get_alt_missing_media(){

        /* 
        sql query for missing alt when alt filed '_wp_attachment_image_alt' is there in table

        SELECT post_id FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type = "attachment" AND post_mime_type LIKE "image/%") AND meta_key="_wp_attachment_image_alt" AND meta_value=""

        sql to find all the attachment with missing alt field '_wp_attachment_image_alt' in table

        SELECT ID FROM wp_posts WHERE post_type = "attachment" AND post_mime_type LIKE "image/%" AND ID not in (SELECT post_id from wp_postmeta where meta_key="_wp_attachment_image_alt")

        union of above two list will give all the attachment missing alt filed or empty alt filed

        geting total count 

        select count(*) as count from (SELECT post_id FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type = "attachment" AND post_mime_type LIKE "image/%") AND meta_key="_wp_attachment_image_alt" AND meta_value=""

        UNION

        SELECT ID FROM wp_posts WHERE post_type = "attachment" AND post_mime_type LIKE "image/%" AND ID not in (SELECT post_id from wp_postmeta where meta_key="_wp_attachment_image_alt")) as dum

        Get all the post from the post list

        select * from wp_posts where ID in (select post_id from (SELECT post_id FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type = "attachment" AND post_mime_type LIKE "image/%") AND meta_key="_wp_attachment_image_alt" AND meta_value=""

        UNION

        SELECT ID FROM wp_posts WHERE post_type = "attachment" AND post_mime_type LIKE "image/%" AND ID not in (SELECT post_id from wp_postmeta where meta_key="_wp_attachment_image_alt")) as dum) 

        */

        global $wpdb;

        $query1 = $wpdb->get_row('Select count(*) as count from (SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE post_id IN (SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_type = "attachment" AND post_mime_type LIKE "image/%") AND meta_key="_wp_attachment_image_alt" AND meta_value=""

        UNION

        SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_type = "attachment" AND post_mime_type LIKE "image/%" AND ID not in (SELECT post_id from '.$wpdb->prefix.'postmeta where meta_key="_wp_attachment_image_alt")) as dum');

        $total_results = ($query1->count);

        $max_pages = ceil($total_results / $this->media_per_page);

        $offset = ($this->page_number - 1) * $this->media_per_page;

        $query =  $wpdb->get_results('select * from '.$wpdb->prefix.'posts where ID in (select post_id from (SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE post_id IN (SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_type = "attachment" AND post_mime_type LIKE "image/%") AND meta_key="_wp_attachment_image_alt" AND meta_value=""

        UNION
        
        SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_type = "attachment" AND post_mime_type LIKE "image/%" AND ID not in (SELECT post_id from '.$wpdb->prefix.'postmeta where meta_key="_wp_attachment_image_alt")) as dum) Limit '.$this->media_per_page.' Offset '.$offset);

        //print_r($query);
        
        $this->media_list['present_page'] = (int)$this->page_number;

        foreach($query as $media){
            $this->media_list['data'][]= array('id'=> $media->ID,
                            'caption' => $media->post_excerpt,
                            'description' => $media->post_content,
                            'image' => wp_get_attachment_image($media->ID, $this->media_size), 
                            'alt' =>  get_post_meta( $media->ID, '_wp_attachment_image_alt', true),
                    );
        }

        $this->media_list['max_pages'] = $max_pages;
    }

    /*
        Register root for update single media
    */
    public function update_media_route(){
        register_rest_route( 
            'dvin508-seo/v1',
            '/update_media/',
            array(
                'methods' => 'POST',
                'callback' =>  array($this,'update_media'),
                'permission_callback' => array($this, 'check_permission')
            )
        );
    }

    /*
        Passing all the media detial at once and it will be updated
    */
    public function update_media( $request_data ){
        
        $parameters_list = $request_data->get_params();

        foreach($parameters_list as $parameters){
        $update = array(
            'ID'		=> $parameters['id'],
			'post_excerpt' => $parameters['caption'],		// Set image Caption (Excerpt) to sanitized title
			'post_content'	=> $parameters['description']	
        );
        wp_update_post($update);
        update_post_meta($parameters['id'] , '_wp_attachment_image_alt', $parameters['alt'] );
        }
        return array("result"=>true);
    }
    
}

new Dvin508_Media_api();