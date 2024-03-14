<?php

namespace wobef\classes\services\update\handlers;

use wobef\classes\helpers\Order_Helper;
use wobef\classes\repositories\History;
use wobef\classes\repositories\Order;
use wobef\classes\services\update\Handler_Interface;

class Meta_Field_Handler implements Handler_Interface
{
    private static $instance;

    private $order_ids;
    private $order_repository;
    private $order;
    private $setter_method;
    private $update_data;
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
    }

    public function update($order_ids, $update_data)
    {
        $this->setter_method = $this->get_setter($update_data['name']);
        if (empty($this->setter_method) && empty($order_ids) && !is_array($order_ids)) {
            return false;
        }

        // has update method ?
        if (!method_exists($this, $this->setter_method)) {
            return false;
        };

        $this->update_data = $update_data;
        $this->order_ids = $order_ids;

        foreach ($this->order_ids as $order_id) {
            if (!isset($this->update_data['value'])) {
                $this->update_data['value'] = '';
            }

            $this->order_repository = new Order();
            $this->order = $this->order_repository->get_order(intval($order_id));
            if (!($this->order instanceof \WC_Order)) {
                return false;
            }

            $this->current_field_value = (!empty($this->update_data['name'])) ? get_post_meta($this->order->get_id(), $this->update_data['name'], true) : '';

            // run update method
            $this->{$this->setter_method}();

            // save history item
            if (!empty($this->update_data['history_id'])) {
                $history_repository = new History();
                $history_item_result = $history_repository->save_history_item([
                    'history_id' => $this->update_data['history_id'],
                    'historiable_id' => $this->order->get_id(),
                    'name' => $this->update_data['name'],
                    'sub_name' => (!empty($this->update_data['sub_name'])) ? $this->update_data['sub_name'] : '',
                    'type' => $this->update_data['type'],
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
        $setter_methods = $this->get_setter_methods();
        return (!empty($setter_methods[$field_name])) ? $setter_methods[$field_name] : $setter_methods['default_meta_field'];
    }

    private function get_setter_methods()
    {
        return [
            'default_meta_field' => 'set_default_meta_field',
        ];
    }

    private function set_default_meta_field()
    {
        // set value with operator
        if (!empty($this->update_data['operator'])) {
            $this->update_data['value'] = Order_Helper::apply_operator($this->current_field_value, $this->update_data);
        }

        return update_post_meta($this->order->get_id(), esc_sql($this->update_data['name']), esc_sql($this->update_data['value']));
    }
}
