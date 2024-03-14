<?php

namespace wobel\classes\controllers;

use wobel\classes\bootstrap\WOBEL_Verification;
use wobel\classes\repositories\Flush_Message;
use wobel\classes\providers\order\OrderProvider;
use wobel\classes\repositories\Column;
use wobel\classes\repositories\History;
use wobel\classes\repositories\Meta_Field;
use wobel\classes\repositories\Order;
use wobel\classes\repositories\Search;
use wobel\classes\repositories\Setting;
use wobel\classes\repositories\Tab_Repository;

class Woo_Order_Controller
{
    private static $instance;

    private $flush_message_repository;
    private $column_presets_fields;
    private $default_presets;
    private $plugin_data;
    private $order_repository;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        $this->flush_message_repository = new Flush_Message();
        if (!WOBEL_Verification::is_active() && !defined('WBEBL_NAME')) {
            return $this->activation_page();
        }

        $this->order_repository = Order::get_instance();

        $this->set_plugin_data();

        add_filter('wobel_top_navigation_buttons', [$this, 'add_navigation_buttons']);

        $this->view();
    }

    private function activation_page()
    {
        $plugin_key = 'wobel';
        $plugin_name = __('iThemeland WooCommerce Bulk Orders Editing Lite', 'ithemeland-woocommerce-bulk-orders-editing-lite');
        $plugin_description = WOBEL_DESCRIPTION;
        $flush_message = $this->flush_message_repository->get();

        include_once WOBEL_VIEWS_DIR . "activation/main.php";
    }

    public function set_plugin_data()
    {
        $meta_field_repository = new Meta_Field();
        $column_repository = new Column();
        $history_repository = History::get_instance();
        $search_repository = new Search();
        $setting_repository = new Setting();
        $tab_repository = new Tab_Repository();

        $settings = $setting_repository->get_settings();
        if (empty($settings)) {
            $setting_repository->set_default_settings();
            $settings = $setting_repository->get_settings();
        }

        if (!isset($settings['close_popup_after_applying'])) {
            $settings['close_popup_after_applying'] = 'no';
            $settings = $setting_repository->update($settings);
        }

        if (!$column_repository->has_column_fields()) {
            $column_repository->set_default_columns();
        }

        if (!$search_repository->has_search_options()) {
            $search_repository->set_default_item();
        }

        $current_settings = $setting_repository->update_current_settings([
            'sort_by' => isset($settings['default_sort_by']) ? $settings['default_sort_by'] : '',
            'sort_type' => isset($settings['default_sort']) ? $settings['default_sort'] : ''
        ]);

        if (!isset($current_settings['count_per_page'])) {
            $current_settings = $setting_repository->update_current_settings([
                'count_per_page' => isset($settings['count_per_page']) ? $settings['count_per_page'] : 10
            ]);
        }

        if (!isset($current_settings['sticky_first_columns'])) {
            $current_settings = $setting_repository->update_current_settings([
                'sticky_first_columns' => isset($settings['sticky_first_columns']) ? $settings['sticky_first_columns'] : 'yes'
            ]);
        }

        if (!isset($current_settings['show_customer_details'])) {
            $current_settings = $setting_repository->update_current_settings([
                'show_customer_details' => isset($settings['show_customer_details']) ? $settings['show_customer_details'] : 'yes'
            ]);
        }

        if (!$column_repository->get_active_columns()) {
            $column_repository->set_default_active_columns();
        }

        $this->column_presets_fields = $column_repository->get_presets_fields();
        $this->default_presets = $column_repository::get_default_columns_name();

        $active_columns_array = $column_repository->get_active_columns();
        if (isset($this->column_presets_fields[$active_columns_array['name']])) {
            $this->column_presets_fields[$active_columns_array['name']] = array_keys($active_columns_array['fields']);
        }

        $current_data = $search_repository->get_current_data();

        $this->plugin_data = [
            'plugin_key' => 'wobel',
            'version' => WOBEL_VERSION,
            'title' => __('iThemeland WooCommerce Bulk Orders Editing Lite', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
            'doc_link' => 'https://ithemelandco.com/Plugins/Documentations/Pro-Bulk-Editing/pro/woocommerce-bulk-orders-editing/documentation.pdf',
            'flush_message' => $this->flush_message_repository->get(),
            'settings' => $settings,
            'current_settings' => $current_settings,
            'columns' => $active_columns_array['fields'],
            'active_columns_key' => $active_columns_array['name'],
            'last_filter_data' => (isset($current_data['last_filter_data'])) ? $current_data['last_filter_data'] : null,
            'default_columns_name' => $column_repository::get_default_columns_name(),
            'items_loading' => true,
            'count_per_page_items' => $setting_repository->get_count_per_page_items(),
            'sort_by' => $current_settings['sort_by'],
            'sort_type' => $current_settings['sort_type'],
            'sticky_first_columns' => $settings['sticky_first_columns'],
            'item_provider' => OrderProvider::get_instance(),
            'show_id_column' => $column_repository::SHOW_ID_COLUMN,
            'filter_profile_use_always' => $search_repository->get_use_always(),
            'histories' => $history_repository->get_histories(),
            'history_count' => $history_repository->get_history_count(),
            'reverted' => $history_repository->get_latest_reverted(),
            'meta_fields_main_types' => Meta_Field::get_main_types(),
            'meta_fields_sub_types' => Meta_Field::get_sub_types(),
            'meta_fields' => $meta_field_repository->get(),
            'grouped_fields' => $column_repository->get_grouped_fields(),
            'column_items' => $column_repository->get_fields(),
            'column_manager_presets' => $column_repository->get_presets(),
            'filters_preset' => $search_repository->get_presets(),
            'order_statuses' => $this->order_repository->get_order_statuses(),
            'bulk_edit_form_tabs_title' => $tab_repository->get_bulk_edit_form_tabs_title(),
            'bulk_edit_form_tabs_content' => $tab_repository->get_bulk_edit_form_tabs_content(),
            'filter_form_tabs_title' => $tab_repository->get_filter_form_tabs_title(),
            'filter_form_tabs_content' => $tab_repository->get_filter_form_tabs_content(),
            'shipping_countries' => $this->order_repository->get_shipping_countries(),
            'payment_methods' => $this->order_repository->get_payment_methods(),

        ];
    }

    private function view()
    {
        $this->print_script();

        extract($this->plugin_data);
        include_once WOBEL_VIEWS_DIR . "layouts/main.php";
    }

    public function add_navigation_buttons($output)
    {
        if (empty($output)) {
            $output = '';
        }

        $last_filter_data = $this->plugin_data['last_filter_data'];
        $settings = $this->plugin_data['settings'];
        $current_settings = $this->plugin_data['current_settings'];

        ob_start();
        include WOBEL_VIEWS_DIR . "navigation/buttons.php";
        $output .= ob_get_clean();

        return $output;
    }

    public function print_script()
    {
        $id_in_url = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
        $shipping_states = $this->order_repository->get_shipping_states();

        echo "
        <script> 
        var itemIdInUrl = " . $id_in_url . "; 
        var wobelShippingStates = " . json_encode($shipping_states) . ";
        var defaultPresets = " . json_encode($this->default_presets) . ";
        var columnPresetsFields = " . json_encode($this->column_presets_fields) . ";
        </script>";
    }
}
