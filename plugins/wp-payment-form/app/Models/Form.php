<?php

namespace WPPayForm\App\Models;

use Exception;
use WPPayForm\App\Services\GeneralSettings;
use WPPayForm\Framework\Support\Arr;
use WPPayForm\Framework\Foundation\App;
use WPPayForm\App\Models\OrderItem;

class Form extends Model
{
    protected $table = 'posts';

    public static function getAllForms()
    {
        return static::select(['ID', 'post_title'])
            ->where('post_type', 'wp_payform')
            ->where('post_status', 'publish')
            ->orderBy('ID', 'DESC')
            ->get();
    }

    public static function migrate()
    {

        if (get_option('wppayform_order_items_meta_migrate') !== false && get_option('wppayform_order_items_meta_migrate') === 'yes') {
            return;
        }

        $metaMigrated = OrderItem::migrate();
        return $metaMigrated;
    }

    public function getFormInfo($formId)
    {
        $form = get_post($formId, 'OBJECT');
        if (!$form || $form->post_type != 'wp_payform') {
            throw new \Exception(esc_html(__('Form not found', 'wp-payment-form')));
        }
        $form->show_title_description = get_post_meta($formId, 'wppayform_show_title_description', true);
        $form->preview_url = site_url('?wp_paymentform_preview=' . $form->ID);
        return $form;
    }

    public function saveForm($formId, $builderSettings, $submit_button_settings)
    {
        $builderSettings = json_decode($builderSettings, true);

        if (!$formId || !$builderSettings) {
            wp_send_json_error(
                array(
                    'message' => __('Validation Error, Please try again', 'wp-payment-form'),
                    'errors' => array(
                        'general' => __('Please add at least one input element', 'wp-payment-form')
                    )
                ), 423);
        }
        $errors = array();
        $hasRecurringField = 'no';
        $hasPaymentItem = 'no';
        $builderSettings = $this->sanitizeFieldMaps($builderSettings);

        foreach ($builderSettings as $builderSetting) {
            $error = apply_filters('wppayform/validate_component_on_save_' . $builderSetting['type'], false, $builderSetting, $formId);
            if ($error) {
                $errors[$builderSetting['id']] = $error;
            }
            if ($builderSetting['type'] == 'recurring_payment_item') {
                $hasRecurringField = 'yes';
            }
            if ($builderSetting['group'] == 'payment') {
                $hasPaymentItem = 'yes';
            }
        }

        if ($errors) {
            wp_send_json_error(
                array(
                    'message' => __('Validation failed when saving the form', 'wp-payment-form'),
                    'errors' => $errors
                ), 423);
        }

        update_post_meta($formId, 'wppayform_paymentform_builder_settings', $builderSettings);
        update_post_meta($formId, 'wppayform_submit_button_settings', $submit_button_settings);
        update_post_meta($formId, 'wpf_has_recurring_field', $hasRecurringField);
        update_post_meta($formId, 'wpf_has_payment_field', $hasPaymentItem);
        return;
    }

    private function sanitizeFieldMaps($fields)
    {
        if (current_user_can('unfiltered_html') || apply_filters('wppayform_disable_fields_sanitize', false)) {
            return $fields;
        }

        if (!is_array($fields)) {
            return $fields;
        }

        $fieldOptionsMap = [
            'label' => 'wp_kses_post',
            'value' => 'wp_kses_post',
            'placeholder' => 'sanitize_text_field',
            'admin_label' => 'sanitize_text_field',
            'default_value' => 'sanitize_textarea_field',
            'wrapper_class' => 'sanitize_text_field',
            'element_class' => 'sanitize_text_field',
            'custom_html' => 'wppayform_sanitize_html'
        ];

        $fieldOptionsKeys = array_keys($fieldOptionsMap);

        $selectFieldMap = ['options' => 'wp_kses_post'];
        $selectFieldKeys = array_keys($selectFieldMap);

        foreach ($fields as $fieldIndex => $field) {
            // for select, radio, checkbox fields
            if ($field['type'] == 'select' || $field['type'] == 'radio' || $field['type'] == 'radio') {
                if (!empty($field['field_options'])) {
                    $attributes = array_filter(Arr::only($field['field_options'], $selectFieldKeys));
                    foreach ($attributes['options'] as $key => $value) {
                        $fields[$fieldIndex]['field_options']['options'][$key]['label'] = call_user_func($fieldOptionsMap['label'], $value['label']);
                        $fields[$fieldIndex]['field_options']['options'][$key]['value'] = call_user_func($fieldOptionsMap['value'], $value['value']);
                    }
                }
            }

            // for all select fields
            if (!empty($field['field_options'])) {
                $attributes = array_filter(Arr::only($field['field_options'], $fieldOptionsKeys));
                foreach ($attributes as $key => $value) {
                    $fields[$fieldIndex]['field_options'][$key] = call_user_func($fieldOptionsMap[$key], $value);
                }
            }
        }

        return $fields;
    }

