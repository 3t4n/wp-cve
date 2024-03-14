<?php

class HelcimApiFactory
{
    public static function buildLineItems(WC_Abstract_Order $order, bool $taxed): array
    {
        $lineItems = [];
        if (is_array($order->get_items())) {
            foreach ($order->get_items() as $item) {
                if (!$item instanceof WC_Order_Item_Product) {
                    continue;
                }
                $product = $item->get_product();
                if (!$product instanceof WC_Product) {
                    continue;
                }
                $sku = $product->get_sku() ?: 'NoSKU';
                $lineItems[] = [
                    'sku' => $sku,
                    'description' => $item['name'],
                    'quantity' => $item['qty'],
                    'price' => $order->get_item_total($item, $taxed, false),
                    'total' => $order->get_line_total($item, $taxed, false),
                    'taxAmount' => $order->get_item_tax($item, false),
                ];
            }
        }
        if (is_array($order->get_fees())) {
            foreach ($order->get_fees() as $fee) {
                $lineItems[] = [
                    'sku' => 'Fee' . $fee->get_id(),
                    'description' => $fee->get_name(),
                    'quantity' => '1',
                    'price' => $fee->get_total(),
                    'total' => $fee->get_total(),
                    'taxAmount' => $fee->get_total_tax(),
                ];
            }
        }
        return $lineItems;
    }

    public static function buildInvoice(string $invoiceNumber, WC_Abstract_Order $order, bool $taxed): array
    {
        if (!$order instanceof WC_Order && !$order instanceof WC_Order_Refund) {
            WCHelcimGateway::log("Invalid Order. type:" . get_class($order));
            return [];
        }

        $shippingAmount = number_format($order->get_shipping_total(), 2, '.', '');
        $taxAmount = number_format($order->get_total_tax(), 2, '.', '');
        $refundAmount = 0;
        if (is_array($order->get_refunds())) {
            foreach ($order->get_refunds() as $refund) {
                $refundAmount += $refund->get_amount();
            }
        }
        $discountAmount = number_format('-' . ($order->get_total_discount() + $refundAmount), 2, '.', '');

        $invoice = [
            'invoiceNumber' => $invoiceNumber,
            'notes' => $order->get_customer_note(),
            'lineItems' => self::buildLineItems($order, $taxed),
        ];
        if ((float)$shippingAmount > 0) {
            $details = empty($order->get_shipping_method()) ? 'no shipping details' : $order->get_shipping_method();
            $invoice['shipping'] = [
                'amount' => (float)$shippingAmount,
                'details' => $details,
                'address' => self::buildShippingAddress($order),
            ];
        }
        if ((float)$taxAmount > 0) {
            $taxNames = [];
            foreach ($order->get_taxes() as $tax) {
                $taxNames[] = $tax->get_name();
            }
            $details = count($taxNames) ? implode(', ', $taxNames) : 'no tax name';
            $invoice['tax'] = [
                'amount' => (float)$taxAmount,
                'details' => $details,
            ];
        }
        if ((float)$discountAmount > 0) {
            $details = empty($order->get_discount_to_display())
                ? 'no discount name' : $order->get_discount_to_display();
            $invoice['discount'] = [
                'amount' => (float)$discountAmount,
                'details' => $details,
            ];
        }
        return $invoice;
    }

    public static function buildPurchasePreauth(
        WCHelcimGateway $wcHelcimGateway,
        WC_Abstract_Order $order,
        string $customerCode
    ): array {
        if (!$order instanceof WC_Order && !$order instanceof WC_Order_Refund) {
            WCHelcimGateway::log("Invalid Order. type:" . get_class($order));
            return [];
        }
        $cardData = [];
        if ($wcHelcimGateway->isJS()) {
            $cardData = [
                'cardToken' => (string)$_POST['cardToken'],
            ];
        } elseif ($wcHelcimGateway->isDirect()) {
            $cardData = [
                'cardNumber' => (string)$_POST['cardNumber'],
                'cardExpiry' => str_pad($_POST['cardExpiryMonth'], 2, '0', STR_PAD_LEFT)
                    . substr($_POST['cardExpiryYear'], -2),
                'cardCVV' => (string)$_POST['cardCVV'],
            ];
        }
        $purchase = array_merge(
            self::genericPaymentPayload($wcHelcimGateway),
            [
                'currency' => $wcHelcimGateway->woocommerceCurrencyAbbreviation(),
                'amount' => number_format($order->get_total(), 2, '.', ''),
                'customerCode' => $customerCode,
                'invoiceNumber' => '',
                'invoice' => self::buildInvoice(
                    $wcHelcimGateway->setOrderNumber((string)$order->get_order_number()),
                    $order,
                    false
                ),
                'billingAddress' => self::buildBillingAddress($order),
                'cardData' => $cardData,
            ]
        );
        self::recursiveTrim($purchase);
        return $purchase;
    }

