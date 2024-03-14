<?php

namespace wobel\classes\repositories;

use wobel\classes\helpers\Meta_Fields;

class Column
{
    const SHOW_ID_COLUMN = true;
    const DEFAULT_PROFILE_NAME = 'default';

    private $columns_option_name;
    private $active_columns_option_name;

    public function __construct()
    {
        $this->columns_option_name = "wobel_column_fields";
        $this->active_columns_option_name = 'wobel_active_columns';
    }

    public function update(array $data)
    {
        if (!isset($data['key'])) {
            return false;
        }

        $presets = $this->get_presets();
        $presets[$data['key']] = $data;
        return update_option($this->columns_option_name, $presets);
    }

    public function delete($preset_key)
    {
        $presets = $this->get_presets();
        if (is_array($presets) && array_key_exists($preset_key, $presets)) {
            unset($presets[$preset_key]);
        }
        return update_option($this->columns_option_name, $presets);
    }

    public function get_preset($preset_key)
    {
        $presets = $this->get_presets();
        return (isset($presets[$preset_key])) ? $presets[$preset_key] : false;
    }

    public function get_presets()
    {
        return get_option($this->columns_option_name);
    }

    public function get_presets_fields()
    {
        $presets_fields = [];
        $presets = $this->get_presets();
        if (!empty($presets)) {
            foreach ($presets as $key => $preset) {
                $presets_fields[$key] = (!empty($preset['checked'])) ? $preset['checked'] : [];
            }
        }

        return $presets_fields;
    }

    public function set_active_columns(string $profile_name, array $columns, string $option_name = "")
    {
        $option_name = (!empty($option_name)) ? esc_sql($option_name) : $this->active_columns_option_name;
        return update_option($option_name, ['name' => $profile_name, 'fields' => $columns]);
    }

    public function get_active_columns()
    {
        return get_option($this->active_columns_option_name);
    }

    public function delete_active_columns()
    {
        return delete_option($this->active_columns_option_name);
    }

    public function has_column_fields()
    {
        $columns = get_option($this->columns_option_name);
        return !empty($columns['default']['fields']);
    }

    public static function get_columns_title()
    {
        return [];
    }

    public function update_meta_field_items()
    {
        $presets = $this->get_presets();
        $meta_fields = (new Meta_Field())->get();
        if (!empty($presets)) {
            foreach ($presets as $preset) {
                if (!empty($preset['fields'])) {
                    foreach ($preset['fields'] as $field) {
                        if (isset($field['field_type'])) {
                            if (isset($meta_fields[$field['name']])) {
                                $preset['fields'][$field['name']]['content_type'] = Meta_Fields::get_meta_field_type($meta_fields[$field['name']]['main_type'], $meta_fields[$field['name']]['sub_type']);
                                $this->update($preset);
                            }
                        }
                    }
                }
            }
        }
    }

    public function set_default_columns()
    {
        $fields['default'] = [
            'name' => 'Default',
            'date_modified' => date('Y-m-d H:i:s', time()),
            'key' => 'default',
            'fields' => $this->get_default_columns_default(),
            'checked' => array_keys($this->get_default_columns_default()),
        ];
        $fields['billing'] = [
            'name' => 'Billing Fields',
            'date_modified' => date('Y-m-d H:i:s', time()),
            'key' => 'billing',
            'fields' => $this->get_default_columns_billing(),
            'checked' => array_keys($this->get_default_columns_billing()),
        ];
        $fields['shipping'] = [
            'name' => 'Shipping Fields',
            'date_modified' => date('Y-m-d H:i:s', time()),
            'key' => 'shipping',
            'fields' => $this->get_default_columns_shipping(),
            'checked' => array_keys($this->get_default_columns_shipping()),
        ];
        return update_option('wobel_column_fields', $fields);
    }

    public function get_grouped_fields()
    {
        $grouped_fields = [];
        $fields = $this->get_fields();
        if (!empty($fields)) {
            foreach ($fields as $key => $field) {
                if (isset($field['field_type'])) {
                    switch ($field['field_type']) {
                        case 'general':
                            $grouped_fields['General'][$key] = $field;
                            break;
                        case 'billing':
                            $grouped_fields['Billing'][$key] = $field;
                            break;
                        case 'shipping':
                            $grouped_fields['Shipping'][$key] = $field;
                            break;
                        case 'pricing':
                            $grouped_fields['Pricing'][$key] = $field;
                            break;
                        case 'other_field':
                            $grouped_fields['Other Fields'][$key] = $field;
                            break;
                        case 'custom_field':
                            $grouped_fields['Custom Fields'][$key] = $field;
                            break;
                    }
                } else {
                    $grouped_fields['General'][$key] = $field;
                }
            }
        }
        return $grouped_fields;
    }

