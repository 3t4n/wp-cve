<?php
include '../../../../../wp-load.php';
$request_args = array(
    'body' => wp_json_encode(array(
        'grant_type' => 'authorization_code',
        'client_id' => get_option('cwmo_format_appid_melhorenvio'),
        'client_secret' => get_option('cwmo_format_token_melhorenvio'),
        'redirect_uri' => CWMP_PLUGIN_URL . 'authentication/melhorenvio/',
        'code' => isset($_GET['code']) ? sanitize_text_field($_GET['code']) : '',
    )),
    'headers' => array(
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'User-Agent' => 'Aplicação (' . get_option('cwmo_format_email_melhorenvio') . ')',
    ),
);

$response = wp_remote_post(CWMP_BASE_URL_MELHORENVIO . 'oauth/token', $request_args);

if (!is_wp_error($response)) {
    $body = wp_remote_retrieve_body($response);
    $retorno = json_decode($body);
	if($retorno->access_token){
		update_option( 'cwmo_format_token_melhorenvio_bearer', $retorno->access_token );
		update_option( 'cwmo_format_token_melhorenvio_refresh', $retorno->refresh_token );
	}
	wp_redirect( get_admin_url()."admin.php?page=cwmp_admin_entrega&type=entrega.melhor-envio" );
	exit;
} else {
    // Lidar com o erro, se necessário
    $error_message = $response->get_error_message();
    // ... faça algo com $error_message
}


