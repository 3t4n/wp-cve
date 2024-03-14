<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Sanitizes the values of an array recursivelly
 *
 * @param array $array
 *
 * @return array
 *
 */
function _wpsbc_array_sanitize_text_field( $array = array() ) {

    if( empty( $array ) || ! is_array( $array ) )
        return array();

    foreach( $array as $key => $value ) {

        if( is_array( $value ) )
            $array[$key] = _wpsbc_array_sanitize_text_field( $value );

        else
            $array[$key] = sanitize_text_field( $value );

    }

    return $array;

}


/**
 * Sanitizes the values of an array recursivelly and allows HTML tags
 *
 * @param array $array
 *
 * @return array
 *
 */
function _wpsbc_array_wp_kses_post( $array = array() ) {

    if( empty( $array ) || ! is_array( $array ) )
        return array();

    foreach( $array as $key => $value ) {

        if( is_array( $value ) )
            $array[$key] = _wpsbc_array_wp_kses_post( $value );

        else
            $array[$key] = wp_kses_post( $value );

    }

    return $array;

}

/**
 * Returns the current locale
 *
 * @return string
 *
 */
function wpsbc_get_locale() {

    return substr( get_locale(), 0, 2 );

}