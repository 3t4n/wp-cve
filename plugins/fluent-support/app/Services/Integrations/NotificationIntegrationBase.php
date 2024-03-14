<?php

namespace FluentSupport\App\Services\Integrations;

use FluentSupport\App\Models\Meta;

abstract class NotificationIntegrationBase
{
    abstract public function getSettings($withFields = false);

    abstract public function getFields();

    public function getKey()
    {
        return $this->key;
    }

    public function saveSettings($settings)
    {
        return $this->save($settings);
    }

    public function save($settings)
    {
        $exist = Meta::where('object_type', 'integration_settings')
            ->where('key', $this->key)
            ->first();

        if ($exist) {
            $exist->value = maybe_serialize($settings);
            $exist->save();
        } else {
            $exist = Meta::insert([
                'object_type' => 'integration_settings',
                'key'         => $this->key,
                'value'       => maybe_serialize($settings)
            ]);
        }

        return $settings;
    }

    public function get()
    {
        $exist = Meta::where('object_type', 'integration_settings')
            ->where('key', $this->key)
            ->first();

        if ($exist) {
            return maybe_unserialize($exist->value);
        }

        return false;
    }

    public function sendRequest($url, $params = [], $method = 'GET', $extraHeaders = [])
    {
        $request = wp_remote_request($url, [
            'sslverify' => false,
            'method'    => $method,
            'body'      => $params,
            'headers'   => $extraHeaders
        ]);

        if (is_wp_error($request)) {
            return new \WP_Error($request->get_error_code(), $request->get_error_message());
        } else if (!$request) {
            return new \WP_Error(423, __('API Request Error', 'fluent-support'), $request->get_error_data());
        }

        $code = wp_remote_retrieve_response_code($request);
        $response = wp_remote_retrieve_body($request);

        $response = json_decode($response, true);

        if ($code > 299) {
            return new \WP_Error($code, __('API Response Error', 'fluent-support'), $response);
        }

        return $response;
    }
}
