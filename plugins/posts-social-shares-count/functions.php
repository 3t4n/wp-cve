<?php
/**
 * Functions file for PSSC
 * @package    WordPress
 * @subpackage Posts Social Shares Count
 * @since      March 2015
 * @author     Bishoy A.
 * @link       http://bishoy.me
 */

/**
 * Marin Function
 * @param  string  $fuction Function to call from the PsscShareCount class
 * @param  integer $post_id post ID
 * @return integer|string count
 */
function pssc_get_count( $function, $post_id = 0 ) {
        $cache = array();
        $cache = get_transient( 'pssc_counts' );
 		
        if ( ! $post_id )
                $post_id = get_the_ID();
               
        $key   = $function . '_' . $post_id;
        $count = isset( $cache[ $key ] ) ? $cache[ $key ] : '';
 
        if ( empty( $count ) && strlen( $count ) == 0 ) {
                $url = get_permalink( $post_id );
 
                if ( ! empty( $url ) ) {
                        require_once 'classes/share.count.php';
                        $share_counter = new PsscShareCount( $url );
                        $count = call_user_func( array( $share_counter, $function ) );
                }
 
                if ( empty( $count ) )
                        $count = '0';
 
                $cache[ $key ] = $count;
 
                set_transient( 'pssc_counts', $cache, HOUR_IN_SECONDS );
        }
 
        return $count;
}

/* Wrapper Functions */

function pssc_facebook( $post_id = 0 ) {
	return pssc_get_count( __FUNCTION__, $post_id );
}

/**
 * @deprecated 1.4.1
 */
function pssc_twitter( $post_id = 0 ) {
	return;
}

function pssc_linkedin( $post_id = 0 ) {
	return pssc_get_count( __FUNCTION__,$post_id );
}

function pssc_gplus( $post_id = 0 ) {
	return pssc_get_count( __FUNCTION__,$post_id );
}

function pssc_pinterest( $post_id = 0 ) {
	return pssc_get_count( __FUNCTION__,$post_id );
}

function pssc_delicious( $post_id = 0 ) {
	return pssc_get_count( __FUNCTION__,$post_id );
}

function pssc_stumble( $post_id = 0 ) {
	return pssc_get_count( __FUNCTION__,$post_id );
}

function pssc_all( $post_id = 0 ) {
	return pssc_get_count( __FUNCTION__,$post_id );
}