    public function get_fields()
    {
        $order_repository = Order::get_instance();
        $currencies = $order_repository->get_currencies();
        $payment_methods = $order_repository->get_payment_methods();
        $countries = $order_repository->get_shipping_countries();
        $payment_methods['other'] = esc_html__('Other', 'ithemeland-woocommerce-bulk-orders-editing-lite');

        return apply_filters('wobel_column_fields', [
            'date_created' => [
                'name' => 'date_created',
                'label' => esc_html__('Date', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date_time_picker',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'customer_note' => [
                'name' => 'customer_note',
                'label' => esc_html__('Customer Note', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'order_notes' => [
                'name' => 'order_notes',
                'label' => esc_html__('Order Notes', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'order_notes',
                'field_type' => 'general',
                'update_type' => 'order_notes',
            ],
            'order_status' => [
                'name' => 'order_status',
                'label' => esc_html__('Status', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'order_status',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_modified' => [
                'name' => 'date_modified',
                'label' => esc_html__('Modification Date', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => false,
                'sortable' => true,
                'content_type' => 'date',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'order_items' => [
                'name' => 'order_items',
                'label' => esc_html__('Order Items', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'order_items',
                'field_type' => 'general',
                'update_type' => 'order_items',
            ],
            'order_items_no' => [
                'name' => 'order_items_no',
                'label' => esc_html__('Order Items No.', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => false,
                'sortable' => true,
                'content_type' => 'text',
                'field_type' => 'general',
                'update_type' => '',
            ],
            'coupon_used' => [
                'name' => 'coupon_used',
                'label' => esc_html__('Coupon Used', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => false,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'other_field',
                'update_type' => '',
            ],
            '_order_stock_reduced' => [
                'name' => '_order_stock_reduced',
                'label' => esc_html__('Order Stock Reduce', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'other_field',
                'update_type' => 'meta_field',
            ],
            '_recorded_sales' => [
                'name' => '_recorded_sales',
                'label' => esc_html__('Recorded Sales', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'other_field',
                'update_type' => 'meta_field',
            ],
            'customer_ip_address' => [
                'name' => 'customer_ip_address',
                'label' => esc_html__('Customer IP Address', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => false,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'general',
                'update_type' => '',
            ],
            'customer_user' => [
                'name' => 'customer_user',
                'label' => esc_html__('Customer User', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'customer',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'customer_user_agent' => [
                'name' => 'customer_user_agent',
                'label' => esc_html__('Customer User Agent', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => false,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'general',
                'update_type' => '',
            ],
            'date_paid' => [
                'name' => 'date_paid',
                'label' => esc_html__('Paid Date', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'date_time_picker',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'date_completed' => [
                'name' => 'date_completed',
                'label' => esc_html__('Completed Date', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'date_time_picker',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'order_total' => [
                'name' => 'order_total',
                'label' => esc_html__('Order Total', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'field_type' => 'pricing',
                'update_type' => 'woocommerce_field',
            ],
            'order_sub_total' => [
                'name' => 'order_sub_total',
                'label' => esc_html__('Order SubTotal', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => false,
                'sortable' => true,
                'content_type' => 'numeric',
                'field_type' => 'pricing',
                'update_type' => '',
            ],
            'order_discount' => [
                'name' => 'order_discount',
                'label' => esc_html__('Cart Discount', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'field_type' => 'pricing',
                'update_type' => 'woocommerce_field',
            ],
            'order_discount_tax' => [
                'name' => 'order_discount_tax',
                'label' => esc_html__('Cart Discount Tax', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'field_type' => 'pricing',
                'update_type' => 'woocommerce_field',
            ],
            'order_details' => [
                'name' => 'order_details',
                'label' => esc_html__('Order Details', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'order_details',
                'field_type' => 'general',
                'update_type' => 'order_details',
            ],
            'all_billing' => [
                'name' => 'all_billing',
                'label' => esc_html__('All Billing', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'all_billing',
                'field_type' => 'billing',
                'update_type' => 'all_billing',
            ],
            'all_shipping' => [
                'name' => 'all_shipping',
                'label' => esc_html__('All Shipping', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'all_shipping',
                'field_type' => 'shipping',
                'update_type' => 'all_shipping',
            ],
            'created_via' => [
                'name' => 'created_via',
                'label' => esc_html__('Create Via', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => [
                    'checkout' => esc_html__('Checkout', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                    'admin' => esc_html__('Admin', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                ],
                'field_type' => 'other_field',
                'update_type' => 'woocommerce_field',
            ],
            'order_currency' => [
                'name' => 'order_currency',
                'label' => esc_html__('Order Currency', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => (!empty($currencies) && is_array($currencies)) ? $currencies : [],
                'field_type' => 'pricing',
                'update_type' => 'woocommerce_field',
            ],
            'payment_method' => [
                'name' => 'payment_method',
                'label' => esc_html__('Payment Method', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => $payment_methods,
                'field_type' => 'other_field',
                'update_type' => 'woocommerce_field',
            ],
            'payment_method_title' => [
                'name' => 'payment_method_title',
                'label' => esc_html__('Payment Method Title', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => false,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'other_field',
                'update_type' => '',
            ],
            'order_version' => [
                'name' => 'order_version',
                'label' => esc_html__('Order Version', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => false,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'general',
                'update_type' => '',
            ],
            'prices_include_tax' => [
                'name' => 'prices_include_tax',
                'label' => esc_html__('Prices Include Tax', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'other_field',
                'update_type' => 'woocommerce_field',
            ],
            '_order_tax' => [
                'name' => '_order_tax',
                'label' => esc_html__('Order Tax', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'numeric',
                'field_type' => 'pricing',
                'update_type' => 'meta_field',
            ],
            'order_shipping' => [
                'name' => 'order_shipping',
                'label' => esc_html__('Order Shipping', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'other_field',
                'update_type' => 'woocommerce_field',
            ],
            'order_shipping_tax' => [
                'name' => 'order_shipping_tax',
                'label' => esc_html__('Order Shipping Tax', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'other_field',
                'update_type' => 'woocommerce_field',
            ],
            'billing_first_name' => [
                'name' => 'billing_first_name',
                'label' => esc_html__('Billing First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_last_name' => [
                'name' => 'billing_last_name',
                'label' => esc_html__('Billing Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_address_1' => [
                'name' => 'billing_address_1',
                'label' => esc_html__('Billing Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_address_2' => [
                'name' => 'billing_address_2',
                'label' => esc_html__('Billing Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_city' => [
                'name' => 'billing_city',
                'label' => esc_html__('Billing City', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_city' => [
                'name' => 'billing_city',
                'label' => esc_html__('Billing City', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_company' => [
                'name' => 'billing_company',
                'label' => esc_html__('Billing Company', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_country' => [
                'name' => 'billing_country',
                'label' => esc_html__('Billing Country', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => $countries,
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_email' => [
                'name' => 'billing_email',
                'label' => esc_html__('Billing Email', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_phone' => [
                'name' => 'billing_phone',
                'label' => esc_html__('Billing Phone', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_address_index' => [
                'name' => 'billing_address_index',
                'label' => esc_html__('Billing Address Index', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_postcode' => [
                'name' => 'billing_postcode',
                'label' => esc_html__('Billing Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_state' => [
                'name' => 'billing_state',
                'label' => esc_html__('Billing State', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_first_name' => [
                'name' => 'shipping_first_name',
                'label' => esc_html__('Shipping First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_last_name' => [
                'name' => 'shipping_last_name',
                'label' => esc_html__('Shipping Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_address_1' => [
                'name' => 'shipping_address_1',
                'label' => esc_html__('Shipping Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_address_2' => [
                'name' => 'shipping_address_2',
                'label' => esc_html__('Shipping Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_city' => [
                'name' => 'shipping_city',
                'label' => esc_html__('Shipping City', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_company' => [
                'name' => 'shipping_company',
                'label' => esc_html__('Shipping Company', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_country' => [
                'name' => 'shipping_country',
                'label' => esc_html__('Shipping Country', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => $countries,
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_address_index' => [
                'name' => 'shipping_address_index',
                'label' => esc_html__('Shipping Address Index', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_postcode' => [
                'name' => 'shipping_postcode',
                'label' => esc_html__('Shipping Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_state' => [
                'name' => 'shipping_state',
                'label' => esc_html__('Shipping State', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
        ]);
    }

    public function set_default_active_columns()
    {
        return $this->set_active_columns(self::DEFAULT_PROFILE_NAME, self::get_default_columns_default());
    }

    public static function get_default_columns_name()
    {
        return [
            'default',
            'billing',
            'shipping'
        ];
    }

    public static function get_default_columns_default()
    {
        $order_repository = Order::get_instance();

        return [
            'order_status' => [
                'name' => 'order_status',
                'label' => esc_html__('Status', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Status', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'order_status',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general',
                'update_type' => 'woocommerce_field',
            ],
            'billing_first_name' => [
                'name' => 'billing_first_name',
                'label' => esc_html__('Billing First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_phone' => [
                'name' => 'billing_phone',
                'label' => esc_html__('Billing Phone', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing Phone', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_first_name' => [
                'name' => 'shipping_first_name',
                'label' => esc_html__('Shipping First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_last_name' => [
                'name' => 'shipping_last_name',
                'label' => esc_html__('Shipping Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
        ];
    }

    public static function get_default_columns_billing()
    {
        $order_repository = Order::get_instance();
        $countries = $order_repository->get_shipping_countries();

        return [
            'billing_first_name' => [
                'name' => 'billing_first_name',
                'label' => esc_html__('Billing First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_last_name' => [
                'name' => 'billing_last_name',
                'label' => esc_html__('Billing Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_email' => [
                'name' => 'billing_email',
                'label' => esc_html__('Billing Email', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing Email', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_phone' => [
                'name' => 'billing_phone',
                'label' => esc_html__('Billing Phone', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing Phone', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_city' => [
                'name' => 'billing_city',
                'label' => esc_html__('Billing City', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing City', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_country' => [
                'name' => 'billing_country',
                'label' => esc_html__('Billing Country', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing Country', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $countries,
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_state' => [
                'name' => 'billing_state',
                'label' => esc_html__('Billing State', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing State', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_address_1' => [
                'name' => 'billing_address_1',
                'label' => esc_html__('Billing Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_address_2' => [
                'name' => 'billing_address_2',
                'label' => esc_html__('Billing Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_address_index' => [
                'name' => 'billing_address_index',
                'label' => esc_html__('Billing Address Index', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing Address Index', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_postcode' => [
                'name' => 'billing_postcode',
                'label' => esc_html__('Billing Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'billing_company' => [
                'name' => 'billing_company',
                'label' => esc_html__('Billing Company', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Billing Company', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'woocommerce_field',
            ],
            'all_billing' => [
                'name' => 'all_billing',
                'label' => esc_html__('All Billing', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('All Billing', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'all_billing',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing',
                'update_type' => 'all_billing',
            ],
        ];
    }

    public static function get_default_columns_shipping()
    {
        $order_repository = Order::get_instance();
        $countries = $order_repository->get_shipping_countries();
        return [
            'shipping_first_name' => [
                'name' => 'shipping_first_name',
                'label' => esc_html__('Shipping First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_last_name' => [
                'name' => 'shipping_last_name',
                'label' => esc_html__('Shipping Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_city' => [
                'name' => 'shipping_city',
                'label' => esc_html__('Shipping City', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping City', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_country' => [
                'name' => 'shipping_country',
                'label' => esc_html__('Shipping Country', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping Country', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'options' => $countries,
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_state' => [
                'name' => 'shipping_state',
                'label' => esc_html__('Shipping State', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping State', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'select',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_address_1' => [
                'name' => 'shipping_address_1',
                'label' => esc_html__('Shipping Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_address_2' => [
                'name' => 'shipping_address_2',
                'label' => esc_html__('Shipping Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_address_index' => [
                'name' => 'shipping_address_index',
                'label' => esc_html__('Shipping Address Index', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping Address Index', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_postcode' => [
                'name' => 'shipping_postcode',
                'label' => esc_html__('Shipping Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'shipping_company' => [
                'name' => 'shipping_company',
                'label' => esc_html__('Shipping Company', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('Shipping Company', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'woocommerce_field',
            ],
            'all_shipping' => [
                'name' => 'all_shipping',
                'label' => esc_html__('All Shipping', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'title' => esc_html__('All Shipping', 'ithemeland-woocommerce-bulk-orders-editing-lite'),
                'editable' => true,
                'content_type' => 'all_shipping',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping',
                'update_type' => 'all_shipping',
            ],
        ];
    }
}
