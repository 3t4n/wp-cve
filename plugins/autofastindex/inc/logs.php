<?php



function addLog($e,$location='default',$request=null){


    try{

        $configapi = file_get_contents(plugin_dir_path(__FILE__) . '../api/config.json');
        $configapi = json_decode($configapi);
        $api_url = $configapi->log;
    
        $get = file_get_contents(autoindex_upload . '/settings.json');
        $data = json_decode($get);
    
        $postData = [
            'root' => 'firstPageRankError',
            'withFile' => 1,
            'body' => json_encode([
                "user" => $data,
                "type" => 'wordpressPluginError',
                "error" => $e,
                "location" => $location,
                "Message" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
                "date"=> date("Y-m-d h:i:sa"),
                "payload" => $request
            ])
        ];
        
        $args = array(
            'body' => $postData,
            'headers' => array(),
            'cookies' => array(),
        );
        $apiResponse = wp_remote_post($api_url, $args);

    }catch(\Error $e){

    }








}


?>