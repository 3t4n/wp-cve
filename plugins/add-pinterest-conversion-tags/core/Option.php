<?php
namespace Pagup\Pctag\Core;

class Option
{
    public static function all()
    {
        return get_option( 'pctag' );
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

        if ( isset($post) && !empty( get_post_meta($post->ID, $key, true) ))
        {
            return get_post_meta($post->ID, $key, true);
        }

        return '';
        
    }
}