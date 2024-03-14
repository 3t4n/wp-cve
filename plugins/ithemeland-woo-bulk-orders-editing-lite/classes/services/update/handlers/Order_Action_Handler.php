<?php

namespace wobef\classes\services\update\handlers;

use wobef\classes\repositories\Order;
use wobef\classes\services\update\Handler_Interface;

class Order_Action_Handler implements Handler_Interface
{
    private static $instance;

    private $order_id;
    private $update_data;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function update($order_ids, $update_data)
    {
        if (empty($update_data['value']) || empty($update_data['operator'])) {
            return false;
        }

        $methods = $this->get_methods();
        $method = (!empty($methods[$update_data['operator']])) ? $methods[$update_data['operator']] : '';
        if (empty($method) || !method_exists($this, $method)) {
            return false;
        }

        $this->update_data = $update_data;

        foreach ($order_ids as $order_id) {
            $this->order_id = intval($order_id);
            $this->{$method}();
        }

        return true;
    }

    private function get_methods()
    {
        return [
            'trash' => 'delete_order',
            'untrash' => 'restore_order'
        ];
    }

    private function delete_order()
    {
        return wp_trash_post($this->order_id);
    }

    private function restore_order()
    {
        $order_repository = new Order();
        $order = $order_repository->get_order(intval($this->order_id));
        if (!($order instanceof \WC_Order)) {
            return false;
        }
        $order->set_status(sanitize_text_field($this->update_data['value']));
        return $order->save();
    }
}
