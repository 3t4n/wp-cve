<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Model\OrderLine;

class OrderLineFactory
{
    /**
     * @param array $order
     * @return OrderLine[]
     */
    public static function create(array $order): array
    {
        $orderItems = [];

        foreach ($order['line_items'] as $line) {
            $orderLine = new OrderLine();
            $orderLine->ASIN = $line['sku'];
            $orderLine->Reference = $line['id'];
            $orderLine->ItemPrice = PriceFactory::create($order['currency'], $line['total']);
            $orderLine->ItemTax = PriceFactory::create($order['currency'], $line['total_tax']);
            $orderLine->OrderItemId = $line['id'];
            $orderLine->QuantityOrdered = $line['quantity'];
            $orderLine->SellerSKU = $line['sku'];
            $orderLine->ShippingPrice = PriceFactory::create($order['currency']);
            $orderLine->ShippingTax = PriceFactory::create($order['currency']);
            $orderLine->ItemTitle = $line['name'];
            $orderItems[] = $orderLine;
        }

        return $orderItems;
    }
}