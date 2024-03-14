<?php

namespace WcMipConnector\Factory\BigBuy;

use WcMipConnector\Client\BigBuy\Model\Delivery;
use WcMipConnector\Client\BigBuy\Model\Order;
use WcMipConnector\Client\BigBuy\Model\Product;
use WcMipConnector\Client\BigBuy\Model\ShippingRequest;

defined('ABSPATH') || exit;

class ShippingFactory
{
    /**
     * @param string $isoCountry
     * @param string $postCode
     * @param array $products
     * @return ShippingRequest
     */
    public function create($isoCountry, $postCode, $products)
    {
        $shippingRequest = new ShippingRequest();
        $order = new Order();
        $delivery = new Delivery();
        $delivery->isoCountry = $isoCountry;
        $delivery->postcode = $postCode;
        $order->delivery = $delivery;
        $productsQuery = [];

        foreach ($products as $product) {
            $productQuery = new Product();
            $productQuery->reference = $product['sku'];
            $productQuery->quantity = (int)$product['quantity'];
            $productsQuery[] = $productQuery;
        }

        $order->products = $productsQuery;
        $shippingRequest->order = $order;

        return $shippingRequest;
    }

    public function createMultiShipping(ShippingRequest $shippingRequest, array $productReferences): ShippingRequest
    {
        $shippingRequestMultiShipping = new ShippingRequest();
        $order = new Order();
        $delivery = new Delivery();
        $delivery->isoCountry = $shippingRequest->order->delivery->isoCountry;
        $delivery->postcode = $shippingRequest->order->delivery->postcode;
        $order->delivery = $delivery;

        $productsQuery = [];

        /** @var Product $product */
        foreach ($shippingRequest->order->products as $product) {
            if (!\in_array($product->reference, $productReferences)) {
                continue;
            }

            $productsQuery[] = $product;
        }

        $order->products = $productsQuery;
        $shippingRequestMultiShipping->order = $order;

        return $shippingRequestMultiShipping;
    }
}