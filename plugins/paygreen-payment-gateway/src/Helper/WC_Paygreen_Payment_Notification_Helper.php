<?php

namespace Paygreen\Module\Helper;

use Paygreen\Module\Subscriber\WC_Paygreen_Payment_Operation_Subscriber;
use Paygreen\Module\Subscriber\WC_Paygreen_Payment_Payment_Config_Subscriber;
use Paygreen\Module\Subscriber\WC_Paygreen_Payment_Payment_Order_Subscriber;
use Paygreen\Module\WC_Paygreen_Payment_Logger;

if (!defined( 'ABSPATH')) {
    exit;
}

class WC_Paygreen_Payment_Notification_Helper
{
    /** @var string[] */
    private static $SUBSCRIBERS = [
        WC_Paygreen_Payment_Payment_Order_Subscriber::class,
        WC_Paygreen_Payment_Operation_Subscriber::class,
        WC_Paygreen_Payment_Payment_Config_Subscriber::class,
    ];

    /**
     * @param array $notification
     * @return int
     */
    public static function process(array $notification)
    {
        $notification_status = $notification['status'];

        foreach (self::$SUBSCRIBERS as $subscriber) {
            $subscribed_events = call_user_func([$subscriber, 'get_subscribed_events']);

            if (in_array($notification_status, array_keys($subscribed_events))) {
                $methodName = $subscribed_events[$notification_status][0];
                return call_user_func([$subscriber, $methodName], $notification);
            }
        }

        WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Notification_Helper::process - Unhandled notification status : ' . $notification_status);

        return 400;
    }

    /**
     * @return string[]
     */
    public static function get_all_subscribed_events()
    {
        $subscribed_events = [];

        foreach (self::$SUBSCRIBERS as $subscriber) {
            $subscribed_events = array_merge($subscribed_events, call_user_func([$subscriber, 'get_subscribed_events']));
        }

        return array_keys($subscribed_events);
    }
}
