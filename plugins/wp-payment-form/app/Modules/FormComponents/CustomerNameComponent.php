<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class CustomerNameComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('customer_name', 10);
    }

    public function component()
    {
        return array(
            'type' => 'customer_name',
            'active_page' => 0,
            'is_pro' => 'no',
            'quick_checkout_form' => true,
            'editor_title' => 'Name',
            'disable' => false,
            'group' => 'input',
            'postion_group' => 'general',
            'isNumberic' => 'no',
            'page' => '3',
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
                'label' => 'Your Name',
                'placeholder' => 'Name',
                'required' => 'yes',
                'admin_label' => 'Name',
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
        $element['type'] = 'text';
        $defaultValue = Arr::get($element, 'field_options.default_value');

        if ($defaultValue && strpos($defaultValue, '{current_user.display_name}') !== false) {
            $currentUserId = get_current_user_id();
            $replaceValue = '';
            if ($currentUserId) {
                $currentUser = get_user_by('ID', $currentUserId);
                $replaceValue = $currentUser->display_name;
            }
            $element['field_options']['default_value'] = str_replace('{current_user.display_name}', $replaceValue, $defaultValue);
        }

        $this->renderNormalInput($element, $form);
    }
}
