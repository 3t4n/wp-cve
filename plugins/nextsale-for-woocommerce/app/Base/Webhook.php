<?php

namespace App\Base;

use App\Utils\Client;
use App\Utils\Helper;

class Webhook
{
    // Webhooks
    const UNINSTALL = 'app/uninstalled';
    const ORDER_CREATE = 'orders/create';
    const ORDER_UPDATE = 'orders/updated';
    const ORDER_DELETE = 'orders/delete';
    const PRODUCT_CREATE = 'products/create';
    const PRODUCT_UPDATE = 'products/update';
    const PRODUCT_DELETE = 'products/delete';
    const CUSTOMER_CREATE = 'customers/create';
    const CUSTOMER_UPDATE = 'customers/update';
    const CUSTOMER_DELETE = 'customers/delete';
    const SHOP_UPDATE = 'shop/update';

    /**
     * Send request to webhook
     *
     * @param string $topic
     * @param array $content
     * @return void
     */
    public static function send($topic, $content)
    {
        // Webhook addresses
        $addresses = json_decode(get_option('nextsale_webhooks'));
        if (!$addresses || !is_array($addresses) || !$content) {
            return false;
        }

        // Headers
        $headers = [
            'X-Wordpress-Signature' => Helper::generateWebhookHmac($content),
            'X-Wordpress-Topic' => $topic,
            'X-Wordpress-Domain' => Helper::getDomain()
        ];

        $client = new Client([
            'headers' => $headers
        ]);

        // Send requests
        foreach ($addresses as $addr) {
            try {
                $client->post($addr, $content);
            } catch (\Exception $e) {
                // Couldn't send the request to the current webhook address
                // Continue to the next address.
            }
        }
    }
}
