<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class HtmlComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('custom_html', 20);
    }

    public function component()
    {
        return array(
            'type' => 'custom_html',
            'editor_title' => 'HTML Markup',
            'group' => 'html',
            'is_pro' => 'no',
            'conditional_hide' => true,
            'postion_group' => 'general',
            'editor_elements' => array(
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
                'custom_html' => array(
                    'label' => 'Custom HTML',
                    'type' => 'html',
                    'group' => 'general',
                    'info' => 'You can use the following dynamic placeholder on your HTML <span>{payment_total}</span> <span>{sub_total}</span> <span>{tax_total}</span>'
                ),
                'wrapper_class' => array(
                    'label' => 'Field Wrapper CSS Class',
                    'type' => 'text',
                    'group' => 'advanced'
                )
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
                )
            ),
        );
    }

    public function render($element, $form, $elements)
    {
        $disable = Arr::get($element, 'field_options.disable', false);
        if ($disable) {
            return;
        }
        $this->renderHtmlContent($element, $form);
    }
}