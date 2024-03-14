<?php

namespace WPPayForm\App\Modules\PaymentMethods\Stripe;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle Strong Customer Authentication here
 * @since 1.0.0
 */
class Events
{
    public static function getEvents($args = [])
    {
        $stripe = new Stripe();
        ApiRequest::set_secret_key($stripe->getSecretKey());
        return ApiRequest::request($args, 'events', 'GET');
    }
}
