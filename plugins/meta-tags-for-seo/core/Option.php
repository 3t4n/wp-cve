<?php
namespace Pagup\MetaTags\Core;

class Option
{
    public static function all()
    {
        return get_option( 'meta-tags-for-seo' );
    }

    public static function get($key)
    {
        $option = static::all();

        return $option[$key];
    }

    public static function check($key)
    {
        $option = static::all();
        return isset($option[$key]) && !empty($option[$key]);
    }

    public static function valid($option, $val)
    {
        return static::check($option) && static::get($option) == $val;
    }

    public static function post_meta($key)
    {
        global $post;
        return get_post_meta($post->ID, $key, true);
    }

    public static function sanitize_array( $array ) {
        foreach ( $array as $k => $v ) {
           if ( is_array( $v ) ) {
               $array[$k] =  self::sanitize_array( $v );
           } else {
               $array[$k] = sanitize_text_field( $v );
           }
        }
     
       return $array;                                                       
    }
}