<?php
namespace Pagup\BetterRobots\Core;

class Option
{
    public static function all()
    {
        return get_option( 'robots_txt' );
    }

    public static function get($key)
    {
        $option = static::all();

        if (isset($option[$key])) {
            return $option[$key];
        }

        return;
        
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