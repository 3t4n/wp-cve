<?php
namespace Pagup\AutoFocusKeyword\Core;

class Request
{
    public static function safe($value, $safe)
    {
        if ( isset( $value ) && !empty( $value ) && in_array( $value, $safe ) ) { 
            return sanitize_text_field( $value ); 
        }
        
        return "";
    }

    public static function check($value)
    {
        return isset( $value ) && !empty( $value ); 
    }

    public static function text($value)
    {
        return static::check($value) ? sanitize_text_field( $value ) : '';
    }

    public static function numeric($value)
    {
        if ( isset( $value ) && is_numeric( $value ) ) {
            return sanitize_text_field( $value );
        } else {
            return null;
        }
    }

    public static function array($array) {
        foreach ( (array) $array as $k => $v ) {
            if ( is_array( $v ) ) {
                // Recursive call for nested arrays
                $array[$k] = self::array($v);
            } else {
                $array[$k] = sanitize_key($v);
            }
        }
     
        return $array;                                                       
    }    
}
