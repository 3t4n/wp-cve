<?php

class Bookero{

    static $api_version = 'v1';
    static $key;

    static function checkApiKey($key){
        $response = self::Query('check-key', array('key' => $key));
        if($response !== false && isset($response->result) && $response->result == 1){
            self::$key = $key;
            return $response->data->bookero_id;
        }
        return false;
    }

    static function getInquiries($status, $today = 0){
        $response = self::Query('inquiries', array('key' => self::$key, 'status' => $status, 'today' => $today));
        if($response !== false && isset($response->result) && $response->result == 1){
            return $response->data;
        }
        return array();
    }

    static function Query($query, $params = array(), $method = 'POST'){
        $post_vars = array();
        foreach($params as $key => $value){
            $post_vars []= $key.'='.urlencode($value);
        }

        $url = 'https://www.bookero.pl/api/'.self::$api_version.'/';
        $url .= $query;

        $ch = curl_init();
        if($method == 'POST'){

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $post_vars));
        }
        elseif($post_vars){
            $url .= '?'.implode('&', $post_vars);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec ($ch);

        curl_close ($ch);

        try{
            $response = json_decode($response);
            return $response;
        }
        catch(Exception $e){
            return false;
        }
    }

}