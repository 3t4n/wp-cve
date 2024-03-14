<?php

namespace WPPayForm\App\Services\Turnstile;

use WPPayForm\Framework\Support\Arr;

class Turnstile
{
    /**
     * Verify turnstile response.
     *
     * @param string $token response from the user.
     * @param null $secret provided or already stored secret key.
     *
     * @return bool
     */
    public static function validate($token, $secret)
    {
        $verifyUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

        $response = wp_remote_post($verifyUrl, [
            'method' => 'POST',
            'body'   => [
                'secret'   => $secret,
                'response' => $token
            ],
        ]);

        $isValid = false;

        if (!is_wp_error($response)) {
            $result = json_decode(wp_remote_retrieve_body($response));
            $isValid = $result->success;
        }

        return $isValid;
    }
}