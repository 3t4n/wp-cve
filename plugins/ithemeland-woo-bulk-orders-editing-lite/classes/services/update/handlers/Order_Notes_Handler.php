<?php

namespace wobef\classes\services\update\handlers;

use wobef\classes\repositories\History;
use wobef\classes\repositories\Order;
use wobef\classes\services\update\Handler_Interface;

class Order_Notes_Handler implements Handler_Interface
{
    private static $instance;

    private $order_repository;
    private $history_repository;
    private $order_ids;
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
        $this->order_repository = new Order();
        $this->history_repository = new History();
    }

    public function update($order_ids, $update_data)
    {
        // check update data
        if (empty($update_data['value']) || empty($update_data['operator'])) {
            return false;
        }

        $this->order_ids = $order_ids;
        $this->update_data = $update_data;

        $update_methods = $this->get_update_methods();
        $method = (!empty($update_methods[$this->update_data['operator']])) ? $update_methods[$this->update_data['operator']] : '';
        if (!method_exists($this, $method)) {
            return false;
        }

        return $this->{$method}();
    }

    private function get_update_methods()
    {
        return [
            'private' => 'add_order_note',
            'customer' => 'add_order_note',
            'trash' => 'remove_order_note',
            'untrash' => 'restore_order_note',
        ];
    }

    private function remove_order_note()
    {
        return wp_trash_comment(intval($this->update_data['value']));
    }

    private function restore_order_note()
    {
        return wp_untrash_comment(intval($this->update_data['value']));
    }

    private function add_order_note()
    {
        foreach ($this->order_ids as $order_id) {
            $order = $this->order_repository->get_order(intval($order_id));
            if (!($order instanceof \WC_Order)) {
                return false;
            }

            $order_note_id = $order->add_order_note(sanitize_text_field($this->update_data['value']), $this->update_data['operator'] == 'customer' ? 1 : 0, true);
            $order->save();

            $result = $this->history_repository->save_history_item([
                'history_id' => $this->update_data['history_id'],
                'historiable_id' => intval($order_id),
                'name' => 'order_notes',
                'undo_operator' => 'trash',
                'redo_operator' => 'untrash',
                'type' => 'order_notes',
                'prev_value' => intval($order_note_id),
                'new_value' => intval($order_note_id)
            ]);

            if (!$result) {
                return false;
            }
        }

        return true;
    }
}
