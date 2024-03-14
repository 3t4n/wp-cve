<?php

/**
 * Check is Buddypress Registration Completed.
 */
function youzify_is_bp_registration_completed() {

	if ( youzify_is_membership_page( 'register' ) && 'completed-confirmation' == bp_get_current_signup_step() ) {
		return true;
	}

	return false;
}


/**
 * # Register Buddypress Custom Template.
 */
function youzify_register_bp_membership_template() {

    if ( function_exists( 'bp_register_template_stack'  ) ) {
        bp_register_template_stack( 'youzify_register_bp_membership_templates_location', 0 );
    }

}

add_action( 'init', 'youzify_register_bp_membership_template', 9999 );

/**
 * # Register Buddypress Custom Template Location .
 */
function youzify_register_bp_membership_templates_location() {
    return YOUZIFY_TEMPLATE . '/membership';
}

/**
 * Check is Buddypress Reset Password Active.
 */
function youzify_is_bp_reset_password_active() {

    // Check if term Already Exist.
    $term = term_exists( 'request_reset_password', bp_get_email_tax_type() );

    if ( ! $term ) {
        return false;
    }

    return true;

}

/**
 * Send Reset Password Email.
 */
function youzify_bp_retrieve_password() {

    $errors = new WP_Error();

    if ( empty( $_POST['user_login'] ) ) {
        $errors->add( 'empty_username', __( 'Enter a username or email address.', 'youzify' ) );
    } elseif ( strpos( $_POST['user_login'], '@' ) ) {
        $user_data = get_user_by( 'email', trim( wp_unslash( sanitize_text_field( $_POST['user_login'] ) ) ) );
        if ( empty( $user_data ) )
            $errors->add( 'invalid_email', __( 'There is no user registered with that email address.', 'youzify' ) );
    } else {
        $login = trim( sanitize_text_field( $_POST['user_login'] ) );
        $user_data = get_user_by( 'login', $login );
    }

    /**
     * Fires before errors are returned from a password reset request.
     *
     * @since 2.1.0
     * @since 4.4.0 Added the `$errors` parameter.
     *
     * @param WP_Error $errors A WP_Error object containing any errors generated
     *                         by using invalid credentials.
     */
    do_action( 'lostpassword_post', $errors, $user_data );

    if ( $errors->get_error_code() )
        return $errors;

    if ( ! $user_data ) {
        $errors->add( 'invalidcombo', __( 'Invalid username or email.', 'youzify' ) );
        return $errors;
    }

    // Redefining user_login ensures we return the right case in the email.
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $key = get_password_reset_key( $user_data );

    if ( is_wp_error( $key ) ) {
        return $key;
    }

    if ( is_multisite() ) {
        $blogname = get_network()->site_name;
    } else {
        $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    }

    $args = array(
        'tokens' => array(
            'site.name' => $blogname,
            'password.reset.url' => network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ),
        ),
    );

    bp_send_email( 'request_reset_password', (int) $user_data->ID, $args );

    return true;
}