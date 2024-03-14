<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
};


class CLP_Sanitize{
    // sanitize functions
	public static function sanitize_select( $input, $setting ) {
		$input   = sanitize_key( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
    }
    
	public static function sanitize_toggle( $input ) {
		return isset( $input ) && true == $input ? '1' : '';
    }
    
	public static function sanitize_float( $input ) {
		return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

	public static function sanitize_negative_int( $input ) {
		return is_numeric( $input ) ? $input : '0';
    }
    
    public static function sanitize_alpha_color( $input ) {

        if ( empty( $input ) || is_array( $input ) ) {
            return 'rgba(0,0,0,0)';
        }

        if ( false === strpos( $input, 'rgba' ) ) {
            return sanitize_hex_color( $input );
        }

        $input = str_replace( ' ', '', $input );
        sscanf( $input, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

        return 'rgba('.$red.','.$green.','.$blue.','.$alpha.')';
    }
}