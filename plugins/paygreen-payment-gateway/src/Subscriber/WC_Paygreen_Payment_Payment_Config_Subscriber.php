<?php

namespace Paygreen\Module\Subscriber;

use Paygreen\Module\WC_Paygreen_Payment_Api;
use Paygreen\Module\WC_Paygreen_Payment_Logger;

if (!defined( 'ABSPATH')) {
    exit;
}

class WC_Paygreen_Payment_Payment_Config_Subscriber
{
    /**
     * @return array[]
     */
    public static function get_subscribed_events()
    {
        return [
            'payment_config.enabled' => ['handle_payment_config'],
            'payment_config.disabled' => ['handle_payment_config'],
            'payment_config.deleted' => ['handle_payment_config'],
        ];
    }

    /**
     * @param array $notification
     * @return int
     */
    public static function handle_payment_config(array $notification)
    {
        try {
            WC_Paygreen_Payment_Api::has_active_payment_methods();
        } catch (\Exception $exception) {
            WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Payment_Config_Subscriber::handle_payment_config - Exception - ' . preg_replace("/\n/", '<br>', (string) $exception->getMessage() . '<br>' . $exception->getTraceAsString()));

            return 400;
        }

        return 200;
    }
}