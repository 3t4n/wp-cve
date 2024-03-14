<?php

namespace WPPayForm\App\Modules\FormComponents;

if (!defined('ABSPATH')) {
    exit;
}

class StepFormComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('step_form', 600);
    }

    public function component()
    {
        return array(
            'type' => 'step_form',
            'is_pro' => 'no',
            'editor_title' => 'Step Form',
            'group' => 'input',
            'postion_group' => 'general',
            'isNumberic' => 'no',
            'editor_elements' => array(
                'settings' => [
                    'prev_btn' => [
                        'type' => 'default',
                        'text' => __('Previous', 'wp-payment-form'),
                        'img_url' => '',
                        'method' => 'prev',
                        'icon' => 'el-icon-back'
                    ],
                    'next_btn' => [
                        'type' => 'default',
                        'text' => __('Next', 'wp-payment-form'),
                        'img_url' => '',
                        'method' => 'next',
                        'icon' => 'el-icon-right'
                    ],
                    'del_step' => [
                        'type' => 'danger',
                        'text' => 'Delete Step',
                        'img_url' => '',
                        'method' => 'del_step',
                        'icon' => ''
                    ],
                ],
                'editor_options' => [
                    'title' => __('Form Step', 'wp-payment-form'),
                    'icon_class' => 'ff-edit-step',
                    'template' => 'formStep',
                ],
                'form_steps' => [
                    [
                        'title' => __('Step 1', 'wp-payment-form'),
                        'description' => '',
                        'fields' => [],
                    ],
                    [
                        'title' => __('Step 2', 'wp-payment-form'),
                        'description' => '',
                        'fields' => [],
                    ]
                ],
            ),
            'field_options' => array(
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
        $element['type'] = 'text';
        $this->renderNormalInput($element, $form);
    }
}
