<?php

if(!function_exists('cg_hash_function')){
    function cg_hash_function ($addString, $forwardedHash = '', $method = 'sha256') {

        if(!empty($forwardedHash)){
            if(strlen($forwardedHash)==32){// in case earlier md5 from previous version was send after update but not reload
                $method = 'md5';
            }
        }

        $hash = '29e1b161e5c1fa';// some random string, to keep whole functionality working right

        if($method=='sha256'){
            if(function_exists('hash')){
                $hash = hash('sha256',wp_salt( 'auth').$addString);
            }else{
                $hash = md5(wp_salt( 'auth').$addString);
            }
        }

        if($method=='md5'){
            $hash = md5(wp_salt( 'auth').$addString);
        }

        return $hash;

    }
}
