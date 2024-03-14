<?php

if (! defined ( 'ABSPATH' )){
    exit (); // Exit if accessed directly
}

function snappay_sign_post_data($post_data, $signKey){
    $sign_type = $post_data['sign_type'];

    $para_filter = snappay_paraFilter($post_data);

    $para_sort = snappay_argSort($para_filter);

    $prestr = snappay_createLinkstring($para_sort);

    $mysign = snappay_md5Sign($prestr, $signKey);

    $para_sort['sign'] = $mysign;
    $para_sort['sign_type'] = $sign_type;

    return $para_sort;
}

function snappay_sign_verify($post_data, $signKey){
    if(!isset($post_data) || !isset($post_data['sign'])){
        return false;
    }

    $sign = $post_data['sign'];

    $para_filter = snappay_paraFilter($post_data);

    $para_sort = snappay_argSort($para_filter);

    $prestr = snappay_createLinkstring($para_sort);

    $mysign = snappay_md5Sign($prestr, $signKey);

    if($sign === $mysign){
        return true;
    }else{
        return false;
    }
}

function snappay_paraFilter($para) {
    $para_filter = array();
    while (list ($key, $val) = snappay_myEach ($para)) {
        if($key == "sign" || $key == "sign_type" || $val == "")continue;
        else    $para_filter[$key] = $para[$key];
    }
    return $para_filter;
}

function snappay_argSort($para) {
    ksort($para);
    reset($para);
    return $para;
}

function snappay_createLinkstring($para) {
    $arg  = "";
    while (list ($key, $val) = snappay_myEach ($para)) {
        $arg.=$key."=".$val."&";
    }
    $arg = substr($arg,0,strlen($arg)-1);
    
    return $arg;
}

function snappay_md5Sign($prestr, $key) {
    $prestr = $prestr . $key;
    return md5($prestr);
}

function snappay_myEach(&$arr) {
    $key = key($arr);
    $result = ($key === null) ? false : [$key, current($arr), 'key' => $key, 'value' => current($arr)];
    next($arr);
    return $result;
}
?>