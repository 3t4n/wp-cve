<?php

function sendwp_get_server_url()
{
    $server_url = ( defined('SENDWP_SERVER_URL') ) ? SENDWP_SERVER_URL : 'https://app.sendwp.com';
    return trailingslashit( $server_url );
}

function sendwp_get_request_auth_header()
{
    return 'Basic ' . base64_encode( sendwp_get_client_name() . ':' . sendwp_get_client_secret() );
}

function sendwp_get_client_name()
{
    $site_url = get_site_url();
    return parse_url( $site_url, PHP_URL_HOST );
}
function sendwp_get_client_url()
{
    return get_site_url();
}

function sendwp_get_client_redirect()
{
    return add_query_arg('page', 'sendwp', get_admin_url(null, 'tools.php') );
}

function sendwp_get_client_secret()
{
    $secret = get_option( 'sendwp_client_secret' );
    if(!$secret) {
        $secret = sendwp_generate_secret();
        sendwp_set_client_secret($secret);
    }
    return $secret;
}

function sendwp_set_client_secret( $secret )
{
    return update_option( 'sendwp_client_secret', $secret );
}

function sendwp_generate_secret( $length = 40 )
{
    if( 0 >= $length ) $length = 40; // Min key length.
    if( 255 <= $length ) $length = 255; // Max key length.

    $characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_string = '';
    for ( $i = 0; $i < $length; $i ++ ) {
    $random_string .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
    }

    return $random_string;
}

function sendwp_connect_client()
{
    update_option('sendwp_client_connected', '1');
}

function sendwp_disconnect_client()
{
    update_option('sendwp_client_connected', '0');
}

function sendwp_client_connected()
{
    $connected = get_option('sendwp_client_connected', '0');
    if ('1' == $connected) {
        $connected = true;
    } else {
        $connected = false;
    }
    return $connected;
}

function sendwp_update_client_connection()
{
    if (! isset($_POST['sendwp_server_request'])) return false;
    if (! isset($_POST['sendwp_hash']) || ! sendwp_validate_hash($_POST['sendwp_hash'])) return false;
    if ('disconnect' == $_POST['sendwp_server_request']) {
        sendwp_disconnect_client();
    } elseif ('connect' == $_POST['sendwp_server_request']) {
        sendwp_connect_client();
    }
}

function sendwp_get_client_status()
{
    if (! isset($_POST['sendwp_request_status'])) return false;
    if (! isset($_POST['sendwp_hash']) || ! sendwp_validate_hash($_POST['sendwp_hash'])) return false;
    $response = [
        'connected' => sendwp_client_connected(),
        'enabled' => sendwp_forwarding_enabled()
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    die();
}

function sendwp_validate_hash( $hash )
{
    return sendwp_generate_hash() === $hash;
}


function sendwp_get_hash()
{
    return sendwp_generate_hash();
}

function sendwp_generate_hash()
{
    $data = sendwp_get_client_name() . sendwp_get_client_secret();
    return hash('sha256', $data);
}

function sendwp_enable_forwarding()
{
    update_option('sendwp_forwarding_enabled', '1');
}

function sendwp_disable_forwarding()
{
    update_option('sendwp_forwarding_enabled', '0');
}

function sendwp_forwarding_disabled_notice()
{
    if( isset( $_GET['page'] ) && 'sendwp' == $_GET['page'] ) return;

    if (!sendwp_forwarding_enabled() ) {
        wp_enqueue_style('sendwp-notices', plugins_url( 'assets/css/admin/notices.css', __DIR__));
        include 'admin/views/disabled-notice.html.php';
    }
}

function sendwp_forwarding_enabled()
{
    $enabled = get_option('sendwp_forwarding_enabled', '1');
    if ('1' == $enabled && sendwp_client_connected()) {
        $enabled = true;
    } else {
        $enabled = false;
    }
    return $enabled;
}

function sendwp_maybe_disable_forwarding()
{
    if (! isset($_POST['security'])) return false;
    if (! wp_verify_nonce($_POST['security'], 'sendwp_settings_nonce')) return false;
    if (isset($_POST['sendwp_forwarding'])) {
        if ('enable' == $_POST['sendwp_forwarding']) {
            sendwp_enable_forwarding();
        } elseif('disable' == $_POST['sendwp_forwarding']) {
            sendwp_disable_forwarding();
        }
    }
}

function sendwp_connect_pulse_monitor()
{
    if (! wp_next_scheduled('sendwp_heartbeat')) {
        wp_schedule_event(time(), 'daily', 'sendwp_heartbeat');
    }
}

function sendwp_pulse_monitor()
{
    if (get_transient('sendwp_pulse_monitor')) return false;
    $status = 'unknown';
    $request = \SendWP\API\Request::create('clients/status');
    $response = $request->post( [] );

    if(is_wp_error($response)){
        set_transient('sendwp_pulse_monitor', $response->get_error_message(), 10 * MINUTE_IN_SECONDS);
        return;
    }

    $response_body = wp_remote_retrieve_body($response);
    set_transient('sendwp_pulse_monitor', $response_body, 10 * MINUTE_IN_SECONDS);

    $status = json_decode($response_body);

    if ('not-connected' == $status->message) {
        sendwp_disconnect_client();
    } elseif ('connected' == $status->message) {
        sendwp_connect_client();
    }
}

function sendwp_last_pulse()
{
    $timestamp = time();
    $saved = get_option('_transient_timeout_sendwp_pulse_monitor', $timestamp);
    if($saved > $timestamp) {
        $timestamp = $saved - 600;
    }
    $iso_date = date( 'Y-m-d H:i:s', $timestamp );
	$local_timestamp = get_date_from_gmt( $iso_date, 'F j, Y, g:i a' );
    return $local_timestamp;
}

function sendwp_last_pulse_result()
{
    return get_option('_transient_sendwp_pulse_monitor', 'unknown');
}

function sendwp_get_client_partner_id()
{
    if ( defined( 'SENDWP_PARTNER_ID' ) ) {
        return SENDWP_PARTNER_ID;
    }

    return null;
}

function sendwp_reset_all_plugin_data()
{
    if (check_admin_referer('reset_sendwp_data' . sendwp_get_client_secret())) {
        $options = [
            'sendwp_client_secret',
            'sendwp_client_connected',
            'sendwp_forwarding_enabled',
        ];

        foreach ($options as $option) {
            if (get_option($option, false) !== false) {
                delete_option($option);
            }
        }

        sendwp_set_client_secret(sendwp_generate_secret());
    }

    wp_redirect(sendwp_get_client_redirect());

    exit;
}
add_action( 'admin_post_sendwp_reset_plugin_data', 'sendwp_reset_all_plugin_data' );