<?php

add_filter( 'register', 'dwprp_remove_registration_link' );

function dwprp_remove_registration_link( $registration_url ) {
	return __( 'Manual registration is disabled', 'dwprp' );
}

add_action( 'init', 'dwprp_redirect_registration_page' );

function dwprp_redirect_registration_page() {
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'register' ) {
		ob_start();
		wp_redirect( wp_login_url() );
		ob_clean();
	}
}