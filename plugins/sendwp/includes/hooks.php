<?php

if (sendwp_forwarding_enabled()) {
    add_action('phpmailer_init', [ \SendWP\mailer::class, 'factory' ]);
}

if( defined( 'WP_DEBUG' ) && WP_DEBUG ){
    add_filter( 'https_local_ssl_verify', '__return_false' );
    add_filter( 'https_ssl_verify', '__return_false' );
}

add_action('admin_notices', 'sendwp_forwarding_disabled_notice');
add_action('wp_ajax_sendwp_forwarding', 'sendwp_maybe_disable_forwarding');

// Register our pulse monitor.
add_action('init', 'sendwp_connect_pulse_monitor');
add_action('sendwp_heartbeat', 'sendwp_pulse_monitor');

add_action('init', 'sendwp_update_client_connection');
add_action('init', 'sendwp_get_client_status');