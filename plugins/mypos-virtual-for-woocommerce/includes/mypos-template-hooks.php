<?php

defined( 'ABSPATH' ) || exit;

/**
 * Auth.
 *
 * @see mypos_output_auth_header()
 * @see mypos_output_auth_footer()
 */
add_action( 'mypos_auth_page_header', 'mypos_output_auth_header', 10 );
add_action( 'mypos_auth_page_footer', 'mypos_output_auth_footer', 10 );