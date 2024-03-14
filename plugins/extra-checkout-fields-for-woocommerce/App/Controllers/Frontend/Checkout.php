<?php

namespace ECFFW\App\Controllers\Frontend;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use ECFFW\App\Controllers\Admin\Settings;
use ECFFW\App\Helpers\WPML;

class Checkout
{
    /**
     * Checkout fields.
     * @var array|null
     */
    public $fields = null;

    /**
     * Checkout skippable fields.
     * @var array
     */
    public $skippable_fields = [
        'billing' => [
            'billing_first_name',
            'billing_last_name',
            'billing_company',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_postcode',
            'billing_country',
            'billing_state',
            'billing_email',
            'billing_phone',
        ],
        'shipping' => [
            'shipping_first_name',
            'shipping_last_name',
            'shipping_company',
            'shipping_address_1',
            'shipping_address_2',
            'shipping_city',
            'shipping_postcode',
            'shipping_country',
            'shipping_state',
            'shipping_phone',
        ],
        'order' => [
            'order_comments'
        ],
        'custom' => [],
    ];

    /**
     * Checkout construct.
     */
    public function __construct()
    {
        add_filter('woocommerce_checkout_fields', array($this, 'fields'), 10, 2);
        add_filter('woocommerce_checkout_get_value', array($this, 'getValue'), 10, 2);
        add_filter('woocommerce_default_address_fields', array($this, 'addressFields'), 10, 2);
        
        add_action('woocommerce_checkout_process', array($this, 'processFields'));
        add_action('woocommerce_checkout_update_order_meta', array($this, 'updateFields'));
    
        $settings = Settings::get();
        if ($settings && isset($settings['custom_fields_position'])) {
            add_action('woocommerce_' . $settings['custom_fields_position'], array($this, 'customFields'));
        }
    }

    /**
     * Checkout Fields.
     */
    public function fields($fields = [])
    {
        if (!is_null($this->fields)) return $this->fields;

        $translate = WPML::stringTranslationIsActive();

        $form = new Form();
        $form_fields = $form->getFields();
        $types = ['billing', 'shipping', 'order', 'custom'];
        foreach ($types as $type) {
            $priority = 1;
            $fields[$type] = [];
            foreach ($form_fields[$type] as $field) {
                if (in_array($field->type, ['header', 'paragraph'])) {
                    if ($field->type == 'header') {
                        $name = 'header_' .  $priority;
                        $fields[$type][$name]['type'] = 'ecffw_header';
                    } else {
                        $name = 'paragraph_' .  $priority;
                        $fields[$type][$name]['type'] = 'ecffw_paragraph';
                    }

                    $classes = isset($field->className) ? trim($field->className) : '';
                    $fields[$type][$name]['label_class'] = explode(' ', $classes);
                    $fields[$type][$name]['tag'] = $field->subtype;
                    $fields[$type][$name]['content'] = $field->label;
                    $fields[$type][$name]['priority'] = $priority++;
                    continue;
                }

                if (isset($field->label)) {
                    if ($translate) {
                        $field->label = WPML::translateString('Label: ' . $field->label, $field->label);
                    }
                    $fields[$type][$field->name]['label'] = $field->label;
                }

                if (isset($field->required)) {
                    $fields[$type][$field->name]['required'] = $field->required;
                }

                if (isset($field->placeholder)) {
                    if ($translate) {
                        $field->placeholder = WPML::translateString('PlaceHolder: ' . $field->placeholder, $field->placeholder);
                    }
                    $fields[$type][$field->name]['placeholder'] = $field->placeholder;
                }

                if (isset($field->description)) {
                    if ($translate) {
                        $field->description = WPML::translateString('Description: ' . $field->description, $field->description);
                    }
                    $fields[$type][$field->name]['description'] = $field->description;
                }

                if (isset($field->rowType)) {
                    $fields[$type][$field->name]['class'] = explode(' ', trim($field->rowType));
                } else {
                    $fields[$type][$field->name]['class'][] = 'form-row-wide';
                }

                if (isset($field->className)) {
                    $fields[$type][$field->name]['input_class'] = explode(' ', trim($field->className));
                }

                if (isset($field->min)) {
                    $fields[$type][$field->name]['custom_attributes']['min'] = $field->min;
                }
                if (isset($field->max)) {
                    $fields[$type][$field->name]['custom_attributes']['max'] = $field->max;
                }
                if (isset($field->step)) {
                    $fields[$type][$field->name]['custom_attributes']['step'] = $field->step;
                }
                if (isset($field->rows)) {
                    $fields[$type][$field->name]['custom_attributes']['rows'] = $field->rows;
                }
                if (isset($field->maxlength)) {
                    $fields[$type][$field->name]['custom_attributes']['maxlength'] = $field->maxlength;
                }

                switch ($field->name) {
                    case $type . '_first_name':
                        $fields[$type][$field->name]['autocomplete'] = 'given-name';
                        break;
                    case $type . '_last_name':
                        $fields[$type][$field->name]['autocomplete'] = 'family-name';
                        break;
                    case $type . '_company':
                        $fields[$type][$field->name]['autocomplete'] = 'organization';
                        break;
                    case $type . '_address_1':
                        $fields[$type][$field->name]['autocomplete'] = 'address-line1';
                        break;
                    case $type . '_address_2':
                        $fields[$type][$field->name]['autocomplete'] = 'address-line2';
                        break;
                    case $type . '_city':
                        $fields[$type][$field->name]['autocomplete'] = 'address-level2';
                        break;
                    case $type . '_state':
                        $fields[$type][$field->name]['autocomplete'] = 'address-level1';
                        $fields[$type][$field->name]['validate'][] = 'state';
                        break;
                    case $type . '_country':
                        $fields[$type][$field->name]['class'][] = 'update_totals_on_change';
                        $fields[$type][$field->name]['autocomplete'] = 'country';
                        $fields[$type][$field->name]['validate'][] = 'country';
                        break;
                    case $type . '_postcode':
                        $fields[$type][$field->name]['autocomplete'] = 'postal-code';
                        $fields[$type][$field->name]['validate'][] = 'postcode';
                        break;
                    case $type . '_phone':
                        $fields[$type][$field->name]['autocomplete'] = 'tel';
                        $fields[$type][$field->name]['validate'][] = 'phone';
                        break;
                    case $type . '_email':
                        $fields[$type][$field->name]['autocomplete'] = 'email';
                        $fields[$type][$field->name]['validate'][] = 'email';
                        break;
                }
                
                if ($field->type == 'text' && isset($field->subtype)) {
                    $fields[$type][$field->name]['type'] = $field->subtype;
                } elseif ($field->type == 'select') {
                    $fields[$type][$field->name]['type'] = 'ecffw_select';
                } else {
                    $fields[$type][$field->name]['type'] = $field->type;
                }

                if (isset($field->value)) {
                    $fields[$type][$field->name]['value'] = $field->value;
                }

                if (isset($field->values)) {
                    foreach ($field->values as $option) {
                        if (isset($option->label) && $translate) {
                            $option->label = WPML::translateString('Option: ' . $option->label, $option->label);
                        }
                        $fields[$type][$field->name]['options'][] = (array) $option;
                    }
                }

                $fields = apply_filters('ecffw_checkout_field', $fields, $type, $field);

                $fields[$type][$field->name]['priority'] = $priority++;
            }
        }

        $this->fields = apply_filters('ecffw_checkout_fields', $fields);

        return $this->fields;
    }

