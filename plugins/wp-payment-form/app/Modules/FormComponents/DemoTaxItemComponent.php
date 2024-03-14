<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;


if (!defined('ABSPATH')) {
    exit;
}

class DemoTaxItemComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('tax_payment_input', 6);
        // add_filter('wppayform/submitted_payment_items', array($this, 'pushTaxItems'), 999, 4);
        add_filter('wppayform/validate_component_on_save_tax_payment_input', array($this, 'validateOnSave'), 1, 3);
    }

    public function component()
    {
        return array(
            'type' => 'tax_payment_input',
            'editor_title' => 'Tax Calculated Amount',
            'group' => 'payment',
            'is_pro' => 'yes',
            'postion_group' => 'payment',
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Field Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'tax_percent' => array(
                    'label' => 'Tax Percentage',
                    'type' => 'number',
                    'group' => 'general'
                ),
                'target_product' => array(
                    'label' => 'Target Product Item',
                    'type' => 'onetime_products_selector',
                    'group' => 'general',
                    'info' => 'Please select the product in where this tax percentage will be applied'
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
            ),
            'field_options' => array(
                'label' => 'Tax Amount',
                'tax_percent' => '10'
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
