<?php
namespace Pagup\MetaTags\Core;

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

    public function array( $array ) {
        foreach ( (array) $array as $k => $v ) {
           if ( is_array( $v ) ) {
               $array[$k] =  array( $v );
           } else {
               $array[$k] = sanitize_text_field( $v );
           }
        }
     
       return $array;                                                       
     }
}