    /**
     * Default Checkout Address Fields.
     */
    public function addressFields($fields)
    {
        $address_fields = [
            'first_name',
            'last_name',
            'company',
            'address_1',
            'address_2',
            'city',
            'postcode',
            'country',
            'state',
        ];

        $priority = 1;
        $form = new Form();
        $form_fields = $form->getFields();
        foreach ($form_fields['billing'] as $field) {
            if (!in_array($field->type, ['header', 'paragraph'])) {
                $field->name = str_replace('billing_', '', $field->name);
                if (in_array($field->name, $address_fields)) {
                    $fields[$field->name]['label'] = $field->label;
                    $fields[$field->name]['required'] = $field->required;
                    if (isset($field->placeholder)) {
                        $fields[$field->name]['placeholder'] = $field->placeholder;
                    }
                    $fields[$field->name]['priority'] = $priority++;
                    continue;
                }
            }
            $priority++;
        }

        return $fields;
    }

    /**
     * Custom Checkout Fields.
     */
    public static function customFields()
    {
        if (!function_exists('WC') && !is_a(WC()->checkout, 'WC_Checkout')) {
            return;
        }

        $checkout = WC()->checkout;
        $custom_fields = $checkout->get_checkout_fields('custom');
        if (empty($custom_fields)) {
            return;
        }

        $heading = '';
        $settings = Settings::get();
        if ($settings && isset($settings['custom_fields_heading'])) {
            $heading = $settings['custom_fields_heading'];
        }
        ?>
            <div id="ecffw-custom-fields">
                <?php
                    if ($heading) echo '<h3>' . esc_html($heading) . '</h3>';
                    foreach ($custom_fields as $key => $field) {
                        woocommerce_form_field($key, $field, $checkout->get_value($key));
                    }
                ?>
            </div>
        <?php
    }

    /**
     * Process Checkout Fields.
     */
    public function processFields() 
    {
        $messages = [];
        $form = new Form();
        $form_fields = $form->getFields();
        foreach ($form_fields as $type => $fields) {
            if ($type == 'shipping' && !isset($_POST['ship_to_different_address'])) {
                continue;
            }
            
            foreach ($fields as $field) {
                $message = $form->validateInput($field);
                if ($message) {
                    $messages[] = '<strong>' . ucfirst($type) . '</strong> ' . $message . '.';
                }
            }
        }

        if (!empty($messages)) {
            $this->errorNotice($messages);
        }
    }

    /**
     * WooCommerce Error Notice.
     */
    public function errorNotice($messages)
    {
        foreach ($messages as $message) {
            wc_add_notice($message, 'error');
        }

        if (is_ajax()) {
            $response = array(
                'result' => 'failure',
                'messages' => wc_print_notices(true),
            );

            wp_send_json($response);
        }
    }

    /**
     * Update Checkout Field.
     */
    public function updateFields($order_id)
    {
        $form = new Form();
        $order = wc_get_order($order_id);
        $form_fields = $form->getFields();
        foreach ($form_fields as $type => $fields) {
            foreach ($fields as $field) {
                $data = $form->getFieldData($field);
                $skippable_fields = $this->skippable_fields[$type];
                if (!empty($data) && !in_array($data['name'], $skippable_fields)) {
                    $label = ucfirst($type) . ': ' . $data['label'];
                    if (is_array($data['value'])) {
                        $value = implode(", ", $data['value']);
                    } else {
                        $value = $data['value'];
                    }
                    
                    $order->update_meta_data($label, $value);
                }
            }
        }

        $order->save_meta_data();
    }

    /**
     * Get Default value.
     */
    public function getValue($value, $input)
    {
        $form = new Form();
        $form_fields = $form->getFields();
        foreach ($form_fields as $type => $fields) {
            foreach ($fields as $field) {
                if (isset($field->name) && !in_array($field->name, $this->skippable_fields[$type])) {
                    if ($field->name == $input && isset($field->value)) {
                        return $field->value;
                    }
                }
            }
        }

        return $value;
    }
}
