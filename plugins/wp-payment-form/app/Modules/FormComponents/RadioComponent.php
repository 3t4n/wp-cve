<?php

namespace WPPayForm\App\Modules\FormComponents;

if (!defined('ABSPATH')) {
    exit;
}

class RadioComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('radio', 17);
    }

    public function component()
    {
        return array(
            'type' => 'radio',
            'is_pro' => 'no',
            'editor_title' => 'Radio Field',
            'quick_checkout_form' => true,
            'group' => 'input',
            'postion_group' => 'general',
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
                'default_value' => array(
                    'label' => 'Default Value',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'options' => array(
                    'label' => 'Field Choices',
                    'type' => 'key_pair',
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
                'label' => 'Radio',
                'placeholder' => '',
                'required' => 'no',
                'options' => array(
                    array(
                        'label' => 'Radio Item 1',
                        'value' => 'Radio Item 1'
                    ),
                    array(
                        'label' => 'Radio Item 2',
                        'value' => 'Radio Item 2'
                    )
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
        $this->renderRadioInput($element, $form);
    }
}