<?php

function decrypt($body, $auth, $iv_sup, $key) {


    if(sodium_crypto_aead_aes256gcm_is_available()){
       

    }else{

    
    }

}

function sodium($body, $auth, $iv_sup, $key){
    $key_from_configuration = $key;
    $iv_from_http_header = $iv_sup;
    $auth_tag_from_http_header = $auth;
    $http_body = $body;
    $key = hex2bin($key_from_configuration);
    $iv = hex2bin($iv_from_http_header);
    $cipher_text = hex2bin($http_body . $auth_tag_from_http_header);
    $result = sodium_crypto_aead_aes256gcm_decrypt($cipher_text, null, $iv, $key);
    return $result;

}

function sodiumOld($body, $auth, $iv_sup, $key){
    $key_from_configuration = $key;
    $iv_from_http_header = $iv_sup;
    $auth_tag_from_http_header = $auth;
    $http_body = $body;
    $key = hex2bin($key_from_configuration);
    $iv = hex2bin($iv_from_http_header);
    $cipher_text = hex2bin($http_body . $auth_tag_from_http_header);
    $result = sodium_crypto_aead_aes256gcm_decrypt($cipher_text, null, $iv, $key);
    return $result;


}



?>