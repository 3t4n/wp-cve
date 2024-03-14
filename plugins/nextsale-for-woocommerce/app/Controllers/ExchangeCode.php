<?php

namespace App\Controllers;

class ExchangeCode
{
    /**
     * Validate the latest exchange code
     * @return array
     */
    public static function validate($request)
    {
        $data = json_decode($request->get_body());
        $code = isset($data->code) ? $data->code : null;

        return [
            'success' => $code && get_option('nextsale_exchange_code') === $code
        ];
    }

    /**
     * Save the permanent access token
     *
     * @param [type] $request
     * @return void
     */
    public static function saveAccessToken($request)
    {
        $data = json_decode($request->get_body());

        if (
            empty($data->access_token)
            || empty($data->code)
            || $data->code != get_option('nextsale_exchange_code')
        ) {
            return [
                'success' => false
            ];
        }

        update_option('nextsale_access_token', $data->access_token);
        delete_option('nextsale_exchange_code');

        return [
            'success' => true
        ];
    }
}
