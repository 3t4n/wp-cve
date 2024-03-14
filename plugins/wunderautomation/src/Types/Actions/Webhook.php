<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;
use WunderAuto\Types\Parameters\Data\JsonParser;

/**
 * Class Webhook
 */
class Webhook extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Webhook', 'wunderauto');
        $this->description = __('Send data to a webhook', 'wunderauto');
        $this->group       = 'Advanced';
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
        parent::sanitizeConfig($config);
        $config->sanitizeObjectProp($config->value, 'method', 'enum', ['GET', 'POST', 'PUT']);
        $config->sanitizeObjectProp($config->value, 'url', 'url');
        $config->sanitizeObjectProp($config->value, 'format', 'key');
        $config->sanitizeObjectProp($config->value, 'useBasicAuth', 'bool');
        $config->sanitizeObjectProp($config->value, 'basicAuthUser', 'text');
        $config->sanitizeObjectProp($config->value, 'basicAuthPass', 'text');
        $config->sanitizeObjectProp($config->value, 'useHeaderKey', 'bool');
        $config->sanitizeObjectProp($config->value, 'headerAPIKey', 'text');
        $config->sanitizeObjectProp($config->value, 'headerAPISecret', 'text');
        $config->sanitizeObjectProp($config->value, 'useHMACSignedPayload', 'bool');
        $config->sanitizeObjectProp($config->value, 'HMACSignatureHeader', 'text');
        $config->sanitizeObjectProp($config->value, 'HMACSignatureSecret', 'text');
        $config->sanitizeObjectArray($config->value, 'rows', ['key' => 'text', 'value' => 'textarea']);
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $method     = $this->get('value.method');
        $url        = $this->getResolved('value.url');
        $dataFormat = $this->get('value.format');
        $rows       = (array)$this->get('value.rows');
        $parameters = [];

        $args = [
            'headers' => [],
        ];

        if (!$method || !$url) {
            return false;
        }

        foreach ($rows as $row) {
            $key              = $this->resolver->resolveField($row->key);
            $value            = $this->resolver->resolveField($row->value);
            $parameters[$key] = $value;
            if ($dataFormat === 'json') {
                $decoded          = json_decode($value);
                $parameters[$key] = is_null($decoded) ? $value : $decoded;
            }
        }
        if (count($parameters) > 0) {
            $args['body'] = $parameters;
        }

        if (strlen($dataFormat) > 0 && $method == 'POST') {
            switch ($dataFormat) {
                case 'json':
                    $args['headers']['Content-Type'] = 'application/json; charset=utf-8';
                    $args['body']                    = json_encode($parameters);
                    break;
                case 'default':
                    $body                            = http_build_query($parameters, '', '&');
                    $args['headers']['Content-Type'] = 'application/x-www-form-urlencoded';
                    $args['body']                    = $body;
                    break;
            }
        }

        // We support workflows that was saved before the useBasicAuth flag existed
        $useBasicAuth  = (bool)$this->get('value.useBasicAuth');
        $basicAuthUser = $this->getResolved('value.basicAuthUser');
        $basicAuthPass = $this->getResolved('value.basicAuthPass');
        if ($useBasicAuth || ($useBasicAuth === false && strlen($basicAuthUser . $basicAuthPass) > 0)) {
            $authString                       = base64_encode($basicAuthUser . ':' . $basicAuthPass);
            $args['headers']['Authorization'] = 'Basic ' . $authString;
        }

        $useHeaderKey = (bool)$this->get('value.useHeaderKey');
        if ($useHeaderKey) {
            $headerAPIKey                   = $this->get('value.headerAPIKey');
            $headerAPISecret                = $this->get('value.headerAPISecret');
            $args['headers'][$headerAPIKey] = $headerAPISecret;
        }

        $useHMACSignedPayload = (bool)$this->get('value.useHMACSignedPayload');
        if ($useHMACSignedPayload) {
            $HMACSignatureHeader = $this->get('value.HMACSignatureHeader');
            $HMACSignatureSecret = $this->get('value.HMACSignatureSecret');
            $body                = isset($args['body']) ? $args['body'] : '';
            $body                = is_string($body) ? $body : '';
            $hash                = hash_hmac('sha1', $body, $HMACSignatureSecret);

            $args['headers'][$HMACSignatureHeader] = $hash;
        }

        $request = null;
        switch ($method) {
            case 'GET':
                $request = wp_remote_get($url, $args);
                break;
            case 'POST':
                $request = wp_remote_post($url, $args);
                break;
            case 'PUT':
                $args['method'] = 'PUT';
                $request        = wp_remote_request($url, $args);
                break;
        }

        if (is_wp_error($request) || is_null($request)) {
            return false;
        }

        $body   = wp_remote_retrieve_body($request);
        $parser = new JsonParser();
        $parser->setRawJson($body);
        $this->resolver->addParameter('webhook.response', $parser);
        return true;
    }
}
