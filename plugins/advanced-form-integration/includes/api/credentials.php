<?php

function adfoin_save_credentials( $platform, $data ) {

    if ( $platform  && $data ) {

        $platform = sanitize_text_field( $platform );

        if( is_array( $data ) ) {
            $all_credentials = (array) maybe_unserialize( get_option( 'adfoin_credentials', array() ) );
            $all_credentials[ $platform ] = $data;
        
            update_option( 'adfoin_credentials', maybe_serialize( $all_credentials ) );
        }
    } 
}

function adfoin_read_credentials( $platform ) {
    $all_credentials = ( array ) maybe_unserialize( get_option( 'adfoin_credentials' ), array() );
    $credentials     = array();

    if( isset( $all_credentials[$platform] ) && count( $all_credentials[$platform] ) > 0 ) {
        $credentials = $all_credentials[$platform];
    }

    $credentials = apply_filters( 'adfoin_get_credentials', $credentials, $platform );

    return $credentials;
}