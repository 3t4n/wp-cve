<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Endpoints;

use Holded\Woocommerce\Services\Settings;

abstract class AbstractEndpoint
{
    /** @var string */
    protected $apiPrefix;

    /** @var string */
    protected $apiVersion;

    /** @var string */
    protected $apiNamespace;

    /** @var string */
    protected $apikey;

    public function __construct()
    {
        $this->apiPrefix = 'holdedwc';
        $this->apiVersion = '1';
        $this->apiNamespace = $this->apiPrefix.'/v'.$this->apiVersion;

        $this->apikey = (Settings::getInstance())->getApiKey();
    }

    /**
     * @return bool|\WP_Error
     */
    public function authentication(\WP_REST_Request $request)
    {
        if (empty($this->apikey)) {
            return new \WP_Error('no-key', 'No holded key is stored at this WordPress installation.', ['status' => 401]);
        } else {
            $headers = $request->get_headers();
            if (empty($headers['apikey'])) {
                return new \WP_Error('no-auth', 'You must provide authorization.', ['status' => 401]);
            } else {
                $receivedAuthorization = sanitize_text_field(reset($headers['apikey']));
                $expectedAuthorization = 'Holded '.$this->apikey;

                if ($receivedAuthorization != $expectedAuthorization) {
                    return new \WP_Error('invalid-auth', 'You must authenticate the request with valid data.', ['status' => 401]);
                } else {
                    return true;
                }
            }
        }
    }
}
