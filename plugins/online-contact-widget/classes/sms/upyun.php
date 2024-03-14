<?php
/**
 * Author: wbolt team
 * Author URI: https://www.wbolt.com
 */

class OCW_Sms_Upyun
{


    public static function send($conf,$mobile,$data)
    {
        $api = 'https://sms-api.upyun.com/api/messages';

        $args = array(
            'headers'=>array(
                'Authorization'=>$conf['secret'],
                'user-agent' => 'Wordpress '.get_bloginfo( 'version' ).' / wbolt.com'
                ),
            'sslverify'=>false,
            'body'=>array('mobile'=>$mobile,'template_id'=>$conf['tpl'],'vars'=>implode('|',array_values($data)))
        );

        $http = wp_remote_post($api,$args);
        if(is_wp_error($http)){
            $obj = new stdClass();
            $obj->code = 1;
            $obj->report_code = 'wp-error';
            $obj->error_code = $http->get_error_message();
            return $obj;
        }

        $data =  wp_remote_retrieve_body($http);
        $code = wp_remote_retrieve_response_code($http);
        if($code == 200){
            return json_decode($data);
        }

        $obj = new stdClass();
        $obj->code = 1;
        $obj->report_code = $code;
        $obj->error_code = $data;
        return $obj;

    }
}