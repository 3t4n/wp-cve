<?php

namespace WPPayForm\App\Modules\PaymentMethods\Stripe;
use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle Stripe Checkout Session
 * @since 1.0.0
 */
class CheckoutSession
{
    public static function create($args, $formId = false)
    {
        $argsDefault = [
            'locale' => 'auto'
        ];

        $args = wp_parse_args($args, $argsDefault);
        $stripe = new Stripe();
        ApiRequest::set_secret_key($stripe->getSecretKey($formId));
        return ApiRequest::request($args, 'checkout/sessions');
    }

    public static function retrive($sessionId, $args = [], $formId = false)
    {
        $stripe = new Stripe();
        ApiRequest::set_secret_key($stripe->getSecretKey($formId));
        return ApiRequest::request($args, 'checkout/sessions/' . $sessionId, 'GET');
    }

    public static function invoices($sessionId, $args = [], $formId = false)
    {
        $stripe = new Stripe();
        ApiRequest::set_secret_key($stripe->getSecretKey($formId));
        return ApiRequest::request($args, 'invoices', 'GET');
    }
}
