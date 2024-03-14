<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Model\Order;

class OrderFactory
{
    private const ROUND_PRECISION = 2;

    /**
     * @param array $order
     * @return Order
     */
    public static function create(array $order): Order
    {
        $orderModel = new Order();
        $orderModel->OrderID = $order['id'];
        $orderModel->Reference = $order['number'];
        $orderModel->Currency = $order['currency'];
        $orderModel->OrderTotal = PriceFactory::create($order['currency'], $order['total']);
        $orderModel->PaymentMethod = $order['payment_method_title'];
        $orderModel->DateCreated = $order['date_created'];
        $orderModel->DateUpdated = $order['date_modified'];
        $orderModel->ShippingAddress = ShippingAddressFactory::create($order);
        $orderModel->ShippingCost = PriceFactory::create($order['currency'], round($order['shipping_total'] + $order['shipping_tax'], self::ROUND_PRECISION));
        $orderModel->OrderLines = OrderLineFactory::create($order);
        $orderModel->State = $order['status'];
        $orderModel->ShippingService = $order['shipping_lines'][0]['method_title'] ?? '';

        return $orderModel;
    }

    /**
     * @param string $shippingService
     * @param string $trackingNumber
     * @return array
     */
    public function createTrackingNote(string $shippingService, string $trackingNumber): array
    {
        return [
            'note' => 'Tracking: '.$shippingService.' - '.$trackingNumber,
            'customer_note' => true,
        ];
    }
}