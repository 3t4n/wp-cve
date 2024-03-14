<?php

namespace WPPayForm\App\Modules\FormComponents;

if (!defined('ABSPATH')) {
    exit;
}

class NumberComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('number', 15);
    }

    public function component()
    {
        return array(
            'type' => 'number',
            'quick_checkout_form' => true,
            'editor_title' => 'Number Field',
            'is_pro' => 'no',
            'group' => 'input',
            'is_markup' => 'no',
            'postion_group' => 'general',
            'isNumberic' => 'yes',
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
                'min_value' => array(
                    'label' => 'Minimum Value',
                    'type' => 'number',
                    'group' => 'general'
                ),
                'max_value' => array(
                    'label' => 'Maximum Value',
                    'type' => 'number',
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
                ),
            ),
            'field_options' => array(
                'label' => 'Numeric Value',
                'placeholder' => '',
                'required' => 'no',
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
        $element['type'] = 'number';
        $this->renderNormalInput($element, $form);
    }
}
