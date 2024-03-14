<?php

namespace Paygreen\Module\Helper;

use DateTime;
use Paygreen\Module\Exception\WC_Paygreen_Payment_Exception;
use Paygreen\Module\WC_Paygreen_Payment_Api;
use Paygreen\Module\WC_Paygreen_Payment_Gateway;
use Paygreen\Module\WC_Paygreen_Payment_Logger;
use Paygreen\Sdk\Payment\V3\Enum\DomainEnum;
use Paygreen\Sdk\Payment\V3\Environment;
use Paygreen\Sdk\Payment\V3\Model\Address;
use Paygreen\Sdk\Payment\V3\Model\Buyer;
use Paygreen\Sdk\Payment\V3\Model\PaymentOrder;
use WC_AJAX;
use WC_Order;

if (!defined('ABSPATH')) {
    exit;
}

class WC_Paygreen_Payment_Payment_Order_Helper
{
    /**
     * Creates payment order using current cart or order.
     *
     * @param WC_Paygreen_Payment_Gateway $gateway
     * @param WC_Order $wc_order The order to be paid for
     * @throws WC_Paygreen_Payment_Exception - Thrown if the call to create a payment order returns an error
     * @return array
     */
    public static function create_payment_order($gateway, $wc_order)
    {
        $return_url = get_site_url() . add_query_arg(
            [
                'order_id' => $wc_order ? $wc_order->get_id() : null,
                'nonce' => wp_create_nonce('wc_paygreen_payment_return_controller'),
                'redirect_to' => rawurlencode($gateway->get_return_url($wc_order)),
            ],
            WC_AJAX::get_endpoint('wc_paygreen_payment_return_controller')
        );
        $cancel_url = $wc_order->get_checkout_payment_url();

        $client = WC_Paygreen_Payment_Api::get_paygreen_client();

        $buyer = new Buyer();
        $buyer->setFirstname($wc_order->get_billing_first_name());
        $buyer->setLastname($wc_order->get_billing_last_name());
        $buyer->setEmail($wc_order->get_billing_email());

        $customer_id = $wc_order->get_customer_id();
        $paygreen_buyer_id = get_user_meta($customer_id, 'paygreen_buyer_id')[0];


        if (!empty($paygreen_buyer_id) && preg_match('/^buy_/', $paygreen_buyer_id)) {
            $response = $client->getBuyer($paygreen_buyer_id);
            $response_data = json_decode($response->getBody()->getContents());

            if (WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
                $buyer->setId($paygreen_buyer_id);

                if ($buyer->getEmail() !== $response_data->data->email) {
                    $response = $client->updateBuyer($buyer);

                    if (WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
                        update_user_meta($customer_id, 'paygreen_buyer_updated_at', (new \DateTime())->getTimestamp());
                    } else {
                        WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Payment_Order_Helper::create_payment_order - Failed to update buyer information. Response: ' . print_r($response->getBody()->getContents(), true));
                    }
                }
            }
        }

        $isShippingAddressValid = self::isShippingAddressValid($wc_order);
        $isBillingAddressValid = self::isBillingAddressValid($wc_order);

        if ($isShippingAddressValid) {
            $shipping_address = new Address();
            $shipping_address->setStreetLineOne($wc_order->get_shipping_address_1());
            $shipping_address->setStreetLineTwo($wc_order->get_shipping_address_2());
            $shipping_address->setPostalCode($wc_order->get_shipping_postcode());
            $shipping_address->setCity($wc_order->get_shipping_city());
            $shipping_address->setCountryCode($wc_order->get_shipping_country());
        }

        if ($isBillingAddressValid) {
            $billing_address = new Address();
            $billing_address->setStreetLineOne($wc_order->get_billing_address_1());
            $billing_address->setStreetLineTwo($wc_order->get_billing_address_2());
            $billing_address->setPostalCode($wc_order->get_billing_postcode());
            $billing_address->setCity($wc_order->get_billing_city());
            $billing_address->setCountryCode($wc_order->get_billing_country());
            $buyer->setBillingAddress($billing_address);
        }

        $payment_order_reference = 'wc-ord-' . (new DateTime())->getTimestamp() . '-' . $wc_order->get_id() . '-1' ;

        $payment_order = new PaymentOrder();
        $payment_order->setAutoCapture(true);
        $payment_order->setEligibleAmounts(self::get_cart_eligible_amount($wc_order));
        $payment_order->setCurrency(strtolower(get_woocommerce_currency()));

        if ($wc_order->has_shipping_address() && $isShippingAddressValid) {
            $payment_order->setShippingAddress($shipping_address);
        }

        $payment_order->setBuyer($buyer);
        $payment_order->setAmount((int) ($wc_order->get_total() * 100));
        $payment_order->setReference($payment_order_reference);
        $payment_order->setReturnUrl($return_url);
        $payment_order->setCancelUrl($cancel_url);
        $payment_order->setMetadata(['order_id' => $wc_order->get_id()]);

        if ($gateway->settings['sub_shop_id'] !== null && preg_match( '/^sh_/', $gateway->settings['sub_shop_id'])) {
            $payment_order->setShopId($gateway->settings['sub_shop_id']);
        }

        switch ($gateway->settings['environment']) {
            case Environment::ENVIRONMENT_RECETTE:
            case Environment::ENVIRONMENT_SANDBOX:
                $result = false;

                do {
                    $response = $client->createPaymentOrder($payment_order);
                    $response_data = json_decode($response->getBody()->getContents());

                    if (WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
                        $paygreen_buyer_id = get_user_meta($customer_id, 'paygreen_buyer_id');

                        if (empty($paygreen_buyer_id)) {
                            add_user_meta($customer_id, 'paygreen_buyer_id', $response_data->data->buyer->id);
                        }

                        $result = true;
                    } elseif ($response->getStatusCode() === 422) {
                        $payment_orders = self::get_payment_orders($payment_order->getReference());
                        $po_has_been_cancelled = false;

                        foreach ($payment_orders as $item) {
                            if ($item->status == 'payment_order.' . \Paygreen\Sdk\Payment\V3\Enum\StatusEnum::PENDING) {
                                if (!self::cancel_payment_order($item->id)) {
                                    $payment_order->setReference(self::regenerate_reference($payment_order->getReference()));
                                    $po_has_been_cancelled = true;
                                    break;
                                }
                            }
                        }

                        if (!$po_has_been_cancelled) {
                            $payment_order->setReference(self::regenerate_reference($payment_order->getReference()));
                        }
                    } else {
                        WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Payment_Order_Helper::create_payment_order - RECETTE/SANDBOX - Failed to create payment order. Response: ' . print_r($response_data, true));

                        throw new WC_Paygreen_Payment_Exception(
                            'Failed to create payment order',
                            __('An error occurred during payment creation. Please check the information you have provided. Check that your information does not contain any special characters and is at least two characters long.', 'paygreen-payment-gateway')
                        );
                    }
                } while (!$result);

                return [
                    'success' => true,
                    'payment_order_id' => $response_data->data->id,
                    'object_secret' => $response_data->data->object_secret,
                    'hosted_payment_url' => $response_data->data->hosted_payment_url,
                ];
            case Environment::ENVIRONMENT_PRODUCTION:
                $response = $client->createPaymentOrder($payment_order);
                $response_data = json_decode($response->getBody()->getContents());

                if (WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
                    $paygreen_buyer_id = get_user_meta($customer_id, 'paygreen_buyer_id');

                    if (empty($paygreen_buyer_id)) {
                        add_user_meta($customer_id, 'paygreen_buyer_id', $response_data->data->buyer->id);
                    }

                    return [
                        'success' => true,
                        'payment_order_id' => $response_data->data->id,
                        'object_secret' => $response_data->data->object_secret,
                        'hosted_payment_url' => $response_data->data->hosted_payment_url,
                    ];
                } elseif ($response->getStatusCode() === 422) {
                    $payment_orders = self::get_payment_orders($payment_order->getReference());

                    foreach ($payment_orders as $item) {
                        switch ($item->status) {
                            case 'payment_order.' . \Paygreen\Sdk\Payment\V3\Enum\StatusEnum::PENDING:
                                if (self::cancel_payment_order($payment_order_reference)) {
                                    $response = $client->createPaymentOrder($payment_order);
                                    $response_data = json_decode($response->getBody()->getContents());

                                    if (!WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
                                        WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Payment_Order_Helper::create_payment_order - PRODUCTION - Failed to create payment order. Response: ' . print_r($response_data, true));

                                        throw new WC_Paygreen_Payment_Exception(
                                            'Failed to create payment order',
                                            __('An error occurred during payment creation. Please check the information you have provided. Check that your information does not contain any special characters and is at least two characters long.', 'paygreen-payment-gateway')
                                        );
                                    }

                                    $paygreen_buyer_id = get_user_meta($customer_id, 'paygreen_buyer_id');

                                    if (empty($paygreen_buyer_id)) {
                                        add_user_meta($customer_id, 'paygreen_buyer_id', $response_data->data->buyer->id);
                                    }

                                    return [
                                        'success' => true,
                                        'payment_order_id' => $response_data->data->id,
                                        'object_secret' => $response_data->data->object_secret,
                                        'hosted_payment_url' => $response_data->data->hosted_payment_url,
                                    ];
                                }

                                break;
                            case 'payment_order.' . \Paygreen\Sdk\Payment\V3\Enum\StatusEnum::REFUSED:
                                $result = false;

                                do {
                                    $payment_order->setReference(self::regenerate_reference($payment_order->getReference()));
                                    $response = $client->createPaymentOrder($payment_order);
                                    $response_data = json_decode($response->getBody()->getContents());

                                    if (WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
                                        $paygreen_buyer_id = get_user_meta($customer_id, 'paygreen_buyer_id');

                                        if (empty($paygreen_buyer_id)) {
                                            add_user_meta($customer_id, 'paygreen_buyer_id', $response_data->data->buyer->id);
                                        }

                                        $result = true;
                                    }
                                } while (!$result);

                                return [
                                    'success' => true,
                                    'payment_order_id' => $response_data->data->id,
                                    'object_secret' => $response_data->data->object_secret,
                                    'hosted_payment_url' => $response_data->data->hosted_payment_url,
                                ];
                        }
                    }
                } else {
                    WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Payment_Order_Helper::create_payment_order - PRODUCTION - Failed to create payment order. Response: ' . print_r($response_data, true));

                    throw new WC_Paygreen_Payment_Exception(
                        'Failed to create payment order',
                        __('An error occurred during payment creation. Please check the information you have provided. Check that your information does not contain any special characters and is at least two characters long.', 'paygreen-payment-gateway')
                    );
                }
        }

        WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Payment_Order_Helper::create_payment_order - An error occurred while create payment order. Response: ' . print_r( $response_data, true));
        throw new WC_Paygreen_Payment_Exception(__('Failed to create payment order.', 'paygreen-payment-gateway'));
    }

