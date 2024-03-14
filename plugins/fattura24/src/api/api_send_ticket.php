<?php

/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com> 
 *
 * Chiamata API a DeskTale per inviare ticket a F24
 * 
 */
namespace fattura24;

if (!defined('ABSPATH')) exit;

/**
 * cfr: https://gist.github.com/sirbrillig/6ab49aa6517d203a6560d75d65e0874a
 */
function fatt_24_send_ticket($content)
{
    $url = 'https://www.desktale.com/api/server/tickets/sendTicket?api_key=fis4xr6FH4Gfhk7rTDFsd2wq3ZdfD3cgjB7ds3sDMnp5';
    //$url = 'http://192.168.178.30:4000/server/tickets/sendTicket?api_key=fis4xr6FH4Gfhk7rTDFsd2wq3ZdfD3cgjB7ds3sDMnp5'; //test server
    $source = $content['source'];
    $accountId = (string) $content['account_id'];
    $widgetId = in_array($accountId, array('server_api_non_raggiungibile', 'non_registrato')) ? 'P-0' : 'P-' . $accountId;
    $pid = 'P-' . $accountId;
    $today = fatt_24_now(); // aggiungo data e orario utilizzando il CEST
    $fileName = plugin_dir_path(__FILE__) . 'att_'. $source . '_' . $accountId . '_' . $today .'.txt';
    file_put_contents($fileName, fatt_24_array2string($content));
   
    $boundary = wp_generate_password(24);
    $headers = array('Content-Type' => 'multipart/form-data; boundary=' . $boundary);
    $formData = '';

    $identify = [
            'widget_id' => $widgetId, 
            'id_account' => $pid,
            'name' => $content['username'],
            'email' => $content['email']
        ];

    $formData .= '--' . $boundary;
    $formData .= "\r\n";
    $formData .= 'Content-Disposition: form-data; name="identify"'. "\r\n\r\n";
    $formData .= json_encode($identify) . "\r\n";
  

    $names = [            
        'name' => $content['username'],
        'email' => $content['email'],
        'subject' => $content['subject'],
        'text' => $content['text']
    ];
        
    foreach ($names as $name => $value) {
        $formData .= '--' . $boundary;
        $formData .= "\r\n";
        $formData .= 'Content-Disposition: form-data; name="' . $name . '"'. "\r\n\r\n";
        $formData .= $value . "\r\n";
    }
    
    $formData .= '--' . $boundary;
    $formData .= "\r\n";
    $formData .= 'Content-Disposition: form-data; name="file";  filename="' . basename($fileName) .'"' . "\r\n";
    $formData .= 'Content-Type: text/plain' . "\r\n"; // necessario perché il file venga inserito come allegato
    $formData .= "\r\n";
    $formData .= file_get_contents($fileName);
    $formData .= "\r\n";
    $formData .= '--' . $boundary . '--';

    $request = wp_remote_post($url, 
        array(
            'method' => 'POST',
            'timeout' => 60,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $headers,
            'body' => $formData,
            'cookies' => array()
        )
    );
    $body = is_wp_error($request)? json_encode($request) : wp_remote_retrieve_body($request);
    unlink($fileName);
    return $body;
}