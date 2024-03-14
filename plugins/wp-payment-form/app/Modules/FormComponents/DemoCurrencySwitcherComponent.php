<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\App\Services\GeneralSettings;
use WPPayForm\App\Models\Form;
use WPPayForm\App\Modules\FormComponents\BaseComponent;
use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class DemoCurrencySwitcherComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('currency_switcher', 11);
        add_filter('wppayform/validate_component_on_save_currency_switcher', array($this, 'validateOnSave'), 1, 3);
    }

    public function component()
    {
        return array(
            'type' => 'currency_switcher',
            'is_pro' => 'yes',
            'quick_checkout_form' => true,
            'editor_title' => 'Currency Switcher',
            'group' => 'currency',
            'postion_group' => 'payment',
            'single_only' => true,
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
                'switch_options' => array(
                    'label' => 'Currency Choices',
                    'type' => 'currency_pair',
                    'group' => 'general',
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
            'field_options' => array(
                'label' => 'Currency Switcher Options',
                'required' => 'no',
                'primary_currency' => '',
                'display_type' => 'select',
                'default_value' => '',
                'switch_options' => array(
                    array(
                        'label' =>  'United States Dollar',
                        'value' => 'USD',
                    ),
                ),
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
            )
        );
    }

    public function render($element, $form, $elements)
    {
        return;
    }

    public function validateOnSave($error, $element, $formId)
    {
        return;
    }
}
