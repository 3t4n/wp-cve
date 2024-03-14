<?php
namespace Pagup\BetterRobots\Core;

class Request
{
    public static function safe($val, $safe)
    {

        if ( isset( $_POST[$val] ) && in_array( $_POST[$val], $safe ) ) 
        { 
            
            return sanitize_text_field( $_POST[$val] );

        } else {

            return "";

        }
        
    }

    public static function text($key)
    {
        if ( isset( $_POST[$key] ) && !empty( $_POST[$key] ) ) {

            return sanitize_text_field( $_POST[$key] );

        } else {

            return "";

        }
    }
    
    public static function textarea($key)
    {
        if ( isset( $_POST[$key] ) && !empty( $_POST[$key] ) ) {

            return sanitize_textarea_field( $_POST[$key] );

        } else {

            return "";

        }
    }

    public static function numeric($key)
    {
        if ( isset( $_POST[$key] ) && is_numeric( $_POST[$key] ) ) {

            return sanitize_text_field( $_POST[$key] );

        } else {

            return "";

        }
    }

    public static function check($key)
    {

        return isset( $_POST[$key] ) && !empty( $_POST[$key] ); 
        
    }

    public static function array( $array ) {
        if (!is_array($array)) {
            return;
        }
        
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