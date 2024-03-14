<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MapSettings {

    private static $instance;

    private static $key = '_rgm_map_settings';

    public static function initialize() {
        if ( empty(self::$instance) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function get( $post_id ) {

        return get_post_meta( $post_id, self::$key, true );
    }
}