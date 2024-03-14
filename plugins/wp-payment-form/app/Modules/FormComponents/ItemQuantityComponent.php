<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class ItemQuantityComponent extends BaseComponent
{
    public function __construct()
    {
        parent::__construct('item_quantity', 5);
        add_filter('wppayform/validate_component_on_save_item_quantity', array($this, 'validateOnSave'), 1, 3);
        add_filter('wppayform/validate_data_on_submission_item_quantity', array($this, 'validateOnSubmission'), 1, 4);
    }

    public function component()
    {
        return array(
            'type' => 'item_quantity',
            'is_pro' => 'no',
            'editor_title' => 'Item Quantity',
            'group' => 'item_quantity',
            'postion_group' => 'payment',
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
                    'label' => 'Default Quantity',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'target_product' => array(
                    'label' => 'Target Payment Item',
                    'type' => 'product_selector',
                    'group' => 'general',
                    'info' => 'Please select the product in where the quantity will be applied'
                ),
                'min_value' => array(
                    'label' => 'Minimum Quantity',
                    'type' => 'number',
                    'group' => 'general'
                ),
                'max_value' => array(
                    'label' => 'Maximum Quantity',
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
                'disable' => false,
                'label' => 'Quantity',
                'placeholder' => 'Provide Quantity',
                'required' => 'yes',
                'min_value' => 1,
                'target_product' => ''
            )
        );
    }

    public function validateOnSave($error, $element, $formId)
    {
        $disable = Arr::get($element, 'field_options.disable', false);
        if ($disable) {
            return;
        }

        if (!Arr::get($element, 'field_options.target_product')) {
            $error = __('Target Product is required for item:', 'wp-payment-form') . ' ' . Arr::get($element, 'field_options.label');
        }
        return $error;
    }

    public function validateOnSubmission($error, $elementId, $element, $form_data)
    {
        $disable = Arr::get($element, 'field_options.disable', false);
        if ($disable) {
            return;
        }

        if ($error) {
            return $error;
        }
        // Check if Min & max valid with data
        $itemValue = Arr::get($form_data, $elementId);

        if (!$itemValue) {
            return $error;
        }

        $minValue = Arr::get($element, 'options.min_value');
        $maxValue = Arr::get($element, 'options.max_value');

        $formId = Arr::get($form_data, '__wpf_form_id');
        // check the min value
        if ($minValue && $itemValue < $minValue) {
            $errorText = sprintf('need to be greater or equal %d', $minValue);
            return $this->getErrorLabel($element, $formId, $errorText);
        }

        if ($maxValue && $itemValue > $maxValue) {
            $errorText = sprintf('need to be less than or equal %d', $maxValue);
            return $this->getErrorLabel($element, $formId, $errorText);
        }
        return $error;
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
        $inputId = 'wpf_input_' . $form->ID . '_' . $element['id'];

        $defaultValue = '';
        if (isset($fieldOptions['default_value'])) {
            $defaultValue = $fieldOptions['default_value'];
        }

        $defaultValue = apply_filters('wppayform/input_default_value', $defaultValue, $element, $form);

        $attributes = array(
            'data-required' => Arr::get($fieldOptions, 'required'),
            'data-type' => 'input',
            'name' => $element['id'],
            'placeholder' => Arr::get($fieldOptions, 'placeholder'),
            'value' => $defaultValue,
            'type' => 'number',
            'min' => Arr::get($fieldOptions, 'min_value', '0'),
            'max' => Arr::get($fieldOptions, 'max_value'),
            'class' => $inputClass . ' wpf_item_qty',
            'data-target_product' => Arr::get($fieldOptions, 'target_product'),
            'id' => $inputId,
            'autocomplete' => 'off'
        );

        if (Arr::get($fieldOptions, 'required') == 'yes') {
            $attributes['required'] = true;
        }

        ?>
        <div style = "display : <?php echo esc_attr($hidden_attr); ?>" data-element_type="<?php echo esc_attr($this->elementName); ?>"
             class="<?php echo esc_attr($controlClass); ?>">
            <?php $this->buildLabel($fieldOptions, $form, array('for' => $inputId)); ?>
            <div class="wpf_input_content">
                <input <?php $this->printAttributes($attributes); ?> />
            </div>
        </div>
        <?php
    }
}
