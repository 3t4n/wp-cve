<?php

namespace Paygreen\Module\Helper;

use Paygreen\Module\WC_Paygreen_Payment_Api;
use Paygreen\Module\Exception\WC_Paygreen_Payment_Exception;
use Paygreen\Module\Exception\WC_Paygreen_Payment_Listener_Exception;
use Paygreen\Module\WC_Paygreen_Payment_Logger;

if (!defined( 'ABSPATH')) {
    exit;
}

class WC_Paygreen_Payment_Listener_Helper
{
    /**
     * Create a listener.
     *
     * @since 1.0.13
     * @return void
     * @throws \Exception
     */
    public static function register_payment_listener()
    {
        $settings = get_option('woocommerce_paygreen_payment_settings');
        $client = WC_Paygreen_Payment_Api::get_paygreen_client();
        $url = self::build_payment_listener_url();

        $response = $client->listListener($settings['shop_id']);

        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);

            if ($responseData['pagination']['totalResults'] >= 10) {
                throw new \Paygreen\Module\Exception\WC_Paygreen_Payment_Listener_Exception(__('The limit of 10 listeners for a store has been reached. The listener subscription has failed. Go to the PayGreen backoffice to manage your listeners.', 'paygreen-payment-gateway'));
            }

            $listeners = $responseData['data'];

            if (!empty($listeners)) {
                foreach ($listeners as $listener) {
                    // Delete listener with wrong events
                    if ($listener['type'] === 'webhook'
                        && $listener['shop_id'] === $settings['shop_id']
                        && $listener['url'] === $url
                        && $listener['events'] !== WC_Paygreen_Payment_Notification_Helper::get_all_subscribed_events()
                    ) {
                        $deletionResult = self::delete_payment_listener($listener['id']);

                        if ($deletionResult) {
                            continue;
                        }
                    }

                    // Delete disabled listener
                    if ($listener['type'] === 'webhook'
                        && $listener['shop_id'] === $settings['shop_id']
                        && $listener['url'] === $url
                        && $listener['events'] === WC_Paygreen_Payment_Notification_Helper::get_all_subscribed_events()
                        && $listener['status'] === 'disabled'
                    ) {
                        $deletionResult = self::delete_payment_listener($listener['id']);

                        if ($deletionResult) {
                            continue;
                        }
                    }

                    // Delete listener with old urls (old urls were used until the refacto from WPP-123)
                    if ($listener['type'] === 'webhook'
                        && $listener['shop_id'] === $settings['shop_id']
                        && $listener['url'] !== $url
                    ) {
                        $listenerUrlParts = explode('?wc-api=', $listener['url']);
                        $newUrlParts = explode('?wc-api=', $url);

                        if (count($listenerUrlParts) > 1
                            && $listenerUrlParts[1] === 'wc_paygreen_payment'
                            && $listenerUrlParts[0] === $newUrlParts[0]
                        ) {
                            $deletionResult = self::delete_payment_listener($listener['id']);

                            if ($deletionResult) {
                                continue;
                            }
                        }
                    }

                    if ($listener['type'] === 'webhook'
                        && $listener['shop_id'] === $settings['shop_id']
                        && $listener['url'] === $url
                        && $listener['events'] === WC_Paygreen_Payment_Notification_Helper::get_all_subscribed_events()
                    ) {
                        $settings['listener_id'] = $listener['id'];
                        $settings['listener_hmac_key'] = $listener['hmac_key'];
                        update_option('woocommerce_paygreen_payment_settings', $settings);

                        return;
                    }
                }
            }

            $listener = new \Paygreen\Sdk\Payment\V3\Model\Listener();
            $listener->setUrl($url);
            $listener->setEvents(WC_Paygreen_Payment_Notification_Helper::get_all_subscribed_events());
            $listener->setType('webhook');

            $response = $client->createListener($listener);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                $listener = $responseData['data'];
                $settings['listener_id'] = $listener['id'];
                $settings['listener_hmac_key'] = $listener['hmac_key'];
                update_option('woocommerce_paygreen_payment_settings', $settings);
            } else {
                WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Webhook_Helper::register_payment_webhook - Listener creation has failed. Response: ' . print_r($response->getBody()->getContents(), true));

                throw new \Paygreen\Module\Exception\WC_Paygreen_Payment_Listener_Exception(__('Payment validation listener creation has failed.', 'paygreen-payment-gateway'));
            }
        } else {
            WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Webhook_Helper::register_payment_webhook - Failed to get listeners. Response: ' . print_r($response->getBody()->getContents(), true));

            throw new \Paygreen\Module\Exception\WC_Paygreen_Payment_Listener_Exception(__('Failed to retrieve payment listeners.', 'paygreen-payment-gateway'));
        }
    }

    /**
     * @param string $listener_id
     * @return true
     * @throws WC_Paygreen_Payment_Listener_Exception|WC_Paygreen_Payment_Exception
     */
    private static function delete_payment_listener($listener_id)
    {
        $client = WC_Paygreen_Payment_Api::get_paygreen_client();
        $response = $client->deleteListener($listener_id);

        if (!WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
            WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Webhook_Helper::deleteListener - Listener deletion has failed. Response: ' . print_r($response->getBody()->getContents(), true));

            throw new WC_Paygreen_Payment_Listener_Exception('The listener deletion has failed : ' . $listener_id);
        }

        return true;
    }

    /**
     * Build the payment listener URL for Paygreen triggers. Used mainly for
     * asynchronous redirect payment methods in which statuses are
     * not immediately chargeable.
     *
     * @since 1.0.13
     * @return string
     */
    private static function build_payment_listener_url() {
        $url = add_query_arg('wc-api', 'wc_paygreen_payment_webhook_controller', trailingslashit(get_home_url()));

        if (getenv('PAYGREEN_DEBUG')) {
            $url = str_replace('http://', 'https://', $url);
        }

        return $url;
    }
}