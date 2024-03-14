<?php

namespace wobef\classes\services\update\handlers;

use wobef\classes\helpers\Order_Helper;
use wobef\classes\repositories\History;
use wobef\classes\repositories\Order;
use wobef\classes\services\update\Handler_Interface;

class Woocommerce_Handler implements Handler_Interface
{
    private static $instance;

    private $order;
    private $update_data;
    private $setter_method;
    private $deleted_ids;
    private $created_ids;
    private $order_repository;
    private $current_field_value;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->order_repository = new Order();
    }

    public function update($order_ids, $update_data)
    {
        $this->setter_method = $this->get_setter($update_data['name']);
        if (empty($this->setter_method) && empty($order_ids) && !is_array($order_ids)) {
            return false;
        }

        foreach ($order_ids as $order_id) {
            $order = $this->order_repository->get_order(intval($order_id));
            if (!($order instanceof \WC_Order)) {
                return false;
            }

            $order_array = $this->order_repository->order_to_array($order);
            if (empty($order_array) || !is_array($order_array)) {
                return false;
            }

            $this->order = $order;
            $this->update_data = $update_data;

            // has update method ?
            if (!is_object(${$this->setter_method['object']}) || !method_exists(${$this->setter_method['object']}, $this->setter_method['method'])) {
                return false;
            };

            $this->current_field_value = (!empty($order_array[$this->update_data['name']])) ? $order_array[$this->update_data['name']] : '';

            // set value with operator
            if (!empty($this->update_data['operator'])) {
                $this->set_value_with_operator();
            }

            // run update method
            try {
                ${$this->setter_method['object']}->{$this->setter_method['method']}($this->update_data['value']);
                if (method_exists(${$this->setter_method['object']}, 'save')) {
                    ${$this->setter_method['object']}->save();
                }
            } catch (\Exception $e) {
                return false;
            }

            // save history item
            if (!empty($this->update_data['history_id'])) {
                $history_repository = new History();
                $history_item_result = $history_repository->save_history_item([
                    'history_id' => $this->update_data['history_id'],
                    'historiable_id' => $this->order->get_id(),
                    'name' => $this->update_data['name'],
                    'sub_name' => (!empty($this->update_data['sub_name'])) ? $this->update_data['sub_name'] : '',
                    'type' => $this->update_data['type'],
                    'deleted_ids' => $this->deleted_ids,
                    'created_ids' => $this->created_ids,
                    'prev_value' => $this->current_field_value,
                    'new_value' => $this->update_data['value'],
                ]);
                if (!$history_item_result) {
                    return false;
                }
            }
        }

        return true;
    }

    private function get_setter($field_name)
    {
        $methods = $this->get_setter_methods();
        return (!empty($methods[$field_name])) ? $methods[$field_name] : null;
    }

    private function set_date_completed()
    {
        $date = new \WC_DateTime($this->update_data['value']);
        $this->order->set_date_completed($date);
        $this->order->save();
    }

    private function set_date_paid()
    {
        $date = new \WC_DateTime($this->update_data['value']);
        $this->order->set_date_paid($date);
        $this->order->save();
    }

    private function set_prices_include_tax()
    {
        $this->order->set_prices_include_tax(!empty($this->update_data['value']) && in_array($this->update_data['value'], ['yes', 1]));
        $this->order->save();
    }

    private function set_shipping_total()
    {
        $this->order->set_shipping_total(!empty($this->update_data['value']) && in_array($this->update_data['value'], ['yes', 1]));
        $this->order->save();
    }

    private function set_shipping_tax()
    {
        $this->order->set_shipping_tax(!empty($this->update_data['value']) && in_array($this->update_data['value'], ['yes', 1]));
        $this->order->save();
    }

    private function get_setter_methods()
    {
        return [
            'customer_note' => [
                'object' => 'order',
                'method' => 'set_customer_note',
            ],
            'date_created' => [
                'object' => 'order',
                'method' => 'set_date_created',
            ],
            'order_status' => [
                'object' => 'order',
                'method' => 'set_status',
            ],
            'customer_ip_address' => [
                'object' => 'order',
                'method' => 'set_customer_ip_address',
            ],
            'customer_user' => [
                'object' => 'order',
                'method' => 'set_customer_id',
            ],
            'customer_user_agent' => [
                'object' => 'order',
                'method' => 'set_customer_user_agent',
            ],
            'date_completed' => [
                'object' => 'this',
                'method' => 'set_date_completed',
            ],
            'date_paid' => [
                'object' => 'this',
                'method' => 'set_date_paid',
            ],
            'order_total' => [
                'object' => 'order',
                'method' => 'set_total',
            ],
            'order_discount' => [
                'object' => 'order',
                'method' => 'set_discount_total',
            ],
            'order_discount_tax' => [
                'object' => 'order',
                'method' => 'set_discount_tax',
            ],
            'created_via' => [
                'object' => 'order',
                'method' => 'set_created_via',
            ],
            'order_currency' => [
                'object' => 'order',
                'method' => 'set_currency',
            ],
            'payment_method' => [
                'object' => 'order',
                'method' => 'set_payment_method',
            ],
            'payment_method_title' => [
                'object' => 'order',
                'method' => 'set_payment_method_title',
            ],
            'order_version' => [
                'object' => 'order',
                'method' => 'set_version',
            ],
            'prices_include_tax' => [
                'object' => 'this',
                'method' => 'set_prices_include_tax',
            ],
            'order_shipping' => [
                'object' => 'this',
                'method' => 'set_shipping_total',
            ],
            'order_shipping_tax' => [
                'object' => 'this',
                'method' => 'set_shipping_tax',
            ],
            'billing_address_1' => [
                'object' => 'order',
                'method' => 'set_billing_address_1',
            ],
            'billing_address_2' => [
                'object' => 'order',
                'method' => 'set_billing_address_2',
            ],
            'billing_city' => [
                'object' => 'order',
                'method' => 'set_billing_city',
            ],
            'billing_company' => [
                'object' => 'order',
                'method' => 'set_billing_company',
            ],
            'billing_country' => [
                'object' => 'order',
                'method' => 'set_billing_country',
            ],
            'billing_email' => [
                'object' => 'order',
                'method' => 'set_billing_email',
            ],
            'billing_phone' => [
                'object' => 'order',
                'method' => 'set_billing_phone',
            ],
            'billing_first_name' => [
                'object' => 'order',
                'method' => 'set_billing_first_name',
            ],
            'billing_last_name' => [
                'object' => 'order',
                'method' => 'set_billing_last_name',
            ],
            'billing_postcode' => [
                'object' => 'order',
                'method' => 'set_billing_postcode',
            ],
            'billing_state' => [
                'object' => 'order',
                'method' => 'set_billing_state',
            ],
            'shipping_address_1' => [
                'object' => 'order',
                'method' => 'set_shipping_address_1',
            ],
            'shipping_address_2' => [
                'object' => 'order',
                'method' => 'set_shipping_address_2',
            ],
            'shipping_city' => [
                'object' => 'order',
                'method' => 'set_shipping_city',
            ],
            'shipping_company' => [
                'object' => 'order',
                'method' => 'set_shipping_company',
            ],
            'shipping_country' => [
                'object' => 'order',
                'method' => 'set_shipping_country',
            ],
            'shipping_first_name' => [
                'object' => 'order',
                'method' => 'set_shipping_first_name',
            ],
            'shipping_last_name' => [
                'object' => 'order',
                'method' => 'set_shipping_last_name',
            ],
            'shipping_postcode' => [
                'object' => 'order',
                'method' => 'set_shipping_postcode',
            ],
            'shipping_state' => [
                'object' => 'order',
                'method' => 'set_shipping_state',
            ],
            'transaction_id' => [
                'object' => 'order',
                'method' => 'set_transaction_id',
            ],
        ];
    }

    private function set_value_with_operator()
    {
        $this->update_data['value'] = Order_Helper::apply_operator($this->current_field_value, $this->update_data);
    }
}