    public static function buildBillingAddress(WC_Abstract_Order $order): array
    {
        if (!$order instanceof WC_Order && !$order instanceof WC_Order_Refund) {
            WCHelcimGateway::log("Invalid Order. type:" . get_class($order));
            return [];
        }

        return [
            'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            'street1' => $order->get_billing_address_1(),
            'street2' => $order->get_billing_address_2(),
            'city' => $order->get_billing_city(),
            'province' => $order->get_billing_state(),
            'postalCode' => $order->get_billing_postcode(),
            'country' => COUNTRIES::convertAlpha2ToAlpha3($order->get_billing_country()),
            'phone' => $order->get_billing_phone(),
            'email' => $order->get_billing_email(),
        ];
    }

    public static function buildShippingAddress(WC_Abstract_Order $order): array
    {
        if (!$order instanceof WC_Order && !$order instanceof WC_Order_Refund) {
            WCHelcimGateway::log("Invalid Order. type:" . get_class($order));
            return [];
        }
        return [
            'name' => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
            'street1' => $order->get_shipping_address_1(),
            'street2' => $order->get_shipping_address_2(),
            'city' => $order->get_shipping_city(),
            'province' => $order->get_shipping_state(),
            'postalCode' => $order->get_shipping_postcode(),
            'country' => COUNTRIES::convertAlpha2ToAlpha3($order->get_shipping_country()),
        ];
    }

    private static function getCountryAbbreviation(string $country): string
    {
        if($country === 'CA'){
            return 'CAN';
        }
        if($country === 'US'){
            return 'USA';
        }
        return $country;
    }

    private static function recursiveTrim(array &$data): void
    {
        foreach ($data as &$value) {
            if (is_array($value)) {
                self::recursiveTrim($value);
                continue;
            }
            $value = trim($value);
        }
    }

    public static function buildCustomerUpdate(string $customerCode, WC_Abstract_Order $order): array
    {
        if (!$order instanceof WC_Order && !$order instanceof WC_Order_Refund) {
            WCHelcimGateway::log("Invalid Order. type:" . get_class($order));
            return [];
        }

        $billingAddress = self::buildBillingAddress($order);
        return [
            'customerCode' => $customerCode,
            'contactName' => $billingAddress['name'] ?? 'No Name',
            'businessName' => $order->get_billing_company(),
            'cellPhone' => $billingAddress['phone'],
        ];

    }
    public static function buildCustomerCreate(string $customerCode, WC_Abstract_Order $order): array
    {
        if (!$order instanceof WC_Order && !$order instanceof WC_Order_Refund) {
            WCHelcimGateway::log("Invalid Order. type:" . get_class($order));
            return [];
        }

        $customer = self::buildCustomerUpdate($customerCode, $order);

        $billingAddress = self::buildBillingAddress($order);
        $buildBillingAddress = false;
        foreach ($billingAddress as $value){
            if(!empty(trim($value))){
                $buildBillingAddress = true;
                break;
            }
        }
        if($buildBillingAddress){
            $customer['billingAddress'] = $billingAddress;
        }

        $shippingAddress = self::buildShippingAddress($order);
        $buildShippingAddress = false;
        foreach ($shippingAddress as $value){
            if(!empty(trim($value))){
                $buildShippingAddress = true;
                break;
            }
        }
        if($buildShippingAddress){
            $customer['shippingAddress'] = $shippingAddress;
        }

        self::recursiveTrim($customer);
        return $customer;
    }

    public static function buildRefund(WC_Abstract_Order $order, float $amount, WCHelcimGateway $helcimGateway): array
    {
        if (!$order instanceof WC_Order && !$order instanceof WC_Order_Refund) {
            WCHelcimGateway::log("Invalid Order. type:" . get_class($order));
            return [];
        }
        return array_merge(
            self::genericPaymentPayload($helcimGateway),
            [
                'originalTransactionId' => $order->get_transaction_id(),
                'amount' => $amount,
                'ipAddress' => class_exists('WC_Geolocation') ? WC_Geolocation::get_ip_address(
                ) : $_SERVER['REMOTE_ADDR'] ?? '',
                'ecommerce' => true
            ]
        );
    }

    public static function apiV2Headers(WCHelcimGateway $wcHelcimGateway): array
    {
        return [
            "api-token: {$wcHelcimGateway->getAPITokenV2()}",
            "account-id: {$wcHelcimGateway->getAccountId()}",
        ];
    }

    public static function genericPaymentPayload(WCHelcimGateway $wcHelcimGateway): array
    {
        return [
//            'test' => $wcHelcimGateway->isTest(),
            'ecommerce' => 1,
            'ipAddress' => class_exists('WC_Geolocation')
                ? WC_Geolocation::get_ip_address() : $_SERVER['REMOTE_ADDR'] ?? '',
            'thirdParty' => WCHelcimGateway::PLUGIN_NAME,
            'pluginVersion' => WCHelcimGateway::VERSION,
            'server_remote_addr' => $_SERVER['REMOTE_ADDR'] ?? '',
        ];
    }

    public static function buildIdempotencyKey(int $length): string
    {
        return (string)substr(bin2hex(openssl_random_pseudo_bytes($length)), 0, $length);
    }
}