    public static function index($request)
    {
        $perPage = absint(Arr::get($request, 'per_page'));
        $pageNumber = absint(Arr::get($request, 'page_number'));
        $searchString = sanitize_text_field(Arr::get($request, 'search_string'));
        $args = array(
            'posts_per_page' => $perPage,
            'offset' => $perPage * ($pageNumber - 1)
        );
        $args = apply_filters('wppayform/get_all_forms_args', $args);
        if ($searchString) {
            $args['s'] = $searchString;
        }

        return static::getForms($request, $args, $with = array('entries_count'));
    }

    public static function storeData($request)
    {
        $postTitle = Arr::get($request, 'post_title');
        if (!$postTitle) {
            $postTitle = 'Blank Form';
        }
        $template = Arr::get($request, 'template');

        $data = array(
            'post_title' => sanitize_text_field($postTitle),
            'post_status' => 'publish'
        );

        do_action('wppayform/before_create_form', $data, $template);
        $formId = static::store($data);

        wp_update_post([
            'ID' => $formId,
            'post_title' => sanitize_text_field($data['post_title']) . ' (#' . $formId . ')'
        ]);

        if (is_wp_error($formId)) {
            throw new Exception(esc_html($formId->get_error_message()));
        }

        do_action('wppayform/after_create_form', $formId, $data, $template);

        return $formId;
    }

    public static function getForms($request, $args = array(), $with = array())
    {
        $whereArgs = array(
            'post_type' => 'wp_payform',
            'post_status' => 'publish'
        );

        $perPage = Arr::get($request, 'per_page', 20);

        $whereArgs = apply_filters('wppayform/all_forms_where_args', $whereArgs);

        $keyword = !empty($args['s']) ? $args['s'] : '';

        $formsQuery = static::orderBy('ID', 'DESC');

        foreach ($whereArgs as $key => $where) {
            $formsQuery->where($key, $where);
        }

        $formsQuery->where(function ($query) use ($keyword) {
            $query->where('post_title', 'LIKE', "%{$keyword}%")
                ->orWhere('ID', 'LIKE', "%{$keyword}%");
        });

        $total = $formsQuery->count();
        $forms = $formsQuery->select('*')->limit($perPage)->offset($args['offset'])->get();

        foreach ($forms as $form) {
            $form->preview_url = site_url('?wp_paymentform_preview=' . $form->ID);
            $form->category = get_post_meta($form->ID, 'wpf_form_category', true);
            if (in_array('entries_count', $with)) {
                $form->entries_count = (new Submission)->getEntryCountByPaymentStatus($form->ID);
            }
        }

        $forms = apply_filters('wppayform/get_all_forms', $forms);

        $lastPage = ceil($total / $perPage);

        return array(
            'forms' => $forms,
            'total' => $total,
            'last_page' => $lastPage
        );
    }

    public static function insertTemplateForm($formId, $data, $template)
    {
        return DemoForms::insertTemplate($formId, $data, $template);
    }

    public static function getTotalCount()
    {
        return static::where('post_type', 'wp_payform')->count();
    }

    public static function getAllAvailableForms()
    {
        return static::select(array('ID', 'post_title'))
            ->where('post_type', 'wp_payform')
            ->orderBy('ID', 'DESC')
            ->get();
    }

    public static function store($data)
    {
        $data['post_type'] = 'wp_payform';
        $data['post_status'] = 'publish';
        $id = wp_insert_post($data);
        return $id;
    }

