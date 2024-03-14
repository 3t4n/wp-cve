<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RGM_Settings {

    private static $key = '__rgm_key';

    public static function get_key() {
        return get_option( self::$key );
    }

    public static function set_key( $gmap_key ) {
        return update_option( self::$key, $gmap_key );
    }
}