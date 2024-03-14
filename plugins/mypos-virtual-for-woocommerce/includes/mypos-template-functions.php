<?php

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'mypos_output_auth_header' ) ) {

    /**
     * Output the Auth header.
     */
    function mypos_output_auth_header()
    {
        mypos_get_template('auth/header.php');
    }
}

if ( ! function_exists( 'mypos_output_auth_footer' ) ) {

    /**
     * Output the Auth footer.
     */
    function mypos_output_auth_footer()
    {
        mypos_get_template( 'auth/footer.php' );
    }
}
