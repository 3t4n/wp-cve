<?php

namespace App\Controllers;

use App\Base\Controller;
use WP_Error;

class Webhook extends Controller
{
    /**
     * List registered webhook addresses
     * @return array
     */
    public static function list()
    {
        if (!self::verifyToken()) {
            return new WP_Error('unauthorized', 'Authentication failed.', [
                'status' => 401
            ]);
        }

        $addresses = json_decode(get_option('nextsale_webhooks'), true);

        if (!$addresses || !is_array($addresses)) {
            $addresses = [];
        }

        return $addresses;
    }

    /**
     * Add new webhook address
     * @return array
     */
    public static function add($request)
    {
        if (!self::verifyToken()) {
            return new WP_Error('unauthorized', 'Authentication failed.', [
                'status' => 401
            ]);
        }

        $data = json_decode($request->get_body());

        if (!isset($data->address)) {
            return new WP_Error('invalid_data', 'Address parameter is required.', [
                'status' => 422
            ]);
        }

        if (!self::validateAddress($data->address)) {
            return new WP_Error('invalid_data', 'Address parameter is not valid.', [
                'status' => 422
            ]);
        }

        $addresses = json_decode(get_option('nextsale_webhooks'), true);

        if (!$addresses || !is_array($addresses)) {
            $addresses = [];
        }

        if (!in_array($data->address, $addresses)) {
            $addresses[] = $data->address;
        }

        update_option('nextsale_webhooks', json_encode($addresses));

        return [
            'success' => true
        ];
    }

    /**
     * Delete script tag
     *
     * @param [type] $request
     * @return array
     */
    public static function delete($request)
    {
        if (!self::verifyToken()) {
            return new WP_Error('unauthorized', 'Authentication failed.', [
                'status' => 401
            ]);
        }

        $data = json_decode($request->get_body());

        if (!isset($data->address)) {
            return new WP_Error('invalid_data', 'address parameter is required.', [
                'status' => 422
            ]);
        }

        if (!self::validateAddress($data->address)) {
            return new WP_Error('invalid_data', 'address parameter is not valid.', [
                'status' => 422
            ]);
        }

        $addresses = json_decode(get_option('nextsale_webhooks'), true);

        if (!$addresses || !is_array($addresses)) {
            $addresses = [];
        }

        $key = array_search($data->address, $addresses);
        if ($key !== false) {
            unset($addresses[$key]);
            $addresses = array_values($addresses);
        }

        update_option('nextsale_webhooks', json_encode($addresses));

        return [
            'success' => true
        ];
    }

    /**
     * Validate the script source url
     *
     * @param string $address
     * @return boolean
     */
    private static function validateAddress(string $address)
    {
        $url_parts = parse_url($address);

        if (!isset($url_parts['scheme']) || !isset($url_parts['host'])) {
            return false;
        }

        if (!in_array($url_parts['scheme'], ['http', 'https'])) {
            return false;
        }

        return true;
    }
}
