<?php

defined( 'ABSPATH' ) || exit;

class WRGRGM_Shortcode {

    public static function init( $atts ) {

        extract( shortcode_atts( array(
            'id' => ''
        ), $atts ) );

        return WRGRGM_Map::render( $id );
    }
}