    public function updateForm($formId, $request_data)
    {
        // validate first
        $title = Arr::get($request_data, 'post_title');
        $description = Arr::get($request_data, 'show_title_description');

        if (!$formId || !$title) {
            throw new Exception(esc_html(__('Please provide form title', 'wp-payment-form')));
        }

        $formData = array(
            'post_title' => sanitize_text_field($title),
            'post_content' => wp_kses_post(Arr::get($request_data, 'post_content'))
        );

        do_action('wppayform/after_update_form', $formId, $formData);
        update_post_meta($formId, 'wppayform_show_title_description', sanitize_text_field($description));

        $formData['ID'] = $formId;
        $formData['post_type'] = 'wp_payform';
        $formData['post_status'] = 'publish';
        $res = wp_update_post($formData);
        do_action('wppayform/before_update_form', $formId, $formData);
        return $res;
    }

    public function saveSettings($request_data, $formId)
    {
        if ($confirm = Arr::get($request_data, 'confirmation_settings')) {
            $confirmationSettings = wp_unslash($confirm);
            $confirmationSettings = $this->sanitizationRules($confirmationSettings);
            update_post_meta($formId, 'wppapyform_paymentform_confirmation_settings', $confirmationSettings);
        }
        if ($currency = Arr::get($request_data, 'currency_settings')) {
            $currency_settings = wp_unslash($currency);
            update_post_meta($formId, 'wppayform_paymentform_currency_settings', $currency_settings);
        }

        if ($recaptcha = Arr::get($request_data, 'form_recaptcha_status')) {
            update_post_meta($formId, '_recaptcha_status', sanitize_text_field($recaptcha));
        }

        if ($turnstile = Arr::get($request_data, 'form_turnstile_status')) {
            update_post_meta($formId, '_turnstile_status', sanitize_text_field($turnstile));
        }

        if ($receipt = Arr::get($request_data, 'receipt_settings')) {
            $confirmationSettings = wp_unslash($receipt);
            update_post_meta($formId, 'wppapyform_receipt_settings', $confirmationSettings);
        }
        return array(
            'message' => __('Settings successfully updated', 'wp-payment-form')
        );
    }

    private function sanitizationRules($confirmationSettings)
    {
        $fieldOptionsMap = array(
            "confirmation_type" => 'sanitize_text_field',
            "redirectTo" => "sanitize_text_field",
            "customUrl" => "sanitize_text_field",
            "messageToShow" => 'wppayform_sanitize_html',
            "samePageFormBehavior" => "sanitize_text_field",
        );

        $fieldOptionsKeys = array_keys($fieldOptionsMap);

        foreach ($confirmationSettings as $key => $value) {
            if (in_array($key, $fieldOptionsKeys)) {
                $confirmationSettings[$key] = call_user_func($fieldOptionsMap[$key], $value);
            }
        }
        return $confirmationSettings;
    }

    public static function getButtonSettings($formId)
    {
        $settings = get_post_meta($formId, 'wppayform_submit_button_settings', true);
        if (!$settings) {
            $settings = array();
        }
        $buttonDefault = array(
            'button_text' => __('Submit', 'wp-payment-form'),
            'processing_text' => __('Please Wait…', 'wp-payment-form'),
            'button_style' => 'wpf_default_btn',
            'css_class' => ''
        );

        return wp_parse_args($settings, $buttonDefault);
    }

    public function getAllPages()
    {
        return $this->select(array('ID', 'post_title'))
            ->where('post_type', 'page')
            ->where('post_status', 'publish')
            ->get();
    }

    public static function getForm($formId)
    {
        $form = get_post($formId, 'OBJECT');
        if (!$form || $form->post_type != 'wp_payform') {
            return false;
        }
        $form->show_title_description = get_post_meta($formId, 'wppayform_show_title_description', true);
        $form->preview_url = site_url('?wp_paymentform_preview=' . $form->ID);
        return $form;
    }

    public static function getFormattedElements($formId)
    {
        $elements = Form::getBuilderSettings($formId);

        $formattedElements = array(
            'input' => array(),
            'payment' => array(),
            'payment_method_element' => array(),
            'item_quantity' => array()
        );
        foreach ($elements as $element) {
            $formattedElements[$element['group']][$element['id']] = array(
                'options' => Arr::get($element, 'field_options'),
                'type' => $element['type'],
                'id' => $element['id'],
                'label' => Arr::get($element, 'field_options.label')
            );
        }

        return $formattedElements;
    }

