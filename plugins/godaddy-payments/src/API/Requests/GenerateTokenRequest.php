<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\API\Requests;

use Exception;
use Firebase\JWT\JWT;

defined('ABSPATH') or exit;

/**
 * Generate token request.
 *
 * @since 1.0.0
 */
class GenerateTokenRequest extends AbstractRequest
{
    /**
     * Generate token request constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->path = '/token';
    }

    /**
     * Sets the token data.
     *
     * @since 1.0.0
     *
     * @param string $appId the application ID
     * @param string $privateKey the private key
     * @param string $apiUrl the Poynt API URL
     * @throws Exception
     */
    public function setTokenData(string $appId, string $privateKey, string $apiUrl)
    {
        $this->data = [
            'grantType' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $this->generateJwt($appId, $privateKey, $apiUrl),
        ];
    }

    /**
     * Generates a signed JSON Web Token.
     *
     * @since 1.0.0
     *
     * @param string $appId the application ID
     * @param string $privateKey the private key
     * @param string $apiUrl the Poynt API URL
     * @return string signed JWT
     * @throws Exception
     */
    public function generateJwt(string $appId, string $privateKey, string $apiUrl) : string
    {
        $issuedAt = $notBeforeThan = time();
        $expireAt = $issuedAt + 300;
        $payload = [
            'iss' => $appId,
            'sub' => $appId,
            'aud' => $apiUrl,
            'iat' => $issuedAt,
            'nbf' => $notBeforeThan,
            'exp' => $expireAt,
            'jti' => wp_generate_uuid4(),
        ];

        return JWT::encode($payload, $privateKey, 'RS256');
    }

    /**
     * Converts the request data to a string.
     *
     * This special requests requires the data to be URL encoded.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function to_string() : string
    {
        return http_build_query($this->get_data(), '', '&');
    }

    /**
     * Gets the log-safe representation of the response.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function to_string_safe()
    {
        $string = parent::to_string_safe();

        if (! empty($this->data['assertion'])) {
            $string = str_replace($this->data['assertion'], str_repeat('*', strlen($this->data['assertion'])), $string);
        }

        return $string;
    }
}
