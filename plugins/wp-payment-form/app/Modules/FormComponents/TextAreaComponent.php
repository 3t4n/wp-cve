<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class TextAreaComponent extends BaseComponent
{
    public static $formInstance = 0;

    public static function getFormInstace($formId)
    {
        static::$formInstance += 1;
        return 'wpf_form_instance_' . $formId . '_' . static::$formInstance;
    }

    public function __construct()
    {
        parent::__construct('textarea', 14);
    }

    public function component()
    {
        return array(
            'type' => 'textarea',
            'is_pro' => 'no',
            'editor_title' => 'Textarea Field',
            'quick_checkout_form' => true,
            'group' => 'input',
            'postion_group' => 'general',
            'isNumberic' => 'no',
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
                    'type' => 'textarea',
                    'group' => 'general'
                ),
                'min_height' => array(
                    'label' => 'Minimum Height',
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
                'label' => 'Textarea Field',
                'placeholder' => '',
                'min_height' => '',
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
        $fieldOptions = Arr::get($element, 'field_options', false);
        $disable = Arr::get($fieldOptions, 'disable', false);
        $hidden_attr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic')  === 'yes' ? 'none' : 'block';
        if (!$fieldOptions || $disable) {
            return;
        }
        $controlClass = $this->elementControlClass($element);
        $inputClass = $this->elementInputClass($element);
        $formID = $this->getFormInstace($form->ID);
        $inputId = 'wpf_input_' . $formID . '_' . $this->elementName;

        $attributes = array(
            'data-required' => Arr::get($fieldOptions, 'required'),
            'data-type' => 'textarea',
            'name' => $element['id'],
            'placeholder' => Arr::get($fieldOptions, 'placeholder'),
            'class' => $inputClass,
            'id' => $inputId
        );

        if ($minHeight = Arr::get($fieldOptions, 'min_height')) {
            $attributes['style'] = 'min-height: ' . $minHeight . 'px;';
        }

        if (Arr::get($fieldOptions, 'required') == 'yes') {
            $attributes['required'] = true;
        }

        $defaultValue = apply_filters('wppayform/input_default_value', Arr::get($fieldOptions, 'default_value'), $element, $form);

        ?>
        <div style = "display : <?php echo esc_attr($hidden_attr); ?>" data-element_type="<?php echo esc_attr($this->elementName); ?>"
             class="<?php echo esc_attr($controlClass); ?>">
            <?php $this->buildLabel($fieldOptions, $form, array('for' => $inputId)); ?>
            <div class="wpf_input_content">
                <textarea <?php $this->printAttributes($attributes); ?>><?php echo esc_textarea($defaultValue); ?></textarea>
            </div>
        </div>
        <?php
    }
}
