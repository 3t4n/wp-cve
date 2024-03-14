<?php
/**
 * Author: wbolt team
 * Author URI: https://www.wbolt.com
 */

class OCW_Sms_Aliyun
{


    public static function send($conf,$mobile,array $data)
    {
        $sms_param = [
            'PhoneNumbers'=>$mobile,
            'SignName'=>$conf['sign'],
            'TemplateCode'=>$conf['tpl'],
            'TemplateParam'=>json_encode($data,JSON_UNESCAPED_UNICODE),
            ];

        $sign_param = [
            "AccessKeyId" => $conf['id'],
            "Action"=>"SendSms",
            "Format"=>"json",
            "RegionId"=>"cn-hangzhou",
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => uniqid(mt_rand(0, 0xffff), true),
            "SignatureVersion" => "1.0",
            "Timestamp" => current_time("Y-m-d\TH:i:s\Z",1),
            "Version"=>"2017-05-25",
        ];

        $param = array_merge($sms_param,$sign_param);

        ksort($param);

        $sign_data = [];
        foreach ($param as $key => $value) {
            $sign_data[] = self::encode($key) . "=" . self::encode($value);
        }

        $string = "GET&%2F&" . self::encode(implode('&', $sign_data));

        $sign = base64_encode(hash_hmac("sha1", $string, $conf['secret'] . "&", true));

        $sign = self::encode($sign);

        $api = "https://dysmsapi.aliyuncs.com/?Signature={$sign}&".implode('&',$sign_data);

        $args = array(
            'headers'=>array(
                'user-agent' => 'Wordpress '.get_bloginfo( 'version' ).' / wbolt.com'
            ),
            'sslverify'=>false
        );

        $http = wp_remote_get($api,$args);

        if(is_wp_error($http)){
            $obj = new stdClass();
            $obj->Code = "wp-error";
            $obj->Message = $http->get_error_message();
            return $obj;
        }

        $data =  wp_remote_retrieve_body($http);
        $code = wp_remote_retrieve_response_code($http);
        if($code == 200){
            return json_decode($data);
        }
        $obj = new stdClass();
        $obj->Code = $code;
        $obj->Message = $data;
        return $obj;

    }

    public static function encode($value)
    {
        $value = urlencode($value);
        return str_replace(['+','*','%7E'],['%20','%2A','~'],$value);
    }

}