    public static function hasPaymentFields($formId)
    {
        $elements = Form::getBuilderSettings($formId);
        foreach ($elements as $element) {
            if (in_array($element['group'], ['payment', 'payment_method_element'])) {
                return true;
            }
        }
        return false;
    }

    public static function hasTheFields($formId, $type)
    {
        $elements = Form::getBuilderSettings($formId);
        foreach ($elements as $element) {
            if ($element['type'] == $type) {
                return true;
            }
        }
        return false;
    }

    public static function getPaymentMethodElements($formId)
    {
        $elements = self::getFormattedElements($formId);
        return $elements['payment_method_element'];
    }

    public static function getFormInputLabels($formId)
    {
        $elements = get_post_meta($formId, 'wppayform_paymentform_builder_settings', true);
        if (!$elements) {
            return (object) array();
        }
        $formLabels = array();
        foreach ($elements as $element) {
            if ($element['group'] == 'input') {
                $elementId = Arr::get($element, 'id');
                if (!$label = Arr::get($element, 'field_options.admin_label')) {
                    $label = Arr::get($element, 'field_options.label');
                }
                if (!$label) {
                    $label = $elementId;
                }
                $formLabels[$elementId] = $label;
            }
        }
        return (object) $formLabels;
    }

    public static function getConfirmationSettings($formId)
    {
        $confirmationSettings = get_post_meta($formId, 'wppapyform_paymentform_confirmation_settings', true);
        if (!$confirmationSettings) {
            $confirmationSettings = array();
        }
        $defaultSettings = array(
            'confirmation_type' => 'custom',
            'redirectTo' => 'samePage',
            'customUrl' => '',
            'messageToShow' => __('Form has been successfully submitted', 'wp-payment-form'),
            'samePageFormBehavior' => 'hide_form',
        );
        return wp_parse_args($confirmationSettings, $defaultSettings);
    }

    public static function getReceiptSettings($formId)
    {
        $receptSettings = get_post_meta($formId, 'wppapyform_receipt_settings', true);
        if (!$receptSettings) {
            $receptSettings = array();
        } else {
            if (isset($receptSettings['receipt_header'])) {
                if (strpos($receptSettings['receipt_header'], '[wppayform_reciept]') !== false || strpos($receptSettings['receipt_header'], '{submission.payment_receipt}') !== false) {
                    $receptSettings['receipt_header'] = str_replace(['[wppayform_reciept]', '{submission.payment_receipt}'], '', $receptSettings['receipt_header']);
                }
            }

            if (isset($receptSettings['receipt_footer'])) {
                if (strpos($receptSettings['receipt_footer'], '[wppayform_reciept]') !== false || strpos($receptSettings['receipt_footer'], '{submission.payment_receipt}') !== false) {
                    $receptSettings['receipt_footer'] = str_replace(['[wppayform_reciept]', '{submission.payment_receipt}'], '', $receptSettings['receipt_footer']);
                }
            }
        }

        $defaultSettings = array(
            'receipt_header' => __('Thanks for your order. Here are the details of your order:', 'wp-payment-form'),
            'receipt_footer' => '',
            'info_modules' => [
                'input_details' => 'yes',
                'payment_info' => 'yes'
            ],
        );

        return wp_parse_args($receptSettings, $defaultSettings);
    }


    public static function getCurrencySettings($formId)
    {
        $currencySettings = get_post_meta($formId, 'wppayform_paymentform_currency_settings', true);
        $globalSettings = GeneralSettings::getGlobalCurrencySettings();
        if (!$currencySettings) {
            $currencySettings = array();
        } elseif ($currencySettings['settings_type'] == 'global') {
            return $globalSettings;
        }

        // Remove it later if form base conversion api key needed
        if (isset($globalSettings['currency_conversion_api_key'])) {
            $currencySettings['currency_conversion_api_key'] = $globalSettings['currency_conversion_api_key'];
        }
        return wp_parse_args($currencySettings, $globalSettings);
    }

