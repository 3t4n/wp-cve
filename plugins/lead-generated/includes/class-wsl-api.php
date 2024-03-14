<?php

class Wsl_Api{
    
    public $api_url = 'https://app.leadgenerated.com/public/api/';

    
    private $api_key = false;
    
    public function __construct($api_key = false) {
        if($api_key){
            $this->set_api_key($api_key);
        }
    }
    
    public function set_api_key($key){
        $this->api_key = $key;
    }
    
    public function get_api_key(){
        if($this->api_key){
            return $this->api_key;
        }else{
            $settings = lgcrm_get_settings();
            if(isset($settings['api_key']) && $settings['api_key'] != ''){
                return $settings['api_key'];
            }
        }
    }
    
    public function is_api_key(){
        return '';
    }
    
    public function get_base_url(){
        return $this->api_url;
    }
    
    public function get_companies(){
        $url = $this->get_base_url().'v2/companies/validate_api_key';
        $site_info = array("site_name"=>get_bloginfo(),"site_url"=>site_url());
        $response = $this->call($url,$site_info,'POST');
        // echo '<pre>';
        // print_r($response);
        // echo '</pre>';die();
        //lgcrm_update_setting('send_to_company',$data->source_id);
        //$prev_setting = lgcrm_get_settings();
        // echo $prev_setting['send_to_company'];
        if($response->type == 'duplicate' && $response->site_data->site_url){
            if($response->site_data->site_url == site_url()){
                $response->type = 'success';
            }
        }
        if($response->type == 'error'){
            $settings = array(
                "api_key" => "",
                "send_to_crm" => 1,
                "send_to_company" => ""
            );
            update_option('wsl_settings', $settings);
        }elseif($response->type == 'success'){
            lgcrm_update_setting("send_to_company",$response->data->id);
        }
        return $response;
    }
    
    public function call($url, $post_data = array(), $method = 'POST'){
        $headers = array();
        $headers['Accept'] = "application/json";
        $headers['Content-Type'] = "application/json";
        $headers['Thrive-Api-Key'] = $this->get_api_key();
        
        $args = array(
            'headers' => $headers,
            'timeout' => '30',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
        );
        
        if($method == 'GET'){
            $response = wp_remote_get( $url,  $args);
        }elseif($method == 'POST'){
            $args['body'] = json_encode($post_data);
            $response = wp_remote_post( $url, $args );
        }else{
            $args['method'] = $method;
            $response = wp_remote_request( $url, $args );
        }
        
        $body = wp_remote_retrieve_body( $response );
        return json_decode($body);
    }
    
        public function callTypeFormAuth($url, $authorization, $method = 'POST'){
        $headers = array();
        $headers['Accept'] = "application/json";
        $headers['Content-Type'] = "application/json";
        $headers['Authorization'] = $authorization;
        
        $args = array(
            'headers' => $headers,
            'timeout' => '30',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
        );
        
        if($method == 'GET'){
            $response = wp_remote_get( $url,  $args);
        }elseif($method == 'POST'){
            $args['body'] = json_encode($post_data);
            $response = wp_remote_post( $url, $args );
        }else{
            $args['method'] = $method;
            $response = wp_remote_request( $url, $args );
        }
        
        $body = wp_remote_retrieve_body( $response );
        return json_decode($body);
    }
    
}

