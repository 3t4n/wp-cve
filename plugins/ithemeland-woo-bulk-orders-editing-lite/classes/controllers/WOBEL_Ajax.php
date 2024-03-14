<?php

namespace wobel\classes\controllers;

use wobel\classes\helpers\Render;
use wobel\classes\helpers\Sanitizer;
use wobel\classes\helpers\Filter_Helper;
use wobel\classes\helpers\Meta_Fields;
use wobel\classes\helpers\Order_Helper;
use wobel\classes\repositories\Column;
use wobel\classes\repositories\History;
use wobel\classes\repositories\Meta_Field;
use wobel\classes\repositories\Order;
use wobel\classes\repositories\Product;
use wobel\classes\repositories\Search;
use wobel\classes\repositories\Setting;
use wobel\classes\services\order\update\WOBEL_Order_Update;

class WOBEL_Ajax
{
    private static $instance;
    private $order_repository;
    private $history_repository;

    public static function register_callback()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->order_repository = Order::get_instance();
        $this->history_repository = History::get_instance();
        add_action('wp_ajax_wobel_add_meta_keys_by_order_id', [$this, 'add_meta_keys_by_order_id']);
        add_action('wp_ajax_wobel_column_manager_add_field', [$this, 'column_manager_add_field']);
        add_action('wp_ajax_wobel_column_manager_get_fields_for_edit', [$this, 'column_manager_get_fields_for_edit']);
        add_action('wp_ajax_wobel_orders_filter', [$this, 'orders_filter']);
        add_action('wp_ajax_wobel_save_filter_preset', [$this, 'save_filter_preset']);
        add_action('wp_ajax_wobel_order_edit', [$this, 'order_edit']);
        add_action('wp_ajax_wobel_create_new_order', [$this, 'create_new_order']);
        add_action('wp_ajax_wobel_delete_orders', [$this, 'delete_orders']);
        add_action('wp_ajax_wobel_untrash_orders', [$this, 'untrash_orders']);
        add_action('wp_ajax_wobel_empty_trash', [$this, 'empty_trash']);
        add_action('wp_ajax_wobel_duplicate_order', [$this, 'duplicate_order']);
        add_action('wp_ajax_wobel_load_filter_profile', [$this, 'load_filter_profile']);
        add_action('wp_ajax_wobel_delete_filter_profile', [$this, 'delete_filter_profile']);
        add_action('wp_ajax_wobel_save_column_profile', [$this, 'save_column_profile']);
        add_action('wp_ajax_wobel_get_text_editor_content', [$this, 'get_text_editor_content']);
        add_action('wp_ajax_wobel_change_count_per_page', [$this, 'change_count_per_page']);
        add_action('wp_ajax_wobel_filter_profile_change_use_always', [$this, 'filter_profile_change_use_always']);
        add_action('wp_ajax_wobel_get_default_filter_profile_orders', [$this, 'get_default_filter_profile_orders']);
        add_action('wp_ajax_wobel_get_taxonomy_parent_select_box', [$this, 'get_taxonomy_parent_select_box']);
        add_action('wp_ajax_wobel_sort_by_column', [$this, 'sort_by_column']);
        add_action('wp_ajax_wobel_get_order_details', [$this, 'get_order_details']);
        add_action('wp_ajax_wobel_get_customer_billing_address', [$this, 'get_customer_billing_address']);
        add_action('wp_ajax_wobel_get_customer_shipping_address', [$this, 'get_customer_shipping_address']);
        add_action('wp_ajax_wobel_get_products', [$this, 'get_products']);
        add_action('wp_ajax_wobel_get_taxonomies', [$this, 'get_taxonomies']);
        add_action('wp_ajax_wobel_get_tags', [$this, 'get_tags']);
        add_action('wp_ajax_wobel_get_categories', [$this, 'get_categories']);
        add_action('wp_ajax_wobel_get_order_notes', [$this, 'get_order_notes']);
        add_action('wp_ajax_wobel_add_order_note', [$this, 'add_order_note']);
        add_action('wp_ajax_wobel_delete_order_note', [$this, 'delete_order_note']);
        add_action('wp_ajax_wobel_get_order_address', [$this, 'get_order_address']);
        add_action('wp_ajax_wobel_get_order_items', [$this, 'get_order_items']);
        add_action('wp_ajax_wobel_clear_filter_data', [$this, 'clear_filter_data']);
        add_action('wp_ajax_wobel_history_change_page', [$this, 'history_change_page']);
    }

    public function get_default_filter_profile_orders()
    {
        $filter_data = Filter_Helper::get_active_filter_data();
        $result = $this->order_repository->get_orders_list($filter_data, 1);
        $this->make_response([
            'success' => true,
            'filter_data' => $filter_data,
            'orders_list' => $result->orders_list,
            'products' => $result->products,
            'pagination' => $result->pagination,
            'status_filters' => $result->status_filters,
            'orders_count' => $result->count,
        ]);
    }

    public function get_products()
    {
        if (!isset($_POST['search'])) {
            return false;
        }

        $list = [];
        $product_repository = new Product();
        $products = $product_repository->get_products([
            'posts_per_page' => '-1',
            'post_status' => 'publish',
            'post_type' => ['product', 'product_variation'],
            'wobel_general_column_filter' => [
                [
                    'field' => 'post_title',
                    'value' => strtolower(sanitize_text_field($_POST['search'])),
                    'operator' => 'like',
                    'type' => 'product',
                ],
            ],
        ]);

        if (!empty($products->posts)) {
            foreach ($products->posts as $product) {
                $list['results'][] = [
                    'id' => $product->post_parent . '__' . $product->ID,
                    'text' => $product->post_title,
                ];
            }
        }

        $this->make_response($list);
    }

    public function get_taxonomies()
    {
        $list = [];
        $product_repository = new Product();
        $taxonomies = $product_repository->get_taxonomies_by_name(sanitize_text_field($_POST['search']));
        if (!empty($taxonomies)) {
            foreach ($taxonomies as $key => $taxonomy_items) {
                if (!empty($taxonomy_items) && !in_array($key, ['product_visibility', 'product_type'])) {
                    foreach ($taxonomy_items as $taxonomy_item) {
                        if ($taxonomy_item instanceof \WP_Term) {
                            $list['results'][] = [
                                'id' => $taxonomy_item->taxonomy . '__' . $taxonomy_item->term_id,
                                'text' => $key . ': ' . $taxonomy_item->name,
                            ];
                        }
                    }
                }
            }
        }
        $this->make_response($list);
    }

    public function get_tags()
    {
        $list = [];
        $product_repository = new Product();
        $tags = $product_repository->get_tags_by_name(sanitize_text_field($_POST['search']));
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                if ($tag instanceof \WP_Term) {
                    $list['results'][] = [
                        'id' => $tag->term_id,
                        'text' => $tag->name,
                    ];
                }
            }
        }
        $this->make_response($list);
    }

    public function get_categories()
    {
        $list = [];
        $product_repository = new Product();
        $categories = $product_repository->get_categories_by_name(sanitize_text_field($_POST['search']));
        if (!empty($categories)) {
            foreach ($categories as $category) {
                if ($category instanceof \WP_Term) {
                    $list['results'][] = [
                        'id' => $category->term_id,
                        'text' => $category->name,
                    ];
                }
            }
        }
        $this->make_response($list);
    }

    public function orders_filter()
    {
        if (isset($_POST['filter_data'])) {
            $data = Sanitizer::array($_POST['filter_data']);
            $search_repository = new Search();
            $search_repository->update_current_data([
                'last_filter_data' => $data,
            ]);
            $current_page = !empty($_POST['current_page']) ? intval($_POST['current_page']) : 1;
            $filter_result = $this->order_repository->get_orders_list($data, $current_page);
            $this->make_response([
                'success' => true,
                'orders_list' => $filter_result->orders_list,
                'products' => $filter_result->products,
                'pagination' => $filter_result->pagination,
                'status_filters' => $filter_result->status_filters,
                'orders_count' => $filter_result->count,
            ]);
        }
        return false;
    }

    public function add_meta_keys_by_order_id()
    {
        if (isset($_POST)) {
            $order_id = intval($_POST['order_id']);
            $order = wc_get_order($order_id);
            if (!($order instanceof \WC_Order)) {
                die();
            }
            $meta_keys = Meta_Fields::remove_default_meta_keys(array_keys(get_post_meta($order_id)));
            $output = "";
            if (!empty($meta_keys)) {
                foreach ($meta_keys as $meta_key) {
                    $meta_field['key'] = $meta_key;
                    $meta_fields_main_types = Meta_Field::get_main_types();
                    $meta_fields_sub_types = Meta_Field::get_sub_types();
                    $output .= Render::html(WOBEL_VIEWS_DIR . "meta_field/meta_field_item.php", compact('meta_field', 'meta_fields_main_types', 'meta_fields_sub_types'));
                }
            }

            $this->make_response($output);
        }
        return false;
    }

    public function column_manager_add_field()
    {
        if (isset($_POST)) {
            if (isset($_POST['field_name']) && is_array($_POST['field_name']) && !empty($_POST['field_name'])) {
                $output = '';
                $field_action = sanitize_text_field($_POST['field_action']);
                for ($i = 0; $i < count($_POST['field_name']); $i++) {
                    $field_name = sanitize_text_field($_POST['field_name'][$i]);
                    $field_label = (!empty($_POST['field_label'][$i])) ? sanitize_text_field($_POST['field_label'][$i]) : $field_name;
                    $field_title = (!empty($_POST['field_label'][$i])) ? sanitize_text_field($_POST['field_label'][$i]) : $field_name;
                    $output .= Render::html(WOBEL_VIEWS_DIR . "column_manager/field_item.php", compact('field_name', 'field_label', 'field_action', 'field_title'));
                }
                $this->make_response($output);
            }
        }

        return false;
    }

    public function column_manager_get_fields_for_edit()
    {
        if (isset($_POST['preset_key'])) {
            $preset = (new Column())->get_preset(sanitize_text_field($_POST['preset_key']));
            if ($preset) {
                $output = '';
                $fields = [];
                if (isset($preset['fields'])) {
                    foreach ($preset['fields'] as $field) {
                        $field_info = [
                            'field_name' => $field['name'],
                            'field_label' => $field['label'],
                            'field_title' => $field['title'],
                            'field_background_color' => $field['background_color'],
                            'field_text_color' => $field['text_color'],
                            'field_action' => "edit",
                        ];
                        $fields[] = sanitize_text_field($field['name']);
                        $output .= Render::html(WOBEL_VIEWS_DIR . 'column_manager/field_item.php', $field_info);
                    }
                }

                $this->make_response([
                    'html' => $output,
                    'fields' => implode(',', $fields),
                ]);
            }
        }

        return false;
    }

    public function save_filter_preset()
    {
        if (!empty($_POST['preset_name'])) {
            $data = Sanitizer::array($_POST['filter_data']);
            $filter_item['name'] = sanitize_text_field($_POST['preset_name']);
            $filter_item['date_modified'] = date('Y-m-d H:i:s');
            $filter_item['key'] = 'preset-' . rand(1000000, 9999999);
            $filter_item['filter_data'] = $data;
            $save_result = (new Search())->update($filter_item);
            if (!$save_result) {
                return false;
            }
            $new_item = Render::html(WOBEL_VIEWS_DIR . 'modals/filter_profile_item.php', compact('filter_item'));
            $this->make_response([
                'success' => $save_result,
                'new_item' => $new_item,
            ]);
        }
        return false;
    }

    public function order_edit()
    {
        if (empty($_POST['order_data']) || !is_array($_POST['order_data'])) {
            return false;
        }

        if (!empty($_POST['order_ids'])) {
            $order_ids = array_map('intval', $_POST['order_ids']);
        } elseif (!empty($_POST['filter_data'])) {
            $args = Order_Helper::set_filter_data_items(Sanitizer::array($_POST['filter_data']), [
                'fields' => 'ids',
            ]);
            $order_ids = ($this->order_repository->get_orders($args))->posts;
        } else {
            return false;
        }

        $update_service = WOBEL_Order_Update::get_instance();
        $update_service->set_update_data([
            'order_ids' => $order_ids,
            'order_data' => Sanitizer::array($_POST['order_data']),
            'save_history' => true,
        ]);
        $update_result = $update_service->perform();

        $result = $this->order_repository->get_orders_rows($order_ids);
        $histories = $this->history_repository->get_histories();
        $history_count = $this->history_repository->get_history_count();
        $reverted = $this->history_repository->get_latest_reverted();
        $histories_rendered = Render::html(WOBEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
        $history_pagination = Render::html(WOBEL_VIEWS_DIR . 'history/history_pagination.php', compact('history_count'));

        $this->make_response([
            'success' => $update_result,
            'orders' => $result->order_rows,
            'order_statuses' => $result->order_statuses,
            'status_filters' => $result->status_filters,
            'history_items' => $histories_rendered,
            'history_pagination' => $history_pagination,
            'reverted' => !empty($reverted),
        ]);
    }

    public function create_new_order()
    {
        if (isset($_POST) && !empty($_POST['count'])) {
            $orders = [];
            for ($i = 1; $i <= intval($_POST['count']); $i++) {
                $orders[] = $this->order_repository->create();
            }
            $this->make_response([
                'success' => true,
                'order_ids' => $orders,
            ]);
        }
    }

    public function delete_orders()
    {
        if (!empty($_POST['delete_type'])) {
            $order_ids = (!empty($_POST['order_ids'])) ? array_map('intval', $_POST['order_ids']) : [];
            $trashed = [];
            switch ($_POST['delete_type']) {
                case 'trash':
                    if (!empty($order_ids)) {
                        foreach ($order_ids as $order_id) {
                            $order = $this->order_repository->get_order(intval($order_id));
                            if (!($order instanceof \WC_Order)) {
                                continue;
                            }

                            $trashed[] = [
                                'order_id' => intval($order_id),
                                'order_status' => $order->get_status(),
                            ];

                            wp_trash_post(intval($order_id));
                        }
                    }

                    break;
                case 'all':
                    if (isset($_POST['filter_data'])) {
                        $args = Order_Helper::set_filter_data_items(Sanitizer::array($_POST['filter_data']), [
                            'fields' => 'ids',
                        ]);
                        $order_ids = ($this->order_repository->get_orders($args))->posts;

                        if (!empty($order_ids)) {
                            foreach ($order_ids as $order_id) {
                                $trashed[] = intval($order_id);
                                wp_trash_post(intval($order_id));
                            }
                        }
                    }

                    break;
                case 'permanently':
                    if (!empty($order_ids)) {
                        foreach ($order_ids as $order_id) {
                            wp_delete_post(intval($order_id), true);
                        }
                    }

                    break;
            }

            if (!empty($trashed)) {
                $this->save_history_for_delete($trashed);
            }

            $histories = $this->history_repository->get_histories();
            $history_count = $this->history_repository->get_history_count();
            $reverted = $this->history_repository->get_latest_reverted();
            $histories_rendered = Render::html(WOBEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $history_pagination = Render::html(WOBEL_VIEWS_DIR . 'history/history_pagination.php', compact('history_count'));

            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'history_items' => $histories_rendered,
                'history_pagination' => $history_pagination,
                'reverted' => !empty($reverted),
                'edited_ids' => $order_ids,
            ]);
        }
        return false;
    }

    public function untrash_orders()
    {
        $trash = (!empty($_POST['order_ids'])) ? array_map('intval', $_POST['order_ids']) : $this->order_repository->get_trash();

        if (!empty($trash) && is_array($trash)) {
            foreach ($trash as $order_id) {
                wp_untrash_post(intval($order_id));
            }
        }

        $this->make_response([
            'success' => true,
            'message' => esc_html__('Success !', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
        ]);
    }

    public function empty_trash()
    {
        $trash = $this->order_repository->get_trash();
        if (!empty($trash)) {
            foreach ($trash as $order_id) {
                wp_delete_post(intval($order_id), true);
            }
            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            ]);
        }
        return false;
    }

    private function save_history_for_delete($orders)
    {
        if (empty($orders) || !is_array($orders)) {
            return false;
        }

        $create_history = $this->history_repository->create_history([
            'user_id' => intval(get_current_user_id()),
            'fields' => serialize(['order_delete']),
            'operation_type' => History::BULK_OPERATION,
            'operation_date' => date('Y-m-d H:i:s'),
        ]);

        if (!$create_history) {
            return false;
        }

        foreach ($orders as $order) {
            if (empty($order['order_id']) || empty($order['order_status'])) {
                continue;
            }

            $this->history_repository->save_history_item([
                'history_id' => intval($create_history),
                'historiable_id' => intval($order['order_id']),
                'name' => 'order_delete',
                'type' => 'order_action',
                'undo_operator' => 'untrash',
                'redo_operator' => 'trash',
                'prev_value' => sanitize_text_field($order['order_status']),
                'new_value' => 'trash',
            ]);
        }

        return true;
    }

    public function duplicate_order()
    {
        $message = esc_html__('Error !', 'ithemeland-woocommerce-bulk-orders-editing-lite');
        if (isset($_POST['order_ids']) && !empty($_POST['order_ids']) && !empty($_POST['duplicate_number'])) {
            $result = $this->order_repository->duplicate(array_map('intval', $_POST['order_ids']), intval($_POST['duplicate_number']));
            if ($result) {
                $message = esc_html__('Success !', 'ithemeland-woocommerce-bulk-orders-editing-lite');
            }
        }

        $this->make_response([
            'success' => $message,
        ]);
    }

    public function load_filter_profile()
    {
        if (isset($_POST['preset_key'])) {
            $search_repository = new Search();

            $preset = $search_repository->get_preset(sanitize_text_field($_POST['preset_key']));
            if (!isset($preset['filter_data'])) {
                return false;
            }
            $search_repository = new Search();
            $search_repository->update_current_data([
                'last_filter_data' => $preset['filter_data'],
            ]);
            $result = $this->order_repository->get_orders_list($preset['filter_data'], 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $preset['filter_data'],
                'orders_list' => $result->orders_list,
                'products' => $result->products,
                'pagination' => $result->pagination,
                'status_filters' => $result->status_filters,
                'orders_count' => $result->count,
            ]);
        }
        return false;
    }

    public function delete_filter_profile()
    {
        if (isset($_POST['preset_key'])) {
            $search_repository = new Search();
            $delete_result = $search_repository->delete(sanitize_text_field($_POST['preset_key']));
            if (!$delete_result) {
                return false;
            }

            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function save_column_profile()
    {
        if (isset($_POST['preset_key']) && isset($_POST['type'])) {
            $column_repository = new Column();
            $fields = $column_repository->get_fields();
            $preset['date_modified'] = date('Y-m-d H:i:s', time());

            switch ($_POST['type']) {
                case 'save_as_new':
                    $preset['name'] = "Preset " . rand(100, 999);
                    $preset['key'] = 'preset-' . rand(1000000, 9999999);
                    break;
                case 'update_changes':
                    $preset_item = $column_repository->get_preset(sanitize_text_field($_POST['preset_key']));
                    if (!$preset_item) {
                        return false;
                    }
                    $preset['name'] = sanitize_text_field($preset_item['name']);
                    $preset['key'] = sanitize_text_field($preset_item['key']);
                    break;
            }

            $preset['fields'] = [];
            foreach ($_POST['items'] as $item) {
                $item = sanitize_text_field($item);
                if (isset($fields[$item])) {
                    $preset['fields'][$item] = [
                        'name' => $item,
                        'label' => $fields[$item]['label'],
                        'title' => $fields[$item]['label'],
                        'editable' => $fields[$item]['editable'],
                        'content_type' => $fields[$item]['content_type'],
                        'allowed_type' => $fields[$item]['allowed_type'],
                        'update_type' => $fields[$item]['update_type'],
                        'background_color' => '#fff',
                        'text_color' => '#444',
                    ];
                    if (isset($fields[$item]['sortable'])) {
                        $preset["fields"][$item]['sortable'] = $fields[$item]['sortable'];
                    }
                    if (isset($fields[$item]['options'])) {
                        $preset["fields"][$item]['options'] = $fields[$item]['options'];
                    }
                    if (isset($fields[$item]['field_type'])) {
                        $preset["fields"][$item]['field_type'] = $fields[$item]['field_type'];
                    }
                    $preset['checked'][] = $item;
                }
            }

            $column_repository->update($preset);
            $column_repository->set_active_columns($preset['key'], $preset['fields']);
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function get_text_editor_content()
    {
        if (isset($_POST['order_id']) && isset($_POST['field'])) {
            $field = sanitize_text_field($_POST['field']);
            $field_type = sanitize_text_field($_POST['field_type']);

            $order_object = $this->order_repository->get_order(intval($_POST['order_id']));
            if (!($order_object instanceof \WC_Order)) {
                return false;
            }
            $order = $this->order_repository->order_to_array($order_object);
            switch ($field_type) {
                case 'meta_field':
                case 'custom_field':
                    $value = (isset($order[$field_type][$field])) ? $order[$field_type][$field][0] : '';
                    break;
                default:
                    $value = $order[$field];
                    break;
            }

            $this->make_response([
                'success' => true,
                'content' => $value,
            ]);
        }
        return false;
    }

    public function change_count_per_page()
    {
        if (isset($_POST['count_per_page'])) {
            $setting_repository = new Setting();
            $setting_repository->update_current_settings([
                'count_per_page' => intval($_POST['count_per_page']),
            ]);
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function filter_profile_change_use_always()
    {
        if (isset($_POST['preset_key'])) {
            (new Search())->update_use_always(sanitize_text_field($_POST['preset_key']));
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function get_taxonomy_parent_select_box()
    {
        if (isset($_POST['taxonomy']) && $_POST['taxonomy'] != 'order_tag') {
            $taxonomies = get_terms(['taxonomy' => sanitize_text_field($_POST['taxonomy']), 'hide_empty' => false]);
            $options = '<option value="-1">None</option>';
            if (!empty($taxonomies)) {
                foreach ($taxonomies as $taxonomy) {
                    $term_id = intval($taxonomy->term_id);
                    $taxonomy_name = sanitize_text_field($taxonomy->name);
                    $options .= "<option value='{$term_id}'>{$taxonomy_name}</option>";
                }
            }
            $this->make_response([
                'success' => true,
                'options' => $options,
            ]);
        }
        return false;
    }

    public function sort_by_column()
    {
        if (!empty($_POST['column_name']) && !empty($_POST['sort_type']) && !empty($_POST['filter_data'])) {
            $setting_repository = new Setting();
            $setting_repository->update_current_settings([
                'sort_by' => sanitize_text_field($_POST['column_name']),
                'sort_type' => sanitize_text_field(strtoupper($_POST['sort_type'])),
            ]);
            $filter_data = Sanitizer::array($_POST['filter_data']);
            $result = $this->order_repository->get_orders_list($filter_data, 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $filter_data,
                'orders_list' => $result->orders_list,
                'products' => $result->products,
                'pagination' => $result->pagination,
                'status_filters' => $result->status_filters,
                'orders_count' => $result->count,
            ]);
        }
        return false;
    }

    public function get_order_details()
    {
        if (empty($_POST['order_id'])) {
            return false;
        }

        $order = $this->order_repository->get_order(intval(sanitize_text_field($_POST['order_id'])));
        if (!($order instanceof \WC_Order)) {
            return false;
        }

        $order_fields = $this->order_repository->order_to_array($order);
        $this->make_response([
            'success' => true,
            'order' => $order_fields,
        ]);
    }

    public function get_customer_billing_address()
    {
        if (empty($_POST['customer_id'])) {
            return false;
        }

        $customer_meta = get_user_meta(intval(sanitize_text_field($_POST['customer_id'])));
        if (!isset($customer_meta['billing_first_name'][0])) {
            return false;
        }

        $customer_billing_address = [
            'billing_first_name' => sanitize_text_field($customer_meta['billing_first_name'][0]),
            'billing_last_name' => sanitize_text_field($customer_meta['billing_last_name'][0]),
            'billing_address_1' => sanitize_text_field($customer_meta['billing_address_1'][0]),
            'billing_address_2' => sanitize_text_field($customer_meta['billing_address_2'][0]),
            'billing_city' => sanitize_text_field($customer_meta['billing_city'][0]),
            'billing_phone' => sanitize_text_field($customer_meta['billing_phone'][0]),
            'billing_email' => sanitize_text_field($customer_meta['billing_email'][0]),
            'billing_postcode' => sanitize_text_field($customer_meta['billing_postcode'][0]),
            'billing_company' => sanitize_text_field($customer_meta['billing_company'][0]),
            'billing_country' => sanitize_text_field($customer_meta['billing_country'][0]),
            'billing_country_name' => $this->order_repository->get_country_name($customer_meta['billing_country'][0]),
            'billing_state' => sanitize_text_field($customer_meta['billing_state'][0]),
        ];

        $this->make_response([
            'success' => true,
            'billing_address' => $customer_billing_address,
        ]);
    }

    public function get_customer_shipping_address()
    {
        if (empty($_POST['customer_id'])) {
            return false;
        }

        $customer_meta = get_user_meta(intval(sanitize_text_field($_POST['customer_id'])));
        if (!isset($customer_meta['shipping_first_name'][0])) {
            return false;
        }

        $customer_shipping_address = [
            'shipping_first_name' => sanitize_text_field($customer_meta['shipping_first_name'][0]),
            'shipping_last_name' => sanitize_text_field($customer_meta['shipping_last_name'][0]),
            'shipping_address_1' => sanitize_text_field($customer_meta['shipping_address_1'][0]),
            'shipping_address_2' => sanitize_text_field($customer_meta['shipping_address_2'][0]),
            'shipping_city' => sanitize_text_field($customer_meta['shipping_city'][0]),
            'shipping_postcode' => sanitize_text_field($customer_meta['shipping_postcode'][0]),
            'shipping_company' => sanitize_text_field($customer_meta['shipping_company'][0]),
            'shipping_country' => sanitize_text_field($customer_meta['shipping_country'][0]),
            'shipping_state' => sanitize_text_field($customer_meta['shipping_state'][0]),
        ];

        $this->make_response([
            'success' => true,
            'shipping_address' => $customer_shipping_address,
        ]);
    }

    public function get_order_notes()
    {
        if (empty($_POST['order_id'])) {
            return false;
        }

        $order_notes = wc_get_order_notes([
            'order_id' => intval(sanitize_text_field($_POST['order_id'])),
        ]);
        if (empty($order_notes)) {
            return false;
        }

        $output = Render::html(WOBEL_VIEWS_DIR . 'bulk_edit/columns_modals/order_notes_items.php', compact('order_notes'));
        $this->make_response([
            'success' => true,
            'order_notes' => $output,
        ]);
    }

    public function add_order_note()
    {
        if (empty($_POST['order_id']) || empty($_POST['order_data'])) {
            return false;
        }

        $update_service = WOBEL_Order_Update::get_instance();
        $update_service->set_update_data([
            'order_ids' => [intval($_POST['order_id'])],
            'order_data' => Sanitizer::array($_POST['order_data']),
            'save_history' => true,
        ]);
        $update_service->perform();

        $this->get_order_notes();
    }

    public function delete_order_note()
    {
        if (empty($_POST['note_id'])) {
            return false;
        }

        $result = wp_delete_comment(intval($_POST['note_id']));

        if (!$result) {
            return false;
        }

        $this->make_response([
            'success' => true,
        ]);
    }

    public function get_order_address()
    {
        if (!isset($_POST['order_id']) || !isset($_POST['field'])) {
            return false;
        }

        $order = $this->order_repository->get_order(intval(sanitize_text_field($_POST['order_id'])));
        if (!($order instanceof \WC_Order)) {
            return false;
        }

        switch ($_POST['field']) {
            case 'billing_address_1':
                $address = $order->get_billing_address_1();
                break;
            case 'billing_address_2':
                $address = $order->get_billing_address_2();
                break;
            case 'billing_address_index':
                $address = $order->get_formatted_billing_address();
                break;
            case 'shipping_address_1':
                $address = $order->get_shipping_address_1();
                break;
            case 'shipping_address_2':
                $address = $order->get_shipping_address_2();
                break;
            case 'shipping_address_index':
                $address = $order->get_formatted_shipping_address();
                break;
            default:
                $address = "";
        }

        $this->make_response([
            'success' => true,
            'address' => $address,
        ]);
    }

    public function get_order_items()
    {
        if (!isset($_POST['order_id'])) {
            return false;
        }

        $order = $this->order_repository->get_order(intval(sanitize_text_field($_POST['order_id'])));
        if (!($order instanceof \WC_Order)) {
            return false;
        }
        $order_array = $this->order_repository->order_to_array($order);
        $order_items = (isset($order_array['order_items_array'])) ? $order_array['order_items_array'] : [];
        $this->make_response([
            'success' => true,
            'order_items' => $order_items,
        ]);
    }

    public function clear_filter_data()
    {
        $search_repository = new Search();
        $search_repository->delete_current_data();
        $this->make_response([
            'success' => true,
        ]);
    }

    public function history_change_page()
    {
        if (empty($_POST['page'])) {
            return false;
        }

        $where = [];

        if (isset($_POST['filters'])) {
            if (isset($_POST['filters']['operation']) && !empty($_POST['filters']['operation'])) {
                $where['operation_type'] = sanitize_text_field($_POST['filters']['operation']);
            }
            if (isset($_POST['filters']['author']) && !empty($_POST['filters']['author'])) {
                $where['user_id'] = sanitize_text_field($_POST['filters']['author']);
            }
            if (isset($_POST['filters']['fields']) && !empty($_POST['filters']['fields'])) {
                $where['fields'] = sanitize_text_field($_POST['filters']['fields']);
            }
            if (isset($_POST['filters']['date'])) {
                $where['operation_date'] = sanitize_text_field($_POST['filters']['date']);
            }
        }
        $per_page = 10;
        $history_count = $this->history_repository->get_history_count($where);
        $current_page = intval($_POST['page']);
        $offset = intval($current_page - 1) * $per_page;
        $histories = $this->history_repository->get_histories($where, $per_page, $offset);
        $histories_rendered = Render::html(WOBEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
        $history_pagination = Render::html(WOBEL_VIEWS_DIR . 'history/history_pagination.php', compact('history_count', 'per_page', 'current_page'));

        $this->make_response([
            'success' => true,
            'history_items' => $histories_rendered,
            'history_pagination' => $history_pagination,
        ]);
    }

    private function make_response($data)
    {
        echo (is_array($data)) ? json_encode($data) : $data;
        die();
    }
}
