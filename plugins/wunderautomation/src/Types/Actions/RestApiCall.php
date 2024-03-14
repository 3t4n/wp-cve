<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;
use WunderAuto\Types\Parameters\Data\JsonParser;

/**
 * Class RestApiCall
 */
class RestApiCall extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('REST API call ', 'wunderauto');
        $this->description = __('Make a REST API call', 'wunderauto');
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
        $config->sanitizeObjectProp($config->value, 'body', 'textarea');
        $config->sanitizeObjectProp($config->value, 'useBasicAuth', 'bool');
        $config->sanitizeObjectProp($config->value, 'basicAuthUser', 'text');
        $config->sanitizeObjectProp($config->value, 'basicAuthPass', 'text');
        $config->sanitizeObjectProp($config->value, 'useHeaderKey', 'bool');
        $config->sanitizeObjectProp($config->value, 'headerAPIKey', 'text');
        $config->sanitizeObjectProp($config->value, 'headerAPISecret', 'text');
        $config->sanitizeObjectProp($config->value, 'useHMACSignedPayload', 'bool');
        $config->sanitizeObjectProp($config->value, 'HMACSignatureHeader', 'text');
        $config->sanitizeObjectProp($config->value, 'HMACSignatureSecret', 'text');
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $method     = $this->get('value.method');
        $url        = $this->getResolved('value.url');
        $dataFormat = $this->get('value.format');
        $body       = $this->getResolved('value.body');

        $args = [
            'headers' => [],
        ];

        if (!$method || !$url) {
            return false;
        }

        if (strlen($dataFormat) > 0 && $method == 'POST') {
            switch ($dataFormat) {
                case 'json':
                    $args['headers']['Content-Type'] = 'application/json; charset=utf-8';
                    $args['body']                    = $body;
                    break;
                case 'default':
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
            $HMACSignatureHeader                   = $this->get('value.HMACSignatureHeader');
            $HMACSignatureSecret                   = $this->get('value.HMACSignatureSecret');
            $body                                  = isset($args['body']) ? $args['body'] : '';
            $hash                                  = hash_hmac('sha1', $body, $HMACSignatureSecret);
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
        $this->resolver->addParameter('rest.response', $parser);

        return true;
    }
}
