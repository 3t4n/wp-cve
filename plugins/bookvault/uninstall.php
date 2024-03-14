<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}


function bvlt_uninstall()
{
	delete_option("bvlt_token");
	delete_option("bvlt_storeid");
	delete_option("bvlt_auth");
	
    $body = ["Url" => get_site_url()]; 
    $bodyStr = wp_json_encode( $body );

    $options = [
        'body'        => $bodyStr,
        'headers'     => [
            'Content-Type' => 'application/json'
        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];
    $endpoint = "https://webhooks.bookvault.app/woocommerce/Uninstall";
    wp_remote_post( $endpoint, $options ); 
}

bvlt_uninstall();
?>