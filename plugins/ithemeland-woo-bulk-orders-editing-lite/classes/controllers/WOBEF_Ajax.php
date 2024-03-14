<?php

namespace wobef\classes\controllers;

use wobef\classes\helpers\Meta_Fields;
use wobef\classes\helpers\Render;
use wobef\classes\helpers\Sanitizer;
use wobef\classes\helpers\Filter_Helper;
use wobef\classes\helpers\Order_Helper;
use wobef\classes\repositories\Column;
use wobef\classes\repositories\History;
use wobef\classes\repositories\Meta_Field;
use wobef\classes\repositories\Order;
use wobef\classes\repositories\Product;
use wobef\classes\repositories\Search;
use wobef\classes\repositories\Setting;
use wobef\classes\services\update\WOBEF_Order_Update;

class WOBEF_Ajax
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
        $this->order_repository = new Order();
        $this->history_repository = new History();

        add_action('wp_ajax_wobef_order_edit', [$this, 'order_edit']);
        add_action('wp_ajax_wobef_add_meta_keys_by_order_id', [$this, 'add_meta_keys_by_order_id']);
        add_action('wp_ajax_wobef_column_manager_add_field', [$this, 'column_manager_add_field']);
        add_action('wp_ajax_wobef_column_manager_get_fields_for_edit', [$this, 'column_manager_get_fields_for_edit']);
        add_action('wp_ajax_wobef_orders_filter', [$this, 'orders_filter']);
        add_action('wp_ajax_wobef_save_filter_preset', [$this, 'save_filter_preset']);
        add_action('wp_ajax_wobef_create_new_order', [$this, 'create_new_order']);
        add_action('wp_ajax_wobef_delete_orders', [$this, 'delete_orders']);
        add_action('wp_ajax_wobef_duplicate_order', [$this, 'duplicate_order']);
        add_action('wp_ajax_wobef_load_filter_profile', [$this, 'load_filter_profile']);
        add_action('wp_ajax_wobef_delete_filter_profile', [$this, 'delete_filter_profile']);
        add_action('wp_ajax_wobef_save_column_profile', [$this, 'save_column_profile']);
        add_action('wp_ajax_wobef_get_text_editor_content', [$this, 'get_text_editor_content']);
        add_action('wp_ajax_wobef_change_count_per_page', [$this, 'change_count_per_page']);
        add_action('wp_ajax_wobef_filter_profile_change_use_always', [$this, 'filter_profile_change_use_always']);
        add_action('wp_ajax_wobef_get_default_filter_profile_orders', [$this, 'get_default_filter_profile_orders']);
        add_action('wp_ajax_wobef_sort_by_column', [$this, 'sort_by_column']);
        add_action('wp_ajax_wobef_get_order_details', [$this, 'get_order_details']);
        add_action('wp_ajax_wobef_order_billing_update', [$this, 'order_billing_update']);
        add_action('wp_ajax_wobef_order_shipping_update', [$this, 'order_shipping_update']);
        add_action('wp_ajax_wobef_get_customer_billing_address', [$this, 'get_customer_billing_address']);
        add_action('wp_ajax_wobef_get_customer_shipping_address', [$this, 'get_customer_shipping_address']);
        add_action('wp_ajax_wobef_get_products', [$this, 'get_products']);
        add_action('wp_ajax_wobef_get_taxonomies', [$this, 'get_taxonomies']);
        add_action('wp_ajax_wobef_get_tags', [$this, 'get_tags']);
        add_action('wp_ajax_wobef_get_categories', [$this, 'get_categories']);
        add_action('wp_ajax_wobef_get_order_notes', [$this, 'get_order_notes']);
        add_action('wp_ajax_wobef_add_order_note', [$this, 'add_order_note']);
        add_action('wp_ajax_wobef_delete_order_note', [$this, 'delete_order_note']);
        add_action('wp_ajax_wobef_get_order_address', [$this, 'get_order_address']);
        add_action('wp_ajax_wobef_get_order_items', [$this, 'get_order_items']);
        add_action('wp_ajax_wobef_add_meta_keys_manual', [$this, 'add_meta_keys_manual']);
    }

    public function order_edit()
    {
        if (empty($_POST['order_data']) || !is_array($_POST['order_data'])) {
            return false;
        }

        if (!empty($_POST['order_ids'])) {
            $order_ids = $_POST['order_ids'];
        } elseif (!empty($_POST['filter_data'])) {
            $args = Order_Helper::set_filter_data_items($_POST['filter_data'], [
                'fields' => 'ids',
            ]);
            $order_ids = ($this->order_repository->get_orders($args))->posts;
        } else {
            return false;
        }

        $update_service = WOBEF_Order_Update::get_instance();
        $update_service->set_update_data([
            'order_ids' => $order_ids,
            'order_data' => $_POST['order_data'],
            'save_history' => true,
        ]);
        $update_result = $update_service->perform();

        $result = $this->order_repository->get_orders_rows($order_ids);
        $histories = $this->history_repository->get_histories();
        $history_count = $this->history_repository->get_history_count();

        $histories_rendered = Render::html(WOBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));

        $this->make_response([
            'success' => $update_result,
            'orders' => $result->order_rows,
            'order_statuses' => $result->order_statuses,
            'status_filters' => $result->status_filters,
            'history_items' => $histories_rendered,
        ]);
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
            'tags' => $result->tags,
            'categories' => $result->categories,
            'taxonomies' => $result->taxonomies,
            'pagination' => $result->pagination,
            'status_filters' => $result->status_filters,
            'orders_count' => $result->count,
        ]);
    }

    public function add_meta_keys_manual()
    {
        if (isset($_POST['meta_key_name'])) {
            $meta_field['key'] = sanitize_text_field($_POST['meta_key_name']);
            $meta_fields_main_types = Meta_Field::get_main_types();
            $meta_fields_sub_types = Meta_Field::get_sub_types();
            $output = Render::html(WOBEF_VIEWS_DIR . "meta_field/meta_field_item.php", compact('meta_field', 'meta_fields_main_types', 'meta_fields_sub_types'));
            $this->make_response($output);
        }
        return false;
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
            'wobef_general_column_filter' => [
                [
                    'field' => 'post_title',
                    'value' => strtolower(sanitize_text_field($_POST['search'])),
                    'operator' => 'like',
                    'type' => 'product'
                ]
            ]
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
                                'text' => $key . ': ' . $taxonomy_item->name
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
                        'text' => $tag->name
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
                        'text' => $category->name
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
                'last_filter_data' => $data
            ]);
            $current_page = !empty($_POST['current_page']) ? intval($_POST['current_page']) : 1;
            $filter_result = $this->order_repository->get_orders_list($data, $current_page);
            $this->make_response([
                'success' => true,
                'orders_list' => $filter_result->orders_list,
                'products' => $filter_result->products,
                'tags' => $filter_result->tags,
                'categories' => $filter_result->categories,
                'taxonomies' => $filter_result->taxonomies,
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
            $order_id = intval(sanitize_text_field($_POST['order_id']));
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
                    $output .= Render::html(WOBEF_VIEWS_DIR . "meta_field/meta_field_item.php", compact('meta_field', 'meta_fields_main_types', 'meta_fields_sub_types'));
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
                    $output .= Render::html(WOBEF_VIEWS_DIR . "column_manager/field_item.php", compact('field_name', 'field_label', 'field_action', 'field_title'));
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
                            'field_name' => sanitize_text_field($field['name']),
                            'field_label' => sanitize_text_field($field['label']),
                            'field_title' => sanitize_text_field($field['title']),
                            'field_background_color' => sanitize_text_field($field['background_color']),
                            'field_text_color' => sanitize_text_field($field['text_color']),
                            'field_action' => "edit",
                        ];
                        $fields[] = sanitize_text_field($field['name']);
                        $output .= Render::html(WOBEF_VIEWS_DIR . 'column_manager/field_item.php', $field_info);
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
            $new_item = Render::html(WOBEF_VIEWS_DIR . 'modals/filter_profile_item.php', compact('filter_item'));
            $this->make_response([
                'success' => $save_result,
                'new_item' => $new_item,
            ]);
        }
        return false;
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
        if (isset($_POST['order_ids']) && is_array($_POST['order_ids']) && !empty($_POST['delete_type'])) {
            $order_ids = array_map('intval', $_POST['order_ids']);
            $trashed = [];
            switch ($_POST['delete_type']) {
                case 'trash':
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
                    break;
                case 'permanently':
                    foreach ($order_ids as $order_id) {
                        wp_delete_post(intval($order_id), true);
                    }
                    break;
            }

            if (!empty($trashed)) {
                $this->save_history_for_delete($trashed);
            }

            $histories = $this->history_repository->get_histories();
            $history_count = $this->history_repository->get_history_count();
            $histories_rendered = Render::html(WOBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));

            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'history_items' => $histories_rendered,
                'edited_ids' => $order_ids,
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
                'last_filter_data' => $preset['filter_data']
            ]);
            $result = $this->order_repository->get_orders_list($preset['filter_data'], 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $preset['filter_data'],
                'orders_list' => $result->orders_list,
                'products' => $result->products,
                'tags' => $result->tags,
                'categories' => $result->categories,
                'taxonomies' => $result->taxonomies,
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
                'success' => true
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
                    $preset['name'] = esc_sql($preset_item['name']);
                    $preset['key'] = esc_sql($preset_item['key']);
                    break;
            }

            $preset['fields'] = [];

            foreach ($_POST['items'] as $item) {
                if (isset($fields[$item])) {
                    $preset['fields'][$item] = [
                        'name' => sanitize_text_field($item),
                        'label' => sanitize_text_field($fields[$item]['label']),
                        'title' => sanitize_text_field($fields[$item]['label']),
                        'editable' => $fields[sanitize_text_field($item)]['editable'],
                        'content_type' => $fields[sanitize_text_field($item)]['content_type'],
                        'allowed_type' => $fields[sanitize_text_field($item)]['allowed_type'],
                        'background_color' => '#fff',
                        'text_color' => '#444',
                    ];
                    if (isset($fields[sanitize_text_field($item)]['sortable'])) {
                        $preset["fields"][sanitize_text_field($item)]['sortable'] = $fields[sanitize_text_field($item)]['sortable'];
                    }
                    if (isset($fields[sanitize_text_field($item)]['options'])) {
                        $preset["fields"][sanitize_text_field($item)]['options'] = $fields[sanitize_text_field($item)]['options'];
                    }
                    if (isset($fields[sanitize_text_field($item)]['field_type'])) {
                        $preset["fields"][sanitize_text_field($item)]['field_type'] = $fields[sanitize_text_field($item)]['field_type'];
                    }
                    $preset['checked'][] = sanitize_text_field($item);
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
                'count_per_page' => intval($_POST['count_per_page'])
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

    public function sort_by_column()
    {
        if (!empty($_POST['column_name']) && !empty($_POST['sort_type']) && !empty($_POST['filter_data'])) {
            $filter_data = Sanitizer::array($_POST['filter_data']);
            $setting_repository = new Setting();
            $setting_repository->update_current_settings([
                'sort_by' => sanitize_text_field($_POST['column_name']),
                'sort_type' => sanitize_text_field(strtoupper($_POST['sort_type'])),
            ]);
            $result = $this->order_repository->get_orders_list($filter_data, 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $filter_data,
                'orders_list' => $result->orders_list,
                'products' => $result->products,
                'tags' => $result->tags,
                'categories' => $result->categories,
                'taxonomies' => $result->taxonomies,
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

    public function order_billing_update()
    {
        if (empty($_POST['order_id'])) {
            return false;
        }

        $billing_data = Sanitizer::array($_POST['billing_data']);
        $order_id = intval(sanitize_text_field($_POST['order_id']));
        if (!empty($billing_data) && is_array($billing_data)) {
            foreach ($billing_data as $field => $value) {
                $this->order_repository->update([$order_id], [
                    'field_type' => 'main_field',
                    'field' => $field,
                    'value' => $value
                ]);
            }
        }

        $this->make_response([
            'success' => true
        ]);
    }

    public function order_shipping_update()
    {
        if (empty($_POST['order_id'])) {
            return false;
        }

        $shipping_data = Sanitizer::array($_POST['shipping_data']);
        $order_id = intval(sanitize_text_field($_POST['order_id']));
        if (!empty($shipping_data) && is_array($shipping_data)) {
            foreach ($shipping_data as $field => $value) {
                $this->order_repository->update([$order_id], [
                    'field_type' => 'main_field',
                    'field' => $field,
                    'value' => $value
                ]);
            }
        }

        $this->make_response([
            'success' => true
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
            'billing_address' => $customer_billing_address
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
            'shipping_address' => $customer_shipping_address
        ]);
    }

    public function get_order_notes()
    {
        if (empty($_POST['order_id'])) {
            return false;
        }

        $order_notes = wc_get_order_notes([
            'order_id' => intval(sanitize_text_field($_POST['order_id']))
        ]);
        if (empty($order_notes)) {
            return false;
        }

        $output = Render::html(WOBEF_VIEWS_DIR . 'bulk_edit/columns_modals/order_notes_items.php', compact('order_notes'));
        $this->make_response([
            'success' => true,
            'order_notes' => $output
        ]);
    }

    public function add_order_note()
    {
        if (empty($_POST['order_id'])) {
            return false;
        }

        $order = $this->order_repository->get_order(intval(sanitize_text_field($_POST['order_id'])));
        if (!($order instanceof \WC_Order)) {
            return false;
        }

        $order->add_order_note(sanitize_text_field($_POST['content']), ($_POST['type'] == 'customer') ? 1 : 0, true);
        $order->save();

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
            'success' => true
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
            case '_billing_address_1':
                $address = $order->get_billing_address_1();
                break;
            case '_billing_address_2':
                $address = $order->get_billing_address_2();
                break;
            case '_billing_address_index':
                $address = $order->get_formatted_billing_address();
                break;
            case '_shipping_address_1':
                $address = $order->get_shipping_address_1();
                break;
            case '_shipping_address_2':
                $address = $order->get_shipping_address_2();
                break;
            case '_shipping_address_index':
                $address = $order->get_formatted_shipping_address();
                break;
            default:
                $address = "";
        }

        $this->make_response([
            'success' => true,
            'address' => $address
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
            'order_items' => $order_items
        ]);
    }

    private function save_history($order_ids, $fields, $new_value, $operation_type)
    {
        $create_history = $this->history_repository->create_history([
            'user_id' => intval(get_current_user_id()),
            'fields' => serialize($fields),
            'operation_type' => esc_sql($operation_type),
            'operation_date' => date('Y-m-d H:i:s'),
        ]);

        if (!$create_history) {
            return false;
        }

        foreach ($order_ids as $order_id) {
            $order_object = $this->order_repository->get_order(intval($order_id));
            if (!($order_object instanceof \WC_Order)) {
                return false;
            }
            $order_item = $this->order_repository->order_to_array($order_object);
            if (!empty($fields)) {
                foreach ($fields as $field_type => $field) {
                    if (is_array($field)) {
                        $new_val = [];
                        $prev_val = [];
                        foreach ($field as $filed_name) {
                            $encoded_field = strtolower(urlencode($filed_name));
                            switch ($field_type) {
                                case 'custom_field':
                                    $new_val['custom_field'][$encoded_field] = $new_value[$encoded_field];
                                    $prev_val['custom_field'][$encoded_field] = (isset($order_item[$field_type][$encoded_field][0])) ? $order_item[$field_type][$encoded_field][0] : '';
                                    break;
                                case 'taxonomy':
                                    $new_val['taxonomy'][$encoded_field] = $new_value[$encoded_field];
                                    $prev_val['taxonomy'][$encoded_field] = ($encoded_field == 'order_tag') ? wp_get_post_terms($order_item['id'], $encoded_field, ['fields' => 'names']) : wp_get_post_terms($order_item['id'], $encoded_field, ['fields' => 'ids']);
                                    break;
                                default:
                                    break;
                            }
                        }
                    } else {
                        $encoded_field = strtolower(urlencode($field));
                        if (is_numeric($field_type)) {
                            $prev_val = (isset($order_item[$field])) ? $order_item[$field] : '';
                            if ($field == '_thumbnail_id') {
                                $new_val = [
                                    'id' => intval($new_value),
                                    'small' => wp_get_attachment_image_src(intval($new_value), [40, 40]),
                                    'big' => wp_get_attachment_image_src(intval($new_value), [600, 600]),
                                ];
                            } else {
                                $new_val = (!empty($new_value[$field])) ? $new_value[$field] : $new_value;
                            }
                        } else {
                            switch ($field_type) {
                                case 'custom_field':
                                    $new_val['custom_field'][$encoded_field] = $new_value;
                                    $prev_val['custom_field'][$encoded_field] = (isset($order_item[$field_type][$encoded_field][0])) ? $order_item[$field_type][$encoded_field][0] : '';
                                    break;
                                case 'taxonomy':
                                    $new_val['taxonomy'][$encoded_field] = $new_value;
                                    $prev_val['taxonomy'][$encoded_field] = ($encoded_field == 'order_tag') ? wp_get_post_terms($order_item['id'], $encoded_field, ['fields' => 'names']) : wp_get_post_terms($order_item['id'], $encoded_field, ['fields' => 'ids']);
                                    break;
                                default:
                                    break;
                            }
                        }
                    }

                    $this->history_repository->create_history_item([
                        'history_id' => intval($create_history),
                        'historiable_id' => intval($order_id),
                        'field' => (!empty($field_type) && !is_numeric($field_type)) ? serialize([$field_type => $field]) : serialize([$field]),
                        'prev_value' => serialize($prev_val),
                        'new_value' => serialize($new_val),
                    ]);
                }
            }
        }
        return true;
    }

    private function make_response($data)
    {
        echo (is_array($data)) ? json_encode($data) : $data;
        die();
    }
}
