<?php

use WC_Gateway_Conotoxia_Pay_Logger as Logger;

class WC_Gateway_Conotoxia_Pay_Public_Key_Generation_Handler
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        add_action('wp_ajax_cx_generate_key_pair', [$this, 'handle_public_key_generation']);
    }

    /**
     * @return void
     */
    public function handle_public_key_generation(): void
    {
        $key_pair = $this->generate_key_pair();
        if (isset($key_pair['private']) && isset($key_pair['public'])) {
            header('Content-Type: application/json');
            echo wp_json_encode($key_pair);
            exit();
        }
        header("HTTP/1.1 500 Internal Server Error");
        exit();
    }

    /**
     * @return array|null[]
     */
    private function generate_key_pair(): array
    {
        if (!extension_loaded('openssl')) {
            Logger::log('Error: Missing OpenSSL extension.');
            return ['public' => null, 'private' => null];
        }
        $key_pair = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ]);
        openssl_pkey_export($key_pair, $private_key);
        $public_key = openssl_pkey_get_details($key_pair) ['key'];
        if (PHP_VERSION_ID < 80000) {
            openssl_pkey_free($key_pair);
        }
        return ['public' => trim($public_key), 'private' => trim($private_key)];
    }
}
