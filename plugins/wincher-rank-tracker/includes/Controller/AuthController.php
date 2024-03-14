<?php

namespace Wincher\Controller;

use WP_REST_Request;
use WP_REST_Response;

/**
 * The AuthController class.
 */
class AuthController extends RestController
{
    /**
     * Gets the generated authorization URL.
     *
     * @return WP_REST_Response the response
     */
    public function authorization_url()
    {
        $url = $this->client->getAuthorizationUrl();

        return new WP_REST_Response($url, 200);
    }

    public function token(WP_REST_Request $request)
    {
        $code = $request->get_param('code');

        if (empty($code)) {
            return new WP_REST_Response(['error' => 'Required parameter code missing'], 400);
        }

        $resp = $this->client->requestTokens($code);

        return new WP_REST_Response($resp, $resp->getValues()['status']);
    }
}
