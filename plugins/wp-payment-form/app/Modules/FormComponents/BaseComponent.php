<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

abstract class BaseComponent
{
    public $elementName = '';

    public function __construct($elementName, $priority = 10)
    {
        $this->elementName = $elementName;
        $this->registerHooks($elementName, $priority);
    }

    public function registerHooks($elementName, $priority = 10)
    {
        add_filter('wppayform/form_components', array($this, 'addComponent'), $priority);
        add_action('wppayform/render_component_' . $elementName, array($this, 'render'), 10, 3);
    }

    public function addComponent($components)
    {
        $component = $this->component();
        if ($component) {
            $components[$this->elementName] = $this->component();
        }
        return $components;
    }

    public function validateOnSave($error, $element, $formId)
    {
        return $error;
    }

    public function renderNormalInput($element, $form)
    {
        $hiddenAttr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic') === 'yes' ? 'none' : 'block';
        $fieldOptions = Arr::get($element, 'field_options', false);
        $has_pro = defined('WPPAYFORMHASPRO') && WPPAYFORMHASPRO;
        $displayValue = $has_pro === true ? $hiddenAttr : '';
        $disable = Arr::get($fieldOptions, 'disable', false);
        if (!$fieldOptions || $disable) {
            return;
        }
        $controlClass = $this->elementControlClass($element);
        $inputClass = $this->elementInputClass($element);
        $inputId = 'wpf_input_' . $form->ID . '_' . str_replace([' ', '[', ']'], '_', $element['id']);
        $condition = '';
        if (strpos($element['id'], 'address_input') !== false) {
            $condition = Arr::get($element, 'condition_id');
        } else {
            $condition = $element['id'];
        }
        $defaultValue = apply_filters('wppayform/input_default_value', Arr::get($fieldOptions, 'default_value'), $element, $form);
        $attributes = array(
            'data-required' => Arr::get($fieldOptions, 'required'),
            'data-type'     => 'input',
            'name'          => $element['id'],
            'condition_id'  => $condition,
            'placeholder'   => Arr::get($fieldOptions, 'placeholder'),
            'value'         => $defaultValue,
            'type'          => Arr::get($element, 'type', 'text'),
            'class'         => $inputClass,
            'id'            => $inputId
        );

        if (isset($fieldOptions['min_value'])) {
            $attributes['min'] = $fieldOptions['min_value'];
        }

        if (isset($fieldOptions['max_value'])) {
            $attributes['max'] = $fieldOptions['max_value'];
        }

        if (Arr::get($fieldOptions, 'required') == 'yes') {
            $attributes['required'] = true;
        }

        if ($extraAtts = Arr::get($fieldOptions, 'extra_data_atts')) {
            if (is_array($extraAtts)) {
                $attributes = wp_parse_args($extraAtts, $attributes);
            }
        } ?>
        <div data-element_type="<?php echo esc_attr($this->elementName); ?>"
             class="<?php echo esc_attr($controlClass); ?>" style="display: <?php echo esc_html($displayValue); ?>">
            <?php $this->buildLabel($fieldOptions, $form, array('for' => $inputId)); ?>
            <div class="wpf_input_content">
                <input <?php $this->printAttributes($attributes); ?> />
            </div>
        </div>
        <?php
    }