    public static function getCurrencyAndLocale($formId)
    {
        $settings = self::getCurrencySettings($formId);
        $globalSettings = GeneralSettings::getGlobalCurrencySettings($formId);
        if (isset($settings['settings_type']) && $settings['settings_type'] != 'global') {
            if (empty($settings['locale'])) {
                $settings['locale'] = 'auto';
            }
            if (empty($settings['currency'])) {
                $settings['currency'] = 'USD';
            }
            $settings['currency_sign_position'] = $globalSettings['currency_sign_position'];
            $settings['currency_separator'] = $globalSettings['currency_separator'];
            $settings['decimal_points'] = $globalSettings['decimal_points'];
        } else {
            $settings = $globalSettings;
        }
        $symbol = html_entity_decode(GeneralSettings::getCurrencySymbol($settings['currency']), ENT_QUOTES, 'UTF-8');
        $settings['currency_sign'] = $symbol;
        $settings['is_zero_decimal'] = GeneralSettings::isZeroDecimal($settings['currency']);
        return $settings;
    }

    public static function getEditorShortCodes($formId, $type = 'submission', $html = true)
    {
        $builderSettings = get_post_meta($formId, 'wppayform_paymentform_builder_settings', true);

        if (!$builderSettings) {
            return array();
        }
        $formattedShortcodes = array(
            'input' => array(
                'title' => 'Custom Input Items',
                'shortcodes' => array()
            ),
            'payment' => array(
                'title' => 'Payment Items',
                'shortcodes' => array()
            )
        );

        $hasPayment = false;

        foreach ($builderSettings as $element) {
            $elementId = Arr::get($element, 'id');
            if ($element['group'] == 'input') {
                if ($element['type'] === 'address_input') {
                    $addresses = Arr::get($element, 'field_options.subfields');
                    foreach ($addresses as $key => $val) {
                        $formattedShortcodes['input']['shortcodes']['{input.' . $elementId . '.' . $key . '}'] = $val['label'];
                    }
                } else {
                    $formattedShortcodes['input']['shortcodes']['{input.' . $elementId . '}'] = self::getLabel($element);
                }
            } elseif ($element['group'] == 'payment') {
                $formattedShortcodes['payment']['shortcodes']['{payment_item.' . $elementId . '}'] = self::getLabel($element);
                $hasPayment = true;
            } elseif ($element['group'] == 'item_quantity') {
                $formattedShortcodes['input']['shortcodes']['{quantity.' . $elementId . '}'] = self::getLabel($element);
            }
        }

        $submissionItem = array(
            'title' => 'Submission Fields',
            'shortcodes' => array(
                '{submission.id}' => __('Submission ID', 'wp-payment-form'),
                '{submission.submission_hash}' => __('Submission Hash ID', 'wp-payment-form'),
                '{submission.customer_name}'   => __('Customer Name', 'wp-payment-form'),
                '{submission.customer_email}'  => __('Customer Email', 'wp-payment-form'),
                '{submission.payment_method}'  => __('Payment Method', 'wp-payment-form'),
                '{submission.created_at}'  => __('Submission Time', 'wp-payment-form'),
            )
        );

        if ($html) {
            $submissionItem['shortcodes']['{submission.all_input_field_html}'] = __('All Input Field', 'wp-payment-form');
            $submissionItem['shortcodes']['{submission.all_input_field_html_with_empty}'] = __('All Input Field With Empty Fields', 'wp-payment-form');
            if ($hasPayment) {
                $submissionItem['shortcodes']['{submission.product_items_table_html}'] = __('Order Items Table', 'wp-payment-form');
                $submissionItem['shortcodes']['{submission.transaction_id}'] = __('Transaction ID', 'wp-payment-form');
            }

            // check if subscription payment is available for this for
            $hasRecurringField = get_post_meta($formId, 'wpf_has_recurring_field', true) == 'yes';
            if ($hasRecurringField) {
                $submissionItem['shortcodes']['{submission.subscription_details_table_html}'] = __('Subscription Details Table', 'wp-payment-form');
                $submissionItem['shortcodes']['{submission.subscription_id}'] = __('Subscription ID ', 'wp-payment-form');
            }

            $submissionItem['shortcodes']['{submission.payment_receipt}'] = __('Payment Receipt', 'wp-payment-form');
        }

        if ($hasPayment) {
            $submissionItem['shortcodes']['{submission.payment_total}'] = __('Payment Total', 'wp-payment-form');
        }

        if ($type == 'payment') {
            return $items = array($formattedShortcodes['payment']);
        } elseif ($type == 'submission') {
            return $items = array($submissionItem);
        } elseif ($type == 'input') {
            return $items = array($formattedShortcodes['input']);
        }
    }

