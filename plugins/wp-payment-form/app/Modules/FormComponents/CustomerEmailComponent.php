<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Models\Submission;

if (!defined('ABSPATH')) {
    exit;
}

class CustomerEmailComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('customer_email', 11);
        add_filter('wppayform/validate_data_on_submission_customer_email', array($this, 'validateEmailOnSubmission'), 10, 4);
    }

    public function component()
    {
        return array(
            'type' => 'customer_email',
            'quick_checkout_form' => true,
            'editor_title' => 'Email',
            'is_pro' => 'no',
            'group' => 'input',
            'postion_group' => 'general',
            'isNumberic' => 'no',
            'page' => '3',
            'active_page' => 0,
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Field Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'placeholder' => array(
                    'label' => 'Placeholder',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'required' => array(
                    'label' => 'Required',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'confirm_email' => array(
                    'label' => __('Enable Confirm Email Field', 'wp-payment-form'),
                    'type' => 'confirm_email_switch',
                    'group' => 'general'
                ),
                'default_value' => array(
                    'label' => 'Default Value',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'admin_label' => array(
                    'label' => 'Admin Label',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'wrapper_class' => array(
                    'label' => 'Field Wrapper CSS Class',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'element_class' => array(
                    'label' => 'Input Element CSS Class',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'conditional_render' => array(
                    'type' => 'conditional_render',
                    'group' => 'advanced',
                    'label' => 'Conditional render',
                    'selection_type' => 'Conditional logic',
                    'conditional_logic' => array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    ),
                    'conditional_type' => array(
                        'any' => 'Any',
                        'all' => 'All'
                    ),
                    'unique_email_validation' => array(
                        'label' => 'Validate as Unique',
                        'type' => 'unique_email_validation_switch',
                        'group' => 'advanced'
                    ),
                )
            ),
            'field_options' => array(
                'disable' => false,
                'label' => 'Email Address',
                'placeholder' => 'Email Address',
                'required' => 'yes',
                'confirm_email' => 'no',
                'confirm_email_label' => 'Confirm Email',
                'conditional_logic_option' => array(
                    'conditional_logic' => 'no',
                    'conditional_type'  => 'any',
                    'options' => array(
                        array(
                            'target_field' => '',
                            'condition' => '',
                            'value' => ''
                        )
                    ),
                ),
                'default_value' => ''
            )
        );
    }

    public function validateEmailOnSubmission($error, $elementId, $element, $data)
    {
        // Validation Already failed so We are just returning it
        if ($error) {
            return $error;
        }
        $value = Arr::get($data, $elementId);
        if ($value) {
            // We have to check if it's a valid email address or not
            if (!is_email($value)) {
                return __('Valid email address is required for field:', 'wp-payment-form') . ' ' . Arr::get($element, 'label');
            }
        }

        // check if confirm email exists and need to validate
        if (Arr::get($element, 'options.confirm_email') == 'yes') {
            $confirmEmailvalue = Arr::get($data, '__confirm_' . $elementId);
            if ($confirmEmailvalue != $value) {
                return Arr::get($element, 'label') . ' & ' . Arr::get($element, 'options.confirm_email_label') . __(' does not match', 'wp-payment-form');
            }
        }

        // check if unique email validation is enabled
        if(Arr::get($element, 'options.unique_email_validation') === true) {
            $submission = Submission::where('form_id', intval($data['__wpf_form_id']))
                ->where('customer_email', $data['customer_email'])
                ->first();
            if(!empty($submission)) {
                return Arr::get($element, 'options.unique_validation_message') ? Arr::get($element, 'options.unique_validation_message') : 'Email already exist.';
            }
        }

        return $error;
    }

    public function render($element, $form, $elements)
    {
        $element['type'] = 'email';
        $element['extra_input_class'] = 'wpf_customer_email';
        $defaultValue = apply_filters('wppayform/input_default_value', Arr::get($element['field_options'], 'default_value'), $element, $form);
        $element['field_options']['default_value'] = $defaultValue;
        $this->renderNormalInput($element, $form);
        if (Arr::get($element, 'field_options.confirm_email') == 'yes') {
            $element['field_options']['extra_data_atts'] = array(
                'data-parent_confirm_name' => $element['id']
            );
            $element['extra_input_class'] = 'wpf_confirm_email';
            $element['id'] = '__confirm_' . $element['id'];
            $element['field_options']['placeholder'] = Arr::get($element, 'field_options.confirm_email_placeholder', 'Confirm Email');
            $element['field_options']['label'] = Arr::get($element, 'field_options.confirm_email_label', 'Confirm Email');
            $this->renderNormalInput($element, $form);
        }
    }
}
