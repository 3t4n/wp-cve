<?php

namespace wobef\classes\services\update;

use wobef\classes\repositories\History;
use wobef\classes\services\update\handlers\Meta_Field_Handler;
use wobef\classes\services\update\handlers\Order_Action_Handler;
use wobef\classes\services\update\handlers\Order_Notes_Handler;
use wobef\classes\services\update\handlers\Taxonomy_Handler;
use wobef\classes\services\update\handlers\Woocommerce_Handler;

class WOBEF_Order_Update
{
    private static $instance;
    private $order_ids;
    private $order_data;
    private $update_classes;
    private $save_history;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->update_classes = $this->get_update_classes();
    }

    public function set_update_data($data)
    {
        if (!isset($data['order_ids']) || empty($data['order_data']) || !is_array($data['order_data'])) {
            return false;
        }

        $this->order_ids = array_unique($data['order_ids']);
        $this->order_data = $data['order_data'];
        $this->save_history = (!empty($data['save_history']));
    }

    public function perform()
    {
        // save history
        if ($this->save_history) {
            $history_id = $this->save_history();
            if (empty($history_id)) {
                return false;
            }
        }

        foreach ($this->order_data as $update_item) {


            if (!empty($history_id)) {
                // set history id for save history item
                $update_item['history_id'] = intval($history_id);
            }

            // check items
            if (!$this->is_valid_update_item($update_item)) {
                return false;
            }

            $class = $this->update_classes[$update_item['type']];
            $instance = $class::get_instance();
            $update_result = $instance->update($this->order_ids, $update_item);
            if (!$update_result) {
                return false;
            }
        }

        return true;
    }

    private function is_valid_update_item($update_item)
    {
        // has require item ?
        if (
            empty($update_item['name'])
            || empty($update_item['type'])
            || (empty($update_item['value']) && (!empty($update_item['operator'] && !is_array($update_item['operator'], ['text_replace'])) && $update_item['operation'] != 'inline_edit'))
        ) {
            return false;
        }

        // has update method ?
        if (!isset($this->update_classes[$update_item['type']]) || !class_exists($this->update_classes[$update_item['type']])) {
            return false;
        }

        return true;
    }

    private function get_update_classes()
    {
        return [
            'woocommerce_field' => Woocommerce_Handler::class,
            'meta_field' => Meta_Field_Handler::class,
            'order_notes' => Order_Notes_Handler::class,
            'order_action' => Order_Action_Handler::class,
        ];
    }

    private function save_history()
    {
        $history_repository = new History();
        $fields = array_column($this->order_data, 'name');
        $history_id = $history_repository->create_history([
            'user_id' => intval(get_current_user_id()),
            'fields' => serialize($fields),
            'operation_type' => (count($this->order_data) > 1) ? History::BULK_OPERATION : History::INLINE_OPERATION,
            'operation_date' => date('Y-m-d H:i:s'),
        ]);

        return $history_id;
    }
}
