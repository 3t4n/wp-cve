<?php

namespace WPPayForm\App\Modules\FormComponents;

if (!defined('ABSPATH')) {
    exit;
}

class DemoChoosePaymentMethod extends BaseComponent
{
    protected $componentName = 'choose_payment_method_gateway_element';

    public function __construct()
    {
        parent::__construct($this->componentName, 600);
    }

    public function component()
    {
        return array(
            'type' => 'choose_payment_method',
            'editor_title' => 'Choose Payment Method',
            'editor_icon' => '',
            'is_pro' => 'yes',
            'disable' => 'yes',
            'group' => 'payment_method_element',
            'postion_group' => 'payment_method',
            'editor_elements' => array(),
            'field_options' => array(
                'disable' => false,
            )
        );
    }

    public function render($element, $form, $elements)
    {
        return;
    }
}