    public static function getInputShortcode($formId)
    {
        $builderSettings = get_post_meta($formId, 'wppayform_paymentform_builder_settings', true);

        if (!$builderSettings) {
            return array();
        }

        $formattedShortcodes = array();

        foreach ($builderSettings as $element) {
            $elementId = Arr::get($element, 'id');
            if ($element['group'] == 'input') {
                $label = self::getLabel($element);
                $formattedShortcodes[$elementId] = array(
                    "element" => $elementId,
                    "admin_label" => Arr::get($element, 'field_options.admin_label'),
                    "options" => [],
                    "attributes" => [
                        "name" => $label,
                        "code" => "{input." . $elementId . "}",
                        "type" => $element['type']
                    ]
                );
            }
        }
        return $formattedShortcodes;
    }

    public static function getBuilderSettings($formId)
    {
        $builderSettings = get_post_meta($formId, 'wppayform_paymentform_builder_settings', true);
        if (!$builderSettings) {
            $builderSettings = array();
        }
        $defaultSettings = array();
        $elements = wp_parse_args($builderSettings, $defaultSettings);
        $allElements = GeneralSettings::getComponents();
        $parsedElements = array();

        foreach ($elements as $elementIndex => $element) {
            // if (!empty($allElements[$element['type']])) {
            //     $componentElement = $allElements[$element['type']];
            //     $fieldOption = Arr::get($element, 'field_options');
            //     if ($fieldOption) {
            //         $componentElement['field_options'] = $fieldOption;
            //     }
            //     $componentElement['id'] = Arr::get($element, 'id');
            //     $element = $componentElement;
            // }
            if (empty($element['active_page'])) {
                $element['active_page'] = 0;
            }
            if (empty($element['field_options']['conditional_logic_option'])) {
                $element['field_options']['conditional_logic_option'] = array(
                    'conditional_logic' => 'no',
                    'conditional_type' => 'any',
                    'options' => array(
                        array(
                            'target_field' => '',
                            'condition' => '',
                            'value' => ''
                        )
                    ),
                );
            }
            if ($element['type'] == 'choose_payment_method') {
                $available_methods = apply_filters('wppayform/available_payment_methods', array());
                $element['editor_elements']['method_settings']['available_methods'] = $available_methods;
            }
            $parsedElements[$elementIndex] = $element;
        }
        return $parsedElements;
    }

    public static function deleteForm($formID)
    {
        do_action('wppayform/before_form_delete', $formID);
        wp_delete_post($formID, true);
        static::where('ID', $formID)
            ->delete();

        Submission::where('form_id', $formID)
            ->delete();

        OrderItem::where('form_id', $formID)
            ->delete();

        Transaction::where('form_id', $formID)
            ->delete();

        SubmissionActivity::where('form_id', $formID)
            ->delete();

        Subscription::where('form_id', $formID)
            ->delete();

        do_action('wppayform/after_form_delete', $formID);

        return true;
    }

    private static function getLabel($element)
    {
        $elementId = Arr::get($element, 'id');
        if (!$label = Arr::get($element, 'field_options.admin_label')) {
            $label = Arr::get($element, 'field_options.label');
        }
        if (!$label) {
            $label = $elementId;
        }
        return $label;
    }

    public static function getDesignSettings($formId)
    {
        $settings = get_post_meta($formId, 'wppayform_form_design_settings', true);
        if (!$settings) {
            $settings = array();
        }
        $defaults = array(
            'labelPlacement' => 'top',
            'asteriskPlacement' => 'right',
            'submit_button_position' => 'left',
            'extra_styles' => array(
                'wpf_default_form_styles' => 'yes',
                'wpf_bold_labels' => 'no'
            )
        );
        return wp_parse_args($settings, $defaults);
    }