    /**
     * Add payment order id and order note to order
     *
     * @since 0.0.0
     * @param string $payment_order_id
     * @param WC_Order $wc_order
     *
     * @return void
     */
    public static function add_payment_order_to_order($payment_order_id, $wc_order)
    {
        $old_payment_order_id = $wc_order->get_meta('_paygreen_payment_order_id');

        if ($old_payment_order_id === $payment_order_id) {
            return;
        }

        $wc_order->add_order_note(
            sprintf(
            /* translators: $1%s payment order id */
                __('Paygreen payment order created (Payment Order ID: %1$s)', 'paygreen-payment-gateway'),
                $payment_order_id
            )
        );

        $wc_order->update_meta_data('_paygreen_payment_order_id', $payment_order_id);
        $wc_order->save();
    }

    /***
     * @param string $payment_order_id
     * @return bool
     * @throws \Exception
     */
    public static function get_payment_order_status($payment_order_id)
    {
        $client = WC_Paygreen_Payment_Api::get_paygreen_client();
        $response = $client->getPaymentOrder($payment_order_id);

        if (WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
            $payment_order = json_decode($response->getBody()->getContents())->data;

            return explode('.', $payment_order->status)[1];
        } else {
            WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Payment_Order_Helper::get_payment_order_status - ' . $payment_order_id . ' - Payment order not found');

            return null;
        }
    }

