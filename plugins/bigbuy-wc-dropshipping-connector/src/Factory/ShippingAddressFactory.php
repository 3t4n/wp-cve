<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Model\ShippingAddress;

class ShippingAddressFactory
{
    /**
     * @param array $order
     * @return ShippingAddress
     */
    public static function create(array $order): ShippingAddress
    {
        $shippingAddress = new ShippingAddress();
        $shippingAddress->Name = $order['shipping']['first_name'];
        $shippingAddress->Surname = $order['shipping']['last_name'];
        $shippingAddress->Email = $order['billing']['email'];
        $shippingAddress->Company = $order['shipping']['company'];
        $shippingAddress->Phone = $order['billing']['phone'];
        $shippingAddress->AddressLine1 = $order['shipping']['address_1'];
        $shippingAddress->AddressLine2 = $order['shipping']['address_2'];
        $shippingAddress->AddressCity = $order['shipping']['city'];
        $shippingAddress->AddressPostalCode = $order['shipping']['postcode'];
        $shippingAddress->AddressCountry = $order['shipping']['country'];
        $shippingAddress->Comments = $order['customer_note'];

        return $shippingAddress;
    }
}