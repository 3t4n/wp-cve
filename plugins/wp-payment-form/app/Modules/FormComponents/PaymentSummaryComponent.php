<?php

namespace WPPayForm\App\Modules\FormComponents;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class PaymentSummaryComponent extends BaseComponent
{
    public static $formInstance = 0;

    public static function getFormInstace($formId)
    {
        static::$formInstance += 1;
        return 'wpf_form_instance_' . $formId . '_' . static::$formInstance;
    }

    public function __construct()
    {
        parent::__construct('payment_summary', 6);
    }

    public function component()
    {
        return array(
            'type' => 'payment_summary',
            'editor_title' => 'Payment Summary',
            'group' => 'payment',
            'is_pro' => 'no',
            'postion_group' => 'payment',
            'conditional_hide' => true,
            'editor_elements' => array(
                'label' => array(
                    'label' => 'Field Label',
                    'type' => 'text',
                    'group' => 'general'
                ),
                'default_value' => array(
                    'label' => 'Default Error Message',
                    'type' => 'textarea',
                    'group' => 'general',
                    'value' => 'You Have Not Any Payment Item'
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
                'label' => __('Payment Summary', 'wp-payment-form'),
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
        $hidden_attr = Arr::get($element, 'field_options.conditional_logic_option.conditional_logic')  === 'yes' ? 'none' : 'block';
        $disable = Arr::get($fieldOptions, 'disable', false);
        if (!$fieldOptions || $disable) {
            return;
        }
        $controlClass = $this->elementControlClass($element);
        $formID = $this->getFormInstace($form->ID);
        $inputId = 'wpf_input_' . $formID . '_' . $this->elementName;

        $defaultValue = apply_filters('wppayform/input_default_value', Arr::get($fieldOptions, 'default_value'), $element, $form);

        ?>
        <div  style = "display: <?php echo esc_attr($hidden_attr); ?>" data-element_type="<?php echo esc_attr($this->elementName); ?>"
             class="<?php echo esc_attr($controlClass); ?>">
        <?php $this->buildLabel($fieldOptions, $form, array('for' => $inputId)); ?>
        <input id="error_message" type="hidden" value="<?php echo esc_attr($defaultValue); ?>" />
        <div id="wpf_table_div" condition_id="<?php echo esc_attr(Arr::get($element, 'id')); ?>">
        </div>
        </div>
        <?php
    }
}
