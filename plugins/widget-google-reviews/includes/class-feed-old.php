<?php

namespace WP_Rplg_Google_Reviews\Includes;

class Feed_Old {

    public static $widget_fields = array(
        'place_name'           => '',
        'place_id'             => '',
        'place_photo'          => '',
        'text_size'            => '120',
        'dark_theme'           => '',
        'view_mode'            => 'list',
        'pagination'           => '5',
        'max_width'            => '',
        'max_height'           => '',
        'refresh_reviews'      => true,
        'hide_based_on'        => false,
        'hide_reviews'         => false,
        'centered'             => false,
        'reduce_avatars_size'  => true,
        'open_link'            => true,
        'nofollow_link'        => true,
        'lazy_load_img'        => true,
        'def_reviews_link'     => false,
        'reviews_lang'         => '',
    );

    public function __construct() {
    }

    public function get_feed($id, $params) {
        $feed_content = array();
        $conn = array();
        $opts = array();

        foreach (self::$widget_fields as $variable => $value) {
            if ($variable == 'place_id' || $variable == 'place_name' || $variable == 'place_photo' || $variable == 'reviews_lang') {
                $key = str_replace('place_', '', $variable);
                $key = str_replace('reviews_', '', $key);
                $conn[$key] = !isset($params[$variable]) ? self::$widget_fields[$variable] : esc_attr($params[$variable]);
            } elseif ($variable == 'refresh_reviews') {
                $conn['refresh'] = isset($params[$variable]) && $params[$variable] == true ? true : false;
            } else {
                $opts[$variable] = !isset($params[$variable]) ? self::$widget_fields[$variable] : esc_attr($params[$variable]);
            }
        }

        $conn['platform'] = 'google';
        $feed_content['connections'] = array($conn);
        $feed_content['options'] = $opts;

        return (object) array('ID' => $id, 'post_content' => json_encode($feed_content));
    }
}
