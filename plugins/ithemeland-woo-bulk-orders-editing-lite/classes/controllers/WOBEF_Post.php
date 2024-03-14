<?php

namespace wobef\classes\controllers;

use wobef\classes\helpers\Sanitizer;
use wobef\classes\repositories\Flush_Message;
use wobef\classes\repositories\Column;
use wobef\classes\repositories\Meta_Field;
use wobef\classes\repositories\Order;
use wobef\classes\repositories\Search;
use wobef\classes\repositories\Setting;
use wobef\classes\services\activation\Activation_Service;

class WOBEF_Post
{
    private static $instance;

    public static function register_callback()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('admin_post_wobef_meta_fields', [$this, 'meta_fields']);
        add_action('admin_post_wobef_load_column_profile', [$this, 'load_column_profile']);
        add_action('admin_post_wobef_settings', [$this, 'settings']);
        add_action('admin_post_wobef_export_orders', [$this, 'export_orders']);
        add_action('admin_post_wobef_import_orders', [$this, 'import_orders']);
        add_action('admin_post_wobef_save_column_profile', [$this, 'save_column_profile']);
        add_action('admin_post_wobef_activation_plugin', [$this, 'activation_plugin']);
    }

    public function activation_plugin()
    {
        $message = "Error! Try again";

        if (isset($_POST['activation_type'])) {
            if ($_POST['activation_type'] == 'skip') {
                update_option('wobef_is_active', 'skipped');
                return $this->redirect('bulk-edit');
            } else {
                if (!empty($_POST['email']) && !empty($_POST['industry'])) {
                    $activation_service = new Activation_Service();
                    $info = $activation_service->activation([
                        'email' => sanitize_email($_POST['email']),
                        'domain' => $_SERVER['SERVER_NAME'],
                        'product_id' => 'wobef',
                        'product_name' => WOBEF_LABEL,
                        'industry' => sanitize_text_field($_POST['industry']),
                        'multi_site' => is_multisite(),
                        'core_version' => null,
                        'subsystem_version' => WOBEF_VERSION,
                    ]);

                    if (!empty($info) && is_array($info)) {
                        if (!empty($info['result']) && $info['result'] == true) {
                            update_option('wobef_is_active', 'yes');
                            $message = esc_html__('Success !', 'ithemeland-woocommerce-bulk-orders-editing-lite');
                        } else {
                            update_option('wobef_is_active', 'no');
                            $message = (!empty($info['message'])) ? esc_html($info['message']) : esc_html__('System Error !', 'ithemeland-woocommerce-bulk-orders-editing-lite');
                        }
                    } else {
                        update_option('wobef_is_active', 'no');
                        $message = esc_html__('Connection Timeout! Please Try Again', 'ithemeland-woocommerce-bulk-orders-editing-lite');
                    }
                }
            }
        }

        $this->redirect('activation', $message);
    }

    public function meta_fields()
    {
        $meta_fields = [];
        if (isset($_POST['save_meta_fields']) && !empty($_POST['meta_field_key'])) {
            for ($i = 0; $i < count($_POST['meta_field_key']); $i++) {
                $main_type = sanitize_text_field($_POST['meta_field_main_type'][$i]);
                $sub_type = sanitize_text_field($_POST['meta_field_sub_type'][$i]);
                $meta_fields[sanitize_text_field($_POST['meta_field_key'][$i])] = [
                    "key" => sanitize_text_field($_POST['meta_field_key'][$i]),
                    "title" => (!empty($_POST['meta_field_title'][$i])) ? sanitize_text_field($_POST['meta_field_title'][$i]) : sanitize_text_field($_POST['meta_field_key'][$i]),
                    "main_type" => $main_type,
                    "sub_type" => $sub_type,
                ];
            }
        }

        (new Meta_Field())->update($meta_fields);
        $column_repository = new Column();
        $column_repository->update_meta_field_items();
        $preset = $column_repository->get_preset($column_repository->get_active_columns()['name']);
        $fields = $column_repository->get_fields();
        $columns = [];
        if (!empty($preset['fields'])) {
            foreach ($preset['fields'] as $key => $column) {
                if (isset($fields[$key]) && isset($fields[$key]['options'])) {
                    $column['options'] = $fields[$key]['options'];
                }
                $columns[$key] = $column;
            }
            $column_repository->set_active_columns($column_repository->get_active_columns()['name'], $columns);
        }
        $this->redirect('meta-fields', esc_html__('Success !', 'ithemeland-woocommerce-bulk-orders-editing-lite'));
    }

    public function load_column_profile()
    {
        if (isset($_POST['preset_key'])) {
            $preset_key = esc_sql($_POST['preset_key']);
            $checked_columns = esc_sql($_POST["columns"]);
            $checked_columns = array_combine($checked_columns, $checked_columns);
            $column_repository = new Column();
            $columns = [];
            $fields = $column_repository->get_fields();
            $preset_columns = $column_repository->get_preset($preset_key);
            if (!empty($checked_columns) && is_array($checked_columns)) {
                if (!empty($preset_columns['fields'])) {
                    foreach ($preset_columns['fields'] as $column_key => $preset_column) {
                        if (isset($checked_columns[$column_key])) {
                            if (isset($preset_column['options']) && isset($fields[$column_key]['options'])) {
                                $preset_column['options'] = $fields[$column_key]['options'];
                            }
                            if (isset($preset_column['content_type']) && isset($fields[$column_key]['content_type'])) {
                                $preset_column['content_type'] = $fields[$column_key]['content_type'];
                            }

                            $columns[$column_key] = $preset_column;
                            unset($checked_columns[$column_key]);
                        }
                    }
                }
                if (!empty($checked_columns)) {
                    foreach ($checked_columns as $column_item) {
                        if (isset($fields[$column_item])) {
                            $checked_column = [
                                'name' => esc_sql($column_item),
                                'label' => esc_sql($fields[$column_item]['label']),
                                'title' => esc_sql($fields[$column_item]['label']),
                                'editable' => $fields[$column_item]['editable'],
                                'content_type' => $fields[$column_item]['content_type'],
                                'allowed_type' => $fields[$column_item]['allowed_type'],
                                'update_type' => $fields[$column_item]['update_type'],
                                'background_color' => '#fff',
                                'text_color' => '#444',
                            ];
                            if (isset($fields[$column_item]['sortable'])) {
                                $checked_column['sortable'] = ($fields[$column_item]['sortable']);
                            }
                            if (isset($fields[$column_item]['options'])) {
                                $checked_column['options'] = $fields[$column_item]['options'];
                            }
                            if (isset($fields[$column_item]['field_type'])) {
                                $checked_column['field_type'] = $fields[$column_item]['field_type'];
                            }
                            $columns[$column_item] = $checked_column;
                        }
                    }
                }
            }
            $column_repository->set_active_columns($preset_key, $columns);
        }
        $this->redirect();
    }

    public function settings()
    {
        if (isset($_POST['settings'])) {
            $setting_repository = new Setting();
            $setting_repository->update(Sanitizer::array($_POST['settings']));
        }

        $this->redirect('settings', esc_html__('Success !', 'ithemeland-woocommerce-bulk-orders-editing-lite'));
    }

    public function export_orders()
    {
        $file_name = "wobef-order-export-" . time() . '.csv';
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename={$file_name}");
        header("Pragma: no-cache");
        header("Expires: 0");
        $file = fopen('php://output', 'w');
        fwrite($file, chr(239) . chr(187) . chr(191)); //BOM For UTF-8
        $search_repository = new Search();
        $current_data = $search_repository->get_current_data();
        $last_filter_data = !empty($current_data['last_filter_data']) ? $current_data['last_filter_data'] : null;
        $column_repository = new Column();
        $order_repository = new Order();

        if (isset($_POST['orders'])) {
            switch ($_POST['orders']) {
                case 'all':
                    $args = \wobef\classes\helpers\Order_Helper::set_filter_data_items($last_filter_data, [
                        'posts_per_page' => '-1',
                        'post_type' => 'shop_order',
                        'post_status' => 'any',
                        'fields' => 'ids',
                    ]);
                    $orders = $order_repository->get_orders($args);
                    $order_ids = $orders->posts;
                    break;
                case 'selected':
                    $order_ids = isset($_POST['item_ids']) ? array_map('intval', $_POST['item_ids']) : [];
                    break;
            }

            switch ($_POST['fields']) {
                case 'all':
                    $columns = $column_repository->get_fields();
                    $columns['_transaction_id'] = ['label' => "Transaction ID"];
                    $columns['order_notes'] = ['label' => "Order Notes"];
                    break;
                case 'visible':
                    $columns = $column_repository->get_active_columns()['fields'];
                    break;
            }

            $except_columns = $order_repository->get_except_columns_for_export();
            if (!empty($except_columns) && is_array($except_columns)) {
                foreach ($except_columns as $except_column) {
                    if (isset($columns[$except_column])) {
                        unset($columns[$except_column]);
                    }
                }
            }

            if (!empty($order_ids)) {
                $header[] = "id";
                if (!empty($columns)) {
                    foreach ($columns as $column_key => $column) {
                        $header[] = $column_key;
                    }
                }
                fputcsv($file, $header);

                foreach ($order_ids as $order_id) {
                    $output = [];
                    $order_object = $order_repository->get_order(intval($order_id));
                    if ($order_object instanceof \WC_Order) {
                        $order = $order_repository->order_to_array_for_export($order_object);
                        if (!empty($order) && is_array($order)) {
                            $output[] = $order['id'];
                            if (!empty($columns)) {
                                foreach ($columns as $column_key => $column_item) {
                                    $output[] = (isset($order[$column_key])) ? (string)$order[$column_key] : " ";
                                }
                                fputcsv($file, $output);
                            }
                        }
                    }
                }
            }
        }
        die();
    }

    public function import_orders()
    {
        $wobef_upload_dir = wp_upload_dir('wobef')['path'];
        $message = "Error ! Please Try again";
        if (isset($_FILES["import_file"]) && !empty($_FILES['import_file']['type']) && $_FILES['import_file']['type'] == 'text/csv') {
            $target_file = $wobef_upload_dir . '/' . time() . rand(100, 999) . '.csv';
            $result = move_uploaded_file($_FILES["import_file"]["tmp_name"], $target_file);
            if ($result) {
                $message = "Success !";
                $order_repository = new Order();
                $order_repository->import_from_csv($target_file);
            }
        }

        $this->redirect('import-export', esc_html__($message, 'ithemeland-woocommerce-bulk-orders-editing-lite'));
    }

    private function redirect($active_tab = null, $message = null)
    {
        $hash = '';
        if (!is_null($active_tab)) {
            $hash = $active_tab;
        }

        if (!is_null($message)) {
            $flush_message_repository = new Flush_Message();
            $flush_message_repository->set(['message' => $message, 'hash' => $hash]);
        }

        return wp_redirect(WOBEF_PLUGIN_MAIN_PAGE . '#' . $hash);
    }
}
