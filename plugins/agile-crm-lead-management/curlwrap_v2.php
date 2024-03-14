<?php
if( !defined( 'ABSPATH' ) ) {
        exit( 'You are not allowed to access this file directly.' );
}

function agilecrm_get_data($entity, $agile_domain, $email, $rest_api) {

    $agile_url = "https://" .$agile_domain. ".agilecrm.com/dev/api/".$entity;
    $headers = array(
        'Authorization' => 'Basic ' . base64_encode( $email. ':' .$rest_api ),
        'Content-type' => 'application/json',
        'Accept' => 'application/json'
        );

    $args_get = array(
        'timeout' => 120,
        'sslverify'   => false,
        'headers' => $headers
        );

    $request = wp_remote_get($agile_url,$args_get);                    
    $result = wp_remote_retrieve_body( $request ); 
    return $result;
}