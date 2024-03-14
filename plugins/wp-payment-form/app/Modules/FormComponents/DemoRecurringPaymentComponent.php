<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\App\Models\Form;
use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class DemoRecurringPaymentComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('recurring_payment_item', 201);
    }

    public function component()
    {
        return array(
            'type' => 'recurring_payment_item',
            'editor_title' => __('Subscription Payment', 'wp-payment-form-pro'),
            'group' => 'payment',
            'is_pro' => 'yes',
            'postion_group' => 'payment',
            'conditional_hide' => true,
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Subscription Payment Item Name',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'required' => array(
                    'label' => 'Required',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'show_main_label' => array(
                    'label' => 'Show Pricing Label',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'show_payment_summary' => array(
                    'label' => 'Show Payment Summary',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'recurring_payment_options' => array(
                    'type' => 'recurring_payment_options',
                    'group' => 'general',
                    'label' => 'Configure Subscription Payment Plans',
                    'choice_label' => __('Choose Your Pricing Plan'),
                    'choice_types' => array(
                        'simple' => __('Simple Subscription Plan (Single)', 'wp-payment-form-pro'),
                        'choose_single' => __('Chose One From Multiple Pricing Plans', 'wp-payment-form-pro'),
                        //'choose_multiple' => __('Choose Multiple Plan from Pricing Plans', 'wp-payment-form-pro')
                    ),
                    'selection_types' => array(
                        'radio' => __('Radio Input Field', 'wp-payment-form-pro'),
                        'select' => __('Select Input Field', 'wp-payment-form-pro')
                    )
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
                ),
            ),
            'is_system_field' => true,
            'is_payment_field' => true,
            'field_options' => array(
                'label' => __('Subscription Item', 'wp-payment-form-pro'),
                'required' => 'yes',
                'show_main_label' => 'yes',
                'show_payment_summary' => 'yes',
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
                'recurring_payment_options' => array(
                    'choice_type' => 'simple',
                    'selection_type' => 'radio',
                    'pricing_options' => [
                        [
                            'name' => __('$9.99 / Month', 'wp-payment-form-pro'),
                            'trial_days' => 0,
                            'has_trial_days' => 'no',
                            'trial_days' => 0,
                            'billing_interval' => 'month',
                            'bill_times' => 0,
                            'has_signup_fee' => 'no',
                            'signup_fee' => 0,
                            'subscription_amount' => '9.99',
                            'is_default' => 'yes',
                            'plan_features' => []
                        ]
                    ]
                )
            )
        );
    }

    public function render($element, $form, $elements)
    {
        return;
    }
}
