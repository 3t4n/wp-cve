<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\App\Models\Form;
use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Models\Submission;

if (!defined('ABSPATH')) {
    exit;
}

class DemoDonationComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('donation_item', 200);
        add_filter('wppayform/validate_component_on_save_payment_item', array($this, 'validateOnSave'), 1, 3);
    }

    public function component()
    {
        return array(
            'type' => 'donation_item',
            'editor_title' => 'Donation Progress Item',
            'group' => 'payment',
            'is_pro' => 'yes',
            'postion_group' => 'payment',
            'conditional_hide' => true,
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Field Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'required' => array(
                    'label' => 'Required',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'enable_image' => array(
                    'label' => 'Enable Image',
                    'type' => 'switch',
                    'group' => 'general'
                ),
                'payment_options' => array(
                    'type' => 'donation_options',
                    'group' => 'general',
                    'label' => 'Configure Donation Progress Item',
                    'selection_type' => 'Payment Type'
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
                'disable' => false,
                'label' => 'Donation Progress Item',
                'required' => 'no',
                'enable_image' => 'yes',
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
                'pricing_details' => array(
                    'show_statistic' => 'no',
                    'donation_goals' => '1000',
                    'progress_bar' => 'yes',
                    'one_time_type' => 'choose_single',
                    'image_url' => array(
                        array(
                            'label' => '',
                            'value' => ''
                        )
                    ),
                    'multiple_pricing' => array(
                        array(
                            'label' => '',
                            'value' => '10'
                        ),
                        array(
                            'label' => '',
                            'value' => '20'
                        )
                    ),
                    'allow_custom_amount' => 'yes',
                    'allow_recurring' => 'no',
                    'bill_time_max' => '0',
                    'intervals' => [__('day', 'wp-payment-form-pro'), __('week', 'wp-payment-form-pro'), __('month', 'wp-payment-form-pro'), __('year', 'wp-payment-form-pro')],
                    'interval_options' => [__('day', 'wp-payment-form-pro'), __('week', 'wp-payment-form-pro'), __('month', 'wp-payment-form-pro'), __('year', 'wp-payment-form-pro')]
                )
            )
        );
    }

    public function validateOnSave($error, $element, $formId)
    {
        return;
    }

    public function render($element, $form, $elements)
    {
        return;
    }

    public function renderSingleAmount($element, $form, $amount = false)
    {
        return;
    }
}
