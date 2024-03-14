<?php
namespace Pagup\Twitter\Core;

class Option
{
    public static function all()
    {
        return get_option( 'add-twitter-pixel' );
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
}