<?php

namespace FluentConnect\App\Services\ThriveCart;

use FluentConnect\App\Models\Integration;
use FluentConnect\Framework\Support\Arr;

class ThriveCartHelper
{
    public static function getProducts($apiKey)
    {
        if(!$apiKey) {
            return [];
        }

        $api = new Api($apiKey);
        $products = $api->get('products');
        if (is_wp_error($products)) {
            $products = [];
        }

        return $products;
    }

    public static function getBumps($apiKey)
    {
        if(!$apiKey) {
            return [];
        }
        $api = new Api($apiKey);
        $bumps = $api->get('bumps');
        if (is_wp_error($bumps)) {
            $bumps = [];
        }

        return $bumps;
    }

    public static function getGroupedProductsOptions($integrationId, $withBumps = true)
    {
        $integration = Integration::where('id', $integrationId)->first();
        if (!$integration || $integration->status != 'published') {
            return [];
        }

        $apiKey = Arr::get($integration->settings, 'api_key');

        $products = self::getProducts($apiKey);

        $returnData = [
            [
                'label'   => 'ThriveCart Products',
                'options' => self::formatProducts($products, 'product-')
            ]
        ];

        if($withBumps) {
            $bumps = self::getBumps($apiKey);
            $returnData[] = [
                'label' => 'ThriveCart Bumps',
                'options' => self::formatProducts($bumps, 'bump-')
            ];
        }

        return $returnData;
    }

    public static function formatProducts($products, $idPrefix = '')
    {
        $formatted = [];

        foreach ($products as $product) {
            $formatted[] = [
                'id'    => $idPrefix.$product['product_id'],
                'title' => $product['name']
            ];
        }

        return $formatted;
    }

    public static function getDataFromCustomer($customer)
    {
        if(!$customer) {
            return [];
        }

        $data = Arr::only($customer, ['name', 'email', 'ip_address', 'first_name', 'last_name']);

        if(!empty($data['ip_address'])) {
            $data['ip'] = $data['ip_address'];
            unset($data['ip_address']);
        }

        if($address = Arr::get($customer, 'address', [])) {
            unset($address['name']);

            if(isset($address['line1'])) {
                $address['address_line_1'] = $address['line1'];
                unset($address['line1']);
            }

            if(!empty($address['zip'])) {
                $address['postal_code'] = $address['zip'];
                unset($address['zip']);
            }
            $address = array_filter($address);
            $data = array_merge($data, $address);
        }

        if($shippingAddress = Arr::get($customer, 'shipping_address', [])) {
            unset($shippingAddress['name']);

            if(isset($shippingAddress['line1'])) {
                $shippingAddress['address_line_1'] = $shippingAddress['line1'];
                unset($shippingAddress['line1']);
            }

            if(!empty($shippingAddress['zip'])) {
                $shippingAddress['postal_code'] = $shippingAddress['zip'];
                unset($address['zip']);
            }
            $shippingAddress = array_filter($shippingAddress);
            $data = array_merge($data, $shippingAddress);
        }

        $data = array_filter($data);

        return apply_filters('fluent_connect_thrivecart_customer_data', $data, $customer);

    }
}
