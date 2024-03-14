<?php
namespace Pagup\Twitter\Core;

class Request
{
    public static function post($key, $safe)
    {

        if ( isset( $_POST[$key] ) && in_array( $_POST[$key], $safe ) ) 
        { 
            $request = sanitize_text_field( $_POST[$key] ); 
        }
        
        return $request ?? '';
    }

    public static function check($key)
    {

        return isset( $_POST[$key] ) && !empty( $_POST[$key] ); 
        
    }
}