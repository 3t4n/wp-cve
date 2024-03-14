<?php

namespace wobel\classes\services\activation;

class Activation_Service
{
    private $service_url;

    public function __construct()
    {
        $this->service_url = "https://www.ithemelandco.com/services/license-update/index.php";
    }

    public function activation($data)
    {
        $data['service'] = 'free_activation';
        $response = wp_remote_post($this->service_url, [
            'sslverify' => false,
            'method' => 'POST',
            'timeout' => 45,
            'httpversion' => '1.0',
            'body' => $data,
        ]);
        if (!is_object($response) && !empty($response['body'])) {
            if (!empty($response['response']['code']) && $response['response']['code'] != 500) {
                $data = json_decode($response['body'], true);
                return (!is_null($data)) ? $data : $response['body'];
            } else {
                return "System Error!";
            }
        }
        return null;
    }
}
