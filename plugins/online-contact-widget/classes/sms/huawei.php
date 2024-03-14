<?php
/**
 * Author: wbolt team
 * Author URI: https://www.wbolt.com
 */

class OCW_Sms_Huawei
{


    public static function send($conf,$mobile,array $data)
    {

        $sms_param = [
            'from'=>$conf['channel'],
            'to'=>$mobile,
            'signature'=>$conf['sign'],
            'templateId'=>$conf['tpl'],
            'templateParas'=>json_encode(array_values($data)),
            ];
        //error_log(print_r([$mobile,$conf,$sms_param],true),3,__DIR__.'/debug.txt');

        //Base64 (SHA256 (Nonce + Created + Password))
        $created = current_time('Y-m-d\TH:i:s\Z');
        $nonce = md5($created);
        $sign = base64_encode(hash("sha256", $nonce.$created.$conf['secret'], true));

        $api = str_replace(':443','',$conf['api'].'/sms/batchSendSms/v1');


        $xs = sprintf('UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"', $conf['id'], $sign, $nonce, $created);
        $args = array(
            'headers'=>array(
                'authorization' => 'WSSE realm="SDP",profile="UsernameToken",type="Appkey"',
                'x-wsse' => $xs,
            ),
            'user-agent' => 'Wordpress '.get_bloginfo( 'version' ).' / wbolt.com',
            'sslverify'=>false,
            'body' => $sms_param
        );

        $http = wp_remote_post($api,$args);

        if(is_wp_error($http)){
            $obj = new stdClass();
            $obj->code = "wp-error";
            $obj->message = $http->get_error_message();
            //error_log(print_r($obj,true),3,__DIR__.'/debug.txt');
            return $obj;
        }

        $data =  wp_remote_retrieve_body($http);
        $code = wp_remote_retrieve_response_code($http);
        //error_log(print_r([$mobile,$data,$code],true),3,__DIR__.'/debug.txt');
        return json_decode($data);

        /*if($code == 200){

        }
        $obj = new stdClass();
        $obj->code = $code;
        $obj->message = json_decode($data,true);
        return $obj;*/

    }

    public static function encode($value)
    {
        $value = urlencode($value);
        return str_replace(['+','*','%7E'],['%20','%2A','~'],$value);
    }

    public static function sign($conf)
    {

    }

}