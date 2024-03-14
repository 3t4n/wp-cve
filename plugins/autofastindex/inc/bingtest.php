<?php
include_once('logs.php');


function complete($site,$data,$email,$perma){

    try{

        $configapi = file_get_contents(plugin_dir_path(__FILE__) . '../api/config.json');
        $configapi = json_decode($configapi);
        $api_url = $configapi->completeIndex;
    
        $userId=$data->yandex_UserId;
        $hostId=$data->yandex_HostId;
        $auth=$data->yandex_AuthKey;
        $api=$data->bingapi;
        $postData=[
            'data' =>  base64_encode(wp_json_encode($data, JSON_PRETTY_PRINT)),
            "email" => $email,
            "site" => $site,
            "test" => false,
            "link" => $perma,
            "userId"=>$userId,
            "hostId"=>$hostId,
            "auth"=>$auth,
            "bing_ap" => base64_encode($api),
            ];
    
            
        $args = array(
            'body' => $postData,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
        );
    
    
        try{
            $apiResponse = wp_remote_post($api_url, $args);
        }catch(\Error $e){
            addLog($e,'apiRequest',$postData);
        }
   
    
        $apiResponse = $apiResponse['body'];
         
        if (isset(json_decode($apiResponse)->notification)) {
    
    
            file_put_contents(autoindex_upload. '/notification.json', wp_json_encode(["request_notify" => json_decode($apiResponse)->notification], JSON_PRETTY_PRINT));
    
        }
    
        
    
        return json_decode($apiResponse);


    }catch(\Error $e){
        addLog($e,'complete');

    }

 

}



function google($site, $data, $email, $perma = null)
{


    try{

        $configapi = file_get_contents(plugin_dir_path(__FILE__) . '../api/config.json');
        $configapi = json_decode($configapi);
        $api_url = $configapi->request_api;
    
        if (!$perma) {
            $perma = $site;
        }
    
        $send = [];
        $send['err'] = 1;
    
    
    
        $postRequest = [
            "data" =>  base64_encode(wp_json_encode($data, JSON_PRETTY_PRINT)),
            "type" => "google",
            "email" => $email,
            "site" => $site,
            "test" => $perma==null?true:false,
            "link" => $perma
        ];
    
    
        $args = array(
            'body' => $postRequest,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
        );
    
    
            
        try{
            $apiResponse = wp_remote_post($api_url, $args);
        }catch(\Error $e){
            addLog($e,'apiRequest',$postRequest);
        }
   
    
        $apiResponse = $apiResponse['body'];
    
        if (isset(json_decode($apiResponse)->notification)) {
    
    
            file_put_contents(autoindex_upload. '/notification.json', wp_json_encode(["request_notify" => json_decode($apiResponse)->notification], JSON_PRETTY_PRINT));
    
        }
    
    
        return json_decode($apiResponse);

    }catch(\Error $e){
        addLog($e,'google');

    }


}

function bing($site, $link, $api, $email,$data)
{

    try{

        
    $configapi = file_get_contents(plugin_dir_path(__FILE__) . '../api/config.json');
    $configapi = json_decode($configapi);
    $api_url = $configapi->request_api;


    $postRequest = [
        "data" =>  base64_encode(wp_json_encode($data, JSON_PRETTY_PRINT)),
        "type" => "bing",
        "email" => $email,
        "site" => $site,
        "link" => $link,
        "bing_ap" => base64_encode($api)
    ];


    $args = array(
        'body' => $postRequest,
        'timeout' => '5',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array(),
    );

                
    try{
        $apiResponse = wp_remote_post($api_url, $args);
    }catch(\Error $e){
        addLog($e,'apiRequest',$postRequest);
    }

    $apiResponse = $apiResponse['body'];



    if (isset(json_decode($apiResponse)->notification)) {


        file_put_contents(autoindex_upload. '/notification.json', wp_json_encode(["request_notify" => json_decode($apiResponse)->notification], JSON_PRETTY_PRINT));

    }

    return json_decode($apiResponse);

    }catch(\Error $e){
        addLog($e,'bing');


    }



}



function direct($site, $link,$data,$email){


    try{

        $configapi = file_get_contents(plugin_dir_path(__FILE__) . '../api/config.json');
        $configapi = json_decode($configapi);
        $api_url = $configapi->request_api;
    
    
        $postRequest = [
            "data" =>  base64_encode(wp_json_encode($data, JSON_PRETTY_PRINT)),
            "type" => "direct",
            "email" => $email,
            "site" => $site,
            "link" => $link,
        ];
    
    
        $args = array(
            'body' => $postRequest,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
        );
    
                
        try{
            $apiResponse = wp_remote_post($api_url, $args);
        }catch(\Error $e){
            addLog($e,'apiRequest',$postRequest);
        }
       
        $apiResponse = $apiResponse['body'];
    
    
        if (isset(json_decode($apiResponse)->notification)) {
    
    
            file_put_contents(autoindex_upload. '/notification.json', wp_json_encode(["request_notify" => json_decode($apiResponse)->notification], JSON_PRETTY_PRINT));
    
        }
    
        return json_decode($apiResponse);
    

    }catch(\Error $e){
        addLog($e,'direct');


    }
  

}


function yandex($site, $link, $userId,$hostId, $auth,$data,$email)
{


    try{

        $configapi = file_get_contents(plugin_dir_path(__FILE__) . '../api/config.json');
        $configapi = json_decode($configapi);
        $api_url = $configapi->request_api;
    
    
        $postRequest = [
            "data" =>  base64_encode(wp_json_encode($data, JSON_PRETTY_PRINT)),
            "type" => "yandex",
            "userId"=>$userId,
            "hostId"=>$hostId,
            "auth"=>$auth,
            "email" => $email,
            "site" => $site,
            "link" => $link,
        ];
    
    
        $args = array(
            'body' => $postRequest,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
        );
    
                
        try{
            $apiResponse = wp_remote_post($api_url, $args);
        }catch(\Error $e){
            addLog($e,'apiRequest',$postRequest);
        }

    
        $apiResponse = $apiResponse['body'];
    
    
    
        if (isset(json_decode($apiResponse)->notification)) {
    
    
            file_put_contents(autoindex_upload. '/notification.json', wp_json_encode(["request_notify" => json_decode($apiResponse)->notification], JSON_PRETTY_PRINT));
    
        }
    
        return json_decode($apiResponse);

    }catch(\Error $e){
        addLog($e,'yandex');


    }

 

}


?>