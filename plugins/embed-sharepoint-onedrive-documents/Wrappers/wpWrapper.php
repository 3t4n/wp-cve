<?php

namespace MoSharePointObjectSync\Wrappers;


class wpWrapper{

    private static $instance;

    public static function getWrapper(){
        if(!isset(self::$instance)){
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public static function mo_urlencode($url, $esc_str='') {
        $newurl = '';
        $url = str_split($url);
        foreach($url as $str) {
            if($str == ' ' && ' ' != $esc_str) $newurl .= '%20';
            else if($str == '`' && '`' != $esc_str) $newurl .= '%60';
            else if($str == "'" && "'" != $esc_str) $newurl .= '%27%27';
            else if($str == '#' && '#' != $esc_str) $newurl .= '%23';
            else if($str == '^' && '^' != $esc_str) $newurl .= '%5E';
            else if($str == '}' && '}' != $esc_str) $newurl .= '%7D';
            else if($str == '{' && '{' != $esc_str) $newurl .= '%7B';
            else if($str == '.' && '.' != $esc_str) $newurl .= '%2E';
            else $newurl .= $str;
        }
        return $newurl;
    }

    public static function mo_sps_set_feedback_config($key, $val) {
        $feedback_config = wpWrapper::mo_sps_get_option(pluginConstants::FEEDBACK_CONFIG);
        $feedback_config[$key] = $val;
        wpWrapper::mo_sps_set_option(pluginConstants::FEEDBACK_CONFIG, $feedback_config);
    }

 
    public static function mo_sps_search_data_refiner($uri, $check_text, $query_text, $response) {
        $result_array = $response['query']['PrimaryQueryResult']['RelevantResults']['Table']['Rows']['results'];
        $element_count = count($result_array);
        $search_data = $Folders = $Files = array();

        for($i = 0; $i < $element_count; $i++) {
            $last_modified_time = $result_array[$i]['Cells']['results']['8']['Value'];
            $title = $result_array[$i]['Cells']['results']['2']['Value'];
            $path = $result_array[$i]['Cells']['results']['5']['Value'];
            $is_container = $result_array[$i]['Cells']['results']['0']['Value'];
            
            $key = ($is_container == 'false') ? count($Files) : count($Folders);
            if($is_container == 'false') {
                $size = $result_array[$i]['Cells']['results']['1']['Value'];
                $parent_path = $result_array[$i]['Cells']['results']['4']['Value'];
                $preview_url = $result_array[$i]['Cells']['results']['6']['Value'];
                $Files[$key]['title'] = $title;
                $Files[$key]['path'] = $path;
                $Files[$key]['size'] = $size;
                $Files[$key]['parent_path'] = $parent_path;
                $Files[$key]['date_time'] = $last_modified_time;
                $Files[$key]['preview_url'] = $preview_url;
            } else {
                $Folders[$key]['title'] = $title;
                $Folders[$key]['path'] = $path;
                $Folders[$key]['date_time'] = $last_modified_time;
            }
        }
        $search_data['query_text'] = $query_text;
        $search_data['check_text'] = $check_text;
        $search_data['site_uri'] = $uri;
        $search_data['files'] = $Files;
        $search_data['folders'] = $Folders;
        return $search_data;
    }


    public static function mo_sps_set_option($key, $value){
        update_option($key,$value);
    }

    public static function mo_sps_get_option($key){
        return get_option($key);
    }

    public static function mo_sps_delete_option($key){
        return delete_option($key);
    }

    public static function mo_sps__show_error_notice($message){
        self::mo_sps_set_option(pluginConstants::notice_message,$message);
        $hook_name = 'admin_notices';
        remove_action($hook_name,[self::getWrapper(),'mo_sps_success_notice']);
        add_action($hook_name,[self::getWrapper(),'mo_sps_error_notice']);
    }

    public static function mo_sps__show_success_notice($message){
        self::mo_sps_set_option(pluginConstants::notice_message,$message);
        $hook_name = 'admin_notices';
        remove_action($hook_name,[self::getWrapper(),'mo_sps_error_notice']);
        add_action($hook_name,[self::getWrapper(),'mo_sps_success_notice']);
    }

    public function mo_sps_success_notice(){
        $class = "updated";
        $message = self::mo_sps_get_option(pluginConstants::notice_message);
        echo "<div style='margin:5px 0' class='" . esc_attr($class) . "'> <p>" . esc_attr($message) . "</p></div>";
    }

    public function mo_sps_error_notice(){
        $class = "error";
        $message = self::mo_sps_get_option(pluginConstants::notice_message);
        echo "<div style='margin:5px 0' class='" . esc_attr($class) . "'> <p>" . esc_attr($message) . "</p></div>";
    }

    /**
     * @param string $data - the key=value pairs separated with &
     * @return string
     */
    public static function mo_sps_encrypt_data($data, $key) {
        $key    = openssl_digest($key, 'sha256');
        $method = 'aes-128-ecb';
        $strCrypt = openssl_encrypt ($data, $method, $key,OPENSSL_RAW_DATA||OPENSSL_ZERO_PADDING);
        return base64_encode($strCrypt);
    }

    public static function mo_sps_get_domain_from_url($url){

        $scheme = parse_url($url, PHP_URL_SCHEME);
        $domain = '';
        if($scheme == 'http'){
            $domain = str_replace('http://', '', $url);
        } else {
            $domain = str_replace('https://', '', $url);
        }
        $domain = rtrim($domain,'/');

        return $domain;
    }


    /**
     * @param string $data - crypt response from Sagepay
     * @return string
     */
    public static function mo_sps_decrypt_data($data, $key) {
        $strIn = base64_decode($data);
        $key    = openssl_digest($key, 'sha256');
        $method = 'AES-128-ECB';
        $ivSize = openssl_cipher_iv_length($method);
        $iv     = substr($strIn,0,$ivSize);
        $data   = substr($strIn,$ivSize);
        $clear  = openssl_decrypt ($data, $method, $key, OPENSSL_RAW_DATA||OPENSSL_ZERO_PADDING, $iv);

        return $clear;
    }

    public static function mo_api__checkPasswordPattern($password)
    {
        $pattern = '/^[(\w)*(\!\@\#\$\%\^\&\*\.\-\_)*]+$/';
        return !preg_match($pattern, $password);
    }

    public static function mo_sps_deactivate(){
        delete_option('mo_sps_admin_password');
        delete_option('mo_sps_admin_customer_key');
        delete_option('mo_sps_admin_api_key');
        delete_option('mo_sps_customer_token');

    }

    public static function mo_sps_is_customer_registered() {

        $email       = get_option( 'mo_sps_admin_email' );
        $customerKey = get_option( 'mo_sps_admin_customer_key' );

        if ( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
            return 0;
        } else {
            return 1;
        }
    }

    public static function mo_azure_sync_wp_remote_call($url, $args = array(), $is_get=false){
        if(!$is_get)
            $response = wp_remote_post($url, $args);
        else
            $response = wp_remote_get($url, $args);
        if(!is_wp_error($response)){
            return $response['body'];
        } else {
            self::mo_sps__show_error_notice('Unable to connect to the Internet. Please try again.');
            return false;
        }
    }

    public static function mo_sps_get_image_src($imageName){
        return esc_url(plugin_dir_url(MO_SPS_PLUGIN_FILE).'images/'.$imageName);
    }

}