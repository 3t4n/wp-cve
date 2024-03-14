<?php

/**
 * Get Provider Data.
 */
function youzify_auth_get_user_profile( $provider ) {

	// Get Adapter
	$adapter = youzify_auth_get_adapter( $provider );

    if ( $adapter->isUserConnected() ) {
    	$profile = $adapter->getUserProfile();
    	return $profile;
    }

    // Display Can't Connect to provider Message
    youzify_auth_redirect( 'cant_connect' );

}

/**
 * Get Provide Adapter.
 */
function youzify_auth_get_adapter( $provider ) {

	// Inculde Authetification.
	if ( ! class_exists( 'Hybrid_Auth', false ) ) {
    	require_once( YOUZIFY_CORE . 'hybridauth/Hybrid/Auth.php' );
	}

	if ( ! class_exists( 'Hybrid_Endpoint', false ) ) {
	    require_once( YOUZIFY_CORE . 'hybridauth/Hybrid/Endpoint.php' );
	}

	// Return Adapter
	return Hybrid_Auth::getAdapter( $provider );
}

/**
 * Redirect User.
 */
function youzify_auth_redirect( $code, $redirect_to = null, $type = null ) {

    // Init Array.
    $messages = array();

    // Get Redirect Url.
    $redirect_url = ! empty( $redirect_to ) ? $redirect_to : home_url('\?e=' . $code );

    // Redirect User.
    wp_redirect( $redirect_url );

    // Exit.
    exit;

}