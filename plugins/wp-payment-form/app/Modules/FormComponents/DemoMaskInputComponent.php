<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class DemoMaskInputComponent extends BaseComponent
{
    protected $componentName = 'mask_input';

    public function __construct()
    {
        parent::__construct($this->componentName, 600);
    }

    public function component()
    {
        return array(
            'type' => $this->componentName,
            'editor_title' => 'Mask Input',
            'group' => 'input',
            'is_pro' => 'yes',
            'postion_group' => 'general',
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
                'mask' => array(
                    'group' => 'general',
                    'label' => 'Mask Format',
                    'type' => 'mask_input',
                    'options' => array(
                        '' => __('None', 'wp-payment-form-pro'),
                        '(000) 000-0000' => '(###) ###-####',
                        '(00) 0000-0000' => '(##) ####-####',
                        '00/00/0000' => __('23/03/2018', 'wp-payment-form-pro'),
                        '00:00:00' => __('23:59:59', 'wp-payment-form-pro'),
                        '00/00/0000 00:00:00' => __('23/03/2018 23:59:59', 'wp-payment-form-pro'),
                        'custom' => __('Custom', 'wp-payment-form-pro'),
                    ),
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
                'label' => 'Mask Input',
                'placeholder' => '',
                'required' => 'no',
                'mask' => '(000) 000-0000',
                'mask_custom' => '(000) 000-0000',
                'is_mask_reverse' => 'no'
            )
        );
    }

    public function render($element, $form, $elements)
    {
        return;
    }
}