    /**
     * @param string $payment_order_id
     * @param \stdClass|null $metadata
     * @return bool|\WC_Order|\WC_Order_Refund
     * @throws \Exception
     */
    public static function get_order_from_metadata($payment_order_id, $metadata)
    {
        if (is_null($metadata) || !isset($metadata['order_id'])) {
            WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Webhook_Controller:get_order_from_metadata - ' . $payment_order_id . ' - Missing mandatory metadata : order_id');
            throw new \Exception('Missing mandatory metadata : order_id');
        }

        if (absint($metadata->order_id)) {
            $order_id = $metadata['order_id'];
        } else {
            $order_id = false;
        }

        return wc_get_order($order_id);
    }

    /**
     * @param string $reference
     * @return string
     */
    public static function get_order_id_from_reference($reference)
    {
        $referenceParts = explode('-', $reference);

        return $referenceParts['3'];
    }

    /**
     * @param string $reference
     * @return mixed
     * @throws WC_Paygreen_Payment_Exception
     */
    private static function get_payment_orders($reference)
    {
        $response = WC_Paygreen_Payment_Api::get_paygreen_client()->listPaymentOrder($reference);
        $response_data = json_decode($response->getBody()->getContents());

        if (!WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
            WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Payment_Order_Helper::get_payment_orders - Failed to fetch payment orders with reference ' . $reference . '. Response: ' . print_r($response_data, true));
            throw new WC_Paygreen_Payment_Exception('Failed to fetch payment order.');
        }

        return $response_data->data;
    }

