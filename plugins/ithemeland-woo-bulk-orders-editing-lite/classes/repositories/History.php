<?php

namespace wobel\classes\repositories;

use wobel\classes\services\order\update\WOBEL_Order_Update;

class History
{
    const BULK_OPERATION = 'bulk';
    const INLINE_OPERATION = 'inline';

    private static $instance;

    private $wpdb;
    private $sub_system;
    private $history_table;
    private $history_items_table;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->history_table = $this->wpdb->prefix . 'itbbc_history';
        $this->history_items_table = $this->wpdb->prefix . 'itbbc_history_items';
        $this->sub_system = "woocommerce_orders";
    }

    public static function get_operation_types()
    {
        return [
            self::BULK_OPERATION => __('Bulk Operation', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            self::INLINE_OPERATION => __('Inline Operation', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ];
    }

    public static function get_operation_type($operation_type)
    {
        $operation_types = self::get_operation_types();
        return (isset($operation_types[$operation_type])) ? $operation_types[$operation_type] : "";
    }

    public function create_history($data)
    {
        $data['sub_system'] = $this->sub_system;
        $format = ['%d', '%s', '%s', '%s', '%s'];
        $this->wpdb->insert($this->history_table, $data, $format);
        return $this->wpdb->insert_id;
    }

    public function create_history_item($data)
    {
        $format = ['%d', '%d', '%s', '%s', '%s'];
        $this->wpdb->insert($this->history_items_table, $data, $format);
        return $this->wpdb->insert_id;
    }

    public function save_history_item($data)
    {
        if (empty($data['history_id']) || !isset($data['historiable_id']) || empty($data['name']) || empty($data['type'])) {
            return false;
        }

        return $this->create_history_item([
            'history_id' => intval($data['history_id']),
            'historiable_id' => intval($data['historiable_id']),
            'field' => serialize([
                'name' => sanitize_text_field($data['name']),
                'sub_name' => (!empty($data['sub_name'])) ? sanitize_text_field($data['sub_name']) : '',
                'type' => sanitize_text_field($data['type']),
                'action' => (!empty($data['action'])) ? sanitize_text_field($data['action']) : '',
                'undo_operator' => (!empty($data['undo_operator'])) ? sanitize_text_field($data['undo_operator']) : '',
                'redo_operator' => (!empty($data['redo_operator'])) ? sanitize_text_field($data['redo_operator']) : '',
                'extra_fields' => (!empty($data['extra_fields'])) ? esc_sql($data['extra_fields']) : [],
                'deleted_ids' => (!empty($data['deleted_ids'])) ? esc_sql($data['deleted_ids']) : [],
                'created_ids' => (!empty($data['created_ids'])) ? esc_sql($data['created_ids']) : [],
            ]),
            'prev_value' => (!empty($data['prev_value'])) ? serialize($data['prev_value']) : '',
            'new_value' => (!empty($data['new_value'])) ? serialize($data['new_value']) : '',
        ]);
    }

    public function get_histories($where = [], $limit = 10, $offset = 0)
    {
        $where_items = "history.reverted = 0 AND history.sub_system = '{$this->sub_system}' ";
        if (!empty($where)) {
            foreach ($where as $field => $value) {
                $field = esc_sql($field);
                $value = esc_sql($value);
                switch ($field) {
                    case 'operation_type':
                        $where_items .= " AND history.{$field} = '{$value}'";
                        break;
                    case 'user_id':
                        $where_items .= " AND history.{$field} = {$value}";
                        break;
                    case 'fields':
                        $fields = explode(',', $value);
                        if (!empty($fields) && is_array($fields)) {
                            foreach ($fields as $field_item) {
                                $where_items .= " AND history.{$field} LIKE '%{$field_item}%'";
                            }
                        }
                        break;
                    case 'operation_date':
                        $from = (!empty($value['from'])) ? date('Y-m-d H:i:s', strtotime($value['from'])) : null;
                        $to = (!empty($value['to'])) ? date('Y-m-d H:i:s', (strtotime($value['to']) + 86400)) : null;
                        if (!empty($from) || !empty($to)) {
                            if (!empty($from) & !empty($to)) {
                                $where_items .= " AND (history.{$field} BETWEEN '{$from}' AND '{$to}')";
                            } else if (!empty($from)) {
                                $where_items .= " AND history.{$field} >= '{$from}'";
                            } else {
                                $where_items .= " AND history.{$field} < '{$to}'";
                            }
                        }
                        break;
                }
            }
        }

        if (!current_user_can('administrator')) {
            $user_id = get_current_user_id();
            $where_items .= " AND history.user_id = {$user_id}";
        }

        $limit = intval(sanitize_text_field($limit));
        $offset = intval(sanitize_text_field($offset));

        $limit_offset = (!empty($offset)) ? "LIMIT {$limit}, {$offset}" : "LIMIT {$limit}";
        return $this->wpdb->get_results("SELECT * FROM {$this->history_table} history WHERE {$where_items} ORDER BY history.id DESC {$limit_offset}");
    }

    public function get_history_count($where = [])
    {
        $where_items = "history.reverted = 0 AND history.sub_system = '{$this->sub_system}' ";
        if (!empty($where)) {
            foreach ($where as $field => $value) {
                $field = esc_sql($field);
                $value = esc_sql($value);
                switch ($field) {
                    case 'operation_type':
                        $where_items .= " AND history.{$field} = '{$value}'";
                        break;
                    case 'user_id':
                        $where_items .= " AND history.{$field} = {$value}";
                        break;
                    case 'fields':
                        $fields = explode(',', $value);
                        if (!empty($fields) && is_array($fields)) {
                            foreach ($fields as $field_item) {
                                $where_items .= " AND history.{$field} LIKE '%{$field_item}%'";
                            }
                        }
                        break;
                    case 'operation_date':
                        $from = (!empty($value['from'])) ? date('Y-m-d H:i:s', strtotime($value['from'])) : null;
                        $to = (!empty($value['to'])) ? date('Y-m-d H:i:s', (strtotime($value['to']) + 86400)) : null;
                        if (!empty($from) || !empty($to)) {
                            if (!empty($from) & !empty($to)) {
                                $where_items .= " AND (history.{$field} BETWEEN '{$from}' AND '{$to}')";
                            } else if (!empty($from)) {
                                $where_items .= " AND history.{$field} >= '{$from}'";
                            } else {
                                $where_items .= " AND history.{$field} < '{$to}'";
                            }
                        }
                        break;
                }
            }
        }

        if (!current_user_can('administrator')) {
            $user_id = get_current_user_id();
            $where_items .= " AND history.user_id = {$user_id}";
        }

        $result = $this->wpdb->get_results("SELECT COUNT(id) as 'count' FROM {$this->history_table} history WHERE {$where_items} ORDER BY history.id DESC");
        return (!empty($result[0]) && !empty($result[0]->count)) ? $result[0]->count : 0;
    }

    public function get_history_items($history_id)
    {
        return $this->wpdb->get_results($this->wpdb->prepare("SELECT history_items.*, posts.post_title FROM {$this->history_items_table} history_items INNER JOIN {$this->wpdb->prefix}posts posts ON (history_items.historiable_id = posts.ID) WHERE history_id = %d", intval($history_id)));
    }

    public function get_history_rows($history_id)
    {
        return $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM {$this->history_items_table} WHERE history_id = %d", intval($history_id)));
    }

    public function get_latest_history()
    {
        return $this->wpdb->get_results("SELECT * FROM {$this->history_table} WHERE reverted = 0 AND sub_system = '{$this->sub_system}' ORDER BY id DESC LIMIT 1");
    }

    public function get_latest_reverted()
    {
        return $this->wpdb->get_results("SELECT * FROM {$this->history_table} WHERE reverted = 1 AND sub_system = '{$this->sub_system}' ORDER BY id DESC LIMIT 1");
    }

    public function delete_history($history_id)
    {
        $result = $this->wpdb->delete($this->history_table, [
            'id' => intval($history_id),
        ], [
            '%d',
        ]);

        return !empty($result);
    }

    public function get_history($history_id)
    {
        return $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM {$this->history_table} WHERE id = %d", intval($history_id)));
    }

    public function update_history($history_id, $where)
    {
        $result = $this->wpdb->update($this->history_table, $where, [
            'id' => intval($history_id),
        ]);

        return !empty($result);
    }

    public function clear_all()
    {
        $this->wpdb->query("DELETE FROM {$this->history_table} WHERE sub_system = '{$this->sub_system}'");
    }

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function revert($history_id)
    {
        $history_items = $this->get_history_rows(intval(esc_sql($history_id)));
        if (empty($history_items) || !is_array($history_items)) {
            return false;
        }

        $update_service = WOBEL_Order_Update::get_instance();

        foreach ($history_items as $item) {
            $order_ids = [intval($item->historiable_id)];
            $field = unserialize($item->field);
            $order_data = [
                [
                    'name' => $field['name'],
                    'sub_name' => (!empty($field['sub_name'])) ? $field['sub_name'] : '',
                    'type' => $field['type'],
                    'deleted_ids' => !empty($field['deleted_ids']) ? $field['deleted_ids'] : [],
                    'operator' => (!empty($field['undo_operator'])) ? $field['undo_operator'] : '',
                    'value' => unserialize($item->prev_value),
                    'operation' => 'inline_edit',
                ]
            ];

            $update_service->set_update_data([
                'order_ids' => $order_ids,
                'save_history' => false,
                'order_data' => $order_data
            ]);

            $update_result = $update_service->perform();
            if (!$update_result) {
                return false;
            }
        }

        return $this->update_history($history_id, ['reverted' => 1]);
    }

    public function reset($history_id)
    {
        $history_items = $this->get_history_rows(intval(esc_sql($history_id)));
        if (empty($history_items) || !is_array($history_items)) {
            return false;
        }

        $update_service = WOBEL_Order_Update::get_instance();
        foreach ($history_items as $item) {
            $order_ids = [intval($item->historiable_id)];
            $field = unserialize($item->field);
            $order_data = [
                [
                    'name' => $field['name'],
                    'sub_name' => (!empty($field['sub_name'])) ? $field['sub_name'] : '',
                    'type' => $field['type'],
                    'deleted_ids' => !empty($field['deleted_ids']) ? $field['deleted_ids'] : [],
                    'operator' => (!empty($field['redo_operator'])) ? $field['redo_operator'] : '',
                    'value' => unserialize($item->new_value),
                    'operation' => 'inline_edit',
                ]
            ];

            $update_service->set_update_data([
                'order_ids' => $order_ids,
                'save_history' => false,
                'order_data' => $order_data
            ]);
            $update_result = $update_service->perform();
            if (!$update_result) {
                return false;
            }
        }

        return $this->update_history($history_id, ['reverted' => 0]);
    }
}
