<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class FetchError{
    protected static $_errors = array(
        1 => "File is missing or has insufficient rights",
        2 => "File size too large",
        3 => "Timeout was reached",
        4 => "Unknown error",
        5 => "Sirv API request limit reached",
        6 => 'Uploading error',
        7 => 'Could not confirm successful upload'
    );

    protected static $_errors_desc = array(
        1 => "check if file exists at expected URL and has permission to be read.",
        2 => 'either upload the image manually to <a class="sirv-get-error-data" href="https://my.sirv.com/#/browse/" target="_blank">your Sirv account</a>; or replace it with a smaller image; or <a class="sirv-get-error-data" href="https://sirv.com/contact/" target="_blank">request</a> a higher limit; or upgrade to a <a class="sirv-get-error-data" href="https://sirv.com/help/resources/fetch-images/#File_size_limit" target="_blank">higher limit</a>.',
        3 => "clear the failed images and try again. Contact your hosting provider if this continues.",
        4 => 'clear the failed images and try again. <a class="sirv-get-error-data" href="https://sirv.com/contact/" target="_blank">Contact Sirv</a> if this error occurs again.',
        5 => '',
        6 => 'this file size probably exceeds the limit - try again or replace with a smaller image.',
        7 => 'clear failed images and they will re-synchronize.'

    );

    protected static $_t_errors = 'sirv_fetching_errors';

    public static function get_error_text($code){
        return self::$_errors[$code];
    }

    public static function get_errors_desc(){
        return self::$_errors_desc;
    }

    public static function get_error_code($error_msg){
        $result = array_search($error_msg, self::$_errors);
        $result = $result !== false ? $result : self::get_error_code_from_db($error_msg);

        return $result;
    }

    public static function get_error_code_from_db($error_msg){
        global $wpdb;
        //$t = $wpdb->prefix . self::$_t_errors;
        $t = self::get_base_db_prefix($wpdb) . self::$_t_errors;
        $escaped_text = '%' . $wpdb->esc_like($error_msg) . '%';
        $res = $wpdb->get_var("SELECT id FROM $t WHERE error_msg LIKE '$escaped_text'");

        if(empty($res)){
            return self::add_error($error_msg);
        }

        return $res;
    }

    protected static function add_error($error_msg){
        global $wpdb;
        $t = self::get_base_db_prefix($wpdb) . self::$_t_errors;
        $wpdb->insert($t, array('error_msg'=> $error_msg));
        return $wpdb->insert_id;
    }

    protected static function get_base_db_prefix($wpdb){
        $prefix = $wpdb->prefix;

        if( is_multisite() ) $prefix = $wpdb->get_blog_prefix(0);

        return $prefix;
    }

    public static function get_errors(){
        return self::$_errors;
    }

    public static function get_errors_from_db(){
        global $wpdb;
        $t = self::get_base_db_prefix($wpdb) . self::$_t_errors;

        return $wpdb->get_results("SELECT * from $t", ARRAY_A);
    }
}

?>