    public function renderSelectInput($element, $form)
    {
        $fieldOptions = Arr::get($element, 'field_options', false);
        $disable = Arr::get($fieldOptions, 'disable', false);
        $hidden_attr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic') === 'yes' ? 'none' : 'block';

        if (!$fieldOptions || $disable) {
            return;
        }
        $controlClass = $this->elementControlClass($element);
        $inputClass = $this->elementInputClass($element);
        $inputId = 'wpf_input_' . $form->ID . '_' . str_replace([' ', '[', ']'], '_', $element['id']);

        $defaultValue = apply_filters('wppayform/input_default_value', Arr::get($fieldOptions, 'default_value'), $element, $form);

        $options = Arr::get($fieldOptions, 'options', array());
        $placeholder = Arr::get($fieldOptions, 'placeholder');
        $inputAttributes = array(
            'data-required' => Arr::get($fieldOptions, 'required'),
            'name'          => $element['id'],
            'class'         => $inputClass,
            'id'            => $inputId,
            'condition_id'  => Arr::get($element, 'condition_id')
        );
        if (Arr::get($fieldOptions, 'required') == 'yes') {
            $inputAttributes['required'] = true;
        }
        $controlAttributes = array(
            'id'                => 'wpf_' . $this->elementName,
            'data-element_type' => $this->elementName,
            'class'             => $controlClass
        );
        ?>
        <div style="display : <?php echo esc_attr($hidden_attr); ?>" <?php $this->printAttributes($controlAttributes); ?>>
            <?php $this->buildLabel($fieldOptions, $form, array('for' => $inputId)); ?>
            <div class="wpf_input_content">
                <select <?php echo $this->builtAttributes($inputAttributes); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $foo is escaped before being passed in. ?>>
                    <?php if ($placeholder) : ?>
                        <option data-type="placeholder" value=<?php echo esc_attr($defaultValue) ?> ><?php echo esc_attr($placeholder); ?></option>
                    <?php endif; ?>
                    <?php foreach ($options as $option) : ?>
                        <?php
                        $optionAttributes = array(
                            'value' => $option['value']
                        );
                        if ($defaultValue == $option['value']) {
                            $optionAttributes['selected'] = 'true';
                        } ?>
                        <option <?php $this->printAttributes($optionAttributes); ?>><?php echo esc_html($option['label']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php
    }

    public function renderPhoneInput($element, $form)
    {
        $fieldOptions = Arr::get($element, 'field_options', false);
        $disable = Arr::get($fieldOptions, 'disable', false);
        $hidden_attr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic') === 'yes' ? 'none' : 'block';
        $default__value = Arr::get($fieldOptions, 'default_value', false);

        if (!$fieldOptions || $disable) {
            return;
        }
        $controlClass = $this->elementControlClass($element);
        $inputClass = $this->elementInputClass($element);
        $inputId = 'wpf_input_' . $form->ID . '_' . str_replace([' ', '[', ']'], '_', $element['id']);

        $attributes = array(
            'value'        => $default__value,
            'class'        => $inputClass,
            'type'         => Arr::get($element, 'type', 'text'),
            'id'           => 'phone_code_' . $inputId . '_input',
            'condition_id' => $element['id']
        );

        $hidden_attributes = array(
            'name'  => $element['id'],
            'value' => $default__value,
            'type'  => 'hidden',
            'id'    => 'phone_code_' . $inputId . '_input_hidden'
        );
        ?>
        <p id="error_<?php echo esc_html($attributes['id']); ?>" hidden
           name="<?php echo esc_html($element['field_options']['label']); ?>" data-element_type="wpf_phone_input_error">
        </p>

        <div field_name="<?php echo esc_html($element['id']); ?>" style="display: <?php echo esc_attr($hidden_attr); ?>"
             data-element_type="<?php echo esc_attr($this->elementName); ?>"
             class="<?php echo esc_attr($controlClass); ?>">
            <?php $this->buildLabel($fieldOptions, $form, array('for' => $inputId)); ?>
            <div class="wpf_input_content">
                <input style="padding-left: 48px;" <?php $this->printAttributes($attributes); ?> />
                <input <?php $this->printAttributes($hidden_attributes); ?> />
            </div>
        </div>
        <?php
    }

    public function renderRadioInput($element, $form)
    {
        $fieldOptions = Arr::get($element, 'field_options', false);
        $hidden_attr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic') === 'yes' ? 'none' : 'block';
        $disable = Arr::get($fieldOptions, 'disable', false);

        if (!$fieldOptions || $disable) {
            return;
        }

        $controlClass = $this->elementControlClass($element);
        $inputClass = $this->elementInputClass($element);
        $inputId = 'wpf_input_' . $form->ID . '_' . $element['id'];

        $defaultValue = apply_filters('wppayform/input_default_value', Arr::get($fieldOptions, 'default_value'), $element, $form);

        $options = Arr::get($fieldOptions, 'options', array());

        $controlAttributes = array(
            'data-element_type'   => $this->elementName,
            'class'               => $controlClass,
            'data-required'       => Arr::get($fieldOptions, 'required'),
            'data-target_element' => $element['id']
        ); ?>
        <div
            style="display : <?php echo esc_attr($hidden_attr); ?>" <?php $this->printAttributes($controlAttributes); ?>>
            <?php $this->buildLabel($fieldOptions, $form, array('for' => $inputId)); ?>
            <div class="wpf_multi_form_controls wpf_input_content">
                <?php foreach ($options as $index => $option) : ?>
                    <?php
                    $optionId = $element['id'] . '_' . $index . '_' . $form->ID;
                    $attributes = array(
                        'class'     => 'form-check-input ' . $inputClass,
                        'data-type' => 'radio',
                        'type'      => 'radio',
                        'name'      => $element['id'],
                        'id'        => $optionId,
                        'value'     => $option['value']
                    );
                    if ($option['value'] == $defaultValue) {
                        $attributes['checked'] = 'true';
                    }
                    // if (Arr::get($fieldOptions, 'required') == 'yes') {
                    //     $attributes['required'] = true;
                    // }
                    ?>
                    <div class="form-check">
                        <input <?php $this->printAttributes($attributes); ?>>
                        <label class="form-check-label" for="<?php echo esc_attr($optionId); ?>">
                            <?php echo wp_kses_post($option['label']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    public function renderCheckBoxInput($element, $form)
    {
        $fieldOptions = Arr::get($element, 'field_options', false);
        $hiddenAttr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic') === 'yes' ? 'none' : 'block';
        $disable = Arr::get($fieldOptions, 'disable', false);
        $has_pro = defined('WPPAYFORMHASPRO') && WPPAYFORMHASPRO;
        $displayValue = $has_pro === true ? $hiddenAttr : '';
        if (!$fieldOptions || $disable) {
            return;
        }
        $controlClass = $this->elementControlClass($element);
        $inputClass = $this->elementInputClass($element);
        $inputId = 'wpf_input_' . $form->ID . '_' . $element['id'];
        $defaultValue = Arr::get($fieldOptions, 'default_value');
        $defaultValues = explode(',', $defaultValue);

        $defaultValues = apply_filters('wppayform/input_default_value', $defaultValues, $element, $form);

        $options = Arr::get($fieldOptions, 'options', array());

        $controlAttributes = array(
            'data-element_type'   => $this->elementName,
            'class'               => $controlClass,
            'data-target_element' => $element['id'],
            'required_id'         => $element['id']
        );
        if (Arr::get($fieldOptions, 'required') == 'yes') {
            $controlAttributes['data-checkbox_required'] = 'yes';
        } ?>
        <div
            style="display : <?php echo esc_attr($displayValue); ?>" <?php $this->printAttributes($controlAttributes); ?>>
            <?php $this->buildLabel($fieldOptions, $form, array('for' => $inputId)); ?>
            <div class="wpf_multi_form_controls wpf_input_content">
                <?php foreach ($options as $index => $option) : ?>
                    <?php
                    $optionId = $element['id'] . '_' . $index . '_' . $form->ID;
                    $attributes = array(
                        'class'        => 'form-check-input ' . $inputClass,
                        'type'         => 'checkbox',
                        'name'         => $element['id'] . '[]',
                        'condition_id' => $element['id'],
                        'id'           => $optionId,
                        'value'        => Arr::get($option, 'value')
                    );
                    if (in_array(Arr::get($option, 'value'), $defaultValues)) {
                        $attributes['checked'] = 'true';
                    } ?>
                    <div class="form-check">
                        <input <?php $this->printAttributes($attributes); ?>>
                        <label class="form-check-label" for="<?php echo esc_attr($optionId); ?>">
                            <?php echo wp_kses_post($option['label']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    public function renderHtmlContent($element, $form)
    {
        $wrapperClass = 'wpf_html_content_wrapper';
        $wpf_has_condition = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic') === 'yes' ? 'wpf_has_condition' : '';
        $hidden_attr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic') === 'yes' ? 'none' : 'block';
        if ($userClass = Arr::get($element, 'field_options.wrapper_class')) {
            $wrapperClass .= ' ' . $userClass;
        } ?>
        <div condition_id="<?php echo esc_attr(Arr::get($element, 'id')); ?>"
             style="display : <?php echo esc_attr($hidden_attr); ?>"
             class="<?php echo esc_attr($wrapperClass); ?> <?php echo esc_html($wpf_has_condition); ?>">
            <?php
            $text = Arr::get($element, 'field_options.custom_html');
            $id = Arr::get($element, 'id');
            wpPayFormPrintInternal($this->parseText($text, $form->ID, $id)); ?>
        </div>
        <?php
    }

    public function builtAttributes($attributes)
    {
        $atts = ' ';
        foreach ($attributes as $attributeKey => $attribute) {
            if (is_array($attribute)) {
                $attribute = json_encode($attribute);
            }
            if ($attribute == '') {
                continue;
            }
            
            $atts .= $attributeKey . '="' . htmlspecialchars($attribute, ENT_QUOTES) . '" ';
        }
        return $atts;
    }

    public function printAttributes($attributes)
    {
        echo ' ';
        foreach ($attributes as $attributeKey => $attribute) {
            if (is_array($attribute)) {
                $attribute = json_encode($attribute);
            }
            echo esc_attr($attributeKey).'="'. htmlspecialchars($attribute ?? '', ENT_QUOTES ?? '') . '" ';
        }
    }

    public function elementControlClass($element)
    {
        $class = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic') === 'yes' ?
            'wpf_has_condition wpf_form_group wpf_item_' . $element['type']
            : 'wpf_form_group wpf_item_' . $element['type'];
        if ($wrapperCssClass = Arr::get($element, 'field_options.wrapper_class')) {
            $class .= ' ' . $wrapperCssClass;
        }
        return apply_filters('wppayform/element_control_class', $class, $element);
    }

    public function elementInputClass($element)
    {
        $extraClasses = '';
        if (isset($element['extra_input_class'])) {
            $extraClasses = ' ' . $element['extra_input_class'];
        }

        if ($inputClass = Arr::get($element, 'field_options.element_class')) {
            $extraClasses .= ' ' . $inputClass;
        }

        return apply_filters('wppayform/element_input_class', 'wpf_form_control' . $extraClasses, $element);
    }

    public function parseText($text, $formId, $id = '')
    {
        return str_replace(
            array(
                '{sub_total}',
                '{tax_total}',
                '{payment_total}'
            ),
            array(
                "<span name='$id' class='wpf_calc_sub_total'></span>",
                "<span  name='$id' class='wpf_calc_tax_total'></span>",
                "<span  name='$id' class='wpf_calc_payment_total'></span>",
            ),
            $text
        );
    }

    public function buildLabel($fieldOptions, $form, $attributes = array())
    {
        $label = Arr::get($fieldOptions, 'label');
        $disable = Arr::get($fieldOptions, 'disable', false);
        if ($disable) {
            return;
        }
        $xtra_left = '';
        $xtra_right = '';
        $astPosition = $form->asteriskPosition;
        if (Arr::get($fieldOptions, 'required') == 'yes') {
            if ($astPosition == 'left') {
                $xtra_left = '<span class="wpf_required_sign wpf_required_sign_left">*</span> ';
            } elseif ($astPosition == 'right') {
                $xtra_right = ' <span class="wpf_required_sign wpf_required_sign_left">*</span>';
            }
        }
        if ($label) : ?>
            <div class="wpf_input_label">
                <label <?php $this->printAttributes($attributes); ?>><?php echo wp_kses_post($xtra_left . $label . $xtra_right); ?></label>
            </div>
        <?php endif;
    }

    abstract public function component();

    abstract public function render($element, $form, $elements);

    public function getErrorLabel($element, $formId, $labelSufix = '')
    {
        if (!$labelSufix) {
            $labelSufix = __('is required', 'wp-payment-form');
        }
        $label = Arr::get($element, 'options.label');
        if (!$label) {
            $label = Arr::get($element, 'options.placeholder');
            if (!$label) {
                $label = $element['id'];
            }
        }
        $label = $label . ' ' . $labelSufix;
        return apply_filters('wppayform/error_label_text', $label, $element, $formId);
    }
}
