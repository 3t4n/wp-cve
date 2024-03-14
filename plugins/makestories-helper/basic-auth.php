<?php
function mscpt_json_basic_auth_handler( $user ) {
    global $mscpt_wp_json_basic_auth_error;
    $mscpt_wp_json_basic_auth_error = null;
    // Don't authenticate twice
    if ( ! empty( $user ) ) {
        return $user;
    }
    // Check that we're trying to authenticate
    if ( !isset( $_SERVER['PHP_AUTH_USER'] ) ) {
        return $user;
    }
    $username = sanitize_text_field($_SERVER['PHP_AUTH_USER']);
    $password = sanitize_text_field($_SERVER['PHP_AUTH_PW']);
    /**
     * In multi-site, wp_authenticate_spam_check filter is run on authentication. This filter calls
     * get_currentuserinfo which in turn calls the determine_current_user filter. This leads to infinite
     * recursion and a stack overflow unless the current function is removed from the determine_current_user
     * filter during authentication.
     */
    remove_filter( 'determine_current_user', 'mscpt_json_basic_auth_handler', 20 );
    $user = wp_authenticate( $username, $password );
    add_filter( 'determine_current_user', 'mscpt_json_basic_auth_handler', 20 );
    if ( is_wp_error( $user ) ) {
        $mscpt_wp_json_basic_auth_error = $user;
        return null;
    }
    $mscpt_wp_json_basic_auth_error = true;
    return $user->ID;
}
add_filter( 'determine_current_user', 'mscpt_json_basic_auth_handler', 100 );
function mscpt_json_basic_auth_error( $error ) {
    // Passthrough other errors
    if ( ! empty( $error ) ) {
        return $error;
    }
    global $mscpt_wp_json_basic_auth_error;
    return $mscpt_wp_json_basic_auth_error;
}
add_filter( 'rest_authentication_errors', 'mscpt_json_basic_auth_error' );