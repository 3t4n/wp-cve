<?php

namespace Qodax\CheckoutManager\Http\Middleware;

use Qodax\CheckoutManager\Http\Request;

if ( ! defined('ABSPATH')) {
    exit;
}

class VerifyCsrfToken
{
    private const TOKEN_ID = 'qodax_checkout_manager';

    public function handle(Request $request)
    {
        if ( ! wp_verify_nonce($request->get('_token', ''), self::TOKEN_ID)) {
            wp_send_json([
                'error' => 'CSRF token mismatch'
            ], 400);
        }
    }
}