    public static function getSchedulingSettings($formId)
    {
        $settings = get_post_meta($formId, 'wppayform_form_scheduling_settings', true);
        if (!$settings) {
            $settings = array();
        }
        $defaults = array(
            'limitNumberOfEntries' => array(
                'status' => 'no',
                'limit_type' => 'total',
                'number_of_entries' => 100,
                'limit_payment_statuses' => array(),
                'limit_exceeds_message' => __('Number of entry has been exceeds, Please check back later', 'wp-payment-form')
            ),
            'scheduleForm' => array(
                'status' => 'no',
                'start_date' => current_time('mysql'),
                'end_date' => '',
                'before_start_message' => __('Form submission time schedule is not started yet. Please check back later', 'wp-payment-form'),
                'expire_message' => __('Form submission time has been expired.')
            ),
            'limitByPayments' => array(
                'status' => 'no',
                'limit_type' => 'payment',
                'payment_limit' => 1000,
                'limit_exceeds_message' => __('Payment target amount reached, Please check back later.', 'wp-payment-form')
            ),
            'requireLogin' => array(
                'status' => 'no',
                'message' => __('You need to login to submit this form', 'wp-payment-form')
            ),
            'restriction_applied_type' => 'hide_form'
        );
        return wp_parse_args($settings, $defaults);
    }

    public static function hasRecurring($formId)
    {
        return get_post_meta($formId, 'wpf_has_recurring_field', true) == 'yes';
    }

    public static function recaptchaType($formId)
    {
        $globalSettings = GeneralSettings::getRecaptchaSettings();
        $type = Arr::get($globalSettings, 'recaptcha_version');
        if ($type == 'none') {
            return false;
        }

        $recaptchaStatus = get_post_meta($formId, '_recaptcha_status', true);

        if ($recaptchaStatus == 'yes') {
            return $type;
        }
        return false;
    }

    public static function turnstileStatus($formId)
    {
        $status = get_post_meta($formId, '_turnstile_status', true);
        if ($status == 'yes') {
            return true;
        }
        return false;
    }

    public static function getStatus()
    {
        $stats = Submission::select([
            'wpf_submissions.id',
            'wpf_submissions.form_id',
            'wpf_submissions.customer_name',
            'wpf_submissions.payment_total',
            'wpf_submissions.payment_status',
            'wpf_submissions.payment_method',
            'wpf_submissions.updated_at',
            'posts.post_title',

            'wpf_subscriptions.recurring_amount',
            'wpf_subscriptions.initial_amount',
            'wpf_subscriptions.quantity',
            'wpf_subscriptions.status'

        ])
            ->orderBy('wpf_submissions.updated_at', 'DESC')
            ->join('posts', 'posts.ID', '=', 'wpf_submissions.form_id')
            ->leftJoin('wpf_subscriptions', function ($table) {
                $table->on('wpf_subscriptions.submission_id', '=', 'wpf_submissions.id');
            })
            ->limit(10)
            ->get();

        $allCurrencySettings = [];
        foreach ($stats as $stat) {
            if (!isset($allCurrencySettings[$stat->form_id])) {
                $currencySettings = Form::getCurrencyAndLocale($stat->form_id);
                $allCurrencySettings[$stat->form_id] = $currencySettings;
            } else {
                $currencySettings = $allCurrencySettings[$stat->form_id];
            }
            if ($stat->recurring_amount) {
                $subsTotal = ($stat->recurring_amount * $stat->quantity);
                $stat->formattedTotal = wpPayFormFormattedMoney($subsTotal, $currencySettings);
            } else {
                $stat->formattedTotal = wpPayFormFormattedMoney($stat->payment_total, $currencySettings);
            }
        }

        $DB = App::make('db');
        $paidStats = Submission::select(
            'currency',
            'form_id',
            $DB->raw("SUM(payment_total) as total_paid")
        )
            ->whereIn('payment_status', ['paid'])
            ->groupBy('currency')
            ->get();

        $subsTotal = Subscription::select(
            $DB->raw('SUM(payment_total - initial_amount) as recurring_total')
        )
            ->whereIn('status', ['active'])
            ->get();

        foreach ($paidStats as $paidStat) {
            if (!isset($allCurrencySettings[$paidStat->form_id])) {
                $currencySettings = Form::getCurrencyAndLocale($paidStat->form_id);
                $allCurrencySettings[$paidStat->form_id] = $currencySettings;
            } else {
                $currencySettings = $allCurrencySettings[$paidStat->form_id];
            }
            $totalPaid = intval($paidStat->total_paid) + intval($subsTotal[0]->recurring_total);
            $paidStat->formattedTotal = wpPayFormFormattedMoney($totalPaid, $currencySettings);
        }
        return [
            'stats' => $stats,
            'paidStats' => $paidStats
        ];
    }
}
