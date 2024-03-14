<?php
//error_reporting(0);
/*
matching image with missing alt

SELECT post_content FROM `wp_posts` WHERE post_type in ('page','post') AND ( post_content REGEXP '^.*<img.*(alt).*(=).*\".*\".*>.*$' )

*/

class Dvin508_Post_api{

    public $page_number;
    public $post_type;
    public $post_per_page = 25;
    public $post_list = array();

    public function __construct(){
        add_action( 'rest_api_init', array($this,'post_route') );
        add_action( 'rest_api_init', array($this,'update_post_route') );
    }

    public function post_route(){
        register_rest_route( 
            'dvin508-seo/v1',
            '/post_type/(?P<post_type>[a-z]+)/(?P<page_number>\d+)',
            array(
                'methods' => 'GET',
                'callback' =>  array($this,'get_post_list'),
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

    public function get_post_list( $request_data ){
        /* getting the varibles from the url */
        $present_page_array = $request_data->get_url_params();

        $this->page_number = $present_page_array['page_number'];
        $this->post_type = $present_page_array['post_type'];

        global $wpdb;

        $query1 = $wpdb->get_row('Select count(*) as count from '.$wpdb->prefix.'posts where post_type = "'.$this->post_type.'" AND post_status = "publish" AND post_content LIKE "%<img%>%"');
        $total_results = ($query1->count);

        $max_pages = ceil($total_results / $this->post_per_page);

        $offset = ($this->page_number - 1) * $this->post_per_page;

        $query =  $wpdb->get_results('Select ID, post_content, post_title from '.$wpdb->prefix.'posts where post_type = "'.$this->post_type.'" AND post_status = "publish" AND post_content LIKE "%<img%>%" Limit '.$this->post_per_page.' Offset '.$offset);

        $this->post_list['present_page'] = (int)$this->page_number;

        $this->post_list['max_pages'] = $max_pages;

        $this->post_list['data'] = $this->prepare_image_data($query);

        return $this->post_list;
    }

    function prepare_image_data($query){

        $data = array();

        foreach($query as $post){
            $data[] = array( 'id' => $post->ID,
                             'title'=>$post->post_title,
                            'images' => $this->extract_images_src($post->post_content));
        }

        return $data;
    }

    function extract_images_src($post_content){
        $doc = new DOMDocument();
        $doc->validateOnParse = false;
        @$doc->loadHTML($post_content);
        $xml = simplexml_import_dom($doc);
        $images = $xml->xpath('//img');
        $count = 0;
        foreach ($images as $img)
        {
           
            $matches[$count]['src']= (string)$img['src'];
            if(isset($img['alt'])){
                $matches[$count]['alt']= (string)$img['alt'];
            }else{
                $matches[$count]['alt'] = "";
            }
            $count++;
        }
        
        return $matches;
    }

    /*
        Register root for update single post
    */
    public function update_post_route(){
        register_rest_route( 
            'dvin508-seo/v1',
            '/update_post/',
            array(
                'methods' => 'POST',
                'callback' =>  array($this,'update_post'),
                'permission_callback' => array($this, 'check_permission')
            )
        );
    }

    /*
        Passing all the media detial at once and it will be updated
    */
    public function update_post( $request_data ){
        
        $parameters_list = $request_data->get_params();

        foreach($parameters_list as $parameters){
        $update = array(
            'ID'		=> $parameters['id'],
			'post_content' => $this->content_merge($parameters['id'], $parameters['images'])		// Set image Caption (Excerpt) to sanitized title
        );
        
        wp_update_post($update);
        }
        return array("result"=>true);
    }

    /* function to insert alt tag in existing image */
    public function content_merge($post_id, $post_images){
        /* step 1: use reg expression to match image based on image url
            step 2: extract that complete image and put it in Doomparser
            step  3: add alt to image using doomparser
            step 4:get new image from doomparser
            step 5: match the image again in content and replace that image with our
                    new doomparsed image 
            step 6 repeat the whole step for next image
        */
        
       $post_content = get_post_field('post_content', $post_id);

       $doc = new DOMDocument();
       $doc->loadHTML($post_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xml = simplexml_import_dom($doc);
        $images = $xml->xpath('//img');
        $count = 0;
        foreach ($images as $img)
        {
            $img_alt = $this->get_alt_tag($img['src'], $post_images);
            $img['alt'] = $img_alt;
            $count++;
        }
        $html = $doc->saveHTML();
        return($html);
        
        //print_r($post_images);
    }

    /* get corrosponding alt tag given the src */
    public function get_alt_tag($img_src, $post_images){
        foreach($post_images as $image){
            if($image['src']==$img_src){
                return $image['alt'];
            }
        }
        return false;
    }

}

new Dvin508_Post_api();