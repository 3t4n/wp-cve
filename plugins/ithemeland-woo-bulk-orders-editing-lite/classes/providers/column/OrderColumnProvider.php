<?php

namespace wobel\classes\providers\column;

use wobel\classes\helpers\Meta_Field as Meta_Field_Helper;
use wobel\classes\repositories\Column;
use wobel\classes\repositories\Order;
use wobel\classes\repositories\Setting;

class OrderColumnProvider
{
    private static $instance;
    private $sticky_first_columns;
    private $order_repository;
    private $order;
    private $order_object;
    private $column_key;
    private $decoded_column_key;
    private $column_data;
    private $field_type;
    private $settings;
    private $fields_method;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->order_repository = Order::get_instance();
        $setting_repository = new Setting();
        $this->settings = $setting_repository->get_settings();
        $this->sticky_first_columns = isset($this->settings['sticky_first_columns']) ? $this->settings['sticky_first_columns'] : 'yes';

        $this->field_type = "";

        $this->fields_method = $this->get_fields_method();
    }

    public function get_item_columns($item, $columns)
    {
        if ($item instanceof \WC_Order) {
            $this->order_object = $item;
            $this->order = $this->order_repository->order_to_array($item);
            $output = '<tr data-item-id="' . sanitize_text_field($this->order['id']) . '">';
            $output .= $this->get_static_columns();
            if (!empty($columns) && is_array($columns)) {
                foreach ($columns as $column_key => $column_data) {
                    $this->column_key = $column_key;
                    $this->column_data = $column_data;
                    $this->decoded_column_key = (substr($this->column_key, 0, 3) == 'pa_') ? strtolower(urlencode($this->column_key)) : urlencode($this->column_key);
                    $field_data = $this->get_field();
                    $output .= (!empty($field_data['field'])) ? $field_data['field'] : '';
                    if (isset($field_data['includes']) && is_array($field_data['includes'])) {
                        foreach ($field_data['includes'] as $include) {
                            if (file_exists($include)) {
                                include $include;
                            }
                        }
                    }
                }
            }
            $output .= $this->get_action_column();
            $output .= "</tr>";
            return $output;
        }
    }

    private function get_action_column()
    {
        $output = '<td class="wobel-action-column wc_actions column-wc_actions">';

        ob_start();
        do_action('woocommerce_admin_order_actions_start', $this->order_object);
        $output .= ob_get_clean();

        $actions = array();
        if ($this->order_object->has_status(array('pending', 'on-hold'))) {
            $actions['processing'] = array(
                'url'    => wp_nonce_url(admin_url('admin-ajax.php?action=woocommerce_mark_order_status&status=processing&order_id=' . $this->order_object->get_id()), 'woocommerce-mark-order-status'),
                'name'   => __('Processing', 'woocommerce'),
                'action' => 'processing',
            );
        }
        if ($this->order_object->has_status(array('pending', 'on-hold', 'processing'))) {
            $actions['complete'] = array(
                'url'    => wp_nonce_url(admin_url('admin-ajax.php?action=woocommerce_mark_order_status&status=completed&order_id=' . $this->order_object->get_id()), 'woocommerce-mark-order-status'),
                'name'   => __('Complete', 'woocommerce'),
                'action' => 'complete',
            );
        }

        ob_start();
        $actions = apply_filters('woocommerce_admin_order_actions', $actions, $this->order_object);
        $output .= ob_get_clean();

        if (!empty($actions)) {
            $output .=  wc_render_action_buttons($actions);
        }

        ob_start();
        do_action('woocommerce_admin_order_actions_end', $this->order_object);
        $output .= ob_get_clean();

        $output .= '</td>';
        return $output;
    }

    private function get_field()
    {
        $output['field'] = '';
        $output['includes'] = [];
        $this->field_type = '';

        $this->set_order_field();
        $color = $this->get_column_colors_style();

        if (isset($this->settings['show_billing_shipping_address_popup']) && $this->settings['show_billing_shipping_address_popup'] == 'no' && $this->column_data['content_type'] == 'address') {
            $this->column_data['content_type'] = 'text';
            if (in_array($this->column_key, ['billing_address_index', 'shipping_address_index'])) {
                $this->column_data['editable'] = false;
            }
        }

        $editable = ($this->column_data['editable']) ? 'yes' : 'no';

        $sub_name = (!empty($this->column_data['sub_name'])) ? $this->column_data['sub_name'] : '';
        $update_type = (!empty($this->column_data['update_type'])) ? $this->column_data['update_type'] : '';
        $output['field'] .= '<td data-item-id="' . sanitize_text_field($this->order['id']) . '" data-editable="' . $editable . '" data-item-title="#' . sanitize_text_field($this->order['id']) . '" data-col-title="' . sanitize_text_field($this->column_data['title']) . '" data-field="' . sanitize_text_field($this->column_key) . '" data-field-type="' . sanitize_text_field($this->field_type) . '" data-name="' . sanitize_text_field($this->column_data['name']) . '" data-sub-name="' . sanitize_text_field($sub_name) . '" data-update-type="' . sanitize_text_field($update_type) . '" style="' . sanitize_text_field($color['background']) . ' ' . sanitize_text_field($color['text']) . '"';
        if ($this->column_data['editable'] === true && !in_array($this->column_data['content_type'], ['multi_select', 'multi_select_attribute'])) {
            $output['field'] .= 'data-content-type="' . sanitize_text_field($this->column_data['content_type']) . '" data-action="inline-editable"';
        }
        $output['field'] .= '>';

        if ($this->column_data['editable'] === true) {
            $generated = $this->generate_field();
            if (is_array($generated) && isset($generated['field']) && isset($generated['includes'])) {
                $output['field'] .= $generated['field'];
                $output['includes'][] = $generated['includes'];
            } else {
                $output['field'] .= $generated;
            }
        } else {
            if (isset($this->order[$this->decoded_column_key])) {
                $output['field'] .= (is_array($this->order[$this->decoded_column_key])) ? sprintf('%s', implode(',', $this->order[$this->decoded_column_key])) : sprintf('%s', $this->order[$this->decoded_column_key]);
            } else {
                $output['field'] .= ' ';
            }
        }

        $output['field'] .= '</td>';
        return $output;
    }

    private function get_id_column()
    {
        $output = '';

        if (Column::SHOW_ID_COLUMN === true) {
            $delete_type = 'trash';
            $delete_label = esc_html__('Delete order', 'ithemeland-woocommerce-bulk-orders-editing-lite');
            $restore_button = '';
            $edit_button = '';

            if ($this->order['order_status'] == 'trash') {
                $delete_type = 'permanently';
                $delete_label = esc_html__('Delete permanently', 'ithemeland-woocommerce-bulk-orders-editing-lite');
                $restore_button = '<button type="button" style="height: 28px;" class="wobel-ml5 wobel-button-flat wobel-float-right wobel-text-green wobel-restore-item-btn" data-item-id="' . sanitize_text_field($this->order['id']) . '" title="' . esc_html__('Restore', 'ithemeland-woocommerce-bulk-orders-editing-lite') . '"><span class="wobel-icon-rotate-cw"></span></button>';
            } else {
                $edit_button = '<a href="' . admin_url("post.php?post=" . sanitize_text_field($this->order['id']) . "&action=edit") . '" target="_blank" style="height: 28px;" class="wobel-ml5 wobel-float-right" title="Edit Order"><span class="wobel-icon-pencil" style="vertical-align: middle;"></span></a>';
            }

            $sticky_class = ($this->sticky_first_columns == 'yes') ? 'wobel-td-sticky wobel-td-sticky-id wobel-gray-bg' : '';
            $output .= '<td data-item-id="' . sanitize_text_field($this->order['id']) . '" data-item-title="#' . sanitize_text_field($this->order['id']) . '" data-col-title="ID" class="' . sanitize_text_field($sticky_class) . '">';
            $output .= '<label class="wobel-td140">';
            $output .= '<input type="checkbox" class="wobel-check-item" value="' . sanitize_text_field($this->order['id']) . '" title="Select Order">';
            $output .= sanitize_text_field($this->order['id']);
            $output .= $restore_button;
            $output .= '<button type="button" class="wobel-ml5 wobel-button-flat wobel-text-red wobel-float-right wobel-delete-item-btn" data-delete-type="' . sanitize_text_field($delete_type) . '" data-item-id="' . sanitize_text_field($this->order['id']) . '" title="' . $delete_label . '"><span class="wobel-icon-trash-2"></span></button>';
            $output .= $edit_button;
            $output .= "</label>";
            $output .= "</td>";
        }
        return $output;
    }

    private function get_static_columns()
    {
        return $this->get_id_column();
    }

    private function set_order_field()
    {
        if (isset($this->column_data['field_type'])) {
            switch ($this->column_data['field_type']) {
                case 'custom_field':
                    $this->field_type = 'custom_field';
                    $this->order[$this->decoded_column_key] = (isset($this->order['custom_field'][$this->decoded_column_key])) ? $this->order['custom_field'][$this->decoded_column_key][0] : '';
                    break;
                default:
                    break;
            }
        }
    }

    private function get_column_colors_style()
    {
        if ($this->column_key == 'order_status' && isset($this->settings['colorize_status_column']) && $this->settings['colorize_status_column'] == 'yes') {
            $status_color = $this->order_repository->get_status_color($this->order['order_status']);
            $status_color = (!empty($status_color)) ? $status_color : '#fff';
            $color['background'] = "background: {$status_color};";
        } else {
            $color['background'] = (!empty($this->column_data['background_color']) && $this->column_data['background_color'] != '#fff' && $this->column_data['background_color'] != '#ffffff') ? 'background:' . sanitize_text_field($this->column_data['background_color']) . ';' : '';
        }
        $color['text'] = (!empty($this->column_data['text_color'])) ? 'color:' . sanitize_text_field($this->column_data['text_color']) . ';' : '';
        return $color;
    }

    private function generate_field()
    {
        if (isset($this->fields_method[$this->column_data['content_type']]) && method_exists($this, $this->fields_method[$this->column_data['content_type']])) {
            return $this->{$this->fields_method[$this->column_data['content_type']]}();
        } else {
            return (is_array($this->order[$this->decoded_column_key])) ? implode(',', $this->order[$this->decoded_column_key]) : $this->order[$this->decoded_column_key];
        }
    }

    private function get_fields_method()
    {
        return [
            'text' => 'text_field',
            'email' => 'text_field',
            'textarea' => 'textarea_field',
            'image' => 'image_field',
            'numeric' => 'numeric_with_calculator_field',
            'numeric_without_calculator' => 'numeric_field',
            'checkbox_dual_mode' => 'checkbox_dual_model_field',
            'checkbox' => 'checkbox_field',
            'radio' => 'radio_field',
            'file' => 'file_field',
            'select' => 'select_field',
            'date' => 'date_field',
            'date_picker' => 'date_field',
            'date_time_picker' => 'datetime_field',
            'time_picker' => 'time_field',
            'color_picker' => 'color_field',
            'order_details' => 'order_details_field',
            'order_items' => 'order_items_field',
            'all_billing' => 'all_billing_field',
            'all_shipping' => 'all_shipping_field',
            'address' => 'address_field',
            'order_notes' => 'order_notes_field',
            'customer' => 'customer_field',
            'order_status' => 'order_status_field',
        ];
    }

    private function text_field()
    {
        $value = (is_array($this->order[$this->decoded_column_key])) ? implode(',', $this->order[$this->decoded_column_key]) : $this->order[$this->decoded_column_key];
        $output = "<span data-action='inline-editable' class='wobel-td160'>" . sprintf('%s', $value) . "</span>";
        return $output;
    }

    private function textarea_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wobel-modal-text-editor' class='wobel-button wobel-button-white wobel-load-text-editor wobel-td160' data-item-id='" . sanitize_text_field($this->order['id']) . "' data-item-name='#" . sanitize_text_field($this->order['id']) . "' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>Content</button>";
    }

    private function image_field()
    {
        if (isset($this->order[$this->decoded_column_key]['small'])) {
            $image_id = intval($this->order[$this->decoded_column_key]['id']);
            $image = sprintf('%s', $this->order[$this->decoded_column_key]['small']);
            $full_size = wp_get_attachment_image_src($image_id, 'full');
        }
        if (isset($this->order[$this->decoded_column_key]) && is_numeric($this->order[$this->decoded_column_key])) {
            $image_id = intval($this->order[$this->decoded_column_key]);
            $image_url = wp_get_attachment_image_src($image_id, [40, 40]);
            $full_size = wp_get_attachment_image_src($image_id, 'full');
            $image = (!empty($image_url[0])) ? "<img src='" . esc_url($image_url[0]) . "' alt='' width='40' height='40' />" : null;
        }
        $image = (!empty($image)) ? $image : esc_html__('No Image', 'ithemeland-woocommerce-bulk-orders-editing-lite');
        $full_size = (!empty($full_size[0])) ? $full_size[0] : esc_url(wp_upload_dir()['baseurl'] . "/woocommerce-placeholder.png");
        $image_id = (!empty($image_id)) ? $image_id : 0;

        return "<span data-toggle='modal' data-target='#wobel-modal-image' data-id='wobel-" . sanitize_text_field($this->column_key) . "-" . sanitize_text_field($this->order['id']) . "' class='wobel-image-inline-edit' data-full-image-src='" . esc_url($full_size) . "' data-image-id='" . sanitize_text_field($image_id) . "'>" . $image . "</span>";
    }

    private function numeric_with_calculator_field()
    {
        return "<span data-action='inline-editable' class='wobel-numeric-content wobel-td120'>" . sanitize_text_field($this->order[$this->decoded_column_key]) . "</span><button type='button' data-toggle='modal' class='wobel-calculator' data-field='" . sanitize_text_field($this->column_key) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' data-item-name='#" . sanitize_text_field($this->order['id']) . "' data-field-type='" . sanitize_text_field($this->field_type) . "' data-target='#wobel-modal-numeric-calculator'></button>";
    }

    private function numeric_field()
    {
        return "<span data-action='inline-editable' class='wobel-numeric-content wobel-td120'>" . sanitize_text_field($this->order[$this->decoded_column_key]) . "</span>";
    }

    private function checkbox_dual_model_field()
    {
        $checked = ($this->order[$this->decoded_column_key] == 'yes' || $this->order[$this->decoded_column_key] == 1) ? 'checked="checked"' : "";
        return "<label><input type='checkbox' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' value='yes' class='wobel-dual-mode-checkbox wobel-inline-edit-action' " . sanitize_text_field($checked) . "><span>" . esc_html__('Yes', 'ithemeland-woocommerce-bulk-orders-editing-lite') . "</span></label>";
    }

    private function file_field()
    {
        $file_id = (isset($this->order[$this->decoded_column_key])) ? intval(sanitize_text_field($this->order[$this->decoded_column_key])) : null;
        $file_url = wp_get_attachment_url($file_id);
        $file_url = !empty($file_url) ? esc_url($file_url) : '';
        return "<button type='button' data-toggle='modal' data-target='#wobel-modal-file' class='wobel-button wobel-button-white' data-item-id='" . sanitize_text_field($this->order['id']) . "' data-item-name='#" . sanitize_text_field($this->order['id']) . "' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "' data-file-id='" . $file_id . "' data-file-url='" . $file_url . "'>Select File</button>";
    }

    private function order_status_field()
    {
        $statuses = $this->order_repository->get_order_statuses();
        $output = "<select class='wobel-inline-edit-action' data-field='" . sanitize_text_field($this->column_key) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' title='Select " . sanitize_text_field($this->column_data['label']) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>";
        foreach ($statuses as $status_key => $status_value) {
            $selected = ($status_key == $this->order[$this->decoded_column_key]) ? 'selected' : '';
            $output .= "<option value='{$status_key}' $selected>{$status_value}</option>";
        }
        $output .= '</select>';
        return $output;
    }

    private function select_field()
    {
        $output = "";
        if (in_array($this->column_key, ['billing_state', 'shipping_state'])) {
            $states = $this->order_repository->get_shipping_states();
            $country = ($this->column_key == 'billing_state') ? $this->order['billing_country'] : $this->order['shipping_country'];
            if (!empty($states) && !empty($states[$country]) && is_array($states[$country])) {
                $output .= "<select class='wobel-inline-edit-action' data-field='" . sanitize_text_field($this->column_key) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' title='Select " . sanitize_text_field($this->column_data['label']) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>";
                foreach ($states[$country] as $state_key => $state_label) {
                    $selected = ($state_key == $this->order[$this->decoded_column_key]) ? 'selected' : '';
                    $output .= "<option value='{$state_key}' {$selected}>{$state_label}</option>";
                }
                $output .= '</select>';
            } else {
                $output .= "<span data-action='inline-editable' class='wobel-td160'>" . $this->order[$this->decoded_column_key] . "</span>";
            }
        } else {
            if (!empty($this->column_data['options'])) {
                $output .= "<select class='wobel-inline-edit-action' data-field='" . sanitize_text_field($this->column_key) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' title='Select " . sanitize_text_field($this->column_data['label']) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>";
                if (in_array($this->decoded_column_key, ['billing_country', 'shipping_country'])) {
                    $output .= "<option value=''>" . esc_html__('Select Country', 'ithemeland-woocommerce-bulk-orders-editing-lite') . "</option>";
                }
                foreach ($this->column_data['options'] as $option_key => $option_value) {
                    $selected = ($option_key == $this->order[$this->decoded_column_key]) ? 'selected' : '';
                    $output .= "<option value='{$option_key}' $selected>{$option_value}</option>";
                }
                $output .= '</select>';
            } else {
                if ($this->column_data['field_type'] == 'custom_field') {
                    $meta_fields = get_option('wobel_meta_fields', []);
                    if (!empty($meta_fields[$this->column_data['name']]) && !empty($meta_fields[$this->column_data['name']]['key_value'])) {
                        $options = Meta_Field_Helper::key_value_field_to_array($meta_fields[$this->column_data['name']]['key_value']);
                        if (!empty($options) && is_array($options)) {
                            $output .= "<select class='wobel-inline-edit-action' data-field='" . sanitize_text_field($this->column_key) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' title='Select " . sanitize_text_field($this->column_data['label']) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>";
                            foreach ($options as $option_key => $option_value) {
                                $selected = isset($this->order[$this->decoded_column_key]) && $this->order[$this->decoded_column_key] == $option_key ? 'selected' : '';
                                $output .= "<option value='{$option_key}' $selected>{$option_value}</option>";
                            }
                            $output .= '</select>';
                        }
                    }
                }
            }
        }

        return $output;
    }

    private function date_field()
    {
        $date = (!empty($this->order[$this->decoded_column_key])) ? date('Y/m/d', strtotime($this->order[$this->decoded_column_key])) : '';
        $clear_button = ($this->decoded_column_key != 'post_date') ? "<button type='button' class='wobel-clear-date-btn wobel-inline-edit-clear-date' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' value=''><img src='" . esc_url(WOBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>" : '';
        return "<input type='text' class='wobel-datepicker wobel-inline-edit-action' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' title='Select " . sanitize_text_field($this->column_data['label']) . "' value='" . sanitize_text_field($date) . "'>" . sprintf('%s', $clear_button);
    }

    private function datetime_field()
    {
        $date = (!empty($this->order[$this->decoded_column_key])) ? date('Y/m/d H:i', strtotime($this->order[$this->decoded_column_key])) : '';
        $clear_button = "<button type='button' class='wobel-clear-date-btn wobel-inline-edit-clear-date' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' value=''><img src='" . esc_url(WOBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>";
        return "<input type='text' class='wobel-datetimepicker wobel-inline-edit-action' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' title='Select " . sanitize_text_field($this->column_data['label']) . "' value='" . sanitize_text_field($date) . "'>" . sprintf('%s', $clear_button);
    }

    private function time_field()
    {
        $date = (!empty($this->order[$this->decoded_column_key])) ? date('H:i', strtotime($this->order[$this->decoded_column_key])) : '';
        $clear_button = "<button type='button' class='wobel-clear-date-btn wobel-inline-edit-clear-date' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' value=''><img src='" . esc_url(WOBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>";
        return "<input type='text' class='wobel-timepicker wobel-inline-edit-action' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' title='Select " . sanitize_text_field($this->column_data['label']) . "' value='" . sanitize_text_field($date) . "'>" . sprintf('%s', $clear_button);
    }

    private function color_field()
    {
        return "<input type='text' class='wobel-color-picker-field wobel-inline-edit-action' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' title='Select " . sanitize_text_field($this->column_data['label']) . "' value='" . sanitize_text_field($this->order[$this->decoded_column_key]) . "'><button type='button' class='wobel-inline-edit-color-action'>" . esc_html__('Apply', 'ithemeland-woocommerce-bulk-orders-editing-lite') . "</button>";
    }

    private function order_details_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wobel-modal-order-details' class='wobel-button wobel-button-white wobel-td160 wobel-order-details-button' data-item-id='" . sanitize_text_field($this->order['id']) . "' data-item-name='#" . sanitize_text_field($this->order['id']) . "' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>" . esc_html__("Details", 'ithemeland-woocommerce-bulk-orders-editing-lite') . "</button>";
    }

    private function all_billing_field()
    {
        $customer_id = $this->order_object->get_customer_id();
        return "<button type='button' data-toggle='modal' data-target='#wobel-modal-order-billing' class='wobel-button wobel-button-white wobel-td160 wobel-order-billing-button' data-customer-id='" . sanitize_text_field($customer_id) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' data-item-name='#" . sanitize_text_field($this->order['id']) . "' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>" . esc_html__("All Billing", 'ithemeland-woocommerce-bulk-orders-editing-lite') . "</button>";
    }

    private function all_shipping_field()
    {
        $customer_id = $this->order_object->get_customer_id();
        return "<button type='button' data-toggle='modal' data-target='#wobel-modal-order-shipping' class='wobel-button wobel-button-white wobel-td160 wobel-order-shipping-button' data-customer-id='" . sanitize_text_field($customer_id) . "' data-item-id='" . sanitize_text_field($this->order['id']) . "' data-item-name='#" . sanitize_text_field($this->order['id']) . "' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>" . esc_html__("All Shipping", 'ithemeland-woocommerce-bulk-orders-editing-lite') . "</button>";
    }

    private function order_notes_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wobel-modal-order-notes' class='wobel-button wobel-button-white wobel-td160 wobel-order-notes-button' data-item-id='" . sanitize_text_field($this->order['id']) . "' data-item-name='#" . sanitize_text_field($this->order['id']) . "' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>" . esc_html__("Order Notes", 'ithemeland-woocommerce-bulk-orders-editing-lite') . "</button>";
    }

    private function address_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wobel-modal-order-address' data-field='" . sanitize_text_field($this->column_key) . "' class='wobel-button wobel-button-white wobel-td160 wobel-order-address' data-item-id='" . sanitize_text_field($this->order['id']) . "' data-item-name='#" . sanitize_text_field($this->order['id']) . "' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>" . esc_html__("Show Address", 'ithemeland-woocommerce-bulk-orders-editing-lite') . "</button>";
    }

    private function order_items_field()
    {
        if (isset($this->settings['show_order_items_popup']) && $this->settings['show_order_items_popup'] == 'yes') {
            return "<button type='button' data-toggle='modal' data-target='#wobel-modal-order-items' class='wobel-button wobel-button-white wobel-td160 wobel-order-items' data-item-id='" . sanitize_text_field($this->order['id']) . "' data-item-name='#" . sanitize_text_field($this->order['id']) . "' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>" . esc_html__("Order Items", 'ithemeland-woocommerce-bulk-orders-editing-lite') . "</button>";
        } else {
            return $this->order['order_items'];
        }
    }

    private function customer_field()
    {
        $customer_id = $this->order_object->get_customer_id();
        $modal = (isset($this->settings['show_customer_details']) && $this->settings['show_customer_details'] == 'yes') ? "data-toggle='modal' data-target='#wobel-modal-customer-details' data-customer-id='" . $customer_id . "'" : '';
        return "<a href='javascript:;'  class='wobel-td160 wobel-customer-details' {$modal} data-item-id='" . sanitize_text_field($this->order['id']) . "' data-item-name='" . esc_Attr($this->order[$this->column_key]) . "' data-field='" . sanitize_text_field($this->column_key) . "' data-field-type='" . sanitize_text_field($this->field_type) . "'>" . sanitize_text_field($this->order[$this->column_key]) . "</a>";
    }
}
