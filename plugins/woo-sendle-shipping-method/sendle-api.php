<?php

function calling_sendle_api($formsetting,$data,$path,$type){

    $mode = $formsetting['mode'];
    if($mode  == "live"){
        $apiurl = "https://api.sendle.com";
    }else{
        $apiurl = "https://sandbox.sendle.com";
    }
    $api_id = $formsetting['api_id'];
    $api_key = $formsetting['api_key'];

    if($path == "labels"){

        $url = $data["label_url"];
 
    }else{
        $url = $apiurl.$path;
    }

 
    $args= array();

    if(!empty($api_key ) && !empty($api_id)){
        $args["headers"] = array(
        'Authorization' => 'Basic '.base64_encode( $api_id.':'.$api_key )
        );
    }

    $args["user-agent"] = "WooCommerce Sendle Shipping Method";

    if($type == "post"){

        $args["body"] = $data;
        $response =  wp_remote_post( $url, $args );
        $content = wp_remote_retrieve_body( $response );

 
    }else{

        $response =  wp_remote_get( $url, $args );
        $content = wp_remote_retrieve_body( $response );
        if($path == "labels"){

            return $content ;
        }
    }

 
    $result = json_decode( $content); // show target page

 
    if(!empty($result->error)){


        file_put_contents(ERROR_FILE, date("y-M-D h:i:s")." API: ".serialize($result)."\n", FILE_APPEND );
        return false;
    }
    return $result;
 
}