    /**
     * @param string $reference
     * @return bool
     * @throws WC_Paygreen_Payment_Exception
     */
    private static function cancel_payment_order($reference)
    {
        $payment_orders = self::get_payment_orders($reference);

        if (!empty($payment_orders)) {
            foreach ($payment_orders as $payment_order) {
                if ($payment_order->status === 'payment_order.' . \Paygreen\Sdk\Payment\V3\Enum\StatusEnum::PENDING) {
                    $response = WC_Paygreen_Payment_Api::get_paygreen_client()->cancelPaymentOrder($payment_order->id);
                    $response_data = json_decode($response->getBody()->getContents());

                    if (!WC_Paygreen_Payment_Api::is_valid_response($response->getStatusCode())) {
                        WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Payment_Order_Helper::cancel_payment_order - Failed to cancel payment order with reference ' . $reference . '. Response: ' . print_r($response_data, true));
                        throw new WC_Paygreen_Payment_Exception('Failed to cancel payment order.');
                    }

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string $reference
     * @return string
     */
    private static function regenerate_reference($reference)
    {
        $reference_parts = explode('-', $reference);
        $attempt = (int) end($reference_parts);
        unset($reference_parts[count($reference_parts) - 1]);

        return implode('-', $reference_parts) . '-' . ($attempt + 1);
    }

    /**
     * Returns the amount of the order which is elible to FOOD or TRAVEL payment methods.
     *
     * @param ?WC_Order $wc_order If an order is not placed, the cart will be used.
     *
     * @return array
     * @since 0.0.4
     */
    private static function get_cart_eligible_amount($wc_order = null)
    {
        $items = [];
        $settings = get_option('woocommerce_paygreen_payment_settings');

        $food = $travel = 0;
        $total_amount = 0;

        if ($wc_order) {
            foreach ($wc_order->get_items() as $item) {
                $total_amount = $wc_order->get_total() * 100;
                $items[] = [
                    'product_id' => $item->get_data()['product_id'],
                    'variation_id' => $item->get_data()['variation_id'],
                    'quantity' => $item->get_quantity(),
                ];
            }
        } elseif (WC()->cart) {
            $cart = WC()->cart;
            $total_amount = ($cart->get_cart_contents_total() + $cart->get_cart_contents_tax()) * 100;
            $items = $cart->get_cart_contents();
        }

        if (empty($items) || (!isset($settings['available_for_food']) && !isset($settings['available_for_travel']))) {
            return [
                DomainEnum::FOOD => $food,
                DomainEnum::TRAVEL => $travel,
            ];
        }

        foreach ($items as $item) {
            $product_id = $item['product_id'];
            $variation_id = $item['variation_id'];
            $isVariableProduct = $variation_id !== 0;
            $quantity = $item['quantity'];
            $product = !$isVariableProduct ? wc_get_product($product_id) : wc_get_product($variation_id);
            $price = $product->get_price() * 100;

            if ($product->is_taxable()) {
                $price = wc_get_price_including_tax($product, ['price' => $price]);
            }

            $product_categories = get_the_terms($product_id, 'product_cat');

            foreach ($product_categories as $category) {
                if (isset($settings['available_for_food_allow_all'])
                    && $settings['available_for_food_allow_all'] === 'yes'
                ) {
                    $food += ($price * $quantity);
                } elseif (isset($settings['available_for_food'])
                    && !empty($settings['available_for_food'])
                    && in_array((string) $category->term_id, $settings['available_for_food'])
                ) {
                    $food += ($price * $quantity);
                }

                if (isset($settings['available_for_travel_allow_all'])
                    && $settings['available_for_travel_allow_all'] === 'yes'
                ) {
                    $travel += ($price * $quantity);
                } elseif (isset($settings['available_for_travel'])
                    && !empty($settings['available_for_travel'])
                    && in_array((string) $category->term_id, $settings['available_for_travel'])
                ) {
                    $travel += ($price * $quantity);
                }
            }
        }

        $eligible_amounts = [
            DomainEnum::FOOD => (int) $food,
            DomainEnum::TRAVEL => (int) $travel,
        ];

        if ($eligible_amounts[DomainEnum::FOOD] > $total_amount) {
            $eligible_amounts[DomainEnum::FOOD] = (int) $total_amount;
        }

        if ($eligible_amounts[DomainEnum::TRAVEL] > $total_amount) {
            $eligible_amounts[DomainEnum::TRAVEL] = (int) $total_amount;
        }

        if (!isset($settings['shipping_cost_exclusion']) || $settings['shipping_cost_exclusion'] === 'no') {
            $eligible_amounts[DomainEnum::FOOD] += (int) ($wc_order->get_shipping_total() * 100);
            $eligible_amounts[DomainEnum::TRAVEL] += (int) ($wc_order->get_shipping_total() * 100);
        }

        return $eligible_amounts;
    }

    /**
     * @param WC_Order $wc_order
     * @return bool
     */
    private static function isShippingAddressValid(WC_Order $wc_order)
    {
        if (self::isAtLeastTwoCharacterLong($wc_order->get_shipping_address_1())
            && self::isAtLeastTwoCharacterLong($wc_order->get_shipping_postcode())
            && self::isAtLeastTwoCharacterLong($wc_order->get_shipping_city())
            && self::isAtLeastTwoCharacterLong($wc_order->get_shipping_country())
        ) {
            if (!empty($wc_order->get_shipping_address_2()) && !self::isAtLeastTwoCharacterLong($wc_order->get_shipping_address_2())) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @param WC_Order $wc_order
     * @return bool
     */
    private static function isBillingAddressValid(WC_Order $wc_order)
    {
        if (self::isAtLeastTwoCharacterLong($wc_order->get_billing_address_1())
            && self::isAtLeastTwoCharacterLong($wc_order->get_billing_postcode())
            && self::isAtLeastTwoCharacterLong($wc_order->get_billing_city())
            && self::isAtLeastTwoCharacterLong($wc_order->get_billing_country())
        ) {
            if (!empty($wc_order->get_billing_address_2()) && !self::isAtLeastTwoCharacterLong($wc_order->get_billing_address_2())) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @param string $value
     * @return bool
     */
    private static function isAtLeastTwoCharacterLong($value)
    {
        return !empty($value) && strlen($value) >= 2;
    }
}