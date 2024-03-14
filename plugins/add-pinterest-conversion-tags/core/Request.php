<?php
namespace Pagup\Pctag\Core;

class Request
{
    public static function post($val, $safe)
    {

        if ( isset( $_POST[$val] ) && in_array( $_POST[$val], $safe ) ) 
        { 
            $request = sanitize_text_field( $_POST[$val] ); 
        } 
        
        return $request ?? false;
    }

    public static function check($key)
    {

        return isset( $_POST[$key] ) && !empty( $_POST[$key] ); 
        
    }
}