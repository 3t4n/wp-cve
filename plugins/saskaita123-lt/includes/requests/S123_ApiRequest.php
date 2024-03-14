<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 *
 * Class Description: Requests to Saskaita123 API
 */

declare(strict_types=1);

namespace S123\Includes\Requests;

use S123\Includes\Base\S123_Options;

if (!defined('ABSPATH')) exit;

class S123_ApiRequest
{
    use S123_Options;

    /**
     * Stores API request header
     *
     * @var array
     */
    protected $apiRequestHeader = array();

    /**
     * Stores API urls
     *
     * @var array
     */
    protected $apiRequestUrls = array();

    // Inject with sandbox true if testing plugin or change param to true if developing locally
    // change prefix of your sandbox
    public function __construct($sandbox = false, $sandboxPrefix = 'http://localhost:8000/api/v1.0')
    {
        $this->apiRequestHeader = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->s123_get_option('api_key'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            )
        );

        $this->apiRequestUrls = array(
            'invoice' => $sandbox ? $sandboxPrefix . '/invoices' : 'https://app.invoice123.com/api/v1.0/invoices',
            'validation' => $sandbox ? $sandboxPrefix . '/validate-token' : 'https://app.invoice123.com/api/v1.0/validate-token',
            'woocommerce_settings' => $sandbox ? $sandboxPrefix . '/woocommerce/settings' : 'https://app.invoice123.com/api/v1.0/woocommerce/settings',
            'vats' => $sandbox ? $sandboxPrefix . '/vats' : 'https://app.invoice123.com/api/v1.0/vats',
            'invoice_pdf' => $sandbox ? $sandboxPrefix . '/invoice/{id}/pdf/{language}' : 'https://app.invoice123.com/api/v1.0/invoice/{id}/pdf/{language}',
            'warehouse_sync' => $sandbox ? $sandboxPrefix . '/woocommerce/warehouse' : 'https://app.invoice123.com/api/v1.0/woocommerce/warehouse',
        );
    }

    public function s123_makeGetRequest(string $url)
    {
        $response = wp_remote_get($url, $this->apiRequestHeader);
        return ['code' => wp_remote_retrieve_response_code($response), 'body' => json_decode(wp_remote_retrieve_body($response), true)];
    }

    public function i123_plainGetRequest(string $url)
    {
        return wp_remote_get($url, $this->apiRequestHeader);
    }

    public function s123_makeRequest(string $url, $data, $method)
    {
        $data = array_merge($data, [
            'generated_from' => 'woocommerce',
        ]);

        $body = array(
            'body' => json_encode($data),
            'method' => $method
        );
        $headers = array_merge($this->apiRequestHeader, $body);

        $response = wp_remote_post($url, $headers);
        return ['code' => wp_remote_retrieve_response_code($response), 'body' => json_decode(wp_remote_retrieve_body($response), true)];
    }

    public function getApiUrl(string $urlType)
    {
        return $this->apiRequestUrls[$urlType];
    }
}